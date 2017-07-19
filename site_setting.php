<?php 
require_once("include/lib.inc.php");

if(isset($_SESSION['site_id']) && $_SESSION['site_id']!=''){
	$site_id=$_SESSION['site_id'];
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$selected_site_id=" and site_guid='$site_guid'";
}else{
	$site_id='';
	$selected_site_id='and 1';
}

if(isset($_POST['save_site_settings'])){

	$site_id=$_POST["site_id"];
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$guid=$_POST["guid"];
	
	$site_host=$_POST["site_host"];
	$site_username=$_POST["site_username"];
	$site_password=$_POST["site_password"];
	$site_port=$_POST["site_port"];
	$site_smtp_status=$_POST["site_smtp_status"];
	$site_SMTPSecure=$_POST["SMTPSecure"];
	$site_isSMTP=$_POST["isSMTP"];
	$site_SMTPAuth=$_POST["SMTPAuth"];
	
	
	$site_stmp_data = array();
	$site_stmp_data['Host']=$site_host;
	$site_stmp_data['Username']=$site_username;
	$site_stmp_data['Password']=$site_password;
	$site_stmp_data['Port']=$site_port;
	$site_stmp_data['SMTPSecure']=$site_SMTPSecure;
	$site_stmp_data['isSMTP']=$site_isSMTP;
	$site_stmp_data['SMTPAuth']=$site_SMTPAuth;
	$time= time();
	$type="smtp";
	$_stmp_data = json_encode($site_stmp_data);
	
	if($guid=="" &&  ($chk=$db->get_row("select * from site_options where name='global_site_smtp_settings' and site_guid='".$site_guid."'"))){
			$_SESSION['err_message'] ="These settings already exists for the selected site";
	}else{
		if($get_smtp_guid = $db->get_var("select guid from site_options where name='global_site_smtp_settings' and site_guid= '".$site_guid."' ")){
			$update = $db->query("UPDATE site_options SET json_object_data='$_stmp_data', status='$site_smtp_status', LastModified='$time', type='$type' WHERE GUID ='$get_smtp_guid'");
			$_SESSION['up_message'] = "Updated successfully";

		}else{
		
			$GUID = UniqueGuid('site_options', 'GUID');
			$insert = $db->query("INSERT INTO site_options (guid, SiteID, site_guid, name, json_object_data, status, LastModified, type) VALUES('".$GUID."','$site_id', '$site_guid',  'global_site_smtp_settings', '$_stmp_data', '$site_smtp_status','$time', '$type')");
			$_SESSION['ins_message'] = "Inserted successfully";
			header("Location:site_settings.php");
			
		}
	}
}

if(isset($_GET['GUID'])) {
$smtp_system_details = $db->get_row("SELECT * FROM site_options where guid='".$_GET['GUID']."'");
}else{
//$smtp_system_details = $db->get_row("SELECT * FROM site_options where name='global_site_smtp_settings' $selected_site_id");
}
if(isset($smtp_system_details)){
	//$db->debug();
	$guid=$smtp_system_details->guid;
	$site_guid=$smtp_system_details->site_guid;
	if($site_id=='' && $site_guid!=''){
	$site_id= $db->get_var("select ID from sites where GUID='$site_guid'");
	}
	$site_smtp_status=$smtp_system_details->status;
	$site_json_object_data=json_decode($smtp_system_details->json_object_data, true);
	$site_smtp_host= $site_json_object_data['Host'];
	$site_smtp_username= $site_json_object_data['Username'];
	$site_smtp_password= $site_json_object_data['Password'];
	$site_smtp_port= $site_json_object_data['Port'];
	$site_isSMTP= $site_json_object_data['isSMTP'];
	$SMTPSecure= $site_json_object_data['SMTPSecure'];
	$SMTPAuth= $site_json_object_data['SMTPAuth'];
}else{
	$guid='';
	$site_smtp_host= '';
	$site_guid= '';
	$site_id='';
	$site_smtp_username= '';
	$site_smtp_password= '';
	$site_smtp_port= '';
	$site_smtp_status= '';
	$site_isSMTP='';
	$SMTPSecure='';
	$SMTPAuth='';
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
								<li><a href="home.php"><i class="icon-home"></i></a></li>
								<li>SMTP Settings</li>
								<?php include_once("include/curr_selection.php"); ?>
							</ul>
						</div>
                    </nav>
					<?php if(isset($error_msg) && $error_msg!=''){ ?>
					<div id="validation" ><span style="color:#FF0000;font-size:18px">
					<?php echo implode("<br>",$error_msg); ?>
					 </span></div><br>
					 <?php } ?>
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					<?php if(isset($_SESSION['err_message']) && $_SESSION['err_message']!=''){ echo $_SESSION['err_message']; unset($_SESSION['err_message']); }?>
					
					 </span></div><br>
					
                   
									
										 <div class="row-fluid">
											<div class="span12">
												<div class="row-fluid">
												<form action="" method="post" class="form_validation_reg" enctype="multipart/form-data">
													<div class="span9">
														<div class="form-horizontal well">
															<fieldset>
																<!-- Site dropdown starts here-->
																<?php include_once("sites_dropdown.php"); ?>
																<!-- Site dropdown ends here-->
																
																<div class="control-group">
																	<label class="control-label">SMTP Host <span class="f_req">*</span></label>
																	<div class="controls">
																		<input type="text" class="span7" name="site_host" id="site_host" value="<?php echo $site_smtp_host;?>" />
																		<input type="hidden" name="guid" id="guid" value="<?php echo $guid;?>" />
																		
																		<span class="help-block">&nbsp;</span>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">IS SMTP <span class="f_req">*</span></label>
																	<div class="controls">	
																		<label class="radio inline">
																			<input type="radio" value="true" name="isSMTP" <?php if($site_isSMTP == true) { echo ' checked'; } ?>/> Active
																		</label>
																		<label class="radio inline"> 
																			<input type="radio" value="false" name="isSMTP" <?php if($site_isSMTP == false) { echo ' checked'; } ?>/> Inactive
																		</label>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">SMTP Auth <span class="f_req">*</span></label>
																	<div class="controls">	
																		<label class="radio inline">
																			<input type="radio" value="true" name="SMTPAuth" <?php if($SMTPAuth == true) { echo ' checked'; } ?>/> Active
																		</label>
																		<label class="radio inline"> 
																			<input type="radio" value="false" name="SMTPAuth" <?php if($SMTPAuth == false) { echo ' checked'; } ?>/> Inactive
																		</label>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">SMTP Username <span class="f_req">*</span></label>
																	<div class="controls">
																		<input type="text" class="span7" name="site_username" id="site_username" value="<?php echo $site_smtp_username;?>" />
																		<span class="help-block">&nbsp;</span>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">SMTP Password <span class="f_req">*</span></label>
																	<div class="controls">
																		<input type="text" class="span7" name="site_password" id="site_password" value="<?php echo $site_smtp_password;?>" />
																		<span class="help-block">&nbsp;</span>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">SMTP Port</label>
																	<div class="controls">
																		<input type="text" class="span7" name="site_port" id="site_port" value="<?php echo $site_smtp_port;?>" />
																		<span class="help-block">&nbsp;</span>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">SMTP Secure</label>
																	<div class="controls">
																		<select name="SMTPSecure" id="SMTPSecure">
																			<option value="">--Select Option--</option>
																			<option value="ssl" <?php if($SMTPSecure == "ssl") { echo ' checked'; } ?>>SSL</option>
																			<option value="tls" <?php if($SMTPSecure == "tls") { echo ' checked'; } ?>>TLS</option>
																		</select>
																		<span class="help-block">&nbsp;</span>
																	</div>
																</div>
																<div class="control-group">
																	<label class="control-label">Status <span class="f_req">*</span></label>
																	<div class="controls">	
																		<label class="radio inline">
																			<input type="radio" value="1" name="site_smtp_status" <?php if($site_smtp_status == 1) { echo ' checked'; } ?>/> Active
																		</label>
																		<label class="radio inline"> 
																			<input type="radio" value="0" name="site_smtp_status" <?php if($site_smtp_status == 0) { echo ' checked'; } ?>/> Inactive
																		</label>
																	</div>
																</div>
																
															</fieldset>
														</div>
														<input class="btn btn-gebo" type="submit" name="save_site_settings" id="submit" value="Save changes">
													</div>
													
													</form>
												</div>

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
			<script>
			$(document).ready(function() {
			gebo_validation.reg();
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
					var err_div_id=$(element).closest('div.tab-pane').attr('id');
								
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
					site_host: { required: true },
					SMTPAuth: { required: true },
					site_username: { required: true },
					site_password: { required: true },
					site_smtp_status: { required: true }
				},
                invalidHandler: function(form, validator) {
					$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
				}
            })
        }
	};
			</script>
	</body>
</html>