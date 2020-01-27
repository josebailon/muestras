<?php
define( 'NODIRECT', true );

include_once "./includes/startClientIncludes.php";


session_start();

//jboTools::regUserAgent();
$controller=new jboClientController();
