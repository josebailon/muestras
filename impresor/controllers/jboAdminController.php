<?php

defined("NODIRECT") or die;

class jboAdminController extends jboController {

    public function __construct() {
        parent::__construct();
        $loged = false;
        if (isset($_SESSION['admin'])) {
            if ($_SESSION['admin'] != null && $_SESSION['admin'] != "") {
                $loged = true;
            }
        }
        if (!$loged) {
            //intentamos loguear
            if (!$this->tryLogin()) {
                //si no se ha logueado mostramos el login
                $this->showLogin();
                die;
            }
        }
        $this->switchSection();
    }

    private function tryLogin() {
        $pw = jboTools::getPostVar("password");
        if ($pw == jboConf::$adminPassword) {
            $_SESSION['admin'] = new stdClass();
            $_SESSION['admin']->startDate = time();
            return true;
        }
        return false;
    }

    private function switchSection() {
        switch ($this->section) {
            //refresco de sesion
            case 'user':
                $this->sectionUser();
                die;
                break;
            case 'printers':
                $this->sectionPrinters();
                die;
                break;
            case 'clients':
                $this->sectionClients();
                die;
                break;
            case 'defaults':
                $this->sectionDefaults();
                die;
                break;
            default://pantalla inicial
                $this->sectionInfo();
                die;
                break;
        }
    }

//fin switchSection
    //mostrar login
    private function showLogin() {
        $this->setTemplate("adminTemplate");
        $this->template->render();
    }
    
    
    
        /*SECCION DE INFO######################################################################################*/
        private function sectionInfo() {
        switch ($this->func) {
            //editar un cliente o añadirlo si no hay id especificada
            case 'unblockClients':
                $cbm=new jboClientBlocker();
                $cbm->unblockClients();
                jboTools::redirect("admin.php");
                die;
                break;
            case 'deactiveclient':
                $id = jboTools::getGetVar("id");
                $cbm=new jboClientBlocker();
                $cbm->unmarkClientAsWorking($id);
                jboTools::redirect("admin.php");
                die;
                break;
            case 'launchPreparers':
                jboTools::launchPrepare();
                //jboTools::redirect("admin.php");
                die;
                break;               
            case 'unblockPreparer';
                $printercode = jboTools::getGetVar("printercode");
                unlink('preparer' . $printercode . '.lock');
                jboTools::redirect("admin.php");
                die;
                break;
            case 'deletePrintQueueJob':
                $jobid= jboTools::getGetVar("jobid");
                $jm=new jboJobModel();
                $jm->removeFromPrintQueue($jobid);
                jboTools::redirect("admin.php");
                die;
                break;
            case 'emptyPrintQueue':
                $jm=new jboJobModel();
                $jm->emptyPrintQueue();
                jboTools::redirect("admin.php");
                die;
                break;
            case 'changeJobStatus':
                $jobid= jboTools::getGetVar("jobid");
                $status= jboTools::getGetVar("status");
                $jb=new jboJobModel();
                $jb->setStatus($jobid, $status);
                $dm=new jboDocumentModel();
                $docs = $dm->getAllByJob($jobid);
                foreach($docs as $doc){
                    $dm->setStatus($doc->tempName, $status);
                }
                jboTools::redirect("admin.php");
                die;
                break;
            case 'deleteJobTempFiles':
                $this->deleteJobTempFiles();
                jboTools::redirect("admin.php");
                die;
                break;           
            case 'deleteJob':
                $this->deleteJob();
                jboTools::redirect("admin.php");
                die;
                break;    
            case 'deleteAllJobs':
                $jm = new jboJobModel();
                $jobs = $jm->getAll();
                foreach ($jobs as $job){
                    $jm->deleteJob($job->id);
                }
                jboTools::redirect("admin.php");
                die;
                break;  
            case 'downloadfile':
                $this->downloadfile();
                jboTools::redirect("admin.php");
                die;
                break;  
            
            case 'emptyJobsFolder':
                jboTools::emptyDir(jboConf::$jobsPath);
                jboTools::redirect("admin.php");
                die;
                break;  
            case 'savecfgfile':
                $this->saveCfgFile();                
                jboTools::redirect("admin.php");
                die;
                break;  
            default://pantalla inicial de edicion
                $this->showInfo();
                break;
        }
    }
    
    /**
     * muestra la informacion general de estado
     */
    private function showInfo(){
        $this->setTemplate("adminTemplate");
        $this->template->setView('info');
        $this->template->data['menuactive']="info";
        $cbm=new jboClientBlocker();
        $this->template->data['clientsWorking']=$cbm->listClientsWorking();
        $this->template->data['editingConfiguration']=$cbm->clientsBlocked();
        
        //preparers
        $pm=new jboPrinterModel();
        $printers = $pm->getAll();
        $preparersWorking=[];
        foreach ($printers as $printer){
            if (file_exists('preparer' . $printer->code . '.lock')){
                $preparersWorking[]=$printer;
            }
            
        }
        $this->template->data['preparersWorking']=$preparersWorking;
        
        //Cola de impresion
        $jm=new jboJobModel();
        $this->template->data['printQueue']=$jm->getPrintQueue();
        
        //trabajos
        $jobs=$jm->getAll();
        $dm=new jboDocumentModel();
        foreach($jobs as $kj=>$job){
            $jobs[$kj]->docs=$dm->getAllByJob($job->id);
            //archivos del trabajo
            $jobs[$kj]->files=[];
              if (file_exists(jboConf::$jobsPath.$job->id)){
                 $files = scandir(jboConf::$jobsPath.$job->id); 
              }else{
                  $files =[];
              }

            foreach($files as $file)
                {
                    if(is_file(jboConf::$jobsPath.$job->id.D_S.$file)){
                        $jobs[$kj]->files[]=$file;
                    }
                }
            
        }
        $this->template->data['jobs']=$jobs;
        
        //contenido carpeta trabajos $jobFolderContent
            $this->template->data['jobFolderContent']= scandir(jboConf::$jobsPath);
        //contenido del archivo de configuracion
            $this->template->data['configFile']=file_get_contents("jboConf.php");
            
            
        $this->template->render();
        }
        
        private function saveCfgFile(){
            $newCfgContent = jboTools::getPostVar('filecontent');
           
            //backup
            copy ("jboConf.php","jboConf.php".date("DMdYGi").".bak");
            //
            $file = 'jboConf.php';
            file_put_contents($file,$newCfgContent);
            
        }

        private function downloadfile(){
        //todo sanitize $name
        $jobid= jboTools::getGetVar("jobid");
        $filename= jboTools::getGetVar('file');
        $file = jboConf::$jobsPath . $jobid . D_S . $filename;

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

    /**
     * Borra los archivos temporales de los documentos de un trabajo
     */
    private function deleteJobTempFiles(){
        $jobid= jboTools::getGetVar("jobid");
        
        if ($jobid){
            $dm=new jboDocumentModel();
            $docs = $dm->getAllByJob($jobid);
            $docsToMantain=[];
            foreach ($docs as $doc){
                $docsToMantain[]=$doc->tempNameWithExtension;
                $docsToMantain[]=$doc->tempName.".pdf";//para imagenes
            }
            
            //archivos del trabajo
            $files=[];
            $files = scandir(jboConf::$jobsPath.$jobid); 
            foreach($files as $file)
                {
                    if(is_file(jboConf::$jobsPath.$jobid.D_S.$file)){
                        if (!in_array($file, $docsToMantain)){
                            unlink(jboConf::$jobsPath.$jobid.D_S.$file);
                        }
                    }
                }
        }
    }
    
    /**
     * Borra un trabajo
     */
    private function deleteJob (){
        $jobid= jboTools::getGetVar("jobid");
        $jm = new jboJobModel();
        if ($jobid){
            $jm->deleteJob($jobid);
        }
    }
    
    
    /*SECCION DE CLIENTES######################################################################################*/
        private function sectionClients() {
        switch ($this->func) {
            //editar un cliente o añadirlo si no hay id especificada
            case 'editclient':
                $this->editClient();
                break;
            case 'saveclient'; //guardar un cliente
                $this->saveClient();
                break;
            case 'deleteclient'://borrar un cliente
                $this->deleteClient();
                break;
            default://pantalla inicial de edicion
                $this->clientsIni();
                break;
        }
    }

        private function clientsIni() {
        $uM = new jboUserModel();
        $this->setTemplate("adminTemplate");
        $this->template->setView('clientsIni');
        $this->template->data['clients'] = $uM->getAll();
        $this->template->data['menuactive']="clients";
        $this->template->render();
    }
    
    
    private function deleteClient(){
        $id= jboTools::getGetVar('id');
        if($id){
            $uM= new jboUserModel();
            $uM->deleteUser($id);
            $ffM=new jboFileFormatsModel();
            $ffM->deleteAllByClientId($id);
        }
        $this->clientsIni();
    }
    private function editClient() {
        $id = jboTools::getGetVar('id');

        $this->setTemplate("adminTemplate");
        $this->template->setView('editClient');
        $ffM = new jboFileFormatsModel();
        if ($id) {
            $uM = new jboUserModel();
            $this->template->data['client'] = $uM->getById($id);
            $this->template->data['clientformats'] = $ffM->getAllByClientId($id);
        } else {
            $this->template->data['client'] = new jboUser();
            $this->template->data['clientformats']=[];
        }

        $this->template->data['formats'] = $ffM->getFormats();
        $this->template->data['menuactive']="clients";
        $this->template->render();
    }

    private function saveClient() {
        $clientId = jboTools::getPostVar('id');
        if (!$clientId) {
            echo "Error, no hay id de cliente";
            $this->editIni();
            return;
        }
        $client = new jboUser();
        $client->id = $clientId;
        $client->name = jboTools::getPostVar('name', '');
        $client->selectPrinter = (jboTools::getPostVar('selectPrinter', 0) ? true : false);
        $client->notes = jboTools::getPostVar('notes', '');
        $client->localPath = jboTools::getPostVar('localPath', '');
        $client->client = (jboTools::getPostVar('client', 0) ? true : false);
        $client->manager = (jboTools::getPostVar('manager', 0) ? true : false);
        $client->hardwareId = jboTools::getPostVar('hardwareId', '');
        $client->transformTimeOutPerFile = jboTools::getPostVar('transformTimeOutPerFile', 0);
        $client->autoAcceptPrintConditions = (jboTools::getPostVar('autoAcceptPrintConditions', 0) ? true : false);
        $client->whatsapp = (jboTools::getPostVar('whatsapp', 0) ? true : false);
        $client->telegram = (jboTools::getPostVar('telegram', 0) ? true : false);
        $client->telegram = (jboTools::getPostVar('telegram', 0) ? true : false);
        $client->addFileDefaultPath= jboTools::getPostVar('addFileDefaultPath');
        
        $uM = new jboUserModel();
        $id = $uM->saveUser($client);
        if ($id) {
            $ffM = new jboFileFormatsModel();
            $formats = $ffM->getFormats();
            $clientFormats = [];
            foreach ($formats as $f) {
                $cliFo = new stdClass();
                $cliFo->formatId = $f->id;
                $cliFo->clientId = $id;
                $cliFo->transform = jboTools::getPostVar('ftransform' . $f->id, 0);
                $cliFo->analysis = jboTools::getPostVar('fanalysis' . $f->id, 0);
                $cliFo->allowed = (jboTools::getPostVar('fallowed' . $f->id, 0) ? true : false);
                $clientFormats[] = $cliFo;
            }

            $ffM->saveFormatsForClient($clientFormats, $client->id);
        }
        $this->clientsIni();
    }
    
    
    
        /*SECCION DE EDIT######################################################################################*/
    private function sectionDefaults() {
        switch ($this->func) {
            //editar las impresoras por defecto para un formato
            case 'editdefaultprinter':
                $this->editDefaultPrinter();
                break;
            case 'savedefaultprinter':
                $this->saveDefaultPrinter();
                break;
            //editar un valor por defecto o anadirlo si no hay codigo especificado
            case 'editdefault':
                $this->editDefault();
                break;
            case 'savedefault'; //guardar un default
                $this->saveDefault();
                break;
            case 'deletedefault'://borrar un default
                $this->deleteDefault();
                break;
            default://pantalla inicial de edicion
                $this->defaultsIni();
                break;
        }
    }

    private function defaultsIni() {
        $dM = new jboDefaultsModel();
        $this->setTemplate("adminTemplate");
        $this->template->setView('defaultsIni');
        $this->template->data['defaults'] = $dM->getAll();
        $pfM=new jboPaperFormatsModel();
        $pM=new jboPrinterModel();
        $this->template->data['formats']=$pfM->getAll();
        $this->template->data['menuactive']="defaults";
        $this->template->render();
    }
     private function deleteDefault(){
        $code= jboTools::getGetVar('code');
        if($code){
            $dM= new jboDefaultsModel();
            $dM->deleteDefault($code);
            }
        $this->defaultsIni();
    }
    private function editDefault() {
        $code = jboTools::getGetVar('code');

        $this->setTemplate("adminTemplate");
        $this->template->setView('editDefault');
        if ($code) {
            $dM = new jboDefaultsModel();
            $this->template->data['default'] = $dM->getFullByCode($code);
        } else {
            $this->template->data['default'] = new jboDefault();
        }
        $this->template->data['menuactive']="defaults";
        $this->template->render();
    }

    private function saveDefault() {
        $default = new jboDefault();
        $default->code = jboTools::getPostVar('code');
        $default->value = jboTools::getPostVar('value');
        if (!$default->code) {
            echo "Error, no hay nombre del valor por defecto";
            $this->defaultsIni();
            return;
        }
       
        $dM = new jboDefaultsModel();
        $r = $dM->saveDefault($default);
        $this->defaultsIni();
    }
    private function editDefaultPrinter(){
        $code = jboTools::getGetVar('code');
        $this->setTemplate("adminTemplate");
        $this->template->setView('editDefaultPrinter');
        if ($code) {
            $pfM = new jboPaperFormatsModel();
            $this->template->data['format'] = $pfM->getBycode($code);
            $pM=new jboPrinterModel();
            $this->template->data['printers']=$pM->getAll();
            $this->template->data['capabilities']=$pM->getAllCapabilities();
        } else {
            $this->defaultsIni();
            return;
        }
        $this->template->data['menuactive']="defaults";
        $this->template->render();
    }
    private function saveDefaultPrinter() {
        $formatCode=jboTools::getPostVar('formatCode');
        $color1=jboTools::getPostVar('color1');
        $color2=jboTools::getPostVar('color2');
        $bn1=jboTools::getPostVar('bn1');
        $bn2=jboTools::getPostVar('bn2');
        
        if (!$formatCode) {
            echo "Error, no hay codigo del formato";
            $this->defaultsIni();
            return;
        }
       
        $pfM = new jboPaperFormatsModel();
        $r = $pfM->saveDefaultPrinterForPaperFormat($formatCode, $color1, $color2, $bn1, $bn2);
        $this->defaultsIni();
    }
    
    /*SECCION DE IMPRESORAS######################################################################################*/
    private function sectionPrinters() {
        switch ($this->func) {
          //editar un valor por defecto o anadirlo si no hay codigo especificado
            case 'editprinter':
                $this->editPrinter();
                break;
            case 'saveprinter'; //guardar un default
                $this->savePrinter();
                break;
            case 'deleteprinter'://borrar un default
                $this->deletePrinter();
                break;
            case 'editpaperformat':
                $this->editPaperformat();
                break;
            case 'savepaperformat':
                $this->savePaperFormat();
                break;
            case 'deletepaperformat':
                $this->deletePaperformat();
                break;
            default://pantalla inicial de edicion
                $this->printersIni();
                break;
        }
    }

    private function printersIni() {
        $pM = new jboPrinterModel();
        $pfM = new jboPaperFormatsModel();
        $this->setTemplate("adminTemplate");
        $this->template->setView('printersIni');
        $this->template->data['printers'] = $pM->getAll();
        $this->template->data['paperFormats'] = $pfM->getAll();
        $this->template->data['menuactive']="printers";
        $this->template->render();
    }
    

   
    private function editPrinter() {
        $code = jboTools::getGetVar('code');

        $this->setTemplate("adminTemplate");
        $this->template->setView('editPrinter');
        $pM = new jboPrinterModel();
        if ($code) {
            
            $this->template->data['printer'] = $pM->getByCode($code);
        } else {
            $this->template->data['printer'] = new jboPrinter();
        }
        
        //capabilities
        $pfm=new jboPaperFormatsModel();
        $pf=$pfm->getAll();
        $this->template->data['capabilities']=[];
        foreach ($pf as $f){
            $t1c=new jboPaperFormat($f->code, $f->name);
            $t1c->doubleSided=false;
            $t1c->color=true;
            $t1c->capable=$pM->isCapable($code, $f->code, true, false);
            $t1c->printcode= jboTools::createPrintingCode($f->code, false, true);
            $this->template->data['capabilities'][]=$t1c;
            $t2c=new jboPaperFormat($f->code, $f->name);
            $t2c->doubleSided=true;
            $t2c->color=true;
            $t2c->capable=$pM->isCapable($code, $f->code, true, true);
            $t2c->printcode= jboTools::createPrintingCode($f->code, true, true);
            $this->template->data['capabilities'][]=$t2c;
            $t1bn=new jboPaperFormat($f->code, $f->name);
            $t1bn->doubleSided=false;
            $t1bn->color=false;
            $t1bn->capable=$pM->isCapable($code, $f->code, false, false);
            $t1bn->printcode= jboTools::createPrintingCode($f->code, false, false);
            $this->template->data['capabilities'][]=$t1bn;
            $t2bn=new jboPaperFormat($f->code, $f->name);
            $t2bn->doubleSided=true;
            $t2bn->color=false;
            $t2bn->capable=$pM->isCapable($code, $f->code, false, true);
            $t2bn->printcode= jboTools::createPrintingCode($f->code, true, false);
            $this->template->data['capabilities'][]=$t2bn;
                         
        }

        $this->template->data['menuactive']="printers";
        $this->template->render();
    }

    private function savePrinter() {
            
        $printer = new jboPrinter();
        $printer->code = jboTools::getPostVar('code');
        if (!$printer->code) {
            echo "Error, no hay nombre del valor por defecto";
            $this->printersIni();
            return;
        }
        $printer->name = jboTools::getPostVar('name');
        $printer->comcopies = jboTools::getPostVar('comcopies');
        $printer->combn = jboTools::getPostVar('combn');
        $printer->comcolor = jboTools::getPostVar('comcolor');
        $printer->com2sidesbindingnormal = jboTools::getPostVar('com2sidesbindingnormal');
        $printer->com2sidesbindingsmall = jboTools::getPostVar('com2sidesbindingsmall');
        $printer->pslevel = jboTools::getPostVar('pslevel');
        $printer->printmode = jboTools::getPostVar('printmode');
        $printer->fittopage = (jboTools::getPostVar('fittopage', 0) ? true : false);
        $printer->comsummaryjobprint = jboTools::getPostVar('comsummaryjobprint');
        
        //capabilities
        $pM = new jboPrinterModel();
        $pfm=new jboPaperFormatsModel();
        $pf=$pfm->getAll();
        $printer->capabilities=[];
        foreach ($pf as $f) {
            $t1c = new jboPrinterCapabilitie($printer->code, $f->code);
            $t1c->doubleSided = false;
            $t1c->color = true;
            $t1c->capable = (jboTools::getPostVar(jboTools::createPrintingCode($f->code, false, true), 0) ? true : false);
            $printer->capabilities[] = $t1c;
            $t2c = new jboPrinterCapabilitie($printer->code, $f->code);
            $t2c->doubleSided = true;
            $t2c->color = true;
            $t2c->capable =  (jboTools::getPostVar(jboTools::createPrintingCode($f->code, true, true), 0) ? true : false);
            $printer->capabilities[] = $t2c;
            $t1bn = new jboPrinterCapabilitie($printer->code, $f->code);
            $t1bn->doubleSided = false;
            $t1bn->color = false;
            $t1bn->capable = (jboTools::getPostVar(jboTools::createPrintingCode($f->code, false, false), 0) ? true : false);
            $printer->capabilities[] = $t1bn;
            $t2bn = new jboPrinterCapabilitie($printer->code, $f->code);
            $t2bn->doubleSided = true;
            $t2bn->color = false;
            $t2bn->capable =  (jboTools::getPostVar(jboTools::createPrintingCode($f->code, true, false), 0) ? true : false);
            $printer->capabilities[] = $t2bn;
        }
        
                
        $r = $pM->savePrinter($printer);

        $this->printersIni();
    }

    private function deletePrinter() {
        $code = jboTools::getGetVar('code');
        if ($code) {
            $pM = new jboPrinterModel();
            $pM->deletePrinter($code);
        }
        $this->printersIni();
    }

    private function editPaperformat() {
        $code = jboTools::getGetVar('code');

        $this->setTemplate("adminTemplate");
        $this->template->setView('editPaperFormat');
        if ($code) {
            $pfM = new jboPaperFormatsModel();
            $this->template->data['paperformat'] = $pfM->getByCode($code);
            $pM=new jboPrinterModel();
            $this->template->data['printers']=$pM->getAll();
            $this->template->data['capabilities']=$pM->getAllCapabilities();

        } else {
            $this->template->data['paperformat'] = new jboPaperFormat();
        }
        $this->template->data['menuactive'] = "printers";
        $this->template->render();
    }

    private function savePaperFormat() {

        $paperFormat = new jboPaperFormat();
        $paperFormat->code = jboTools::getPostVar('code');
        if (!$paperFormat->code) {
            echo "Error, no hay nombre del valor por defecto";
            $this->printersIni();
            return;
        }
        $paperFormat->name = jboTools::getPostVar('name');
        $paperFormat->w = jboTools::string2pt(jboTools::getPostVar('w', 0));
        $paperFormat->h = jboTools::string2pt(jboTools::getPostVar('h', 0));//precision3
        
        
        $pM = new jboPaperFormatsModel();
        $r = $pM->savePaperFormat($paperFormat);
        
        //impresoras por defecto
        
        $formatCode=$paperFormat->code;
        $color1=jboTools::getPostVar('color1');
        $color2=jboTools::getPostVar('color2');
        $bn1=jboTools::getPostVar('bn1');
        $bn2=jboTools::getPostVar('bn2');
        
        if (!$formatCode) {
            echo "Error, no hay codigo del formato";
            $this->defaultsIni();
            return;
        }
       
        $pfM = new jboPaperFormatsModel();
        $r = $pfM->saveDefaultPrinterForPaperFormat($formatCode, $color1, $color2, $bn1, $bn2);
        
        //chequeamos si hay precios para este formato, y si no hay los inicializamos
        $priceM=new jboPaperPricesModel();
        $prices=$priceM->getPriceForPaperFormat($paperFormat->code);
        
        //check si hay precios de color
        if (count($prices->color)<1){
            $price = new stdClass();
            $price->color = 1;
            $price->format=$paperFormat->code;
            $price->min=1;
            $price->max=999999999999;
            $price->price=0.0;
            $priceM->savePrice($price);
        }
        //check si hay precios de bn
        if (count($prices->bn)<1){
            $price = new stdClass();
            $price->color = 0;
            $price->format=$paperFormat->code;
            $price->min=1;
            $price->max=999999999999;
            $price->price=0.0;
            $priceM->savePrice($price);
        }
        $this->printersIni();
    }
    
    private function deletePaperformat() {
        $code = jboTools::getGetVar('code');
        if ($code) {
            //borrar formato
            $pM = new jboPaperFormatsModel();
            $pM->deletePaperFormat($code);
            //borrar precios
            $priceM=new jboPaperPricesModel();
            $priceM->deletePricesForPaperFormat($code);
        }
        $this->printersIni();
    }


      /*SECCION DE USER######################################################################################*/
  
    private function sectionUser() {
        switch ($this->func) {
            //refresco de sesion
            case 'logout':
                unset($_SESSION['admin']);
                jboTools::redirect("admin.php");
                break;
            default://pantalla inicial
                echo "default section user";
                die;
        }
    }

}
