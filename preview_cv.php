 <?php 
require_once("include/lib.inc.php");

if(isset($_GET['GUID'])){
	if($fileContent = $db->get_row("SELECT CVFileName, zCV, CVFileType FROM jobapplications where GUID = '".$_REQUEST['GUID']."'")){
	 	$cv_file=$fileContent->CVFileName;
		$CVFileType=$fileContent->CVFileType;
		header("Content-type: $CVFileType");
        echo $fileContent->zCV;
	}
}

?>