<?php

defined("NODIRECT") or die;

class jboPrinterModel extends jboModel {

    function __construct($initConected = true) {
        parent::__construct($initConected);
    }

    public function getByCode($code) {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'printers WHERE code = :code');
            $gsent->bindValue(':code', $code, PDO::PARAM_STR);
            $gsent->execute();
            $result = $gsent->fetch(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            print $e->getMessage();
            $this->autoDisconect();
            return false;
        }
    }

    //detecta la impresora para un documento y la chequea. Elije la correspondiente por defecto si la elegida no existe o no puede imprimir el trabajo.
    public function detectPrinter($doc) {

        if ($doc->printer != "default") {
            if ($this->checkPrinterDoc($doc)) {
                return $doc->printer;
            }
        }
        //como es default o la impresora no puede imprimir el documento cogemos el default 
        $pfm=new jboPaperFormatsModel();
        return $pfm->getDefaultPrinter($doc->printFormat, $doc->color, $doc->doubleSided);
    }

    //chequea si la impresora elegida para un documento es adecuada
    private function checkPrinterDoc($doc) {
        $printingCode = jboTools::createPrintingCode($doc->printFormat, $doc->doubleSided, $doc->color);
        $printer = $this->getByCode($doc->printer);
            if ($this->isCapable($doc->printer, $doc->printFormat, $doc->color, $doc->doubleSided)) {
                return true;
        }
        return false;
    }


    
    public function getAll() {
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'printers ORDER BY name COLLATE NOCASE');
            $gsent->execute();
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            print $e->getMessage();
        }
    }
public function deletePrinter($code){
        if ($code == null || $code == "") {
            return false;
        }
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'printers WHERE code = :code');
        $gsent->bindValue(':code', $code, PDO::PARAM_STR);
        $out = $gsent->execute();
        $this->autoDisconect();
        $this->deleteCapabilitiesForPrinter($code);
        return $out;
    }
   public function savePrinter($printer){
       
        if (isset($printer->code)){
            if ($printer->code==null&&$printer->code==""){return false;}
        }
        $this->deletePrinter($printer->code);
         $this->autoConect();
         try {  
             $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'printers (name,code,comcopies,combn,comcolor,com2sidesbindingnormal,com2sidesbindingsmall,pslevel,printmode,fittopage,comsummaryjobprint) VALUES (:name,:code,:comcopies,:combn,:comcolor,:com2sidesbindingnormal,:com2sidesbindingsmall,:pslevel,:printmode,:fittopage,:comsummaryjobprint)');
 
            $gsent->bindValue(':code', $printer->code, PDO::PARAM_STR);
            $gsent->bindValue(':name', $printer->name, PDO::PARAM_STR);
             $gsent->bindValue(':comcopies', $printer->comcopies, PDO::PARAM_STR);
             $gsent->bindValue(':combn', $printer->combn, PDO::PARAM_STR);
             $gsent->bindValue(':comcolor', $printer->comcolor, PDO::PARAM_STR);
             $gsent->bindValue(':com2sidesbindingnormal', $printer->com2sidesbindingnormal, PDO::PARAM_STR);
             $gsent->bindValue(':com2sidesbindingsmall', $printer->com2sidesbindingsmall, PDO::PARAM_STR);
             $gsent->bindValue(':pslevel', $printer->pslevel, PDO::PARAM_STR);
             $gsent->bindValue(':printmode', $printer->printmode, PDO::PARAM_INT);
             $gsent->bindValue(':fittopage', $printer->fittopage, PDO::PARAM_BOOL);
            $gsent->bindValue(':comsummaryjobprint', $printer->comsummaryjobprint, PDO::PARAM_STR);

             
             
            $out = $gsent->execute();
             $this->autoDisconect();
             
             foreach($printer->capabilities as $c){
                 $this->saveCapabilitie($c);
             }
            return $out;
        } catch (PDOException $e) {
            var_dump($e);
            $this->autoDisconect();
            return false;
        }
    }
    
    
    
    
    //CAPABILITIES DE IMPRESORAS
    
    

        //devuelve si una impresora es capaz de imprimir en un formato, color y doble cara
        public function isCapable ($printerCode,$formatCode,$color,$doubleSided){
         $this->autoConect();
        try {
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'printer_capability WHERE printerCode=:printerCode AND formatCode=:formatCode AND color=:color AND doubleSided=:doubleSided');
            $gsent->bindValue(':printerCode', $printerCode, PDO::PARAM_STR);
            $gsent->bindValue(':formatCode', $formatCode, PDO::PARAM_STR);
            $gsent->bindValue(':color', $color, PDO::PARAM_BOOL);
            $gsent->bindValue(':doubleSided', $doubleSided, PDO::PARAM_BOOL);
            $gsent->execute();
            $printers = $gsent->fetchAll(PDO::FETCH_OBJ);
            $out=false;
            if (is_array($printers)){
                if (count($printers)>0){
                    $out=($printers[0]->capable==true);
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
    
    public function getAllCapabilities(){
        try {
            $this->autoConect();
            $gsent = $this->con->db->prepare('SELECT * FROM ' . jboConf::$tablePrefix . 'printer_capability');
            $gsent->execute();
            $result = $gsent->fetchAll(PDO::FETCH_OBJ);
            $this->autoDisconect();
            return $result;
        } catch (PDOException $e) {
            $this->autoDisconect();
            print $e->getMessage();
        }
   
    }
    
    
    public function deleteCapabilitiesForPrinter($code){
        if ($code == null || $code == "") {
            return false;
        }
        $this->autoConect();
        $gsent = $this->con->db->prepare('DELETE FROM ' . jboConf::$tablePrefix . 'printer_capability WHERE printerCode = :code');
        $gsent->bindValue(':code', $code, PDO::PARAM_STR);
        $out = $gsent->execute();
        $this->autoDisconect();
        return $out;
    }
    
    public function saveCapabilitie($cap){
       
         $this->autoConect();
         try {  
             $gsent = $this->con->db->prepare('INSERT INTO ' . jboConf::$tablePrefix . 'printer_capability (printerCode, formatCode, color, doubleSided, capable) VALUES (:printerCode, :formatCode, :color, :doubleSided, :capable)');
 
            $gsent->bindValue(':printerCode', $cap->printerCode, PDO::PARAM_STR);
            $gsent->bindValue(':formatCode', $cap->formatCode, PDO::PARAM_STR);
             $gsent->bindValue(':color', $cap->color, PDO::PARAM_BOOL);
             $gsent->bindValue(':doubleSided', $cap->doubleSided, PDO::PARAM_BOOL);
             $gsent->bindValue(':capable', $cap->capable, PDO::PARAM_BOOL);
 
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

//fin de clase