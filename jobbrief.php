 <?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from jobbriefs where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: jobbrief.php");
	}
}


				
				if(isset($_POST['submit']))
				{	

						$site_id = $_POST['site_id'];
						$Name = addslashes($_POST["Name"]);
						$Email = $_POST["Email"];
						$TelephoneMobile = $_POST["TelephoneMobile"];
						$Comments = addslashes($_POST["Comments"]);
						$time = time();
						$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
						$insert=true; 
						$update=true; 
						$update_file=true;
						
					 if(isset($_GET['GUID']))
					 {
					
					$update = $db->query("UPDATE jobbriefs SET  Name = '$Name', SiteID='$site_id',Site_GUID='$site_guid', Email = '$Email', Mobile = '$TelephoneMobile', Notes = '$Comments', Modified = '$time' where  GUID = '".$_GET['GUID']."'");	
					 
					 
					 
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
									$update_file = $db->query("update jobbriefs set JobBriefBLOB='$content', JobBriefType='$fileType', JobBriefFileName='$fileName' where GUID='".$_GET['GUID']."'");
									//$db->debug();
									
	}
	
						
				 
				 //$db->debug();
	}
		else {
				//echo "INSERT INTO jobbriefs (GUID, SiteID, Name, Email, Mobile, Notes, created, modified)  VALUES ('$GUID', '$site_id', '$Name', '$Email', '$TelephoneMobile', '$Comments', '$time', '$time')";
			$GUID=UniqueGuid('jobbriefs', 'GUID');		
			$insert = $db->query("INSERT INTO jobbriefs (GUID, SiteID,Site_GUID, Name, Email, Mobile, Notes, created, modified)  VALUES ('$GUID', '$site_id','$site_guid', '$Name', '$Email', '$TelephoneMobile', '$Comments', '$time', '$time')");
			
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
									
									//echo "update jobbriefs set JobBriefBLOB='$content', JobBriefType='$fileType', JobBriefFileName='$fileName' where GUID='$GUID'";
									$update_file = $db->query("update jobbriefs set JobBriefBLOB='$content', JobBriefType='$fileType', JobBriefFileName='$fileName' where GUID='$GUID'");
									//$db->debug();
									
	}
			
			
			//$db->debug();
		
	}
	
	if(!isset($_GET['GUID']) && $insert && $update_file) {
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:jobbriefs.php");
	 }
	 elseif(isset($_GET['GUID']) && $update && $update_file) {
	 	 $_SESSION['up_message'] = "Updated successfully";
	 }
}		

			
if(isset($_GET['GUID'])){

	 $user3 = $db->get_row("SELECT * FROM jobbriefs where GUID = '".$_REQUEST['GUID']."'");
	 //$db->debug();
	 
	 	$GUID=$user3->GUID;
		$site_id=$user3->SiteID;
		$Name=$user3->Name;
		$Email=$user3->Email;
		$Mobile=$user3->Mobile;
		$Notes=$user3->Notes;
		$JobBriefFileName=$user3->JobBriefFileName;
		
	}
		else
		  {
		  
		   $GUID='';
		   $site_id='';
		   $Name='';
		   $Email='';
		   $Mobile='';
		   $Notes='';
		   $JobBriefFileName='';
		  
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
			<a href="jobbriefs.php">Job Briefs</a>
		</li>
		<li>
			<a href="#">Job Brief</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
					 
					
					<!--<div><h3 class="heading"><?php if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add New"; } ?> Job Briefs</h3></div>-->
					<div><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					</span></div>
					<div><span style="color:#FF0000;font-size:18px"><?php if (isset($msg_user)) echo $msg_user; ?></span></div>
 
 <br/>
							
                    <div class="row-fluid">
						<div class="span12">
							
							
							
							
							
									<form name="form1" id="form1" class="form-horizontal form_validation_reg" action="" enctype="multipart/form-data" method="post" >
										<fieldset>
										
										
									
										
										<?php
											
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="control-group formSep">
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
										
										
										
										
											<div class="control-group formSep">
												<label class="control-label">Name<span class="f_req">*</span></label>
												<div class="controls text_line">
													<input type="hidden" value="<?php if($GUID!='') { echo $GUID; } ?>" name="GUID" id="GUID" >
													<input type="text"  name="Name" id="Name" class="input-xlarge" value="<?php echo $Name; ?>">
													<span>&nbsp;</span>
												</div></div>
											
												
												
												<div class="control-group formSep">
												<label class="control-label">Email<span class="f_req">*</span></label>
												<div class="controls text_line">
													
													<input type="text"  name="Email" id="Email" class="input-xlarge" value="<?php echo $Email; ?>">
													<span>&nbsp;</span>
												</div></div>
												
												<div class="control-group formSep">
												<label class="control-label">Telephone/Mobile<span class="f_req">*</span></label>
												<div class="controls text_line">
													
													<input type="text"  name="TelephoneMobile" id="TelephoneMobile" class="input-xlarge" value="<?php echo $Mobile; ?>">
													<span>&nbsp;</span>
												</div></div>
												
												<?php if($JobBriefFileName!='') { ?>
									<div class="control-group formSep">
												<label class="control-label">View current job description</label>
												<div class="controls text_line">
												
												<a target="_blank" href="download_jobfile.php?GUID=<?php echo $_GET['GUID']; ?>" title="Download jobfile" ><i class="splashy-document_letter"></i><?php echo $JobBriefFileName; ?></a>
												</div></div>
												<?php } ?>
												
													
												<div class="control-group formSep">
												<label class="control-label">
												<?php if(isset($_GET['GUID'])) { ?>
												Please upload your job description here




												<?php } else { ?>
												Please upload your job description here



<span class="f_req">*</span>
												<?php } ?>
												</label>
												<div class="controls text_line">
												<input type="file" id="fileinput" name="fileinput"  /><span>&nbsp;</span>
												
									</div></div>
									
									
											
											
												<div class="control-group formSep">
												<label for="u_signature" class="control-label">Comments</label>
												<div class="controls">
													<textarea rows="4" id="Comments" name="Comments" class="input-xlarge"><?php echo $Notes; ?></textarea>
													<span>&nbsp;</span>
													<span class="help-block">Automatic resize</span>
												</div>
											</div>
											
											
											
											
									<div class="control-group">
												<div class="controls">
													<button class="btn btn-gebo" type="submit" name="submit" id="submit">Submit</button>
												</div>	
												</div>
											
										
										</fieldset>
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
		             
			  <script type="text/javascript">
			$(document).ready(function(){
				
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
			</script>
            
		</div>
	</body>
</html>
