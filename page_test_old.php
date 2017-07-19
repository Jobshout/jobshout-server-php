<?php 
session_start();
if($_SESSION['UserEmail'] =='') {
       header("location:index.php");
}

require_once("connect.php"); 

require_once("constants.php");

if(isset($_GET['GUID']) && $_GET['GUID']!='' && isset($_SESSION['site_id']) && $_SESSION['site_id']!=''){
	$chk_num=$db->get_var("select count(*) as num from documents where GUID='".$_GET['GUID']."' and SiteID in ('".$_SESSION['site_id']."') ");
	if($chk_num==0){
		header("Location: page.php");
	}
}

function NewGuid() { 
	$s = strtoupper(md5(uniqid(rand(),true))); 
	$guidText = 
		substr($s,0,8) . '-' . 
		substr($s,8,4) . '-' . 
		substr($s,12,4). '-' . 
		substr($s,16,4). '-' . 
		substr($s,20); 
	return $guidText;
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
			
	$insert=false; 
	$update=false; 
	$insert_cat=true; 

		
		$Code=$_POST["Code"];
		$ShortUrl=$_POST["short_url"];
		if($ShortUrl==''){
		$ShortUrl=$_POST["doc_id"];
		}
		$site_id=$_POST["site_id"];
		$new_Document=addslashes($_POST["Document"]);
		$new_Body=addslashes($_POST["Body"]);
		$new_MetaTagKeywords=addslashes($_POST["MetaTagKeywords"]);
		$new_MetaTagDescription=addslashes($_POST["MetaTagDescription"]);
		$UserID=$_POST["UserID"];
		$template=$_POST["template"];
		$Active=$_POST["status"];
		$time= time();
		
		$auto_format=1;
		if(isset($_POST["chk_manual"])) {
		$auto_format=0;
		}
		
		$new_WindowTitle=addslashes($_POST["WindowTitle"]);
		$new_PageTitle=addslashes($_POST["PageTitle"]);
		$Type=$_POST["Type"];
		
		$arr_pub_date=explode('/', $_POST["Published_timestamp"]);
		$Published_timestamp=$arr_pub_date[1].'/'.$arr_pub_date[0].'/'.$arr_pub_date[2];
		$Published_timestamp = strtotime($Published_timestamp);
		//$Published_timestamp= strtotime($timestamp);
		//$location=$_POST["location"];
		
		$GUID = $_POST["GUID"];
		

	

	if(isset($_GET['GUID'])) {
	
	$guid= $_GET['GUID'];
	
	if(!$db->get_row("select * from documents where short_url='$ShortUrl' and GUID <> '$guid'"))
	{
		
		if($update = $db->query("UPDATE documents SET SiteID='".$site_id."', Document='$new_Document', Code='$Code', short_url='$ShortUrl', Body='$new_Body', MetaTagKeywords='$new_MetaTagKeywords', MetaTagDescription='$new_MetaTagDescription', UserID='$UserID', Modified='$time', Template='$template', 
	Title='$new_PageTitle',
	PageTitle='$new_WindowTitle',
	Type='$Type', Status = '$Active',
	Published_timestamp='$Published_timestamp', AutoFormatTitle=$auto_format
	WHERE GUID ='$guid'")) {
	
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
			$changed_by=$db->get_var("select uuid from wi_users where code='".$_SESSION['UserEmail']."'");
			$doc_id=$documentID;
			$old_Document='';
			$old_PageTitle='';
			$old_Body='';
			$old_MetaTagKeywords='';
			$old_MetaTagDescription='';
			
			if(stripslashes($Document)!=$_POST["Document"]){
				$old_Document=$Document;
			}
			if(stripslashes($PageTitle)!=$_POST["PageTitle"]){
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
				$user_guid=$db->get_var("select uuid from wi_users where code='".$_SESSION['UserEmail']."'");
				$db->select($history_db_name);
				
				$insert = $db->query("INSERT INTO documents (Update_GUID, ID, GUID, Created, Document, Title, Body, MetaTagKeywords, MetaTagDescription, User_GUID) 
						VALUES(UUID(), $doc_id, '".$_GET['GUID']."', '$changed_time', '".addslashes($old_Document)."', '".addslashes($old_PageTitle)."', '".addslashes($old_Body)."', '".addslashes($old_MetaTagKeywords)."', '".addslashes($old_MetaTagDescription)."', '$user_guid')");

				$db->select($db_name);

			}
		}
	
	}
	else
	{
	$error_msg[]="This short url already exists.";
	}
						
		}
		else
		{
			if($ShortUrl=='' || !$db->get_row("select * from documents where short_url='$ShortUrl'"))
			{
				if($ShortUrl==''){				
				$insert = $db->query("INSERT INTO documents (ID,SiteID, Created, Modified,Document,Code,Body,MetaTagKeywords,MetaTagDescription,UserID,Title,PageTitle,Type,Published_timestamp,GUID,Sync_LastTime,AutoFormatTitle,PostedTimestamp,Template, Status) 
										VALUES(NULL,'".$site_id. "','$time','$time', '$new_Document','$Code','$new_Body','$new_MetaTagKeywords','$new_MetaTagDescription','$UserID','$new_PageTitle', '$new_WindowTitle','$Type','$Published_timestamp','$GUID',1,$auto_format,'$Published_timestamp','$template', '$Active')");
				}
				else{
				$insert = $db->query("INSERT INTO documents (ID,SiteID, Created, Modified,Document,Code,short_url,Body,MetaTagKeywords,MetaTagDescription,UserID,Title,PageTitle,Type,Published_timestamp,GUID,Sync_LastTime,AutoFormatTitle,PostedTimestamp,Template, Status) 
										VALUES(NULL,'".$site_id. "','$time','$time', '$new_Document','$Code','$ShortUrl','$new_Body','$new_MetaTagKeywords','$new_MetaTagDescription','$UserID','$new_PageTitle', '$new_WindowTitle','$Type','$Published_timestamp','$GUID',1,$auto_format,'$Published_timestamp','$template', '$Active')");
				
				}
				if($ShortUrl==''){
					$ShortUrl=$db->insert_id;
					$db->query("UPDATE documents SET short_url='$ShortUrl' WHERE GUID ='$GUID'");
				}
			}
			else
			{
				$error_msg[]="This short url already exists.";
			}	
			
		}
	
	
	if($document = $db->get_row("SELECT ID,GUID FROM documents where documents.GUID ='".$_POST["GUID"]."'")) {

	$documentId=$document->ID;
		
	$document_GUID=$document->GUID;
	
	$time= time();
	
	$category1=$_POST["category1"];
	$category2=$_POST["category2"];
	$category3=$_POST["category3"];
	$category4=$_POST["category4"];
	
	$db->query("delete FROM documentcategories WHERE SiteID='".$site_id."' AND DocumentID='$documentId'");
	
	
	$categories = array($category1,$category2,$category3,$category4);
	foreach($categories as $categoriesID){
		if($categoriesID != ''){ 
			
			$selectCategory = $db->get_row("SELECT CategoryGroupID,Server_Number,GUID FROM categories WHERE SiteID='".$site_id."' AND ID='$categoriesID'");
			$CategoryGroupID = $selectCategory->CategoryGroupID;
			$Server_Number = $selectCategory->Server_Number;
			$Category_GUID = $selectCategory->GUID;
			
			$dcGUID=NewGuid();
			
			
			$insert_cat= $db->query("INSERT INTO documentcategories(ID, Created, Modified, SiteID,CategoryGroupID, CategoryID,DocumentID, GUID,Server_Number,Category_GUID,Document_GUID, Sync_Modified) 
			VALUES(Null,'$time','$time','".$site_id."','$CategoryGroupID','$categoriesID','$documentId','$dcGUID','$Server_Number','$Category_GUID','$document_GUID','0')");
			
		}
	}
	
	
	if(isset($_GET['GUID']) && isset($_POST['obj_id']) && isset($_POST['obj_guid'])){
			if(is_array($_POST['obj_id']) && is_array($_POST['obj_guid'])){
				for($i=0; $i<count($_POST['obj_id']); $i++){
					$curr_id=$_POST['obj_id'][$i];
					if(isset($_POST['delete_'.$curr_id])){
						$del_obj=$db->query("delete from objects where `GUID`='".$_POST['obj_guid'][$i]."' and ID='".$_POST['obj_id'][$i]."' and SiteID='".$site_id."'");
					}
					elseif(isset($_POST['chk_editable_'.$curr_id])){
					if($_POST['obj_code_'.$curr_id]!=''){
						$chk_obj=$db->get_var("select count(*) as num from objects where Code='".$_POST['obj_code_'.$curr_id]."' and `GUID`!='".$_POST['obj_guid'][$i]."' and (`DocumentID`=$documentId || `Document_GUID`='".$document_GUID."') and SiteID='".$site_id."'");
						if($chk_obj>0){
							$error_msg[]="The code ".$_POST['obj_code_'.$curr_id]." already exists for this document. Please enter another code";
						}
						else{
							$obj_auto_format=1;
							if(isset($_POST["obj_chk_manual_".$curr_id])) {
								$obj_auto_format=0;
							}
							$obj_order_by=$_POST['OrderBy_'.$curr_id];
							if($obj_order_by=='')
							{
								$obj_order_by=0;
							}						  	
								$update_obj=$db->query("update objects set Modified='$time', Code='".$_POST['obj_code_'.$curr_id]."', `TextObject`='".addslashes($_POST['ObjectText_'.$curr_id])."', `UserID`='$UserID', `Title`='".$_POST['ObjectHeading_'.$curr_id]."', `Order`='".$obj_order_by."', `Type`='".$_POST['Type_'.$curr_id]."', `AutoFormat`=$obj_auto_format where `GUID`='".$_POST['obj_guid'][$i]."' and ID='".$_POST['obj_id'][$i]."' and SiteID='".$site_id."'");
						}
					}
					}
				}
			}
		}	
	
	if($_POST['obj_guid_nw']!='' && $_POST['obj_code_nw']!=''){
		$chk_obj=$db->get_var("select count(*) as num from objects where Code='".$_POST['obj_code_nw']."' and (`DocumentID`=$documentId || `Document_GUID`='".$document_GUID."') and SiteID='".$site_id."'");
			if($chk_obj>0){
				$error_msg[]="The code ".$_POST['obj_code_nw']." already exists for this document. Please enter another code";
			}
			else{
				$obj_auto_format=1;
				if(isset($_POST["obj_chk_manual_nw"])) {
					$obj_auto_format=0;
				}
				$obj_order_by=$_POST['OrderBy_nw'];
				if($obj_order_by=='')
				{
					$obj_order_by=0;
				}
				$insert_obj=$db->query("insert into objects(`SiteID`, `Created`, `Modified`, `Code`, `TextObject`, `DocumentID`, `UserID`, `Title`, `Order`, `Type`, `GUID`, `Document_GUID`, `Status`, `AutoFormat`) values('".$site_id."', '$time','$time', '".$_POST['obj_code_nw']."', '".addslashes($_POST['ObjectText_nw'])."', $documentId, $UserID, '".$_POST['ObjectHeading_nw']."', '".$obj_order_by."', '".$_POST['Type_nw']."', '".$_POST['obj_guid_nw']."', '".$document_GUID."', '1', $obj_auto_format)");	
			}
	}
	
	
	if(isset($_GET['GUID']) && isset($_POST['pic_id']) && isset($_POST['pic_guid'])){
			if(is_array($_POST['pic_id']) && is_array($_POST['pic_guid'])){
				for($i=0; $i<count($_POST['pic_id']); $i++){
					$curr_id=$_POST['pic_id'][$i];

					if($_POST['pic_code_'.$curr_id]!=''){
						/*$chk_obj=$db->get_var("select count(*) as num from pictures where Code='".$_POST['pic_code_'.$curr_id]."' and `GUID`!='".$_POST['pic_guid'][$i]."' and (`DocumentID`=$documentId || `Document_GUID`='".$document_GUID."') and SiteID='".$site_id."'");
						if($chk_obj>0){
							$error_msg[]="The picture code ".$_POST['pic_code_'.$curr_id]." already exists for this document. Please enter another code";
						}
						else{*/
							$pic_status=0;
				if(isset($_POST["pic_status_".$curr_id])) {
					$pic_status=$_POST["pic_status_".$curr_id];
				}					  	
					$update_obj=$db->query("update pictures set Modified='$time', Code='".$_POST['pic_code_'.$curr_id]."', Status='".$pic_status."', Order_By='".$_POST['pic_order_by_'.$curr_id]."' where `GUID`='".$_POST['pic_guid'][$i]."'");
					
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
	
	if($_POST['pic_guid_nw']!='' && $_POST['pic_code_nw']!=''){
		/*$chk_obj=$db->get_var("select count(*) as num from pictures where Code='".$_POST['pic_code_nw']."' and (`DocumentID`=$documentId || `Document_GUID`='".$document_GUID."') and SiteID='".$site_id."'");
			if($chk_obj>0){
				$error_msg[]="The picture code ".$_POST['pic_code_nw']." already exists for this document. Please enter another code";
			}
			else{*/
				$pic_status=0;
				if(isset($_POST["pic_status_nw"])) {
					$pic_status=$_POST["pic_status_".$curr_id];
				}
				
				$insert_obj=$db->query("insert into pictures(GUID,Created,Modified,SiteID,Name,Code,Status,Type,DocumentID,Picture,Order_By) values('".$_POST['pic_guid_nw']."', '$time','$time', '".$site_id."', '', '".$_POST['pic_code_nw']."', '".$pic_status."', '', $documentId, '', '".$_POST['pic_order_by_nw']."')");
				
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
			$update_pic=$db->query("update pictures set Name='$fileName', Picture='$content', Type='$fileType' where GUID='".$_POST['pic_guid_nw']."'");
			//$db->debug();
			
		}	
			/*}*/
	}
	//sharing documents
	
		$delete=$db->query("delete from documents_shared_with_sites where uuid_document='".$_GET['GUID']."'");
		if(isset($_POST['document_share_uuid'])!=''){
			if(is_array($_POST['document_share_uuid'])){
				for($i=0; $i<count($_POST['document_share_uuid']); $i++){
					$curr_site_uuid=$_POST['document_share_uuid'][$i];
					$document_sharing=$db->query("insert into documents_shared_with_sites(`uuid_document`, `uuid_site`) values('".$_GET['GUID']."', '$curr_site_uuid')");
					// $db->debug();
				}
			}
		}
	
	
	
	
	}
	
	
	
	
	if(!isset($_GET['GUID']) && $insert && $insert_cat) {
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:pages.php");
	 }
	 elseif(isset($_GET['GUID']) && $update && $insert_cat) {
	 	 $_SESSION['up_message'] = "Updated successfully";
	 }
	
}


if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$document = $db->get_row("SELECT * FROM documents where documents.GUID ='$guid'");

		$documentID=$document->ID;
		$site_id=$document->SiteID;
		$Code=$document->Code;
		$ShortUrl=$document->short_url;
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
		$zStatus=$document->Status;
		$Published_timestamp=$document->Published_timestamp;
		//$location= $document->FFAlpha80_1;
		$date = Date('d/m/Y',$Published_timestamp);
		$auto_format=$document->AutoFormatTitle;
		$doc_template=$document->Template;
		
		$where_cond=" and SiteID ='".$site_id."' ";

$dc = $db->get_results("SELECT CategoryID FROM documentcategories WHERE SiteID='".$site_id."' AND DocumentID='$documentID'");
//$db->debug();
if($dc != ''){
 foreach($dc as $dc){
	$dcCategoryId[]= $dc->CategoryID;
	
}
}
}
else {
		$guid= '';
		$documentID='';
		$site_id='';
		$Code='';
		$ShortUrl='';
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
		$zStatus=2;
		$Published_timestamp='';
		//$location='';
		$date ='';
		$auto_format=1;
		$doc_template='';
		$dcCategoryId[]='';
		
		$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
}

}



?>
 <?php require_once('include/main-header.php'); ?>

<script type="text/javascript">
function generate_code()
{
	var status=document.getElementById('chk_manual').checked;
	if(status!=true){
		var val=document.getElementById('Document').value;
		var patt=/[^A-Za-z0-9_-]/g;
		var result=val.replace(patt,' ');
		result=result.replace(/\s+/g, ' ');
		result = result.replace(/^\s+|\s+$/g,'');
		result=result.replace(/\s/g, '-');
		result=result.toLowerCase();
		//alert(result);	
		document.getElementById('Code').value=result;
	}
}


function generate_obj_code(id)
{
	var status=document.getElementById('obj_chk_manual_'+id).checked;
	if(status!=true){
		var val=document.getElementById('obj_head_'+id).value;
		var patt=/[^A-Za-z0-9_-]/g;
		var result=val.replace(patt,' ');
		result=result.replace(/\s+/g, ' ');
		result = result.replace(/^\s+|\s+$/g,'');
		result=result.replace(/\s/g, '-');
		result=result.toLowerCase();
		//alert(result);	
		document.getElementById('obj_code_'+id).value=result;
	}
}

function en_dis_code(status,id)
{
	if(status==true)
	{
		document.getElementById('obj_code_'+id).readOnly=false;
		document.getElementById('obj_code_'+id).value='';		
	}
	else
	{
		document.getElementById('obj_code_'+id).readOnly='readonly';
		document.getElementById('obj_code_'+id).value='';
		generate_obj_code(id);
	}
}

function en_dis_obj(status,id)
{	
	if(status==true)
	{
		document.getElementById('obj_head_'+id).readOnly=false;
		if(document.getElementById('obj_chk_manual_'+id).checked==true){
			document.getElementById('obj_code_'+id).readOnly=false;
		}
		document.getElementById('obj_chk_manual_'+id).readOnly=false;
		document.getElementById('ObjectText_'+id).readOnly=false;
		document.getElementById('OrderBy_'+id).readOnly=false;
		document.getElementById('Type_'+id).readOnly=false;
		document.getElementById('delete_'+id).readOnly=false;				
	}
	else
	{
		document.getElementById('obj_head_'+id).readOnly=true;		
		document.getElementById('obj_code_'+id).readOnly=true;
		document.getElementById('obj_chk_manual_'+id).readOnly=true;
		document.getElementById('ObjectText_'+id).readOnly=true;
		document.getElementById('OrderBy_'+id).readOnly=true;
		document.getElementById('Type_'+id).readOnly=true;
		document.getElementById('delete_'+id).readOnly=true;	
	}
}

</script>

 <style>
  .custom-combobox {
    position: relative;
    display: inline-block;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
    /* support: IE7 */
    *height: 1.7em;
    *top: 0.1em;
  }
  
  .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
  	width:auto;
	height:auto;
	background:#FFFFFF;
  }
  .custom-combobox-input {
    margin: 0;
    padding: 0.3em;
	width:400px;
  }
  .ui-autocomplete{
  	max-height:300px;
	overflow:scroll;
  }
  
  </style>
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
			<a href="pages.php">Pages</a>
		</li>
		<li>
			<a href="#">Page</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                    
					
					<!--<div><h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add New"; } ?> Page</h3></div><br/>-->
					<?php if(isset($error_msg) && $error_msg!=''){ ?>
					<div id="validation" ><span style="color:#FF0000;font-size:18px">
					<?php echo implode("<br>",$error_msg); ?>
					 </span></div><br>
					 <?php } ?>
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					 </span></div><br>
					
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Page Editor</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Search Optimisation</a></li>
									<li id="li3"><a href="#tab3" data-toggle="tab">Sharing</a></li>
									<li id="li4"><a href="#tab4" data-toggle="tab">Page Setup</a></li>
									<li id="li5"><a href="#tab5" data-toggle="tab">Objects</a></li>
									<li id="li6"><a href="#tab6" data-toggle="tab">Pictures</a></li>
								</ul>
								<form action="" method="post" class="form_validation_reg" enctype="multipart/form-data">
								<div class="tab-content">
								
									<div class="tab-pane active" id="tab1">

                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<?php include_once("sites_dropdown.php"); ?>

										
										<div class="control-group">
												<label class="control-label">Heading<span class="f_req">*</span></label>
												<div class="controls">
													<input type="hidden" value="<?php if($guid!='') { echo $guid; } else { $Guid = NewGuid();
									echo $Guid; } ?>" name="GUID" class="textbox">
									<input type="hidden" value="<?php echo $documentID; ?>" name="doc_id" class="textbox">
												
													<input type="text" class="span10" name="Document" id="Document" onKeyUp="generate_code()" onBlur="generate_code()" value="<?php echo $Document;?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Sub heading</label>
												<div class="controls">
													
												
													<input type="text" class="span10" name="PageTitle" id="PageTitle" value="<?php echo $PageTitle;?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Body<span class="f_req">*</span></label>
												<div class="controls">
												
													<textarea cols="30" rows="35" class="span10" name="Body" id="Body"><?php echo $Body;?></textarea>
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Status <span class="f_req">*</span></label>
											<div class="controls">	
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($zStatus == 1) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline"> 
												<input type="radio" value="0" name="status" <?php if($zStatus == 0) { echo ' checked'; } ?>/>												Inactive
												

											</label>
										</div></div>
											
										<!--	<div class="control-group">
												<label class="control-label">Location</label>
												<div class="controls">
												
													<input type="text" class="span10" name="location" id="location" value="<?php echo $location;?>" />
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>-->
										
										</fieldset>
									</div>
                                </div>

                                <div class="span3">
									<div class="well form-inline">
										<p class="f_legend">Published By</p>
										<div class="controls">
										<select onChange="" name="UserID" id="UserID" style="width:140px;" >
		<option value="">-- Select User--</option>
		<?php
			$documentUserID = $UserID;
			$users = $db->get_results("SELECT ID,Name FROM `users` WHERE zStatus='Active' $where_cond ORDER BY ID");
			foreach($users as $user){
				$UserID=$user->ID;
				$name=$user->Name;
				?>
				<option <?php if($UserID == $documentUserID) { echo "selected"; } ?> value='<?php echo $UserID; ?>' >
						<?php echo $name; ?>
				</option>
				<?php
			}
		?>
		</select>
										&nbsp;At 
										<span class="help-block">&nbsp;</span>
										</div><br />
										
								<div class="input-append date" id="dp2">
									<input class="input-small" placeholder="Published Date" type="text" readonly="readonly"  name="Published_timestamp" id="Published_timestamp" value="<?php echo $date; ?>"  /><span class="add-on"><i class="splashy-calendar_day"></i></span><span class="help-block">&nbsp;</span>
								</div>
																			
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
												
												$change_by_user=$db->get_var("select code from wi_users where uuid='".$last_change->User_GUID."'");
												
												if($last_change->Document!='') {
											?>
												<li>Document : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)	<br/>											
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('Document', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->PageTitle!='') {
											?>
												<li>Title : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<br/><button class="btn btn-success" type="button" name="rollback" onClick="roll_back('PageTitle', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->Body!='') {
											?>
												<li>Body : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<br/><button class="btn btn-success" type="button" name="rollback" onClick="roll_back('Body', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->MetaTagKeywords!='') {
											?>
												<li>Meta Tag Keywords : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<br/><button class="btn btn-success" type="button" name="rollback" onClick="roll_back('MetaTagKeywords', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->MetaTagDescription!='') {
											?>
												<li>Meta Tag Description : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<br/><button class="btn btn-success" type="button" name="rollback" onClick="roll_back('MetaTagDescription', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
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
													<input type="text" class="span10 code" name="Code" id="Code" <?php if($auto_format!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $Code;?>" />
													<span class="help-block">URL (SEO friendly)</span>
													<span class="help-block">
													<input type="checkbox" name="chk_manual" id="chk_manual" value="0" <?php if($auto_format==0) { ?> checked="checked" <?php } ?>  />
													I want to manually enter code</span>
												</div>
												<div class="control-group">
												<label class="control-label">Short URL</label>
												<div class="controls">
													<input type="text" class="span10 code" name="short_url" id="short_url" value="<?php echo $ShortUrl;?>" />
												</div>
											</div>
											
										<div class="control-group">
												<label class="control-label">Window Title</label>
												<div class="controls">
													
												
													<input type="text" class="span10" name="WindowTitle" id="WindowTitle" value="<?php echo $WindowTitle;?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											

											
											<div class="control-group">
												<label class="control-label">Meta Data Keywords</label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagKeywords" id="MetaTagKeywords" class="span10"><?php echo $MetaTagKeywords;?></textarea>
													<span class="help-block">Search terms relevant to this page to find content of this page easily</span>
												</div>
												
												
																								<label class="control-label">Meta Data Description</label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagDescription" id="MetaTagDescription" class="span10"><?php echo $MetaTagDescription;?></textarea>
													<span class="help-block">Overview which describes this page (About 300 words)</span>
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
												<label class="control-label">Document Type<span class="f_req">*</span> </label>
												<div class="controls">
													
													<select onChange="" name="Type" id="Type">
		<option <?php if($Type=="page") { ?> selected="selected" <?php } ?> value="page">Page</option>
				
		</select>
<span class="help-block">[ Default value : Page ]</span>
												</div>
											</div>
											
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
											<!-- categories -->
											<div class="control-group">
												<label class="control-label">Categories</label>
												<div class="controls" id="cats">
													
													<select onChange="" name="category1" class="cats">
		<option value="">-- Select Category --</option>
			<?php
			if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
			$cat_no=0;
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name"))
			{
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID='$categorygroupId' $where_cond ")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==0) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				}
				?>
				</optgroup>
				<?php
			}}
			?>
			
			<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID not in (select ID from categorygroups ) $where_cond  ORDER BY Name ")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==0) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				}
				?>
			
			<?php }
		?>
		</select>
		<select onChange="" name="category2" class="cats">
		<option value="">-- Select Category --</option>
			<?php
			if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
			$cat_no=0;
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name"))
			{
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID='$categorygroupId' $where_cond "))
				{
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==1) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				}
				?>
				</optgroup>
				<?php
			} }
			?>
			<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID not in (select ID from categorygroups ) $where_cond  ORDER BY Name ")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==1) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				}
				?>
			<?php }
		?>
		</select>
		<select onChange="" name="category3" class="cats" >
		<option value="">-- Select Category --</option>
			<?php
			if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
			$cat_no=0;
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name"))
			{
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID='$categorygroupId' $where_cond "))
				{
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==2) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				}
				?>
				</optgroup>
				<?php
			} } ?>
			
			<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID not in (select ID from categorygroups ) $where_cond  ORDER BY Name ")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==2) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				}
				?>
			
			<?php }
		?>
		</select>
		<select onChange="" name="category4" class="cats" >
		<option value="">-- Select Category --</option>
			<?php
			if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
			$cat_no=0;
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name")){
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID='$categorygroupId' $where_cond ")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==3) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				}
				?>
				</optgroup>
				<?php
			} } ?>
			
			<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID not in (select ID from categorygroups ) $where_cond  ORDER BY Name ")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==3) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				}
				?>
			
			<?php }
		?>
		</select>

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
					<?php if(isset($_GET['GUID'])) {
					$GUID = $_GET['GUID'];
					$document = $db->get_row("SELECT ID,GUID,SiteID FROM documents where GUID ='$GUID' ");
						if(count($document)>0){
							$documentId=$document->ID;
							$SiteID=$document->SiteID;
							$objects= $db->get_results("SELECT * FROM objects where DocumentID='$documentId' AND SiteID='$SiteID' ");
							if(count($objects)>0){
								foreach($objects as $object){
									$obj_id= $object->ID;
									$obj_guid= $object->GUID;
									$objectCode = $object->Title;
									$TextObject = $object->TextObject;
									$OrderBy = $object->Order;
									$Type = $object->Type;
									$TextObject=str_replace("<br>","",$TextObject);
									$obj_code= $object->Code;
									$obj_auto_format=$object->AutoFormat;
								?>
								<div class="row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<div class="span9">
												<div class="form-horizontal well">
													<fieldset>
														<div class="control-group">
															<label class="control-label">Object Heading</label>
															<div class="controls">
															<input type="hidden" class="span10" name="obj_id[]" id="obj_id_<?php echo $obj_id;?>" value="<?php echo $obj_id;?>" />
															<input type="hidden" class="span10" name="obj_guid[]" id="obj_guid_<?php echo $obj_id;?>" value="<?php echo $obj_guid;?>" />
																<input type="text" class="span10" name="ObjectHeading_<?php echo $obj_id;?>" id="obj_head_<?php echo $obj_id;?>" value="<?php echo $objectCode;?>" onKeyUp="generate_obj_code('<?php echo $obj_id;?>')" onBlur="generate_obj_code('<?php echo $obj_id;?>')" />
																<span class="help-block">&nbsp;</span>
															</div>
															<label class="control-label">Code</label>
															<div class="controls">
																<input type="text" class="span10 code" name="obj_code_<?php echo $obj_id;?>" id="obj_code_<?php echo $obj_id;?>" value="<?php echo $obj_code;?>" <?php if($obj_auto_format==1) { ?> readonly="readonly" <?php } ?> />
																<span class="help-block">
																<input type="checkbox" name="obj_chk_manual_<?php echo $obj_id;?>" id="obj_chk_manual_<?php echo $obj_id;?>" value="0" <?php if($obj_auto_format!=1) { ?> checked="checked" <?php } ?> onClick="en_dis_code(this.checked,'<?php echo $obj_id;?>')" />
													I want to manually enter code
																</span>
																<span class="help-block">&nbsp;</span>
															</div>
															<label class="control-label">Object Text</label>
															<div class="controls">
																<textarea cols="30" rows="5" name="ObjectText_<?php echo $obj_id;?>" id="ObjectText_<?php echo $obj_id;?>" class="span10"><?php echo $TextObject;?></textarea>
																<span class="help-block">&nbsp;</span>
															</div>
														</div>
													</fieldset>
												</div>
											</div>
											<div class="span3">
												<div class="well form-inline">
													<div class="controls">
														<label class="control-label">Order</label>
														<input type="text" class="input-small" name="OrderBy_<?php echo $obj_id;?>" id="OrderBy_<?php echo $obj_id;?>" value="<?php echo $OrderBy;?>" />
														<span class="help-block">&nbsp;</span>
													</div>
													<div class="controls">
														<label class="control-label">Type</label>
														<input class="input-small" type="text" name="Type_<?php echo $obj_id;?>" id="Type_<?php echo $obj_id;?>" value="<?php echo $Type; ?>"  />
													</div><br/>
													<div class="controls">
														<input type="checkbox" name="chk_editable_<?php echo $obj_id;?>" id="chk_editable" value="0" checked="checked" onClick="en_dis_obj(this.checked,'<?php echo $obj_id;?>')" />
														<label class="control-label">Editable</label>
													</div>
													<div class="controls">
														<input type="checkbox" name="delete_<?php echo $obj_id;?>" id="delete_<?php echo $obj_id;?>" value="<?php echo $obj_id;?>" />
														<label class="control-label">Delete</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
						<?php }
							}
						}
					}
					?>
					<div class="row-fluid">
						<div class="span12">
							<div class="row-fluid">
								<div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<p class="f_legend">Add new Object </p>
											<div class="control-group">
												<label class="control-label">Object Heading</label>
												<div class="controls">
												<input type="hidden" value="<?php $Guid = NewGuid();
									echo $Guid; ?>" name="obj_guid_nw" class="textbox">
													<input type="text" class="span10" name="ObjectHeading_nw" id="obj_head_nw" value="" onKeyUp="generate_obj_code('nw')" onBlur="generate_obj_code('nw')" />
													<span class="help-block">&nbsp;</span>
												</div>
												<label class="control-label">Code</label>
															<div class="controls">
																<input type="text" class="span10 code" name="obj_code_nw" id="obj_code_nw" value="" readonly="readonly" />
																<span class="help-block">
																<input type="checkbox" name="obj_chk_manual_nw" id="obj_chk_manual_nw" value="0" onClick="en_dis_code(this.checked,'nw')"  />
													I want to manually enter code
																</span>
																<span class="help-block">&nbsp;</span>
															</div>
												<label class="control-label">Object Text</label>
												<div class="controls">
													<textarea cols="30" rows="5" name="ObjectText_nw" id="ObjectText_nw" class="span10"></textarea>
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
										</fieldset>
									</div>
								</div>
								<div class="span3">
									<div class="well form-inline">
										<div class="controls">
											<label class="control-label">Order</label>
											<input type="text" class="input-small" name="OrderBy_nw" id="OrderBy_nw" value="" />
											<span class="help-block">&nbsp;</span>
										</div>
										<div class="controls">
											<label class="control-label">Type</label>
											<input class="input-small" type="text" name="Type_nw" id="Type_nw" value=""  />
										</div><br/>
										<div class="controls">
											<input type="checkbox" name="chk_editable_nw" id="chk_editable" value="0" checked="checked" onClick="en_dis_obj(this.checked,'nw')" />
											<label class="control-label">Editable</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					</p>
				</div>
							
							
						<div class="tab-pane" id="tab6">
										<p>
										<?php
										if(isset($_GET['GUID'])) {
					$GUID = $_GET['GUID'];
					$document = $db->get_row("SELECT ID,GUID,SiteID FROM documents where GUID ='$GUID' ");
						if(count($document)>0){
							$documentId=$document->ID;
							$SiteID=$document->SiteID;
							$pics= $db->get_results("SELECT * FROM pictures where DocumentID='$documentId' AND SiteID='$SiteID' ");
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
												<label class="control-label">Picture </label>
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
												<div class="controls">
													<label class="radio inline">
												<input type="radio" value="1" name="pic_status_<?php echo $pic_id;?>" <?php if($Active == 1) { echo ' checked'; } ?> />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="pic_status_<?php echo $pic_id;?>" <?php if($Active == 0) { echo ' checked'; } ?> />
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
						<?php } } } } ?>
						
						
						<div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<p class="f_legend">Add new Picture </p>
											<div class="control-group">
												<label class="control-label">Code </label>
												<div class="controls">
											
											<input type="hidden" class="span10" name="pic_guid_nw" id="pic_guid_nw" value="<?php $Guid = NewGuid();
									echo $Guid; ?>" />		
											<input type="text" name="pic_code_nw" class="span12" id="pic_code_nw" value=""/>
											
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Picture </label>
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
												<input type="radio" value="1" name="pic_status_nw" />
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
										</p>
									</div>	
							
									
								
								
								
								
									
								<button class="btn btn-gebo" type="submit" name="submit" id="submit" >Save changes</button>
								
								
								
								
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
			
			       
            

            
		            <!-- validation -->
            <script src="lib/validation/jquery.validate.min.js"></script>
           <script src="js/jquery-ui-1.9.1.custom.js"></script>
            
			<script>
	 var xhr;
  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
	  var ele_select= this.element;
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
		  	//alert("show all");
            ui.item.option.selected = true;
			
            this._trigger( "select", event, {
              item: ui.item.option
            });
			
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass last search string as value to search for, displaying last results
            input.autocomplete( "search", 'SHOWALL' );
          });
      },
 
      _source: function( request, response ) {
        //var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
		var ele_select= this.element;
		if(request.term=='SHOWALL'){
			response(ele_select.children( "option" ).map(function() {
          var text = $( this ).text();
		 var value= $( this ).val();
          //if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
		
		}
		else
		{
		var jsonRow = 'sites_list.php?srch='+request.term;
		

		//alert(jsonRowURLStr);
		if(xhr) xhr.abort();
		xhr=$.getJSON(jsonRow,function(result){
			
			if(result){
				var html='<option value=""></option>';

				$.each(result, function(i,item)
				{
					html += '<option value="'+item.id+'">'+item.value+'</option>';
				});
				ele_select.html(html);
				
				
				response(ele_select.children( "option" ).map(function() {
          var text = $( this ).text();
		 var value= $( this ).val();
          //if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
				
				
			}
		});
       
	  } 
		
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {

          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
		  var ele_select= this.element;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;	
			
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.data( "ui-autocomplete" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  $(function() {
    $( "#site_id_sel" ).combobox();
   
	
  });

  </script>
  
			<!-- datepicker -->
            <script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
			
			  <script type="text/javascript">
			  
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
			  
			  
			  var val_flag=0;
			$(document).ready(function() {
				//* datepicker
				gebo_datepicker.init();
				
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
							generate_code();
						}
					
					});
					
					$('.code').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<48 || k>57) && (k<65 || k>90) && (k<97 || k>122) && (k!=45) && (k!=95) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							alert("Allowed characters are A-Z, a-z, 0-9, _, -");
        					return false;
    					}
					
					});
					
					$("#site_id_sel").change(function(){
						var site_id=$(this).val();
						$.ajax({
						type: "POST",
						url: "user_list.php",
						data: "site_id=" + site_id ,
						success: function(response){
							
							$("#UserID").html(response);
						}
					 });
					 
					 $.ajax({
						type: "POST",
						url: "categories_list.php",
						data: {'site_id' : site_id/*, 'type' : 'page'*/},
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
				if($('#wysiwg_full').val()=='') {
					return confirm("Are you sure you want to left the content blank?");
				}
			});
			
			
			$.validator.setDefaults({
        ignore: ""
    });
		
		//* regular validation
		gebo_validation.reg();
			
			});
		
			//* bootstrap datepicker
			gebo_datepicker = {
				init: function() {
					$('#dp2').datepicker({format: "dd/mm/yyyy"}).on('changeDate', function(ev){
						$('#dp2').datepicker('hide');
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
					
					UserID: { required: true },
					Published_timestamp: { required: true },
					Code: { required: true},
									
					Type: { required: true }
					
				},
                invalidHandler: function(form, validator) {
					$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
				}
            })
        }
	};

			</script>
       <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
//tinymce.PluginManager.load('moxiemanager', '/js/moxiemanager/plugin.min.js');

tinymce.init({
	selector: "textarea#Body",
   
	plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste "
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
	autosave_ask_before_unload: false
});


</script>


</div>
	</body>
</html>