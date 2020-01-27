<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of jboAnalysisPerformer
 *
 * @author Jose
 */
class jboTransformPerformer {
   
    private $formats=[];
    
    public function __construct() {
        //TODO SI ES NECESARIO COGER LOS FORMATOS DE LA BASE DE DATOS
    }
    
    public function transform($doc){
        $result=array();
        $result['errorMessage']="Archivo<b> ". $doc->name ."</b> está corrupto o no es compatible";
        $result['fail']=true;
        //INICIA la transformación 
        $dm=new jboDefaultsModel();
        $iccfile=$dm->getByCode("icc");
        $orig= jboConf::$jobsPath . $doc->idJob . D_S . $doc->tempNameWithExtension;
        $dest=jboConf::$jobsPath . $doc->idJob . D_S . $doc->tempName.'.pdf';
        if (!file_exists($dest)){//ver si ya existe
            $resultc = jboConverter::jpg2pdf($orig, $dest,300, $iccfile);
        }else{
            $resultc=true;
        }
        if ($resultc){
            $result['fail']=false;
            $result['errorMessage']="";
        }
        
        return $result;
    }
    

}
