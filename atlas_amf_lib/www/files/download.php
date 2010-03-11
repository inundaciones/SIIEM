<?php

		
	
	include('../../config/dbconf.php');
	
	
	$sql="select * from FILES where URI_FILE='".$_POST['URI_FILE']."'";
	$rs=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_object($rs);
	
	header('Content-Type: application/x-download');
	header('Content-Type: application/octet-stream');	
	header('Expires: 0');
	header('Pragma: cache');
	header('Cache-Control: private');
	header("Content-Length: ".$row->SIZE);
	header("Content-Disposition: attachment; filename=".$row->NAME);
	
	
	echo $row->CONTENT;
?>