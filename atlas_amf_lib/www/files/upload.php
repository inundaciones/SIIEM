<?php
	
		include('../../config/dbconf.php');		

		$fileName = $_FILES['userfile']['name'];
		$tmpName  = $_FILES['userfile']['tmp_name'];
		$fileSize = $_FILES['userfile']['size'];
		$fileType = $_FILES['userfile']['type'];
	
		$fp      = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);
		
		$name=explode('.',$fileName);
	
		echo "hola";
		
		$sql = "insert into FILES (URI_FILE, ID_EVENTO, NAME, TYPE, SIZE, CONTENT) VALUES (";
		$sql.="'".sha1($content)."','".$_POST['ID_EVENTO']."'";
		$sql.=", '".$fileName."','".strtoupper($name[sizeof($name)-1])."','".$fileSize."','".$content."')";

		mysql_query($sql) or die(mysql_error());
		

?>