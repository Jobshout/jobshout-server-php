<?php
session_start();
require_once("connect.php");

	if($_REQUEST['sel_id']!='') {
	$chk=explode(",", $_REQUEST['sel_id']);
	
		
		
			foreach($chk as $cmnt_id){
				
					$sql=$db->query("delete from jobbriefs where GUID='".$cmnt_id."'");
				
			}
			echo $succ_msg="<span style='color:#00CC00;font-size:18px'>Jobbrief(s) successfully deleted</span><br/><br/>";
	
		
		
	}
	



?>
