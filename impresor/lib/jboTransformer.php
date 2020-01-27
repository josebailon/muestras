<?php

defined("NODIRECT") or die;

/**
 * Description of jboAnalyzer
 *
 * @author Jose
 */
class jboTransformer {

    private $documentM; //modelo de documentos
    private $currentDocument; //documento con el que trabajar actualmente
    public $transforming; //almacena si se esta transformando actualmente
    private $transformPerformer;
    function __construct($autoStart = true) {
        print "Constructor del jboTransformer" . PHP_EOL;

        $this->documentM = new jboDocumentModel(false);
        $this->transformPerformer = new jboTransformPerformer();
        if ($autoStart && !$this->running()) {
            $this->init();
        }
    }

    public function init() {
        print "init jbotransformer";
        $this->lockRunning();
        while ($this->transforming) {
            if ($this->getNextDocument()) {
                $this->performTransform();
                $this->saveTransform();
            } else {

                $this->unlockRunning();
            }
        }
    }

    //recoge un nuevo documento para analizar. Si no hay ninguno para analizar devuelve false.
    private function getNextDocument() {
        print "RECOGIENDO NUEVO DOCUMENTO<br>" . PHP_EOL;
        $this->documentM->conect();

        $nextDocument = $this->documentM->getFirstNotTransformed();

        if ($nextDocument) {
            $this->currentDocument = $nextDocument;
            $this->documentM->disconect();
            return $this->currentDocument;
        } else {
            $this->documentM->disconect();
            return false;
        }
    }

    //lanza el analisis
    public function performTransform() {
        //INICIA la transformacion 
        $currentTransform=$this->transformPerformer->transform($this->currentDocument);
        
        $this->currentDocument->transformed=true;   
        if ($currentTransform['fail']){
            $this->currentDocument->errorMessage = $currentTransform['errorMessage'];
            $this->currentDocument->analyzed=true;
            $this->currentDocument->errorOnAnalyze=true;
            $this->currentDocument->errorOnTransform=true;
        }else{
            //actualizamos tamien el nombre del tempwithextension
            $this->currentDocument->tempNameWithExtension=$this->currentDocument->tempName.".pdf";
        }
      }


    //guarda la informacion del analisis
    private function saveTransform() {
        var_dump($this->currentDocument);
        $this->documentM->conect();
        $this->documentM->updateDocument($this->currentDocument);
        $this->documentM->disconect();
    }

    //devuelve true si ya hay un analizador funcionando
    private function running() {
        return file_exists('transforming.lock');
    }

    //se apropia de la cola de analisis bloqueando que otros procesos puedan analizar
    private function lockRunning() {
        //bloqueamos la transformacion 
        $this->transforming = true;
        $f = fopen("transforming.lock", "w") or die("Unable to open file!");
        fclose($f);
    }

    //desbloquea la cola de analisis permitiendo que otros procesos puedan analizar
    private function unlockRunning() {
        $this->transforming = false;
        if ($this->running()) {
            unlink('transforming.lock');
        }
    }

}
