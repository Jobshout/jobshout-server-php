<?php
	include("connect.php");
	
	if($db->query("DELETE FROM site_options WHERE GUID ='".$_REQUEST['GUID']."'")) {
		$_SESSION['ins_message'] = "Deleted successfully ";	
	}
	//$db->debug();
	if(isset($_REQUEST['page']) && $_REQUEST['page']!=""){
		header("Location: ".$_REQUEST['page']);
	}else{
		header("Location: site_settings.php");
	}
?>