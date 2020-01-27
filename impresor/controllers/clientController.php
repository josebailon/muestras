<?php

defined("NODIRECT") or die;


class jboClientController extends jboController {
private $clientModel; //client model

                
public function __construct(){

    parent::__construct();
        $this->clientModel=new jboClientModel();
        $this->initSessionVariables();

      if ($_SESSION['client']==null&&!jboConf::$debugPreview){
           die("Cliente no autorizado<script>var controller={};controller.controlledClose=function(){}</script>");
       }
       $this->switchSection();
    }

private function initSessionVariables(){
                
    if (!isset($_SESSION['client'])||$_SESSION['client']==false&&!jboConf::$debugPreview){
                
        $_SESSION['client']=$this->clientModel->autoIdentifyClient();
              

    }
    else{ //cliente si registrado
        }
    
    
}
private function switchSection(){

	switch ($this->section) {
                            case 'test':
                    $this->test();
                    break;
                //refresco de sesion
                case 'refreshSession':
                    echo "Refresh OK";
                    die;
                    break;
                //ver si hay que bloquear el cliente
                case 'needtoblock':
                    $cb=new jboClientBlocker();
                    $out=[];
                    $out['allowed']=(!($cb->clientsBlocked()));
                    echo json_encode($out);
                    die;
                    break;
                //desbloquear el cliente
                case 'unblockclient':
                        $cb=new jboClientBlocker();
                        $cb->unmarkClientAsWorking($_SESSION['client']['id']);
                case 'getclientdata':
                    $idClient= jboTools::getPostVar("idClient");
                        if ($_SESSION['client']['id']!=$idClient){
                            echo "Error idcliente";
                            die;
                        }                       
                        $out=[];                        
                        $clientm=new jboClientModel();
                        $out['clientdata']=$clientm->getById($_SESSION['client']['id']);
                        echo json_encode($out); 
                        die;
                        break;
		case 'docs'://si elige trabajar con documentos
                        $this->switchDocs();
			break;
                case 'getserverfile':
                        echo json_encode($this->switchGetServerFile());
                        die;
                        break;
                case 'geticon':
                        $out["error"]="";
                        $idJobRequest= jboTools::getPostVar("idJob");
                        if ($_SESSION['idJob']!=$idJobRequest){$out['error']="Numero de trabajo erroneo"; echo json_encode($out); die;}
                        $documentCode=jboTools::getPostVar("documentCode");
                        $icon=new jboIconCreator($documentCode);                
                        echo json_encode(  $icon->getIconPath());
                        die;
                        break;
                case 'getpreview':
                        $idJobRequest= jboTools::getGetVar("idJob");
                        $idDocRequest= jboTools::getGetVar("idDoc");
                        if ($_SESSION['idJob']!=$idJobRequest && !jboConf::$debugPreview){$out['error']="Numero de trabajo erroneo"; echo json_encode($out); die;}
                        $dm=new jboDocumentModel();
                        $doc=$dm->getById($idDocRequest);
                        $previewCreator=new jboPreviewCreator();
                        $previewName=$previewCreator->createPreview($doc);
                        $this->getPdf($idJobRequest,$previewName);
                        break;
                case 'getpreviewPerSheet':

                        $idJobRequest= jboTools::getGetVar("idJob");
                        $idDocRequest= jboTools::getGetVar("idDoc");
                        $sheet= jboTools::getGetVar("sheet");
                        if ($_SESSION['idJob']!=$idJobRequest&&!jboConf::$debugPreview){$out['error']="Numero de trabajo erroneo"; echo json_encode($out); die;}
                        $dm=new jboDocumentModel();
                        $doc=$dm->getById($idDocRequest);
                        $previewCreator=new jboPreviewCreator();
                        $previewName=$previewCreator->createPreviewPerSheet($doc, $sheet);
                        $this->getPdf($idJobRequest,$previewName);
                        break;
                default://pantalla inicial
                        $cb=new jboClientBlocker();
                        $cb->unmarkClientAsWorking($_SESSION['client']['id']);
                        $jobM=new jboJobModel();
                        $jobM->cleanJobsWithStatus0($_SESSION['client']['id']);
			$this->setTemplate("clientTemplate");
			$this->template->data['client']=$_SESSION['client'];
                        $dm=new jboDefaultsModel();
                        $shopName=$dm->getByCode("shopName");
                        $this->template->data["shopName"]=$shopName;
                        $this->template->pageTitle="Impresor ".$shopName;
                        $this->template->render();
			break;
	}

}//fin switchSection


//GET PDF
private function getPdf($idJob,$previewName){
           //todo sanitize $name
       
       $file= jboConf::$jobsPath.$idJob.D_S.$previewName;
                
        if(file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
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


private function switchGetServerFile(){
    //todo chequear la existencia de los archivos
    if (in_array($this->func, jboConf::$selectorServerFiles)){
                
           jboTools::sendArchivoMadre($this->func);
        }
            die;
}

private function switchDocs(){
    	switch ($this->func) {

                case 'update':               
                      $idJobRequest= jboTools::getPostVar("idJob");
                    if ($_SESSION['idJob']!=$idJobRequest){$out['error']="Numero de trabajo erroneo"; echo json_encode($out); die;}
                    $documentData= json_decode(jboTools::getPostVar('documentData'));
                    $documentsForDelete = json_decode(jboTools::getPostVar('documentsForDelete'));
                    $out=[];
                
                    $dM=new jboDocumentModel();
                    $out['success']=true;
                    foreach ($documentsForDelete as $fd){
                        $resdel=$dM->deleteById($fd);
                        if ($resdel!==true){
                            $out['success']=false;
                        }
                    }
                    foreach ($documentData as $d){
                        $result = $dM->updateDocument($d);
                        if ($result!==true){
                            $out['success']=false;
                        }
                    }
                    
                    echo json_encode($out);
                    die;
                    break;
                case 'sendfilefortransform':
                        echo json_encode($this->uploadDocumentForTransform());
                        die;
                    break;
                case 'sendfileforanalysis':
                        echo json_encode($this->uploadDocumentForAnalysis());
                        die;
                        break;
                case 'getanalysisdata':                    
                        echo json_encode($this->getJobAnalysis());
                        die;
                        break;
                case 'sendjobtoprint':
                        $this->sendJobToPrint();
                        break;
                 case 'getclientdata':
                        $idClient= jboTools::getPostVar("idClient");
                        if ($_SESSION['client']['id']!=$idClient){
                            echo "Error idcliente";
                            die;
                        }                       
                        $out=[];                        
                        $clientm=new jboClientModel();
                        $out['clientdata']=$clientm->getById($_SESSION['client']['id']);
                        
                        $pricesM=new jboPaperPricesModel();
                        $out['prices']=$pricesM->getAll();
                        $bindingM= new jboBindingModel();
                        $out['bindings'] = $bindingM->getAll();
                        $ffM=new jboFileFormatsModel();
                        $out['allowedExtensions']=$ffM->getAllowedByClientId($_SESSION['client']['id']);
                        
                        $out['selectorServerFiles']=jboConf::$selectorServerFiles;
                        
                        $out['idJob']=$_SESSION['idJob'];
                        
                        $dm=new jboDefaultsModel();
                        $out['previewDocMaxPagesLimit']=$dm->getByCode('previewDocMaxPagesLimit');
                        $out['previewImgMaxPagesLimit']=$dm->getByCode('previewImgMaxPagesLimit');
 
                        
                        //defaults de trabajos
                        $out['documentDefaults']=[];
                        $out['documentDefaults']['documentDefaultDoubleSided']=$dm->getByCode('documentDefaultDoubleSided');
                        $out['documentDefaults']['documentDefaultImagesDoubleSided']=$dm->getByCode('documentDefaultImagesDoubleSided');
                        $out['documentDefaults']['documentDefaultForceRotation']=$dm->getByCode('documentDefaultForceRotation');
                        
                        //paper formats
                        $pfm=new jboPaperFormatsModel();
                        $out['paperFormats']=$pfm->getAll();
                        $out['mosaicData']= jboMosaicTools::getAllMosaicsForAllFormats();
                        echo json_encode($out); 
                        die;
                        break;
                default://init de pedido de documentos, se crea su id y se pasa
                            //jboTools::log("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"." pide inicializacion a las ".microtime().PHP_EOL);
                        $cb=new jboClientBlocker();
                        $cb->markClientAsWorking($_SESSION['client']['id']);
                        $jobM=new jboJobModel();
                        $ffM=new jboFileFormatsModel();
			$this->setTemplate("clientTemplate");
                        $this->template->title="Seleccionar Documentos a Imprimir";                       
                        $this->template->data['client']=$_SESSION['client'];
                        $_SESSION['idJob']=$jobM->initJob($_SESSION['client']['id']);
                        $this->template->data['idJob']=$_SESSION['idJob'];
                        $this->template->data['allowedDocExtensions']=$ffM->getAllowedExtStringByClientId(($_SESSION['client']['id']));
                        $dm=new jboDefaultsModel();
                        $shopName=$dm->getByCode("shopName");
                        $this->template->data["shopName"]=$shopName;
                        $this->template->data["documentDefaultDoubleSided"]=$dm->getByCode("documentDefaultDoubleSided");
                        $this->template->data["documentDefaultImagesDoubleSided"]=$dm->getByCode("documentDefaultImagesDoubleSided");
                        $cM=new jboClientModel();
                        $this->template->data["addFileDefaultPath"]=$cM->getById($_SESSION['client']['id'])['addFileDefaultPath'];
                        $this->template->pageTitle="Impresor ".$shopName;
                        $this->template->setView('documentSelector');
                        $this->template->render();
 			break;
	}
}
    private function test (){
        $id = jboTools::getPostVar('id');
        $dm = new jboDocumentModel();
        var_dump($dm->setStatus($id,69));
        echo "despues";
        die;
    }
    private function sendJobToPrint(){
                //jboTools::log("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"." pide sendjob to print a las a las ".microtime().PHP_EOL);
        $out= array("success"=>false);
        $job= json_decode(jboTools::getPostVar('jobData'));   
        $out['test']='$job->idClient:'.$job->idClient." comparado con ".$_SESSION['client']['id'].' y comparamos $job->id:'.$job->id. "con :".$_SESSION['idJob'];
        if ($job->idClient==$_SESSION['client']['id']&&$job->id==$_SESSION['idJob']){
            $docsSaved=true;
            $printerm=new jboPrinterModel();
            foreach ($job->documents as $doc){
                 //chequeamos la impresora elegida y si es la default la pondra
                $doc->printer = $printerm->detectPrinter($doc);
                $docm = new jboDocumentModel();
                if (!$docm->updateDocument($doc)) {
                    $docsSaved = false;
                }
            }
            $docm->cleanDocsWithStatus0ByJob($job->id);
            if ($docsSaved) {
                $jobm = new jboJobModel();
                $out['success'] = $jobm->updateJob($job, true);
                $out['metadata']=$job;

                //mandar a la cola de impresion si esta en modo automatico
                if (jboTools::isAutoPrint()) {
                    $jobm->addToPrintQueue($job->id);
                    jboTools::launchPrepare();
                    jboTools::launchPrinterQueueManager();
                } else {
                    jboTools::launchPrepare();
                }
            }
        }
        echo json_encode($out);
        die;
    }
    private function getJobAnalysis(){
        $out=array();
        $out["error"]="";
        $idJobRequest= jboTools::getPostVar("idJob");
        if ($_SESSION['idJob']!=$idJobRequest){$out['error']="Numero de trabajo erroneo"; return $out;}
        $model=new jboDocumentModel();
        $out['data']=$model->getAllAnalizedByJob($idJobRequest);
        return $out;
    }
    
    private function uploadDocumentForTransform(){
         $out=array();
        $out["error"]="";
        $idJobRequest= jboTools::getPostVar("idJob");
         if ($_SESSION['idJob']!=$idJobRequest){$out['error']="Numero de trabajo erroneo"; return $out;}

        $documentData= json_decode(jboTools::getPostVar('documentData'));
        $dir_dest= jboConf::$jobsPath.$_SESSION['idJob'].D_S;
        $fichero_subido = $dir_dest . basename($_FILES['file']['name']);
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $fichero_subido)) {
            $out["msg"]="Envio correcto para la transformacion.";
            $documentM=new jboDocumentModel();
            if ($documentM->addDocument($documentData)){
                //Si se ha añadido a la tabla lanzamos el transformador
                //TODO LAUNCHTRANSFORM
                $this->launchTransform();
                $this->launchAnalysis();
                }
             else{$out['error']="Problema al registrar el documento en la base de datos. ".$documentData->tempName;}

            return $out;
        } else {
            var_dump($_FILES);
            $out['error']="Error en la reccepción del archivo en el servidior. ".$fichero_subido.$_FILES['file']['tmp_name']; return $out;
        }
    }
    
    private function uploadDocumentForAnalysis(){
         $out=array();
        $out["error"]="";
        $idJobRequest= jboTools::getPostVar("idJob");
         if ($_SESSION['idJob']!=$idJobRequest){$out['error']="Numero de trabajo erroneo"; return $out;}

        $documentData= json_decode(jboTools::getPostVar('documentData'));
        $dir_dest= jboConf::$jobsPath.$_SESSION['idJob'].D_S;
        $fichero_subido = $dir_dest . basename($_FILES['file']['name']);
        
        if (move_uploaded_file($_FILES['file']['tmp_name'], $fichero_subido)) {
            $out["msg"]="Envio correcto.";
            $documentM=new jboDocumentModel();
            if ($documentM->addDocument($documentData)){
                //Si se ha añadido a la tabla lanzamos el analizador
                $this->launchAnalysis();
                }
             else{$out['error']="Problema al registrar el documento en la base de datos. ".$documentData->tempName;}

            return $out;
        } else {
            var_dump($_FILES);
            $out['error']="Error en la reccepción del archivo en el servidior. ".$fichero_subido.$_FILES['file']['tmp_name']; return $out;
        }
    }

    private function launchAnalysis(){
        //paron si debugAnalisys;
        if (jboConf::$debugAnalysis){return;}
        exec(jboConf::$phpPath.' initAnalysis.php  > /dev/null &');
                
    }
    private function launchTransform(){
        //paron si debugTransform;
        if (jboConf::$debugTransform){return;}
        exec(jboConf::$phpPath.' initTransform.php  > /dev/null &');
                        
    }

}