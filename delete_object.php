<?php
	include("connect.php");
	$result= array();
	$object_uuid= isset($_POST['id']) ? $_REQUEST['id'] : '';
	$document_id= isset($_POST['doc_id']) ? $_REQUEST['doc_id'] : '';
	if($object_uuid!="" && $document_id!=""){
		if($db->query("DELETE FROM objects WHERE GUID ='".$object_uuid."' and DocumentID='".$document_id."'")){
			$result["success"]="Deleted successfully!";
		}else{
			$result["error"]="No such object exists for deletion!";
		}
	}else{
		$result["error"]="No such object exists for deletion!";
	}
	echo json_encode($result);
?>