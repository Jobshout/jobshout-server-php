<?php
session_start();
require_once("connect.php");


	$mail_id=$_REQUEST['list_id'];
	if($_REQUEST['sel_id']!='') {
	$chk=explode(",", $_REQUEST['sel_id']);
		
		
			foreach($chk as $contact){
				
					$sql=$db->query("delete from wi_mailinglist_contacts where uuid_contact='".$contact."' and uuid_mailinglist='".$mail_id."'");
				
			}
			echo $succ_msg="<span style='color:#00CC00;font-size:18px'>Contact(s) successfully removed from the selected mailing list</span><br/><br/>";
		
	}
	else
	{
		echo $err_msg="<span style='color:red;font-size:18px'>Please select contact(s) to remove from mailing list</span><br/><br/>";
	}



?>
