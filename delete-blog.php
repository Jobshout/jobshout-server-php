<?php
	session_start();
	include("connect.php");
	$delete_guid= isset($_REQUEST['GUID']) ? $_REQUEST['GUID'] : '';
	if($db->query("DELETE FROM documents WHERE GUID ='".$_REQUEST['GUID']."'")){
		$sql=$db->query("delete from blog_comments where blog_uuid='".$delete_guid."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: blogs.php");
?>