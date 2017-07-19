<?php
require_once("connect.php"); 

require_once("include/functions.php"); 
$result=array();
$uuid = isset($_POST['uuid']) ? $_POST['uuid'] : '';
$link_uuid = isset($_POST['link_uuid']) ? $_POST['link_uuid'] : '';
if($uuid!=''){
	if($existItems= $db->get_var("SELECT GUID FROM link_items where link_uuid = '$link_uuid' and GUID='$uuid'")){
		if($db->query("DELETE FROM link_items WHERE GUID ='".$uuid."'")) {
			$result['success']= "Bookmark item deleted successfully!";
		}
	}else{
		$result['error']= "This item doesn't exists!";
	}
}else{
	$result['error']= "Please select valid bookmark item to delete!";
}
echo json_encode($result);
?>