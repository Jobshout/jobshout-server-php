<?php
set_time_limit(0);
require_once("include/lib.inc.php");

$output=array();
$guid= isset($_POST['uuid']) ? $_POST['uuid'] : "";

if($guid!=""){
	if($resultData=$db->get_row("SELECT * FROM uk_towns_cities Where GUID = '".$guid."'")){
		$output['postcode']=$resultData->postcode;
		$output['name']=$resultData->name;
		$output['lat']=$resultData->lat;
		$output['lng']=$resultData->lng;
	}else{
		$output['error']="no record found";
	}
}else{
	$output['error']="no record found";
}
echo json_encode($output);

?>