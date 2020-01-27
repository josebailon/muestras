<?php

defined("NODIRECT") or die;
define("D_S", DIRECTORY_SEPARATOR);

class jboConf {

    public static $dbHost = "localhost";

    public static $dbName = "/media/sf_disc/impresor/db.db"; 

    public static $dbUser = "root";
    public static $dbPassword = "root";
    public static $tablePrefix = "impr_";

    public static $rootRealPath = "/media/sf_disc/impresor/html/";
    public static $jobsPath = "/media/sf_disc/jobs/";
    public static $iconFolder = '/docIcons/'; //folder bajo el root de la web

    public static $iconRealPath = "/media/sf_disc/impresor/html/docIcons/";
    public static $phpPath = "php";
    public static $xpdfPath = "";
    public static $imPath = ""; //imagemagic path
    public static $selectorServerFiles = array("wtransformer.docm", "pptransformer.pptm", "WImpresor.exe", "PImpresor.exe");
    
    public static $shutDownServerWorkingDirectory = "/var/virtual/";
    public static $shutDownServerPath = "sh /var/virtual/apagar.sh";
    public static $restartServerWorkingDirectory = "/var/virtual/";
    public static $restartServerPath = "sh /var/virtual/reiniciar.sh";
    
    public static $margin = "14.17"; //en puntos, el marge a aplicar
    
    public static $ptXmm=2.83465; //puntos que hay en cada milimetro
    public static $ptXcm=28.3465; //puntos que hay en cada centimetro
    
    public static $analyzeFormatErrorMargin=5.669;//error permitido en el calculo del formato en puntos tipograficos(5.669 es 2 mm)
    //timeoutsqlite
    public static $busyTimeout = 5000;
    
    //log de fuentes
    public static $fontLogActive = false;//true para que este activo el analisis de fuentes 
                                       //false para que no se guarde el analisis de fuentes

    public static $fontLogPath = "/media/sf_disc/";//ruta donde se guarda el log de fuentes y los archivos originales
//    public static $fontLogPath = "/var/www/";//ruta donde se guarda el log de fuentes y los archivos originales

    public static $fontLogName = "fuentescambiadas.txt";//nombre del archivo que almacena un registro de jueges sustituidas
    public static $fontEmbedMethod = "cairo"; //"gs" para el modo antiguo "cairo" para el modo pdftocairo
    //fuentes para GS
    public static $gsFonMapPath="/media/sf_disc/fuentes/Fontmap.GS";
//    public static $gsFonMapPath="/var/www/fuentes/Fontmap.GS";
     
    public static $iccPath="/media/sf_disc/icc/";//ruta donde esta el icc
//    public static $iccPath="/var/www/icc/";//ruta donde esta el icc
    //activar los parones para debug
    public static $debugAnalysis = false;
    public static $debugPrepare = false;
    public static $debugPrintQueue = false;
    public static $debugPostPqStatus = false;
    public static $debugDeleteAfterPrint = false; 
    public static $debugTransform = false; 
    public static $debugPreview = false;
    
    public static $adminPassword="";
} 