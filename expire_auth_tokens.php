<?php 
require_once("include/lib.inc.php");

$currentTiestamp=time();
$pastTimestamp = time()-(60*15);
$output = array();
$authenticatePage = $db->get_var("SELECT count(*) FROM authenticate_tokens where timestamp <= ".$pastTimestamp."");
if($authenticatePage>=1){
	if($db->query("delete from authenticate_tokens where timestamp <= ".$pastTimestamp."")){
		$output['success']= "OK";
	}	else	{
		$output['error']= "error";
	}
}else{
	$output['error']= "no data found";
}
echo json_encode($output);
?>