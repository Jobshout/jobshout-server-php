<?php
	include("connect.php");
	session_start();
	
	if($db->query("DELETE FROM jobbriefs WHERE GUID ='".$_REQUEST['GUID']."'")) {
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: jobbriefs.php");
?>