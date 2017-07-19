<?php
session_start();
require_once("connect.php");
require_once("include/functions.php");

 
	$mail_id=$_REQUEST['list_id'];
	$exist_contact='';
	$add=0;
	if($_REQUEST['sel_id']!='') {
	$chk=explode(",", $_REQUEST['sel_id']);
		
		
			foreach($chk as $contact){
				$chk_sql=$db->get_var("select count(*) from wi_mailinglist_contacts where uuid_contact='".$contact."' and uuid_mailinglist='".$mail_id."'");
				if($chk_sql==0) {
					$uuid=UniqueGuid('wi_mailinglist_contacts', 'uuid');
					$sql=$db->query("insert into wi_mailinglist_contacts(uuid, uuid_contact, uuid_mailinglist) values('".$uuid."', '".$contact."', '".$mail_id."')");
					$add=1;
				}
				else{
					
					$sql_name=$db->get_var("select name from contacts where guid='".$contact."'");
					$exist_contact[]=$sql_name;
				}
			}
			
			if(is_array($exist_contact)) {
				echo $err_msg="<span style='color:red;font-size:18px'>Contact(s): <b>".implode(", ",$exist_contact)."</b> already exists in the selected mailing list</span><br/>";
			}
			if($add==1){
				echo $succ_msg="<span style='color:#00CC00;font-size:18px'>Contact(s) successfully added to the selected mailing list</span><br/>";
			}
		
	}
	else
	{
		echo $err_msg="<span style='color:red;font-size:18px'>Please select contact(s) to add to mailing list</span><br/>";
	}
	echo "<br/>";


?>
