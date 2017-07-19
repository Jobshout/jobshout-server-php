<?php
session_start();
require_once("connect.php");

	if($_REQUEST['sel_id']!='') {
	$chk=explode(",", $_REQUEST['sel_id']);
	$mode=	$_REQUEST['mode'];
		
		if($mode=='del') {
			foreach($chk as $cmnt_id){
				
					$sql=$db->query("delete from blog_comments where uuid='".$cmnt_id."'");
				
			}
			echo $succ_msg="<span style='color:#00CC00;font-size:18px'>Comment(s) successfully deleted</span><br/><br/>";
		}
		if($mode=='act') {
			foreach($chk as $cmnt_id){
				
					$sql=$db->query("update blog_comments set Status='1' where uuid='".$cmnt_id."'");
				
			}
			echo $succ_msg="<span style='color:#00CC00;font-size:18px'>Comment(s) successfully activated</span><br/><br/>";
		}
		if($mode=='deact') {
			foreach($chk as $cmnt_id){
				
					$sql=$db->query("update blog_comments set Status='0' where uuid='".$cmnt_id."'");
				
			}
			echo $succ_msg="<span style='color:#00CC00;font-size:18px'>Comment(s) successfully deactivated</span><br/><br/>";
		}
	}
	



?>
