<?php


require_once('../atlas/model/geoPoly.php');
require_once('../atlas/model/geoPoint.php');

class geoController {
	
	public function savePoly($poly){
		
		$res=$poly->id_evento;
		
		$sql="INSERT INTO POLIGONOS (ID_EVENTO) VALUES(".$poly->id_evento.")";
		mysql_query($sql) or ($res.=mysql_error()."\n");
		
		$sql="SELECT MAX(ID_POLIGONO) AS ID FROM POLIGONOS WHERE ID_EVENTO=".$poly->id_evento;
		$rs=mysql_query($sql);
		$row=mysql_fetch_object($rs);
		$id_poligono=$row->ID;
		
		for($i=0;$i<count($poly->puntos);$i++){
			$gp=new geoPoint();
			$gp=$poly->puntos[$i];			
			$sql="INSERT INTO POLIGONOS_PUNTOS (ID_POLIGONO,X,Y) VALUES (".$id_poligono.",".$gp->x.",".$gp->y.")";
			mysql_query($sql)  or ($res.=mysql_error()."\n");
		}
		return  $res;
	}

	public function loadPoly($id_evento){
		$ret=array();
		
		$sql="select ID_POLIGONO FROM POLIGONOS WHERE ID_EVENTO=".$id_evento;
		$rs=mysql_query($sql);
		while($row=mysql_fetch_object($rs)){
			$p=new geoPoly();
			$p->id_evento=$id_evento;
			$p->puntos=array();
			
			$sql="SELECT * FROM POLIGONOS_PUNTOS WHERE ID_POLIGONO=".$row->ID_POLIGONO;
			$rs2=mysql_query($sql);
			while($row2=mysql_fetch_object($rs2)){
				$gp=new geoPoint();
				$gp->x=$row2->X;
				$gp->y=$row2->Y;
				$p->puntos[]=$gp;				
			}
			$ret[]=$p;			
		}	
		return $ret;
	}
}
	
	

?>
