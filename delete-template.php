<?php
	session_start();
	include("connect.php");
	
	$file_path=$db->get_var("select Path from templates where GUID ='".$_REQUEST['GUID']."'"); 
	if($db->query("DELETE FROM templates WHERE GUID ='".$_REQUEST['GUID']."'")) {
		if(file_exists($file_path)){
			unlink($file_path);
		}
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	header("Location: templates.php");
?>