<?php

defined("NODIRECT") or die;

class jboManagerController extends jboController {

    public function __construct() {
        parent::__construct();

        $this->initSessionVariables();
        if ($_SESSION['manager'] == null) {
            die("Manager no autorizado");
        }
        $this->switchSection();
    }

    private function initSessionVariables() {
        if (!isset($_SESSION['manager']) || $_SESSION['manager'] == false) {
            $managerM = new jboManagerModel();
            $_SESSION['manager'] = $managerM->autoIdentifyManager();
        } else { //manager sí se ha registrado
        }
    }

    private function switchSection() {

        switch ($this->section) {
            //refresco de sesion
            case 'refreshSession':
                echo "Refresh OK";
                die;
                break;
            case 'pausePrinter':
                $printer = jboTools::getPostVar("printer");
                $pm = new jboPrinterModel();
                $cc = new jboCupsController();
                $p = $pm->getByCode($printer);
                if ($p) {
                    $cc->pausePrinter($p->code);
                }
                break;
            case 'conectPrinter':
                $printer = jboTools::getPostVar("printer");
                $pm = new jboPrinterModel();
                $cc = new jboCupsController();
                $p = $pm->getByCode($printer);
                if ($p) {
                    $cc->conectPrinter($p->code);
                }
                break;
            case 'getInitialData':
                $out = array();
                $pm = new jboPrinterModel();
                $cc = new jboCupsController();
                $printers = $pm->getAll();
                foreach ($printers as $p) {
                    $p->status = $cc->getPrinterStatus($p->code);
                    $out['printers'][]= $p;
                }
                
                $out['capabilities']=$pm->getAllCapabilities();
                $pqm = new jboJobModel();
                $pq = $pqm->getPrintQueue();
                $out['printQueue'] = array();
                if ($pq) {
                    $out['printQueue'] = $pq;
                }
                $bindingM= new jboBindingModel();
                $out['bindings'] = $bindingM->getAll();
                
                
                //paper formats
                $pfm=new jboPaperFormatsModel();
                $out['paperFormats']=$pfm->getAll();
                $out['mosaicData']= jboMosaicTools::getAllMosaicsForAllFormats();
                echo json_encode($out);
                die;
                break;
            case 'getPrinterStatus':
                $pm = new jboPrinterModel();
                $cc = new jboCupsController();
                $printers = $pm->getAll();
                $out = array();
                foreach ($printers as $p) {
                    $pt = array();
                    $pt['code'] = $p->code;
                    $pt['status'] = $cc->getPrinterStatus($p->code);
                    $out[] = $pt;
                }
                echo json_encode($out);
                die;
                break;
            //ordena el bloqueo de los clientes
            case 'blockclients':
                $cliBlk = new jboClientBlocker();
                $cliBlk->blockClients();
                break;
            //devuelve si los clientes están libres
            case 'clientsiddle':
                $cliBlk = new jboClientBlocker();
                $out = [];
                $out['alliddle'] = $cliBlk->clientsIddle();
                echo json_encode($out);
                die;
            case 'unblockclients':
                $cliBlk = new jboClientBlocker();
                $cliBlk->unblockClients();
                break;

            //ordena el apagado del servidor    
            case 'servershutdown':
                echo "vamos a apagar";
                chdir(jboConf::$shutDownServerWorkingDirectory);
                exec(jboConf::$shutDownServerPath);
                echo "hemos apagado";
                break;
            //ordena el reinicio del servidor
            case 'serverrestart';
                echo "vamos a reiniciar";
                chdir(jboConf::$restartServerWorkingDirectory);
                exec(jboConf::$restartServerPath);
                echo "hemos reiniciado";
                break;
            //devuelve la lista de trabajos aceptados por los clientes
            case 'getJobs':
                $jm = new jboJobModel();
                $dm = new jboDocumentModel();
                $out = [];
                $out['jobs']=$jm->getAllJobsAccepted(false);
                
                $out['errormessages']=$dm->getAllErrorMessages();
                
                echo json_encode($out);
                die;
                break;
            //devuelve los docuentos de un trabajo
            case 'getJobDocuments':
                $idJob = jboTools::getPostVar('idJob');
                $jm = new jboDocumentModel();
                $out = array();
                $out['error'] = "";
                $documents = $jm->getAllByJob($idJob);
                if ($documents) {
                    $out['documents'] = $documents;
                } else {
                    $out['error'] = "Error recogiendo documentos de trabajo " . $idJob;
                }
                    $out['errormessages']=$jm->getAllErrorMessages();
                echo json_encode($out);
                die;
                break;
            //hace update en los documentos
            case'updateDocuments':
                $this->updateDocuments();
                break;
            //da acceso a una vista previa
            case 'getpreview':
                $idJobRequest = jboTools::getGetVar("idJob");
                $previewname = jboTools::getGetVar('previewname');
                $this->getPdf($idJobRequest, $previewname);
                break;
            //devuelve las opciones actuales
            case 'getcurrentoptions':
                $this->getCurrentOptions();
                break;
            //guarda las opciones
            case 'saveoptions':
                $out = array();
                $out['error'] = "";
                $pricesM = new jboPaperPricesModel();
                $bindingM = new jboBindingModel();
                $options = json_decode(jboTools::getPostVar('options'));
                if (!$pricesM->savePrices($options->prices)) {
                    $out['error'] .= "Error guardando precios.";
                }
                    
                if (!$bindingM->saveBindings($options->bindings)) {
                    $out['error'] .= "Error guardando acabados";
                }
                echo json_encode($out);
                die;
                break;
            case 'sendJobToPrintQueue':
                $out = array();
                $out['error'] = "Error enviando a cola de impresion";
                $idjob = jboTools::getPostVar('idJob');
                $jobm = new jboJobModel();
                if ($jobm->addToPrintQueue($idjob)) {
                    $out['error'] = "";
                    jboTools::launchPrepare();
                    jboTools::launchPrinterQueueManager();
                }
                echo json_encode($out);
                die;
                break;
            case 'cancelJob':
                $idJob = jboTools::getPostVar('idJob');
                $this->cancelJob($idJob);
                break;
            default://pantalla inicial 
                $this->setTemplate("managerTemplate");
                
                 $dm=new jboDefaultsModel();
                $shopName=$dm->getByCode("shopName");
                $this->template->data["shopName"]=$shopName;
                $this->template->pageTitle="Impresor Manager ".$shopName;
                
                if (jboTools::isAutoPrint()) {
                    $this->template->setView('managerAuto');
                } else {
                    $this->template->setView('managerManual');
                }
                $this->template->data['manager'] = $_SESSION['manager'];
                $this->template->render();
                break;
        }
    }

//fin switchSection

    //get current options
   
    private function getCurrentOptions(){
        $pricesM = new jboPaperPricesModel();
        $bindingM= new jboBindingModel();
        $out = array();
        $out['prices'] = $pricesM->getAll();
        $out['bindings'] = $bindingM->getAll();
         echo json_encode($out);
    }
    
    //update documents
    
    private function updateDocuments(){
        $docs= json_decode(jboTools::getPostVar('docs'));
        $dm=new jboDocumentModel();
        $jm=new jboJobModel();
        $pm=new jboPrinterModel();
        $out=[];
        $out["error"]="";
        foreach ($docs as $doc){
            $job=$jm->getById($doc->idJob);//recogemos el trabajo
            //si el trabajo tiene estatus menor de 4 y no está en la cola de impresion actualizadomo el documento
            if ($job->status<4&&!$jm->isInPrintQueue($doc->idJob)){
                //cotejamos la impresora
                $doc->printer=$pm->detectPrinter($doc);
                
                $r=$dm->updateDocument($doc,false);//false para que no updatee el status
                if ($r!==true){
                    $out["error"]="error:".$r;
                }
                //Marcamos el trabajo como no preparado       
                $jm->setStatus($doc->idJob, 1);
                
                //refrescamos documento desde base de datos
                $newDoc=$dm->getById($doc->tempName);
                //marcamos el documento como no preparado(1) si ya estaba preparado(3) o lo dejamos tal cual si se esta
                //si se esta preparando ya que el mismo preparador lo volvera a no preparado(1) si es necesario.
                if ($newDoc->status==3){
                    $dm->setStatus($doc->tempName, 1);
                }
            }
        }
        
        
        
        //relanzamos la preparacion de documentos por si hay que recalcular alguna preparacion
        jboTools::launchPrepare();
        //devolvemos la respuesta al tendero
        echo json_encode($out);
    }
    
    // cancel job
    private function cancelJob($idJob) {
        $ch = new jboCancelerHelper();
        $docsM = new jboDocumentModel();
        $docs = $docsM->getAllByJob($idJob);
        foreach ($docs as $d) {
            $ch->initDocCancelation($d->tempName);
        }
        $ch->initJobCancelation($idJob);
    }

    private function getPrinterStatus($printerCode) {
        $cc = new jboCupsController();
        $status = $cc->getPrinterStatus($printerCode);
    }

//GET PDF
    private function getPdf($idJob, $previewName) {
        //todo sanitize $name

        $file = jboConf::$jobsPath . $idJob . D_S . $previewName;

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }

}
