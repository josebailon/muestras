<?php
defined("NODIRECT") or die;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $this->pageTitle;?></title>
<script src="./js/jquery-3.1.0.min.js"></script>
<link rel="icon" type="image/png" href="<?php echo $this->templatePath;?>/css/images/favicon.png" />

<link rel="stylesheet" href="./js/bootstrap-3.3.7-dist/css/bootstrap.min.css" crossorigin="anonymous" />
<link rel="stylesheet" href="./js/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" crossorigin="anonymous" />
<script src="./js/bootstrap-3.3.7-dist//js/bootstrap.min.js"  crossorigin="anonymous"></script>
<script src="./js/bootstrap-touchspin-master/jquery.bootstrap-touchspin.js"></script>
<link href="./js/bootstrap-touchspin-master/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" media="all" />
<script src="./js/bootstrap-select-1.11.2/bootstrap-select.min.js"></script>
<link href="./js/bootstrap-select-1.11.2/bootstrap-select.min.css?a=1" rel="stylesheet" type="text/css" media="all" />

<link href="./js/bootstrap-toggle-master/bootstrap-toggle.min.css" rel="stylesheet">
<script src="./js/bootstrap-toggle-master/bootstrap-toggle.min.js"></script>


<link rel="stylesheet" type="text/css" href="./<?php echo $this->templatePath;?>/css/style.css" />
 

<?php echo $this->headerHtml;?>
</head>

<body>