<?php require_once("connect.php"); 
$time=time();
if($users=$db->get_results("select * from users")){
	foreach($users as $user){
		$site_uuid=$db->get_var("select GUID from sites where ID='".$user->SiteID."'");
		$user_uuid=$db->get_var("select uuid from wi_users where code='".$user->Code."'");
		if($site_uuid!='' && $user_uuid!=''){
			if($chk=$db->get_row("select * from wi_user_sites where uuid_site='".$site_uuid."' and uuid_user='".$user_uuid."'")){
			}
			else{
				$db->query("insert into wi_user_sites(uuid, uuid_user, uuid_site, created, modified, server) values(UUID(), '".$user_uuid."', '".$site_uuid."', '".$time."', '".$time."', '4')");
			}
		}
	}
}

?>