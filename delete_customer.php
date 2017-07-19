5<?php
	include("connect.php");
	
	if($db->query("DELETE FROM customers WHERE  GUID ='".$_GET['GUID']."'")) {
		$db->query("DELETE FROM contacts WHERE  Customer_GUID ='".$_GET['GUID']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: customers.php");
?>