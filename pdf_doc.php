<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from pdf_docs where uuid='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: pdf_doc.php");
	}
}



// to update selected catgory
if(isset($_POST['submit']))
{ 
	$site_id=$_POST["site_id"];
	$title=$_POST["title"];
	if(isset($_POST['access_level']) && $_POST['access_level']!=''){
			$access_level_num=$_POST['access_level'];
	} else {
		$access_level_num= 'Null';
	}
	$Active=$_POST["status"];
	
	$time= time();

	$insert=true; 
	$update=true; 
	$insert_doc=true; 
	$update_file=true;
	
	
	if(isset($_GET['GUID'])) {
		$GUID= $_GET['GUID'];
	
	
	
	//echo "update categorygroups set SiteID='".$site_id."', Modified='$time', Name='$Name',Code='$CODE',Active='$Active',UserID='$user_id',Sync_Modified='$Sync_Modified',Type='$Type', Auto_Format=$Auto_Format where GUID='$guid'";
	
	$update = $db->query("update pdf_docs set SiteID='".$site_id."', modified='$time',doc_title='$title', status='$Active',access_level_num=$access_level_num where uuid='$GUID'");
	 
	 //$db->debug();
	}
	else {
		$GUID=UniqueGuid('pdf_docs', 'uuid');
			//echo "INSERT INTO categorygroups (GUID,Created,Modified,SiteID,Name,Code,Active,UserID,Sync_Modified,Type,Auto_Format) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$CODE','$Active','$user_id','$Sync_Modified','$Type',$Auto_Format)";
			
		$insert = $db->query("INSERT INTO pdf_docs (uuid,created,modified,SiteID,doc_name,doc_title,status,doc_type,doc_blob,access_level_num) VALUES ('$GUID','$time','$time','".$site_id."','','$title','$Active','','',$access_level_num)");
		
		 //$db->debug();
	}
	
	if($insert || $update) {
	
	$pdf = $db->get_row("SELECT id FROM pdf_docs where uuid ='$GUID'");

		$pdfId=$pdf->id;
	
	$db->query("delete FROM pdf_to_documents WHERE siteid='".$site_id."' AND pdf_id='$pdfId'");
	
	
	$documents=$_POST["docu"];
	if(is_array($documents)){
	foreach($documents as $document){
			$doc_guid = $db->get_var("SELECT GUID FROM documents where documents.ID ='$document'");
			
			$pdGUID=UniqueGuid('pdf_to_documents', 'uuid');
			
			
			$insert_doc = $db->query("INSERT INTO pdf_to_documents(uuid, pdf_id, document_id, pdf_uuid, document_uuid, siteid, created, modified) 
			VALUES('$pdGUID', $pdfId, $document, '$GUID', '$doc_guid', '$site_id', '$time', '$time')");
			
		
	}
	}
	
	
	if($_FILES['fileinput']['size'] > 0)
	{
		$fileName = $_FILES['fileinput']['name'];
		$tmpName  = $_FILES['fileinput']['tmp_name'];
		$fileSize = $_FILES['fileinput']['size'];
		$fileType = $_FILES['fileinput']['type'];

		$fp = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);
		if(!get_magic_quotes_gpc())
		{
			$fileName = addslashes($fileName);
		}
		
		
		$update_file = $db->query("update pdf_docs set doc_name='$fileName', doc_blob='$content', doc_type='$fileType' where uuid='".$GUID."'");
		//$db->debug();
		
	} 
	
	}
	
	if(!isset($_GET['GUID']) && $insert && $insert_doc && $update_file) {
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:pdf_docs.php");
	 }
	 elseif(isset($_GET['GUID']) && $update && $insert_doc && $update_file) {
	 	 $_SESSION['up_message'] = "Updated successfully";
	 }

}

if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
$pict = $db->get_row("SELECT * FROM pdf_docs where uuid ='$guid'");

		$site_id=$pict->SiteID;
		$title=$pict->doc_title;				
		$Active=$pict->status;
		$pdf_id=$pict->id;
		$doc_name=$pict->doc_name;	
		$access_level_num=$pict->access_level_num;	
						
		$where_cond=" and SiteID ='".$site_id."' ";
		
		$dc = $db->get_results("SELECT document_id FROM pdf_to_documents WHERE siteid='".$site_id."' AND pdf_id='$pdf_id'");
//$db->debug();
if($dc != ''){
 foreach($dc as $dc){
	$doc_ids[]= $dc->document_id;
	
}
}
		
	}
	else
	{
		$guid='';
		$site_id='';
		$title='';				
		$Active=2;
		$doc_id='';
		$doc_name='';
		$access_level_num='';
		
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
			<a href="pdf_docs.php">PDF Documents</a>
		</li>
		<li>
			<a href="#">PDF Document</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                    

					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category Group</h3>-->
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br/>
                    <div class="row-fluid">
                        
                        <div class="span6">
							
							<form class="form_validation_reg" method="post" action="" enctype="multipart/form-data">
								
								<?php
											//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
												<label >Site Name (code)<span class="f_req">*</span></label>
												
												
													<select name="site_id" id="site_id_sel" >
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
														$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."') order by zStatus asc, Name ASC limit 0,100");
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
										<div class="span4">
											<label>Title <span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" class="textbox">
											<input type="text" name="title" class="span12" id="title" value="<?php echo $title; ?>"/>
											
																					</div>
										
									</div>
								</div>
								
								<?php if(isset($_GET['GUID'])) { ?>
									<div class="control-group formSep">
												<label class="control-label">View current File</label>
												<div class="controls text_line">
												
												<a target="_blank" href="download_pdf_file.php?GUID=<?php echo $_GET['GUID']; ?>" title="Download File" ><i class="splashy-document_letter"></i><?php echo $doc_name; ?></a>
												</div></div>
												<?php } ?>
								
								<div class="control-group formSep">
												<label for="fileinput" class="control-label">
												<?php if(isset($_GET['GUID'])) { ?>
												Change File
												<?php } else { ?>
												Upload File<span class="f_req">*</span>
												<?php } ?>
												</label>
												
													<div class="controls text_line">
												<input type="file" id="fileinput" name="fileinput"  /><span>&nbsp;</span>
												
									</div>	
												
											</div>
								
								
								
								<?php if(false){?>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Document <span class="f_req">*</span></label>
											<select onChange="" name="docu[]" id="docu" multiple="multiple" size="5" style="width:350px">
											
											<?php
												if(isset($_GET['GUID']) || (isset($_SESSION['site_id']) && strpos($_SESSION['site_id'],",")===false)) {
												if($op = $db->get_results("SELECT ID,Document,Code FROM `documents` WHERE 1 $where_cond ORDER BY ID")){
												foreach($op as $opt){
													$id=$opt->ID;
													$doc=$opt->Document;
													
													$top_level_cat='';
													$cat_group='';
													if($top_level_id=$db->get_row("select TopLevelID, CategoryGroupID  from `categories` where code='".$opt->Code."'")){
														if($top_level_id->TopLevelID!=0){
														$top_level_cat=$db->get_var("select Name from `categories` where ID=".$top_level_id->TopLevelID."");
														}
														$cat_group=$db->get_var("select Name from categorygroups where ID=".$top_level_id->CategoryGroupID."");
														
													}
													
													?>
													<option <?php if(isset($doc_ids) && in_array($id, $doc_ids)) { echo "selected"; } ?>  value="<?php echo $id; ?>"><?php if(isset($top_level_cat) && $top_level_cat!=''){ echo $top_level_cat.'('.$cat_group.')'.':  '; } elseif($cat_group!=''){ echo $cat_group.':  '; } echo $doc; ?></option>	
													<?php
												}}
												}
											?>
											</select>
										</div>
									</div>
								</div>
								<?php } ?>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Document Search </label>
											<input type="text" class="span12" name="document" id="document" value="" />
										</div>
										<div class="span6">
											<label>Related Documents </label>
											
											<select onChange="" name="docu[]" id="docu" multiple="multiple" size="5" style="width:350px">
												<?php
													if($doc_ids && isset($_GET['GUID'])) {
														foreach($doc_ids as $doc_id){
														if($doc = $db->get_row("SELECT ID,Document,Code FROM `documents` WHERE 1 $where_cond AND ID='$doc_id' ")){
														?>
														<option  value="<?php echo $doc->ID; ?>" selected><?php echo $doc->Document." (".$doc->Code.")"; ?></option>	
														<?php
													}}}
												?>
											</select>
										</div>
										
									</div>
									<ul style="padding-left:160px;">
										<li><b>(Ctrl + Click to select multiple Documents)</b></li>
									</ul>
								</div>
								
								
												
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label><span class="error_placement">Status </span> <span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($Active == 1 || $Active == 2) { echo ' checked'; } ?> />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" <?php if($Active == 0) { echo ' checked'; } ?> />
												Inactive
											</label>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>User Access Level </label>
											<select onChange="" name="access_level">
												<option value="">-- Select --</option>
												<option <?php if($access_level_num == '0') { echo "selected"; } ?> value="0">Public</option>
												<option <?php if($access_level_num == '1') { echo "selected"; } ?> value="1">Active</option>
												<option <?php if($access_level_num == '2') { echo "selected"; } ?> value="2">Basic</option>
												<option <?php if($access_level_num == '3') { echo "selected"; } ?> value="3">Full</option>
											</select>
										</div>
									</div>
								</div>
								
								<div class="form-actions">
									<button class="btn btn-gebo" type="submit" name="submit">Save changes</button>
									<!--<button class="btn" onclick="window.location.href='categories.php'">Cancel</button>-->
								</div>
							</form>
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
           
			
			<script>
				$(document).ready(function() {
					//search documents
					
					$('#document').blur(function() {
						if($('#document').val()!=''){
						$.__contactSearch();
						}
					});
					$.__contactSearch = function() {
					var ID = '';
						var keyword = $("#document").val() ;
						var ids=$("#docu").val();
						if(ids){
							ID = ids.join();
							
						}
						
						$.ajax({
						  url: "search-documents.php",
						  data: 'keyword='+keyword + '&id=' +ID,
						  cache: false
						}).done(function( html ) {
						
							$("#docu").empty();
							$("#docu").append(html);
						});
						$('#docu').show();
					}
					//* regular validation
					gebo_validation.reg();
					
					$("#site_id_sel").change(function(){
						var site_id=$(this).val();
						$.ajax({
						type: "POST",
						url: "doc_list.php",
						data: "site_id=" + site_id ,
						success: function(response){
							
							$("#docu").html(response);
						}
					 });
					 
					 
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
								title: { required: true },
								'docu[]': { required: true },
								status: { required: true },
								<?php if(!isset($_GET['GUID'])) { ?>
								fileinput: { required: true }
								<?php } ?>
								
								
								
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