<?php
session_start();
	require_once("connect.php");
	
$srch=$_REQUEST['srch'];
$where_cond='';
if(isset($_REQUEST['site_id']) && $_REQUEST['site_id']!='') {
	$where_cond=" and SiteID in ('".$_REQUEST['site_id']."') ";
}
elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
}

$i=0;
if($sql_docs=$db->get_results("select GUID,ID,Code,Document from `documents` where `Document` COLLATE UTF8_GENERAL_CI LIKE '" . $srch . "%' $where_cond  ORDER BY `Document` ASC limit 0,100")){
	foreach($sql_docs as $sql_doc) {
		$result[$i]['id']=$sql_doc->ID;
		$result[$i]['guid']=$sql_doc->GUID;
		$result[$i]['value']=$sql_doc->Document." (".$sql_doc->Code.")";
		
		$i++;
	}
	echo json_encode($result);
}


?>
