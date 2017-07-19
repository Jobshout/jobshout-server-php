<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM web_enquiries WHERE GUID ='".$_REQUEST['GUID']."'")){
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: enquiries.php");
?>