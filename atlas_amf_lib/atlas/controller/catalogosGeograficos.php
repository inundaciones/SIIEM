<?php

require_once('../atlas/model/cuenca.php');
require_once('../atlas/model/municipio.php');
require_once('../atlas/model/localidad.php');
require_once('../atlas/model/sitio.php');
require_once('../atlas/model/gerencia.php');

class catalogosGeograficos {
	
		
	public function loadCuencas(){
			
		$sql="select * from CAT_CUENCAS order by NAME";
    	$rs=mysql_query($sql);

     	$ret=array();     	

     	while($row=mysql_fetch_object($rs)){
     		$tmp=new cuencaCAEM();
     		$tmp->id_cuenca=$row->ID_CUENCA;
     		$tmp->name=$row->NAME;
     		$tmp->municipios=$this->loadMunicipios($tmp->id_cuenca);     		     	     		
     		$ret[] = $tmp;
     	}
     	     	     	     	     	
     	mysql_free_result($rs);
     	return $ret;
	}	

	public function loadCuencasOtro(){
			
		$sql="select * from CAT_CUENCAS order by NAME";
    	$rs=mysql_query($sql);

     	$ret=array();     	

     	while($row=mysql_fetch_object($rs)){
     		$tmp=new cuencaCAEM();
     		$tmp->id_cuenca=$row->ID_CUENCA;
     		$tmp->name=$row->NAME;
     		$tmp->municipios=$this->loadMunicipiosOtro($tmp->id_cuenca);     		     	     		
     		$ret[] = $tmp;
     	}
     	     	     	     	     	
     	mysql_free_result($rs);
     	return $ret;
	}
	
	public function loadCuencasSitios(){
			
		$sql="select * from CAT_CUENCAS order by NAME";
    	$rs=mysql_query($sql);

     	$ret=array();     	

     	while($row=mysql_fetch_object($rs)){
     		$tmp=new cuencaCAEM();
     		$tmp->id_cuenca=$row->ID_CUENCA;
     		$tmp->name=$row->NAME;
     		$tmp->municipios=$this->loadMunicipiosSitios($tmp->id_cuenca);     		     	     		
     		$ret[] = $tmp;
     	}
     	     	     	     	     	
     	mysql_free_result($rs);
     	return $ret;
	}	
	
	public function loadMunicipios($id_cuenca){
	
		$sql="select * from CAT_MUNICIPIOS where ID_CUENCA=".$id_cuenca."  order by NAME";
     	$rs=mysql_query($sql);

     	$ret=array();
     	
     	while($row=mysql_fetch_object($rs)){
     		$tmp=new municipioCAEM();
     		
     		$tmp->id_municipio=$row->ID_MUNICIPIO;
     		$tmp->id_cuenca=$row->ID_CUENCA;
     		$tmp->txt_cuenca=toTextCuenca($row->ID_CUENCA);
     		$tmp->name=$row->NAME;
     		$tmp->id_gerencia=$row->ID_GERENCIA;
     		$tmp->txt_gerencia=toTextGerencia($row->ID_GERENCIA);     	
     		$tmp->sitios=$this->loadSitios($row->ID_MUNICIPIO);
     					
     		$ret[] = $tmp;
     	}     	
     	mysql_free_result($rs);
	
     	return $ret;
	}

	public function loadMunicipiosOtro($id_cuenca){
	
		$sql="select * from CAT_MUNICIPIOS where ID_CUENCA=".$id_cuenca."  order by NAME";
     	$rs=mysql_query($sql);

     	$ret=array();
     	
     	while($row=mysql_fetch_object($rs)){
     		$tmp=new municipioCAEM();
     		
     		$tmp->id_municipio=$row->ID_MUNICIPIO;
     		$tmp->id_cuenca=$row->ID_CUENCA;
     		$tmp->txt_cuenca=toTextCuenca($row->ID_CUENCA);
     		$tmp->name=$row->NAME;
     		$tmp->id_gerencia=$row->ID_GERENCIA;
     		$tmp->txt_gerencia=toTextGerencia($row->ID_GERENCIA);     		     		
     		$tmp->sitios=$this->loadSitiosOtro($row->ID_MUNICIPIO);
     					
     		$ret[] = $tmp;
     	}     	
     	mysql_free_result($rs);
	
     	return $ret;
	}	
	
	public function loadMunicipiosSitios($id_cuenca){
	
		$sql="select * from CAT_MUNICIPIOS where ID_CUENCA=".$id_cuenca." AND ID_MUNICIPIO IN (SELECT ID_MUNICIPIO FROM CAT_SITIOS)  order by NAME";
     	$rs=mysql_query($sql);

     	$ret=array();
     	
     	while($row=mysql_fetch_object($rs)){
     		$tmp=new municipioCAEM();
     		
     		$tmp->id_municipio=$row->ID_MUNICIPIO;
     		$tmp->id_cuenca=$row->ID_CUENCA;
     		$tmp->txt_cuenca=toTextCuenca($row->ID_CUENCA);
     		$tmp->name=$row->NAME;
     		$tmp->id_gerencia=$row->ID_GERENCIA;
     		$tmp->txt_gerencia=toTextGerencia($row->ID_GERENCIA);
     		
     		//$tmp->localidades=$this->loadLocalidades($tmp->id_municipio);
     		$tmp->sitios=$this->loadSitios($row->ID_MUNICIPIO);
     					
     		$ret[] = $tmp;
     	}     	
     	mysql_free_result($rs);
	
     	return $ret;
	}
		
	public function loadSitios($id_municipio){
		$sql="select * from CAT_SITIOS where ID_MUNICIPIO=".$id_municipio." order by CLAVE";
     	$rs=mysql_query($sql);

     	$ret=array();
     	
     	while($row=mysql_fetch_object($rs)){
     		$tmp=new sitioCAEM();
     		$tmp->id_sitio=$row->ID_SITIO;
     		$tmp->id_municipio=$row->ID_MUNICIPIO;
     		$tmp->txt_municipio=toTextMunicipio($row->ID_MUNICIPIO);  

     		$sql="SELECT ID_GERENCIA, ID_CUENCA FROM CAT_MUNICIPIOS WHERE ID_MUNICIPIO=".$row->ID_MUNICIPIO;
     		$rs2=mysql_query($sql);
     		$row2=mysql_fetch_object($rs2);
     		$tmp->id_cuenca=$row2->ID_CUENCA;
     		$tmp->txt_gerencia=toTextGerencia($row2->ID_GERENCIA);
     		
     		$tmp->clave=$row->CLAVE;
     		$tmp->name=$row->NAME;
     		$tmp->D_N=$row->D_N;
     		$tmp->D_S=$row->D_S;
     		$tmp->D_O=$row->D_O;
     		$tmp->D_P=$row->D_P;
     		$tmp->lat=$row->LAT;
     		$tmp->lon=$row->LON;   

     		$sql="SELECT MAX(POBLACION) as POBLACION FROM TBL_EVENTOS WHERE ID_SITIO=".$tmp->id_sitio;
     		$rs2=mysql_query($sql);
     		$row2=mysql_fetch_object($rs2);
     		$tmp->poblacion=$row2->POBLACION;
     		
     		
     		$sql="select YEAR(FECHA) AS A from TBL_EVENTOS  where ID_SITIO= ".$tmp->id_sitio;
     		$rs2=mysql_query($sql);
     		
     		$cur_anhio=0;
     		$r=0;
     		while($row2=mysql_fetch_object($rs2)){
     			if($cur_anhio!=$row2->A){
     				$r++;	
     			}     		
     		}
     		
     		$tmp->reincidencia=$r;
     		
     		$ret[] = $tmp;
     	}   
          	     	
     	mysql_free_result($rs);
	
     	return $ret;
	}
	
	public function loadSitiosOtro($id_municipio){
		$sql="select * from CAT_SITIOS where ID_MUNICIPIO=".$id_municipio." order by CLAVE";
     	$rs=mysql_query($sql);

     	$ret=array();
     	
     	while($row=mysql_fetch_object($rs)){
     		$tmp=new sitioCAEM();
     		$tmp->id_sitio=$row->ID_SITIO;
     		$tmp->id_municipio=$row->ID_MUNICIPIO;
     		$tmp->txt_municipio=toTextMunicipio($row->ID_MUNICIPIO);  

     		$sql="SELECT ID_GERENCIA FROM CAT_MUNICIPIOS WHERE ID_MUNICIPIO=".$row->ID_MUNICIPIO;
     		$rs2=mysql_query($sql);
     		$row2=mysql_fetch_object($rs2);
     		
     		$tmp->txt_gerencia=toTextGerencia($row2->ID_GERENCIA);
     		
     		$tmp->clave=$row->CLAVE;
     		$tmp->name=$row->NAME;
     		$tmp->D_N=$row->D_N;
     		$tmp->D_S=$row->D_S;
     		$tmp->D_O=$row->D_O;
     		$tmp->D_P=$row->D_P;
     		$tmp->lat=$row->LAT;
     		$tmp->lon=$row->LON;     		     		
     		$ret[] = $tmp;
     	}   
     	//AGREGA EL SITIO OTROS CON ID 0
     	
	     	$tmp=new sitioCAEM();
	     	$tmp->id_sitio='0';
	     	$tmp->clave='000';
	     	$tmp->name='OTRO';
	     	$ret[] = $tmp;
     	
     	
     	mysql_free_result($rs);
	
     	return $ret;
	}	

	public function loadSitiosTmp(){
		$sql="select * from CAT_SITIOS_TEMP ORDER BY NAME";
     	$rs=mysql_query($sql);

     	$ret=array();
     	
     	while($row=mysql_fetch_object($rs)){
     		$tmp=new sitioCAEM();
     		$tmp->id_sitio=$row->ID_SITIO;
     		$tmp->id_municipio=$row->ID_MUNICIPIO;
     		$tmp->txt_municipio=toTextMunicipio($row->ID_MUNICIPIO);  

     		$sql="SELECT ID_GERENCIA FROM CAT_MUNICIPIOS WHERE ID_MUNICIPIO=".$row->ID_MUNICIPIO;
     		$rs2=mysql_query($sql);
     		$row2=mysql_fetch_object($rs2);
     		
     		$tmp->txt_gerencia=toTextGerencia($row2->ID_GERENCIA);
     		     		
     		$tmp->name=$row->NAME;
     		$tmp->D_N=$row->D_N;
     		$tmp->D_S=$row->D_S;
     		$tmp->D_O=$row->D_O;
     		$tmp->D_P=$row->D_P;
     		$tmp->lat=$row->LAT;
     		$tmp->lon=$row->LON;     		     		
     		$ret[] = $tmp;
     	}    	
     	
     	mysql_free_result($rs);
	
     	return $ret;
	}
	
	public function addSitio($sitio){
				
		
		$sql="SELECT COUNT(*) AS C FROM CAT_SITIOS WHERE ID_MUNICIPIO=".$sitio->id_municipio;
		$rs=mysql_query($sql);
		$row=mysql_fetch_object($rs);
		
		if($row->C=='0'){
			$next=99;
		}else{
			$sql="SELECT MAX(NUM_SITIO) AS NUM_SITIO FROM CAT_SITIOS WHERE ID_MUNICIPIO=".$sitio->id_municipio;
			$rs=mysql_query($sql);
			$row=mysql_fetch_object($rs);
				
			$next=$row->NUM_SITIO+1;
			
		}
		
		$sql="SElECT CLAVE FROM CAT_MUNICIPIOS WHERE ID_MUNICIPIO=".$sitio->id_municipio;
		$rs=mysql_query($sql);
		$row=mysql_fetch_object($rs);
		
		if($next<10){
			$clave=$row->CLAVE."-0".$next;	
		}else{
			$clave=$row->CLAVE."-".$next;
		}
		
		$sql="INSERT INTO CAT_SITIOS (ID_MUNICIPIO, NAME, CLAVE, D_N, D_S, D_O, D_P, LAT, LON, NUM_SITIO ) VALUES (";
		$sql.=$sitio->id_municipio.",'".$sitio->name."', '".$clave."','".$sitio->D_N."','".$sitio->D_S."','".$sitio->D_O."','".$sitio->D_P."',".$sitio->lat.",".$sitio->lon.",".$next.")";
		mysql_query($sql);
		
		return 1;
		
	}
	
	public function addSitioTmp($sitio){
						
		$sql="INSERT INTO CAT_SITIOS_TEMP (ID_MUNICIPIO, NAME, D_N, D_S, D_O, D_P, LAT, LON ) VALUES (";
		$sql.=$sitio->id_municipio.",'".$sitio->name."', '".$sitio->D_N."','".$sitio->D_S."','".$sitio->D_O."','".$sitio->D_P."',".$sitio->lat.",".$sitio->lon.")";
		mysql_query($sql);		
		return 1;
		
	}
	
	public function validateSitioTmp($sitio){
		$sql="delete from CAT_SITIOS_TEMP WHERE ID_SITIO=".$sitio->id_sitio;
		mysql_query($sql);
		$this->addSitio($sitio);
		return $this->loadSitiosTmp();
	}
	
	public function deleteSitioTmp($sitio){
		$sql="delete from CAT_SITIOS_TEMP WHERE ID_SITIO=".$sitio->id_sitio;
		mysql_query($sql);
		return $this->loadSitiosTmp();
	}
	
	public function loadGerencias(){
			
		$sql="select * from CAT_GERENCIAS order by NAME";
    	$rs=mysql_query($sql);

     	$ret=array();     	

     	while($row=mysql_fetch_object($rs)){
     		$tmp=new gerenciaCAEM();
     		$tmp->id_gerencia=$row->ID;
     		$tmp->name=$row->NAME;
     		$tmp->municipios=$this->loadMunicipiosGerencia($tmp->id_gerencia);     		     	     		
     		$ret[] = $tmp;
     	}
     	     	     	     	     	
     	mysql_free_result($rs);
     	return $ret;
	}	
	
	public function loadMunicipiosGerencia($id_gerencia){
	
		$sql="select * from CAT_MUNICIPIOS where ID_GERENCIA=".$id_gerencia." order by NAME";
     	$rs=mysql_query($sql);

     	$ret=array();
     	
     	while($row=mysql_fetch_object($rs)){
     		$tmp=new municipioCAEM();
     		
     		$tmp->id_municipio=$row->ID_MUNICIPIO;
     		$tmp->id_cuenca=$row->ID_CUENCA;
     		$tmp->txt_cuenca=toTextCuenca($row->ID_CUENCA);
     		$tmp->name=$row->NAME;
     		$tmp->id_gerencia=$row->ID_GERENCIA;
     		$tmp->txt_gerencia=toTextGerencia($row->ID_GERENCIA);
     		
     		//$tmp->localidades=$this->loadLocalidades($tmp->id_municipio);
     		$tmp->sitios=$this->loadSitios($row->ID_MUNICIPIO);
     					
     		$ret[] = $tmp;
     	}     	
     	mysql_free_result($rs);
	
     	return $ret;
	}
	
public function loadMunicipiosGerenciaOtro($id_gerencia){
	
		$sql="select * from CAT_MUNICIPIOS where ID_GERENCIA=".$id_gerencia." order by NAME";
     	$rs=mysql_query($sql);

     	$ret=array();
     	
     	while($row=mysql_fetch_object($rs)){
     		$tmp=new municipioCAEM();
     		
     		$tmp->id_municipio=$row->ID_MUNICIPIO;
     		$tmp->id_cuenca=$row->ID_CUENCA;
     		$tmp->txt_cuenca=toTextCuenca($row->ID_CUENCA);
     		$tmp->name=$row->NAME;
     		$tmp->id_gerencia=$row->ID_GERENCIA;
     		$tmp->txt_gerencia=toTextGerencia($row->ID_GERENCIA);
     		
     		//$tmp->localidades=$this->loadLocalidades($tmp->id_municipio);
     		$tmp->sitios=$this->loadSitiosOtro($row->ID_MUNICIPIO);
     					
     		$ret[] = $tmp;
     	}     	
     	mysql_free_result($rs);
	
     	return $ret;
	}
	
	public function loadMunicipiosGerenciaSitios($id_gerencia){
	
		$sql="select * from CAT_MUNICIPIOS where ID_GERENCIA=".$id_gerencia." AND ID_MUNICIPIO IN (SELECT ID_MUNICIPIO FROM CAT_SITIOS) order by NAME";
     	$rs=mysql_query($sql);

     	$ret=array();
     	
     	while($row=mysql_fetch_object($rs)){
     		$tmp=new municipioCAEM();
     		
     		$tmp->id_municipio=$row->ID_MUNICIPIO;
     		$tmp->id_cuenca=$row->ID_CUENCA;
     		$tmp->txt_cuenca=toTextCuenca($row->ID_CUENCA);
     		$tmp->name=$row->NAME;
     		$tmp->id_gerencia=$row->ID_GERENCIA;
     		$tmp->txt_gerencia=toTextGerencia($row->ID_GERENCIA);
     		
     		//$tmp->localidades=$this->loadLocalidades($tmp->id_municipio);
     		$tmp->sitios=$this->loadSitios($row->ID_MUNICIPIO);
     					
     		$ret[] = $tmp;
     	}     	
     	mysql_free_result($rs);
	
     	return $ret;
	}
	
}
	
	

?>
