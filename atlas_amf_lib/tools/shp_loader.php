<?
include ('ShapeFile.inc.php');
include('../config/dbconf.php');

//sets the options to show the polygon points, 'noparts' => true would skip that and save time 
$options = array('noparts' => false); 
$shp = new ShapeFile($_GET['CLAVE'].".shp",$options); 


//Dump the ten first records 

while ($record = $shp->getNext() ) { 
//	$dbf_data = $record->getDbfData(); 
	$shp_data = array($record->getShpData()); 
	
	print_r($shp_data);
	//echo "\n".$shp_data[0]['xmax'];
	$sql="INSERT INTO POLIGONOS (CLAVE, XMIN, YMIN, XMAX, YMAX) VALUES ('".$_GET['CLAVE']."',".$shp_data[0]['xmin'].",".$shp_data[0]["ymin"].",".$shp_data[0]["xmax"].",".$shp_data[0]["ymax"].")";
	mysql_query($sql) or die("\n".$sql."\n".mysql_error());
	
	$sql="SELECT MAX(ID_POLIGONO) AS ID_POLIGONO FROM POLIGONOS WHERE CLAVE='".$_GET['CLAVE']."'";
	$rs=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_object($rs);
	
	$ID_POLIGONO=$row->ID_POLIGONO;
	
	$np=$shp_data[0]['numpoints'];
	$puntos=$shp_data[0]['parts'][0]['points'];
	for($i=0;$i<$np;$i++){
		$sql="INSERT INTO POLIGONOS_PUNTOS (ID_POLIGONO,X,Y) VALUES (".$ID_POLIGONO.",".$puntos[$i]['x'].",".$puntos[$i]['y'].")";
		mysql_query($sql) or die("\n".$sql."\n".mysql_error());	
	}
}
?> 