<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); 

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from web_enquiries where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: enquiry.php");
	}
}


// to update selected catgory
if(isset($_POST['submit']))
{ 
	$site_id=$_POST["site_id"];
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$Name=addslashes($_POST["name"]);
	$email=$_POST["email"];
	$phone=$_POST["phone"];
	$en_type=$_POST["en_type"];
	$notes=addslashes($_POST["notes"]);
	
	$time= time();
	$date_now=date("Y-m-d");
	$time_now=date("H:i:s");
	
		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		//echo "UPDATE categories SET SiteID='".$site_id."', Modified='$time', Code='$Code', CategoryGroupID='$CategoryGroupID', Name='$Name', Active='$Active', UserID='$UserID',
//	Type='$Type',FTS='$fts',
//	Sync_Modified='$Sync_Modified', Auto_Format=$Auto_Format
//	WHERE GUID ='$guid'";
	
	if($db->query("UPDATE web_enquiries SET SiteID='".$site_id."',Site_GUID= '$site_guid', Modified='$time', Code='$email', Email='$email', Name='$Name', Telephone='$phone' , enquiry_type='$en_type', Notes='$notes'
	WHERE GUID ='$guid'")) {
	$_SESSION['up_message'] = "Successfully updated";
	}
	 //$db->debug();
	}
	else
	{
		// echo "INSERT INTO categories (GUID,Created,Modified,SiteID,Name,Code,CategoryGroupID,Active,UserID,Type,FTS,Sync_Modified,Auto_Format,TopLevelID,Order_By_Num,MetaKeywords,MetaDescription,access_level_num) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$Code','$CategoryGroupID','$Active','$UserID','$Type','$fts','$Sync_Modified',$Auto_Format,$top_id,$order_by,'$MetaKeywords','$MetaDescription',$access_level_num)";
		$GUID=UniqueGuid('web_enquiries', 'GUID');
	 if($db->query("INSERT INTO `web_enquiries` (
						`SiteID`,`Site_GUID`, `Created`,`Modified`,`Code`,`Title`,`Firstname`,`Middlename`,`Lastname`,
						`Telephone`,`Fax`,`Email`,`zStatus`,`zPassword`,
						`DateRegistered`,`TimeRegistered`,`Name`,`Telephone_daytime`,
						`Mobile`,`JobTitle`,`GUID`,`Notes`,`Email_Preferences`,
						`Rank`, `enquiry_type`
					)
					VALUES (
						'".$site_id."','".$site_guid."', '$time', '$time', '$email', '', '', '', 
						'', '$phone', '', '$email', 'ACTIVE', '', 
						 '$date_now', '$time_now', '$Name', 
						'', '', '', '".$GUID."', '".$notes."', '0', '', '$en_type'
					)")) {
	
	$_SESSION['ins_message'] = "Successfully Inserted";
	header("Location:enquiries.php");
	}
	//$db->debug();
	
	}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];

$db->query("UPDATE web_enquiries SET zStatus='INACTIVE'	WHERE GUID ='$guid'");

$category = $db->get_row("SELECT * FROM web_enquiries where GUID ='$guid'");

		$site_id=$category->SiteID;

		$Name=$category->Name;
		$email=$category->Email;
		$phone=$category->Telephone;
		$en_type=$category->enquiry_type;
		$notes=$category->Notes;

		$where_cond=" and SiteID ='".$site_id."' ";
		
// $db->debug();
}
else
{
	$guid='';
	$site_id='';
	
		$Name='';
		$email='';
		$phone='';
		$en_type='';
		$notes='';
		
		$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}

} ?>

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
			<a href="enquiries.php">Enquiries</a>
		</li>
		<li>
			<a href="#">Enquiry</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
                    
					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category</h3>-->
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br/>
                    <div class="row-fluid">
                        
                        <div class="span6">
							
							<form class="form_validation_reg" method="post" action="">
							
							<?php
											
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
												<label >Site Name(code)<span class="f_req">*</span></label>
												
												
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
												<label >Site Name(code)<span class="f_req">*</span></label>
												
												
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
										<div class="span10">
											<label>Name<span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" class="textbox">

											<input type="text" name="name" class="span12" id="name" value="<?php echo $Name; ?>"/>
											
											
										</div>

											</div>
										</div>
										
									<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label>Email<span class="f_req">*</span></label>
											
											<input type="text" name="email" class="span12" id="email" value="<?php echo $email; ?>"/>
																						
										</div>

											</div>
										</div>
										
										<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label>Telephone </label>

											<input type="text" name="phone" class="span12" id="phone" value="<?php echo $phone; ?>"/>
											
											
										</div>

											</div>
										</div>	
										
								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label>Enquiry Type<span class="f_req">*</span> </label>

											<input type="text" name="en_type" class="span12" id="en_type" value="<?php echo $en_type; ?>"/>
											
											
										</div>

											</div>
										</div>			
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label>Notes </label>
											<textarea cols="30" rows="5" name="notes" id="notes" class="span10"><?php echo $notes;?></textarea>
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

				});
				//* bootstrap datepicker
				gebo_datepicker = {
					init: function() {
						$('#dp2').datepicker();
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
							},
							unhighlight: function(element) {
								$(element).closest('div').removeClass("f_error");
							},
							errorPlacement: function(error, element) {
								$(element).closest('div').append(error);
							},
							rules: {
								site_id: { required: true },
								name: { required: true },
								email: { required: true, isemail:true },
								en_type: { required: true },

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