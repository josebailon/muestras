<?php
 
class jboPaperFormat{
    public $code;
    public $name;
    public $w;
    public $h;
    public $defaultPrinterColorSingle;
    public $defaultPrinterColorDouble;
    public $defaultPrinterBnSingle;
    public $defaultPrinterBnDouble;
    
    public function __construct($code="",$name="",$w=0.0,$h=0.0,$defaultPrinterColorSingle="",$defaultPrinterColorDouble="",$defaultPrinterBnSingle="",$defaultPrinterBnDouble="") {
        $this->code = $code;
        $this->name = $name;
        $this->w = $w;
        $this->h = $h;
        $this->defaultPrinterColorSingle=$defaultPrinterColorSingle;
        $this->defaultPrinterColorDouble=$defaultPrinterColorDouble;
        $this->defaultPrinterBnSingle=$defaultPrinterBnSingle;
        $this->defaultPrinterBnDouble=$defaultPrinterBnDouble;
        
    }
}