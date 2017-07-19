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
$categoriesStr='';

	//meta tag keywords
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
	// Window Title
	$output['WindowTitle']=$document_name;
	
	//initiate meta keywords class
	$inst_Metakeywords = new Metakeywords();
	$create_MetaTagKeywords=$document_name;
	if($location!=''){
		$create_MetaTagKeywords.='- '.$location;
	}
	if($body!=''){
		$create_MetaTagKeywords.='- '.$body;
	}
	if($categoriesStr!=''){
		$create_MetaTagKeywords.='- '.$categoriesStr;
	}
	$output['MetaTagKeywords']= $inst_Metakeywords->get( $create_MetaTagKeywords );

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
	$create_MetaTagDescription = preg_replace( "#(^(&nbsp;|\s)+|(&nbsp;|\s)+$)#", "", $create_MetaTagDescription );
	$output['MetaTagDescription']=$create_MetaTagDescription;
if(count($output)>0){
	echo json_encode($output);
}
?>