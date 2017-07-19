<?php
require_once("connect.php"); 
$site_id=$_POST['site_id'];

if($session_site_guid = $db->get_var("select GUID from sites where ID = '".$site_id."'")){
	if($sys_config_value = $db->get_var("SELECT config_value FROM sys_config where uuid_site ='".$session_site_guid."' and config_name='job_editor_show_lat_long'")){ 
		echo $sys_config_value;
	}
}
?>
