<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from newsletter_subscribers where uuid='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: news_sub.php");
	}
}


// to update selected catgory
if(isset($_POST['submit']))
{ 
	$GUID=$_POST["GUID"];
	$site_id=$_POST["site_id"];
	
	$Name=addslashes($_POST["name"]);
	$email=$_POST["email"];
	$notes=addslashes($_POST["notes"]);
	$Active=$_POST["status"];

	$registered= date("Y-m-d H:i:s");
	$time= time();
	
	
	$Auto_Format=1;
		if(isset($_POST["Auto_Format"])) {
		$Auto_Format=0;
		}
		
		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
	if($chk_sql=$db->get_row("select * from newsletter_subscribers where email='$email' and uuid !='$guid' and SiteID='".$site_id."'")){
		$error_msg="Email already exists";
	}	
	else{
	if($db->query("UPDATE newsletter_subscribers SET SiteID='".$site_id."', name='$Name', email ='$email', notes='$notes', zStatus='$Active',
	registered_timestamp='$registered' WHERE uuid ='$guid'")) {
	$_SESSION['up_message'] = "Successfully updated";
	}
	
	}
	 //$db->debug();
	}
	else
	{
	if($chk_sql=$db->get_row("select * from newsletter_subscribers where email='$email' and SiteID='".$site_id."'")){
		$error_msg="Email already exists";
	}	
	else{	
		
	 if($db->query("INSERT INTO newsletter_subscribers (uuid, SiteID, name, email, notes, zStatus, registered_timestamp) VALUES ('$GUID', '".$site_id."', '$Name','$email','$notes','$Active','$registered')")) {
	
	$_SESSION['ins_message'] = "Successfully Inserted";
	header("Location:news_subs.php");
	}
	}
	//$db->debug();
	
	}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$category = $db->get_row("SELECT * FROM newsletter_subscribers where uuid ='$guid'");

		$site_id=$category->SiteID;

		$Name=$category->name;
		$email=$category->email;
		$notes=$category->notes;
		$Active=$category->zStatus;
		

		$where_cond=" and SiteID ='".$site_id."' ";
		
// $db->debug();
}
else
{
	$guid='';
	$site_id='';
		
		$Name='';
		$email='';
		$notes='';
		$Active='';

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
			<a href="news_subs.php">Newsletter Subscribers</a>
		</li>
		<li>
			<a href="#">Newsletter Subscriber</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
                    
					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category</h3>-->
					<?php if(isset($error_msg)) { ?>
					<div ><span style="color:#FF0000;font-size:18px">
					<?php echo $error_msg; ?>
					</span></div><br/>
					<?php } ?>
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
												<label>Site Name (code)<span class="f_req">*</span></label>
												
												
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
											<label>Name<span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; } else { $Guid = NewGuid();
									echo $Guid; } ?>" name="GUID" class="textbox">

											<input type="text" name="name" class="span12" id="name" value="<?php echo $Name; ?>"/>
	
										</div>
										
										
											</div>
										</div>
										
										
										
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Email<span class="f_req">*</span></label>
											
											<input type="text" name="email" class="span12" id="email" value="<?php echo $email; ?>"/>
												
										</div>
										
										
											</div>
										</div>		
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Notes </label>
											<textarea cols="30" rows="5" name="notes" id="notes" class="span10"><?php echo $notes;?></textarea>
										</div>
									</div>
								</div>
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Status<span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="ACTIVE" name="status" <?php if($Active == 'ACTIVE' || $Active == '') { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="INACTIVE" name="status" <?php if($Active == 'INACTIVE') { echo ' checked'; } ?>/>
												Inactive
											</label>
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