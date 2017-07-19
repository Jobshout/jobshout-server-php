<?php
	include("connect.php");
	
	if($db->query("DELETE FROM contacts WHERE  GUID ='".$_GET['GUID']."'")) {
		$db->query("DELETE FROM wi_mailinglist_contacts WHERE uuid_contact ='".$_GET['GUID']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: contacts.php");
?>