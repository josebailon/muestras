<?php 
defined("NODIRECT") or die;
//LIBRERIAS
include_once "./jboConf.php"; 
include_once './lib/jboConection.php';
include_once "./lib/jboTools.php";
include_once './lib/jboModel.php';
include_once './lib/jboPrintSender.php';
include_once './lib/jboPrinterQueueManager.php';
include_once './lib/jboCupsController.php';
include_once './lib/jboCancelerHelper.php';

//include_once './lib/jboExecAsync.php';

//MODELOS
include_once './models/jboJobModel.php';
include_once './models/jboDocumentModel.php';
include_once './models/jboPrinterModel.php';
include_once './models/jboDefaultsModel.php';

include_once "./includes/tcpdfConfig.php";
include_once './lib/jboPrintJobSummaryGenerator.php';
