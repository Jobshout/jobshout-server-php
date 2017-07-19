<?php require_once("connect.php"); ?>
<?php
$guid=$_GET['uuid'];
$token_id=$_GET['token_id'];
$col=$_GET['col'];
$datetime=$_GET['datetime'];
$action=$_GET['action'];

$result[0]['uuid']=$guid;

$current=$db->get_var("select `$col` from tokens where ID='".$token_id."'");
if($action=='Cancel'){		
	$result[0]['content']=$current;
}
elseif($action=='Rollback') {
	$db->select($history_db_name);
	$history=$db->get_row("select `$col` from tokens where Update_GUID='".$guid."' and Created='".$datetime."'");
	$db->select($db_name);

	$result[0]['content']=$history->$col;	
}


echo json_encode($result);
?>