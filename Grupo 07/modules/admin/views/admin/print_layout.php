<?php
use Mix\Application;
$ui = Application::getDefault()->getUiManager();
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="pt_BR" class="no-js">
	<!--<![endif]-->
	<!-- start: HEAD -->
	<head>
        <title><?=$ui->getPageTitle()?> | <?=$ui->getProjectName()?></title>
		<!-- start: META -->
		<meta charset="utf-8" />
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="" name="description" />
		<meta content="" name="author" />
        <meta name="robots" content="noindex, nofollow" />
		<!-- end: META -->
		<!-- start: MAIN CSS -->
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/plugins/bootstrap/css/bootstrap.min.css')?>">
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/plugins/font-awesome/css/font-awesome.min.css')?>">
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/fonts/style.css')?>">
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/css/main.css')?>">
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/css/main-responsive.css')?>">
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/plugins/iCheck/skins/all.css')?>">
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css')?>">
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/plugins/bootstrap-datepicker/css/datepicker.css')?>">
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')?>">
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/css/theme_light.css" type="text/css" id="skin_color')?>">		
		<link rel="stylesheet" href="<?=$this->moduleAsset('admin', 'assets/css/print.css')?>" type="text/css" media="print"/>
		<link rel="stylesheet" href="<?=$this->url('/assets/plugins/form-validation/css/formValidation.css')?>" type="text/css" />
		<!--[if IE 7]>
		<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome-ie7.min.css">
		<![endif]-->
		<!-- end: MAIN CSS -->		
		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
		<link rel="shortcut icon" href="favicon.ico" />
	</head>
	<!-- end: HEAD -->
	<!-- start: BODY -->
	<body>
		<?=$this->content()?>
        <script type="text/javascript">
            window.print();
        </script>
	</body>
	<!-- end: BODY -->
</html>
