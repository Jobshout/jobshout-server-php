<?php
$conn = new Mongo( 'mongodb://85.92.89.214:27017/' );
$mon_db= $conn->cvscreen;
$collection = $mon_db->documents;
if(isset($_GET['GUID']) && $_REQUEST['GUID']<>''){
	$guid = $_GET['GUID'];
	$collection->remove(array("GUID" => $guid));
}
header("Location: mongo-jobs.php");
?>
