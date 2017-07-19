<?php
require_once("connect.php");
if (isset($_GET['GUID'])) {
    $fileid = $_GET['GUID'];
    try {
      $sql = "SELECT * FROM `pictures` WHERE `GUID` = '".$fileid."'";
        $results = $db->get_row($sql);
        
            $filename = str_replace(" ","",$results->Name);
            $mimetype = $results->Type;
            $filedata = $results->Picture;
            header("Content-length: ".strlen($filedata));
            header("Content-type: $mimetype");
            header("Content-disposition: download; filename=$filename"); //disposition of download forces a download
            echo $filedata; 
            // die();
       
    } //try
    catch (Exception $e) {
        $error = '<br>Database ERROR fetching requested file.';
        echo $error;
        die();    
    } //catch
} //isset
?>