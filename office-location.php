<?php 
require_once("include/lib.inc.php");


if(isset($_GET['id']) && $_GET['id']!=''){
	$chk_num=$db->get_var("select count(*) as num from locations_live where ID='".$_GET['id']."'");
	if($chk_num==0){
		header("Location: office_locations.php");
	}
}

// to update selected catgory
if(isset($_POST['submit']))
{ 
	$site_id=$_POST["site_id"];
	$name=$_POST["name"];
	$type=$_POST['type'];
	if(isset($_POST['type'])) {
		$type_vals=$_POST['type'];
		$typeValArr=array();
		foreach($type_vals as $type_val){
			if($type_val!=''){
				$typeValArr[]=$type_val;
			}
		}
		if(count($typeValArr)>0){
			$type=json_encode($typeValArr);
		}
	}
	$status=$_POST["status"];
	$timestamp=time();
	if(isset($_GET['id'])) {
		$ID= $_GET['id'];
		$sql= $db->get_var("SELECT count(*) FROM locations_live WHERE Name='".addslashes($name)."' and ID <>'$ID' and  SiteID='$site_id'");
		//$db->debug();
		if($sql>0){
			$err_msg = "This office location already exists";
		}else {
			if($db->query("UPDATE locations_live SET Name='".addslashes($name)."', Type='$type', Active='$status', Modified='$timestamp' WHERE ID ='$ID' and SiteID='$site_id'")) {
				$_SESSION['up_message'] = "Successfully updated";
			}
	 	}
	}	else	{
		$sql= $db->get_var("SELECT count(*) FROM locations_live WHERE Name='".addslashes($name)."' and  SiteID='$site_id'");
		//$db->debug();
		if($sql>0){
			$err_msg = "This location already exists";
		}else {
			if($db->query("INSERT INTO locations_live (Created, Modified, Code, Name, Type, Active, SiteID) VALUES ($timestamp, $timestamp, '".addslashes($name)."', '".addslashes($name)."', '$type', '$status', '$site_id')")) {
				$_SESSION['ins_message'] = "Successfully Inserted";
				header("Location:office_locations.php");
			}
		}
	}
	//$db->debug();
	//exit;
}

//to fetch category content

function isJSON($string){
   return is_string($string) && is_array(json_decode($string, true)) ? true : false;
}

$typeJsonArr=array();
if(isset($_GET['id'])) {
	$id= $_GET['id'];
	$link = $db->get_row("SELECT * FROM locations_live where ID ='$id'");
		$name=$link->Name;
		if(isJson($link->Type)){
			$typeJsonArr=json_decode($link->Type);
		}else{
			$typeJsonArr[]=$link->Type;
		}
		$status=$link->Active;
		$site_id=$link->SiteID;
}else{
	$id='';
	$name='';
	$typeJsonArr='';
	$status='';
	$site_id='';
}
//print_r($typeJsonArr);
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
								<li><a href="office_locations.php">Office Locations</a></li>
								<li><a href="#">Office Location</a></li>
								<?php include_once("include/curr_selection.php"); ?>
							</ul>
						</div>
               		</nav>
                    
					<div id="validation" style="padding-left: 200px;color:#FF0000;font-size:18px"><?php if (isset($err_msg)) echo $err_msg; ?></div>
						<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
						</span></div><br/>
                    	<div class="row-fluid">
                        	<div class="span6">
								<form class="form_validation_reg" method="post" action="">
								<?php	if($user_access_level>=11 && !isset($_SESSION['site_id'])) {	?>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												
												
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
											<?php	} elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!=''){
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
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
											<label>Name <span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($id!='') { echo $id; } ?>" name="ID" class="textbox">
											<input type="text" name="name" class="span12" id="name" value="<?php echo $name; ?>"/>
										</div>
									</div>
								</div>

								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Linked To <span class="f_req">*</span></label>
											<select name="type[]" id="type" multiple>
												<option value="job" <?php if(in_array('job', $typeJsonArr)) { echo "selected"; } ?> >Jobs</option>
												<option value='office-location' <?php if(in_array('office-location', $typeJsonArr)) { echo "selected"; } ?> >Office Location</option>
												<option value='sitemap-location' <?php if(in_array('sitemap-location', $typeJsonArr)) { echo "selected"; } ?> >Sitemap Location</option>
											</select>	
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Status <span class="f_req">*</span></label>
											<select name="status" id="status" >
												<option value="1" <?php if($status==1) { ?>  selected="selected" <?php } ?> >Active</option>
												<option value='0' <?php if($status==0) { ?>  selected="selected" <?php } ?> >Inactive</option>
											</select>	
										</div>
									</div>
								</div>
								
								<div class="form-actions">
									<button class="btn btn-gebo" type="submit" name="submit">Save changes</button>
									<a href="office_locations.php" class="btn">Cancel</a>
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
			
			<script>
				$(document).ready(function() {
					//* regular validation
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
							},
							unhighlight: function(element) {
								$(element).closest('div').removeClass("f_error");
							},
							errorPlacement: function(error, element) {
								$(element).closest('div').append(error);
							},
							rules: {
								loc_name: { required: true },
								loc_type: { required: true },
								loc_country: { required: true },
								loc_lat: { required: true, number:true },
								loc_lng: { required: true, number:true },
								
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