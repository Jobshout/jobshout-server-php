<?php
	include("connect.php");
	
	if($db->query("DELETE FROM sites WHERE  GUID ='".$_GET['GUID']."'")) {
		$db->query("DELETE FROM usersites WHERE Site_GUID ='".$_GET['GUID']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: sites.php");
?>