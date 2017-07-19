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

$output = "";
if( $pKeywordStr != "" ) {

if($selectedID!=''){

$queryStr = "select ID,Code,Name from `sites` WHERE ID in ($selectedID)  ORDER BY `Name` ASC ";

$sites = $db->get_results($queryStr);
// $db->debug();


if($db->num_rows > 0)//$db->num_rows > 0
{

foreach ( $sites as $site )
{

$output = "<option value=".$site->ID." selected>";

$output .= $site->Name." (".$site->Code.")";
$output .= "</option>"	;echo $output;
}

} 

}

$queryStr = "select ID,Code,Name from `sites` WHERE (`Name` COLLATE UTF8_GENERAL_CI LIKE '" . $pKeywordStr . "%' or `Code` COLLATE UTF8_GENERAL_CI LIKE '" . $pKeywordStr . "%') $where_ids ORDER BY `Name` ASC ";

$sites = $db->get_results($queryStr);
//$db->debug();


if($db->num_rows > 0)//$db->num_rows > 0
{

foreach ( $sites as $site )
{

$output = "<option value=".$site->ID.">";

$output .= $site->Name." (".$site->Code.")";
$output .= "</option>"	;echo $output;
}

} 



}


?>
