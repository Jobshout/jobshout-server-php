<?php
require_once("connect.php"); 
$delmaster_site=$_POST['delmaster_guid'];
$delrelated_site=$_POST['delrelatedsite_guid'];
if($db->query("Delete FROM wi_related_sites WHERE uuid_master_site='$delmaster_site' and uuid_related_site='$delrelated_site'")){
	echo "Deleted Successfully";
}

?>