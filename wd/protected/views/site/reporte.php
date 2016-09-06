<?php
/* @var $this AlumnosController */
/* @var $model Alumnos */
$generapdf = Yii::createComponent('application.vendors.MPDF.mpdf');
$nn=1;
$totales=0;
$totalcred=0;
$totalpunt=1;
$totalpuntacu=1;
$i=0;
$html = '<link rel="stylesheet" type="text/css" href="'.Yii::app()->theme->baseUrl.'/css/layout.css" />';
 $html.= '<div style="width:600px; background-color: #FFF;" align="center"><table width="600px" border="1" cellpadding="0" cellspacing="0" align="center" style="font-size:11px;background-color: #FFF;"><tr><col width="24" /> <tr><td colspan="22" align="left" valign="top" style="border:none;"><img src="'.Yii::app()->request->baseUrl.'/images/minedu.png"></img></td></tr> <tr><td colspan="11" style="border:none;">Direcci&oacute;n General de Educaci&oacute;n Superior y T&eacute;cnico Profesional</td><th colspan="4" bgcolor="#CCCCCC">PROMOCI&Oacute;N</th><td colspan="7">2012</td> </tr> <tr><td colspan="22" style="border:none;">&nbsp;</td> </tr> <tr><th colspan="3" rowspan="2" bgcolor="#CCCCCC" >Nombre de la Instituci&oacute;n</th><td colspan="7" rowspan="2" >I.S.E. PUBLICO - NUESTRA SE&Ntilde;ORA DE CHOTA</td><th colspan="2" bgcolor="#CCCCCC">DRE</th><td colspan="10">CAJAMARCA</td> </tr> <tr><th colspan="2" bgcolor="#CCCCCC">UGEL</th><td colspan="10">CHOTA</td> </tr> <tr><th colspan="2" bgcolor="#CCCCCC">C&oacute;digo Modular</th><th colspan="2" bgcolor="#CCCCCC" >Denominaci&oacute;n</th><th width="94" bgcolor="#CCCCCC" >Gesti&oacute;n</th><th colspan="4" bgcolor="#CCCCCC" >D.S. / R.M. de Creaci&oacute;n y R.D. de Revalidaci&oacute;n</th><th width="50" bgcolor="#CCCCCC" >Direcci&oacute;n</th><td colspan="12" >JR. ATAHUALPA 151</td> </tr> <tr><td colspan="2">123456</td><td colspan="2" >IESEP</td><td >PUBLICO</td><td colspan="4">R.D.</td><th bgcolor="#CCCCCC" >Provincia</th><td colspan="4" >CHOTA</td><th colspan="3" bgcolor="#CCCCCC">Distrito</th><td colspan="5">CHOTA</td> </tr>
 <tr><td colspan="22" style="border:none;">&nbsp;</td> </tr> <tr><th colspan="3" bgcolor="#CCCCCC">Grado</th><td colspan="8">'.$model->especialidad.'&nbsp;</td><td colspan="2">&nbsp;</td><th colspan="6" bgcolor="#CCCCCC">Periodo <br /> Acad&eacute;mico</th><td width="64" colspan="3">&nbsp;'.$model->ciclo.'</td> </tr> <tr><th colspan="3" bgcolor="#CCCCCC">Resoluci&oacute;n de Autorizaci&oacute;n</th><td colspan="8">R.A. 123</td><td colspan="2">&nbsp;</td><th colspan="6" bgcolor="#CCCCCC">Semestre<br />Acad&eacute;mico</th><td colspan="3">&nbsp;'.$model->semestreact.'</td> </tr> <tr><td colspan="22" style="border:none;">&nbsp;</td> </tr> <tr><th width="69" bgcolor="#CCCCCC">N&deg;</th><th colspan="2" bgcolor="#CCCCCC"><center>  N&deg; de matr&iacute;cula<br />  (8 d&iacute;gitos del DNI)</center></th><th colspan="19" bgcolor="#CCCCCC"><center>APELLIDOS Y NOMBRES<br />  (Por orden alfab&eacute;tico)</center></th> </tr> <tr><td>1</td><td colspan="2">&nbsp;'.$model->codalumno.'</td><td colspan="19">&nbsp;'.$model->apellidosnombres.'</td> </tr> <tr><td colspan="22" style="border:none;">&nbsp;</td> </tr> <tr><th bgcolor="#CCCCCC">N&deg;</th><th colspan="8" bgcolor="#CCCCCC">&Aacute;REAS</th><th width="35" bgcolor="#CCCCCC">Cred.</th><th width="24" bgcolor="#CCCCCC">Nota</th><th width="41" bgcolor="#CCCCCC">Puntaje</th><th colspan="10" bgcolor="#CCCCCC">Observaci&oacute;n</th> </tr> <tr><td colspan="22">&nbsp;</td>';
 foreach($model->notases as $array)
  {
 $totales=$totales+$model->notases[$i]->nota;
$totalcred=$totalcred+$model->notases[$i]->creditos;
$totalpunt=$model->notases[$i]->nota*$model->notases[$i]->creditos;
$totalpuntacu=$totalpuntacu+$totalpunt;
$html.='<tr><td>'.$nn.'</td>
<td colspan="8">'.$model->notases[$i]->curso.'</td>
<td>&nbsp;'.$model->notases[$i]->creditos.'</td>
<td>'.$model->notases[$i]->nota.'</td>
<td>&nbsp;'.$totalpunt.'</td>
<td colspan="10">&nbsp;</td></tr>';
$nn++;
$i++;
 }
$html.='</tr><tr><td>&nbsp;</td><th colspan="8" bgcolor="#CCCCCC">TOTALES</th><td>&nbsp;'.$totalcred.'</td><td>&nbsp;'.$totales.'</td><td>&nbsp;'.$totalpuntacu.'</td><td colspan="10">&nbsp;</td> </tr> <tr><td height="49" colspan="7" style="border:none;">&nbsp;</td><td colspan="15" style="border:none;">&nbsp;</td> </tr> <tr><td colspan="7" style="border:none;"><center> <strong>____________________________  </strong></center></td><td colspan="15" style="border:none;"><center>  <strong>____________________________</strong></center></td> </tr> <tr><td colspan="7" style="border:none;"><center>ESTUDIANTE</center></td><td colspan="15" style="border:none;"><center>SECRETARIO ACAD&Eacute;MICO<br />  Firma, Post Firma y Sello</center></td> </tr></table></div>';

$mpdf = new mPDF('c','A4','','',32,25,27,25,16,13); 
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output('reporte-'.$model->codalumno.'pdf','I');
exit;
?>