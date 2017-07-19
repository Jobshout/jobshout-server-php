<?php require_once("connect.php"); 
$where='';
if(isset($_POST['site_id']) && $_POST['site_id']!=''){
	$site_guid=$db->get_var("select GUID from sites where ID='".$_POST['site_id']."'");
	$user_uuids=$db->get_col("select uuid_user from wi_user_sites where uuid_site='".$site_guid."'");
	$where="and uuid in ('".implode("','", $user_uuids)."') or SiteID='".$site_guid."'";
}


?>


<option value="">-- Select User--</option>
		<?php
			
			if($users = $db->get_results("SELECT ID,firstname,lastname FROM `wi_users` WHERE status='1' $where ORDER BY ID")) {
			foreach($users as $user){
				$UserID=$user->ID;
				$name=$user->firstname.' '.$user->lastname;
				if($name!='') {
				?>
				<option value='<?php echo $UserID; ?>' <?php if(isset($_POST['usr_id']) && $_POST['usr_id']==$UserID) { ?> selected="selected" <?php } ?> >
						<?php echo $name; ?>
				</option>
				<?php
				}
			} }
		?>