<?php
define( 'NODIRECT', true );
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
include_once "./includes/startManagerIncludes.php";
 

session_start();
//jboTools::regUserAgent();
$controller=new jboManagerController();
