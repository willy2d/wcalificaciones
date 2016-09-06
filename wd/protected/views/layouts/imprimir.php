<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="es" />
	
	<?PHP
// setup versions
$cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;

/**
* StyleSHeets
*/
$cs->registerCssFile($themePath . '/assets/css/bootstrap.css');
$cs->registerCssFile($themePath . '/assets/css/bootstrap-theme.css');
?>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<!-- blueprint CSS framework -->
 <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/src/css/960.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/src/css/print.css" type="text/css" media="print" />
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/src/css/print-preview.css" type="text/css" media="screen">
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/src/jquery.tools.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/src/jquery.print-preview.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
$('a.print-preview').printPreview();
$.printPreview.loadPrintPreview();
});
/*]]>*/
</script>
	<title><?php echo "Reporte de Notas"; ?></title>
	
</head>

<body>
<center>
 <div class="alert alert-success" role="alert">
<span class="glyphicon glyphicon-remove" aria-hidden="true"></span> <span class="glyphicon-class"><a  onclick="window.close();" >CERRAR VENTANA </a></span>
 <span class="glyphicon glyphicon-print" aria-hidden="true"></span> <span class="glyphicon-class"><a  class="print-preview" >IMPRIMIR REPORTE</a></span> 
</div>


<?php echo $content; ?>

   <footer >
            <p class="btn-default">
             <a class="print-preview"><?php echo base64_decode("wqkgV2lsbHkgRGVsZ2Fkbw=="); ?></a>
            </p>
		</footer>
		</center>
</body>
</html>
