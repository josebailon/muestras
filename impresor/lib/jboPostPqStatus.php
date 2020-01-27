<?php

defined("NODIRECT") or die;

/**
 * Description of jboPostPqStatus
 *
 * @author Jose
 */
class jboPostPqStatus {

    private $jobs;
    private $docs;
    private $jobM;
    private $docM;
    private $cc;

    function __construct() {
        echo "construct".PHP_EOL;

        if ($this->running()) {
            print "Ya está activa la post pq ";
            die;
        }

        $this->lockRunning();
        $this->docM = new jboDocumentModel(true);
        $this->jobM = new jboJobModel(true);
        $this->cc = new jboCupsController();
        $this->init();
    }

    private function init() {
        $needToWork = true;
        while ($needToWork) {
            echo "Need to Work".PHP_EOL;
            $this->jobs = $this->jobM->getAcceptedJobsWithUnfinishedStatus();
            $jobNotFinishedFound = false;
            //recorremos los trabajos
            foreach ($this->jobs as $j) {
                $jobNotFinishedFound = true;
                echo PHP_EOL.PHP_EOL."Trabajo ".$j->id.PHP_EOL;    
                //cogemos los documentos del trabajo actual
                $this->docs = $this->docM->getAllByJob($j->id);
                $smallerStatus = 300;
                $greaterStatus = 0;
                //recorremos los documentos
                foreach ($this->docs as $d) {
                    echo "Analizar documento ".$d->tempName." con id de cups ".$d->idPrint.PHP_EOL;
                    if ($d->status >= 100) {
                        //documento ya en estado definitivo
                        echo "Status ya definitivo ".$d->status.PHP_EOL;
                        continue;
                    } else if($d->status>4){
                        echo "Status mayor de 4 por tanto miramos si está en la cola de impresion".PHP_EOL;
                    //si ya se ha pasado el estado de ejecucion de comando de impresion
                    //y por el if de antes no esta aun en un estado definitivo
                    //recogemos su estaus en la cola de impresion
                        $newStatus = $this->cc->getJobStatus($d);
                        //si hay un status reconocido lo analizadmo
                        if ($newStatus!==false) {
                            echo "Hay nuevo status y es ".$newStatus.PHP_EOL;
                            //si no es igual que el que tenia lo guardamos
                            if ($d->status!=$newStatus){
                                echo "Como no es igual al que tenia lo guardamos".PHP_EOL;
                                $this->docM->setStatus($d->tempName, $newStatus);
                                //jboTools::logAction("PostPqStatus pone el documento".$d->tempName." a ".$newStatus." ya que ha cambiado" );
                                $d->status=$newStatus;
                            }else{
                                //jboTools::logAction("PostPqStatus no cambia el status de".$d->tempName." que es ".$newStatus."porque no ha cambido" );
                            }
                        }//si no hay un status reconocido por el cups controller vemos si es que ya se ha imprimido
                        else{
                            echo "El status de la cola no es reconocido. Lo marcamos como 8(ya enviado a impresora)";
                            //como ya sabemos que su status tiene que ser por lo menos 5(comando de imprimir ejecutado)
                            //deberia aparecer en la cola de impresion de no completados. Como no esta lo identificados como imprimido         
                            if ($d->status<100){//si es menor de 8 lo marcamos como 8(ya enviado a impresora)
                                $this->docM->setStatus($d->tempName, 100);
                                //jboTools::logAction("PostPqStatus pone el documento".$d->tempName." a 100 y el estatus no es conocido lo ponemos a 100" );
                                $d->status=100;
                            }else{
                                //jboTools::logAction("PostPqStatus no cambia el status de".$d->tempName." porque el estado detectado en cups es false y el status actual".$d->status." no es menor de 100" );
                            }
                            //TODO : ANALIZAR CASOS TRAS ENVIAR A LA IMPRESORA

                        }
                    }
                    
                    if ($d->status < $smallerStatus) {
                        $smallerStatus = $d->status;
                }else{echo $d->status." no es menor que ".$smallerStatus;}
                    if ($d->status > $greaterStatus) {
                        echo "Doc ".$d->tempName."tine estatus ".$d->status;
                        $greaterStatus = $d->status;
                    }else{
                     echo $d->status. " no es mayor que ".$greaterStatus;   
                    }
                }//fin recorrer documentos
                $finalJobStatus = $smallerStatus;
                //si hay algún trabajo con cancelacion lo marcamos como cancelado
                if ($greaterStatus == 300) {
                    echo "hay trabajos con cancelación. el status final del trabajo es 300".PHP_EOL;
                    $finalJobStatus = 300;
                }
                $this->jobM->setStatus($j->id, $finalJobStatus);
                echo " status final para el trabajo: ".$finalJobStatus.PHP_EOL;
                sleep(4);
            }
            if (!$jobNotFinishedFound) {
                echo "No hemos encontrado trabajos que analizar";
                $needToWork = false;
            } 
        }
        
       $this->unlockRunning();
    }
    
    
     //devuelve true si ya hay un analizador funcionando
    private function running() {
        return file_exists('postpqstatus.lock');
    }

    //se apropia de la cola de analisis bloqueando que otros procesos puedan analizar
    private function lockRunning() {
        if ($this->running()) {
            return;
        }

        $f = fopen('postpqstatus.lock', "w") or die("Unable to open file!");
        fclose($f);
    }

    //desbloquea la cola de analisis permitiendo que otros procesos puedan analizar
    private function unlockRunning() {
        if (!$this->running()) {
            return;
        }
        unlink('postpqstatus.lock');
        echo "desbloqueado";
    }

}
