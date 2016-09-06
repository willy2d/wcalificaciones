<?php

/* @var $this SiteController */

$this->pageTitle=Yii::app()->name. 'Wcalificaciones, sistema de consulta de calificaciones';
?>
<div class="site-index">

    <div class="jumbotron btn-warning">
        <h1>Bienvenido!</h1>

        <p class="lead">Wcalificaciones, sistema de consulta de calificaciones.</p>

      	<p>
		<form  action="<?php echo (Yii::app()->baseUrl);?>/site/search.do" method="post" id="search">
		<div class="form-group ">
		<input type="text" name="q" id="q" class="form-control" placeholder="Ingrese DNI a buscar" required="true" minlength="7" maxlength="8" pattern="[0-9]*" />
		</div>
		<button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-search"></span> Mostrar Calificaciones
</button>  </form></p>
		
    </div>
<div class="container-fluid">
 

    <div class="body-content center-block">
 
        <div class="row">
            <div class="col-lg-4 btn btn-danger">
                <h3>Facil</h3>

                <p>Ingrese su DNI para consultar sus calificativos</p>

                <p><a class="btn btn-default" href="<?php echo Yii::app()->baseUrl; ?>/site/page.do?view=about">Mas informacion &raquo;</a></p>
            </div>
            <div class="col-lg-4 btn btn-success">
                <h3>Seguro</h3>

                <p>La informacion es actualizada constantemente</p>

                <p><a class="btn btn-default" href="<?php echo Yii::app()->baseUrl; ?>/site/page.do?view=about">Mas informacion &raquo;</a></p>
            </div>
            <div class="col-lg-4 btn btn-primary">
                <h3>Multiplataforma</h3>

                <p>Utilice cualquiera de sus dispositivos favoritos.</p>

                <p><a class="btn btn-default" href="<?php echo Yii::app()->baseUrl; ?>/site/page.do?view=about">Mas informacion &raquo;</a></p>
            </div>
        </div>

    </div>
	</div>
</div>