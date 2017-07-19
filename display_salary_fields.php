<?php
require_once("connect.php"); 

$site_id=$_POST['site_id'];

if($session_site_guid = $db->get_var("select GUID from sites where ID = '".$site_id."'")){
	if($sys_config_value = $db->get_var("SELECT value FROM site_options where site_guid ='".$session_site_guid."' and name='job_editor_salary_fields' and status=1")){ 
		echo $sys_config_value;
	}
}
?>
