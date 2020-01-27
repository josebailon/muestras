<?php
define( 'NODIRECT', true );

include_once "./includes/prepareIncludes.php";


    $printercode= $argv[1];   
    
    print "iniciar preparer de ".$printercode."\n";

$preparer=new jboPreparer($printercode);
sleep(20);
 //exit;