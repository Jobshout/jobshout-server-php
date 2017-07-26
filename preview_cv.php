<?php 
require_once("connect.php"); 
require_once("constants.php");
require_once("../../private_config/constants.inc.php");

function fetch_postcode($str)	{
	$returnVal='';
	$pattern = "/((GIR 0AA)|((([A-PR-UWYZ][0-9][0-9]?)|(([A-PR-UWYZ][A-HK-Y][0-9][0-9]?)|(([A-PR-UWYZ][0-9][A-HJKSTUW])|([A-PR-UWYZ][A-HK-Y][0-9][ABEHMNPRVWXY])))) [0-9][ABD-HJLNP-UW-Z]{2}))/i";
	preg_match($pattern, $str, $matches);
	if(isset($matches) && isset($matches[0]) && $matches[0]!="") {
		$returnVal=$matches[0];
	}
	return $returnVal;
}

function return_json_from_csv($fileNameStr){
	$outputJson= array(); $keyArr= array(); $rawText=""; $jsonArr= array();
	$row = 0;
	if (($fileHandle = fopen($fileNameStr, "r")) !== FALSE) {
    	while (($data = fgetcsv($fileHandle, 0, ",")) !== FALSE) {
    		$num = count($data);
	        if($row==0){
    			for ($c=0; $c < $num; $c++) {
        	    	array_push($keyArr, $data[$c]);
       			}
	    	} else {
    			$tempArr= array();
    			for ($c=0; $c < $num; $c++) {
    				if($keyArr[$c]=="raw_text"){
    					$rawText= $data[$c];
    				}
    				$tempArr[$keyArr[$c]]=$data[$c];
        		}
    	   		$jsonArr[$row-1] = $tempArr;
    		}
   		 	$row++;
    	}
    	$outputJson['aaData']=$jsonArr;
    	fclose($fileHandle);
	}
	
	$outputJson['raw_content']=$rawText;
	return json_encode($outputJson);
}

if(isset($_GET['GUID']) && $_GET['GUID']!="" && isset($_GET['token']) && $_GET['token']!=""){
	// added security for authentication
	$authenticatePage = $db->get_var("SELECT count(*) as num FROM authenticate_tokens where guid = '".$_REQUEST['token']."'");
	if($authenticatePage>=1){
		if($fileContent = $db->get_row("SELECT CVFileName, zCV, CVFileType, CV_Extracted_Information FROM jobapplications where GUID = '".$_REQUEST['GUID']."'")){
			$cv_file=$fileContent->CVFileName;
			$CVFileType=$fileContent->CVFileType;
			
			if($CVFileType=="application/pdf" && $fileContent->CV_Extracted_Information==""){
				array_map('unlink', glob(DATAINPUTPATH."/*"));	//remove all the files in input directory
				$outputPathStr=DATAINPUTPATH."/".$_REQUEST['GUID'].".pdf";
    			file_put_contents($outputPathStr,$fileContent->zCV);
    			
    			@unlink(DATAOUTPUTPATH);	// remove csv file
    			$commandStr = PYTHONSCRIPTPATH." --data_path ".DATAINPUTPATH." --output_path ".DATAOUTPUTPATH;

    			exec ( $commandStr );
   				$get_cv_content = file_get_contents(DATAOUTPUTPATH);
				$getPostCode=fetch_postcode($get_cv_content);
	
    			//save the csv data in db                                                                                                                                                                                                                                    
   				$CV_File_Content = addslashes($get_cv_content);
    
    			$extracted_Information=return_json_from_csv(DATAOUTPUTPATH);
    			$extracted_InformationArr= json_decode($extracted_Information);
    			if(isset($extracted_InformationArr) && isset($extracted_InformationArr->raw_content) && $extracted_InformationArr->raw_content!=""){
    				$CV_File_Content = addslashes($extracted_InformationArr->raw_content);
    			}    
    			$CV_Extracted_Information="";
    			if(isset($extracted_InformationArr) && isset($extracted_InformationArr->aaData) && $extracted_InformationArr->aaData!=""){
    				$CV_Extracted_Information = json_encode($extracted_InformationArr->aaData);
    				$CV_Extracted_Information = addslashes($CV_Extracted_Information);
    			}
    			$formqueryStr="update jobapplications set CV_File_Content='$CV_File_Content', CV_Extracted_Information='$CV_Extracted_Information' ";
    			if($getPostCode!="")	{
    				$formqueryStr.=", HomePostcode='$getPostCode' ";
    			}
    			$formqueryStr.=" where  GUID = '".$_GET['GUID']."'";
    			$db->query($formqueryStr);
			}
	 		header("Content-type: $CVFileType");
       	 	echo $fileContent->zCV;
		}
	} else	{
		echo "Invalid Doc";
	}
}	else {
	echo "Invalid Doc UUID";
}
exit;
?>