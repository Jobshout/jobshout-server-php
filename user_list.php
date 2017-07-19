<?php require_once("connect.php"); 
session_start();
$where='';

$login_usr_id=$_POST['login_usr_id'];

if(isset($_POST['site_id']) && $_POST['site_id']!=''){
	$site_selected=$db->get_row("select ID, GUID from sites where ID='".$_POST['site_id']."'");
	$user_uuids=$db->get_col("select uuid_user from wi_user_sites where uuid_site='".$site_selected->GUID."'");
	$where="and (uuid in ('".implode("','", $user_uuids)."') or SiteID='".$site_selected->ID."' or ID ='$login_usr_id' )";
}elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!=''){
	
	$session_sites=$db->get_results("select ID, GUID from sites where ID in ('".$_SESSION['site_id']."')");
	$user_uuids=array();
	foreach($session_sites as $session_site){
		
		$user_uuids= array_merge($user_uuids, $db->get_col("select uuid_user from wi_user_sites where uuid_site='".$session_site->GUID."'"));
	}
	$where="and (uuid in ('".implode("','",$user_uuids)."') or ID ='$login_usr_id')";
}

$curr_usr_id=$_POST['usr_id'];
if($curr_usr_id!=''){
	if($chk_usr= $db->get_row("select * from wi_users where ID='$curr_usr_id'")){	
	}
	else{
		$curr_usr_id=$login_usr_id;
	}
}
else{
	$curr_usr_id=$login_usr_id;
}


?>


<option value="">-- Select User--</option>
		<?php
			
			if($users = $db->get_results("SELECT ID,firstname,lastname,code FROM `wi_users` WHERE status='1' $where ORDER BY ID")) {
			foreach($users as $user){
				$UserID=$user->ID;
				$name=$user->firstname.' '.$user->lastname;
				if(trim($name)=='') {
					$name=$user->code;
				}
				?>
				<option value='<?php echo $UserID; ?>' <?php if(isset($curr_usr_id) && $curr_usr_id==$UserID) { ?> selected="selected" <?php } ?> >
						<?php echo $name; ?>
				</option>
				<?php
				
			} }
		?>