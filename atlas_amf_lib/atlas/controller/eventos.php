<?php

require_once('../atlas/model/evento.php');
require_once('../atlas/model/basicItem.php');
require_once('../atlas/model/accionEvento.php');

class eventoController {

	public function createEvento($evento){
		$sql="insert into TBL_EVENTOS (ID_SITIO, FECHA, ID_EVENTO, URI_USER) VALUES (".$evento->id_sitio.",'".$evento->fecha."',".$evento->id_evento.",'".$evento->uri_user."')";
		if(mysql_query($sql)){
			$sql="select ID from TBL_EVENTOS WHERE ID_SITIO=".$evento->id_sitio." AND ID_EVENTO=".$evento->id_evento." AND FECHA='".$evento->fecha."' AND URI_USER='".$evento->uri_user."'";
			$rs=mysql_query($sql);
			$row=mysql_fetch_object($rs);
			$evento->id=$row->ID;			
			
			$sql="SELECT * FROM CAT_SITIOS WHERE ID_SITIO=".$evento->id_sitio;
			$rs=mysql_query($sql);
			$row=mysql_fetch_object($rs);
			$evento->lat=$row->LAT;
			$evento->lon=$row->LON;			
			$evento->acciones=array();
			$evento->observaciones=array();
			$evento->causas=array();
			$evento->files;
			
			return $evento;
		}else{
			return "0";
		}		
	}
	
	public function getEvento($evento){
		
		$tmp=new eventoCAEM();
		$tmp->id='0';
		
		$sql="select * from TBL_EVENTOS WHERE ID_SITIO=".$evento->id_sitio." AND ID_EVENTO=".$evento->id_evento." AND FECHA='".$evento->fecha."' AND URI_USER='".$evento->uri_user."'";		
		$rs=mysql_query($sql);
		if($row=mysql_fetch_object($rs)){				
			$tmp->id=$row->ID;
			$tmp->id_sitio=$row->ID_SITIO;			
			$tmp->txt_sitio=toTextSitio($tmp->id_sitio);
						
			//LAT Y LON
			$sql="select LAT,LON from CAT_SITIOS WHERE ID_SITIO=".$row->ID_SITIO;
			$rs2=mysql_query($sql);
			$row2=mysql_fetch_object($rs2);
			
			$tmp->lat=$row2->LAT;
			$tmp->lon=$row2->LON;
			
			//BUSCA EL MUNICIPIO
			$sql="select ID_MUNICIPIO FROM CAT_SITIOS WHERE ID_SITIO=".$tmp->id_sitio;
			$rs2=mysql_query($sql);
			$row2=mysql_fetch_object($rs2);						
			$tmp->id_municipio=$row2->ID_MUNICIPIO;
			$tmp->txt_municipio=toTextMunicipio($row2->ID_MUNICIPIO);
			
			//BUSCA LA CUENCA
			$sql="select ID_CUENCA FROM CAT_MUNICIPIOS WHERE ID_MUNICIPIO=".$tmp->id_municipio;
			$rs2=mysql_query($sql);
			$row2=mysql_fetch_object($rs2);						
			$tmp->id_cuenca=$row2->ID_CUENCA;
		
			$tmp->fecha=$row->FECHA;
			$tmp->id_evento=$row->ID_EVENTO;
			$tmp->txt_evento=toTextEvento($tmp->id_evento);
			$tmp->descripcion=$row->DESCRIPCION;
			$tmp->colonia=$row->COLONIA;
			$tmp->L_N=$row->L_N;
			$tmp->L_S=$row->L_S;
			$tmp->L_O=$row->L_O;
			$tmp->L_P=$row->L_P;
			$tmp->superficie=$row->SUPERFICIE;
			$tmp->unidad=$row->UNIDAD;
			$tmp->poblacion=$row->POBLACION;
			$tmp->casa=$row->CASA;
			$tmp->local=$row->LOCAL;
			$tmp->industria=$row->INDUSTRIA;
			$tmp->otros=$row->OTROS;
			$tmp->publico=$row->PUBLICO;
			$tmp->t_vial=$row->T_VIAL;
			$tmp->t_interior=$row->T_INTERIOR;			
			
			$tmp->uri_user=$row->URI_USER;
			$tmp->txt_user=toTextLogin($row->URI_USER);
			
			$sql="SELECT TIPO FROM TBL_USUARIOS WHERE URI_USER='".$tmp->uri_user."'";
			$rs2=mysql_query($sql);
			$row2=mysql_fetch_object($rs2);
			$tmp->user_type=$row2->TIPO;
			
			
			$tmp->causas=$this->loadCausas($tmp);
			$tmp->acciones=$this->loadAcciones($tmp);
			$tmp->observaciones=$this->loadObservaciones($tmp);
			$tmp->files=$this->loadFiles($tmp);
			$tmp->file_count=count($tmp->files);
			$tmp->last_update=$row->LASTUPDATE;
			$tmp->prioridad=$row->PRIORIDAD;
		}

		return $tmp;
	}
		
	public function save($evento){		
		$sql="UPDATE TBL_EVENTOS SET ";
		$sql.="DESCRIPCION='".$evento->descripcion."', ";
		$sql.="COLONIA='".$evento->colonia."', ";
		$sql.="L_N='".$evento->L_N."', ";
		$sql.="L_S='".$evento->L_S."', ";
		$sql.="L_O='".$evento->L_O."', ";
		$sql.="L_P='".$evento->L_P."', ";
		$sql.="SUPERFICIE=".$evento->superficie.", ";
		$sql.="UNIDAD='".$evento->unidad."', ";
		$sql.="POBLACION=".$evento->poblacion.", ";
		$sql.="CASA=".$evento->casa.", ";
		$sql.="LOCAL=".$evento->local.", ";
		$sql.="INDUSTRIA=".$evento->industria.", ";
		$sql.="OTROS=".$evento->otros.", ";
		$sql.="PUBLICO=".$evento->publico.", ";
		$sql.="T_VIAL=".$evento->t_vial.", ";
		$sql.="T_INTERIOR=".$evento->t_interior.", ";
		$sql.="PRIORIDAD=".$evento->prioridad;		
		$sql.=" WHERE ID=".$evento->id;
					
		if(mysql_query($sql)){			
			$ret=1;
		}else{
			$ret=0;	
		}
						
		return $ret;
		
	}
			
	public function addCausa($evento,$id_causa){		
		$sql="INSERT INTO EVENTOS_CAUSAS (ID_SITIO, ID_EVENTO, FECHA, URI_USER, ID_CAUSA) VALUES";
		$sql.="(".$evento->id_sitio.",".$evento->id_evento.",'".$evento->fecha."','".$evento->uri_user."',".$id_causa.")";
		if(mysql_query($sql)){
			$ret=1;	
		}else{
			$ret=0;
		}		
		return $ret;
	}

	public function removeCausa($evento,$id_causa){
		$sql="delete from EVENTOS_CAUSAS WHERE ID_EVENTO=".$evento->id_evento." AND ID_SITIO=".$evento->id_sitio." AND FECHA='".$evento->fecha."' AND ID_CAUSA=".$id_causa." AND URI_USER='".$evento->uri_user."'";
		if(mysql_query($sql)){
			$ret=1;	
		}else{
			$ret=0;
		}		
		return $ret;
	}
		
	public function loadEventos($uri_user=''){
		$ret=array();
		if($uri_user==''){
			$sql="select ID_SITIO, FECHA, ID_EVENTO, URI_USER from TBL_EVENTOS  order by FECHA DESC";	
		}else{
			$sql="SELECT TIPO, ID_MUNICIPIO FROM TBL_USUARIOS WHERE URI_USER='".$uri_user."'";
			$rs=mysql_query($sql);
			if($row=mysql_fetch_object($rs)){
				$tipo=$row->TIPO;
				$id_municipio=$row->ID_MUNICIPIO;
				if($tipo=="M"){
					$sql="select ID_SITIO, FECHA, ID_EVENTO, URI_USER from TBL_EVENTOS WHERE ID_SITIO IN (SELECT ID_SITIO FROM CAT_SITIOS WHERE ID_MUNICIPIO=".$id_municipio.") order by FECHA DESC";
				}else{
					$sql="SELECT ID_GERENCIA FROM CAT_MUNICIPIOS WHERE ID_MUNICIPIO=".$id_municipio;
					$rs=mysql_query($sql);
					$row=mysql_fetch_object($rs);
					$id_gerencia=$row->ID_GERENCIA;
					
					$sql="select ID_SITIO, FECHA, ID_EVENTO, URI_USER from TBL_EVENTOS WHERE ID_SITIO IN (SELECT ID_SITIO FROM CAT_SITIOS A INNER JOIN CAT_MUNICIPIOS B ON A.ID_MUNICIPIO=B.ID_MUNICIPIO WHERE ID_GERENCIA=".$id_gerencia.") order by FECHA DESC";
					
				}
				
			}
			
		}
		
		$rs=mysql_query($sql);
		while($row=mysql_fetch_object($rs)){
			$tmp=new eventoCAEM();
			$tmp->id_sitio=$row->ID_SITIO;
			$tmp->id_evento=$row->ID_EVENTO;
			$tmp->fecha=$row->FECHA;
			$tmp->uri_user=$row->URI_USER;
			$ret[]=$this->getEvento($tmp);
		}
		return $ret;
	}
			
		
	private function loadCausas($evento){
		$ret=array();		
		$sql="select A.ID_CAUSA, B.NAME from EVENTOS_CAUSAS A INNER JOIN CAT_CAUSAS B ON A.ID_CAUSA=B.ID WHERE ID_SITIO=".$evento->id_sitio." AND ID_EVENTO=".$evento->id_evento." AND FECHA='".$evento->fecha."' AND URI_USER='".$evento->uri_user."'";
		if($rs=mysql_query($sql)){
			while($row=mysql_fetch_object($rs)){
				$tmp=new basicItemCAEM();
				$tmp->id=$row->ID_CAUSA;
				$tmp->name=$row->NAME;
				$ret[]=$tmp;				
			}
		}else{
			$ret[]=mysql_error();
			$ret[]=$sql;	
		}
		
		return $ret;
		
	}
	
	public function loadCausasDisp($evento){
		$ret=array();		
		$sql="select ID, NAME from CAT_CAUSAS WHERE ID NOT IN (SELECT ID_CAUSA FROM EVENTOS_CAUSAS WHERE ID_SITIO=".$evento->id_sitio." AND ID_EVENTO=".$evento->id_evento." AND FECHA='".$evento->fecha."' AND URI_USER='".$evento->uri_user."')";
		if($rs=mysql_query($sql)){
			while($row=mysql_fetch_object($rs)){
				$tmp=new basicItemCAEM();
				$tmp->id=$row->ID;
				$tmp->name=$row->NAME;
				$ret[]=$tmp;				
			}
		}else{
			$ret[]=mysql_error();
			$ret[]=$sql;	
		}
		
		return $ret;
	}
	
	public function loadAcciones($evento){
		$ret=array();
		$sql="select * from EVENTOS_ACCIONES WHERE ID_SITIO=".$evento->id_sitio." AND ID_EVENTO=".$evento->id_evento." AND FECHA='".$evento->fecha."' AND URI_USER='".$evento->uri_user."'";
		if($rs=mysql_query($sql)){
			while($row=mysql_fetch_object($rs)){
				$tmp=new accionEventoCAEM();
				$tmp->id_accion=$row->ID_ACCION;
				$tmp->id_dependencia=$row->ID_DEPENDENCIA;
				$tmp->tipo=$row->TIPO;
				$tmp->tipo2=$row->TIPO2;
				$tmp->costo=$row->COSTO;
				$tmp->txt_dependencia=toTextDependencia($tmp->id_dependencia);
				if($tmp->tipo2=='A'){
					$tmp->txt_accion=toTextAccion($tmp->id_accion);
				}else{
					$tmp->txt_accion=toTextObra($tmp->id_accion);	
				}
				$ret[]=$tmp;
			}
		}else {
			$ret[]=mysql_error();	
		}
		return $ret;
	}

	public function addAccion($evento,$accion){
		$sql="insert into EVENTOS_ACCIONES (ID_SITIO, ID_EVENTO, FECHA, URI_USER, ID_ACCION, ID_DEPENDENCIA, COSTO, TIPO, TIPO2) VALUES (";
		$sql.=$evento->id_sitio.",".$evento->id_evento.",'".$evento->fecha."','".$evento->uri_user."',".$accion->id_accion.",".$accion->id_dependencia;
		$sql.=",".$accion->costo.",'".$accion->tipo."','".$accion->tipo2."')";
		if(mysql_query($sql)){
			$ret=1;	
		}else{
			$ret=0;
		}		
		return $ret;
	}

	public function removeAccion($evento,$accion){
		$sql="delete from EVENTOS_ACCIONES WHERE ID_SITIO=".$evento->id_sitio." AND ID_EVENTO=".$evento->id_evento." AND FECHA='".$evento->fecha."'";
		$sql.=" AND ID_ACCION=".$accion->id_accion." AND ID_DEPENDENCIA=".$accion->id_dependencia." AND TIPO='".$accion->tipo."' AND TIPO2='".$accion->tipo2."' AND URI_USER='".$evento->uri_user."'";
		if(mysql_query($sql)){
			$ret=1;	
		}else{
			$ret=0;
		}		
		return $ret;
		
	}

	public function addObservacion($evento,$observacion){
		$sql="insert into EVENTOS_OBSERVACIONES (ID_SITIO, ID_EVENTO, FECHA, URI_USER, OBSERVACION) VALUES (";
		$sql.=$evento->id_sitio.",".$evento->id_evento.",'".$evento->fecha."','".$evento->uri_user."',";
		$sql.="'".$observacion."')";
		if(mysql_query($sql)){
			$ret=1;	
		}else{
			$ret=mysql_error()."\n".$sql;
		}		
		return $ret;
	}

	public function removeObservacion($evento,$observacion){
		$sql="delete from EVENTOS_OBSERVACIONES WHERE ID_SITIO=".$evento->id_sitio." AND ID_EVENTO=".$evento->id_evento." AND FECHA='".$evento->fecha."'";
		$sql.=" AND OBSERVACION='".$observacion."' AND URI_USER='".$evento->uri_user."'";
		if(mysql_query($sql)){
			$ret=1;	
		}else{
			$ret=0;
		}		
		return $ret;
		
	}

	public function loadObservaciones($evento){
		$ret=array();
		$sql="select * from EVENTOS_OBSERVACIONES where ID_EVENTO=".$evento->id_evento." AND ID_SITIO=".$evento->id_sitio." AND FECHA='".$evento->fecha."' AND URI_USER='".$evento->uri_user."'";
		$rs=mysql_query($sql);
		while($row=mysql_fetch_object($rs)){
			$ret[]=$row->OBSERVACION;
		}
		return $ret;
	}

	public function loadFiles($evento){
		$ret=array();
		
		$sql="SELECT URI_FILE, ID_EVENTO, NAME, TYPE, SIZE FROM FILES WHERE ID_EVENTO=".$evento->id;
		$rs=mysql_query($sql);
		
		while($row=mysql_fetch_object($rs)){
			$tmp=new basicItemCAEM();
			
			$tmp->name=$row->NAME;
			$tmp->id=$row->ID_EVENTO;
			$tmp->data1=$row->URI_FILE;
			$tmp->data2=$row->TYPE;
			$tmp->data3=$row->SIZE;
			
			$ret[]=$tmp;			
		}
		
		return $ret;
	}

}
?>
