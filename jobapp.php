 <?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from jobapplications where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: jobapp.php");
	}
}
				
				if(isset($_POST['submit']))
				{	
					$Email = $_POST["Email"];
					$site_id = $_POST['site_id'];
					$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
					/*if(isset($_GET['GUID']))
					{
					
						$sql= $db->get_var("SELECT count(*) FROM jobapplications WHERE Email='$Email' AND SiteID='".$site_id."' and GUID !='".$_GET['GUID']."'");
					}
					else {
						$sql= $db->get_var("SELECT count(*) FROM jobapplications WHERE Email='$Email' AND SiteID='".$site_id."' and GUID not in (select JobApplication_GUID from jobsapplicants where SiteID='".$site_id."')");
						}
						if($sql != 0)
					{
						$msg_user = "This Email address is already exist";
						} else 	{*/

						
						$Name = addslashes($_POST["Name"]);
						$Email = $_POST["Email"];
						$TelephoneMobile = $_POST["TelephoneMobile"];
						$Comments = addslashes($_POST["Comments"]);
						$CV_File_Content = addslashes($_POST["CV_File_Content"]);
						$time = time();
						
						$insert=true; 
						$update=true; 
						$update_file=true;
						
					 if(isset($_GET['GUID']))
					 {
					
					$update = $db->query("UPDATE jobapplications SET  Name = '$Name', SiteID='$site_id', Site_GUID='$site_guid', Email = '$Email', TelephoneMobile = '$TelephoneMobile', Comments = '$Comments', Modified = '$time', CV_File_Content ='$CV_File_Content' where  GUID = '".$_GET['GUID']."'");	
					 
					 
					 
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
									
									//echo "update jobapplications set zCV='$content', CVFileType='$fileType', CVFileName='$fileName' where GUID='$GUID'";
									$update_file = $db->query("update jobapplications set zCV='$content', CVFileType='$fileType', CVFileName='$fileName' where GUID='".$_GET['GUID']."'");
									//$db->debug();
									
	}
	
						
				 
				 //$db->debug();
	}
		else {
				//echo "INSERT INTO jobapplications (GUID, SiteID, Name, Email, TelephoneMobile, Comments, created, modified,SourceSite,Free_Text_Search,CV_File_Content,Rank,SourceType)  VALUES ('$GUID', '$site_id', '$Name', '$Email', '$TelephoneMobile', '$Comments', '$time', '$time', '', '', '', '', '')";
			$GUID=UniqueGuid('jobapplications', 'GUID');	
			$insert = $db->query("INSERT INTO jobapplications (GUID, SiteID,Site_GUID, Name, Email, TelephoneMobile, Comments, created, modified,SourceSite,Free_Text_Search,CV_File_Content,Rank,SourceType,HomePostcode,SalaryExpectations)  VALUES ('$GUID', '$site_id','$site_guid', '$Name', '$Email', '$TelephoneMobile', '$Comments', '$time', '$time', '', '', '$CV_File_Content', '', '', '', '')");
			
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
									
									//echo "update jobapplications set zCV='$content', CVFileType='$fileType', CVFileName='$fileName' where GUID='$GUID'";
									$update_file = $db->query("update jobapplications set zCV='$content', CVFileType='$fileType', CVFileName='$fileName' where GUID='$GUID'");
									//$db->debug();
									
	}
			
			
			//$db->debug();
		}
/*	}*/
	
	if(!isset($_GET['GUID']) && $insert && $update_file) {
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:jobapps.php");
	 }
	 elseif(isset($_GET['GUID']) && $update && $update_file) {
	 	 $_SESSION['up_message'] = "Updated successfully";
	 }
}		

$CV_File_Content=''; $GUID=''; $site_id=''; $Name='';
$Email=''; $TelephoneMobile=''; $Comments=''; $cv_file=''; $CV_Extracted_Information="";	
if(isset($_GET['GUID'])){
	if($jobApplicationDetails = $db->get_row("SELECT GUID, SiteID, Name, Email, TelephoneMobile, Comments, modified, CVFileName, CV_File_Content, CV_Extracted_Information FROM jobapplications where GUID = '".$_REQUEST['GUID']."'")){
	 	$GUID=$jobApplicationDetails->GUID;
		$site_id=$jobApplicationDetails->SiteID;
		$Name=$jobApplicationDetails->Name;
		$Email=$jobApplicationDetails->Email;
		$TelephoneMobile=$jobApplicationDetails->TelephoneMobile;
		$Comments=$jobApplicationDetails->Comments;
		$cv_file=$jobApplicationDetails->CVFileName;
		$CV_File_Content=$jobApplicationDetails->CV_File_Content;
		$CV_Extracted_Information=$jobApplicationDetails->CV_Extracted_Information;
	}
}
?>
<style>
.row-fluid {
width: 99%!important;
  *zoom: 1;
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
			<a href="jobapps.php">Job Applications</a>
		</li>
		<li>
			<a href="#">Job Application</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
					 
<div class="row-fluid" id="showPrevNext" style="display:none;">
	<a href="javascript:void(0)" style="float:left" id="prevBtn" title="Previous Application"><img src="img/previous.png" alt="< Previous"/></a>
	<a href="javascript:void(0)" style="float:right" id="nextBtn"  title="Next Application"><img src="img/next.png" alt="Next >"/></a>
</div>					
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){	?>
					<br/>
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php  echo $_SESSION['up_message']; $_SESSION['up_message']=''; ?>
					</span></div>
					<br/>
					<?php } ?>
 						
                    <div class="row-fluid">
							
								<form name="form1" id="form1" class="form-horizontal form_validation_reg" action="" enctype="multipart/form-data" method="post" >
									<div class="span4">	
										<fieldset>
											<?php
											//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="control-group ">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												<div class="controls">
												
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
													
													<span>&nbsp;</span>
												</div>
											</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="control-group">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												<div class="controls">
												
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
																					
													<span>&nbsp;</span>
												</div>
											</div>
											
											<?php
											} else {
										?>
										<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
										<?php
										} }
										?>	
										
										<?php
										if(isset($_GET['GUID'])){
											if($chk_applied_for=$db->get_var("select Job_GUID from jobsapplicants where JobApplication_GUID='".$_GET['GUID']."'"))
											{
												if($job_applied_for=$db->get_var("select Document from documents where GUID = '".$chk_applied_for."'"))
												{
											?>
											<div class="control-group ">
												<label class="control-label">Job Applied for</label>
												<div class="controls text_line">
													
													<input type="text"  name="applied_for" id="applied_for" class="input-xlarge" value="<?php echo $job_applied_for; ?>" style="width:400px;" readonly>
													<span>&nbsp;</span>
												</div></div>
											
											<?php
												}
											}
										}
										
										?>
										
										
											<div class="control-group ">
												<label class="control-label">Name<span class="f_req">*</span></label>
												<div class="controls text_line">
													<input type="hidden" value="<?php if($GUID!="") { echo $GUID; } ?>" name="GUID" id="GUID" >
													<input type="text"  name="Name" id="Name" class="input-xlarge span12" value="<?php echo $Name; ?>">
													<span>&nbsp;</span>
												</div></div>
											
												
												
												<div class="control-group ">
												<label class="control-label">Email<span class="f_req">*</span></label>
												<div class="controls text_line">
													
													<input type="text"  name="Email" id="Email" class="input-xlarge span12" value="<?php echo $Email; ?>">
													<span>&nbsp;</span>
												</div></div>
												
												<div class="control-group ">
												<label class="control-label">Telephone/Mobile<span class="f_req">*</span></label>
												<div class="controls text_line">
													
													<input type="text"  name="TelephoneMobile" id="TelephoneMobile" class="input-xlarge span12" value="<?php echo $TelephoneMobile; ?>">
													<span>&nbsp;</span>
												</div></div>
												
												<?php if($cv_file!='') { ?>
									<div class="control-group ">
												<label class="control-label">Download Original CV</label>
												<div class="controls text_line">
												
												<a target="_blank" href="download_cv.php?GUID=<?php echo $_GET['GUID']; ?>" title="Download CV" ><i class="splashy-document_letter"></i><?php echo $cv_file; ?></a>
												</div></div>
												<?php } ?>
												
													
												<div class="control-group ">
												<label class="control-label">
												<?php if(isset($_GET['GUID'])) { ?>
												Upload new CV
												<?php } else { ?>
												Attach CV<span class="f_req">*</span>
												<?php } ?>
												</label>
												<div class="controls text_line">
												<input type="file" id="fileinput" name="fileinput"  /><span>&nbsp;</span>
												
									</div></div>
									
									
											
											
												<div class="control-group ">
												<label for="u_signature" class="control-label">Comments</label>
												<div class="controls">
													<textarea rows="10" id="Comments" name="Comments" class="input-xlarge span12"><?php echo $Comments; ?></textarea>
													<span>&nbsp;</span>
													<span class="help-block">Automatic resize</span>
												</div>
											</div>
											
											
											
									
											
										
										</fieldset>
									</div>
									<?php if($GUID!="") {	?>
									<div class="span7" style="float:right;">
										
										<div class="tabbable">
											<ul class="nav nav-tabs">
												<li id="li1" class="active"><a href="#tab1" data-toggle="tab">CV Preview</a></li>
												<li id="li2"><a href="#tab2" data-toggle="tab">CV Extracted Information</a></li>
												<li id="li3"><a href="#tab3" data-toggle="tab">CV Content</a></li>
											</ul>
											<div class="tab-content">
												<div class="tab-pane active" id="tab1">
													<div class="row-fluid">
                        								<div class="span12">
                        								<?php
                        									echo "<iframe src=\"preview_cv.php?GUID=".$GUID."\" width=\"98%\" style=\"height:500px;\"></iframe>";
                        								?>
                        								</div>
                        							</div>
												</div>
												<div class="tab-pane" id="tab2">
													
												</div>
												<div class="tab-pane" id="tab3">
													<div class="row-fluid">
                        								<div class="span12">
															<textarea name="CV_File_Content" id="CV_File_Content" class="input-xlarge span12" rows="20"><?php echo $CV_File_Content; ?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<?php } ?>
								<div class="span12">	
									<div class="control-group">
										<div style="text-align:center;padding-top: 50px;" class="">
											<button class="btn btn-gebo" type="submit" name="submit" id="submit">Submit</button>
										</div>	
									</div>
								
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
		    <script type="text/javascript">
var nextLink='', previousLink='';
$(document).keydown(function(e){
    if (e.keyCode == 37) { 
    	if(previousLink!=""){
    		window.location.href=previousLink;
    	}
    }if (e.keyCode == 39) { 
       if(nextLink!=""){
    		window.location.href=nextLink;
    	}
    }
});				$(document).ready(function(){
					fetch_nex_previous();
					drawExtractedInfo();
					
					//* regular validation
					gebo_validation.reg();
					
					$.validator.addMethod("isemail", 
                           function(value, element) {
                              var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  								return regex.test(value);
                           }, 
                           "Not a valid email"    ); 
				});
					
			
			//* validation
				gebo_validation = {
					
					reg: function() {
						reg_validator = $('#form1').validate({
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
								Name: { required: true },
								TelephoneMobile: { required: true },
								Email: { required: true, isemail:true },
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
function drawExtractedInfo(){
	var infoStr= '<?php echo $CV_Extracted_Information;?>', tableContent="";
	if(infoStr!=""){
		tableContent+="<table class='table table-striped table-bordered dataTable no-footer'>";
		tableContent+="<th>Key</th><th>Value</th>";
			
		var infoObj = JSON.parse(infoStr); 
		$.each(infoObj, function(index,row){
			tableContent+="<tr><td>"+index+"</td><td>"+row+"</td></tr>";
		});
		tableContent+="</table>";
	}	else {
		tableContent+="<div class='alert alert-danger'>Sorry, no information extracted from CV.</div>";
	}
	$("#tab2").html(tableContent);
}
function fetch_nex_previous(){
	var oSearch='', currentPage= '<?php echo $GUID; ?>';
	if(currentPage!=""){
		<?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications']) && isset($_SESSION['last_search']['jobapplications']['sSearch'])) { ?>
			oSearch= "<?php  echo $_SESSION['last_search']['jobapplications']['sSearch'];  ?>";
		<?php } ?>
		var iDisplayLength= <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications']) && isset($_SESSION['last_search']['jobapplications']['iDisplayLength'])) { echo $_SESSION['last_search']['jobapplications']['iDisplayLength']; } else { echo '25'; } ?>;
		var iDisplayStart= <?php if(isset($_SESSION['last_search']) && isset($_SESSION['last_search']['jobapplications']) && isset($_SESSION['last_search']['jobapplications']['iDisplayStart'])) { echo $_SESSION['last_search']['jobapplications']['iDisplayStart']; } else { echo '0'; } ?>;
								
		var jsonRow="lib/datatables/server_jobapps.php?repeatQuery=yes&sSearch="+oSearch+"&iDisplayStart="+iDisplayStart+"&iDisplayLength="+iDisplayLength;
		$.getJSON(jsonRow,function(response){
			if(response.aaData && response.aaData.length>0){
				var createUUIDArr= new Array();
				$.each(response.aaData, function(i,row){
					createUUIDArr.push(row[6]);
				});
				var totalArrCount=(createUUIDArr.length)-1;
				var foundPosInArr=createUUIDArr.indexOf(currentPage);
				if(foundPosInArr==-1){
					$("#showPrevNext").hide();
				} else{
					$("#showPrevNext").show();
					if(foundPosInArr==totalArrCount){
						previousLink="jobapp.php?GUID="+createUUIDArr[foundPosInArr-1];
						nextLink="";
						
						$('#prevBtn').attr("href", previousLink);
						$('#nextBtn').attr("disabled", "disabled");
					}else if(foundPosInArr==0){
						previousLink="";
						nextLink="jobapp.php?GUID="+createUUIDArr[foundPosInArr+1];
						
						$('#nextBtn').attr("href", nextLink);
						$('#prevBtn').attr("disabled", "disabled");
					}else if(foundPosInArr<totalArrCount){
						previousLink="jobapp.php?GUID="+createUUIDArr[foundPosInArr-1];
						nextLink="jobapp.php?GUID="+createUUIDArr[foundPosInArr+1];
						$('#prevBtn').attr("href", previousLink);
						$('#nextBtn').attr("href", nextLink);						
					}
				}				
			}
		});
	}
}
			</script>
            
		</div>
	</body>
</html>
