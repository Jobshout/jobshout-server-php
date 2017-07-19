<?php
ini_set('max_execution_time', 300);
session_start();
require_once("connect.php");
$output=array();
if($_REQUEST['sel_id']!='') {
	$chk=explode(",", $_REQUEST['sel_id']);
	$json_result=array();
	foreach($chk as $job_uuid){
		if($docDetails=$db->get_row("select * from documents WHERE GUID ='".$job_uuid."'")){
			$rowArr= array();
			$document_id=$docDetails->ID;
			foreach($docDetails as $key => $value) {
        		if($key!="Body"){
					$rowArr[$key]=$value;
				}
			}
			if($doc_categories=$db->get_results("select * from documentcategories WHERE DocumentID ='".$document_id."'")){
				$docatArr= array();
				foreach($doc_categories as $key => $value){
					$docatArr[$key]=$value;
				}
				$rowArr['documentcategories']=$value;
			}
     		//delete document and its related documentcategories
			if($db->query("DELETE FROM documents WHERE ID ='".$document_id."'")) {
				$db->query("delete FROM documentcategories WHERE DocumentID='".$document_id."'");
				$json_result[] = $rowArr;
			}
		}
	}
	
	if(count($json_result)>0){
		$jsonText= json_encode($json_result);
		$generatefilenameStr="deleted-Jobs-".time().".json";
		$checkDir = realpath(dirname(__FILE__)).'/CVS-Deleted-Jobs';
		$directoryExistsBool=false;
		if (is_dir($checkDir)) {
    		$directoryExistsBool=true;
		} else {
   	 		if(mkdir($checkDir, 0777)){
   	 			$directoryExistsBool=true;
   	 		}
		}
		if($directoryExistsBool){
			$createFile = fopen($checkDir."/".$generatefilenameStr, "w");
			if($createFile){
				fwrite($createFile, $jsonText);
				$output['created_file']=$checkDir."/".$generatefilenameStr;
				fclose($createFile);
			}
		}else{
			$output['dir_error']= "no directory found";
		}
		$output['success']="Jobs(s) successfully deleted";
	}else{
		$output['error']="No job(s) found for deletion";
	}
	
}else{
	$output['error']="Please set job(s) to delete";
}
echo json_encode($output);
?>
