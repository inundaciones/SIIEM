<?php

	$input = $_POST['file'];
	try{
		unlink('snapshots/'.$_POST['NAME']);
	}catch (Exception $e){
		
	}
	$fp = fopen('snapshots/'.$_POST['NAME'], 'w');
	fwrite($fp, base64_decode($input));
	fclose($fp);
	echo "<FILE></FILE>";
?>