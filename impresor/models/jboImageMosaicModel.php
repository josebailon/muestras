<?php
defined("NODIRECT") or die;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of jboImageMosaicModel
 *
 * @author Jose
 */
class jboImageMosaicModel extends jboModel{
    
    
    
    function __construct($initConected = true) {
        parent::__construct($initConected);
    }
    

    //obtener el objeto completo
    public function getByCode($code) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'image_mosaic WHERE code = :code');
            $gsent->bindValue(':code', $code, PDO::PARAM_STR);
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            print $e->getMessage();
            $this->autoDisconect();
        }
    }
    
    public function getAll() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'image_mosaic ORDER BY code');
             $gsent->execute();
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
 
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            print $e->getMessage();
            $this->autoDisconect();
        }
    }
    
    public function deleteDefault($code){
        if ($code == null || $code == "") {
            return false;
        }
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'defaults WHERE code = :code');
        $gsent->bindValue(':code', $code, PDO::PARAM_STR);
        $out = $gsent->execute();
        $this->autoDisconect();
        return $out;
    }
   public function saveDefault($default){
       
        if (isset($default->code)){
            if ($default->code==null&&$default->code==""){return false;}
        }
        $this->deleteDefault($default->code);
         $this->autoConect();
         try {
       
             $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'defaults (code,value) VALUES (:code,:value)');
            
            
            $gsent->bindValue(':code', $default->code, PDO::PARAM_STR);
             $gsent->bindValue(':value', $default->value, PDO::PARAM_STR);
            $out = $gsent->execute();
             $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }
}
