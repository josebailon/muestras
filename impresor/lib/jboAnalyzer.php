<?php

defined("NODIRECT") or die;

/**
 * Description of jboAnalyzer
 *
 * @author Jose
 */
class jboAnalyzer {

    private $documentM; //modelo de documentos
    private $currentDocument; //documento con el que trabajar actualmente
    public $analyzing; //almacena si se esta analizando actualmente
    private $mmperpoint = 0.3527;
    private $analysisPerformer;
    function __construct($autoStart = true) {
        print "Constructor del analyzer" . PHP_EOL;

        $this->documentM = new jboDocumentModel(false);
        $this->analysisPerformer = new jboAnalysisPerformer();
        if ($autoStart && !$this->running()) {

            $this->init();
        }
    }

    public function init() {
        $this->lockRunning();
        while ($this->analyzing) {
            if ($this->getNextDocument()) {
                $this->performAnalysis();
                $this->saveAnalysis();
            } else {
                //chequeamos si hay algo aún sin analizar
                if ($this->documentM->needToAnalize()){
                    sleep(1);//esperamos 1 segundo y seguimos el bucle   
                }else{
                $this->unlockRunning();
                }
            }
        }
    }

    //recoge un nuevo documento para analizar. Si no hay ninguno para analizar devuelve false.
    private function getNextDocument() {
        print "RECOGIENDO NUEVO DOCUMENTO<br>" . PHP_EOL;
        $this->documentM->conect();

        $nextDocument = $this->documentM->getFirstNotAnalized();

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
    public function performAnalysis() {
        $docpath= jboConf::$jobsPath . $this->currentDocument->idJob . D_S . $this->currentDocument->tempNameWithExtension;
        //INICIA EL ANALISIS 
        $currentAnalysis=$this->analysisPerformer->analyze($docpath,$this->currentDocument->name);
        
        if ($currentAnalysis['fail']){
            $this->createFailAnalysis($currentAnalysis);         
        }else{
            var_dump($currentAnalysis);
            $this->currentDocument->errorMessage = $currentAnalysis['errorMessage'];
            $this->currentDocument->nPages=$currentAnalysis['nPages'];
            $this->currentDocument->printingPages=$currentAnalysis['printingPages'];
            $this->currentDocument->originalFormat=$currentAnalysis['originalFormat'];
            $this->currentDocument->printFormat=$currentAnalysis['printFormat'];
            $this->currentDocument->width=$currentAnalysis['width'];
            $this->currentDocument->height=$currentAnalysis['height'];
            $this->currentDocument->isForm=$currentAnalysis['isForm'];
            $this->currentDocument->analyzed=$currentAnalysis['analyzed'];   
            $this->currentDocument->warningMessage=$currentAnalysis['warningMessage'];   
            $this->currentDocument->pagesAnalysis=$currentAnalysis['pagesAnalysis'];   
        }      
    }


    private function createFailAnalysis($currentAnalysis) {
        $this->currentDocument->errorMessage = $currentAnalysis['errorMessage'];
        $this->currentDocument->analyzed = true;
        $this->currentDocument->errorOnAnalyze = true;
    }


    //guarda la informacion del analisis
    private function saveAnalysis() {
        var_dump($this->currentDocument);
        $this->documentM->conect();

        $this->documentM->updateDocument($this->currentDocument);

        $this->documentM->disconect();
    }

    //devuelve true si ya hay un analizador funcionando
    private function running() {
        return file_exists('analizing.lock');
    }

    //se apropia de la cola de analisis bloqueando que otros procesos puedan analizar
    private function lockRunning() {
        //bloqueamos la transformacion 
        $this->analyzing = true;
        $f = fopen("analizing.lock", "w") or die("Unable to open file!");
        fclose($f);
    }

    //desbloquea la cola de analisis permitiendo que otros procesos puedan analizar
    private function unlockRunning() {
        $this->analyzing = false;
        if ($this->running()) {
            unlink('analizing.lock');
        }
    }

}
