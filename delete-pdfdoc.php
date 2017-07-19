<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM pdf_docs WHERE uuid ='".$_REQUEST['GUID']."'")) {
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: pdf_docs.php");
?>