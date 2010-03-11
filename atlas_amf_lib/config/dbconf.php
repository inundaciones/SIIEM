	<?php
	define("DATABASE_SERVER", "localhost");
	define("DATABASE_USERNAME", "equidna_caem");
	define("DATABASE_PASSWORD", "c@3m");
	define("DATABASE_NAME", "equidna_caem");
	mysql_pconnect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD);
	mysql_selectdb(DATABASE_NAME);
?>