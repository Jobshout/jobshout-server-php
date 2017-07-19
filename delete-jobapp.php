<?php
	include("connect.php");
	session_start();
	
	if($db->query("DELETE FROM jobapplications WHERE GUID ='".$_REQUEST['GUID']."'")){
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: jobapps.php");
?>