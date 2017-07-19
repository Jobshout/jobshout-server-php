<?php 
session_start();
require_once("connect.php");

$sites = $db->get_results("SELECT Distinct(zStatus) FROM `sites`");
$i=0;
if($sites){
foreach($sites as $site){
	$status=$site->zStatus;
	$result[$i]['label']=$status;
	$no_of_sites = $db->get_var("SELECT count(*) FROM `sites` where zStatus='$status'");
	$result[$i]['data']=$no_of_sites;
	$i++;
}
}else{
$result=0;
}
echo json_encode($result);
?>