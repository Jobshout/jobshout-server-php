<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM uk_towns_cities WHERE GUID ='".$_REQUEST['GUID']."'")){
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: locations.php");
?>