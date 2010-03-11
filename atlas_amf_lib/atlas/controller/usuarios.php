<?php

require_once '/home/equidna/public_html/tester/atlas2010/atlas_amf_lib/atlas/model/usuario.php';

class userController {
	
	public function addUser($usuario){
		$sql="insert into TBL_USUARIOS values ('".sha1($usuario->email)."','".$usuario->email."'";
		$sql.=",'".$usuario->login."'";
		$sql.=",AES_ENCRYPT('".$usuario->clear_pass."','CAEM')";
		$sql.=",'".$usuario->name."'";
		$sql.=",".$usuario->id_municipio;
		$sql.=",'".$usuario->tipo."'";
		$sql.=")";
		
		if(mysql_query($sql)){
			return "Exito Creando el Usuario!!";
		}else{
			return "Error en la transaccion";
		}
	}
	
	public function changePass($usuario){
		$pass=explode(",",$usuario->clear_pass);
		
		$sql="select URI_USER from TBL_USUARIOS where URI_USER='".$usuario->uri_user."' and PASSWORD=AES_ENCRYPT('".$pass[0]."','CAEM')";
		$rs=mysql_query($sql);
		if($row=mysql_fetch_object($rs)){
			$sql="UPDATE TBL_USUARIOS SET PASSWORD=AES_ENCRYPT('".$pass[1]."','CAEM'); WHERE URI_USER='".$row->URI_USER."'";
			if(mysql_query($sql)){
				return "Contrase–a Cambiada Con Exito!!";
			}else{
				return "Error en la transaccion";
			}
		}else{
			return "La Contrase–a es Incorrecta!!";
		}
	}
	
	public function loginUser($usuario){
		$sql="select URI_USER FROM TBL_USUARIOS WHERE LOGIN like '".$usuario->login."' AND PASSWORD=AES_ENCRYPT('".$usuario->clear_pass."','CAEM')";
		if($rs=mysql_query($sql)){
			
			$usuario=new usuarioCAEM();
			$usuario->uri_user="";
			if($row=mysql_fetch_object($rs)){
				$usuario->uri_user=$row->URI_USER;
			}
			
			$usuario=$this->getUser($usuario);
			
			return $usuario;
		}else{
			return mysql_error();	
		}
	}
	
	private function getUser($usuario){
		$sql="select * from TBL_USUARIOS WHERE URI_USER='".$usuario->uri_user."'";
		$rs=mysql_query($sql);
		if($row=mysql_fetch_object($rs)){			
			$usuario->login=$row->LOGIN;
			$usuario->name=$row->NAME;
			$usuario->id_municipio=$row->ID_MUNICIPIO;
			$usuario->txt_municipio=toTextMunicipio($row->ID_MUNICIPIO);
			
			$sql="select ID_GERENCIA FROM CAT_MUNICIPIOS WHERE ID_MUNICIPIO=".$usuario->id_municipio;
			$rs2=mysql_query($sql);
			$row2=mysql_fetch_object($rs2);
			
			$usuario->id_gerencia=$row2->ID_GERENCIA;
			$usuario->txt_gerencia=toTextGerencia($row2->ID_GERENCIA);
			
			mysql_free_result($rs2);
						
			$usuario->email=$row->EMAIL;
			$usuario->tipo=$row->TIPO;
			$usuario->clear_pass='';
		}
		return $usuario;
	}

	public function listUsers(){
		$ret=array();
		$sql="select URI_USER from TBL_USUARIOS";
		$rs=mysql_query($sql);
		while($row=mysql_fetch_object($rs)){
			$tmp=new usuarioCAEM();
			$tmp->uri_user=$row->URI_USER;
			$tmp=$this->getUser($tmp);			
			$ret[]=$tmp;
		}
		
		return $ret;
	}
}	
?>
