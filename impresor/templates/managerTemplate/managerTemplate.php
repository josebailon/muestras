<?php
defined("NODIRECT") or die;

Class managerTemplate extends jboTemplate {

 


public function __construct(){
 
	parent::__construct();
	$this->setTemplateName("managerTemplate");
	$this->pageTitle="Impresor Manager";
}



}