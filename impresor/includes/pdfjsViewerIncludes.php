<?php 
defined("NODIRECT") or die;
//ajuste de cwd
chdir("../../../");

//LIBRERIAS
include_once "./jboConf.php"; 
include_once './lib/jboConection.php';
include_once "./lib/jboTools.php";
include_once './lib/jboMosaicTools.php';

//CONTROLADORES
include_once './lib/jboModel.php';
include_once './models/jboDocumentModel.php';
include_once './models/jboImageMosaic.php';
include_once './models/jboImageMosaicModel.php';
include_once './models/jboPaperFormatsModel.php';
