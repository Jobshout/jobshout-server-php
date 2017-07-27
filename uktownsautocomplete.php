<?php
set_time_limit(0);
require_once("include/lib.inc.php");

$output=array();
$term= isset($_GET['term']) ? $_GET['term'] : "";
$forceDisplayBool= isset($_GET['forceDisplay']) ? $_GET['forceDisplay'] : false;

function findTermsArr($termStr){
	$termLenthNum= strlen($termStr);
	$termsArr=array();
	$termsArr[]=$termStr;
	$tempSubTermStr=$termStr;
	$y=$termLenthNum;
		
	while($y >= 4) {
		$tempSubTermStr=substr($tempSubTermStr, 0, -1);
		$tempSubTermStr= trim($tempSubTermStr);
		if(strlen($tempSubTermStr)>=4){
			$termsArr[]= $tempSubTermStr;
    	}
   		 $y--;
	}	
	return implode(",",$termsArr);
}

function findSubStrings($termStr, $pos=3){
	$returnedTermsStr="";
	$termLenthNum= strlen($termStr);
	if($termLenthNum>=$pos){
		$subStr= str_replace(substr($termStr, 0, $pos), substr($termStr, 0, $pos)." ", $termStr);
		if($subStr!=""){
			if($returnedTermsStr!=""){
				$returnedTermsStr.=",".findTermsArr($subStr);
			}else{
				$returnedTermsStr.=findTermsArr($subStr);
			}
		}
	}
	return $returnedTermsStr;
}

function checkAllPossiblePostcodes($termStr, $inClause=false, $debugBool=false){
	global $db;
	$returnData=array();
	$termLenthNum= strlen($termStr);
	if($termLenthNum>=4){
		
		$returnedTermsStr="";
		$returnedTermsStr.=findTermsArr($termStr);
		if($debugBool){	echo $returnedTermsStr."<br>";	}
		
		$substringWithoutSpace= str_replace(' ', '', $termStr);
		if($substringWithoutSpace!=""){
			if($returnedTermsStr!=""){
				$returnedTermsStr.=",".findTermsArr($substringWithoutSpace);
			}else{
				$returnedTermsStr.= findTermsArr($substringWithoutSpace);
			}	
		}
		if($debugBool){	echo $returnedTermsStr."<br>";	}
		
		if($returnedTermsStr!=""){
			$returnedTermsStr.=",".findSubStrings($substringWithoutSpace,3);
		}else{
			$returnedTermsStr.=findSubStrings($substringWithoutSpace,3);
		}
		if($debugBool){	echo $returnedTermsStr."<br>";	}
		
		if($returnedTermsStr!=""){
			$returnedTermsStr.=",".findSubStrings($substringWithoutSpace,4);
		}else{
			$returnedTermsStr.=findSubStrings($substringWithoutSpace,4);
		}
		
		if($debugBool){	echo $returnedTermsStr."<br>";	}
		$termsArr=explode(",",$returnedTermsStr);
		$termsArr= array_unique($termsArr);
		
		if(count($termsArr)>0){
			if($inClause){
				$whereClause = " or postcode IN ('". implode("', '", $termsArr) ."') ";		
			}else	{
				$arrNum=0;
				$whereClause="";
				foreach($termsArr as $row){
					$arrNum++;
					if($arrNum==0){
						$whereClause.=" or postcode LIKE '".$row."%' ";
					}else{
						$whereClause.=" or postcode LIKE '".$row."%' ";
					}
				}
			}
		}else{
			$whereClause="or postcode LIKE '".$termStr."%'";
		}
		
		$resultData=$db->get_results("SELECT * FROM uk_towns_cities Where name LIKE '".$termStr."%' ".$whereClause." ORDER BY postcode ASC limit 200");
		if($debugBool){	$db->debug();	}
		if(count($resultData)==0){
			$termStr=substr($termStr, 0, 3);
			$resultData=$db->get_results("SELECT * FROM uk_towns_cities Where name LIKE '".$termStr."%' or postcode LIKE '".$termStr."%' ORDER BY postcode ASC limit 200");
			if($debugBool){	$db->debug();	}
		}
	}
	return $resultData;
}

if($term!=""){
	$db->query('SET NAMES utf8');
	$resultData=$db->get_results("SELECT * FROM uk_towns_cities Where name LIKE '".$term."%' or postcode LIKE '".$term."%' ORDER BY name ASC limit 200");
	if(count($resultData)==0){
		$resultData= checkAllPossiblePostcodes($term);
	}
	
	if(count($resultData)>0){
		foreach($resultData as $row){
			$subArr=array();
			$outputVal=$row->name;
			$postcode ='';
			if(isset($row->postcode) && $row->postcode!=""){
				$outputVal.=" (".$row->postcode.")";
				$postcode = $row->postcode;
			}
			$subArr['value']=$row->GUID;
			$subArr['postcode']=$postcode;
			$subArr['name']=$outputVal;
			$output[]=$subArr;
			
		}
	}
} 
if($forceDisplayBool==true && $forceDisplayBool=="true"){
	$db->query('SET NAMES utf8');
	$resultData=$db->get_results("SELECT * FROM uk_towns_cities ORDER BY name ASC limit 100");
		
	if(count($resultData)>0){
		foreach($resultData as $row){
			$subArr=array();
			$outputVal=$row->name;
			$postcode ='';
			if(isset($row->postcode) && $row->postcode!=""){
				$outputVal.=" (".$row->postcode.")";
				$postcode = $row->postcode;
			}
			$subArr['value']=$row->GUID;
			$subArr['postcode']=$postcode;
			$subArr['name']=$outputVal;
			$output[]=$subArr;
			
		}
	}
}
if(count($output)>0){
	echo json_encode($output);
}else{
	$output['error']="no record found";
	echo json_encode($output);
}
?>