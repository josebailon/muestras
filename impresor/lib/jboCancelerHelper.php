<?php

defined("NODIRECT") or die;
/*
 * Ayudante para la gestión de las cancelaciones
 */

/**
 * Description of jboCancelerHelper
 *
 * @author jose
 */
class jboCancelerHelper {

    private $docM;
    private $jobM;
    private $cancelableStatuses = array(0, 1, 3, 5,6,7,8,50,100,300); //status en los que se puede ejecutar directamente la cancelacion
    //0 en cliente
    //1 aceptado
    //2 preparando(por eso no esta)
    //3 preparado
    //4 mandando a imprimir
    //5 comando imprimir terminado
    //6 en cola de cups
    //7 - enviando a la impresora por cups #fff700
    //8 - terminado el envio a la impresora
    //50 - retenido
    //100 - enviado completamente a la impresora #a7a7a7
    //200 - mal imprimido por la impresora
    //300 - cancelado en la impresora #f00
    private $cupsC;
    
    public function __construct() {
        $this->docM = new jboDocumentModel(true);
        $this->jobM = new jboJobModel(true);
        $this->cupsC = new jboCupsController();
    }

    public function  initDocCancelation($docId) {
        echo "doc inicancelation".PHP_EOL;
        $d = $this->docM->getById($docId);

        if ($this->docIsTotalyCancelable($d->tempName)) {
            echo "doc Es totalmente cancelable".PHP_EOL;
            $this->docTotalyCancel($d->tempName);
        } else {
            echo "doc no es totalmente cancelable".PHP_EOL;
            $this->docAskForCancel($d->tempName);
        }
        $this->docContinueCancelation($docId);
    }

    public function docAskForCancel($docId) {
        echo "pedir cancelacion de documento".PHP_EOL;
        $this->docM->setCanceledLevel($docId, 2);
    }

    public function docTotalyCancel($docId) {
        $this->docM->setCanceledLevel($docId, 3);
        $this->docM->setStatus($docId, 300);
        $doc = $this->docM->getById($docId);
        $this->cupsC->cancelJob($doc);//cancelar el trabajo de impresion en cups
    }

    public function docIsTotalyCancelable($docId) {
        $d = $this->docM->getById($docId);
        return in_array(intval($d->status), $this->cancelableStatuses);
    }
    
    //continuar con el prograso de la cancelación. Si se pone $force se ignorará si es cancelable o no y simplemente se subirá el estado de cancelación excepto si no está iniciada la cancelacion
    public function docContinueCancelation($docId, $force=false) {
        echo "continuar cancelacion de documento".PHP_EOL;
        $d = $this->docM->getById($docId);
        if ($d->canceled == 0) {
            return false;
        } else {
            if ($this->docIsTotalyCancelable($docId)||$force) {
                $this->docTotalyCancel($docId);
            }
        }
        if ($d->status > 4 ){
            $cc=new jboCupsController();
            $cc->cancelJob($d);
        }
        return true;
    }

    public function initJobCancelation($idJob) {
        $docs = $this->docM->getAllByJob($idJob);
        foreach ($docs as $d) {
            $this->initDocCancelation($d->tempName);
        }

        if ($this->jobIsTotalyCancelable($idJob)) {
            $this->jobTotalyCancel($idJob);
        } else {
            $this->jobAskForCancel($idJob);
        }
        $this->jobContinueCancelation($idJob);
    }

    public function jobContinueCancelation($idJob) {
        $j = $this->jobM->getById($idJob);

        if ($j->canceled == 0) {
            return false;
        }

        if ($this->jobIsTotalyCancelable($idJob)) {
            $this->jobTotalyCancel($idJob);
        }
        return true;
    }

    public function jobAskForCancel($idJob) {
        $this->jobM->setCanceledLevel($idJob, 2);
    }

    public function jobTotalyCancel($idJob) {
        $this->jobM->setCanceledLevel($idJob, 3);
        $this->jobM->deleteJob($idJob);
    }

    public function jobIsTotalyCancelable($idJob) {
        $docs = $this->docM->getAllByJob($idJob);
        $out = true;
        foreach ($docs as $d) {
            if ($d->canceled < 3) {
                $out = false;
            }
        }
        return $out;
    }

    public function jobIsTotalyCanceled($idJob) {
        $j = $this->jobM->getById($idJob);
        return ($j->canceled == 3);
    }


}
