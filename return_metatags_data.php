<?php
require_once("include/lib.inc.php");
require_once("include/Metakeywords.php");

$output = array();
$document_name = isset($_POST['document_name']) ? $_POST['document_name'] : '';
$body = isset($_POST['body']) ? $_POST['body'] : '';
$location = isset($_POST['location']) ? $_POST['location'] : '';
$category1 = isset($_POST['category1']) ? $_POST['category1'] : '';
$category2 = isset($_POST['category2']) ? $_POST['category2'] : '';
$category3 = isset($_POST['category3']) ? $_POST['category3'] : '';
$category4 = isset($_POST['category4']) ? $_POST['category4'] : '';
$category5 = isset($_POST['category5']) ? $_POST['category5'] : '';
$category6 = isset($_POST['category6']) ? $_POST['category6'] : '';
$site_id = isset($_POST['site_id']) ? $_POST['site_id'] : '';
$subtitle = isset($_POST['subtitle']) ? $_POST['subtitle'] : '';
$post_code = isset($_POST['post_code']) ? $_POST['post_code'] : '';
$metaTagsDisplayNum = isset($_POST['metaTagsDisplayNum']) ? $_POST['metaTagsDisplayNum'] : 1;
$searchDisplayNum = isset($_POST['searchDisplayNum']) ? $_POST['searchDisplayNum'] : 0;
$search_keywords = isset($_POST['search_keywords']) ? $_POST['search_keywords'] : 0;

$categoriesStr='';
	
	//initiate meta keywords class
	$inst_Metakeywords = new Metakeywords();
	
	$categoriesArr = array($category1,$category2,$category3,$category4,$category5,$category6);
	$categoryNameArr=array();
	if(count($categoriesArr)>0){
		foreach($categoriesArr as $categoriesID){
			if($categoriesID != ''){ 
				$categoryNameArr[] = $db->get_var("SELECT Name FROM categories WHERE SiteID='".$site_id."' AND ID='$categoriesID'");
			}
		}
		if(count($categoryNameArr)>0){
			$categoriesStr= implode(",",$categoryNameArr);
		}
	}
	
if($metaTagsDisplayNum==1){
	
	// Window Title
	$output['WindowTitle']=$document_name;
	
	//meta tag keywords
	$create_MetaTagKeywords=$document_name;
	if($location!=''){
		$create_MetaTagKeywords.='- '.$location;
	}
	if($body!=''){
		//$create_MetaTagKeywords.='- '.$body;
		if($body!=''){
			$k_body=substr($body,0,512);
			$getlast_position = strrpos($k_body, ".");
			if($getlast_position!=''){
				$k_body=substr($k_body,0,$getlast_position);
			}
			$create_MetaTagKeywords.='- '.$k_body;
		}
	}
	if($categoriesStr!=''){
		$create_MetaTagKeywords.='- '.$categoriesStr;
	}
	$output['MetaTagKeywords']= $inst_Metakeywords->get( $db, $db_name, $create_MetaTagKeywords );

	//meta tag description
	$create_MetaTagDescription=$document_name;
	if($location!=''){
		$create_MetaTagDescription.='- '.$location;
	}
	if($body!=''){
		$create_MetaTagDescription.='- '.$body;
	}
	$create_MetaTagDescription=substr($create_MetaTagDescription,0,512);
	$getlast_position = strrpos($create_MetaTagDescription, ".");
	if($getlast_position!=''){
		$create_MetaTagDescription=substr($create_MetaTagDescription,0,$getlast_position);
	}
	$create_MetaTagDescription=strip_tags($create_MetaTagDescription);
	$create_MetaTagDescription = str_replace("&nbsp;"," ",$create_MetaTagDescription);
	$create_MetaTagDescription = str_replace("\n"," ",$create_MetaTagDescription);
	$output['MetaTagDescription']=$create_MetaTagDescription;
	
}
	
if($searchDisplayNum==1){
	//create search tags
	$create_SearchTags=$document_name;
	$searchTags="";
	
	if($document_name!=""){
		$document_name = str_replace("/"," ",$document_name);
		$document_name=preg_replace('/-+\s+/', ' ', $document_name);
		//$document_name=preg_replace('/\s+/', ' ', $document_name);
	
		$wordCount=str_word_count($document_name);
		switch ($wordCount) {
			case 1:
				$searchTags.=$document_name;
        		break;
        	case 2:
				$searchTags.=$document_name;
        		break;
   			default:
        		$x = 1;
        		//$wordsArr=str_word_count($document_name, 1);
        		$wordsArr = preg_split("/[\s,]+/", $document_name);
        		$wordsArr = $inst_Metakeywords->removeStopWords($wordsArr);
        		while($x <= $wordCount) {
					$subWordArr = array_slice($wordsArr, 0, $x);
    				
    				$subWordStr= implode(" ",$subWordArr);
    				if($searchTags!=""){
    					$searchTags.=', '.$subWordStr;
    				}else{
    					$searchTags.=$subWordStr;
    				}
    				$x++;
				} 
		}
	}
	
	if($location!=''){
		$create_SearchTags.='- '.$location;
	}
	if($body!=''){
		$create_SearchTags.='- '.$body;
	}
	if($subtitle!=''){
		$create_SearchTags.='- '.$subtitle;
		if($searchTags!=""){
    		$searchTags.=', '.$subtitle;
    	}else{
    		$searchTags.=$subtitle;
    	}
	}
	if($categoriesStr!=''){
		$create_SearchTags.='- '.$categoriesStr;
	}
	
	if($searchTags!=""){
    	$searchTags.=', '.$inst_Metakeywords->filterKeywords( $db, $db_name, $create_SearchTags );
    }else{
    	$searchTags.=$inst_Metakeywords->filterKeywords( $db, $db_name, $create_SearchTags );
    }
	
	$searchKeywordsGenerated=$inst_Metakeywords->uniqueWords($searchTags);
	if($post_code!=''){
		if($searchKeywordsGenerated!=""){
    		$searchKeywordsGenerated.=', '.$post_code;
    	}else{
    		$searchKeywordsGenerated.=$post_code;
    	}
	}
	$output['SearchKeywords']= $searchKeywordsGenerated;
}
	
if(count($output)>0){
	echo json_encode($output);
}
?>