<?php require_once("connect.php"); 

if($users=$db->get_results("select * from wi_users where ID<2135433882")){
	foreach($users as $user){
		$user_id=$user->ID;
		$user_pwd=md5($user->password);
		
		$db->query("update wi_users set password='".$user_pwd."' where ID='".$user_id."'");			
	}
}

			
?>