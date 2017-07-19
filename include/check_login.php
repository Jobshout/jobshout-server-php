<?php
if(!isset($_SESSION['UserEmail']) || $_SESSION['UserEmail'] =='') {
       header("location:index.php");
}

$user_details=$db->get_row("select * from wi_users where code='".$_SESSION['UserEmail']."'");
$user_access_level= $user_details->access_rights_code;
$login_user_uuid= $user_details->uuid;
$login_user_code= $user_details->code;

?>