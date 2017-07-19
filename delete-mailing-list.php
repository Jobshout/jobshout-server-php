<?php
	include("connect.php");
	
	if($db->query("DELETE FROM wi_mailinglists WHERE uuid ='".$_REQUEST['uuid']."'")) {
		$db->query("DELETE FROM wi_mailinglist_contacts WHERE uuid_mailinglist ='".$_REQUEST['uuid']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: mails.php");
?>