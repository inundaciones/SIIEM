<?php

require_once('../atlas/model/basicItem.php');
require_once('../atlas/model/directorioItem.php');
	
class catalogosGenerales {

	public function loadBasic($name){
		$sql="select * from CAT_".$name." order by NAME";
		$rs=mysql_query($sql);
		
		$ret=array();
		
		while($row=mysql_fetch_object($rs)){
			$tmp=new basicItemCAEM();
			$tmp->id=$row->ID;			
			$tmp->name=$row->NAME;
			$ret[]=$tmp;
		}
		mysql_free_result($rs);
		return $ret;		
	}
	
	public function loadDependencias($id_cuenca){
		
		$sql="select * from CAT_DEPENDENCIAS WHERE ID_CUENCA=0 OR ID_CUENCA=".$id_cuenca." order by NAME";
		$rs=mysql_query($sql);
		
		$ret=array();
		
		while($row=mysql_fetch_object($rs)){
			$tmp=new basicItemCAEM();
			$tmp->id=$row->ID;
			$tmp->name=$row->NAME;
			$ret[]=$tmp;
		}
		mysql_free_result($rs);
		return $ret;		
	}
	
	public function loadDependenciasMunicipio($id_municipio){
		
		$sql="select * from CAT_DEPENDENCIAS WHERE ID_CUENCA=0 OR ID_MUNICIPIO=".$id_municipio." order by ID_MUNICIPIO DESC, NAME";
		$rs=mysql_query($sql);
		
		$ret=array();
		
		while($row=mysql_fetch_object($rs)){
			$tmp=new basicItemCAEM();
			$tmp->id=$row->ID;
			$tmp->name=$row->NAME;
			$ret[]=$tmp;
		}
		mysql_free_result($rs);
		return $ret;		
	}
	
	public function loadDirectorio($id_municipio){
		$sql="select * from DIRECTORIO_MUNICIPIOS where ID_MUNICIPIO=".$id_municipio." order by NAME";
		$rs=mysql_query($sql);
		
		$ret=array();
		
		while($row=mysql_fetch_object($rs)){
			$tmp=new directorioItemCAEM();
			$tmp->id=$row->ID_DIRECTORIO;
			$tmp->name=$row->NAME;
			$tmp->dom=$row->DOM;
			$tmp->tel=$row->TEL;
			$tmp->id_municipio=$row->ID_MUNICIPIO;
			$ret[]=$tmp;
		}
		mysql_free_result($rs);
		return $ret;	
	}

	public function addDirectorioItem(directorioItemCAEM $item){
		$sql="Insert into DIRECTORIO_MUNICIPIOS (ID_MUNICIPIO, NAME, DOM, TEL) VALUES (";
		$sql.=$item->id_municipio.",";
		$sql.="'".$item->name."',";
		$sql.="'".$item->dom."',";
		$sql.="'".$item->tel."')";
		
		mysql_query($sql);
		
		return $this->loadDirectorio($item->id_municipio);
	}
	
	public function deleteDirectorioItem(directorioItemCAEM $item){
		$sql="delete from DIRECTORIO_MUNICIPIOS WHERE ID_DIRECTORIO=".$item->id;
		mysql_query($sql);
		return $this->loadDirectorio($item->id_municipio);
	}

	public function loadGlosario($tipo){
		$ret="";
		
		$sql="SELECT * FROM CAT_".$tipo;
		$rs=mysql_query($sql);
		
		while($row=mysql_fetch_object($rs)){
			$ret.="<p><b>".$row->NAME.": </b>".$row->DESCRIPCION."</p><br/>";
		}
		
		return $ret;
	}
}
?>
