<?php
	include("connect.php");
	
	if($db->query("DELETE FROM tokens WHERE GUID ='".$_REQUEST['GUID']."'")) {
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: tokens.php");
?>