<?php
/* @var $this AlumnosController */
/* @var $model Alumnos */
$hora= date("h:i:s");
$fecha= date("j/n/Y");
$generapdf = Yii::createComponent('application.vendors.mpdf.mpdf');
$html = '<link rel="stylesheet" type="text/css" href="'.Yii::app()->theme->baseUrl.'/css/layout.css" />';
 $html.= '<div class="view" style="width:600px;background-color: #FFF;" align="center">  <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#999999" style="font-size:11px;background-color: #FFF;">
 <tr><td colspan="23" align="left" valign="top" style="border:none;"><img src="'.Yii::app()->request->baseUrl.'/images/minedu.png"></img></td>
 </tr> <tr><td colspan="11" style="border:none;">Unidad de Gestion Educativa Local</td>
<th colspan="4" bgcolor="#CCCCCC">REPORTE</th><td colspan="8">2016 - I TRIMESTRE</td>
 </tr> <tr><td colspan="23" style="border:none;">&nbsp;</td>
 </tr> <tr><th colspan="3" rowspan="2" bgcolor="#CCCCCC" >Nombre de la Instituci&oacute;n</th>
<td colspan="7" rowspan="2" >I.E.WILLY DELGADO</td>
<th colspan="2" bgcolor="#CCCCCC">DRE</th><td colspan="11">CAJAMARCA</td>
 </tr> <tr>
<th colspan="2" bgcolor="#CCCCCC">UGEL</th>
<td colspan="21">CHOTA</td> </tr>
 <tr>
<th colspan="3" bgcolor="#CCCCCC">C&oacute;digo Modular</th>
<th colspan="3" bgcolor="#CCCCCC" >Denominaci&oacute;n</th>
<th  colspan="3" bgcolor="#CCCCCC" >Gesti&oacute;n</th>
<th  colspan="3" bgcolor="#CCCCCC" >Direcci&oacute;n</th>
<td colspan="11" >JR. CAJAMARCA</td>
 </tr>
 <tr >
<td colspan="3">1234567</td>
<td colspan="3" >I.E. SECUNDARIA</td>
<td colspan="3">PUBLICO</td>
<th colspan="3"bgcolor="#CCCCCC" >Provincia</th>
<td colspan="3" >CAJAMARCA</td>
<th colspan="4" bgcolor="#CCCCCC">Distrito</th>
<td colspan="4">CAJAMARCA</td>
 </tr>
 <tr>
<td height="1" colspan="23" style="border:none;">&nbsp;</td>
 </tr>
 <tr>
<th colspan="3" bgcolor="#CCCCCC">Situaci&oacute;n</th>
<td colspan="9">Matriculado,Areas Desaprobadas:  '.$model->areasdesaprobadas.'</td><td colspan="7" bgcolor="#CCCCCC"><strong>Secci&oacute;n</strong></td>
<td colspan="4">&nbsp;'.$model->grado.'</td>
 </tr>
 <tr>
<td colspan="23" style="border:none;">&nbsp;</td>
 </tr>
 <tr>
<th colspan="2" bgcolor="#CCCCCC">N&deg;</th>
<th colspan="3" bgcolor="#CCCCCC"><center>
  N&deg; de matr&iacute;cula<br />
  (8 d&iacute;gitos del DNI)</center></th>
<th colspan="18" bgcolor="#CCCCCC">
<center>APELLIDOS Y NOMBRES<br />
  (Por orden alfab&eacute;tico)</center></th>
 </tr>
 <tr>
<td colspan="2" >&nbsp;'.$model->orden.'</td>
<td colspan="3">&nbsp;'.$model->codalumno.'</td>
<td colspan="18">&nbsp;'.$model->apellidosnombres.'</td>
 </tr>
 <tr>
<td colspan="23" style="border:none;">&nbsp;</td>
 </tr>
 <tr>
<th bordercolor="#666666" bgcolor="#CCCCCC">N&deg;</th>
<th colspan="9" bordercolor="#666666" bgcolor="#CCCCCC">&Aacute;REAS</th>
<th bordercolor="#666666" bgcolor="#CCCCCC">I</th>
<th bordercolor="#666666" bgcolor="#CCCCCC">II</th>
<th bordercolor="#666666" bgcolor="#CCCCCC">III</th>
<th bordercolor="#666666" bgcolor="#CCCCCC">P.F</th>
<th colspan="9" bordercolor="#666666" bgcolor="#CCCCCC">Observaci&oacute;n</th>
 </tr>
 
  <tr>
  <td bordercolor="#666666">1</td>
<td colspan="9" bordercolor="#666666">ARTE</td>
<td bordercolor="#666666">&nbsp;'.$model->arte.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr>
 
  <tr>
  <td bordercolor="#666666">2</td>
  <td colspan="9" bordercolor="#666666">CIENCIA, TECNOLOGIA Y AMBIENTE</td>
<td bordercolor="#666666">&nbsp;'.$model->cta.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr>
  
  <tr>
  <td bordercolor="#666666">3</td>
  <td colspan="9" bordercolor="#666666">COMUNICACION</td>
  <td bordercolor="#666666">&nbsp;'.$model->comu.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
  <tr>
  <td bordercolor="#666666">4</td>
  <td colspan="9" bordercolor="#666666">EDUCACION FISICA</td>
<td bordercolor="#666666">&nbsp;'.$model->efis.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
  <tr>
  <td bordercolor="#666666">5</td>
  <td colspan="9" bordercolor="#666666">EDUCACION PARA EL TRABAJO</td>
<td bordercolor="#666666">&nbsp;'.$model->etra.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
  <tr>
  <td bordercolor="#666666">6</td>
<td colspan="9" bordercolor="#666666">EDUCACION RELIGIOSA</td>
<td bordercolor="#666666">&nbsp;'.$model->erel.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
  <tr>
  <td bordercolor="#666666">7</td>
<td colspan="9" bordercolor="#666666">FORMACION CIUDADANA Y CIVICA</td>
<td bordercolor="#666666">&nbsp;'.$model->fcc.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
  <tr>
  <td bordercolor="#666666">8</td>
<td colspan="9" bordercolor="#666666">HISTORIA, GEOGRAFIA Y ECONOMIA</td>
<td bordercolor="#666666">&nbsp;'.$model->hge.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
  <tr>
  <td bordercolor="#666666">9</td>
<td colspan="9" bordercolor="#666666">INGLES</td>
<td bordercolor="#666666">&nbsp;'.$model->ingl.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
  <tr>
  <td bordercolor="#666666">10</td>
<td colspan="9" bordercolor="#666666">MATEMATICA</td>
<td bordercolor="#666666">&nbsp;'.$model->mate.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
  <tr>
  <td bordercolor="#666666">11</td>
<td colspan="9" bordercolor="#666666">PERSONA, FAMILIA Y RELACIONES HUMANAS</td>
<td bordercolor="#666666">&nbsp;'.$model->pfrrhh.'</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
  <tr>
  <td bordercolor="#666666">12</td>
<td colspan="9" bordercolor="#666666">COMPORTAMIENTO</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td bordercolor="#666666">&nbsp;</td>
<td colspan="9" bordercolor="#666666">&nbsp;</td>
 </tr> 
 
  <tr>
<td height="49" colspan="7" style="border:none;">&nbsp;</td>
<td colspan="16" style="border:none;">&nbsp;</td>
 </tr>
 <tr>
<td colspan="7" style="border:none;"><center>
  <strong>____________________________  </strong>
</center></td>
<td colspan="16" style="border:none;"><center>
  <strong>____________________________  </strong>
</center></td>
 </tr>
 <tr>
<td colspan="7" style="border:none;"><center>
  DIRECTOR<br />
Firma, Post Firma y Sello
</center></td>
<td colspan="16" style="border:none;"><center>
  COORDINADOR
<br />
  Firma, Post Firma y Sello
</center></td>
 </tr>
 <tr>
   <td colspan="7" style="border:none;">&nbsp;</td>
   <td colspan="16" style="border:none;">&nbsp;</td>
 </tr>
 <tr>
   <td  colspan="7" style="border:none;">&nbsp;</td>
   <td colspan="16" style="border:none;">&nbsp;</td>
 </tr>
 <tr>
   <td colspan="3" style="border:none;">Recomendacion:</td>
   <td colspan="20" style="border:none;">&nbsp;'.$model->recomendacion.'</td>
 </tr>
  </table>
</div>
<span>* La informacion presentada es referencial de caracter informativo - Impreso el: </br> '.$fecha.'-'.$hora.base64_decode("wqkgV2lsbHkgRGVsZ2Fkbw=="). '</span>';
$mpdf = new mPDF('c','A4','','',32,25,27,25,16,13); 
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output('reporte-'.$model->codalumno.'pdf','I','S');
exit;
?>