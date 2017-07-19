<?php
	session_start();
	include("connect.php");
	
	if($db->query("DELETE FROM kb_article WHERE uuid ='".$_REQUEST['GUID']."'")) {
		$db->query("delete FROM kb_article_categories WHERE article_uuid ='".$_REQUEST['GUID']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: kb_articles.php");
?>