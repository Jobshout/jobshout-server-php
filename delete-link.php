<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM links WHERE GUID ='".$_REQUEST['GUID']."'")){
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: links.php");
?>