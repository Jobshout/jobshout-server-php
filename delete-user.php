<?php
	session_start();	
	include("connect.php");
	//$db = new ezSQL_mysql('root','14141714','jobshout_live','localhost');
	
	if($db->query("DELETE FROM wi_users WHERE uuid ='".$_REQUEST['GUID']."'")) {
		$db->query("DELETE FROM wi_user_sites WHERE uuid_user ='".$_REQUEST['GUID']."'");
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: users.php");
?>