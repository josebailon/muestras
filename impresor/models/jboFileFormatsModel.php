<?php

defined("NODIRECT") or die;

class jboFileFormatsModel extends jboModel {

    function __construct($initConected = true) {
        parent::__construct($initConected);
    }

    public function deleteFormatsByClientId($clientId) {
                $this->autoConect();

        try {
            $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'client_file_formats  WHERE clientId=:clientId');

             $gsent->bindValue(':clientId', $clientId, PDO::PARAM_INT);
             $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }

    public function saveFormat($format) {
        $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'client_file_formats (formatId,clientId,transform,analysis,allowed) VALUES (:formatId,:clientId,:transform,:analysis,:allowed)');

            $gsent->bindValue(':formatId', $format->formatId, PDO::PARAM_INT);
            $gsent->bindValue(':clientId', $format->clientId, PDO::PARAM_INT);
            $gsent->bindValue(':transform', $format->transform, PDO::PARAM_INT);
            $gsent->bindValue(':analysis', $format->analysis, PDO::PARAM_INT);
            $gsent->bindValue(':allowed', $format->allowed, PDO::PARAM_INT);
           
            
            $out = $gsent->execute();
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }

    public function saveFormats($formatsArray) {
         foreach ($formatsArray as $f) {
            $this->saveFormat($f);
        }
    }

    public function saveFormatsForClient($formatsArray, $clientId) {
 
        $this->deleteFormatsByClientId($clientId);
        $this->saveFormats($formatsArray);
    }

    public function getAllByClientId($clientId) {
        $formats = $this->getFormats();
        $out = [];
        foreach ($formats as $value) {
            $current = $this->getByClientFormat($clientId, $value->id);
            if ($current) {
                $current->name = $value->name;
                $current->ext = $value->ext;
                $current->type=$value->type;
                $out[] = $current;
            }
        }
        return $out;
    }

    public function getAllowedByClientId($clientId){
        $all=$this->getAllByClientId($clientId); 
        $finalArray=[];
        foreach ($all as $f){
            if($f->allowed==1){
                $finalArray[]=$f;
            }
        }
        return $finalArray;
    }
    
    public function getAllowedExtArrayByClientId($clientId){
        $allowed=$this->getAllowedByClientId($clientId);
        $allowedArray=[];
        foreach ($allowed as $f){
            $allowedArray[]=$f->ext;
        }
        return $allowedArray;
    }
    public function getAllowedExtStringByClientId($clientId){
        $a=$this->getAllowedExtArrayByClientId($clientId);
        $s="";
        if (count($a)){$s=".". implode(",.", $a);}
        return $s;
    }
    
    public function getByClientFormat($clientId, $formatId) {
        $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'client_file_formats WHERE clientId=:clientId AND formatId=:formatId');
            $gsent->bindValue(':clientId', $clientId, PDO::PARAM_INT);
            $gsent->bindValue(':formatId', $formatId, PDO::PARAM_INT);
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

    public function deleteAllByClientId($id){
        if ($id == null || $id == "") {
            return;
        }
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'client_file_formats WHERE clientId = :id');
        $gsent->bindValue(':id', $id, PDO::PARAM_INT);
        $out = $gsent->execute();
        $this->autoDisconect();
        return $out;
    }
    public function getFormats() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'file_formats');
            $gsent->execute();
            /* Obtener todos los valores que se han analizado */
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            return $e->getMessage();
        }
    }
    public function isImage($ext){
        $formats=$this->getFormats();
        for ($i = 0 ; $i<count($formats);$i++){
            if (formats[$i].ext==$ext){
                if (formats[$i].type==1){
                    return true;
                }
            }
        }
        return false;
    }
}
