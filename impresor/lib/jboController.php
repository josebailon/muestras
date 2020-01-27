<?php
defined("NODIRECT") or die;

class jboController{

protected $template=null;
protected $section=null;
protected $subsection=null;
protected $func=null;//function

public function __construct(){
	$this->section=jboTools::getGetVar("sec");
	$this->subsection=jboTools::getGetVar("subsec");
	$this->func=jboTools::getGetVar("func");
 
}

public function setTemplate($name){
include_once "templates/".$name."/".$name.".php";
$this->template = new $name();
}




}