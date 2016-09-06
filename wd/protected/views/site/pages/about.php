<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . 'Wcalificaciones - Informacion';
$this->breadcrumbs=array(
	'Informacion',
);
?>
  <div class="alert btn-primary">
              <p class="lead">Bienvenido!-WCalificaciones, sistema de consulta de calificaciones.</p>

      	<p>
		<form  action="<?php echo (Yii::app()->baseUrl);?>/site/search.do" method="post" id="search">
		<div class="form-group">
		<input type="text" name="q" id="q" class="form-control" placeholder="Ingrese DNI a buscar" required="true" minlength="7" maxlength="8" pattern="[0-9]*" />
		</div>
		<button type="submit" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-search"></span> Mostrar Calificaciones
</button>  </form></p>

<p><strong>¿Qué es  mi WCalificaciones?</strong></p>
<p>Es un proyecto de publicación vía web de las calificaciones de todos los estudiantes, el cual permite realizar consultas de notas de manera rápida, ágil sin ningún costo, facilitando al estudiante mantener un mayor control sobre sus áreas académicas.<br />
</p>
<p><strong>¿Qué busca mi WCalificaciones?</strong></p>
<p>
 Facilitar el acceso a la información de las notas por trimestre académico.
 </p>
<p><strong>¿Cómo ingresar a mi MiVHD Notas?</strong><br />
  <br />
  Para ingresar a “CONSULTA DE NOTAS”,se necesita un smartphone, tablet o computadora con acceso a internet.
<br /> Para ingresar, debe  escribirse en la barra de dirección del browser
(software de navegación, por  ejemplo: Internet Explorer, Google Chrome, Mozilla, Safari, Opera...) la  dirección url:
&ldquo;<a href="http://mivhd.byethost5.com/" target="_blank">http://mivhd.byethost5.com/</a>&rdquo;. </p>
<p><br />
  <strong>¿Cuál es mi usuario en  mi WCalificaciones?</strong></p>
  <p>El usuario es su número de DNI</p>
    </div>