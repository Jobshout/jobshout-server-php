<?php 
require_once("include/lib.inc.php");
if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from documents where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: blog.php");
	}
}

if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$document = $db->get_row("SELECT * FROM documents where documents.GUID ='$guid'");

		$documentID=$document->ID;
		$Document=$document->Document;
		$Body=$document->Body;
		$MetaTagKeywords=$document->MetaTagKeywords;
		$MetaTagDescription=$document->MetaTagDescription;
		$PageTitle=$document->Title;
		$WindowTitle=$document->PageTitle;
}

if(isset($_POST['submit']))
{
		
	
	$Code=$_POST["Code"];
	$site_id = $_POST['site_id'];
	
	$new_Document=addslashes($_POST["Document"]);
	//$Header=$_POST["Header"];
	//	$new_Body=addslashes(str_replace("http://cdn.jobshout.com","",$_POST["Body"]));
	$new_Body=addslashes($_POST["Body"]);
	
	//meta tag keywords
	$new_MetaTagKeywords=addslashes($_POST["MetaTagKeywords"]);
		
	//meta Tag description
	$new_MetaTagDescription=addslashes($_POST["MetaTagDescription"]);
	
	$UserID=$_POST["UserID"];
	$Active=$_POST["status"];
	//$Title=$_POST["Title"];
	$new_PageTitle=addslashes($_POST["PageTitle"]);
	$WindowTitle=addslashes($_POST["WindowTitle"]);
	
	if(isset($_POST["WindowTitle"]) && $_POST["WindowTitle"]!='') {
			$WindowTitle=addslashes($_POST["WindowTitle"]);
		}else{
			$WindowTitle=addslashes($_POST["Document"]);
		}
	
	$template=$_POST["template"];
		
		$FFAlpha80_1 = $_POST["FFAlpha80_1"];
		$FFAlpha80_2 = $_POST["FFAlpha80_2"];
		$FFAlpha80_3 = $_POST["FFAlpha80_3"];
		$FFAlpha80_4 = $_POST["FFAlpha80_4"];
		$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
		$user_guid= $db->get_var("select uuid from wi_users where ID='$UserID'");
	
	$Type="blog";
	//$SiteName=$_POST["SiteName"];
	//$Reference=$_POST["Reference"];
	//$Sync_LastTime=$_POST["Sync_LastTime"];
	$arr_pub_date=explode('/', $_POST["Published_timestamp"]);
	$Published_timestamp=$arr_pub_date[1].'/'.$arr_pub_date[0].'/'.$arr_pub_date[2];
	$pub_time=$_POST["pub_time"];
	if($pub_time==''){
		$pub_time=date('h:i A');
	}
	/*$pub_time_arr=explode(" ",$pub_time);
	$pub_hour_min_arr=explode(":",$pub_time_arr[0]);
	if($pub_time_arr[1]=='PM'){
		$pub_hour=$pub_hour_min_arr[0]+12;
	}
	else{
		$pub_hour=$pub_hour_min_arr[0];
	}*/
	$pub_time_string=$Published_timestamp." ".$pub_time;
	
	
	$Published_timestamp= strtotime($pub_time_string);
	
	$time= time();
	$AutoFormatMetaData=1;
	if(isset($_POST["chk_manual_metatags"])) {
		$AutoFormatMetaData=0;
	}
	$auto_format=1;
		if(isset($_POST["chk_manual"])) {
		$auto_format=0;
		}
		
		$insert=true; 
	$update=true; 
	$insert_cat=true; 
	
	if(isset($_GET['GUID'])) {
		$GUID= $_GET['GUID'];
		if($chk=$db->get_row("select * from documents where Code='$Code' and SiteID='".$site_id."' and GUID<>'$GUID'")){
			$error_msg[]="This Code already exists.";
			$update = false;
		}
		else{
		
		if($update = $db->query("UPDATE documents SET SiteID='$site_id', Document='$new_Document',Code='$Code', Modified='$time', Body='$new_Body', MetaTagKeywords='$new_MetaTagKeywords', MetaTagDescription='$new_MetaTagDescription', UserID='$UserID', Template='$template', 
	Title='$new_PageTitle',PageTitle='$WindowTitle',
	Type='$Type',FFAlpha80_1='$FFAlpha80_1',FFAlpha80_2='$FFAlpha80_2',FFAlpha80_3='$FFAlpha80_3',FFAlpha80_4='$FFAlpha80_4',
	Status = '$Active', Site_GUID= '$site_guid', User_GUID='$user_guid',
	Published_timestamp='$Published_timestamp',PostedTimestamp='$Published_timestamp', AutoFormatTitle=$auto_format, AutoFormatMetaData=$AutoFormatMetaData
	WHERE GUID ='$GUID'")) {
	
		if(isset($_POST['rlbk_update_ids']) && $_POST['rlbk_update_ids']!=''){
				$arr_rlbk_uuids=explode(",", $_POST['rlbk_update_ids']);
				$arr_rlbk_cols=explode(",", $_POST['rlbk_update_col']);
				$db->select($history_db_name);
				for($i=0; $i<count($arr_rlbk_uuids); $i++){
					$delete = $db->query("update documents set ".$arr_rlbk_cols[$i]."='' where Update_GUID='".$arr_rlbk_uuids[$i]."'");
				}
				$delete = $db->query("delete from documents where Document='' and PageTitle='' and Body='' and MetaTagKeywords='' and MetaTagDescription=''");
				$db->select($db_name);
	
			}
	
			$changed_time=time();
			
			$doc_id=$documentID;
			$old_Document='';
			$old_PageTitle='';
			$old_Body='';
			$old_MetaTagKeywords='';
			$old_MetaTagDescription='';
			
			if($Document!=$_POST["Document"]){
				$old_Document=$Document;
			}
			if($PageTitle!=$_POST["PageTitle"]){
				$old_PageTitle=$PageTitle;
			}
			if(stripslashes($Body)!=$_POST["Body"]){
				$old_Body=$Body;
			}
			if(stripslashes($MetaTagKeywords)!=$_POST["MetaTagKeywords"]){
				$old_MetaTagKeywords=$MetaTagKeywords;
			}
			if(stripslashes($MetaTagDescription)!=$_POST["MetaTagDescription"]){
				$old_MetaTagDescription=$MetaTagDescription;
			}
			
			if($old_Document!='' || $old_PageTitle!='' || $old_Body!='' || $old_MetaTagKeywords!='' || $old_MetaTagDescription!=''){
				
				$db->select($history_db_name);
				
				$insert = $db->query("INSERT INTO documents (Update_GUID, ID, GUID, Created, Document, Title, Body, MetaTagKeywords, MetaTagDescription, User_GUID) 
						VALUES(UUID(), $doc_id, '".$_GET['GUID']."', '$changed_time', '$old_Document', '$old_PageTitle', '".addslashes($old_Body)."', '".addslashes($old_MetaTagKeywords)."', '".addslashes($old_MetaTagDescription)."', '$login_user_uuid')");

				$db->select($db_name);

			}	
		}
		}
	}
	else {
	
	//echo "INSERT INTO documents (ID,SiteID,Document,Code,Body,MetaTagKeywords,MetaTagDescription,UserID,PageTitle,Type,Published_timestamp,GUID,Sync_LastTime,AutoFormatTitle,PostedTimestamp,Created,Modified) 
									//VALUES(NULL,'$site_id','$Document','$Code','$Body','$MetaTagKeywords','$MetaTagDescription','$UserID','$PageTitle','$Type','$Published_timestamp','$GUID',1,$auto_format,'$Published_timestamp','$time','$time')";
	if($chk=$db->get_row("select * from documents where Code='$Code' and SiteID='".$site_id."'")){
		$error_msg[]="This Code already exists.";
		$insert = false;
	}
	else{
	 	$GUID = UniqueGuid('documents', 'GUID');
		$insert = $db->query("INSERT INTO documents (ID,SiteID,Document,Code,Body,MetaTagKeywords,MetaTagDescription,UserID,Title,PageTitle,Type,Published_timestamp,GUID,Sync_LastTime,AutoFormatTitle,PostedTimestamp,Created,Modified, Status,Template, Site_GUID, User_GUID, FFAlpha80_1,FFAlpha80_2,FFAlpha80_3,FFAlpha80_4, AutoFormatMetaData) 
									VALUES(NULL,'$site_id','$new_Document','$Code','$new_Body','$new_MetaTagKeywords','$new_MetaTagDescription','$UserID','$new_PageTitle','$WindowTitle','$Type','$Published_timestamp','$GUID',1,$auto_format,'$Published_timestamp','$time','$time','$Active','$template', '$site_guid', '$user_guid', '$FFAlpha80_1', '$FFAlpha80_2', '$FFAlpha80_3', '$FFAlpha80_4', '$AutoFormatMetaData')");
	}
						
	}
	
	//if((isset($_GET['GUID']) && $update) || (!isset($_GET['GUID']) && $insert)) {
	$document = $db->get_row("SELECT ID,Site_GUID,GUID FROM documents where documents.GUID ='$GUID'");

		$documentId=$document->ID;
		$documentSiteGUID=$document->Site_GUID;
		$document_GUID=$document->GUID;
	
	
	$category1=$_POST["category1"];
	$category2=$_POST["category2"];
	$category3=$_POST["category3"];
	$category4=$_POST["category4"];
	
	$db->query("delete FROM documentcategories WHERE SiteID='$site_id' AND DocumentID='$documentId'");
	
	$categories = array($category1,$category2,$category3,$category4);
	foreach($categories as $categoriesID){
		if($categoriesID != ''){ 
			
			$selectCategory = $db->get_row("SELECT CategoryGroupID,Server_Number,GUID FROM categories WHERE SiteID='".$site_id."' AND ID='$categoriesID'");
			$CategoryGroupID = $selectCategory->CategoryGroupID;
			$Server_Number = $selectCategory->Server_Number;
			$Category_GUID = $selectCategory->GUID;
			
			$dcGUID=UniqueGuid('documentcategories', 'GUID');
			
			
			$insert_cat= $db->query("INSERT INTO documentcategories(ID, Created, Modified, SiteID,CategoryGroupID, CategoryID,DocumentID, GUID,Server_Number,Site_GUID,Category_GUID,Document_GUID, Sync_Modified, UserID, User_GUID) 
			VALUES(Null,'$time','$time','$site_id','$CategoryGroupID','$categoriesID','$documentId','$dcGUID','$Server_Number','$documentSiteGUID','$Category_GUID','$document_GUID','0', '$UserID', '$user_guid')");
			//$db->debug();
			
		}
	}
	
	//sharing documents
	
		$delete=$db->query("delete from documents_shared_with_sites where uuid_document='".$document_GUID."'");
		if(isset($_POST['document_share_uuid'])!=''){
			if(is_array($_POST['document_share_uuid'])){
				for($i=0; $i<count($_POST['document_share_uuid']); $i++){
					$curr_site_uuid=$_POST['document_share_uuid'][$i];
					$document_sharing=$db->query("insert into documents_shared_with_sites(`uuid_document`, `uuid_site`) values('".$document_GUID."', '$curr_site_uuid')");
					// $db->debug();
				}
			}
		}
	//}

if(isset($_GET['GUID']) && isset($_POST['cmnt_id']) && isset($_POST['cmnt_guid'])){
			if(is_array($_POST['cmnt_id']) && is_array($_POST['cmnt_guid'])){
				for($i=0; $i<count($_POST['cmnt_id']); $i++){
					$curr_id=$_POST['cmnt_id'][$i];
					if(isset($_POST['delete_'.$curr_id])){
						$del_obj=$db->query("delete from blog_comments where `uuid`='".$_POST['cmnt_guid'][$i]."' and ID='".$_POST['cmnt_id'][$i]."'");
					}
					elseif($_POST['cmnt_by_name_'.$curr_id]!=''){
						
							$cmnt_order_by=$_POST['cmnt_order_by_'.$curr_id];
							if($cmnt_order_by=='')
							{
								$cmnt_order_by=0;
							}						  	
								$update_obj=$db->query("update blog_comments set Modified='$time', Name='".addslashes($_POST['cmnt_by_name_'.$curr_id])."', `email`='".$_POST['cmnt_by_email_'.$curr_id]."', `comments`='".addslashes($_POST['cmnt_text_'.$curr_id])."', `OrderNum`='".$cmnt_order_by."', `Status`='".$_POST['cmnt_status_'.$curr_id]."', `site_uuid`='".$documentSiteGUID."' where `uuid`='".$_POST['cmnt_guid'][$i]."' and ID='".$_POST['cmnt_id'][$i]."'");
						
					}
					
				}
			}
		}	
	
	if($_POST['cmnt_by_name']!=''){
			$cmnt_guid_nw= UniqueGuid('blog_comments', 'uuid');
				$remoteIPStr = isset($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["HTTP_X_REAL_IP"] : "";
				$cmnt_order_by=$_POST['cmnt_order_by'];
				if($cmnt_order_by=='')
				{
					$cmnt_order_by=0;
				}
				$insert_obj=$db->query("insert into blog_comments( `Created`, `Modified`, `Name`, `email`, `comments`, `ip_address`, `OrderNum`, `Status`, `uuid`, `blog_id`, `blog_uuid`, `site_uuid`, comment_by, Server_Number) values( '$time','$time', '".addslashes($_POST['cmnt_by_name'])."', '".$_POST['cmnt_by_email']."', '".addslashes($_POST['cmnt_text'])."','".$remoteIPStr."', $cmnt_order_by, '".$_POST['cmnt_status']."', '".$cmnt_guid_nw."', $documentId,  '".$document_GUID."', '".$documentSiteGUID."', '', 0)");	
			
	}
	
	
	if(isset($_GET['GUID']) && isset($_POST['pic_id']) && isset($_POST['pic_guid'])){
			if(is_array($_POST['pic_id']) && is_array($_POST['pic_guid'])){
				for($i=0; $i<count($_POST['pic_id']); $i++){
					$curr_id=$_POST['pic_id'][$i];

					if(isset($_POST['delete_image_'.$curr_id])){
						$del_obj=$db->query("delete from pictures where `GUID`='".$_POST['pic_guid'][$i]."' and ID='".$_POST['pic_id'][$i]."'");
					}
					elseif($_POST['pic_code_'.$curr_id]!=''){
						/*$chk_obj=$db->get_var("select count(*) as num from pictures where Code='".$_POST['pic_code_'.$curr_id]."' and `GUID`!='".$_POST['pic_guid'][$i]."' and (`DocumentID`=$documentId || `Document_GUID`='".$document_GUID."') and SiteID='".$site_id."'");
						if($chk_obj>0){
							$error_msg[]="The picture code ".$_POST['pic_code_'.$curr_id]." already exists for this document. Please enter another code";
						}
						else{*/
							$pic_status=0;
				if(isset($_POST["pic_status_".$curr_id])) {
					$pic_status=$_POST["pic_status_".$curr_id];
				}					  	
					$update_obj=$db->query("update pictures set Modified='$time', Code='".$_POST['pic_code_'.$curr_id]."', Status='".$pic_status."', Order_By='".intval($_POST['pic_order_by_'.$curr_id])."', SiteID='".$site_id."', Site_GUID= '$site_guid' where `GUID`='".$_POST['pic_guid'][$i]."'");
					
					if($_FILES['fileinput_'.$curr_id]['size'] > 0)
		{
			$fileName = $_FILES['fileinput_'.$curr_id]['name'];
			$tmpName  = $_FILES['fileinput_'.$curr_id]['tmp_name'];
			$fileSize = $_FILES['fileinput_'.$curr_id]['size'];
			$fileType = $_FILES['fileinput_'.$curr_id]['type'];
	
			$fp = fopen($tmpName, 'r');
			$content = fread($fp, filesize($tmpName));
			$content = addslashes($content);
			fclose($fp);
			if(!get_magic_quotes_gpc())
			{
				$fileName = addslashes($fileName);
			}
			
			//echo "update wi_users set photo_avatar='$content' where uuid='$GUID'";
			$update_pic=$db->query("update pictures set Name='$fileName', Picture='$content', Type='$fileType' where GUID='".$_POST['pic_guid'][$i]."'");
			//$db->debug();
			
		}
					
						/*}*/
					}
					
				}
			}
		}	
	
	if($_POST['pic_code_nw']!=''){
		/*$chk_obj=$db->get_var("select count(*) as num from pictures where Code='".$_POST['pic_code_nw']."' and (`DocumentID`=$documentId || `Document_GUID`='".$document_GUID."') and SiteID='".$site_id."'");
			if($chk_obj>0){
				$error_msg[]="The picture code ".$_POST['pic_code_nw']." already exists for this document. Please enter another code";
			}
			else{*/
			$pic_guid_nw= UniqueGuid('pictures', 'GUID');
				$pic_status=0;
				if(isset($_POST["pic_status_nw"])) {
					$pic_status=$_POST["pic_status_nw"];
				}
				
				$insert_pic=$db->query("insert into pictures(GUID,Created,Modified,SiteID,Name,Code,Status,Type,DocumentID,Picture,Order_By, Site_GUID) values('".$pic_guid_nw."', '$time','$time', '".$site_id."', '', '".$_POST['pic_code_nw']."', '".$pic_status."', '', $documentId, '', '".intval($_POST['pic_order_by_nw'])."', '$site_guid')");
				
				if($_FILES['fileinput_nw']['size'] > 0)
		{
			$fileName = $_FILES['fileinput_nw']['name'];
			$tmpName  = $_FILES['fileinput_nw']['tmp_name'];
			$fileSize = $_FILES['fileinput_nw']['size'];
			$fileType = $_FILES['fileinput_nw']['type'];
	
			$fp = fopen($tmpName, 'r');
			$content = fread($fp, filesize($tmpName));
			$content = addslashes($content);
			fclose($fp);
			if(!get_magic_quotes_gpc())
			{
				$fileName = addslashes($fileName);
			}
			
			//echo "update wi_users set photo_avatar='$content' where uuid='$GUID'";
			$update_pic=$db->query("update pictures set Name='$fileName', Picture='$content', Type='$fileType' where GUID='".$pic_guid_nw."'");
			//$db->debug();
			
		}	
			/*}*/
	}

	
	//$db->debug();
	if(!isset($_GET['GUID']) && $insert && $insert_cat) {
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:blogs.php");
	 }
	 elseif(isset($_GET['GUID']) && $update && $insert_cat) {
	 	 $_SESSION['up_message'] = "Updated successfully";
	 }
}
?>

<?php
if(isset($_GET['GUID'])) {
$GUID= $_GET['GUID'];
$document = $db->get_row("SELECT ID,SiteID,Document,Code,AutoFormatMetaData,Document,Header,Body,MetaTagKeywords,MetaTagDescription,UserID,Title,PageTitle,Type,SiteName,GUID,Site_GUID,Reference,Sync_LastTime, AutoFormatTitle,
PostedTimestamp,Status,Template,FFAlpha80_1,FFAlpha80_2,FFAlpha80_3,FFAlpha80_4 FROM documents where documents.GUID ='$GUID'");

		$documentID=$document->ID;
		$site_id=$document->SiteID;
		
		$Code=$document->Code;
		$Document=$document->Document;
		$Header=$document->Header;
		$Body=$document->Body;
		$MetaTagKeywords=$document->MetaTagKeywords;
		$MetaTagDescription=$document->MetaTagDescription;
		$UserID=$document->UserID;
		$Title=$document->Title;
		$PageTitle=$document->Title;
		$WindowTitle=$document->PageTitle;
		$Type=$document->Type;
		$SiteName=$document->SiteName;
		$document_GUID=$document->GUID;
		$documentSiteGUID=$document->Site_GUID;
		$Reference=$document->Reference;
		$Sync_LastTime=$document->Sync_LastTime;
		$Published_timestamp=$document->PostedTimestamp;
		$FFAlpha80_1=$document->FFAlpha80_1;
		$FFAlpha80_2=$document->FFAlpha80_2;
		$FFAlpha80_3=$document->FFAlpha80_3;
		$FFAlpha80_4=$document->FFAlpha80_4;
		$zStatus=$document->Status;
		$date = date('d/m/Y',$Published_timestamp);
		$time = date('H:i:s',$Published_timestamp);
		$time_arr=explode(":",$time);
		if($time_arr[0]>12){
			$hour=$time_arr[0]-12;
			$am_pm="PM";
		}
		else{
			$hour=$time_arr[0];
			$am_pm="AM";
		}
		$time_string=$hour.":".$time_arr[1]." ".$am_pm;
		$auto_format=$document->AutoFormatTitle;
		$AutoFormatMetaData=$document->AutoFormatMetaData;
		$doc_template=$document->Template;
		$where_cond=" and SiteID ='".$site_id."' ";
		
		if($WebsiteAddress= $db->get_row("SELECT WebsiteAddress FROM sites where ID ='$site_id' ")){
			$websiteAddress=$WebsiteAddress->WebsiteAddress;
		}else{
			$websiteAddress='';
		}
		
$dc = $db->get_results("SELECT CategoryID FROM documentcategories WHERE SiteID='$site_id' AND DocumentID='$documentID'");
//$db->debug();
if($dc != ''){
 foreach($dc as $dc){
	$dcCategoryId[]= $dc->CategoryID;
	
}
}
}
else {
	$documentID='';
		$GUID= '';
		$Code='';
		$site_id='';
		$Document='';
		$Header='';
		$Body='';
		$MetaTagKeywords='';
		$MetaTagDescription='';
		$UserID='';
		$Title='';
		$PageTitle='';
		$WindowTitle='';
		$Type='';
		$SiteName='';
		$document_GUID='';
		$documentSiteGUID='';
		$Reference='';
		$Sync_LastTime='';
		$FFAlpha80_1='';
		$FFAlpha80_2='';
		$FFAlpha80_3='';
		$FFAlpha80_4='';
		$Published_timestamp='';
		$date = date('d/m/Y');
		$time_string=date("h:i A");
		$zStatus=2;
		$auto_format=1;
		$AutoFormatMetaData=1;
		$doc_template='';
		$websiteAddress='';
$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
}
}

require_once('include/main-header.php'); 
?>


</head>
    <body>
		<div id="maincontainer" class="clearfix">
			<!-- header -->
            <header>
                <?php require_once('include/top-header.php');?>
            </header>
            
            <!-- main content -->
            <div id="contentwrapper">
                <div class="main_content">
                    <nav>
                        <div id="jCrumbs" class="breadCrumb module">
	<ul>
		<li>
			<a href="home.php"><i class="icon-home"></i></a>
		</li>
		<li>
			<a href="index.php">Dashboard</a>
		</li>
		<li>
			<a href="blogs.php">Blogs</a>
		</li>
		<li>
			<a href="#">Blog</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                    
					
					 
					 <!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo 'Update Blog'; } else { echo 'Add New blog'; } ?></h3><br/>-->
					  <div id="validation" ><span style="color:#00CC00;font-size:18px">
					  <?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					  </span></div><br/>
					 
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Blog Editor</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Search Optimisation</a></li>
									<li id="li3"><a href="#tab3" data-toggle="tab">Sharing</a></li>
									<li id="li4"><a href="#tab4" data-toggle="tab">Page Setup</a></li>
									<li id="li5"><a href="#tab5" data-toggle="tab" id="tab_cmnts">Blog Comments</a></li>
									<li id="li6"><a href="#tab6" data-toggle="tab">Images</a></li>
								</ul>
								<form action="" method="post" class="form_validation_reg" enctype="multipart/form-data" >
								
								<div class="tab-content">
								
									<div class="tab-pane active" id="tab1">

                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<!-- Site dropdown starts here-->
											<?php include_once("sites_dropdown.php"); ?>
											<!-- Site dropdown ends here-->
										
										<div class="control-group">
												<label class="control-label">Heading<span class="f_req">*</span></label>
												<div class="controls">
												<input type="text" class="span10" name="Document" id="Document" onKeyUp="generate_code('chk_manual','Document','Code')" onBlur="generate_code('chk_manual','Document','Code'); generate_metatags();"  value="<?php echo $Document;?>" />
											<span class="help-block">&nbsp;</span>
											</div></div>
										
											<div class="control-group">
												<label class="control-label">Sub heading</label>
												<div class="controls">
													<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" >
												
													<input type="text" class="span10" name="PageTitle" id="PageTitle"  value="<?php echo $PageTitle;?>" />

													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Body<span class="f_req">*</span></label>
												<div class="controls">
												
													<textarea cols="30" rows="35" class="span10" name="Body" id="wysiwg_full"><?php echo $Body;?></textarea>
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Status <span class="f_req">*</span></label>
											<div class="controls">	
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($zStatus == 1 || $zStatus == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline"> 
												<input type="radio" value="0" name="status" <?php if($zStatus == 0) { echo ' checked'; } ?>/>												Inactive
												

											</label>
										</div></div>
											
										</fieldset>
									</div>
                                </div>

                                <div class="span3">
									<div class="well form-inline">
										<p class="f_legend">Posted By</p>
										<div class="controls">
										<select onChange="" name="UserID" id="UserID"  style="width:140px;" >
		<option value="">-- Select User--</option>
		
		</select>
										&nbsp;At
										<span class="help-block">&nbsp;</span>
										</div><br />
										
								<div class="input-append date" id="dp2">
									<input class="input-small" placeholder="Published Date" type="text" readonly="readonly"  name="Published_timestamp" id="Published_timestamp"  value="<?php echo $date; ?>" data-date-format="dd/mm/yyyy" /><span class="add-on"><i class="splashy-calendar_day"></i></span>
								</div>
									
									<div>
									<span class="help-block">&nbsp;</span>
									<input type="text" class="span8" id="tp_2" name="pub_time" value="<?php echo $time_string; ?>" readonly="readonly" placeholder="Published Time" />
								<span class="help-block">&nbsp;</span>
								</div>
										<?php if(isset($GUID) && $GUID!='' && isset($websiteAddress) && $websiteAddress!=''){	?>
											<a href="<?php echo $websiteAddress.'/content.php?code='.$Code;?>" target="_blank" style="background:none repeat scroll 0 0 #48a6d2; color: #fff; text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);" class="btn">Preview changes</a>
										<?php } ?>										
									</div>
									
									<?php	if(isset($_GET['GUID'])) { ?>
								
								<?php
								$db->select($history_db_name);
								$last_changes=$db->get_results("select * from documents where ID='$documentID' and GUID='".$_GET['GUID']."' order by Created desc limit 0,10");
								
								$db->select($db_name);

								
								if(count($last_changes)>0){
								?>
								
									<div class="well form-inline">
										<p class="f_legend">Last Modified</p>
										<div class="controls">
											<ul>
											<?php
											foreach($last_changes as $last_change){
												
												$change_by_user=$login_user_code;
												
												if($last_change->Document!='') {
											?>
												<li>Document : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('Document', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->PageTitle!='') {
											?>
												<li>Title : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('PageTitle', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->Body!='') {
											?>
												<li>Body : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('Body', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->MetaTagKeywords!='') {
											?>
												<li>Meta Tag Keywords : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('MetaTagKeywords', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->MetaTagDescription!='') {
											?>
												<li>Meta Tag Description : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('MetaTagDescription', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											}
											?>
											
											</ul>
											<input type="hidden" name="rlbk_update_ids" id="rlbk_update_ids" >
											<input type="hidden" name="rlbk_update_col" id="rlbk_update_col" >
										</div><br />
									</div>
								<?php }
								
								 } ?>
									
									
                                </div>


                            </div>

                        </div>
                    </div>
                        


									</div>
									
									
									
									<div class="tab-pane" id="tab2">
										<p>


                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											
											<div class="control-group">
												<label class="control-label">Code<span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" class="span10" name="Code" id="Code" <?php if($auto_format!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $Code;?>" />
													<span class="help-block">URL (SEO friendly)</span>
													<span class="help-block">
													<input type="checkbox" name="chk_manual" id="chk_manual" value="0" <?php if($auto_format==0) { ?> checked="checked" <?php } ?>  />
													I want to manually enter code</span>

												

												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Window Title</label>
												<div class="controls">
													<input type="text" class="span10" name="WindowTitle" id="WindowTitle" <?php if($AutoFormatMetaData!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $WindowTitle;?>" />
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Meta Data Keywords</label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagKeywords" id="MetaTagKeywords" class="span10" <?php if($AutoFormatMetaData!=0) { ?> readonly="readonly" <?php } ?>><?php echo $MetaTagKeywords;?></textarea>
													<span class="help-block">Search terms relevant to this page to find content of this page easily</span>
												</div>
												
												
																								<label class="control-label">Meta Data Description</label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagDescription" id="MetaTagDescription" class="span10" <?php if($AutoFormatMetaData!=0) { ?> readonly="readonly" <?php } ?>><?php echo $MetaTagDescription;?></textarea>
													<span class="help-block">Overview which describes this page (About 300 words)</span>
													<span class="help-block">
													<input type="checkbox" name="chk_manual_metatags" id="chk_manual_metatags" value="0" <?php if($AutoFormatMetaData==0) { ?> checked="checked" <?php } ?> />
													I want to manually enter meta tags</span>
												</div>

											</div>
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
                    </div>


										</p>
									</div>
									
									
									<div class="tab-pane" id="tab3">
										<p>
											<div class="row-fluid">
												<div class="span12">
													<div class="row-fluid">
														<div class="span9">
															<div class="form-horizontal well">
																<fieldset>
																	<div class="control-group">
																		<label class="control-label">Share With:</label>
																		<div class="controls" id="shared_sites">
									<?php	
									$RelatedSitesGUID='';
									if(isset($_GET['GUID'])) { 
										$sh_SiteID = $site_id; 
									}
									elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 								{
										$site_arr=explode("','",$_SESSION['site_id']);
										if(count($site_arr)==1) {
											$sh_SiteID = $_SESSION['site_id']; 
										}
									}
									
									if(isset($sh_SiteID) && $sh_SiteID!='') {
										if($SiteGUID= $db->get_row("SELECT GUID FROM sites where ID ='$sh_SiteID' ")){
											
											if($RelatedSitesGUID = $db->get_results("SELECT uuid_related_site FROM wi_related_sites where  uuid_master_site = '$SiteGUID->GUID'")){ ?>
																			<?php 
																			foreach($RelatedSitesGUID as $relatedsite){
																				$relatedSiteguid=$relatedsite->uuid_related_site;
																				$site = $db->get_row("SELECT Name, GUID FROM sites where GUID = '$relatedSiteguid'");
																				$relatedSiteName=$site->Name;
																				$relatedSiteGUID=$site->GUID;
																				
																				?>
																				
																				<?php 
																				$shared_document=0;
																				if(isset($_GET['GUID']) && $_GET['GUID']!='') {
																				$shared_document= $db->get_var("SELECT count(*) FROM documents_shared_with_sites where uuid_document = '".$_GET['GUID']."' and uuid_site='$relatedSiteGUID'");
																				}
																				
																					?>
																					<input type="checkbox" name="document_share_uuid[]" id="document_share_uuid" value="<?php echo $relatedSiteGUID; ?>" <?php if($shared_document>0) { ?> checked="checked" <?php } ?>  />
																				<?php echo $relatedSiteName;?>
																				<br/>
																			<?php } ?>
										<?php 	} 
										}
									} ?>
									<?php
									if($RelatedSitesGUID=='') {
				 echo "No Site available for sharing";
			}
									?>
																</div>
																	</div>
																</fieldset>
															</div>
														</div>
													</div>
												</div>
											</div>
										</p>
									</div>
									
									
									
					
									
									
									<div class="tab-pane" id="tab4">
										<p>
											<div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											
											
											<div class="control-group">
												<label class="control-label">Template </label>
												<div class="controls">
													
													<select onChange="" name="template" id="template">
													<option value="">--Select Template--</option>
													<?php
													if($templates = $db->get_results("SELECT Code,Name FROM `templates` WHERE 1 $where_cond and Type=0 ORDER BY Name")){
			foreach($templates as $template){
				$temp_code = $template->Code;
				$temp_name = $template->Name;
													?>
													
		<option <?php if($doc_template==$temp_code) { ?> selected="selected" <?php } ?> value="<?php echo $temp_code; ?>"><?php echo $temp_name; ?></option>
				<?php }
				}
				?>
		</select>

												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Categories</label>
												<div class="controls" id="cats" >
													
<?php

$select_cats='';													

			if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
			
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name"))
			{
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;

				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID='$categorygroupId' $where_cond ORDER BY Name")){
				$select_cats.='<optgroup label="'.$categorygroupName.'">';
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				
				$select_cats.='<option value="'.$categoryID.'" >'.$categoryName; 
				if($top_level){ $select_cats.=' ('.$top_level.')'; } 
				$select_cats.='</option>';
				
				}
				$select_cats.='</optgroup>';
				}
	
			}}
			?>
			
			<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID not in (select ID from categorygroups ) $where_cond ORDER BY Name ")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				
				$select_cats.='<option value="'.$categoryID.'" >'.$categoryName; 
				if($top_level){ $select_cats.=' ('.$top_level.')'; } 
				$select_cats.='</option>';
				
				}
				}
				 }

?>

<select name="category1" id="category1" class="cats" onChange="generate_metatags()"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
</select>		

<select name="category2" id="category2" class="cats" onChange="generate_metatags()"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
</select>	

<select name="category3" id="category3" class="cats" onChange="generate_metatags()"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
</select>	

<select name="category4" id="category4" class="cats" onChange="generate_metatags()"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
</select>	

												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Extra 1</label>
												<div class="controls">
													<input type="text" class="span10" name="FFAlpha80_1" id="FFAlpha80_1" onBlur="generate_metatags()" value="<?php echo $FFAlpha80_1;?>" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Extra 2</label>
												<div class="controls">
													<input type="text" class="span10" name="FFAlpha80_2" id="FFAlpha80_2" value="<?php echo $FFAlpha80_2;?>" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Extra 3</label>
												<div class="controls">
													<input type="text" class="span10" name="FFAlpha80_3" id="FFAlpha80_3" value="<?php echo $FFAlpha80_3;?>" />
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Extra 4</label>
												<div class="controls">
													<input type="text" class="span10" name="FFAlpha80_4" id="FFAlpha80_4" value="<?php echo $FFAlpha80_4;?>" />
												</div>
											</div>
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
                    </div>
										</p>
									</div>
																		
									
									
									<div class="tab-pane" id="tab5">
										<p>
										<?php
										if(isset($_GET['GUID'])) {
					$GUID = $_GET['GUID'];
					$document = $db->get_row("SELECT ID,GUID,SiteID FROM documents where GUID ='$GUID' ");
						if(count($document)>0){
							$documentId=$document->ID;
							$SiteID=$document->SiteID;
							$comments= $db->get_results("SELECT * FROM blog_comments where blog_id='$documentId' ");
							if(count($comments)>0){
								foreach($comments as $comment){
									$cmnt_id= $comment->ID;
									$cmnt_uuid= $comment->uuid;			
									$cmnt_status=$comment->Status;										
									$cmnt_by_name=$comment->Name;
									$cmnt_by_email=$comment->email;
									$cmnt_text=$comment->comments;
									$cmnt_order_by=$comment->OrderNum;
									$cmnt_ip_addr=$comment->ip_address;
						?>
							<a name="<?php echo $cmnt_id;?>" ></a>
											<div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											
											<div class="control-group">
												<label class="control-label">Name </label>
												<div class="controls">
													<input type="hidden" class="span10" name="cmnt_id[]" id="cmnt_id<?php echo $cmnt_id;?>" value="<?php echo $cmnt_id;?>" />
													<input type="hidden" class="span10" name="cmnt_guid[]" id="cmnt_guid<?php echo $cmnt_id;?>" value="<?php echo $cmnt_uuid;?>" />
											<input type="text" name="cmnt_by_name_<?php echo $cmnt_id;?>" class="span12" id="cmnt_by_name_<?php echo $cmnt_id;?>" value="<?php echo $cmnt_by_name; ?>"/>
											
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Email </label>
												<div class="controls">
													
													<input type="text" class="span12" name="cmnt_by_email_<?php echo $cmnt_id;?>" id="cmnt_by_email_<?php echo $cmnt_id;?>" value="<?php echo $cmnt_by_email;?>" />

												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Comment </label>
												<div class="controls">
													<textarea cols="30" rows="5" name="cmnt_text_<?php echo $cmnt_id;?>" id="cmnt_text_<?php echo $cmnt_id;?>" class="span10" ><?php echo $cmnt_text;?></textarea>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Order By </label>
												<div class="controls span3">													
													<input type="text" class="span12" name="cmnt_order_by_<?php echo $cmnt_id;?>" id="cmnt_order_by_<?php echo $cmnt_id;?>" value="<?php echo $cmnt_order_by;?>" />
												</div>
												<label class="control-label">IP Address</label>
												<div class="controls span3">													
													<input type="text" class="span12" name="cmnt_ip_addr_<?php echo $cmnt_id;?>" id="cmnt_ip_addr_<?php echo $cmnt_id;?>" value="<?php echo $cmnt_ip_addr;?>" readonly />
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Status </label>
												<div class="controls span3">
													<label class="radio inline">
												<input type="radio" value="1" name="cmnt_status_<?php echo $cmnt_id;?>" <?php if($cmnt_status == 1) { echo ' checked'; } ?> />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="cmnt_status_<?php echo $cmnt_id;?>" <?php if($cmnt_status == 0) { echo ' checked'; } ?> />
												Inactive
											</label>
												</div>
												<div class="controls span3">
													<label class="control-label">Delete</label>													
													<input type="checkbox" name="delete_<?php echo $cmnt_id;?>" id="delete_<?php echo $cmnt_id;?>" value="<?php echo $cmnt_id;?>" /> 
												</div>
											</div>
												
												
											
											
											
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
						</div>
						<?php } } } } ?>
						
						
						<div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<p class="f_legend">Add new Comment </p>
											<div class="control-group">
												<label class="control-label">Name </label>
												<div class="controls">
													
											<input type="text" name="cmnt_by_name" class="span12" id="cmnt_by_name" value=""/>
											
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Email </label>
												<div class="controls">
													
													<input type="text" class="span12" name="cmnt_by_email" id="cmnt_by_email" value="" />

												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Comment </label>
												<div class="controls">
													<textarea cols="30" rows="5" name="cmnt_text" id="cmnt_text" class="span10" ></textarea>
													
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Order By </label>
												<div class="controls span3">
													
													<input type="text" class="span12" name="cmnt_order_by" id="cmnt_order_by" value="" />

												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Status </label>
												<div class="controls">
													<label class="radio inline">
												<input type="radio" value="1" name="cmnt_status" checked="checked"  />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="cmnt_status"  />
												Inactive
											</label>
												</div>
											</div>

											
										</fieldset>
									</div>
                                </div>
								

                            </div>
							</div>
                        </div>
						
						
						
                    </div>
							
							
							<div class="tab-pane" id="tab6">
										<p>
										<?php
										if(isset($_GET['GUID'])) {
					
							$pics= $db->get_results("SELECT * FROM pictures where DocumentID='$documentID' AND SiteID='$site_id' ");
							if(count($pics)>0){
								foreach($pics as $pic){
									$pic_id= $pic->ID;
									$pic_guid= $pic->GUID;
									
																
									
									$Code=$pic->Code;				
									$Active=$pic->Status;										
									$Order_By_Num=$pic->Order_By;
									$mime = $pic->Type;
											
									if($pic->Picture == ''){
									$b64Src ='http://www.placehold.it/80x80/EFEFEF/AAAAAA';
									$size="";
									}
									else{
									$b64Src = "data:".$mime.";base64," . base64_encode($pic->Picture);	
									$image = imagecreatefromstring($pic->Picture);
									$image_width = imagesx($image);
									$image_height = imagesy($image);
									$size= "Size( ".$image_width." X ".$image_height." )";
									}	
										?>
											<div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											
											<div class="control-group">
												<label class="control-label">Code </label>
												<div class="controls">
													<input type="hidden" class="span10" name="pic_id[]" id="pic_id<?php echo $pic_id;?>" value="<?php echo $pic_id;?>" />
													<input type="hidden" class="span10" name="pic_guid[]" id="pic_guid<?php echo $pic_id;?>" value="<?php echo $pic_guid;?>" />
											<input type="text" name="pic_code_<?php echo $pic_id;?>" class="span12" id="pic_code_<?php echo $pic_id;?>" value="<?php echo $Code; ?>"/>
											
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Image </label>
												<div class="controls">
													<div data-fileupload="image" class="fileupload fileupload-new">
														<input type="hidden" />
														<div style="width: 80px; height: 80px;" class="fileupload-new thumbnail">
														<a target="_blank" href="view_image.php?GUID=<?php echo $pic_guid; ?>" title="<?php echo $size; ?>" >
														<img src="<?php echo $b64Src; ?>" alt="" width="80" height="80" id="usr_img" />
														</a>
														</div>
														<div style="width: 80px; height: 80px; line-height: 80px;" class="fileupload-preview fileupload-exists thumbnail"></div>
														<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="fileinput_<?php echo $pic_id;?>" name="fileinput_<?php echo $pic_id;?>" class="pic_file" /></span>
														<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
													</div>
													
													<a target="_blank" href="view_image.php?GUID=<?php echo $pic_guid; ?>" title="View Image" ><?php echo $pic->Name; ?></a>
													
												</div>
	
											</div>
											
											<div class="control-group">
												<label class="control-label">Order By </label>
												<div class="controls">
													
													<input type="text" class="span12" name="pic_order_by_<?php echo $pic_id;?>" id="pic_order_by_<?php echo $pic_id;?>" value="<?php echo $Order_By_Num;?>" />

												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Status </label>
												<div class="controls span3">
													<label class="radio inline">
														<input type="radio" value="1" name="pic_status_<?php echo $pic_id;?>" <?php if($Active == 1) { echo ' checked'; } ?> />
														Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="pic_status_<?php echo $pic_id;?>" <?php if($Active == 0) { echo ' checked'; } ?> />
												Inactive
											</label>
												</div>
												<div class="controls span3">
													<label class="control-label">Delete</label>													
													<input type="checkbox" name="delete_image_<?php echo $pic_id;?>" id="delete_image_<?php echo $pic_id;?>" value="<?php echo $pic_id;?>" /> 
												</div>
											</div>
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
						</div>
						<?php } }  } ?>
						
						
						<div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<p class="f_legend">Add new Image </p>
											<div class="control-group">
												<label class="control-label">Code </label>
												<div class="controls">
											
											
											<input type="text" name="pic_code_nw" class="span12" id="pic_code_nw" value=""/>
											<div id="for_vh" <?php  if(!isset($_SESSION['site_id']) || ($_SESSION['site_id']!='244329095')){ ?> style="display:none" <?php } ?> >
<ul>
<li>Enter <b>thumb-image</b> for Thumbnail Image</li>
<li>Enter <b>main-content</b> for Content page Image</li>
</ul>
</div>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Image </label>
												<div class="controls">
													<div data-fileupload="image" class="fileupload fileupload-new">
														<input type="hidden" />
														<div style="width: 80px; height: 80px;" class="fileupload-new thumbnail">
														<a target="_blank" href="javascript:void(0)"  >
														<img src="http://www.placehold.it/80x80/EFEFEF/AAAAAA" alt="" width="80" height="80" id="usr_img" />
														</a>
														</div>
														<div style="width: 80px; height: 80px; line-height: 80px;" class="fileupload-preview fileupload-exists thumbnail"></div>
														<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="fileinput_nw" name="fileinput_nw" class="pic_file" /></span>
														<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
													</div>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Order By </label>
												<div class="controls">
													
													<input type="text" class="span12" name="pic_order_by_nw" id="pic_order_by_nw" value="" />

												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Status </label>
												<div class="controls">
													<label class="radio inline">
												<input type="radio" value="1" checked="checked" name="pic_status_nw" />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="pic_status_nw" />
												Inactive
											</label>
												</div>
											</div>
												
												
											
											
											
										</fieldset>
									</div>
                                </div>
								

                            </div>
							</div>
                        </div>
						
						
						
                    </div>		
									
									
								</div>
								
								
								
									
								<button class="btn btn-gebo" type="submit" name="submit" id="submit">Save changes</button>
								
								
								
								
								</form>
							</div>
						</div>
					</div>
					
					

                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
            <?php require_once('include/footer.php');?>
			
			  <!-- datepicker -->
            <script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
			<!-- timepicker -->
            <script src="lib/datepicker/bootstrap-timepicker.min.js"></script>
		    
           
			
			  <script type="text/javascript">
			  
			   <?php
			  if(isset($_GET['GUID']) && isset($dcCategoryId) && count($dcCategoryId)>0){
			  	$curr_cat_no=1;
			  	foreach($dcCategoryId as $curr_cat_id){
			  ?>
			  $("#category<?php echo $curr_cat_no; ?>").val('<?php echo $curr_cat_id; ?>');
			  <?php
			  	$curr_cat_no++;
			  	}
			  }
			  ?>
			  
			  
			  function roll_back(col, uuid, doc_id, datetime, btn){			  		
				var	jsonRow= "get_history_data.php?uuid="+uuid+"&doc_id="+doc_id+"&col="+col+"&datetime="+datetime+"&action="+$(btn).html();
				
				$.getJSON(jsonRow,function(result){
					if(result){
						$.each(result, function(i,item)
						{
							var field= col;
							var content= item.content;
							if(field=='Body'){
								tinyMCE.activeEditor.setContent(content);
							}
							else{
								$('#'+field).val(content);	
							}
							if($(btn).html()=='Rollback'){				
								$(btn).html('Cancel');
								if($('#rlbk_update_ids').val()==''){
									$('#rlbk_update_ids').val(item.uuid);
									$('#rlbk_update_col').val(col);
								}
								else{
									$('#rlbk_update_ids').val($('#rlbk_update_ids').val()+','+item.uuid);
									$('#rlbk_update_col').val($('#rlbk_update_col').val()+','+col);
								}
							}
							else if($(btn).html()=='Cancel'){
								$(btn).html('Rollback');
								var arr_uuids=	$('#rlbk_update_ids').val().split(",");
								var arr_cols=	$('#rlbk_update_col').val().split(",");
								 $('#rlbk_update_ids').val('');
								 $('#rlbk_update_col').val('');
								 for(var i=0; i<arr_uuids.length; i++){
									if(arr_uuids[i]!=item.uuid){
										if($('#rlbk_update_ids').val()==''){
											$('#rlbk_update_ids').val(arr_uuids[i]);
											$('#rlbk_update_col').val(arr_cols[i]);
										}
										else{
											$('#rlbk_update_ids').val($('#rlbk_update_ids').val()+','+arr_uuids[i]);
											$('#rlbk_update_col').val($('#rlbk_update_col').val()+','+arr_cols[i]);
										}
									}
								 }
							}
						});
					}
				});				
			}
			  
			function generate_metatags(){
				var status=$('#chk_manual_metatags').prop("checked");
				if(status!=true){
					var site=$('[name="site_id"]').val();
					var document_name= $('#Document').val();
					var content =  tinyMCE.activeEditor.getContent();
					var body = content.replace(/(<([^>]+)>)/ig,"");
					var location = $('#FFAlpha80_1').val();
					var category1 = $('#category1').val();
					var category2 = $('#category2').val();
					var category3 = $('#category3').val();
					var category4 = $('#category4').val();
					// var	jsonRow= "return_metatags_data.php?document_name="+document_name+"&body="+body+"&category1="+category1+"&category2="+category2+"&category3="+category3+"&category4="+category4+"&site_id="+site+"&location="+location;
					
					$.ajax({                
						type: "POST",
						url: "return_metatags_data.php",
						data: {site_id : site, document_name: document_name, body :body, category1 : category1, category2: category2 , category3:category3 , category4:category4, location:location },
						cache: false,
						dataType: "json",  
						success:  function(response){ 
							if(response){
								if(response.WindowTitle!=''){
									$('#WindowTitle').val(response.WindowTitle);
								}
								if(response.MetaTagKeywords!=''){
									$('#MetaTagKeywords').val(response.MetaTagKeywords);
								}if(response.MetaTagDescription!=''){
									$('#MetaTagDescription').val(response.MetaTagDescription);
								}
							}
						}
					});
				}
			} 
			  var val_flag=0;
			$(document).ready(function() {
				//* datepicker
				gebo_datepicker.init();
				gebo_timepicker.init();
				
				<?php if(isset($_GET['cmnt']) && !isset($_POST['submit'])){ ?>
				$('#tab_cmnts').trigger('click');
				
				<?php } ?>
				
				$("#chk_manual").click(function(){
						var status=$(this).attr("checked");
						if(status=="checked"){
							$('#Code').attr("readonly",false);
							$('#Code').val("");
						}
						else
						{
							$('#Code').attr("readonly",true);
							$('#Code').val("");
							generate_code('chk_manual','Document','Code');
						}
					
					});
					
					$("#chk_manual_metatags").click(function(){
						var status=$(this).attr("checked");
						if(status=="checked"){
							$('#WindowTitle').attr("readonly",false);
							$('#WindowTitle').val("");
							$('#MetaTagDescription').attr("readonly",false);
							$('#MetaTagDescription').val("");
							$('#MetaTagKeywords').attr("readonly",false);
							$('#MetaTagKeywords').val("");
						}
						else
						{
							$('#WindowTitle').attr("readonly",true);
							$('#WindowTitle').val("");
							$('#MetaTagDescription').attr("readonly",true);
							$('#MetaTagDescription').val("");
							$('#MetaTagKeywords').attr("readonly",true);
							$('#MetaTagKeywords').val("");
							generate_metatags();
						}
					
					});
					
					$('#Code').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<48 || k>57) && (k<65 || k>90) && (k<97 || k>122) && (k!=45) && (k!=95) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							alert("Allowed characters are A-Z, a-z, 0-9, _, -");
        					return false;
    					}
					
					});
					
					$(".pic_file").change(function(){
				
					var par_div=$(this).parents('div.fileupload');
						var ele_img=par_div.find('img');
						
						par_div.find('a').attr('href','javascript:void(0)');
						par_div.find('a').attr('title','');
						
					if (this.files && this.files[0]) {
            			var reader = new FileReader();

            			reader.onload = function (e) {
						
						ele_img.attr('src', e.target.result);
           				 };

           				 reader.readAsDataURL(this.files[0]);
       				 }
					//$("#usr_img").attr("src",img);
					
					
				});
					
					var site_id=$('[name="site_id"]').val();
					var usr_id='<?php echo $UserID; ?>';
					var	login_usr_id='<?php echo $user_details->ID; ?>';
					$.ajax({
					type: "POST",
					url: "user_list.php",
					data: {'site_id' : site_id, 'usr_id' : usr_id, 'login_usr_id' : login_usr_id},
					success: function(response){
						
						$("#UserID").html(response);
					}
					});
					
					$("#site_id_sel").change(function(){
						site_id=$(this).val();
						if(site_id=='244329095'){
							$('#for_vh').show();
						}
						else{
							$('#for_vh').hide();
						}
						if(site_id!=''){
							$.ajax({
								type: "POST",
								url: "return_sitecode.php",
								data: {'site_id' : site_id},
								success: function(response){						
									//$("#UserID").html(response);
									var i, t = tinyMCE.editors;
									for (i in t){
										if (t.hasOwnProperty(i)){
											t[i].remove();
										}
									}
									tiny_options['moxiemanager_rootpath']= "/wwwroot/"+response+"/";
									tiny_options['moxiemanager_path']= "/wwwroot/"+response+"/";
									tinymce.init(tiny_options);
								}
							 });
						}
						
						var usr_id='<?php echo $UserID; ?>';
						var	login_usr_id='<?php echo $user_details->ID; ?>';
						$.ajax({
						type: "POST",
						url: "user_list.php",
						data: {'site_id' : site_id, 'usr_id' : usr_id, 'login_usr_id' : login_usr_id},
						success: function(response){
							
							$("#UserID").html(response);
						}

					 });
					 
					 $.ajax({
						type: "POST",
						url: "categories_list.php",
						data: {'site_id' : site_id, 'type' : 'blog'},
						success: function(response){
						
							$(".cats").each(function(){
							$(this).html(response);
							});
						}
					 });
					
					$.ajax({
						type: "POST",
						url: "shared_site_list.php",
						data: {'site_id' : site_id },
						success: function(response){
						
							$("#shared_sites").html(response);
							
						}
					 });
				
			});
			$('#submit').click(function(){
				tinyMCE.triggerSave();
			});
			
			
		
		//* regular validation
		gebo_validation.reg();
		
		$('.splashy-calendar_day').click(function(){
			$('#Published_timestamp').datepicker( "show" );
		});
		
		$(document).click(function(event){
			//console.log($(event.target).closest('div').attr('id'));
			if($(event.target).closest('div').attr('id')!='dp2') {
				$('#Published_timestamp').datepicker( "hide" );
			}
		});
		
				
			});
		
			//* bootstrap datepicker
			gebo_datepicker = {
				init: function() {
					$('#Published_timestamp').datepicker({"autoclose": true});
				}
			};
			
			gebo_timepicker = {
		init: function() {
			
			$('#tp_2').timepicker({
				defaultTime: '<?php echo $time_string; ?>',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown'
			});
		}
	};
			
			//* validation
	gebo_validation = {
		
        reg: function() {
            reg_validator = $('.form_validation_reg').validate({
			
				onkeyup: false,
				errorClass: 'error',
				validClass: 'valid',
				highlight: function(element) {
				$(element).closest('div').addClass("f_error");
					var err_div_id=$(element).closest('div.tab-pane').attr('id');
								if($("#"+err_div_id).hasClass("active")){
								
								val_flag=1;
								}
								
					else if(!$("#"+err_div_id).hasClass("active") && val_flag==0){
					//$(element).closest('div').addClass("f_error");
					for(var i=1; i<=6; i++) {
						if(err_div_id=="tab"+i){
							$("#tab"+i).addClass("active");
							$("#li"+i).addClass("active");

						}
						else {
							$("#tab"+i).removeClass("active");
							$("#li"+i).removeClass("active");
						}
					}
					}
						


				},
				unhighlight: function(element) {
					$(element).closest('div').removeClass("f_error");
					val_flag=0;
				},
                errorPlacement: function(error, element) {
                    $(element).closest('div').append(error);
                },
                rules: {
					site_id: { required: true },
					Document: { required: true },
					Body: { required: true, minlength: 10 },
					UserID: { required: true },
					Published_timestamp: { required: true },
					Code: { required: true},
					status: { required: true},
				},
                invalidHandler: function(form, validator) {
					$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
				}
            })
        }
	};

			</script>

<?php
//BSW 20140805 2:13AM handles images paths correctly now

$pSiteRootFolderPath="";

if((!isset($_COOKIE['sitecode']) || $_COOKIE['sitecode']=='') && $site_id!='') { 
$pSiteRoot=$db->get_row("SELECT Code, RootDirectory FROM sites where ID ='".$site_id."' ");

if($pSiteRoot->RootDirectory!='')
{
$pSiteRootFolderPath=$pSiteRoot->RootDirectory;
}
else{
$pSiteRootFolderPath=$pSiteRoot->Code;
}

}

?>			

<script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
//tinymce.PluginManager.load('moxiemanager', '/js/moxiemanager/plugin.min.js');
var tiny_options=new Array();
tiny_options['selector']= "textarea#wysiwg_full";
tiny_options['theme']= "modern";
tiny_options['plugins']= "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor moxiemanager";
tiny_options['theme_advanced_buttons1']= "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect";
tiny_options['theme_advanced_buttons2']= "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor";
tiny_options['theme_advanced_buttons3']= "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen";
tiny_options['theme_advanced_buttons4']= "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak";
tiny_options['theme_advanced_toolbar_location']= "top";
tiny_options['theme_advanced_toolbar_align']= "left";
tiny_options['theme_advanced_statusbar_location']= "bottom";
tiny_options['theme_advanced_resizing']= true;
tiny_options['relative_urls']=false;
tiny_options['remove_script_host']=false;
tiny_options['document_base_url']='http://www.cvscreen.co.uk/';
tiny_options['moxiemanager_rootpath']= "/var/www/vhosts/cvscreen.co.uk/public_html/";
tiny_options['moxiemanager_path']= "/var/www/vhosts/cvscreen.co.uk/public_html/";
tiny_options['setup'] = function(editor) {  editor.on('blur', function (e) {  generate_metatags();  }); };
tinymce.init(tiny_options);



</script>


</div>
	</body>
</html>