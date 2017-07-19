<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from templates where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: template.php");
	}
} 


	
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$temp = $db->get_row("SELECT * FROM templates where GUID ='$guid'");

		$temp_id=$temp->ID;
		$Name=$temp->Name;
		$Class=$temp->Class;
		$temp_blob=$temp->TemplateBLOB;
		$Description=$temp->Description;
		$cache=$temp->CacheBLOB;
		$ip_rule=$temp->IPRules;
		
// $db->debug();
}

// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$site_id=$_POST["site_id"];
	$Code=$_POST["Code"];

	$new_Name=addslashes($_POST["Name"]);
	$new_Class=addslashes($_POST["Class"]);
	$Active=$_POST["status"];
	$UserID=$_POST["userID"];
	$new_Description=addslashes($_POST["Description"]);
	
	$file_data=$_POST["TemplateBLOB"];
	$new_cache=$_POST["CacheBLOB"];

	$new_ip_rules=addslashes($_POST["IPRules"]);
	$time= time();
	
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$user_guid= $db->get_var("select uuid from wi_users where ID='$UserID'");
	
	/*$email_temp=0;
	$clean_up=0;
	$clean_memory=0;
	$crtobr=0;
	$hidden=0;*/
	$cache_enabled=0;
	/*if(isset($_POST['email_temp'])){
		$email_temp=1;
	}
	if(isset($_POST['clean_up'])){
		$clean_up=1;
	}
	if(isset($_POST['clean_memory'])){
		$clean_memory=1;
	}
	if(isset($_POST['crtobr'])){
		$crtobr=1;
	}
	if(isset($_POST['hidden'])){
		$hidden=1;
	}*/
	if(isset($_POST['cache_enabled'])){
		$cache_enabled=1;
	}
	if(isset($_GET['GUID']))
	{
	$chk_code=$db->get_var("select count(*) as num from templates where Code='$Code' and GUID !='".$_GET['GUID']."' and SiteID='".$site_id."'");
	
	}
	else
	{
	$chk_code=$db->get_var("select count(*) as num from templates where Code='$Code' and SiteID='".$site_id."'");
	}
	if($chk_code==0){
	
	$site_detail=$db->get_row("select Code, RootDirectory from sites where ID=".$site_id."");
	if($site_detail->RootDirectory!=''){
		$site_dir=$site_detail->RootDirectory;
	}
	else{
		$site_dir=$site_detail->Code;
	}
	$file_path=SERVER_PATH."\\".$site_dir."\\".$Code;
	
	
	if((!isset($_GET['GUID']) || (isset($_POST['old_file_path']) && $_POST['old_file_path']!=$file_path)) && file_exists($file_path))
	{
		$error_msg=$Code." already exists. Please enter another code";
		
	}
	else
	{

	
	if(isset($_POST['old_file_path']) && file_exists($_POST['old_file_path'])){
	unlink($_POST['old_file_path']);
	}
	
	file_put_contents($file_path,$file_data);


		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		
		//echo "UPDATE categories SET SiteID='".$site_id."', Modified='$time', Code='$Code', CategoryGroupID='$CategoryGroupID', Name='$Name', Active='$Active', UserID='$UserID',
//	Type='$Type',FTS='$fts',
//	Sync_Modified='$Sync_Modified', Auto_Format=$Auto_Format
//	WHERE GUID ='$guid'";
	
	if($db->query("UPDATE templates SET SiteID='".$site_id."', Modified='$time', Code='$Code', Name='$new_Name', Class='$new_Class', Description='$new_Description', Path='".addslashes($file_path)."', TemplateBLOB='".$db->escape($file_data)."', CacheBLOB='".$db->escape($new_cache)."', IPRules='$new_ip_rules', CacheEnabled='$cache_enabled', UserID=$UserID, Status='$Active', Site_GUID= '$site_guid', User_GUID= '$user_guid'
	WHERE GUID ='$guid'")) {
	
		if(isset($_POST['rlbk_update_ids']) && $_POST['rlbk_update_ids']!=''){
				$arr_rlbk_uuids=explode(",", $_POST['rlbk_update_ids']);
				$arr_rlbk_cols=explode(",", $_POST['rlbk_update_col']);
				$db->select($history_db_name);
				for($i=0; $i<count($arr_rlbk_uuids); $i++){
					$delete = $db->query("update templates set ".$arr_rlbk_cols[$i]."='' where New_Template_GUID='".$arr_rlbk_uuids[$i]."'");
				}
				$delete = $db->query("delete from templates where Name='' and TemplateBLOB='' and Description='' and Class='' and CacheBLOB='' and IPRules=''");
				$db->select($db_name);
	
			}
	
			$changed_time=time();
			
			$old_Name='';
			$old_Class='';
			$old_temp_text='';
			$old_Description='';
			$old_cache='';
			$old_ip_rule='';
			
			if(stripslashes($Name)!=$_POST["Name"]){
				$old_Name=$Name;
			}
			if(stripslashes($Class)!=$_POST["Class"]){
				$old_Class=$Class;
			}
			if(stripslashes($temp_blob)!=$_POST["TemplateBLOB"]){
				$old_temp_text=$temp_blob;
			}
			if(stripslashes($Description)!=$_POST["Description"]){
				$old_Description=$Description;
			}
			if(stripslashes($cache)!=$_POST["CacheBLOB"]){
				$old_cache=$cache;
			}
			if(stripslashes($ip_rule)!=$_POST["IPRules"]){
				$old_ip_rule=$ip_rule;
			}
			
			if($old_Name!='' || $old_Class!='' || $old_temp_text!='' || $old_Description!='' || $old_cache!='' || $old_ip_rule!=''){
				$user_guid=$db->get_var("select uuid from wi_users where code='".$_SESSION['UserEmail']."'");
				$db->select($history_db_name);
				
				
				
				$insert = $db->query("INSERT INTO templates (New_Template_GUID, ID, GUID, Created, Name, TemplateBLOB, Description, Class, CacheBLOB, IPRules, User_GUID) 
						VALUES(UUID(), $temp_id, '".$_GET['GUID']."', '$changed_time', '".addslashes($old_Name)."', '".$db->escape($old_temp_text)."', '".addslashes($old_Description)."', '".addslashes($old_Class)."', '".$db->escape($old_cache)."', '".addslashes($old_ip_rule)."', '$login_user_uuid')");
						

				$db->select($db_name);

			}
	
		$_SESSION['up_message'] = "Successfully updated";
	}
	 //$db->debug();
	}
	else
	{
		// echo "INSERT INTO categories (GUID,Created,Modified,SiteID,Name,Code,CategoryGroupID,Active,UserID,Type,FTS,Sync_Modified,Auto_Format,TopLevelID,Order_By_Num,MetaKeywords,MetaDescription,access_level_num) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$Code','$CategoryGroupID','$Active','$UserID','$Type','$fts','$Sync_Modified',$Auto_Format,$top_id,$order_by,'$MetaKeywords','$MetaDescription',$access_level_num)";
		
	$GUID = UniqueGuid('templates', 'GUID');	
	 if($db->query("INSERT INTO templates (GUID, Created, Modified, SiteID, Name, Code, Class, Type, Description, Path, TemplateBLOB, CacheBLOB, IPRules, CacheEnabled, UserID, Status, Site_GUID, User_GUID) VALUES ('$GUID', '$time', '$time', '".$site_id."', '$new_Name', '$Code', '$new_Class', 0, '$new_Description', '".addslashes($file_path)."', '".$db->escape($file_data)."', '".$db->escape($new_cache)."', '$new_ip_rules', '$cache_enabled',  $UserID, '$Active', '$site_guid', '$user_guid')")) {
	
	$_SESSION['ins_message'] = "Successfully Inserted";
	header("Location:templates.php");
	}
	//$db->debug();
	
	}
	}
	
	}
	else
	{
		$error_msg=$Code." already exists. Please enter another code";
	}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$temp = $db->get_row("SELECT * FROM templates where GUID ='$guid'");
		
		$temp_id=$temp->ID;
		$site_id=$temp->SiteID;
		$Code=$temp->Code;
		$Name=$temp->Name;
		$Class=$temp->Class;
		$UserID=$temp->UserID;
		$temp_text=$temp->TemplateBLOB;
		$Description=$temp->Description;
		$cache=$temp->CacheBLOB;
		$cache_enabled=$temp->CacheEnabled;
		$ip_rule=$temp->IPRules;
		$status=$temp->Status;
		$path=$temp->Path;
		$where_cond=" and SiteID ='".$site_id."' ";
		
// $db->debug();
}
else
{
	$guid='';
	$site_id='';
		$Code='';
		$Name='';
		$Class='';
		$UserID='';
		$temp_text='';
		$Description='';
		$cache='';
		$cache_enabled='';
		$ip_rule='';
		$status=2;
		$path="";
		$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}

}

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
			<a href="templates.php">Templates</a>
		</li>
		<li>
			<a href="#">Template</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
                    
					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category</h3>-->
					<?php if(isset($error_msg) && $error_msg!=''){ ?>
					<div ><span style="color:#FF0000;font-size:18px">
					<?php echo $error_msg; ?>
					 </span></div><br>
					 <?php } ?>
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br/>
                    <div class="row-fluid">
					<form class="form_validation_reg" method="post" action="" enctype="multipart/form-data">
                        
                        <div class="span6">

							
							<?php
											// $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="formSep">
											<div class="row-fluid">
													<div class="span4">
															<label >Site Name (code)<span class="f_req">*</span></label>
															
															
																<select onChange="" name="site_id" id="site_id_sel"  style="width:350px">
																	<option value=""></option>
																	<?php
																	if($site=$db->get_row("select id, GUID, name,Code from sites where ID='$site_id'")){ ?>
																			<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
																		<?php 
																	}else{
																		$sites=$db->get_results("select id, GUID, name,Code from sites order by zStatus asc, Name ASC limit 0,100 ");
																		foreach($sites as $site){ ?>
																		<option value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
																		<?php }
																	}				
																	?>
																	</select>
													
													</div>
												</div>
											</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
												<label >Site Name (code)<span class="f_req">*</span></label>
												
												
													<select onChange="" name="site_id" id="site_id_sel" >
													<option value=""></option>
														<?php
														if($sites=$db->get_results("select id, GUID, name,Code from sites where ID='$site_id' ")){
															foreach($sites as $site){ ?>
																<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
															<?php }
														}else {
															$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."') order by zStatus asc, Name ASC limit 0,100 ");
															foreach($sites as $site)
															{
															?>
															<option value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
															<?php } 
														} ?>
													</select>
													
													</div>
												</div>
											</div>
											
											<?php
											} else {
										?>
										<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
										<?php
										} }
										?>	
							
							
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Name <span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" class="textbox">

											<input type="text" name="Name" class="span12" id="Name" value="<?php echo $Name; ?>"/>
											
											
										</div>
										
											</div>
										</div>
										
									<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Code <span class="f_req">*</span></label>
					 							
													<input type="text" class="span12" name="Code" id="Code" value="<?php echo $Code;?>" />
													
													
										</div>
									</div>
								</div>	
										
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Class </label>
					 							
													<input type="text" class="span12" name="Class" id="Class" value="<?php echo $Class ;?>" />
													
													
										</div>
									</div>
								</div>			
									
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Description</label>
											
											<textarea cols="30" rows="5" name="Description" id="Description" class="span12"><?php echo $Description;?></textarea>
										</div>
									</div>
								</div>
								
								
								
								
								<!--<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											
											<label class="checkbox inline">
											
												<input type="checkbox" value="1" name="email_temp" <?php //if($email_temp == 1) { echo ' checked'; } ?>/>
												Email Template
											</label>
											<label class="checkbox inline">
											
												<input type="checkbox" value="1" name="clean_up" <?php //if($clean_up == 1) { echo ' checked'; } ?>/>
												 Remove extra whitespaces
											</label>
											<label class="checkbox inline">
											
												<input type="checkbox" value="1" name="clean_memory" <?php //if($clean_memory == 1) { echo ' checked'; } ?>/>
												 Insert clean memory token 
											</label>
											<label class="checkbox inline">
											
												<input type="checkbox" value="1" name="crtobr" <?php //if($crtobr == 1) { echo ' checked'; } elseif(!isset($_GET['GUID'])){ echo ' checked'; } ?>/>
												Convert CRs to BRs
											</label>
											<label class="checkbox inline">
											
												<input type="checkbox" value="1" name="hidden" <?php //if($hidden == 1) { echo ' checked'; } ?>/>
												Hide it from Job entry
											</label>
										</div>
									</div>
								</div>-->
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Upload the template</label>
											
											<input type="file" id="fileinput" name="fileinput" class="temp_file" /><span>&nbsp;</span>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>OR Write/Edit the Template text below</label>
											
											<textarea cols="30" rows="20" name="TemplateBLOB" id="TemplateBLOB" class="span12 temp_file"><?php echo $temp_text;?></textarea>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label class="checkbox inline">
											
											<input type="checkbox" value="1" name="cache_enabled" <?php if($cache_enabled == 1) { echo ' checked'; } ?>/>
											Cache Enabled</label>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>IP Rules </label>

											<input type="text" name="IPRules" class="span12" id="IPRules" value="<?php echo $ip_rule; ?>"/>
											
											
										</div>
										
											</div>
										</div>
										
									<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Cache</label>
											
											<textarea cols="30" rows="20" name="CacheBLOB" id="CacheBLOB" class="span12"><?php echo $cache;?></textarea>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
									  <div class="span12">
											<label>User <span class="f_req">*</span></label>
											<select onChange="" name="userID" id="UserID">
												<option value="">-- Select User --</option>
											
											</select>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Status<span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($status == 1 || $status == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" <?php if($status == 0) { echo ' checked'; } ?>/>
												Inactive
											</label>
										</div>
									</div>
								</div>
								
								<?php if(isset($_GET['GUID']) && file_exists($path)) { ?>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Last Uploaded from </label>
					 							
											<?php echo $path;?>
											<input type="hidden" name="old_file_path" id="old_file_path" value="<?php echo $path;?>" />		
													
										</div>
									</div>
								</div>	
								
								<?php } ?>
								
								
								<div class="form-actions">
									<button class="btn btn-gebo" type="submit" name="submit">Save changes</button>
									<!--<button class="btn" onclick="window.location.href='categories.php'">Cancel</button>-->
								</div>
							
                        </div>
						
						<?php	if(isset($_GET['GUID'])) { ?>
								
								<?php
								$db->select($history_db_name);
								$last_changes=$db->get_results("select * from templates where ID='$temp_id' and GUID='".$_GET['GUID']."' order by Created desc limit 0,10");
								
								$db->select($db_name);

								
								if(count($last_changes)>0){
								?>
								<div class="span3">
									<div class="well form-inline">
										<p class="f_legend">Last Modified</p>
										<div class="controls">
											<ul>
											<?php
											foreach($last_changes as $last_change){
												
												$change_by_user=$login_user_code;
												
												if($last_change->Name!='') {
											?>
												<li>Name : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('Name', '<?php echo $last_change->New_Template_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>												
												
												</li>
												
											<?php
											}
											
											if($last_change->Class!='') {
											?>
												<li>Class : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('Class', '<?php echo $last_change->New_Template_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->Description!='') {
											?>
												<li>Description : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('Description', '<?php echo $last_change->New_Template_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->TemplateBLOB!='') {
											?>
												<li>Template Text : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('TemplateBLOB', '<?php echo $last_change->New_Template_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->IPRules!='') {
											?>
												<li>IP Rules : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)
												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('IPRules', '<?php echo $last_change->New_Template_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
												</li>
												
											<?php
											}
											
											if($last_change->CacheBLOB!='') {
											?>
												<li>Cache : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y H:i:s",$last_change->Created); ?>)												
												
												<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('CacheBLOB', '<?php echo $last_change->New_Template_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
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
									</div>
								<?php }
								
								 } ?>
						
						</form>
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
           
			<script>
			
			function roll_back(col, uuid, temp_id, datetime, btn){
						  		
				var	jsonRow= "get_component_history.php?uuid="+uuid+"&component_id="+temp_id+"&col="+col+"&datetime="+datetime+"&action="+$(btn).html();;
				
				$.getJSON(jsonRow,function(result){
					if(result){
						$.each(result, function(i,item)
						{
							var field= col;
							var content= item.content;							
							$('#'+field).val(content);
							if(field=="TemplateBLOB"){
								$('#'+field).trigger('change');
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
			
			
				$(document).ready(function() {
					//* regular validation
					gebo_validation.reg();
					
					$.validator.addMethod("content", 
                           function(value, element) {
                              if($("#TemplateBLOB").val()!=''){
							  return true;
							  }
							  else
							  {
							  return false;
							  }
                           }, 
                           "Either upload a file or Write/Edit the Template text"    ); 
					
					$('#Code').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<48 || k>57) && (k<65 || k>90) && (k<97 || k>122) && (k!=45) && (k!=46) && (k!=95) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							alert("Allowed characters are A-Z, a-z, 0-9, _, -, .");
        					return false;
    					}
					
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
						var site_id=$(this).val();
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
					 
					});
					
					$('#fileinput').change(function(){
						if (this.files && this.files[0]) {
							var f= this.files[0];
							var r = new FileReader();
							r.onload = function (e) {
								var contents = e.target.result;
								//alert(contents);
								$('#TemplateBLOB').val(contents);
							};
							r.readAsText(f);
						} 
					});
					
					
				});
				
				
				//* validation
				gebo_validation = {
					
					reg: function() {
						reg_validator = $('.form_validation_reg').validate({
							onkeyup: false,
							errorClass: 'error',
							validClass: 'valid',
							highlight: function(element) {
								$(element).closest('div').addClass("f_error");
							},
							unhighlight: function(element) {
								$(element).closest('div').removeClass("f_error");
							},
							errorPlacement: function(error, element) {
								$(element).closest('div').append(error);
							},
							rules: {
								site_id: { required: true },
								Code: { required: true },
								Name: { required: true },
								userID: { required: true },
								status: { required: true },
								TemplateBLOB: { content: true },
							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
					}
				};
			</script>
			
		</div>
	</body>
</html>