<?php
// setup versions
$cs = Yii::app()->clientScript;
$themePath = Yii::app()->theme->baseUrl;

/**
* StyleSHeets
*/
$cs->registerCssFile($themePath . '/assets/css/bootstrap.css');
$cs->registerCssFile($themePath . '/assets/css/bootstrap-theme.css');

/**
* JavaScripts
*/
$cs->registerCoreScript('jquery', CClientScript::POS_END);
$cs->registerCoreScript('jquery.ui', CClientScript::POS_END);
$cs->registerScriptFile($themePath . '/assets/js/bootstrap.min.js', CClientScript::POS_END);
$cs->scriptMap["jquery.js"] =  Yii::app()->theme->baseUrl . "/assets/js/jquery-1.9.1.js";
$cs->registerScript('tooltip', "$('[data-toggle=\"tooltip\"]').tooltip();$('[data-toggle=\"popover\"]').tooltip()", CClientScript::POS_READY);
?>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="<?php
echo Yii::app()->theme->baseUrl . '/assets/js/html5shiv.js';
?>"></script>
<script src="<?php
echo Yii::app()->theme->baseUrl . '/assets/js/respond.min.js';
?>"></script>
<![endif]-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Willy Delgado, wcalificaciones">
    <meta name="author" content="Willy Delgado">
    <!-- Javascript -->
    <script>var baseUrl = "<?php echo Yii::app()->baseUrl; ?>";	</script>
    <!-- NOTE: Yii uses this title element for its asset manager, so keep it last -->
	<!-- blueprint CSS framework -->

 <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/src/css/960.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/src/css/screen.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/src/css/print.css" type="text/css" media="print" />
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/src/css/print-preview.css" type="text/css" media="screen">
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/src/jquery.tools.min.js"></script>
    <script src="<?php echo Yii::app()->theme->baseUrl; ?>/src/jquery.print-preview.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
$('a.print-preview').printPreview();
});
/*]]>*/
</script>
		<!-- blueprint CSS framework -->
			<!-- Willy Delgado -->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
    <div class="container">
	
        <nav class="navbar navbar-default" role="navigation">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Menu</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                <a class="navbar-brand" href="<?php echo Yii::app()->baseUrl; ?>"><?php echo Yii::app()->name; ?></a>
            </div>
<a href="<?php echo (Yii::app()->baseUrl);?>"><?php echo CHtml::image(Yii::app()->baseUrl."/images/vhd.png");?></a>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
			
                <!-- Main nav -->
                <?php $this->widget('zii.widgets.CMenu',array(
                    'htmlOptions'=>array('class'=>'nav navbar-nav'),
                    'items'=>array(
					array('label'=>'Inicio', 'url'=>array('/')),
                        array('label'=>'Informacion', 'url'=>array('/site/page', 'view'=>'about')),
                       ),
                )); ?>

                <!-- Right nav -->
				<div class="nav navbar-nav pull-right">
				<form class="form-inline" action="<?php echo (Yii::app()->baseUrl);?>/site/search.do" method="post" id="search">
              <div class="form-group"> <input size="8" maxlength="8" name="q" id="q" type="text" class="form-control" placeholder="Ingrese DNI" required="true" minlength="7" maxlength="8" pattern="[0-9]*"/></div>
		<div class="form-group"> <input type="hidden" name="usr" id="usr" value="<?php echo Yii::app()->request->userHostAddress;?>"/></div>
			        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span> Buscar
</button>
      </form></div>
                   </div><!-- /.navbar-collapse -->
        </nav>
    </div>

    <div class="container">
              <?php if(isset($this->breadcrumbs)):?>
            <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                'links'=>$this->breadcrumbs,
            )); ?>
        <?php endif?>

        <div id="main-content">

            <?php if (!$this->menu): ?>

                <div class="row">
                    <div class="col-lg-12">
                        <?php echo $content; ?>
                    </div>
                </div>

            <?php else: ?>

                <div class="row">
                    <div class="col-lg-10">
                        <?php echo $content; ?>
                    </div>

                    <div class="col-lg-2">
                        <div class="panel panel-info">
                            <div class="panel-heading">Opciones</div>
                                <?php
                                $this->widget('zii.widgets.CMenu', array(
                                    'items'=>$this->menu,
                                    'htmlOptions'=>array('class'=>'nav nav-pills nav-stacked'),
                                ));
                                ?>
                        </div>
                    </div>
                </div>

            <?php endif; ?>


        </div> <!-- /#main-content -->

        <hr>

        <footer >
            <p class="btn-default"><?php echo base64_decode("wqkgV2lsbHkgRGVsZ2Fkbw==");?>
                  </p>
		</footer>

    </div> <!-- /.container -->
	<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-51300245-2', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
