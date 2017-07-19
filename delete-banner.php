<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM pictures WHERE GUID ='".$_REQUEST['GUID']."'")) {
		$db->query("delete FROM picturecategories WHERE Picture_GUID='".$_REQUEST['GUID']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: banners.php");
?>