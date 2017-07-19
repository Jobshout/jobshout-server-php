<?php
session_start();
require_once("connect.php");
	
$srch=$_REQUEST['srch'];

if(isset($_SESSION['UserEmail']) && $_SESSION['UserEmail']!=''){
	$user=$db->get_row("select * from wi_users where code='".$_SESSION['UserEmail']."'");
}
if(!isset($_SESSION['UserEmail']) || $user->access_rights_code>=11){
$i=0;
if($sites=$db->get_results("select id, GUID, name, Code from sites where Code like '%".$srch."%' or name like '%".$srch."%' order by zStatus asc, name ASC limit 0,100")){
	foreach($sites as $site) {
		$result[$i]['id']=$site->id;
		$result[$i]['value']=$site->name." (".$site->Code.")";
		
		$i++;
	}
	echo json_encode($result);
}
} else {
$i=0;
if($sites=$db->get_results("select id, GUID, name, Code from sites where GUID in (select uuid_site from wi_user_sites where uuid_user='".$user->uuid."') and (Code like '%".$srch."%' or name like '%".$srch."%') order by zStatus asc, name ASC limit 0,100")){
	foreach($sites as $site) {
		$result[$i]['id']=$site->id;
		$result[$i]['value']=$site->name." (".$site->Code.")";
		
		$i++;
	}
	echo json_encode($result);
}

}
?>
