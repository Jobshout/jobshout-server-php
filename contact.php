<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');
include_once("../include/config_mailer.php");

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from contacts where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: contact.php");
	}
}

	// to update selected catgory
if(isset($_POST['submit']))
{ 

    $CODE=$_POST["Code"];
    $first_name=addslashes($_POST["First_name"]);
	$last_name=addslashes($_POST["last_name"]);
	$telephone=$_POST["tele_phn"];
	$fax=$_POST["fax_id"];
	$email=$_POST["email_id"];
	$password=$_POST["password"];
	$Name=$first_name.' '.$last_name;
	$jobtitle=addslashes($_POST["Job_Title"]);
	$status=$_POST["status"];
	$time= time();		
	$site_id=$_POST["site_id"];
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$arr_pub_date=explode('/', $_POST["Sync_Modified"]);
	$dateregistered=$arr_pub_date[1].'/'.$arr_pub_date[0].'/'.$arr_pub_date[2];
	$dateregistered=date("Y-m-d",strtotime($dateregistered));
	$pub_time=$_POST["pub_time"];
	if($pub_time==''){
		$pub_time=date('h:i A');
	}
	$timeregistered=date("H:i:s",strtotime($pub_time));
	
	
	$auto_format=1;
		if(isset($_POST["chk_manual"])) {
		$auto_format=0;
		}
    /*$time_arr=explode(" ",$_POST["time_picker"]);
	if($time_arr[1]=='PM')
	{
		$time_arr1=explode(":",$time_arr[0]);
		$hour=$time_arr1[0]+12;
		$timeregistered=$hour.":".$time_arr1[1].":00";
	}
	else
	{
		$timeregistered=$time_arr[0].":00";
	}	 */
		 $insert=true; 
		$update=true; 
		$update_pic=true;
		 
		 if(isset($_GET['GUID'])){
		 
		 //echo "UPDATE contacts SET  SiteID='$site_id',Modified = '$time',Code = '$CODE',  Firstname = '$first_name', 	Lastname='$last_name',Telephone='$telephone',Fax='$fax', 	Email='$email', 	Name='$Name',JobTitle='$jobtitle',zStatus='$status',DateRegistered='$dateregistered',auto_format_code=$auto_format where  GUID='".$_GET['guid']."'";
		 $old_access=$db->get_var("select zStatus from contacts where GUID='".$_GET['GUID']."'");
		 
		 
		$update = $db->query("UPDATE contacts SET  SiteID='$site_id',Site_GUID= '$site_guid',Modified = '$time',Code = '$CODE',  Firstname = '$first_name', 	Lastname='$last_name',Telephone='$telephone',Fax='$fax', 	Email='$email', zPassword='$password',	Name='$Name',JobTitle='$jobtitle',zStatus='$status',DateRegistered='$dateregistered', TimeRegistered='$timeregistered', auto_format_code=$auto_format where  GUID='".$_GET['GUID']."'");
		
		
		
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
									
									//echo "update contacts set contact_image='$content',contact_type='$fileType' where  	GUID='".$_GET['guid']."'";
									$update_pic = $db->query("update contacts set contact_image='$content',contact_type='$fileType' where  	GUID='".$_GET['GUID']."'");
									//$db->debug();
									
					
								}
		  if($site_id==29201){
			if($update && $update_pic){
	 			if($old_access!=$status){
				
				$db->query("delete from options where option_name='uuid_access_requested' and option_value='".$_GET['GUID']."'");
				
				$WM_Email = "nehak189@gmail.com";
				$WM_Name = "Webmaster";
							
			$mail->AddAddress($email);
			$mail->SetFrom($WM_Email, $WM_Name);
			$mail->Subject = "Access level : Technology Packaging";
			
			$message  = "<table border='0' style='text-align:left; width:470px; padding:5px;'>";
			$message .= "<tr><td colspan='2' style='text-align:left;'>Hi ".$first_name." ".$last_name.",\n\n</td></tr>";
			$message .= "<tr><td colspan='2'>&nbsp;</td></tr>";
			
			
			if($status=='ACTIVE'){
				$message .= "<tr><td colspan='2'>I would like to inform you are successfully registered with Technology Packaging.</td></tr>";
			
			}
			elseif($status=='BASIC'){
				$message .= "<tr><td colspan='2'>I would like to inform you that your request for further Access to Technology Packaging has been granted.</td></tr>";
			}
			elseif($status=='FULL'){
				$message .= "<tr><td colspan='2'>I would like to inform that Technology Packaging provides you the full access to higher level documents.</td></tr>";
			}
			elseif($status==0){
				$message .= "<tr><td colspan='2'>I would like to inform you that your account has been deactivated for Technology Packaging due to some technical issue. So please register again to use our services.</td></tr>";
			}
			
			
			$message .= "<tr><td colspan='2'>&nbsp;</td></tr>";
			$message .= "<tr><td colspan='2'>&nbsp;</td></tr>";
			$message .= "<tr><td colspan='2'>Thank you</td></tr>";
			$message .= "<tr><td colspan=\"2\">$WM_Name</td></tr></table>";
			
			$mail->MsgHTML($message);
			$mail->Send();
			$mail->ClearAddresses();
			
		
			}
		}
		}
	
	}
	
	
	  else
	{
	$GUID=UniqueGuid('contacts', 'GUID');			  
	$insert = $db->query("INSERT INTO contacts (GUID,SiteID,Site_GUID, Created,Modified,Code,Firstname,Lastname,contact_image,contact_type,Telephone,Fax,	Email,zPassword,Name,JobTitle,Email_Preferences,Rank,zStatus,DateRegistered, TimeRegistered, auto_format_code) VALUES ('$GUID','$site_id','$site_guid','$time','$time','$CODE' ,'$first_name','$last_name','','','$telephone','$fax','$email','$password','$Name','$jobtitle',0,'','$status','$dateregistered', '$timeregistered', $auto_format)");
	 
	 // $db->debug();
	 if($site_id==29201){
	 
	 $con_id = $db->insert_id;
	 $uuid=UniqueGuid('contactcategories', 'GUID');	
	 
	 $querysite=$db->get_row("select GUID from sites where ID='".$site_id."'");
	 
	 $queryCat = "SELECT ID, Code, GUID FROM categories WHERE Code='registered-users' AND (`SiteID`='".$site_id."' || `Site_GUID`='".$site_id."' )";
				
		$cats = $db->get_row($queryCat);
		
		if( !empty($cats) && count($cats) >0){
			
			$queryCat = "INSERT INTO `contactcategories` SET 
									 `Created`='$time',
									 `Modified` ='$time',
									 `SiteID`='".$site_id."',
									 `GUID` = '".$uuid."',
									 `Site_GUID`='".$querysite->GUID."',
									 `CategoryID`='".$cats->ID."',
									 `Category_GUID`='".$cats->GUID."',
									 `ContactID`='".$con_id."',
									 `Contact_GUID`='".$GUID."',
									 `Server_Number`='".SERVER_NUMBER."'";
			 $db->query($queryCat);
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
									
									
									$update_pic = $db->query("update contacts set contact_image='$content',contact_type='$fileType' where GUID='$GUID'");
									//$db->debug();
									
								
								
								}
	 
	
	
	//$db->debug();
}

if(!isset($_GET['GUID']) && $insert && $update_pic) {
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:contacts.php");
	 }
	 elseif(isset($_GET['GUID']) && $update && $update_pic) {
	 	 $_SESSION['up_message'] = "Updated successfully";
	 }

	}
 if(isset($_GET['GUID'])){
 
 
	          $user3 = $db->get_row("SELECT Modified, SiteID, Code,GUID ,Firstname,Lastname,contact_image,contact_type,Telephone,Fax,Email,zPassword,Name,JobTitle,zStatus,DateRegistered,TimeRegistered,auto_format_code  FROM contacts where  GUID = '".$_GET['GUID']."'");
			  
			   
	 $mime = $user3->contact_type;
	$cont_pic= $user3->contact_image;
	if($mime!='' && $cont_pic!='') {
$b64Src = "data:".$mime.";base64," . base64_encode($cont_pic);
}
else{
		$b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
		}
       
	    			
					$CODE=$user3->Code;
					$site_id=$user3->SiteID;
					$GUID=$user3->GUID;
					$Firstname=$user3->Firstname;
					$Lastname=$user3->Lastname;
					$contact_image=$user3->contact_image;
					$contact_type=$user3->contact_type;
					$Telephone=$user3->Telephone;
					$Fax=$user3->Fax;
					$Email=$user3->Email;
					$password=$user3->zPassword;
					$JobTitle=$user3->JobTitle;
					$status=$user3->zStatus;
					$DateRegistered=date("d/m/Y",strtotime($user3->DateRegistered));
					$time_string = date('h:i A',strtotime($user3->TimeRegistered));
					$auto_format=$user3->auto_format_code;
					$name=$user3->Name;
					/*$TimeRegistered=$user3->TimeRegistered;*/
	       
		   
		  }
		  
		  else
		  {
		  
		           $CODE='';
				   $site_id='';
				   $GUID='';
				   $Firstname='';
				   $Lastname='';
				   $contact_image;
				   $contact_type;
				   $Telephone='';
				   $Fax='';
				   $Email='';
				   $password='';
				   $JobTitle='';
				   $status='0';
				   $DateRegistered=date('d/m/Y');;
				   $time_string=date("h:i A");
				   $auto_format=1;
				   $b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
			  /* $TimeRegistered='';*/
			  
			   
				   $where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
		  }
		    
		   
 ?>
<script type="text/javascript">
function generateCode()
{
	var status=document.getElementById('chk_manual').checked;
	if(status!=true){
	var val=document.getElementById('email_id').value;
	
	document.getElementById('Code').value=val;
	}
}
</script>
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
			<a href="contacts.php">Contacts</a>
		</li>
		<li>
			<a href="#">Contact</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                    
                   
                       <h3 class="heading"><?php if(isset($_GET['GUID'])) { echo 'Update '.$name; } else { echo 'Add New contact'; } ?> </h3> 
                        
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br>
	 
							<div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Contact</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Contact Details</a></li>
									
									<li id="li3"><a href="#tab3" data-toggle="tab">Additional Information</a></li>
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
											
											
												
										<div class="control-group">
												<label class="control-label">Telephone</label>
												<div class="controls">
													
												
													<input type="text" name="tele_phn" class="span12" value="<?php echo $Telephone; ?>"  />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">First Name<span class="f_req">*</span></label>
												<div class="controls">
													<input type="hidden" value="<?php echo $GUID; ?>" name="GUID" id="GUID" >
												
													<input type="text" name="First_name" class="span12" id="CategoryName" value="<?php echo $Firstname; ?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Last Name<span class="f_req">*</span></label>
												<div class="controls">
												
													<input type="text" name="last_name" class="span12" value="<?php echo $Lastname; ?>" />
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											<br/>
												<div class="control-group ">
												<label for="fileinput" class="control-label">Contact Image</label>
												<div class="controls">
													<div data-fileupload="image" class="fileupload fileupload-new">
														<input type="hidden" />
														<div style="width: 80px; height: 80px;" class="fileupload-new thumbnail"><img src="<?php echo $b64Src; ?>" alt="" width="80" height="80" id="usr_img" style="background-color:#FFFFFF"/></div>
														<div style="width: 80px; height: 80px; line-height: 80px;" class="fileupload-preview fileupload-exists thumbnail"></div>
														<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="fileinput" name="fileinput" /></span>
														<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
													</div>	
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Fax</label>
												<div class="controls">
												
                                <input type="text" name="fax_id" class="span12" value="<?php echo $Fax; ?>" /> 
									</div>
									</div>
										
										</fieldset>
									</div>
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
												<label class="control-label">Email<span class="f_req">*</span></label>
												<div class="controls">
													
											<input type="text" name="email_id" id="email_id" class="span12" onKeyUp="generateCode()" onBlur="generateCode()"  value="<?php echo $Email; ?>" /> 
													<span class="help-block"></span>
												</div>
												</div>
												
												<div class="control-group">
												<label class="control-label">Password<span class="f_req">*</span></label>
												<div class="controls">
													
											<input type="password" name="password" id="password" class="span12" value="<?php echo $password; ?>" /> 
													<span class="help-block"></span>
												</div>
												</div>
												
												<div class="control-group">
												
												<label class="control-label">JobTitle</label>
												<div class="controls">
													<input type="text" name="Job_Title" class="span12"   value="<?php echo $JobTitle; ?>"  />
													<span class="help-block"></span>
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
												<label class="control-label">Access Level<span class="f_req">*</span> </label>
												<div class="controls">
													
													
											<label class="radio inline">
												<input type="radio" value="0" name="status" <?php if($status == 0) { echo ' checked'; } ?>/>
												Inactive
											</label>
											<label class="radio inline">
												<input type="radio" value="ACTIVE" name="status" <?php if($status == 'ACTIVE') { echo ' checked'; } ?>/>												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="BASIC" name="status" <?php if($status == 'BASIC') { echo ' checked'; } ?>/>												Basic
											</label>
											<label class="radio inline">
												<input type="radio" value="FULL" name="status" <?php if($status == 'FULL') { echo ' checked'; } ?>/>												Full
											</label>
												</div>
											</div><br/>
											
											
									<div class="control-group">
										
											<label class="control-label">Date<span class="f_req">*</span></label>
											<div class="controls">
											<div class="input-append date" id="dp2" >
												<input class="input-small" placeholder="DateTime" type="text" readonly="readonly"  name="Sync_Modified" id="Sync_Modified"  value="<?php echo $DateRegistered; ?>" data-date-format="dd/mm/yyyy" ><span class="add-on"><i class="splashy-calendar_day"></i></span>
											</div>
											
											<div>
									<span class="help-block">&nbsp;</span>
									<input type="text" class="span3" id="tp_2" name="pub_time" value="<?php echo $time_string; ?>" readonly="readonly" />
								<span class="help-block">&nbsp;</span>
								</div>
											
										</div>
									</div>
									
								<!-- Site dropdown starts here-->
								<?php include_once("sites_dropdown.php"); ?>
								<!-- Site dropdown ends here-->
										
										<div class="control-group">
												<label class="control-label">Code<span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" class="span5" name="Code" id="Code" <?php if($auto_format!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $CODE;?>" />
													<!--<span class="help-block">URL (SEO friendly)</span>-->
													<span class="help-block">
													<input type="checkbox" name="chk_manual" id="chk_manual" value="0" <?php if($auto_format==0) { ?> checked="checked" <?php } ?>  />
													I want to manually enter code</span>

												

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
            <!-- validation functions -->
			
			<script>
			var val_flag=0;
				$(document).ready(function() {
				$("#fileinput").change(function(){
					if (this.files && this.files[0]) {
            			var reader = new FileReader();

            			reader.onload = function (e) {
						$('#usr_img').attr('src', e.target.result);
           				 };

           				 reader.readAsDataURL(this.files[0]);
       				 }
					//$("#usr_img").attr("src",img);
					
					
				});
				
				
					//* regular validation
					gebo_validation.reg();
					
					$.validator.addMethod("isemail", 
                           function(value, element) {
                              var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  								return regex.test(value);
                           }, 
                           "Not a valid email"    ); 
						   
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
							generateCode();
						}
					
					});
					
					$('.splashy-calendar_day').click(function(){
						$('#Sync_Modified').datepicker( "show" );
					});
					
					$(document).click(function(event){
						//console.log($(event.target).closest('div').attr('id'));
						if($(event.target).closest('div').attr('id')!='dp2') {
							$('#Sync_Modified').datepicker( "hide" );
						}
					});	
					
				});
				//* bootstrap datepicker
				gebo_datepicker = {
					init: function() {
						
						$('#Sync_Modified').datepicker({"autoclose": true});
						
						/*$('#dp2').datepicker().on('changeDate', function(ev){
							$('#dp2').datepicker('hide');
						});*/
					}
				};
				//* bootstrap timepicker
				<!--gebo_timepicker = {
					<!--init: function() {
						/*$('#tp_2').timepicker({
							defaultTime: 'current',
							minuteStep: 1,
							disableFocus: true,
							template: 'dropdown'
						});
					}
				};-->*/
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
								//$(element).closest('div').addClass("f_error");
								val_flag=1;
								}
								
					else if(!$("#"+err_div_id).hasClass("active") && val_flag==0){
					//$(element).closest('div').addClass("f_error");
					for(var i=1; i<=3; i++) {
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
							    site_id:{ required: true },
								Code: { required: true },
								First_name: { required: true },
								last_name: { required: true },							
								email_id: { required: true, isemail:true },	
								password: { required: true },							
								status: { required: true },
								Sync_Modified: { required: true },
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