<?php
require_once("connect.php"); 
$master_site=$_POST['site_uuid'];
$site_id=$_POST['id'];
$old_site=$_POST['old_id'];


$site = $db->get_row("SELECT * FROM sites where ID = '$site_id'");
$related_site= $site->GUID;

if($db->get_row("SELECT * FROM wi_related_sites where uuid_master_site = '$master_site' and uuid_related_site='$old_site'")){
	$res = array();
	$res['error']= 'Already assigned';
	if($old_site!=''){
		if($site = $db->get_row("SELECT * FROM sites where GUID = '".$old_site."'")){
			$res['site_name']= $site->Name.' ('.$site->Code.')';
			$res['id']= $site->ID;
		}
	}
	echo json_encode($res);
}else{
	if($old_site!=''){
		$db->query("delete from wi_related_sites where uuid_related_site='$old_site'");
	}
	if($db->query("INSERT INTO wi_related_sites (uuid_master_site, uuid_related_site) VALUES ('$master_site', '$related_site')")){

		$res = array();
		$res['succ']= 'Successfully assigned';
		$res['site_name']= $site->Name.' ('.$site->Code.')';
		$res['uuid']= $related_site;
		echo json_encode($res);
	}
}
?>
