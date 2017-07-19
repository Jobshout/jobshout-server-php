<?php
session_start();
require_once "../../con_details.php";
require_once "datatable-connect.php";			
require_once("../../constants.php");
require_once "functions.php";

$user_query=mysql_query("select * from wi_users where code='".$_SESSION['UserEmail']."'");
while($user_details=mysql_fetch_array($user_query)) {
	$user_access_level= $user_details['access_rights_code'];
	$login_user_uuid= $user_details['uuid'];
	$login_user_code= $user_details['code'];
}

		
?>
