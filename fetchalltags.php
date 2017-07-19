<?php
session_start();
require_once("connect.php"); 

$rowTagsArr=array();

/**
if($tags=$db->get_results("SELECT fts_content FROM documents_fts where type='tags'")){
	
	$rowTagsStr="";
	$rowTagsArr=array();
	foreach($tags as $tagStr){
		$explodeTags=explode(",",$tagStr->fts_content);
		foreach($explodeTags as $tag){
			if (in_array($tag, $rowTagsArr)) {
  				//echo $tag."====";
			}else{
				$rowTagsArr[]=$tag;
				if($rowTagsStr!=""){
					$rowTagsStr.=",".$tag;
				}else{
					$rowTagsStr.=$tag;
				}
			}
		}
	}
}
**/
$newKeyArrStr = array();
if($document_tags=$db->get_results("SELECT Tags FROM documents where Tags<>''")){
  foreach($document_tags as $tagStr){
    $explodeRowTags=explode(",",$tagStr->Tags);
    foreach($explodeRowTags as $tag){
      if (in_array($tag, $rowTagsArr)) {
        //tag already exists                                                                                                                                                                                                                                                   
      }else{
        $rowTagsArr[]=$tag;
      }
    }
  }
}

if($tags=$db->get_results("SELECT fts_content FROM documents_fts where type='tags'")){
  foreach($tags as $tagStr){
    $explodeTags=explode(",",$tagStr->fts_content);
    foreach($explodeTags as $tag){
      if (in_array($tag, $rowTagsArr)) {
        //tag already exists                                                                                                                                                                                                                                                   
      }else{
        $rowTagsArr[]=$tag;
      }
    }
  }
}
if(count($rowTagsArr)>0){
  
  foreach($rowTagsArr as $val){
    $tagLengthNum=strlen($val);
    if($tagLengthNum>=5){
      array_push($newKeyArrStr, $val);
    }else{
      if(strpos($val, '_')){
	$tempVal=substr($val,0,strpos($val, '_'));
      }else {
	$tempVal=$val;
      }
      array_push($newKeyArrStr, $tempVal);
    }
  }
}
echo json_encode($newKeyArrStr);

?>