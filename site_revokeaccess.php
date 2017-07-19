<?php
require_once("connect.php"); 
$delacc_site=$_POST['delaccsite_guid'];
if($db->query("Delete FROM wi_user_sites WHERE uuid='$delacc_site'")){
 echo "Removed Successfully";
}
?>