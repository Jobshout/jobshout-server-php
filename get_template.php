<?php 
session_start();
require_once("connect.php"); ?>

		<?php
			
			if($path = $db->get_var("SELECT Path from templates where GUID='".$_POST['temp_id']."'")) {
				if(file_exists($path)){
					echo file_get_contents($path);
				}
			
			 }
		?>