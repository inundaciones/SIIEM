<?php
	
	function toTextGerencia($id_gerencia){
		if($id_gerencia!=''){
			$sql="SELECT NAME from CAT_GERENCIAS WHERE ID=".$id_gerencia;
			$rs=mysql_query($sql);
			$row=mysql_fetch_object($rs);
			
			return $row->NAME;
		}else{
			return "NOT FOUND";	
		}
	}
	
	function toTextCuenca($id_cuenca){
		$sql="SELECT NAME from CAT_CUENCAS WHERE ID_CUENCA=".$id_cuenca;
		$rs=mysql_query($sql);
		$row=mysql_fetch_object($rs);
		
		return $row->NAME;
	}

	function toTextSitio($id_sitio){
		$ret='';
		$sql="select CLAVE, NAME from CAT_SITIOS where ID_SITIO=".$id_sitio;
		if($rs=mysql_query($sql)){
			if($row=mysql_fetch_object($rs)){		
				$ret=$row->CLAVE."-".$row->NAME;
			}else{
				$ret='No Encontrado';	
			}
		}else{
			$ret=mysql_error();	
		}
		return $ret;
	}
	
	function toTextEvento($id_evento){
		$ret='';
		$sql="select NAME from CAT_EVENTOS where ID=".$id_evento;
		if($rs=mysql_query($sql)){
			if($row=mysql_fetch_object($rs)){		
				$ret=$row->NAME;
			}else{
				$ret='No Encontrado';	
			}
		}else{
			$ret=mysql_error();	
		}
		return $ret;
	}
	
	function toTextDependencia($id_dependencia){
		$ret='';
		$sql="select NAME from CAT_DEPENDENCIAS where ID=".$id_dependencia;
		if($rs=mysql_query($sql)){
			if($row=mysql_fetch_object($rs)){		
				$ret=$row->NAME;
			}else{
				$ret='No Encontrado';	
			}
		}else{
			$ret=mysql_error();	
		}
		return $ret;
	}
	
	function toTextAccion($id_accion){
		$ret='';
		$sql="select NAME from CAT_ACCIONES where ID=".$id_accion;
		if($rs=mysql_query($sql)){
			if($row=mysql_fetch_object($rs)){		
				$ret=$row->NAME;
			}else{
				$ret='No Encontrado';	
			}
		}else{
			$ret=mysql_error();	
		}
		return $ret;
	}
	
	function toTextObra($id_obra){
		$ret='';
		$sql="select NAME from CAT_OBRAS where ID=".$id_obra;
		if($rs=mysql_query($sql)){
			if($row=mysql_fetch_object($rs)){		
				$ret=$row->NAME;
			}else{
				$ret='No Encontrado';	
			}
		}else{
			$ret=mysql_error();	
		}
		return $ret;
	}
	
	function toTextMunicipio($id_municipio){
		$ret='';
		$sql="select NAME from CAT_MUNICIPIOS where ID_MUNICIPIO=".$id_municipio;
		if($rs=mysql_query($sql)){
			if($row=mysql_fetch_object($rs)){		
				$ret=$row->NAME;
			}else{
				$ret='No Encontrado';	
			}
		}else{
			$ret=mysql_error();	
		}
		return $ret;
	}

	function toTextCausa($id_causa){
		$ret='';	
		$sql="select NAME from CAT_CAUSAS where ID=".$id_causa;
		if($rs=mysql_query($sql)){
			if($row=mysql_fetch_object($rs)){		
				$ret=$row->NAME;
			}else{
				$ret='No Encontrado';	
			}
		}else{
			$ret=mysql_error();	
		}
		return $ret;
	}
	
	function toTextLogin($uri_user){
		$sql="select LOGIN from TBL_USUARIOS WHERE URI_USER='".$uri_user."'";
		$rs=mysql_query($sql);
		$row=mysql_fetch_object($rs);
		return $row->LOGIN; 
	}
	
	function toTextTipo($uri_user){
		$sql="select TIPO from TBL_USUARIOS WHERE URI_USER='".$uri_user."'";
		$rs=mysql_query($sql);
		$row=mysql_fetch_object($rs);
		return $row->TIPO; 
	}
	
	function formatFecha($fecha){
			
		$meses=array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo','Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre','Diciembre');
		$fecha=substr($fecha, 0,10);
		
		$fecha=explode('-',$fecha);
		
		$ret=$fecha[2]."-".$meses[$fecha[1]-1]."-".$fecha[0];
		
		return $ret;
		
		
		
	}
	
	function generatePassword($length=9, $strength=0) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength>=1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength>=2) {
			$vowels .= "AEUY";
		}
		if ($strength>=4) {
			$consonants .= '23456789';
		}
		if ($strength>=5) {
			$consonants .= '@#$%';
		}
	 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}
	
	?>