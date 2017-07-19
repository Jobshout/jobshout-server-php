<?php 
require_once("include/lib.inc.php");
require_once("include/Metakeywords.php");

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from documents where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: job.php");
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
		$location=$document->FFAlpha80_1;
		$WindowTitle=$document->PageTitle;
}

if(isset($_POST['submit']))
{
		$latitude=0;
		$longitude=0;
			
		if(isset($_POST["WindowTitle"]) && $_POST["WindowTitle"]!='') {
			$new_WindowTitle=addslashes($_POST["WindowTitle"]);
		}else{
			$new_WindowTitle=addslashes($_POST["Document"]);
		}
		$new_PageTitle=addslashes($_POST["PageTitle"]);
		$reference=$_POST["reference"];
		$job_type=$_POST["job_type"];
		// $sal_from=$_POST["sal_from"];
		// $sal_to=$_POST["sal_to"];
		// $sal_dur=$_POST["sal_dur"];
		$sal_from=isset($_POST['sal_from']) ? $_POST['sal_from'] : 0;
		if($sal_from==''){
			$sal_from=0;
		}
		$sal_to=isset($_POST['sal_to']) ? $_POST['sal_to'] : 0;
		if($sal_to==''){
			$sal_to=0;
		}
		$sal_dur=isset($_POST['sal_dur']) ? $_POST['sal_dur'] : '';
		
		$new_location=addslashes($_POST["FFAlpha80_1"]);
		$post_code=$_POST["post_code"];
		$template=$_POST["template"];
		// $FFAlpha80_2 = $_POST["FFAlpha80_2"];
		// $FFAlpha80_4 = $_POST["FFAlpha80_4"];
		$FFAlpha80_2 = isset($_POST['FFAlpha80_2']) ? $_POST['FFAlpha80_2'] : '';
		$FFAlpha80_4 = isset($_POST['FFAlpha80_4']) ? $_POST['FFAlpha80_4'] : '';
		
		if($_POST["latt"]!=''){
		$latitude=$_POST["latt"];
		}
		if($_POST["long"]!=''){
		$longitude=$_POST["long"];
		}
		$site_id=$_POST["site_id"];
		
		$auto_format=1;
		if(isset($_POST["chk_manual"])) {
		$auto_format=0;
		}

		$Code=$_POST["Code"];
		//$new_Body=addslashes(str_replace("http://cdn.jobshout.com","",$_POST["Body"]));
		$new_Body=addslashes($_POST["Body"]);
		$new_Document=addslashes($_POST["Document"]);
		
		//meta tag keywords
		$new_MetaTagKeywords=addslashes($_POST["MetaTagKeywords"]);
		if($new_MetaTagKeywords==""){
			$categoriesStr='';
			$category1=$_POST["category1"];
			$category2=$_POST["category2"];
			$category3=$_POST["category3"];
			$category4=$_POST["category4"];
			$category5=$_POST["category5"];
			$category6=$_POST["category6"];
			
			$categoriesArr = array($category1,$category2,$category3,$category4,$category5,$category6);
			$categoryNameArr=array();
			if(count($categoriesArr)>0){
				foreach($categoriesArr as $categoriesID){
					if($categoriesID != ''){ 
						$categoryNameArr[] = $db->get_var("SELECT Name FROM categories WHERE SiteID='".$site_id."' AND ID='$categoriesID'");
					}
				}
				if(count($categoryNameArr)>0){
					$categoriesStr= implode(",",$categoryNameArr);
				}
			}
			//initiate meta keywords class
			$inst_Metakeywords = new Metakeywords();
			$create_MetaTagKeywords=$new_Document.'- '.$new_location.'- '.$new_Body.'- '.$categoriesStr;
			$new_MetaTagKeywords= $inst_Metakeywords->get( $create_MetaTagKeywords );
		}

		//meta Tag description
		$new_MetaTagDescription=addslashes($_POST["MetaTagDescription"]);
		if($new_MetaTagDescription==""){
			$create_MetaTagDescription=$new_Document.'- '.$new_location.'- '.$new_Body;
			$create_MetaTagDescription=substr($create_MetaTagDescription,0,512);
			
			$getlast_position = strrpos($create_MetaTagDescription, ".");
			
			if($getlast_position!=''){
			$create_MetaTagDescription=substr($create_MetaTagDescription,0,$getlast_position);
			}
			$create_MetaTagDescription=strip_tags($create_MetaTagDescription);
			$new_MetaTagDescription=addslashes($create_MetaTagDescription);
		}

		$UserID=$_POST["UserID"];
		$Active=$_POST["status"];
		$job_status=$_POST["job_status"];
		$time= time();
		$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
		$user_guid= $db->get_var("select uuid from wi_users where ID='$UserID'");
		
		$Type='job';
		$arr_pub_date=explode('/', $_POST["Published_timestamp"]);
		$Published_timestamp=$arr_pub_date[1].'/'.$arr_pub_date[0].'/'.$arr_pub_date[2];
		$pub_time=$_POST["pub_time"];
		if($pub_time==''){
			$pub_time=date('h:i A');
		}
		$pub_time_string=$Published_timestamp." ".$pub_time;
		$Published_timestamp= strtotime($pub_time_string);
		
		$GUID = $_POST["GUID"];
		
		$insert=true; 
		$update=true; 
		$insert_cat=true;
		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		
		if($chk=$db->get_row("select * from documents where Code='$Code' and SiteID='".$site_id."' and GUID<>'$guid'")){
			$error_msg[]="This Code already exists.";
			$update = false;
		}
		else{
			$db->query('SET NAMES utf8');
			if($update = $db->query("update documents set SiteID='".$site_id."',  Modified='$time', Document='$new_Document', Code='$Code', Body='$new_Body', Reference='$reference', FFAlpha80_3='$job_type', FFReal01='$sal_from', FFReal02='$sal_to', FFAlpha80_7='$sal_dur', FFAlpha80_1='$new_location', FFAlpha80_2='$FFAlpha80_2', FFAlpha80_4='$FFAlpha80_4', MetaTagKeywords='$new_MetaTagKeywords', MetaTagDescription='$new_MetaTagDescription', UserID='$UserID', Title='$new_PageTitle', PageTitle='$new_WindowTitle', PostedTimestamp='$Published_timestamp', Published_timestamp='$Published_timestamp', AutoFormatTitle=$auto_format,post_code='$post_code', latitude='$latitude', longitude='$longitude',status = '$Active', Site_GUID= '$site_guid', User_GUID='$user_guid', PublishCode = '$job_status', Template='$template' where GUID='$guid'")) {
			
			if(isset($_POST['rlbk_update_ids']) && $_POST['rlbk_update_ids']!=''){
				$arr_rlbk_uuids=explode(",", $_POST['rlbk_update_ids']);
				$arr_rlbk_cols=explode(",", $_POST['rlbk_update_col']);
				$db->select($history_db_name);
				for($i=0; $i<count($arr_rlbk_uuids); $i++){
					$delete = $db->query("update documents set ".$arr_rlbk_cols[$i]."='' where Update_GUID='".$arr_rlbk_uuids[$i]."'");
				}
				$delete = $db->query("delete from documents where Document='' and PageTitle='' and Body='' and MetaTagKeywords='' and MetaTagDescription='' and FFAlpha80_1=''");
				$db->select($db_name);		
			}
		
				$changed_time=time();
				
				$doc_id=$documentID;
				$old_Document='';
				$old_PageTitle='';
				$old_Body='';
				$old_MetaTagKeywords='';
				$old_MetaTagDescription='';
				$old_location='';
				
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
				if(stripslashes($location)!=$_POST["FFAlpha80_1"]){
					$old_location=$location;
				}
				
				if($old_Document!='' || $old_PageTitle!='' || $old_Body!='' || $old_MetaTagKeywords!='' || $old_MetaTagDescription!='' || $old_location!=''){
					
					$db->select($history_db_name);
					
					$insert = $db->query("INSERT INTO documents (Update_GUID, ID, GUID, Created, Document, Title, Body, MetaTagKeywords, MetaTagDescription, FFAlpha80_1, User_GUID) 
							VALUES(UUID(), $doc_id, '".$_GET['GUID']."', '$changed_time', '$old_Document', '$old_PageTitle', '".addslashes($old_Body)."', '".addslashes($old_MetaTagKeywords)."', '".addslashes($old_MetaTagDescription)."', '".addslashes($old_location)."', '$login_user_uuid')");
	
					$db->select($db_name);
	
				}
			
			}
		}
	}
	else {
	
	if($chk=$db->get_row("select * from documents where Code='$Code' and SiteID='".$site_id."'")){
		$error_msg[]="This Code already exists.";
		$insert = false;
	}
	else{
		$GUID = UniqueGuid('documents', 'GUID');
		
		$db->query('SET NAMES utf8');
		$insert = $db->query("INSERT INTO documents (ID, SiteID, Created, Modified, Document, Code, Body, Reference, FFAlpha80_3, FFReal01, FFReal02, FFAlpha80_7, FFAlpha80_1, MetaTagKeywords, MetaTagDescription, UserID, Title, PageTitle, Type, Published_timestamp, GUID, Sync_LastTime, AutoFormatTitle, PostedTimestamp, post_code, latitude, longitude,status, PublishCode, Template, Site_GUID, User_GUID, FFAlpha80_2, FFAlpha80_4) 
									VALUES(NULL, '".$site_id."','$time','$time', '$new_Document', '$Code', '$new_Body', '$reference', '$job_type', '$sal_from', '$sal_to', '$sal_dur', '$new_location', '$new_MetaTagKeywords', '$new_MetaTagDescription', '$UserID', '$new_PageTitle', '$new_WindowTitle', '$Type', '$Published_timestamp', '$GUID', 1, '$auto_format', '$Published_timestamp','$post_code','$latitude','$longitude','$Active','$job_status', '$template', '$site_guid', '$user_guid', '$FFAlpha80_2', '$FFAlpha80_4')");
	// $db->debug();
	// exit;
	
	}
	
	}
	
	if((isset($_GET['GUID']) && $update) || (!isset($_GET['GUID']) && $insert)) {
	$document = $db->get_row("SELECT ID,GUID FROM documents where documents.GUID ='$GUID'");

		$documentId=$document->ID;
		
		$document_GUID=$document->GUID;
	
	$time= time();
	
	$category1=$_POST["category1"];
	$category2=$_POST["category2"];
	$category3=$_POST["category3"];
	$category4=$_POST["category4"];
	$category5=$_POST["category5"];
	$category6=$_POST["category6"];
	
	$db->query("delete FROM documentcategories WHERE SiteID='".$site_id."' AND DocumentID='$documentId'");
	
	$categories = array($category1,$category2,$category3,$category4,$category5,$category6);
	foreach($categories as $categoriesID){
		if($categoriesID != ''){ 
			
			$selectCategory = $db->get_row("SELECT CategoryGroupID,Server_Number,GUID FROM categories WHERE SiteID='".$site_id."' AND ID='$categoriesID'");
			$CategoryGroupID = $selectCategory->CategoryGroupID;
			$Server_Number = $selectCategory->Server_Number;
			$Category_GUID = $selectCategory->GUID;
			
			$dcGUID=UniqueGuid('documentcategories', 'GUID');
			
			
			$insert_cat= $db->query("INSERT INTO documentcategories(ID, Created, Modified, SiteID,CategoryGroupID, CategoryID,DocumentID, GUID,Server_Number,Category_GUID,Document_GUID, Sync_Modified, UserID, Site_GUID, User_GUID) 
			VALUES(Null,'$time','$time','".$site_id."','$CategoryGroupID','$categoriesID','$documentId','$dcGUID','$Server_Number','$Category_GUID','$document_GUID','0', '$UserID', '$site_guid', '$user_guid')");
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
	}
	
	
	if(!isset($_GET['GUID']) && $insert && $insert_cat) {
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:jobs.php");
	 }
	 elseif(isset($_GET['GUID']) && $update && $insert_cat) {
	 	 $_SESSION['up_message'] = "Updated successfully";
	 }
	
}

if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
	$document = $db->get_row("SELECT ID, SiteID, Document, Code, Body, Reference, FFAlpha80_3,FFAlpha80_2, FFAlpha80_4, FFReal01, FFReal02, FFAlpha80_7, FFAlpha80_1, Title,MetaTagKeywords, MetaTagDescription, UserID, PageTitle, Type, Published_timestamp, GUID, Sync_LastTime,AutoFormatTitle, PostedTimestamp, post_code, latitude, longitude, status, PublishCode, Template FROM documents where documents.GUID ='$guid'");

		$documentID=$document->ID;
		$site_id=$document->SiteID;
		$Code=$document->Code;
		$Document=$document->Document;
		$Body=$document->Body;
		$MetaTagKeywords=$document->MetaTagKeywords;
		$MetaTagDescription=$document->MetaTagDescription;
		$documentUserID=$document->UserID;
		$PageTitle=$document->Title;
		$WindowTitle=$document->PageTitle;
		$auto_format=$document->AutoFormatTitle;	
		$document_GUID=$document->GUID;
		$Reference=$document->Reference;
		
		$job_type=$document->FFAlpha80_3;
		$sal_from=$document->FFReal01;
		$sal_to=$document->FFReal02;
		$sal_dur=$document->FFAlpha80_7;
		$location=$document->FFAlpha80_1;
		
		$FFAlpha80_2=$document->FFAlpha80_2;
		$FFAlpha80_4=$document->FFAlpha80_4;
		
		$post_code=$document->post_code;
		$latitude=$document->latitude;
		if($latitude==0) { $latitude=''; }
		$longitude=$document->longitude;
		if($longitude==0) { $longitude=''; }
		$zStatus=$document->status;
		$publish=$document->PublishCode;
		$Sync_LastTime=$document->Sync_LastTime;
		$Published_timestamp=$document->PostedTimestamp;
		$date = Date('d/m/Y',$Published_timestamp);
		$time_string = date('h:i A',$Published_timestamp);

		$doc_template=$document->Template;
		$where_cond=" and SiteID ='".$site_id."' ";
		
		if($WebsiteAddress= $db->get_row("SELECT WebsiteAddress FROM sites where ID ='$site_id' ")){
			$websiteAddress=$WebsiteAddress->WebsiteAddress;
		}else{
			$websiteAddress='';
		}
		
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
		$Document='';
		$site_id='';
		$Code='';
		
		$Body='';
		$MetaTagKeywords='';
		$MetaTagDescription='';
		$documentUserID='';
		$PageTitle='';
		$WindowTitle='';
		$auto_format=1;	
		$document_GUID='';
		$Reference='';
		
		$job_type='';
		$sal_from='';
		$sal_to='';
		$sal_dur='';
		$location='';
		$post_code='';
		$latitude='';
		$longitude='';
		$FFAlpha80_2='';
		$FFAlpha80_4='';
		$Sync_LastTime='';
		$Published_timestamp='';
		$date = date('d/m/Y');
		$time_string=date("h:i A");
		$zStatus=2;
		$publish=2;
		$doc_template='';
		$websiteAddress='';
		$dcCategoryId[]= '';
		
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
			<a href="jobs.php">Jobs</a>
		</li>
		<li>
			<a href="#">Job</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                    
					
					<!--<div><h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add New"; } ?> Job</h3></div><br/>-->
					<?php if(isset($error_msg) && $error_msg!=''){ ?>
					<div id="validation" ><span style="color:#FF0000;font-size:18px">
					<?php echo implode("<br>",$error_msg); ?>
					 </span></div><br>
					 <?php } ?>
					 <?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ ?>
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php echo $_SESSION['up_message']; $_SESSION['up_message']=''; ?>
					</span></div><br>
					<?php } ?>
					
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Job Editor</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Search Optimisation</a></li>
									<li id="li3"><a href="#tab3" data-toggle="tab">Sharing</a></li>
									<li id="li4"><a href="#tab4" data-toggle="tab">Job Sectors</a></li>
								</ul>
								<form action="" method="post" class="form_validation_reg">
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
												<label class="control-label">Title<span class="f_req">*</span></label>
												<div class="controls">
													
												
													<input type="text" class="span10" name="Document" id="Document" onKeyUp="generate_job_code('chk_manual')" onBlur="generate_job_code('chk_manual')" value="<?php echo $Document;?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Sub Title</label>
												<div class="controls">
													<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" class="textbox">
												
													<input type="text" class="span10" name="PageTitle" id="PageTitle" value="<?php echo $PageTitle;?>"  />
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
												<label class="control-label">Reference</label>
												<div class="controls">
																									
													<input type="text" class="span10" name="reference" id="reference" value="<?php echo $Reference;?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>

											<div class="control-group">
												<label class="control-label">Job Type<span class="f_req">*</span></label>
												<div class="controls">
													
													<select onChange="" name="job_type" id="job_type" >
		<option value="">-- Select Job Type--</option>
		
				<option <?php if($job_type == 'Temporary') { echo "selected"; } ?> value='Temporary' >Temporary</option>
				<option <?php if($job_type == 'Permanent') { echo "selected"; } ?> value='Permanent' >Permanent</option>
				<option <?php if($job_type == 'Contract') { echo "selected"; } ?> value='Contract' >Contract</option>
				<option <?php if($job_type == 'Freelance') { echo "selected"; } ?> value='Freelance' >Freelance</option>
				<option <?php if($job_type == 'Part time') { echo "selected"; } ?> value='Part time' >Part time</option>
				
		</select>
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group" id="salary_range">
												<label class="control-label">Salary<span class="f_req">*</span></label>
												<div class="controls">
													<div class="span4" style="width:80px">
													<input type="text" class="span10" name="sal_from" id="sal_from" value="<?php echo $sal_from;?>"  style="width:80px;" />
													<span class="help-block">&nbsp;</span></div>
													<div class="span4" style="width:20px">&nbsp;To&nbsp;</div>
													<div class="span4" style="width:80px">
													<input type="text" class="span10" name="sal_to" id="sal_to" value="<?php echo $sal_to;?>" style="width:80px;" />
													<span class="help-block">&nbsp;</span></div>
													<div class="span4">
													<select onChange="" name="sal_dur" id="sal_dur" >
				
															<option <?php if($sal_dur == 'per annum') { echo "selected"; } ?> value='per annum' >Per Annum</option>
															<option <?php if($sal_dur == 'per month') { echo "selected"; } ?> value='per month' >Per Month</option>
															<option <?php if($sal_dur == 'per week') { echo "selected"; } ?> value='per week' >Per Week</option>
															<option <?php if($sal_dur == 'per day') { echo "selected"; } ?> value='per day' >Per Day</option>
															<option <?php if($sal_dur == 'per hour') { echo "selected"; } ?> value='per hour' >Per Hour</option>
															
													</select></div>
		
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div id="salary_employer_field">
												<div class="control-group">
													<label class="control-label">Salary<span class="f_req">*</span></label>
													<div class="controls">
														<input type="text" class="span10" name="FFAlpha80_2" id="FFAlpha80_2" value="<?php echo $FFAlpha80_2;?>" />
														<span class="help-block">&nbsp;</span>
													</div>
												</div>
												
												<div class="control-group">
													<label class="control-label">Employer</label>
													<div class="controls">
														<input type="text" class="span10" name="FFAlpha80_4" id="FFAlpha80_4" value="<?php echo $FFAlpha80_4;?>" />
														<span class="help-block">&nbsp;</span>
													</div>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Location<span class="f_req">*</span></label>
												
												
													<div class="span4" style="width:130px">
													<input type="text" class="span10" name="FFAlpha80_1" id="FFAlpha80_1" onKeyUp="generate_job_code('chk_manual')" onBlur="generate_job_code('chk_manual')" value="<?php echo $location;?>" style="width:200px;" />
													<span class="help-block">&nbsp;</span></div>
													<label class="control-label">Post Code</label>
													<div class="span4" style="width:130px">
													<input type="text" class="span10" name="post_code" id="post_code" value="<?php echo $post_code;?>" style="width:130px;" />
													<span class="help-block">&nbsp;</span>
													</div>
													
												
											</div>
											<div class="control-group" id="lat_long_div">
												<label class="control-label">Latitude</label>
													<div class="span4" style="width:130px">
													<input type="text" class="span10" name="latt" id="latt" value="<?php echo $latitude;?>" style="width:130px;" />
													<span class="help-block">&nbsp;</span></div>
													<label class="control-label">Longitude</label>
													<div class="span4" style="width:130px">
													<input type="text" class="span10" name="long" id="long" value="<?php echo $longitude;?>" style="width:130px;" />
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
											
										<div class="control-group">
												<label class="control-label">Job Status <span class="f_req">*</span></label>
											<div class="controls">	
											<label class="radio inline">
												<input type="radio" value="1" name="job_status" <?php if($publish == 1 || $publish == 2) { echo ' checked'; } ?>/>
												Vacant
											</label>
											<label class="radio inline"> 
												<input type="radio" value="0" name="job_status" <?php if($publish == 0) { echo ' checked'; } ?>/>												Filled
												
											</label>
										</div></div>
											
										</fieldset>
									</div>
                                </div>

                                <div class="span3">
									<div class="well form-inline">
										<p class="f_legend">Posted By</p>
										<div class="controls">
										<select onChange="" name="UserID" id="UserID" style="width:140px;" >
		<option value="">-- Select User--</option>
		
		</select>
										&nbsp;At 
										<span class="help-block">&nbsp;</span>
										</div><br />
										
								<div class="input-append date" id="dp2">
									<input class="input-small" placeholder="Posted Date" type="text" readonly="readonly"  name="Published_timestamp" id="Published_timestamp" value="<?php echo $date; ?>"  data-date-format="dd/mm/yyyy" /><span class="add-on"><i class="splashy-calendar_day"></i></span>
								</div>
								
								<div>
									<span class="help-block">&nbsp;</span>
									<input type="text" class="span8" id="tp_2" name="pub_time" value="<?php echo $time_string; ?>" readonly="readonly" placeholder="Published Time" />
								<span class="help-block">&nbsp;</span>
								</div>
										<?php if(isset($guid) && $guid!='' && isset($websiteAddress) && $websiteAddress!=''){	?>
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
											
											if($last_change->FFAlpha80_1!='') {
											?>
												<li>Location : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('FFAlpha80_1', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
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
											
											<!-- categories -->
											<div class="control-group">
												<label class="control-label">Categories</label>
												<div class="controls" id="cats">
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

<select name="category1" id="category1" class="cats"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
</select>		

<select name="category2" id="category2" class="cats"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
</select>	

<select name="category3" id="category3" class="cats"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
</select>	

<select name="category4" id="category4" class="cats"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
</select>

<select name="category5" id="category5" class="cats"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
</select>

<select name="category6" id="category6" class="cats"><option value="">-- Select Category --</option>
<?php echo $select_cats; ?>
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
			
			       
     </div>       

            <!-- datepicker -->
            <script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
			<!-- timepicker -->
            <script src="lib/datepicker/bootstrap-timepicker.min.js"></script>
			
			<script>
			var _salary_range_validation=true;
			function generate_job_code(chkd){
				var status=document.getElementById(chkd).checked;
				if(status!=true){
					var titleStr=document.getElementById("Document").value;
					var patt=/[^A-Za-z0-9_-]/g;
					var result=titleStr.replace(patt,' ');
					result=result.replace(/\s+/g, ' ');
					result = result.replace(/^\s+|\s+$/g,'');
					result=result.replace(/\s/g, '-');
					result=result.toLowerCase();
					if(result!=''){
						var locationStr=document.getElementById("FFAlpha80_1").value;
						if(locationStr!=""){
							var patt=/[^A-Za-z0-9_-]/g;
							var loc_result=locationStr.replace(patt,' ');
							loc_result=loc_result.replace(/\s+/g, ' ');
							loc_result = loc_result.replace(/^\s+|\s+$/g,'');
							loc_result=loc_result.replace(/\s/g, '-');
							loc_result=loc_result.toLowerCase();
							//result =result+'-'+loc_result;
							var result1 =result+'-'+loc_result;
							if((result1.length)>=81){
								result =result;
							}else{
								result =result+'-'+loc_result;
							}
						}
					}
					document.getElementById("Code").value=result;
				}
			}
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
			function __display_hide_salary_fields(site_id){
				if(site_id!=''){
					$.ajax({
						type: "POST",
						url: "display_salary_fields.php",
						data: {'site_id' : site_id},
						success: function(response){
							if(response=='true'){
								$_salary_range_validation=false;
								$('#salary_employer_field').show();
								$('#salary_range').hide();
							}else{
								_salary_range_validation=true;
								$('#salary_employer_field').hide();
								$('#salary_range').show();
							}
						}
					});
				}else{
					$('#lat_long_div').hide();
				}
			}
			function _display_lat_long(site_id){
				if(site_id!=''){
					$.ajax({
						type: "POST",
						url: "display_lat_long.php",
						data: {'site_id' : site_id},
						success: function(response){
							if(response=='true'){
								// console.log('show');
								$('#lat_long_div').show();
							}else{
								// console.log('hide');
								$('#lat_long_div').hide();
							}
						}
					});
				}else{
					// console.log('hide');
					$('#lat_long_div').hide();
				}
			}
			
			var val_flag=0;
				$(document).ready(function() {
					$('#salary_employer_field').hide();
					//* regular validation
					$.validator.setDefaults({
						ignore: ":hidden"
					});
				
					gebo_validation.reg();
					//* datepicker
					gebo_datepicker.init();
					
					$('#tp_2').timepicker({
						defaultTime: '<?php echo $time_string; ?>',
						minuteStep: 1,
						disableFocus: true,
						template: 'dropdown'
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
							generate_job_code('chk_manual');
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
					
					var site_id=$('[name="site_id"]').val();
					// console.log(site_id);
					_display_lat_long(site_id);
					__display_hide_salary_fields(site_id);
					
					var usr_id='<?php echo $documentUserID; ?>';
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
							_display_lat_long(site_id)
							__display_hide_salary_fields(site_id);
						}
						
						
						var usr_id='<?php echo $documentUserID; ?>';
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
						data: {'site_id' : site_id, 'type' : 'job'},
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
			
			$('.splashy-calendar_day').click(function(){
				$('#Published_timestamp').datepicker( "show" );
			});
			
			$(document).click(function(event){
				//console.log($(event.target).closest('div').attr('id'));
				if($(event.target).closest('div').attr('id')!='dp2') {
					$('#Published_timestamp').datepicker( "hide" );
				}
			});	
			
			$.validator.addMethod("checkrequired", function (value, element) {
				var field_id = $(element).attr('id');
				console.log(field_id)
				if(_salary_range_validation==true){
					return value==$("#"+field_id).val();
					console.log('hi')
				}else{
					return value==$("#"+field_id).val(); 
					console.log('hiiii')
				}
			}, 'This field is required.');
		
		});
				//* bootstrap datepicker
				gebo_datepicker = {
					init: function() {
						$('#Published_timestamp').datepicker({"autoclose": true});						
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
					for(var i=1; i<=4; i++) {
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
								Body: { required: true },
								job_type: { required: true },
								sal_from: { required: true },
								sal_to: { required: true },
								sal_dur: { required: true },
								FFAlpha80_2: { required: true	},
								FFAlpha80_1: { required: true },
								UserID: { required: true },
								Published_timestamp: { required: true },
								Code: { required: true },
								status: { required: true },
								job_status: { required: true }
								
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
tiny_options['selector']= "textarea#Body";
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
tiny_options['document_base_url']='http://cdn.jobshout.com/';
<?php if(isset($_COOKIE['sitedir']) && $_COOKIE['sitedir']!='') { ?>
tiny_options['moxiemanager_rootpath']= "/vhosts/<?php echo $_COOKIE['sitedir']; ?>/";
tiny_options['moxiemanager_path']= "/vhosts/<?php echo $_COOKIE['sitedir']; ?>/";
<?php } elseif($pSiteRootFolderPath!='') { ?>
tiny_options['moxiemanager_rootpath']= "/vhosts";
tiny_options['moxiemanager_path']= "/vhosts/<?php echo $pSiteRootFolderPath; ?>/";
<?php } ?>
tinymce.init(tiny_options);

</script>

	</body>
</html>