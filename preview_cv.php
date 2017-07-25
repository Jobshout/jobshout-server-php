<?php 
require_once("connect.php"); 
require_once("constants.php");

if(isset($_GET['GUID']) && $_GET['GUID']!="" && isset($_GET['token']) && $_GET['token']!=""){
	// added security for authentication
	$authenticatePage = $db->get_var("SELECT count(*) as num FROM authenticate_tokens where guid = '".$_REQUEST['token']."'");
	if($authenticatePage>=1){
		if($fileContent = $db->get_row("SELECT CVFileName, zCV, CVFileType FROM jobapplications where GUID = '".$_REQUEST['GUID']."'")){
	 		$cv_file=$fileContent->CVFileName;
			$CVFileType=$fileContent->CVFileType;
			header("Content-type: $CVFileType");
       	 	echo $fileContent->zCV;
		}
	}
}

?>