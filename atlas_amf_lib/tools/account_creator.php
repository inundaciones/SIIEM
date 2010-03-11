<?php
	include_once('../config/dbconf.php');
	include_once('../includes/aux.php');
	
	$sql="select URI_USER FROM TBL_USUARIOS";
	$rs=mysql_query($sql) or die(mysql_error());
	while($row=mysql_fetch_object($rs)){
		$sql="UPDATE TBL_USUARIOS SET PASSWORD=AES_ENCRYPT('".generatePassword(5,4)."','CAEM') WHERE URI_USER='".$row->URI_USER."'";
		mysql_query($sql) or die(mysql_error());
	}
	
	
	$sql="DELETE FROM TBL_USUARIOS WHERE TIPO='M'";
	
	mysql_query($sql);
	
	echo "\nBORRADOS<br/>";
	
	$sql="SELECT ID_MUNICIPIO, CLAVE FROM `CAT_MUNICIPIOS` WHERE CLAVE<>'XXX'";
	
	$rs=mysql_query($sql) or die(mysql_error());
	
	while($row=mysql_fetch_object($rs)){
		
		$sql="insert into TBL_USUARIOS values ('".sha1($row->CLAVE)."','".$row->CLAVE."'";
		$sql.=",'".$row->CLAVE."'";
		$sql.=",AES_ENCRYPT('".generatePassword(5,4)."','CAEM')";
		$sql.=",'".toTextMunicipio($row->ID_MUNICIPIO)."'";
		$sql.=",".$row->ID_MUNICIPIO;
		$sql.=",'M'";
		$sql.=")";
		
		mysql_query($sql) or die(mysql_error());
		
		echo "\nInsertado ".$row->CLAVE."<br/>";
	}
?>