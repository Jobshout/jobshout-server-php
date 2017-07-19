<?php
session_start();
	require_once("connect.php");
	
$kLimit = isset($_GET["limit"]) ? $_GET["limit"] : "1000";
$pKeywordStr	= isset($_GET["keyword"]) ? $_GET["keyword"] : "";
$selectedID	= isset($_GET["id"]) ? $_GET["id"] : "";
$main_site_id	= isset($_GET["main_site_id"]) ? $_GET["main_site_id"] : "";

$where_ids='';
if($selectedID!=''){
$where_ids=" and ID not in ($selectedID) ";
}
echo $where_ids;
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
if($site->ID != $main_site_id){
$output = "<option value=".$site->ID." selected>";

$output .= $site->Name." (".$site->Code.")";
$output .= "</option>"	;echo $output;
}
}

} 

}
$user=$db->get_row("select * from wi_users where code='".$_SESSION['UserEmail']."'");
if($user->access_rights_code>=11){
$queryStr = "select ID,Code,Name from `sites` WHERE (`Name` COLLATE UTF8_GENERAL_CI LIKE '" . $pKeywordStr . "%' or `Code` COLLATE UTF8_GENERAL_CI LIKE '" . $pKeywordStr . "%') $where_ids ORDER BY `Name` ASC ";
}else{
$queryStr = "select ID,Code,Name,GUID from `sites` WHERE  GUID in (select uuid_site from wi_user_sites where uuid_user='".$user->uuid."') and (`Name` COLLATE UTF8_GENERAL_CI LIKE '" . $pKeywordStr . "%' or `Code` COLLATE UTF8_GENERAL_CI LIKE '" . $pKeywordStr . "%') $where_ids ORDER BY `Name` ASC ";
}
$Sites = $db->get_results($queryStr);
// $db->debug();


if($db->num_rows > 0)//$db->num_rows > 0
{

foreach ( $Sites as $sites )
{
if($sites->ID != $main_site_id){
$output = "<option value=".$sites->ID.">";

$output .= $sites->Name." (".$sites->Code.")";
$output .= "</option>"	;echo $output;
}
}

} 



}


?>






