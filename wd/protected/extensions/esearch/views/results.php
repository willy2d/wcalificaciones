<div class="jumbotron">
<?php if($dataProvider): ?>
<div class="alert alert-warning"><?php echo Yii::t('app', 'Resultados para "{query}"', array('{query}'=>CHtml::encode($query))) ?></div>
<?php 
 $this->widget('zii.widgets.grid.CGridView', array(
    'id' =>'nombre-grid', 
    'dataProvider'=>$dataProvider,
    'columns'=>array(
       	'apellidosnombres',
		         array(
				 'header'=>'Calificaciones',
            'class' => 'CButtonColumn',
            'template'=>'{accion_nueva}', 
                 'buttons'=>array(
		'accion_nueva' => array( 
			    'label'=>'Mostar Calificaciones', 
		    'imageUrl'=>Yii::app()->theme->baseUrl.'/src/images/icon-print.png',
		    'url'=>'Yii::app()->createUrl("/site/$data->codalumno" )', 
		    ),
		),
          ),
      ),
 ));
?>
<?php else: ?>
	<?php echo Yii::t('app', 'No hay resultados, ingrese su numero de DNI'); ?>
<?php endif ?>
</div>