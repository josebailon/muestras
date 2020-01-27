<?php

defined("NODIRECT") or die;

class jboPaperFormatsModel extends jboModel {

    function __construct($initConected = true) {
        parent::__construct($initConected);
    }

   
    //get todos los formatos y sus precios
    public function getAll() {
        $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'paper_formats ORDER BY w');
            $gsent->execute();
            $formats = $gsent->fetchAll(PDO::FETCH_OBJ);
            
            foreach ($formats as $k=>$f){
            $formats[$k]->defaultPrinterColorSingle = $this->getDefaultPrinter($f->code, true,false);
            $formats[$k]->defaultPrinterColorDouble = $this->getDefaultPrinter($f->code, true,true);
            $formats[$k]->defaultPrinterBnSingle = $this->getDefaultPrinter($f->code, false,false);
            $formats[$k]->defaultPrinterBnDouble = $this->getDefaultPrinter($f->code, false,true);
        }
            
            $this->autoDisconect();
            return $formats;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            $this->autoDisconect();
            return false;
        }
    }
    

    public function getBycode($code){
         try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'paper_formats WHERE code = :code ');
            $gsent->bindValue(':code', $code, PDO::PARAM_STR);
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_OBJ);
           

            $result->defaultPrinterColorSingle = $this->getDefaultPrinter($code, true,false);
            $result->defaultPrinterColorDouble = $this->getDefaultPrinter($code, true,true);
            $result->defaultPrinterBnSingle = $this->getDefaultPrinter($code, false,false);
            $result->defaultPrinterBnDouble = $this->getDefaultPrinter($code, false,true);

            
            $this->autoDisconect();

            return $result;
        } catch (PDOException $e) {
            print $e->getMessage();
            $this->autoDisconect();
        }
    }
    
    
    /**
     * borra un formato de papel
     * @param type $code Codigo de formato
     * @param type $deleteCapabilites si true borrara tambien las capabilities asociadas a este formato y los parametros de impresora por defecto para este formato
     * @return boolean
     */
    public function deletePaperFormat($code,$deleteCapabilites=true){
        if ($code == null || $code == "") {
            return false;
        }
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'paper_formats WHERE code = :code');
        $gsent->bindValue(':code', $code, PDO::PARAM_STR);
        $out = $gsent->execute();
        $this->autoDisconect();
        
        if ($deleteCapabilites){
            $this->deleteCapabilitiesForPaperFormat($code);
            $this->deleteDefaultPrinterForPaperFormat($code);
        }
        return $out;
    }
   public function savePaperFormat($pf){
       
        if (isset($pf->code)){
            if ($pf->code==null&&$pf->code==""){return false;}
        }
        $this->deletePaperFormat($pf->code,false);//false para que no elimine los parametros de capabilities ya existentes
         $this->autoConect();
         try {  
             $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'paper_formats (name,code,w,h) VALUES (:name,:code,:w, :h)');
 
            $gsent->bindValue(':code', $pf->code, PDO::PARAM_STR);
            $gsent->bindValue(':name', $pf->name, PDO::PARAM_STR);
            $gsent->bindValue(':w', $pf->w, PDO::PARAM_STR);
            $gsent->bindValue(':h', $pf->h, PDO::PARAM_STR);
            $out = $gsent->execute();
             $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }

   public function deleteCapabilitiesForPaperFormat($code){
        if ($code == null || $code == "") {
            return false;
        }
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'printer_capability WHERE formatCode = :code');
        $gsent->bindValue(':code', $code, PDO::PARAM_STR);
        $out = $gsent->execute();
        $this->autoDisconect();
        return $out;
   }
    
   
       // devuelve la impresora por defecto para la combinacion de formato color y doble cara
    public function getDefaultPrinter($formatCode,$color,$doubleSided){
        $this->autoConect();
        try {                                                                                                  
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'printer_default WHERE formatCode=:formatCode AND color=:color AND doubleSided=:doubleSided');
            $gsent->bindValue(':formatCode', $formatCode, PDO::PARAM_STR);
            $gsent->bindValue(':color', $color, PDO::PARAM_BOOL);
            $gsent->bindValue(':doubleSided', $doubleSided, PDO::PARAM_BOOL);
            $gsent->execute();
            $printers = $gsent->fetchAll(PDO::FETCH_OBJ);
            $out=false;
            if (is_array($printers)){
                if (count($printers)>0){
                    $out=$printers[0]->defaultPrinter;
                }
            }
            $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            $this->autoDisconect();
            return false;
        }
    }
    
    
    public function saveDefaultPrinterForPaperFormat ($formatCode,$color1,$color2,$bn1,$bn2){
        
        $this->saveDefaultPrinter($formatCode, true, false, $color1);
        $this->saveDefaultPrinter($formatCode, true, true, $color2);
        $this->saveDefaultPrinter($formatCode, false, false, $bn1);
        $this->saveDefaultPrinter($formatCode, false, true, $bn2);
    }


    
    public function saveDefaultPrinter($formatCode,$color,$doubleSided,$defaultPrinter){
        if (($formatCode == null || $formatCode == "")||($color === null)||($doubleSided === null)) {
            return false;
        }
        $this->deleteDefaultPrinter($formatCode, $color, $doubleSided);//elimina el default que coincida
         $this->autoConect();
         try {  
             $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'printer_default (formatCode,color,doubleSided,defaultPrinter) VALUES (:formatCode,:color,:doubleSided, :defaultPrinter)');
 
            $gsent->bindValue(':formatCode', $formatCode, PDO::PARAM_STR);
            $gsent->bindValue(':color', $color, PDO::PARAM_BOOL);
            $gsent->bindValue(':doubleSided', $doubleSided, PDO::PARAM_BOOL);
            $gsent->bindValue(':defaultPrinter', $defaultPrinter, PDO::PARAM_STR);
            $out = $gsent->execute();
             $this->autoDisconect();
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }
    
    public function deleteDefaultPrinterForPaperFormat ($formatCode){
        $this->deleteDefaultPrinter($formatCode, true, false);
        $this->deleteDefaultPrinter($formatCode, true, true);
        $this->deleteDefaultPrinter($formatCode, false, false);
        $this->deleteDefaultPrinter($formatCode, false, true);
    }
            
    public function deleteDefaultPrinter($formatCode,$color,$doubleSided){
        if (($formatCode == null || $formatCode == "")||($color === null)||($doubleSided === null)) {
            return false;
        }
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'printer_default WHERE formatCode = :formatCode AND color=:color AND doubleSided=:doubleSided');
        $gsent->bindValue(':formatCode', $formatCode, PDO::PARAM_STR);
        $gsent->bindValue(':color', $color, PDO::PARAM_BOOL);
        $gsent->bindValue(':doubleSided', $doubleSided, PDO::PARAM_BOOL);
        $out = $gsent->execute();
        $this->autoDisconect();
        return $out;
    }
    
}

//fin de clase