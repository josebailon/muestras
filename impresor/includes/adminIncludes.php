<?php 
defined("NODIRECT") or die;
//LIBRERIAS

include_once "./jboConf.php"; 
include_once './lib/jboConection.php';
include_once "./lib/jboTools.php";
include_once "./lib/jboController.php";
include_once "./lib/jboTemplate.php";
include_once './lib/jboModel.php';
include_once './lib/jboClientBlocker.php';
//CONTROLADORES
include_once './controllers/jboAdminController.php';

//MODELOS
include_once './models/jboUser.php';
include_once './models/jboUserModel.php';
include_once './models/jboJobModel.php';
include_once './models/jboDocumentModel.php';
include_once './models/jboPaperPricesModel.php';
include_once './models/jboDefault.php';
include_once './models/jboDefaultsModel.php';
include_once './models/jboPrinter.php';
include_once './models/jboPrinterModel.php'; 
include_once './models/jboBindingModel.php';
include_once './models/jboFileFormatsModel.php';
include_once './models/jboPaperFormat.php';
include_once './models/jboPaperFormatsModel.php';
include_once './models/jboPrinterCapabilitie.php';
include_once './models/jboClientModel.php';

