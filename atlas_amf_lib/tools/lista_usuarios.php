<table width="800px" border="1" >
<tr>
	<td>LOGIN</td>
	<td>NOMBRE</td>
	<td>CONTRASENA</td>
	<td>TIPO</td>
</tr>
 <?php
	include_once('../config/dbconf.php');
	
	
	$sql="SELECT LOGIN, NAME, AES_DECRYPT(PASSWORD, 'CAEM') AS PASSWORD, TIPO FROM TBL_USUARIOS ORDER BY LOGIN";
	$rs=mysql_query($sql) or die(mysql_error());
	
	while($row=mysql_fetch_object($rs)){
		echo "<tr>\n";
		echo "\t\t<td>".$row->LOGIN."</td>\n";
		echo "\t\t<td>".$row->NAME."</td>\n";
		echo "\t\t<td>".$row->PASSWORD."</td>\n";
		echo "\t\t<td>".$row->TIPO."</td>\n";
		echo "</tr>\n";
	}
?>
</table>