<?php

/**
 * Description of jboIconCreator
 *
 * @author Jose
 */
class jboIconCreator {
    
    private $documentM;//modelo de documentos
    private $Document;//documento con el que trabajar actualmente
    private $documentId;
    private $iconpath;
    private $iconuri;
    function __construct($documentCode) {
        $this->documentId=$documentCode;
        $this->iconpath= jboConf::$iconRealPath.$this->documentId.'.jpg';
        $this->iconuri= jboConf::$iconFolder.$this->documentId.'.jpg';
   }
   
    
   
   public function getIconPath(){
       $this->documentM=new jboDocumentModel();
       $this->Document=$this->documentM->getById($this->documentId);
       if (!$this->Document){return $this->responseToRetray();}
      
        if ($this->fileExist()){
            $out=array();
            $out['uri']=$this->iconuri;
            $out['error']="";
            return $out;
       }
       else{
       if($this->running()){return $this->responseToRetray();}
        //si no esta funcionando
        $this->lockRunning();
        $out=array();
        $out['uri']=$this->createIcon();
        $this->unlockRunning();
        if($out['uri']){$out['error']="";}
        else{$out['error']=true;}
        return $out;
       }
   }
   
   private function createIcon(){
       $orig=jboConf::$jobsPath.$this->Document->idJob.D_S.$this->Document->tempNameWithExtension;
       $density=intval(842/$this->Document->height*5);
       jboConverter::pdf2jpg($orig, $this->iconpath,0,$density);
        return $this->iconuri;
   }
   
   private function fileExist(){
      if(file_exists ( $this->iconpath )){
          return true;
      }
      else{
          return false;
      }
   }

   private function responseToRetray(){
           $out=array();
           $out['result']=true;
           return $out;
   }
 
   //devuelve true si ya hay un analizador funcionando
   private function running(){
       return file_exists('iconcreation.lock');
   }
   
   //se apropia de la cola de analisis bloqueando que otros procesos puedan analizar
   private function lockRunning(){
 
        $this->analyzing=true;
        $f=fopen("iconcreation.lock", "w") or die("Unable to open file!");
        fclose($f);

   }
   
   //desbloquea la cola de analisis permitiendo que otros procesos puedan analizar
   private function unlockRunning(){
       $this->analyzing=false;
       if($this->running()){
          unlink ('iconcreation.lock');
       }  
   }

}
