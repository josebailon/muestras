<?php
define( 'NODIRECT', true );
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once "./includes/adminIncludes.php";



session_start();

//jboTools::regUserAgent();
$controller=new jboAdminController();
