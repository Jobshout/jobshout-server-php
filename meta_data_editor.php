<?php 
require_once("include/lib.inc.php");

require_once('include/main-header.php');

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$sessionSiteGUIDs="";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$sitesQry=$db->get_results("Select GUID from sites where ID in ('".$_SESSION['site_id']."')");
		if(count($sitesQry)>0){
			foreach($sitesQry as $siteRow){
				if($sessionSiteGUIDs==""){
					$sessionSiteGUIDs="'".$siteRow->GUID."'";
				}else{
					$sessionSiteGUIDs=", '".$siteRow->GUID."'";
				}
			}
		}
	}
	
	$query_chk="select count(*) as num from site_options where guid='".$_GET['GUID']."'";
	if(isset($sessionSiteGUIDs) && $sessionSiteGUIDs!='') {
		$query_chk.=" and site_guid in (".$sessionSiteGUIDs.") ";
	}
	$chk_num=$db->get_var($query_chk);
	//$db->debug();exit;
	if($chk_num==0){
		header("Location: meta_data_list.php");
	}
} 

// to update selected catgory
if(isset($_POST['submit'])){
	
	$site_idNum=$_POST["site_id"];
	$site_guidStr =$db->get_var("Select GUID from sites where ID = '$site_idNum'");
	$select_pageStr=$_POST["select_page"];
	$statusNum=0;

	$contentTxt= array();
	$contentTxt['meta_title']=$_POST["meta_title"];
	$contentTxt['meta_tag_keywords']=$_POST["meta_tag_keywords"];
	$contentTxt['meta_tag_description']=$_POST["meta_tag_description"];
	$type="meta_data";
	
	$jsonContentTxt= addslashes(json_encode($contentTxt));
	if(isset($_POST['status'])){
		$statusNum=1;
	}
	$time=time();
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		if($db->query("UPDATE site_options SET site_guid='".$site_guidStr."', SITEID='$site_idNum', status='$statusNum', name='$select_pageStr', json_object_data='$jsonContentTxt', LastModified='$time', type='$type' WHERE guid ='$guid'")) {
			$_SESSION['up_message'] = "Successfully updated";
			//$db->debug();
		}
	}else	{
		$checkExisting = $db->get_var("SELECT count(*) FROM site_options where name ='$select_pageStr' and site_guid='".$site_guidStr."'");
		if($checkExisting == 0){
			$GUID = UniqueGuid('site_options', 'guid');	
	 		if($db->query("INSERT INTO site_options (guid, name, site_guid, status, json_object_data, SITEID, LastModified, type) VALUES ('$GUID', '$select_pageStr', '".$site_guidStr."', '$statusNum', '".$jsonContentTxt."', '$site_idNum', '$time', '$type')")) {
				//$db->debug();
				$_SESSION['ins_message'] = "Successfully Inserted";
				header("Location:meta_data_list.php");
			}
		}else{
				$error_msg= "Global meta data already already exists for the selected page!";
		}
	}
}

//to fetch category content
$site_id="";
$selected_page="";
$meta_title="";
$meta_tag_keywords="";
$meta_tag_description="";
$statusNum="";

if(isset($_GET['GUID'])) {
	$guid= $_GET['GUID'];
	$temp = $db->get_row("SELECT * FROM site_options where guid ='$guid'");
		$site_guidStr=$temp->site_guid;
		$site_id=$db->get_var("Select ID from sites where GUID in ('".$site_guidStr."')");
		$selected_page=$temp->name;
		$jsonContent=$temp->json_object_data;
		$contentArr=json_decode($jsonContent);
		if(count($contentArr)>0){
			foreach($contentArr as $key=>$value){
				if(isset($key) && $key=="meta_title"){
					$meta_title=$value;
				}
				if(isset($key) && $key=="meta_tag_keywords"){
					$meta_tag_keywords=$value;
				}
				if(isset($key) && $key=="meta_tag_description"){
					$meta_tag_description=$value;
				}
				
			}
		}
		$statusNum=$temp->status;
}

 ?>
 <style>
 .pageClass{
 	font-weight:bold;
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
			<a href="meta_data_list.php">Global Metadata Listing</a>
		</li>
		<li>
			<a href="javascript:void(0)">Global Metadata Editor</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
	</nav>
                   	<?php if(isset($error_msg) && $error_msg!=''){ ?>
					<div ><span style="color:#FF0000;font-size:18px">
					<?php echo $error_msg; ?>
					 </span></div><br>
					 <?php } ?>
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br/>
                    		<div class="row-fluid">
							<form class="form_validation_reg" method="post" action="">
                        	<div class="span12">
							<?php
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
											<label>Select Page<span class="f_req">*</span></label>
											<select onChange="" name="select_page" id="select_page">
												<option value="">-- Select Page --</option>
												<option value="index.php" <?php if($selected_page=="index.php") { echo "selected";	} ?>>Home (index.php)</option>
												<option value="register-a-cv.php" <?php if($selected_page=="register-a-cv.php") { echo "selected";	} ?>>Register A CV (register-a-cv.php)</option>
												<option value="register-a-vacancy.php" <?php if($selected_page=="register-a-vacancy.php") { echo "selected";	} ?>>Register A Vacancy (register-a-vacancy.php)</option>
												<option value="search-jobs.php" <?php if($selected_page=="search-jobs.php") { echo "selected";	} ?>>Search Jobs (search-jobs.php)</option>
												<option value="contact-us.php" <?php if($selected_page=="contact-us.php") { echo "selected";	} ?>>Contact Us (contact-us.php)</option>
												<!--<option value="our-divisions.php" <?php if($selected_page=="our-divisions.php") { echo "selected";	} ?>>Our Divisions (our-divisions.php)</option>
												<option value="blog.php" <?php if($selected_page=="blog.php") { echo "selected";	} ?>>Blog (blog.php)</option>
												<option value="cookies.php" <?php if($selected_page=="cookies.php") { echo "selected";	} ?>>Cookies (cookies.php)</option>
												<option value="privacy-policy.php" <?php if($selected_page=="privacy-policy.php") { echo "selected";	} ?>>Privacy Policy (privacy-policy.php)</option>
												<option value="sitemap.php" <?php if($selected_page=="sitemap.php") { echo "selected";	} ?>>Sitemap (sitemap.php)</option>-->
											</select>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Page Title<span class="f_req">*</span></label>
					 						<input type="text" class="span8" name="meta_title" id="meta_title" value="<?php echo $meta_title;?>" />
											<span class="help-block">Note: This will be displayed as page title for <span class="pageClass">web page</span></span>
										</div>
									</div>
								</div>	
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Meta Tags<span class="f_req">*</span></label>
					 						<textarea cols="30" rows="5" name="meta_tag_keywords" id="meta_tag_keywords" class="span8"><?php echo $meta_tag_keywords;?></textarea>
											<span class="help-block">Note: This will be used as meta tag keywords for <span class="pageClass">web page</span></span>
										</div>
									</div>
								</div>	
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Meta Description<span class="f_req">*</span></label>
					 						<textarea cols="30" rows="8" name="meta_tag_description" id="meta_tag_description" class="span8"><?php echo $meta_tag_description;?></textarea>
											<span class="help-block">Note: This will be used as meta description for <span class="pageClass">web page</span></span>
										</div>
									</div>
								</div>	
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label class="checkbox inline">
												<input type="checkbox" value="1" name="status" <?php if($statusNum == 1) { echo ' checked'; } ?>/>
												Active
											</label>
										</div>
									</div>
								</div>
								
								
								<div class="form-actions">
									<button class="btn btn-gebo" type="submit" name="submit">Save changes</button>
								</div>
							</div>
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
            <script>
				$(document).ready(function() {
					//* regular validation
					gebo_validation.reg();
					$("#select_page").change(function(){
						var selval=$(this).val();
						if(selval!=""){
							var seltxt=$(this).find("option:selected").text();
							$(".pageClass").html(seltxt);
						}else{
							$(".pageClass").html('web page');
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
								select_page: { required: true },
								meta_title: { required: true },
								meta_tag_keywords: { required: true },
								meta_tag_description: { required: true },
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