<?php 
defined("NODIRECT") or die;
//LIBRERIAS
include_once "./jboConf.php"; 
include_once './lib/jboConection.php';
include_once "./lib/jboTools.php";
include_once "./lib/jboController.php";
include_once "./lib/jboTemplate.php";
include_once './lib/jboModel.php'; 
include_once './lib/jboConverter.php';
include_once './lib/jboPrintableDocumentGenerator.php';
include_once './lib/jboClientBlocker.php';
include_once './lib/jboCancelerHelper.php';
include_once './lib/jboCupsController.php';
include_once './lib/jboMosaicTools.php';

//CONTROLADORES
include_once './controllers/managerController.php';
//MODELOS
include_once './models/jboManagerModel.php';
include_once './models/jboJobModel.php';
include_once './models/jboDocumentModel.php';
include_once './models/jboPaperPricesModel.php';
include_once './models/jboPaperFormatsModel.php';
include_once './models/jboDefaultsModel.php';
include_once './models/jboClientModel.php';
include_once './models/jboPrinterModel.php';
include_once './models/jboBindingModel.php';
include_once './models/jboFileFormatsModel.php';
include_once './models/jboImageMosaic.php';
include_once './models/jboImageMosaicModel.php';
