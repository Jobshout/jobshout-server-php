<?php
	include("connect.php");
	
	if($db->query("DELETE FROM wi_campaigns WHERE uuid ='".$_GET['GUID']."'")) {
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: campaigns.php");
?>