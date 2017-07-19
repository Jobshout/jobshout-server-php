<?php
require_once("connect.php"); 

$result=array();
$link_uuid = isset($_GET['e']) ? $_GET['e'] : '';
$uuid = isset($_GET['uuid']) ? $_GET['uuid'] : '';
if($link_uuid!=''){
	if($checkBookmarksaved=$db->get_var("SELECT GUID FROM links where GUID = '$link_uuid'")){
		if($uuid!=""){
			$links= $db->get_results("SELECT * FROM link_items where GUID='$uuid' And link_uuid = '$link_uuid'");
		}else{
			$links= $db->get_results("SELECT * FROM link_items where link_uuid = '$link_uuid'");
		}
		//$db->debug();
		if(count($links)>0){
			$row = array();
			foreach($links as $link){
				//$db->query("UPDATE link_items SET item_link='sitemap-generate.php' WHERE GUID ='$link->GUID'");
					
				$row['GUID']=$link->GUID;
				$row['item_type']=$link->item_type;
				$row['level']=$link->level;
				$row['sort_order']=$link->sort_order;
				$row['source']=$link->source;
				$row['item_label']=$link->item_label;
				$row['item_link']=$link->item_link;
				$docID=$link->document_id;
				$row['docID']=$docID;
				
				if($link->query_string != ""){
					$row['query']=$link->query_string;
				}else{
					$row['query']="";
				}
				if($doc=$db->get_row("SELECT Document, Code FROM documents where ID = '$docID'")){
					$row['doc']=$doc->Document." (".$doc->Code.")";
				}else{
					$row['doc']="";
				}
				if($link->active != 1){
					$row['Status']=  "Inactive";
				} else { 
					$row['Status']= "Active"; 
				}
				$result['aaData'][] = $row;
			}
		}else{
			$result['error']= "No bookmark items exist!";
		}
	}else{
		$result['error']= "No such bookmark exists!";
	}
}else{
	$result['error']= "Please select valid bookmark!";
}
echo json_encode($result);
?>