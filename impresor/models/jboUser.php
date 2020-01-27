<?php

class jboUser{
    public $id;
    public $name;
     public $selectPrinter;
    public $notes;
    public $localPath;
    public $client;
    public $manager;
    public $hardwareId;
    public $transformTimeOutPerFile;
    public $autoAcceptPrintConditions;
    public $whatsapp;
    public $telegram;
    public $addFileDefaultPath;
    public function __construct($id=-1) {
        $this->id = $id;
        $this->name = "";
         $this->selectPrinter = true;
        $this->notes = jboTools::getPostVar('notes', '');
        $this->localPath = "";
        $this->client = true;
        $this->manager = true;
        $this->hardwareId = "";
        $this->transformTimeOutPerFile = 60;
        $this->autoAcceptPrintConditions = true;
        $this->whatsapp = false;
        $this->telegram = false;
        $this->addFileDefaultPath="";
    }
}