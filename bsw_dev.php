<?php 
require_once("include/lib.inc.php");

$guid= $_GET['GUID'];
$document = $db->get_row("SELECT * FROM documents where documents.GUID ='$guid'");

		$Body=$document->Body;

//echo $Body;
		$new_Body=str_replace("http://cdn.jobshout.com","",$Body);
echo $new_Body;

?>