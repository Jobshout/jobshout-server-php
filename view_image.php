<?php
require_once("connect.php");
if (isset($_GET['GUID'])) {
    $fileid = $_GET['GUID'];

      $sql = "SELECT * FROM `pictures` WHERE `GUID` = '".$fileid."'";
        $results = $db->get_row($sql);
        
           $mime = $results->Type;
			$b64Src = "data:".$mime.";base64," . base64_encode($results->Picture);
			
?>
<img src="<?php echo $b64Src; ?>" alt=""  />
<?php
           
} //isset
?>
