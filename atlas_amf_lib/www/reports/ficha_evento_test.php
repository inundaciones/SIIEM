<?php		
	require_once '../../config/dbconf.php';
	require_once '../../includes/aux.php';
	
	$_POST['ID']=8;  
	
	include ('../../includes/pdf/class.ezpdf.php');
		
	
	$pdf =& new Cezpdf('LETTER');
	
	$pdf->selectFont('../../includes/pdf/fonts/Helvetica.afm');	
	//Poner márgenes conforme a machote entrgado	
	$pdf->ezSetMargins(30,30,36,36);	
	
	$sql="select * from TBL_EVENTOS WHERE ID=".$_POST['ID'];
	$rs=mysql_query($sql);
	$row=mysql_fetch_object($rs);
	$pdf->ezColumnsStart(array('num'=>1,'gap'=>0));
	
	//poner la pleca y los logos
	plecar($pdf,$row);
	
	
	//////////////////////////////////
	//Primer segmento: Datos del sitio
	//////////////////////////////////
	
	///Pidos los datos de cuenca y municipio
	$sql="SELECT A.ID_MUNICIPIO AS ID_MUNICIPIO, A.ID_CUENCA AS ID_CUENCA FROM CAT_MUNICIPIOS A INNER JOIN CAT_SITIOS B ON A.ID_MUNICIPIO=B.ID_MUNICIPIO WHERE B.ID_SITIO=".$row->ID_SITIO;
	$rsTmp=mysql_query($sql);
	$rowTmp=mysql_fetch_object($rsTmp);
	
	
	$pdf->setLineStyle(0.06);
	$pdf->rectangle(28,657,555,33);
	
	$pdf->setColor(1,0,0);
	$pdf->addText(32,678,8,"<b>Cuenca:</b> ");
	$pdf->setColor(0,0,0);
	$pdf->addText(80,678,8,toTextCuenca($rowTmp->ID_CUENCA));
	$pdf->setColor(1,0,0);
	$pdf->addText(32,666,8,"<b>Municipio:</b> ");
	$pdf->setColor(0,0,0);
	$pdf->addText(80,666,8,toTextMunicipio($rowTmp->ID_MUNICIPIO));
	
	$pdf->setColor(1,0,0);
	$pdf->addText(183,678,8,"<b>Gerencial regional de la CAEM:</b> ");
	$pdf->setColor(0,0,0);
	$pdf->addText(308,678,8,".");
	$pdf->setColor(1,0,0);
	$pdf->addText(245,666,8,"<b>Clave del sitio:</b> ");
	$pdf->setColor(0,0,0);
	//$pdf->addText(295,666,8,substr(toTextSitio($row->ID_SITIO),0,6));
	$pdf->addText(308,666,8,toTextSitio($row->ID_SITIO));
	
	//_______________________________
	
	//////////////////////////////////////////////
	//Segundo segmento: localización y embergadura
	//////////////////////////////////////////////
	
	//Cuadro principal
	$pdf->setLineStyle(0.06);
	$pdf->rectangle(28,508,555,145);
	//Pleca divisoria
	$pdf->line(295,653,295,508);
	
	//Parte izquierda
	$pdf->setColor(1,0,0);
	$pdf->addText(36,641,8,"<b>I. Identificación de zona afectada</b> ");
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	$pdf->line(36,637,163,637);
	 
	$pdf->addText(36,625,8,"<b>Localidad: </b>");
	//$pdf->addText(36,613,8,"<b>Colonias y/o barrios: </b>".$row->COLONIA);
	$y=613;
	$restText=$pdf->addTextWrap(36,$y,263,8,"<b>Colonias y/o barrios: </b>".$row->COLONIA);
	while(str_word_count($restText)>0){
		$y-=12;
		$restText=$pdf->addTextWrap(36,$y,42,8,$restText);
	}
	$pdf->addText(36,577,8,"<b>Delimitación del área (calles):  </b>");
	$pdf->addText(36,565,8,"<b>Al norte:</b>");
	$pdf->addText(94,565,8,$row->L_N);
	$pdf->addText(36,553,8,"<b>Al sur:</b>");
	$pdf->addText(94,553,8,$row->L_S);
	$pdf->addText(36,541,8,"<b>Al oriente:</b>");
	$pdf->addText(94,541,8,$row->L_O);
	$pdf->addText(36,529,8,"<b>Al poniente: </b>");
	$pdf->addText(94,529,8,$row->L_P); 
	 
	//Parte derecha
	$pdf->setColor(1,0,0);
	$pdf->addText(303,641,8,"<b>II. Vulnerabilidad</b> ");
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	$pdf->line(303,637,372,637);
	
	$pdf->addText(303,625,8,"<b>Superficie (m2): </b>".$row->SUPERFICIE);
	$pdf->addText(303,613,8,"<b>Población: </b>".$row->POBLACION);
	//$pdf->addText(306,588,8,"<b>Inmuebles \nafectados: </b>");
	$y=588;
	$restText=$pdf->addTextWrap(306,588,42,8,"<b>Inmuebles afectados: </b>");
	while(str_word_count($restText)>0){
		$y-=12;
		$restText=$pdf->addTextWrap(306,$y,42,8,$restText);
	}
	$pdf->addText(370,593,8,"<b>Casa habitación: </b>".$row->CASA);
	$pdf->addText(370,581,8,"<b>Industria: </b>".$row->INDUSTRIA);
	$pdf->addText(370,569,8,"<b>Edificio público: </b>".$row->PUBLICO);
	
	$pdf->addText(480,593,8,"<b>Local comercial: </b>".$row->LOCAL);
	$pdf->addText(480,581,8,"<b>Otros: </b>".$row->OTRO);
	//$pdf->addText(480,569,8,"<b>Total: </b>");
	
	$pdf->addText(303,552,8,"<b>Fecha de evento: </b>".formatFecha($row->FECHA));
	$pdf->addText(303,536,8,"<b>Tirante(s) (m.)</b>");
	$pdf->addText(396,540,8,"<b>Vial: </b>".$row->T_VIAL);
	$pdf->addText(382,528,8,"<b>Interior: </b>".$row->T_INTERIOR);
	$pdf->addText(303,516,8,"<b>Evento:  </b>".toTextEvento($row->ID_EVENTO));
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	$pdf->line(303,604,580,604);
	$pdf->line(303,604,303,565);
	$pdf->line(366,604,366,565);
	$pdf->line(475,604,475,565);
	$pdf->line(580,604,580,565); 
	$pdf->line(366,590,580,590);
	$pdf->line(366,578,580,578);
	$pdf->line(303,565,580,565);
	/*$pdf->ezText("\n<b>Sitio:</b> ".toTextSitio($row->ID_SITIO) ,8);
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
	$y2=$pdf->ezText("<b>Otos:</b> ".$row->OTROS,8);*/
	
	//_______________________________
	
	//////////////////////////////////////////////
	//Tercer segmento: problemática
	//////////////////////////////////////////////
	
	//Cuadro principal
	$pdf->setLineStyle(0.06);
	$pdf->rectangle(28,418,555,85);
	
	//Parte izquierda
	$pdf->setColor(1,0,0);
	$pdf->addText(36,491,8,"<b>III. Problemática</b> ");
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	$pdf->line(36,487,101,487);
	
	$pdf->ezSetY(483);
	
	$sql="SELECT * FROM EVENTOS_CAUSAS WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."'";
	$rs2=mysql_query($sql);
	$causas="";
	while($row2=mysql_fetch_object($rs2)){
		//$pdf->ezText("\t".toTextCausa($row2->ID_CAUSA)." ",8);
		$causas=$causas.toTextCausa($row2->ID_CAUSA).". ";
	}
	
	$pdf->ezText("<b>Causas: </b>".$causas,8);
	$pdf->ezText("\n<b>Descripcion:</b> ".$row->DESCRIPCION ,8);
	
	//$y=398;
	//_______________________________
	
	$yi=413;
	//////////////////////////////////////////////
	//Cuarto segmento: Acciones y Obras Emergentes
	//////////////////////////////////////////////
	$sql="SELECT * FROM EVENTOS_ACCIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."' AND TIPO='E'"; 
	$rs2=mysql_query($sql);		
	$pdf->addText(36,$yi,8,  mysql_fetch_lengths($rs2));
	$y=$yi-15; 
	
	//titluos y sus plecas
	$pdf->setColor(1,0,0);
	$pdf->addText(36,$yi-11,8,"<b>IV. Acciones y obras emergentes para disminuir las afectaciones</b> ");
	$pdf->addText(378,$yi-11,8,"<b>Dependencia(s) ejecutora(s)</b> ");
	$pdf->addText(506,$yi-11,8,"<b>Costo estimado ($)</b> ");
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	
	$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
	$pdf->line(36,$yi-15,285,$yi-15);// pleca bajo titulo izquierdo
	$pdf->line(370,$yi-15,582.5,$yi-15);//pleca bajo título derecho
	
	while($row2=mysql_fetch_object($rs2)){
		$y-=12;
		if($row2->TIPO2='O'){
			$restText1=$pdf->addTextWrap(36,$y,330,8,"-".toTextObra($row2->ID_ACCION));
		}else{
			$restText1=$pdf->addTextWrap(36,$y,330,8,"-".toTextAccion($row2->ID_ACCION));
		}
		$restText3=$pdf->addTextWrap(378,$y,118,6,toTextDependencia($row2->ID_DEPENDENCIA));
		$wC=$pdf->getTextWidth(8,"$".$row2->COSTO); 
		$pdf->addText(570-$wC,$y,8,"$".$row2->COSTO);
		 
		$y1=$y3=$y;
		while(str_word_count($restText1)>0 || str_word_count($restText3)>0){
			if(str_word_count($restText1)>0){
				$y1-=12; 
				$restText1=$pdf->addTextWrap(36,$y1,330,8,$restText1);
			}
			if(str_word_count($restText3)>0){
				$y3-=10; 
				$restText3=$pdf->addTextWrap(378,$y3,118,6,$restText3);
			}
			
			if(min($y3,$y1)-15<30)
			{
				$pdf->setColor(0,0,0);
				$pdf->setLineStyle(0.06);
				$pdf->setLineStyle(0.06);
				
				$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
				$pdf->line(28,$yi,28,$y-15);//pleca límite izquierdo
				$pdf->line(370,$yi,370,$y-15);//pleca media izquierda
				$pdf->line(495,$yi,495,$y-15);//pleca media derecha
				$pdf->line(582.5,$yi,582.5,$y-15);///pleca límite derecho              
				$pdf->line(28,$y-15,582.5,$y-15);//pleca horizontal inferior
				$pdf->newPage();
				$y=$yi=690; 
				$y1=$y3=$y-5;
				plecar($pdf,$row);
			}
		}
		
		$y=min($y3,$y1);  
	}
	if ($yi-$y<50){
		
		$y=$yi-60;
	}
	
	
	$y-=10;
	//Cuadro principal
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	
	$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
	$pdf->line(28,$yi,28,$y);//pleca límite izquierdo
	$pdf->line(370,$yi,370,$y);//pleca media izquierda
	$pdf->line(495,$yi,495,$y);//pleca media derecha
	$pdf->line(582.5,$yi,582.5,$y);///pleca límite derecho              
	$pdf->line(28,$y,582.5,$y);//pleca horizontal inferior
	
	$y-=5;
	if($y-60<30)
		{
		
			$pdf->newPage();
			$y=$yi=690; 
			plecar($pdf,$row);
		}
	//_______________________________
	
	
	$yi=$y;
	//////////////////////////////////////////////
	//Quinto segmento: Acciones y Obras    Preventivas
	//////////////////////////////////////////////
	$sql="SELECT * FROM EVENTOS_ACCIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."' AND TIPO='P'"; 
	$rs2=mysql_query($sql);		
	$pdf->addText(36,$yi,8,  mysql_fetch_lengths($rs2));
	$y=$yi-15; 
	//$data = array();
	//Titulo de la tabla con sus plecas
	$pdf->setColor(1,0,0);
	$pdf->addText(36,$yi-11,8,"<b>V. Acciones y obras preventivas contempladas</b> ");
	$pdf->addText(378,$yi-11,8,"<b>Dependencia(s) ejecutora(s)</b> ");
	$pdf->addText(506,$yi-11,8,"<b>Costo estimado ($)</b> ");
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06); 
	
	$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
	$pdf->line(36,$yi-15,225,$yi-15);// pleca bajo titulo izquierdo
	$pdf->line(370,$yi-15,582.5,$yi-15);//pleca bajo título derecho
	
	while($row2=mysql_fetch_object($rs2)){
		$y-=12;
		if($row2->TIPO2='O'){
			$restText1=$pdf->addTextWrap(36,$y,330,8,"-".toTextObra($row2->ID_ACCION));
		}else{
			$restText1=$pdf->addTextWrap(36,$y,330,8,"-".toTextAccion($row2->ID_ACCION));
		}
		$restText3=$pdf->addTextWrap(378,$y,118,6,toTextDependencia($row2->ID_DEPENDENCIA));
		//
		$wC=$pdf->getTextWidth(8,"$".$row2->COSTO); 
		$pdf->addText(570-$wC,$y,8,"$".$row2->COSTO);
		$y1=$y3=$y;
		while(str_word_count($restText1)>0 || str_word_count($restText3)>0){
			if(str_word_count($restText1)>0){
				$y1-=12; 
				$restText1=$pdf->addTextWrap(36,$y1,330,8,$restText1);
			}
			if(str_word_count($restText3)>0){
				$y3-=10; 
				$restText3=$pdf->addTextWrap(378,$y3,118,6,$restText3);
			}
			
			if(min($y3,$y1)-15<30)
			{
				$pdf->setColor(0,0,0);
				$pdf->setLineStyle(0.06);
				$pdf->setLineStyle(0.06);
				
				$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
				$pdf->line(28,$yi,28,$y-15);//pleca límite izquierdo
				$pdf->line(370,$yi,370,$y-15);//pleca media izquierda
				$pdf->line(495,$yi,495,$y-15);//pleca media derecha
				$pdf->line(582.5,$yi,582.5,$y-15);///pleca límite derecho              
				$pdf->line(28,$y-15,582.5,$y-15);//pleca horizontal inferior
				$pdf->newPage();
				$y=$yi=690; 
				$y1=$y3=$y-5;
				plecar($pdf,$row);
			}
		}
		
		$y=min($y3,$y1);  
		
	}
	if ($yi-$y<50){
		
		$y=$yi-60;
	}
	
	
	$y-=10; 
	
	
	//Cuadro principal
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	
	$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
	$pdf->line(28,$yi,28,$y);//pleca límite izquierdo
	$pdf->line(370,$yi,370,$y);//pleca media izquierda
	$pdf->line(495,$yi,495,$y);//pleca media derecha
	$pdf->line(582.5,$yi,582.5,$y);///pleca límite derecho              
	$pdf->line(28,$y,582.5,$y);//pleca horizontal inferior
	
	$y-=5;
	
	if($y-60<30)
		{
		
			$pdf->newPage();
			$y=$yi=690; 
			plecar($pdf,$row);
		}
	//_______________________________
	
	$yi=$y;
	//////////////////////////////////////////////
	//Sexto segmento: Acciones y Obras   Definitivas
	//////////////////////////////////////////////
	$sql="SELECT * FROM EVENTOS_ACCIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."' AND TIPO='D'"; 
	$rs2=mysql_query($sql);		
	$pdf->addText(36,$yi,8,  mysql_fetch_lengths($rs2));
	$y=$yi-15; 
	///Primero pongo los primero titulos en rojo y sus plecas
	$pdf->setColor(1,0,0);
	$pdf->addText(36,$yi-11,8,"<b>VI. Obras y acciones necesarias para evitar de forma definitiva la problemática</b> ");
	$pdf->addText(378,$yi-11,8,"<b>Dependencia(s) ejecutora(s)</b> ");
	$pdf->addText(506,$yi-11,8,"<b>Costo estimado ($)</b> ");
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	$pdf->line(36,$yi-15,345,$yi-15);// pleca bajo titulo izquierdo
	$pdf->line(370,$yi-15,582.5,$yi-15);//pleca bajo título derecho
	
	while($row2=mysql_fetch_object($rs2)){
		$y-=12;
		if($row2->TIPO2='O'){
			$restText1=$pdf->addTextWrap(36,$y,330,8,"-".toTextObra($row2->ID_ACCION));
		}else{
			$restText1=$pdf->addTextWrap(36,$y,330,8,"-".toTextAccion($row2->ID_ACCION));
		}
		$restText3=$pdf->addTextWrap(378,$y,118,6,toTextDependencia($row2->ID_DEPENDENCIA));
		
		$wC=$pdf->getTextWidth(8,"$".$row2->COSTO); 
		$pdf->addText(570-$wC,$y,8,"$".$row2->COSTO);
		 
		$y1=$y3=$y;
		while(str_word_count($restText1)>0 || str_word_count($restText3)>0){
			if(str_word_count($restText1)>0){
				$y1-=12; 
				$restText1=$pdf->addTextWrap(36,$y1,330,8,$restText1);
			}
			if(str_word_count($restText3)>0){
				$y3-=10; 
				$restText3=$pdf->addTextWrap(378,$y3,118,6,$restText3);
			}
			
			if(min($y3,$y1)-15<30)
			{
				$pdf->setColor(0,0,0);
				$pdf->setLineStyle(0.06);
				$pdf->setLineStyle(0.06);
				
				$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
				$pdf->line(28,$yi,28,$y-15);//pleca límite izquierdo
				$pdf->line(370,$yi,370,$y-15);//pleca media izquierda
				$pdf->line(495,$yi,495,$y-15);//pleca media derecha
				$pdf->line(582.5,$yi,582.5,$y-15);///pleca límite derecho              
				$pdf->line(28,$y-15,582.5,$y-15);//pleca horizontal inferior
				$pdf->newPage();
				$y=$yi=690; 
				$y1=$y3=$y-5;
				plecar($pdf,$row);
			}
		}
		
		$y=min($y3,$y1);  
		
	}
	if ($yi-$y<50){
		
		$y=$yi-60;
	}
	
	
	$y-=10;
	//Cuadro principal
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	
	$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
	$pdf->line(28,$yi,28,$y);//pleca límite izquierdo
	$pdf->line(370,$yi,370,$y);//pleca media izquierda
	$pdf->line(495,$yi,495,$y);//pleca media derecha
	$pdf->line(582.5,$yi,582.5,$y);///pleca límite derecho              
	$pdf->line(28,$y,582.5,$y);//pleca horizontal inferior
	
	$y-=5;
	
	if($y-60<30)
		{
		
			$pdf->newPage();
			$y=$yi=690; 
			plecar($pdf,$row);
		}
	//_______________________________
	
	
	$yi=$y;
	//////////////////////////////////////////////
	//Observaciones
	//////////////////////////////////////////////
	//$sql="SELECT * FROM EVENTOS_OBSERVACIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."'";
	$sql="SELECT * FROM EVENTOS_OBSERVACIONES WHERE ID_EVENTO=".$row->ID_EVENTO." AND ID_SITIO=".$row->ID_SITIO." AND FECHA='".$row->FECHA."'";
	$rs2=mysql_query($sql);
	$y=$yi-15; 
	//Titluos y sus plecas
	$pdf->setColor(1,0,0);
	$pdf->addText(36,$yi-11,8,"<b>VII. Reincidencia/Observaciones</b> ");
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	  
	$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
	$pdf->line(36,$yi-15,160,$yi-15);// pleca bajo titulo izquierdo
	
	while($row2=mysql_fetch_object($rs2)){
		$y-=12; 
		$restText3=$pdf->addTextWrap(36,$y,535,8,"<b>Usuario </b> <i>".toTextLogin($row2->URI_USER)."</i>:   ".$row2->OBSERVACION);
		while(str_word_count($restText3)>0){
			$y-=12; 
			$restText3=$pdf->addTextWrap(36,$y,535,8,$restText3);
			if($y-15<30)
			{
				$pdf->setColor(0,0,0);
				$pdf->setLineStyle(0.06);
				$pdf->setLineStyle(0.06);
				
				$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
				$pdf->line(28,$yi,28,$y-10);//pleca límite izquierdo
				$pdf->line(370,$yi,370,$y-10);//pleca media izquierda
				$pdf->line(495,$yi,495,$y-10);//pleca media derecha
				$pdf->line(582.5,$yi,582.5,$y-10);///pleca límite derecho              
				$pdf->line(28,$y-10,582.5,$y-10);//pleca horizontal inferior
				$pdf->newPage();
				$y=$yi=690; 
				plecar($pdf,$row);
			}
		}
		$y-=12;  
	}
	if ($yi-$y<50){
		
		$y=$yi-60;
	}
	
	
	$y-=10;
	
	//Cuadro principal
	$pdf->setColor(0,0,0);
	$pdf->setLineStyle(0.06);
	
	$pdf->line(28,$yi,582.5,$yi);//pleca horizontal superior
	$pdf->line(28,$yi,28,$y);//pleca límite izquierdo
	$pdf->line(582.5,$yi,582.5,$y);///pleca límite derecho              
	$pdf->line(28,$y,582.5,$y);//pleca horizontal inferior
	
	
	//_______________________________
	
	
	
	$pdf->ezColumnsStop();
	$pdf->ezStream();	
	
	//Funcion que pinta la pleca de colro, y pone los logos
	function plecar($pdf,$row){
		//_-----Marca de agua-----------
		$pdf->addJpegFromFile('./logoCompromiso.jpg',175,220,260);
		
		///-----Header-------
		$user_type=toTextTipo($row->URI_USER);
		
		if($user_type=='A'){
			$pdf->setColor(0.929,0.0,0.0);
			$pdf->filledRectangle(-2,760,616,22);
		}
		if($user_type=='T'){
			$pdf->setColor(0.89,0.92,0.94);
			$pdf->filledRectangle(-2,760,616,22);
		}
		if($user_type=='G'){
			$pdf->setColor(0.0,0.34,0.0);
			$pdf->filledRectangle(-2,760,616,22);
		}
		if($user_type=='M'){
			$pdf->setColor(0.0,0.64,0.0);
			$pdf->filledRectangle(-2,760,616,22);
		}
		if($user_type=='O'){
			$pdf->setColor(0.55,0.0,0.0);
			$pdf->filledRectangle(-2,760,616,22);
		}
		if($user_type==''){
			$pdf->setColor(0.55,0.0,0.0);
			$pdf->filledRectangle(-2,760,616,22); 
		}
		
		$pdf->addJpegFromFile('./logosHeader.jpg',0,698,612,54);
		
		///Datos de usuario sobre pleca de color
		$pdf->setColor(1,1,1);
		$pdf->addText(28,765,8,"Fecha de modificación: ".formatFecha($row->LASTUPDATE));
		$pdf->addText(250,765,8,"Usuario: ".toTextLogin($row->URI_USER));
		$pdf->setColor(0,0,0);
		//--------
	
	}
?>