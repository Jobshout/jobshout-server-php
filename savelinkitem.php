<?php
require_once("connect.php"); 

require_once("include/functions.php"); 
$result=array();
$time= time();
$item_uuid = isset($_POST['item_uuid']) ? $_POST['item_uuid'] : '';
$link_uuid = isset($_POST['link_uuid']) ? $_POST['link_uuid'] : '';
$item_label = isset($_POST['item_label']) ? addslashes($_POST['item_label']) : '';
$item_type = isset($_POST['item_type']) ? $_POST['item_type'] : '';
$item_link = isset($_POST['item_link']) ? addslashes($_POST['item_link']) : '';
$item_level = isset($_POST['item_level']) ? $_POST['item_level'] : '';
$item_query_string = isset($_POST['item_query_string']) ? $_POST['item_query_string'] : '';

if($item_level==""){
	$item_level=0;
}
$item_sort_order = isset($_POST['item_sort_order']) ? $_POST['item_sort_order'] : '';
if($item_sort_order==""){
	$item_sort_order=0;
}
$item_target = isset($_POST['item_target']) ? $_POST['item_target'] : '';
if($item_target==""){
	$item_target=1;
}
$item_active = isset($_POST['item_active']) ? $_POST['item_active'] : '';
if($item_active==""){
	$item_active=0;
}
$item_related_doc= isset($_POST['item_related_doc']) ? $_POST['item_related_doc'] : '';
if($item_related_doc=="" || $item_related_doc==null || $item_related_doc=="null"){
	$item_related_doc=0;
}

//echo $link_uuid."==".$item_label."==".$item_link."===".$item_related_doc."==".$item_type;exit;
if($link_uuid!='' &&  $item_label!="" && ($item_link!="" || $item_related_doc!=0) && $item_type!=""){
	if($checkBookmarksaved=$db->get_var("SELECT GUID FROM links where GUID = '$link_uuid' and Type='items'")){
		if($item_uuid!=""){
			$existItems= $db->get_row("SELECT GUID FROM link_items where link_uuid = '$link_uuid' and GUID='$item_uuid'");
			if(count($existItems) >0){
				if($db->query("UPDATE link_items SET modified='$time', item_label='$item_label', item_link='$item_link', item_type='$item_type', level='$item_level', sort_order='$item_sort_order', source='$item_target', active='$item_active', document_id='$item_related_doc', query_string='$item_query_string' WHERE GUID ='$item_uuid'")) {
					$result['success']= $item_label." item updated successfully!";
				}
			}else{
				$result['error']= "No such bookmark item exist!";
			}
		}else{
			$existItems= $db->get_results("SELECT GUID FROM link_items where link_uuid = '$link_uuid' and item_label='$item_label'");
			if(count($existItems) >0){
				$result['error']= "This item already exists!";
			}else{
				$newUUID=UniqueGuid('link_items', 'GUID');
				if($db->query("INSERT INTO link_items (GUID, link_uuid, item_label, item_type, item_link, level, sort_order, source, active, document_id, created, modified, query_string) VALUES ('$newUUID', '$link_uuid', '$item_label', '$item_type', '$item_link', '$item_level', '$item_sort_order', '$item_target', '$item_active', '$item_related_doc', '$time', '$time', '$item_query_string')")){
					//$db->debug();
					$result['success']= $item_label." item saved successfully!";
				}
			}
		}
	}else{
		$result['error']= "Please save the bookmark first, then you can add items!";
	}
}else{
	$result['error']= "Please add all the required fields!";
}
echo json_encode($result);
?>