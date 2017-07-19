<?php
require_once("connect.php"); 
require_once("include/functions.php"); 
$result=array();
$uuid = isset($_POST['uuid']) ? $_POST['uuid'] : '';
if($uuid!=''){
	
	$linkUUIDArr= explode(",",$uuid);
	$linkUUIDStr =  "'" . implode("','", $linkUUIDArr) . "'";
	$link_items= $db->get_results("SELECT * FROM link_items where GUID IN ($linkUUIDStr)");
	//$db->debug();
	
	if(count($link_items)>0){
		$keyStr=""; 
		$valueStr="";
		$suucessCountNum=0;
		foreach($link_items as $link_item){
			foreach($link_item as $key => $value){
				if($keyStr!=""){
					$keyStr.=', '.$key;
				}else{
					$keyStr.=$key;
				}
				if($key=="GUID"){
					$value="'".UniqueGuid('link_items', 'GUID')."'";
				}
				if($key=="item_label"){
					$value="'".addslashes($value." (Copy)")."'";
				}
				if($key=="created" || $key=="modified"){
					$value=time();
				}
				if($key=="document_id" && ($value=='' || $value==null)){
					$value=0;
				}
				if($key=="query_string" || $key=="item_link" || $key=="link_uuid" || $key=="item_type"){
					$value="'".$value."'";
				}
				
				if($valueStr!=""){
					$valueStr.=', '.$value;
				}else{
					$valueStr.=$value;
				}
			}
			$insertQuery= "INSERT INTO link_items (".$keyStr.") VALUES (".$valueStr.")";
			if($db->query($insertQuery)){
				$suucessCountNum++;
			}
			$keyStr=""; 
			$valueStr="";
		}
		if($suucessCountNum>0){
			$result['success']= "Copied item(s) successfully!";
			$result['suucessCountNum']= $suucessCountNum;
		}
	}else{
		$result['error']= "Please select atleast one valid bookmark item!";
	}
}else{
	$result['error']= "Please select atleast one bookmark item!";
}
echo json_encode($result);
?>