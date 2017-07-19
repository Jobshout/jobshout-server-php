<?php
session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM videos WHERE uuid ='".$_REQUEST['GUID']."'")){
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: videos.php");
?>