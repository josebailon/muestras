<?php
defined("NODIRECT") or die;

Class clientTemplate extends jboTemplate {

 


public function __construct(){
 
	parent::__construct();
	$this->setTemplateName("clientTemplate");
	$this->pageTitle="Impresor Cliente";
}



}