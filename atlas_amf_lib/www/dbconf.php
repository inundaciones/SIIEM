	<?php
	define("DATABASE_SERVER", "localhost");
	define("DATABASE_USERNAME", "equidna_caem");
	define("DATABASE_PASSWORD", "c@3m");
	define("DATABASE_NAME", "equidna_caem");
	
	mysql_connect(DATABASE_SERVER, DATABASE_USERNAME, DATABASE_PASSWORD);
	mysql_select_db(DATABASE_NAME);
?>