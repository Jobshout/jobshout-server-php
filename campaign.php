 <?php 
require_once("include/lib.inc.php");;
require_once('include/main-header.php');

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$chk_num=$db->get_var("select count(*) as num from wi_campaigns where uuid='".$_GET['GUID']."'");
	if($chk_num==0){
		header("Location: campaign.php");
	}
}
				
				if(isset($_POST['submit']))
				{	

						
						$Name = addslashes($_POST["Name"]);
						
						$descr = addslashes($_POST["descr"]);
						$arr_sdate=explode('/', $_POST["sdate"]);
						$sdate=$arr_sdate[1].'/'.$arr_sdate[0].'/'.$arr_sdate[2];
						$sdate = strtotime($sdate);
						$arr_edate=explode('/', $_POST["edate"]);
						$edate=$arr_edate[1].'/'.$arr_edate[0].'/'.$arr_edate[2];
						$edate = strtotime($edate);
						$status = $_POST["status"];
						$time=time();
						$site_id=$_POST["site_id"];
						
					 if(isset($_GET['GUID']))
					 {
					
					 if($db->query("UPDATE wi_campaigns SET  campaign_name = '$Name', campaign_description='$descr', start_timestamp = '$sdate', end_timestamp = '$edate', Modified = '$time', SiteID=$site_id, status='$status' where  uuid = '".$_GET['GUID']."'")) {	
			
				 $_SESSION['up_message'] = "Updated successfully";
				 
				 }
				 //$db->debug();
	}
		else {
				//echo "INSERT INTO jobapplications (GUID, SiteID, Name, Email, TelephoneMobile, Comments, created, modified,SourceSite,Free_Text_Search,CV_File_Content,Rank,SourceType)  VALUES ('$GUID', '$site_id', '$Name', '$Email', '$TelephoneMobile', '$Comments', '$time', '$time', '', '', '', '', '')";
			$GUID=UniqueGuid('wi_campaigns', 'uuid');	
			if($db->query("INSERT INTO wi_campaigns (uuid, campaign_name, campaign_description, start_timestamp, end_timestamp, created, modified, status, SiteID)  VALUES ('$GUID', '$Name', '$descr', '$sdate', '$edate', '$time', '$time', '$status', $site_id)")) {
			
			$_SESSION['ins_message'] = "Inserted successfully ";
	 			header("Location:campaigns.php");
			
			}
			//$db->debug();
		}
	
}		

			
if(isset($_GET['GUID'])){

	 $user3 = $db->get_row("SELECT * FROM wi_campaigns where uuid = '".$_GET['GUID']."'");
	 //$db->debug();
	 
	 	$GUID=$user3->uuid;
		$site_id=$user3->SiteID;
		$Name=$user3->campaign_name;
		$descr=$user3->campaign_description;
		$sdate=date('d/m/Y',$user3->start_timestamp);
		$edate=date('d/m/Y',$user3->end_timestamp);
		$status=$user3->status;
		
	}
		else
		  {
		  
		   $GUID='';
		$site_id='';
		$Name='';
		$descr='';
		$sdate='';
		$edate='';
		$status=2;
		  
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
			<a href="campaigns.php">Campaigns</a>
		</li>
		<li>
			<a href="#">Campaign</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
					 
					
					<!--<div><h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add New"; } ?> Campaign</h3></div><br/>-->
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					</span></div><br>
 
 
							
                    <div class="row-fluid">
						<div class="span12">
							
							
							
							
							
									<form name="form1" id="form1" class="form-horizontal form_validation_reg" action="" enctype="multipart/form-data" method="post" >
										<fieldset>
										
										<?php
											// $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
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
													
													
												</div>
											</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="control-group formSep">
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
												<label for="u_signature" class="control-label">Description<span class="f_req">*</span></label>
												<div class="controls">
													<textarea rows="4" id="descr" name="descr" class="input-xlarge"><?php echo $descr; ?></textarea>
													<span>&nbsp;</span>
													<span class="help-block"></span>
												</div>
											</div>
											
											<div class="control-group formSep">
												<label for="u_signature" class="control-label">Start Date<span class="f_req">*</span></label>
												<div class="controls">
													<div class="input-append date" id="dp1" >
									<input class="input-small" placeholder="Start Date" type="text" readonly="readonly"  name="sdate" id="sdate" value="<?php echo $sdate; ?>" data-date-format="dd/mm/yyyy" /><span class="add-on"><i class="splashy-calendar_day"></i></span>
								</div>
													<span>&nbsp;</span>
													<span class="help-block"></span>
												</div>
											</div>
											
											<div class="control-group formSep">
												<label for="u_signature" class="control-label">End Date<span class="f_req">*</span></label>
												<div class="controls">
													<div class="input-append date" id="dp2" >
									<input class="input-small" placeholder="End Date" type="text" readonly="readonly"  name="edate" id="edate" value="<?php echo $edate; ?>" data-date-format="dd/mm/yyyy" /><span class="add-on"><i class="splashy-calendar_day"></i></span>
								</div>
													<span>&nbsp;</span>
													<span class="help-block"></span>
												</div>
											</div>
											
											<div class="control-group formSep">
												<label class="control-label">Status<span class="f_req">*</span></label>
											<div class="controls text_line">	
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($status == 1 || $status == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline"> 
												<input type="radio" value="0" name="status" <?php if($status == 0) { echo ' checked'; } ?>/>												Inactive
												
											</label>
										</div></div>
											
											
											
											
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
					gebo_datepicker.init();
					
					$('#dp1 i.splashy-calendar_day').click(function(){
						$('#sdate').datepicker( "show" );
					});
					$('#dp2 i.splashy-calendar_day').click(function(){
						$('#edate').datepicker( "show" );
					});
					
					$(document).click(function(event){
						//console.log($(event.target).closest('div').attr('id'));
						if($(event.target).closest('div').attr('id')!='dp1') {
							$('#sdate').datepicker( "hide" );
						}
						if($(event.target).closest('div').attr('id')!='dp2') {
							$('#edate').datepicker( "hide" );
						}
					});	
					
					
			});
			
			gebo_datepicker = {
				init: function() {
					//$('#sdate').datepicker({"autoclose": true});
					//$('#edate').datepicker({"autoclose": true});
					
					$('#sdate').datepicker({"autoclose": true}).on('changeDate', function(ev){
							var arrStartDate= $('#sdate').val().split('/');
							var dateText = new Date(arrStartDate[1]+'/'+arrStartDate[0]+'/'+arrStartDate[2]);
							console.log(dateText);
							var endDateTextBox = $('#edate');
							if (endDateTextBox.val() != '') {
								var testStartDate = new Date(dateText);
								var arrEndDate= endDateTextBox.val().split('/');
								var testEndDate = new Date(arrEndDate[1]+'/'+arrEndDate[0]+'/'+arrEndDate[2]);
								console.log(testEndDate);
								if (testStartDate > testEndDate) {
									endDateTextBox.val($('#sdate').val());
								}
							}
							else {
								endDateTextBox.val($('#sdate').val());
							};
							$('#edate').datepicker('setStartDate', dateText);
							
						});
						$('#edate').datepicker({"autoclose": true}).on('changeDate', function(ev){
							var arrEndDate= $('#edate').val().split('/');
							var dateText = new Date(arrEndDate[1]+'/'+arrEndDate[0]+'/'+arrEndDate[2]);
							console.log(dateText);
							var startDateTextBox = $('#sdate');
							if (startDateTextBox.val() != '') {
								var arrStartDate= startDateTextBox.val().split('/');
								var testStartDate = new Date(arrStartDate[1]+'/'+arrStartDate[0]+'/'+arrStartDate[2]);
								console.log(testStartDate);
								var testEndDate = new Date(dateText);
								if (testStartDate > testEndDate) {
									startDateTextBox.val($('#edate').val());
								}
							}
							else {
								startDateTextBox.val($('#edate').val());
							};
							$('#sdate').datepicker('setEndDate', dateText);
							
						});
					
				}
			};
					
			
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
								descr: { required: true },
								sdate: { required: true },
								edate: { required: true },
								status: { required: true },
								
								
								
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
