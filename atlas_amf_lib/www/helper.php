<?php
	include_once('../config/dbconf.php');
	include_once('../includes/aux.php');
	include('../atlas/controller/usuarios.php');
	
/*	$sql="SELECT * from tmp";
	$rs=mysql_query($sql) or die(mysql_error());
	
	while($row=mysql_fetch_array($rs)){
		
		for ($i=0;$i<8;$i++){		
			if($row[2+($i*3)]!='.'){
				$sql="INSERT INTO DIRECTORIO_MUNICIPIOS (ID_MUNICIPIO, NAME, DOM, TEL) VALUES ";
				$sql.=" (".$row[0].", '".$row[2+($i*3)]."', '".$row[3+($i*3)]."','".$row[4+($i*3)]."')";	
				mysql_query($sql) or die(mysql_error());				
			}
		}
		
	}*/
	
	/*$sql="SELECT * from CAT_ACCIONES";
	$rs=mysql_query($sql) or die(mysql_error());
	while($row=mysql_fetch_array($rs)){
		print_r($row);
	}*/
	
	echo realpath("zend.php");
?>