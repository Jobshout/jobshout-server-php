<?php require_once("connect.php"); 
if(isset($_POST['site_id']) && $_POST['site_id']!=''){
	$site_code=$db->get_var("select Code from sites where ID='".$_POST['site_id']."'");
	echo $site_code;
}
?>