<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM categories WHERE GUID ='".$_REQUEST['GUID']."'")){
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: categories.php");
?>