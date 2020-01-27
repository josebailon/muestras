<?php
define( 'NODIRECT', true );

include_once "./includes/printerQueueManagerIncludes.php";
       

    $printercode= $argv[1];   
    
    print "iniciar printer queue manager de ".$printercode."\n";

     $printerQueueManager= new jboPrinterQueueManager($printercode);
 
exit;