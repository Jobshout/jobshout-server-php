<?php
// Include ezSQL core
include_once "ez_sql_core.php";
// Include ezSQL database specific component
include_once "ez_sql_mysql.php";

$db_user='root';
$db_host='lon2.jobshout.co.uk';
$db_name='jobshout_live';
$db_pass='!ChBi2K1ngS';

$db = new ezSQL_mysql($db_user,$db_pass,$db_name,$db_host);	

define("SITE_ID","29201");
define("SITE_GUID","88B9F09C-5975-4674-BCE6-D6E65EB98333");

if($categories=$db->get_results("select * from categories where SiteID='".SITE_ID."'")){
	foreach($categories as $category){
		if($document= $db->get_row("select * from documents where Code= '".$category->Code."' and SiteID='".SITE_ID."'")){
			if($update=$db->query("update categories set Cat_Doc_GUID='".$document->GUID."' where GUID='".$category->GUID."'")){
				echo "Document <i>".$document->Code."</i> added for category <i>".$category->Code."</i><br/>";
			}
		}
	}
}

?>