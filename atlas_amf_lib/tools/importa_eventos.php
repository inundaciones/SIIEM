<?php

include_once '../config/dbconf.php';

$sql="SELECT * FROM tmp";
$rs=mysql_query($sql) or die(mysql_error());

$z=0;

while($row=mysql_fetch_object($rs)){
	$sql="select ID_SITIO FROM CAT_SITIOS WHERE CLAVE='".$row->CLAVE."'";
	$rsSitio=mysql_query($sql) or die(mysql_error());
	$rowSitio=mysql_fetch_object($rsSitio);

	$anhios=explode("-",$row->ANHIOS);
	for($i=0;$i<count($anhios);$i++){
		if($anhios[$i]<1000){
			$anhios[$i]=$anhios[$i]+2000;
		}
		$sql="INSERT INTO TBL_EVENTOS (ID_SITIO, FECHA, ID_EVENTO, URI_USER, COLONIA, POBLACION) ";
		$sql.="VALUES(".$rowSitio->ID_SITIO.",'".$anhios[$i]."-07-01',".$row->EVENTO.",'TEST_LOAD','".$row->COLONIA."','".$row->POB_MAX."')";
		mysql_query($sql) or die(mysql_error()."<br/>\n".$sql);
		$z++;
		
		echo $z." registros insertados</br>\n";
	}
	
}


?>