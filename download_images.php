<?php
require_once("connect.php");
require_once("constants.php");
$id=array();
    try {
      $sql = "SELECT * FROM `pictures` WHERE `SiteID` =29201 AND `Code` IN ( 'temp-image', 'main-content', 'image-box' ) and Name in ('Barrier Foil Packaging Flat Bag styles 2.jpg','home-02.jpg', 'Industry-Steel and Metal NBP 2.jpg', 'MultiMetalFilmShot.jpg', 'silver-solution-main.jpg', 'TechCorro[F] MMI Flow-wrapper film 2.jpg', 'VCI_BubbleBagsSmall.jpg') order by Name";
        $results = $db->get_results($sql);
		echo count($results);
        foreach($results as $result)
		{
			
            $filename = str_replace(" ","",$result->Name);
            $mimetype = $result->Type;
            $filedata = $result->Picture;
            //header("Content-length: ".strlen($filedata));
            //header("Content-type: $mimetype");
            //header("Content-disposition: download; filename=$filename"); //disposition of download forces a download
            //echo $filedata; 
			if(!file_exists(SERVER_PATH."\\technologypackaging\\tp_images\\".$filename)){
			if(file_put_contents(SERVER_PATH."\\technologypackaging\\tp_images\\".$filename,$filedata)){
				$id[]=$result->Name;
			}
			}

      } 
	  print_r($id);     // die();
       
    } //try
    catch (Exception $e) {
        $error = '<br>Database ERROR fetching requested file.';
        echo $error;
        die();    
    } //catch

?>