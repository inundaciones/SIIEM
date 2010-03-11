<?php

	include('../../config/dbconf.php');
	
	header('Content-Type: application/x-download');
	header("Content-length: ".strlen(GET_THUMB($_GET['URI_FILE'],$_GET['WIDTH'],$_GET['HEIGHT'])));
	header("Content-Disposition: attachment; filename=preview.jpg");
	echo GET_THUMB($_GET['URI_FILE'],$_GET['WIDTH'],$_GET['HEIGHT']);
	
	function GET_THUMB($URI_FILE,$WIDTH,$HEIGHT){
		
		$myFile="../../tmp/".$URI_FILE.$WIDTH."x".$HEIGHT.".xgr";
		//checa si existe el thumb
		if(@$fh = fopen($myFile, 'r')){
			$Data = fread($fh, filesize($myFile));								
			fclose($fh);
			return $Data;
		}else{
		
			//crea archivo temporal
			$sql="select TYPE, CONTENT from FILES where URI_FILE='".$URI_FILE."'";
			$rs=mysql_query($sql) or die(mysql_error());
			$row=mysql_fetch_object($rs);
			$Image=$row->CONTENT;
			
			$fh = fopen($myFile, 'w') or die("can't open file");
			fwrite($fh, $Image);
			fclose($fh);
			
			//checa tamaño empezando por el alto;
			$imgsize=getimagesize($myFile);
			
			$prop=$imgsize[0]/$imgsize[1];		
			
			if($imgsize[0]>=$imgsize[1]){				
				
				$nw_width=$WIDTH;
				$nw_height=$WIDTH/$prop;
				
				if($nw_height>$HEIGHT){
					$scale=$nw_height/$HEIGHT;
					$nw_height=$nw_height/$scale;
					$nw_width=$nw_width/$scale;
				}
			
			}else{
				
				$nw_width=$HEIGHT*$prop;
				$nw_height=$HEIGHT;
				if($nw_width>$WIDTH){
					$scale=$nw_width/$WIDTH;
					$nw_height=$nw_height/$scale;
					$nw_width=$nw_width/$scale;				
				}
				
				
			}			
		
						
			$ext=strtolower($row->TYPE);			
			
			if($ext=='jpg'||$ext=='jpeg'){
				$o_im = imagecreatefromjpeg($myFile);
			}else if($ext=='png'){
				$o_im = imagecreatefrompng($myFile);
			}else if($ext=='gif'){
				$o_im = imagecreatefromgif($myFile);
			}else if($ext=='bmp'){
				$o_im = imagecreatefromwbmp($myFile);
			}
			
			unlink($myFile);
			$o_wd = imagesx($o_im);
			$o_ht = imagesy($o_im);
			$t_im = imagecreatetruecolor($nw_width,$nw_height);
			imagecopyresampled($t_im,$o_im,0,0,0,0,$nw_width,$nw_height,$o_wd,$o_ht);
			imagejpeg($t_im,$myFile);
			
			$fh = fopen($myFile, 'r');
			$Data = fread($fh, filesize($myFile));
			
			fclose($fh);
			if($WIDTH>600||$HEIGHT>400){
				unlink($myFile);
			}
			
			return $Data;		
		}
	}

?>