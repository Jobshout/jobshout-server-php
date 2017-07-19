<?php
session_start();
if(!isset($_SESSION['UserEmail']) || $_SESSION['UserEmail'] =='') {
       header("location:index.php");
}
require_once("connect.php"); 
$site_srch_keyword=$_POST['site_srch_keyword'];
?>

<li> <a href="javascript:void(0)" onclick="chng_site('all')">All</a></li>
<?php
	$user=$db->get_row("select * from wi_users where code='".$_SESSION['UserEmail']."'");
	if($user){
		if($user->access_rights_code>=11){
		if($sites=$db->get_results("select ID, GUID, Name, Code from sites where Code like '%".$site_srch_keyword."%' OR Name like '%".$site_srch_keyword."%'  order by zStatus asc, Name ASC limit 0,25 ")) {
			foreach($sites as $site)
				{ ?>				
					<li> <a href="javascript:void(0)" onClick="chng_site('<?php echo $site->GUID; ?>')" ><?php echo $site->Code; ?></a></li>
		<?php } 
		 } 
		 } 
		 else {
			if($sites=$db->get_results("select ID, GUID, Name, Code from sites where GUID in (select uuid_site from wi_user_sites where uuid_user='".$user->uuid."') and (Code like '%".$site_srch_keyword."%' OR Name like '%".$site_srch_keyword."%') order by zStatus asc, Name ASC limit 0,25")) {
				foreach($sites as $site)
				{ ?>				
					<li> <a href="javascript:void(0)" onClick="chng_site('<?php echo $site->GUID; ?>')" ><?php echo $site->Code; ?></a></li>
		<?php } 
		 } 
		 } 
	 }
?>