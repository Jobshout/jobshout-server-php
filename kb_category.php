<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from kb_categories where uuid='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: kb_category.php");
	}
}


// to update selected catgory
if(isset($_POST['submit']))
{ 

	$site_id=$_POST["site_id"];
	$Code=$_POST["Code"];
	
	$Name=addslashes($_POST["name"]);
	$Active=$_POST["status"];
	$UserID=$_POST["userID"];
	
	
	$MetaKeywords=addslashes($_POST["MetaKeywords"]);
	$MetaDescription=addslashes($_POST["MetaDescription"]);

	$time= time();
	
	
	$Auto_Format=1;
		if(isset($_POST["Auto_Format"])) {
		$Auto_Format=0;
		}
		
		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		
	
	if($db->query("UPDATE kb_categories SET SiteID='".$site_id."', Modified='$time', code='$Code', name='$Name', status='$Active', user_id='$UserID',
	auto_format_code=$Auto_Format, MetaKeywords='$MetaKeywords', MetaDescription = '$MetaDescription'
	WHERE uuid ='$guid'")) {
	$_SESSION['up_message'] = "Successfully updated";
	}
	 //$db->debug();
	}
	else
	{
		
		$GUID=UniqueGuid('kb_categories', 'uuid');	
	 if($db->query("INSERT INTO kb_categories (uuid,Created,Modified,SiteID,name,code,status,user_id,auto_format_code,MetaKeywords,MetaDescription,Published_timestamp) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$Code','$Active','$UserID',$Auto_Format,'$MetaKeywords','$MetaDescription','$time')")) {
	
	$_SESSION['ins_message'] = "Successfully Inserted";
	header("Location:kb_categories.php");
	}
	//$db->debug();
	
	}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$category = $db->get_row("SELECT * FROM kb_categories where uuid ='$guid'");

		$site_id=$category->SiteID;
		$Code=$category->code;
		
		$Name=$category->name;
		$Active=$category->status;
		$UserID=$category->user_id;
		
		
		$Auto_Format=$category->auto_format_code;
		$MetaKeywords=$category->MetaKeywords;
		$MetaDescription=$category->MetaDescription;
		
		$where_cond=" and SiteID ='".$site_id."' ";
		
// $db->debug();
}
else
{
	$guid='';
	$site_id='';
		$Code='';
		
		$Name='';
		$Active=2;
		$UserID='';
		
		$Auto_Format=1;
		$MetaKeywords='';
		$MetaDescription='';
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
			<a href="kb_categories.php">KB Categories</a>
		</li>
		<li>
			<a href="#">KB Category</a>
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
											// $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
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
											<label>Category Name<span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; }  ?>" name="GUID" class="textbox">

											<input type="text" name="name" class="span12" id="CategoryName" onKeyUp="generate_code('Auto_Format','CategoryName','Code')" onBlur="generate_code('Auto_Format','CategoryName','Code')" value="<?php echo $Name; ?>"/>
											
											
										</div>
										
										
										
										
										
										
										<div class="span4">
												<label>Code<span class="f_req">*</span></label>
					 							
													<input type="text" class="span12" name="Code" id="Code" <?php if($Auto_Format!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $Code;?>" />
													<span class="help-block">URL (SEO friendly)</span>
													<span class="help-block">
													<input type="checkbox" name="Auto_Format" id="Auto_Format" value="0" <?php if($Auto_Format==0) { ?> checked="checked" <?php } ?>  />
													I want to manually enter code</span>


												</div>
											</div>
										</div>
										
										
										
										
								
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Status<span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($Active == 1 || $Active == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" <?php if($Active == 0) { echo ' checked'; } ?>/>
												Inactive
											</label>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
									  <div class="span8">
											<label>User<span class="f_req">*</span></label>
											<select onChange="" name="userID" id="UserID">
												<option value="">-- Select User --</option>
											
											</select>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Meta Description </label>
											<textarea cols="30" rows="5" name="MetaDescription" id="MetaDescription" class="span10"><?php echo $MetaDescription;?></textarea>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Meta Keywords </label>
											<textarea cols="30" rows="5" name="MetaKeywords" id="MetaKeywords" class="span10"><?php echo $MetaKeywords;?></textarea>
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
			 
            <!-- validation functions -->
			
			<script>
				$(document).ready(function() {
					//* regular validation
					gebo_validation.reg();
					
					$("#Auto_Format").click(function(){
						var status=$(this).attr("checked");
						if(status=="checked"){
							$('#Code').attr("readonly",false);
							$('#Code').val("");
						}
						else
						{
							$('#Code').attr("readonly",true);
							$('#Code').val("");
							generate_code('Auto_Format','CategoryName','Code');
						}
					
					});
					
					$('#Code').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<48 || k>57) && (k<65 || k>90) && (k<97 || k>122) && (k!=45) && (k!=95) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							alert("Allowed characters are A-Z, a-z, 0-9, _, -");
        					return false;
    					}
					
					});
					
					var site_id=$('[name="site_id"]').val();
					var usr_id='<?php echo $UserID; ?>';
					var	login_usr_id='<?php echo $user_details->ID; ?>';
					$.ajax({
					type: "POST",
					url: "user_list.php",
					data: {'site_id' : site_id, 'usr_id' : usr_id, 'login_usr_id' : login_usr_id},
					success: function(response){
						
						$("#UserID").html(response);
					}
					});
					
					$("#site_id_sel").change(function(){
						var site_id=$(this).val();
						var usr_id='<?php echo $UserID; ?>';
						var	login_usr_id='<?php echo $user_details->ID; ?>';
						$.ajax({
						type: "POST",
						url: "user_list.php",
						data: {'site_id' : site_id, 'usr_id' : usr_id, 'login_usr_id' : login_usr_id},
						success: function(response){
							
							$("#UserID").html(response);
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
								Code: { required: true },
								name: { required: true },
								status: { required: true },
								userID: { required: true },
								
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