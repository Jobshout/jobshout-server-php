<?php 
session_start();
require_once("connect.php"); 

$user=$db->get_var("select user_uuid from wi_tokens where uuid='".$_GET['token']."'");

$db->query("update wi_users set status=1 where uuid='".$user."'");
$_SESSION['activate_msg']="Your account have been successfully activated";
echo "<script>document.location.href='index.php'</script>";
?>
                           
