<?php
session_start();
	require_once("connect.php");
	
$kLimit = isset($_GET["limit"]) ? $_GET["limit"] : "1000";
$pKeywordStr	= isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$selectedID	= isset($_GET["id"]) ? $_GET["id"] : "";
$where_ids='';
if($selectedID!=''){
$where_ids=" and ID not in ($selectedID) ";
}
$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
$output = "";
if( $pKeywordStr != "" ) {

if($selectedID!=''){

$queryStr = "select ID,Code,Document from `documents` WHERE ID in ($selectedID)  ORDER BY `Document` ASC ";

$documents = $db->get_results($queryStr);
// $db->debug();


if($db->num_rows > 0)//$db->num_rows > 0
{

foreach ( $documents as $document )
{

$output = "<option value=".$document->ID." selected>";

$output .= $document->Document." (".$document->Code.")";
$output .= "</option>"	;echo $output;
}

} 

}

$queryStr = "select ID,Code,Document from `documents` WHERE `Document` COLLATE UTF8_GENERAL_CI LIKE '" . $pKeywordStr . "%' $where_ids $where_cond  ORDER BY `Document` ASC ";

$documents = $db->get_results($queryStr);
// $db->debug();


if($db->num_rows > 0)//$db->num_rows > 0
{

foreach ( $documents as $document )
{

$output = "<option value=".$document->ID.">";

$output .= $document->Document." (".$document->Code.")";
$output .= "</option>"	;echo $output;
}

} 



}


?>






