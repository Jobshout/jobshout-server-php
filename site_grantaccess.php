<?php
require_once("connect.php"); 
require_once("include/functions.php"); 
$usr_uuid=$_POST['usr_uuid'];
$site_id=$_POST['id'];
$old_site=$_POST['old_id'];
$time = time();


$site = $db->get_row("SELECT * FROM sites where ID = '$site_id'");
$acc_site= $site->GUID;

if($db->get_row("SELECT * FROM wi_user_sites where uuid_user = '$usr_uuid' and uuid_site='$acc_site' and uuid!='$old_site'")){
	$res = array();
	$res['error']= 'Already assigned';
	if($old_site!=''){
		if($old_site_details=$db->get_row("select * from wi_user_sites where uuid='$old_site'")){
			if($site = $db->get_row("SELECT * FROM sites where GUID = '".$old_site_details->uuid_site."'")){
				$res['site_name']= $site->Name.' ('.$site->Code.')';
				$res['id']= $site->ID;
			}
		}
	}
	echo json_encode($res);
}else{
	if($old_site!=''){
		$db->query("delete from wi_user_sites where uuid='$old_site'");
	}
	$uuid=UniqueGuid('wi_user_sites', 'uuid');	
	if($db->query("INSERT INTO wi_user_sites (uuid, uuid_user, uuid_site, created, modified, server) VALUES ('$uuid', '$usr_uuid', '$acc_site', '$time', '$time', 4)")){

		$res = array();
		$res['succ']= 'Successfully assigned';
		$res['site_name']= $site->Name.' ('.$site->Code.')';
		$res['uuid']= $uuid;
		echo json_encode($res);
	}
}
?>
