<?php

defined("NODIRECT") or die;
/*
  STATUS:
 * 0:en cliente
 * 1:Aceptado por el cliente
 * 2:preparado
 * 3:imprimiendo
 * 4:imprimido
 * 
 */

class jboDocumentModel extends jboModel {

    function __construct($initConected = true) {
        parent::__construct($initConected);
    }

    public function getById($id) {
 
        $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'documents WHERE tempName=:id');
            $gsent->bindValue(':id', $id, PDO::PARAM_STR);
            $gsent->execute();

            /* Obtener todos los valores de la primera fila */
            $result = $gsent->fetch(PDO::FETCH_OBJ, 0);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
        $this->autoDisconect();
    }

    public function addDocument($document) {
         $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'documents (tempName, idJob, printFormat, originalFormat, width, height, name, ext, tempNameWithExtension, analyzed, errorOnAnalyze, nPages, color, doubleSided, pageRange, mosaic, nCopies, printer, transformed, errorOnTransform, status, errorMessage, printingPages, printableName,iconUrl, shortBound, idPrint, canceled, forceRotation, paperOrientation, isForm, binding, bindingName, groupName, groupOrder, type, warningMessage, pagesAnalysis, fileOrder) VALUES (:tempName, :idJob, :originalFormat, :printFormat, :width, :height, :name, :ext, :tempNameWithExtension, :analyzed, :errorOnAnalyze, :nPages, :color, :doubleSided, :pageRange, :mosaic, :nCopies, :printer, :transformed, :errorOnTransform, 0, :errorMessage, :printingPages, :printableName,:iconUrl,:shortBound,:idPrint,:canceled,:forceRotation,:paperOrientation, :isForm, :binding, :bindingName,:groupName,:groupOrder, :type, :warningMessage, :pagesAnalysis, :fileOrder)');
            $gsent->bindValue(':tempName', $document->tempName, PDO::PARAM_STR);
            $gsent->bindValue(':idJob', $document->idJob, PDO::PARAM_INT);
            $gsent->bindValue(':printFormat', $document->printFormat, PDO::PARAM_STR);
            $gsent->bindValue(':originalFormat', $document->originalFormat, PDO::PARAM_STR);
            $gsent->bindValue(':width', $document->width, PDO::PARAM_INT);
            $gsent->bindValue(':height', $document->height, PDO::PARAM_INT);
            $gsent->bindValue(':name', $document->name, PDO::PARAM_STR);
            $gsent->bindValue(':ext', $document->ext, PDO::PARAM_STR);
            $gsent->bindValue(':tempNameWithExtension', $document->tempNameWithExtension, PDO::PARAM_STR);
            $gsent->bindValue(':analyzed', $document->analyzed, PDO::PARAM_BOOL);
            $gsent->bindValue(':errorOnAnalyze', $document->errorOnAnalyze, PDO::PARAM_BOOL);
            $gsent->bindValue(':nPages', $document->nPages, PDO::PARAM_INT);
            $gsent->bindValue(':color', $document->color, PDO::PARAM_BOOL);
            $gsent->bindValue(':doubleSided', $document->doubleSided, PDO::PARAM_BOOL);
            $gsent->bindValue(':pageRange', $document->pageRange, PDO::PARAM_STR);
            $gsent->bindValue(':mosaic', $document->mosaic, PDO::PARAM_INT);
            $gsent->bindValue(':nCopies', $document->nCopies, PDO::PARAM_INT);
            $gsent->bindValue(':printer', $document->printer, PDO::PARAM_STR);
            $gsent->bindValue(':transformed', $document->transformed, PDO::PARAM_BOOL);
            $gsent->bindValue(':errorOnTransform', $document->errorOnTransform, PDO::PARAM_BOOL);
            $gsent->bindValue(':errorMessage', $document->errorMessage, PDO::PARAM_STR);
            $gsent->bindValue(':printingPages', $document->printingPages, PDO::PARAM_INT);
            $gsent->bindValue(':printableName', $document->printableName, PDO::PARAM_STR);
            $gsent->bindValue(':iconUrl', $document->iconUrl, PDO::PARAM_STR);
            $gsent->bindValue(':shortBound', $document->shortBound, PDO::PARAM_BOOL);
            $gsent->bindValue(':idPrint', $document->idPrint, PDO::PARAM_STR);
            $gsent->bindValue(':canceled', $document->canceled, PDO::PARAM_INT);
            $gsent->bindValue(':forceRotation', $document->forceRotation, PDO::PARAM_BOOL);
            $gsent->bindValue(':paperOrientation', $document->paperOrientation, PDO::PARAM_INT);
            $gsent->bindValue(':isForm', $document->isForm, PDO::PARAM_INT);
            $gsent->bindValue(':binding', $document->binding, PDO::PARAM_INT);//acabado
            $gsent->bindValue(':bindingName', $document->bindingName, PDO::PARAM_STR);//acabado
            $gsent->bindValue(':groupName', $document->groupName, PDO::PARAM_STR);//grupo
            $gsent->bindValue(':groupOrder', $document->groupOrder, PDO::PARAM_INT);//orden
            $gsent->bindValue(':type', $document->type, PDO::PARAM_INT);//tipo /0doc 1 imagen
            $gsent->bindValue(':warningMessage', $document->warningMessage, PDO::PARAM_STR);//mensaje de aviso(para formato no estandar, problemas de paginas...)
            if (!is_string($document->pagesAnalysis)){
                $document->pagesAnalysis = json_encode($document->pagesAnalysis);
            }
            $gsent->bindValue(':pagesAnalysis', $document->pagesAnalysis, PDO::PARAM_STR);//tipo /0doc 1 imagen
            $gsent->bindValue(':fileOrder', $document->fileOrder, PDO::PARAM_INT);//orden dentro del trabajo
            
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }

//fin deinitjob

    public function updateDocument($document,$updatestatus=true) {
        $this->autoConect();
        try {
            $updateStatusString="";
            //miramos si hay que hacer update de status
            if ($updatestatus){
                $updateStatusString=" status=:status,";
            }
            $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET idJob=:idJob , originalFormat=:originalFormat , printFormat=:printFormat, width=:width , height=:height , name=:name , ext=:ext , tempNameWithExtension=:tempNameWithExtension , analyzed=:analyzed , errorOnAnalyze=:errorOnAnalyze , nPages=:nPages , color=:color , doubleSided=:doubleSided , pageRange=:pageRange , mosaic=:mosaic , nCopies=:nCopies , printer=:printer , transformed=:transformed , errorOnTransform=:errorOnTransform , '.$updateStatusString.' errorMessage=:errorMessage, printingPages=:printingPages, printableName=:printableName,iconUrl=:iconUrl,shortBound=:shortBound,idPrint=:idPrint,canceled=:canceled,forceRotation=:forceRotation, paperOrientation=:paperOrientation, isForm=:isForm, binding=:binding, bindingName=:bindingName, groupName=:groupName, groupOrder=:groupOrder, type=:type, warningMessage=:warningMessage, pagesAnalysis=:pagesAnalysis, fileOrder=:fileOrder WHERE tempName=:tempName');
            $gsent->bindValue(':tempName', $document->tempName, PDO::PARAM_STR);
            $gsent->bindValue(':idJob', $document->idJob, PDO::PARAM_INT);
            $gsent->bindValue(':originalFormat', $document->originalFormat, PDO::PARAM_STR);
            $gsent->bindValue(':printFormat', $document->printFormat, PDO::PARAM_STR);
            $gsent->bindValue(':width', $document->width, PDO::PARAM_INT);
            $gsent->bindValue(':height', $document->height, PDO::PARAM_INT);
            $gsent->bindValue(':name', $document->name, PDO::PARAM_STR);
            $gsent->bindValue(':ext', $document->ext, PDO::PARAM_STR);
            $gsent->bindValue(':tempNameWithExtension', $document->tempNameWithExtension, PDO::PARAM_STR);
            $gsent->bindValue(':analyzed', $document->analyzed, PDO::PARAM_BOOL);
            $gsent->bindValue(':errorOnAnalyze', $document->errorOnAnalyze, PDO::PARAM_BOOL);
            $gsent->bindValue(':nPages', $document->nPages, PDO::PARAM_INT);
            $gsent->bindValue(':color', $document->color, PDO::PARAM_BOOL);
            $gsent->bindValue(':doubleSided', $document->doubleSided, PDO::PARAM_BOOL);
            $gsent->bindValue(':pageRange', $document->pageRange, PDO::PARAM_STR);
            $gsent->bindValue(':mosaic', $document->mosaic, PDO::PARAM_INT);
            $gsent->bindValue(':nCopies', $document->nCopies, PDO::PARAM_INT);
            $gsent->bindValue(':printer', $document->printer, PDO::PARAM_STR);
            $gsent->bindValue(':transformed', $document->transformed, PDO::PARAM_BOOL);
            $gsent->bindValue(':errorOnTransform', $document->errorOnTransform, PDO::PARAM_BOOL);
            if ($updatestatus){
                $gsent->bindValue(':status', $document->status, PDO::PARAM_INT);
            }
            $gsent->bindValue(':errorMessage', $document->errorMessage, PDO::PARAM_STR);
            $gsent->bindValue(':printingPages', $document->printingPages, PDO::PARAM_INT);
            $gsent->bindValue(':printableName', $document->printableName, PDO::PARAM_STR);
            $gsent->bindValue(':iconUrl', $document->iconUrl, PDO::PARAM_STR);
            $gsent->bindValue(':shortBound', $document->shortBound, PDO::PARAM_BOOL);
            $gsent->bindValue(':idPrint', $document->idPrint, PDO::PARAM_BOOL);
            $gsent->bindValue(':canceled', $document->canceled, PDO::PARAM_INT);
            $gsent->bindValue(':forceRotation', $document->forceRotation, PDO::PARAM_BOOL);
            $gsent->bindValue(':paperOrientation', $document->paperOrientation, PDO::PARAM_INT);
            $gsent->bindValue(':isForm',$document->isForm, PDO::PARAM_INT);
            $gsent->bindValue(':binding',$document->binding, PDO::PARAM_INT);
            $gsent->bindValue(':bindingName',$document->bindingName, PDO::PARAM_STR);
            $gsent->bindValue(':groupName', $document->groupName, PDO::PARAM_STR);//grupo
            $gsent->bindValue(':groupOrder', $document->groupOrder, PDO::PARAM_INT);//orden
            $gsent->bindValue(':type', $document->type, PDO::PARAM_INT);//tipo /0doc 1 imagen
            $gsent->bindValue(':warningMessage', $document->warningMessage, PDO::PARAM_STR);//mensaje de aviso(para formato no estandar, problemas de paginas...)
            if (!is_string($document->pagesAnalysis)){
                $document->pagesAnalysis = json_encode($document->pagesAnalysis);
            }
            $gsent->bindValue(':pagesAnalysis', $document->pagesAnalysis, PDO::PARAM_STR);//tipo /0doc 1 imagen
            $gsent->bindValue(':fileOrder', $document->fileOrder, PDO::PARAM_INT);//tipo /0doc 1 imagen
            
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    public function addErrorMessage($id,$msg){ 
        echo "se va a actualizar el mensaje de error con ID:".$id." MENSAJE:".$msg.PHP_EOL;
        $doc = $this->getById($id);
        $errormsg=$doc->errorMessage;
        $finalerrorMessage=$errormsg." - ".$msg;
        $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET  errorMessage=:errorMessage WHERE tempName=:tempName');
            $gsent->bindValue(':errorMessage', $finalerrorMessage, PDO::PARAM_STR);
            $gsent->bindValue(':tempName', $id, PDO::PARAM_STR);
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            return false;
        }
    }
    
    public function getFirstNotAnalized() {
        $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'documents WHERE analyzed=0 AND transformed=1 AND errorOnTransform=0');
            $gsent->execute();

            /* Obtener todos los valores de la primera fila */
            $result = $gsent->fetch(PDO::FETCH_OBJ, 0);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }
    public function needToAnalize(){
        $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'documents WHERE analyzed=0 AND errorOnTransform=0');
            $gsent->execute();

            /* Obtener todos los valores de la primera fila */
            $result = $gsent->fetch(PDO::FETCH_OBJ, 0);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }
    
        public function getFirstNotTransformed() {
        $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'documents WHERE transformed=0 AND errorOnTransform=0');
            $gsent->execute();

            /* Obtener todos los valores de la primera fila */
            $result = $gsent->fetch(PDO::FETCH_OBJ, 0);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }

    public function cleanDocsWithStatus0ByJob($idJob) {
        if ($idJob == null || $idJob == "") {
            return;
        }
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'documents WHERE idJob = :idJob AND status=0');
        $gsent->bindValue(':idJob', $idJob, PDO::PARAM_INT);
        $out = $gsent->execute();
        $this->autoDisconect();
        return $out;
    }
    public function deleteById($tempName){

        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'documents WHERE tempName = :tempName');
        $gsent->bindValue(':tempName', $tempName, PDO::PARAM_STR);
        $out = $gsent->execute();
        $this->autoDisconect();


        return $out;
    }
    public function getAllAnalizedByJob($idJob) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'documents WHERE analyzed=1 AND idJob=:idJob ORDER BY fileOrder');
            $gsent->bindValue(':idJob', $idJob, PDO::PARAM_INT);
            $gsent->execute();

            /* Obtener todos los valores que se han analizado */
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            var_dump($e);
            return $e->getMessage();
        }
    }

    /**
     * recoje los documentos de un trabajo determinado
     * @param type $idJob
     * @return boolean
     */
    public function getAllByJob($idJob) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'documents WHERE idJob=:idJob  ORDER BY fileOrder');
            $gsent->bindValue(':idJob', $idJob, PDO::PARAM_INT);
            $gsent->execute();

            /* Obtener todos los valores que se han analizado */
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }
    
        /**
     * recoje los documentos de un trabajo determinado para una impresora
     * @param type $idJob
     * @return boolean
     */
    public function getAllByJobAndPrinter($idJob,$printer) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'documents WHERE idJob=:idJob AND printer=:printer ORDER BY fileorder');
            $gsent->bindValue(':idJob', $idJob, PDO::PARAM_INT);
            $gsent->bindValue(':printer', $printer, PDO::PARAM_STR);
            $gsent->execute();

            /* Obtener todos los valores que se han analizado */
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }

    public function deleteDocumentsByJob($idJob) {
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'documents WHERE idJob = :idJob');
        $gsent->bindValue(':idJob', $idJob, PDO::PARAM_INT);
        $gsent->execute();
        $this->autoDisconect();
    }
    public function isGrouped($tempName){
        $doc = $this->getById($tempName);
        if ($doc->groupName!=""){
            return true;
        }
        return false;
    }

    public function setStatus($tempName, $status, $allgroup = true) {
        try {
                        $this->autoConect();
            $doc = $this->getById($tempName);


            
            //gsent para grupos
            if ($this->isGrouped($tempName) && $allgroup) {
                $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET status=:status WHERE groupName=:groupName');
                $gsent->bindValue(':groupName', $doc->groupName, PDO::PARAM_STR);

            }else{//gsent individual
                $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET status=:status WHERE tempName=:tempName');
                $gsent->bindValue(':tempName', $tempName, PDO::PARAM_STR);
            }
            $gsent->bindValue(':status', $status, PDO::PARAM_INT);
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }

    public function setCanceledLevel($tempName, $level, $allgroup=true) {
        try {
            $this->autoConect();
            $doc = $this->getById($tempName);
            if ($this->isGrouped($tempName) && $allgroup) {
                $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET canceled=:canceled WHERE groupName=:groupName');
                $gsent->bindValue(':groupName', $doc->groupName, PDO::PARAM_STR);

            }else{
            $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET canceled=:canceled WHERE tempName=:tempName');
            $gsent->bindValue(':tempName', $tempName, PDO::PARAM_STR);
            }
            $gsent->bindValue(':canceled', $level, PDO::PARAM_INT);
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }

    public function setPrintableName($tempName, $printableName, $allgroup = true) {
        try {
            $this->autoConect();
            $doc = $this->getById($tempName);
            if ($this->isGrouped($tempName) && $allgroup) {
                $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET printableName=:printableName WHERE groupName=:groupName');
                $gsent->bindValue(':groupName', $doc->groupName, PDO::PARAM_STR);

            }else{
                $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET printableName=:printableName WHERE tempName=:tempName');
                $gsent->bindValue(':tempName', $tempName, PDO::PARAM_STR);
            }
            $gsent->bindValue(':printableName', $printableName, PDO::PARAM_STR);
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }
    public function setIdPrint($tempName, $idPrint, $allgroup=true) {
        try {
            if ($this->isGrouped($tempName) && $allgroup) {

            $doc = $this->getById($tempName);

                $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET idPrint=:idPrint WHERE groupName=:groupName');
                $gsent->bindValue(':groupName', $doc->groupName, PDO::PARAM_STR);

            }else{
                $gsent = $this->con->db->prepare('UPDATE ' . jboConf::$tablePrefix . 'documents SET idPrint=:idPrint WHERE tempName=:tempName');
                $gsent->bindValue(':tempName', $tempName, PDO::PARAM_STR);
            }
            $gsent->bindValue(':idPrint', $idPrint, PDO::PARAM_STR);
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }
    public function getByIdPrint($id) {
 
        $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'documents WHERE idPrint=:id');
            $gsent->bindValue(':id', $id, PDO::PARAM_INT);
            $gsent->execute();
            /* Obtener todos los valores de la primera fila */
            $result = $gsent->fetch(PDO::FETCH_OBJ, 0);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
        $this->autoDisconect();
    }
        public function getAllByGroupName($groupName) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'documents WHERE groupName=:groupName  ORDER BY fileOrder');
            $gsent->bindValue(':groupName', $groupName, PDO::PARAM_STR);
            $gsent->execute();

            /* Obtener todos los valores que se han analizado */
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }

    
    public function getAllErrorMessages(){

        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT tempName,name,errorMessage,idJob FROM ' . jboConf::$tablePrefix . 'documents WHERE errorMessage IS NOT NULL AND errorMessage <> ""');
            $gsent->execute();

            /* Obtener todos los valores que se han analizado */
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }
}

//fin de clase