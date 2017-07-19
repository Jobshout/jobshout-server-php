<?php
	session_start();
	include("connect.php");
	
	$doc_id=$db->get_var("select ID from documents WHERE GUID ='".$_REQUEST['GUID']."'");
	if($db->query("DELETE FROM documents WHERE GUID ='".$_REQUEST['GUID']."'")) {
		$db->query("delete FROM documentcategories WHERE DocumentID='".$doc_id."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: jobs.php");
?>