<?php
defined("NODIRECT") or die;

Class adminTemplate extends jboTemplate {

 


public function __construct(){
 
	parent::__construct();
	$this->setTemplateName("adminTemplate");
	$this->pageTitle="Impresor Admin";
}



}