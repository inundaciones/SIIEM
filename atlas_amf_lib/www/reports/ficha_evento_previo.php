<?php		
	require_once '../../config/dbconf.php';
	require_once '../../includes/aux.php';
	
	//$_POST['ID']=3;
	
	include ('../../includes/pdf/class.ezpdf.php');
		
	
	$pdf =& new Cezpdf('LETTER');
	
	$pdf->selectFont('../../includes/pdf/fonts/Helvetica.afm');	
		
	$sql="select * from TBL_EVENTOS WHERE ID=".$_POST['ID'];
	$rs=mysql_query($sql);
	$row=mysql_fetch_object($rs);
	$pdf->ezColumnsStart(array('num'=>1,'gap'=>0));
	//_-----Marca de agua
	$pdf->addJpegFromFile('./logoCompromiso.jpg',175,220,260);
	
	///-----Header-------
	$user_type=toTextTipo($row->URI_USER);
	
	if($user_type=='A'){
		$pdf->addJpegFromFile('./headerRojo.jpg',0,710);
	}
	if($user_type=='T'){
		$pdf->addJpegFromFile('./headerGris.jpg',0,710);
	}
	if($user_type=='G'){
		$pdf->addJpegFromFile('./headerVerde.jpg',0,710);
	}
	if($user_type=='M'){
		$pdf->addJpegFromFile('./headerClaro.jpg',0,710);
	}
	if($user_type=='O'){
		$pdf->addJpegFromFile('./headerQuemado.jpg',0,710);
	}
	if($user_type==''){
		$pdf->addJpegFromFile('./headerQuemado.jpg',0,710);
	}
	
	//$pdf->ezImage('./headerVerde.jpg');
	//--------
	$pdf->setColor(1,1,1);
	$pdf->addText(24,780,8,"Fecha de modificación: ".formatFecha($row->LASTUPDATE));
	$pdf->addText(250,780,8,"Usuario: ".toTextLogin($row->URI_USER));
	$pdf->setColor(0,0,0);
	$pdf->ezText(" ",8);
	$pdf->ezText(" ",8);
	$pdf->ezText(" ",8);
	$pdf->ezText(" ",8);
	$pdf->ezText(" ",8);
	$pdf->ezText(" ",8);	
	$pdf->ezText("<b>Fecha de Evento:</b> ".formatFecha($row->FECHA) ,8);	
	$pdf->ezText("\n<b>Sitio:</b> ".toTextSitio($row->ID_SITIO) ,8);
	$pdf->ezText("\n<b>Colonia:</b> ".$row->COLONIA ,8);
	$pdf->ezText("\n<b>Evento:</b> ".toTextEvento($row->ID_EVENTO) ,8);
	//----------
	$pdf->ezText("\n<b>Limite Norte:</b> ".$row->L_N,8);
	$pdf->ezText("\n<b>Limite Sur:</b> ".$row->L_S,8);
	$pdf->ezText("\n<b>Limite Oriente:</b> ".$row->L_O,8);
	$pdf->ezText("\n<b>Limite Poniente:</b> ".$row->L_P,8);
	//----------
	$pdf->ezText("\n<b>Superficie Afectada:</b> ".$row->SUPERFICIE."\t\t\t<b>Poblacion Afectada:</b> ".$row->POBLACION,8);
	$pdf->ezText("\n<b>Casa Habitacion:</b> ".$row->CASA."\t\t\t<b>Local Comercial:</b> ".$row->LOCAL,8);
	$pdf->ezText("<b>Edificio Industrial:</b> ".$row->INDUSTRIA."\t\t\t<b>Edificio Publico:</b> ".$row->PUBLICO,8);
	$pdf->ezText("<b>Otos:</b> ".$row->OTROS,8);
	
	//---------
	$pdf->ezText("\n<b>Descripcion:</b> ".$row->DESCRIPCION ,8);		
	
	$sql="SELECT * FROM EVENTOS_CAUSAS WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."'";
	$rs2=mysql_query($sql);
	$pdf->ezText("\n<b>Causas: </b>",8);
	while($row2=mysql_fetch_object($rs2)){
		$pdf->ezText("\t".toTextCausa($row2->ID_CAUSA)." ",8);
	}
	
	$tbl_options=array("width"=>450,"fontSize" => 8);
	$tbl_headings=array("ACCION"=>"ACCION","DEPENDENCIA"=>"DEPENDENCIA","COSTO"=>"COSTO");
	
	$sql="SELECT * FROM EVENTOS_ACCIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."' AND TIPO='E' AND TIPO2='A'";
	$rs2=mysql_query($sql);		
	$data = array();
	while($row2=mysql_fetch_object($rs2)){
		$data[]=array("ACCION"=>toTextAccion($row2->ID_ACCION),"DEPENDENCIA"=>toTextDependencia($row2->ID_DEPENDENCIA),"COSTO"=>$row2->COSTO);
	}
	$pdf->ezTable($data,$tbl_headings,"<i>Acciones Emergentes</i>",$tbl_options);
	
	//----
	
	$sql="SELECT * FROM EVENTOS_ACCIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."' AND TIPO='E' AND TIPO2='O'";
	$rs2=mysql_query($sql);		
	$data = array();
	$tbl_headings=array("ACCION"=>"OBRA","DEPENDENCIA"=>"DEPENDENCIA","COSTO"=>"COSTO");
	while($row2=mysql_fetch_object($rs2)){
		$data[]=array("ACCION"=>toTextObra($row2->ID_ACCION),"DEPENDENCIA"=>toTextDependencia($row2->ID_DEPENDENCIA),"COSTO"=>$row2->COSTO);
	}
	$pdf->ezTable($data,$tbl_headings,"<i>Obras Emergentes</i>",$tbl_options);
	
	//----
	
	$sql="SELECT * FROM EVENTOS_ACCIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."' AND TIPO='P' AND TIPO2='A'";
	$rs2=mysql_query($sql);		
	$data = array();
	$tbl_headings=array("ACCION"=>"ACCION","DEPENDENCIA"=>"DEPENDENCIA","COSTO"=>"COSTO");
	while($row2=mysql_fetch_object($rs2)){
		$data[]=array("ACCION"=>toTextAccion($row2->ID_ACCION),"DEPENDENCIA"=>toTextDependencia($row2->ID_DEPENDENCIA),"COSTO"=>$row2->COSTO);
	}
	$pdf->ezTable($data,$tbl_headings,"<i>Acciones Preventivas</i>",$tbl_options);
	
	//----
	
	$sql="SELECT * FROM EVENTOS_ACCIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."' AND TIPO='P' AND TIPO2='O'";
	$rs2=mysql_query($sql);		
	$data = array();
	$tbl_headings=array("ACCION"=>"OBRA","DEPENDENCIA"=>"DEPENDENCIA","COSTO"=>"COSTO");
	while($row2=mysql_fetch_object($rs2)){
		$data[]=array("ACCION"=>toTextObra($row2->ID_ACCION),"DEPENDENCIA"=>toTextDependencia($row2->ID_DEPENDENCIA),"COSTO"=>$row2->COSTO);
	}
	$pdf->ezTable($data,$tbl_headings,"<i>Obras Preventivas</i>",$tbl_options);
	
	//----
	
	$sql="SELECT * FROM EVENTOS_ACCIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."' AND TIPO='D' AND TIPO2='A'";
	$rs2=mysql_query($sql);		
	$data = array();
	$tbl_headings=array("ACCION"=>"ACCION","DEPENDENCIA"=>"DEPENDENCIA","COSTO"=>"COSTO");
	while($row2=mysql_fetch_object($rs2)){
		$data[]=array("ACCION"=>toTextAccion($row2->ID_ACCION),"DEPENDENCIA"=>toTextDependencia($row2->ID_DEPENDENCIA),"COSTO"=>$row2->COSTO);
	}
	$pdf->ezTable($data,$tbl_headings,"<i>Acciones Definitivas</i>",$tbl_options);
	
	//----
	
	$sql="SELECT * FROM EVENTOS_ACCIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."' AND TIPO='D' AND TIPO2='O'";
	$rs2=mysql_query($sql);		
	$data = array();
	$tbl_headings=array("ACCION"=>"OBRA","DEPENDENCIA"=>"DEPENDENCIA","COSTO"=>"COSTO");
	while($row2=mysql_fetch_object($rs2)){
		$data[]=array("ACCION"=>toTextObra($row2->ID_ACCION),"DEPENDENCIA"=>toTextDependencia($row2->ID_DEPENDENCIA),"COSTO"=>$row2->COSTO);
	}
	
	$pdf->ezTable($data,$tbl_headings,"<i>Obras Definitivas</i>",$tbl_options);
	
	$pdf->ezText("\n<b>Observaciones:</b>" ,8);	
	
	$sql="SELECT * FROM EVENTOS_OBSERVACIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."'";
	$rs2=mysql_query($sql);
		
	while($row2=mysql_fetch_object($rs2)){
		$pdf->ezText("\n".$row2->OBSERVACION."--<b>".toTextLogin($row2->URI_USER)."</b>",8);
	}
	$pdf->ezColumnsStop();
	$pdf->ezStream();	
?>