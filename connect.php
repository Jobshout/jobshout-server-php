<?php

include_once "con_details.php";

// Include ezSQL core
	include_once "ez_sql_core.php";
	// Include ezSQL database specific component
	include_once "ez_sql_mysql.php";

 $db = new ezSQL_mysql($db_user,$db_pass,$db_name,$db_host);	

?>