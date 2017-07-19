<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); 


if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from categories where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: category.php");
	}
}

// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$site_id=$_POST["site_id"];
	$Code=$_POST["Code"];
	$CategoryGroupID=$_POST["category_gropuID"];
	$Name=$_POST["name"];
	$Active=$_POST["status"];
	$UserID=$_POST["userID"];
	$Type=$_POST["reg_type"];
	$cat_docu=$_POST['cat_docu'];
	if(isset($_POST['access_level']) && $_POST['access_level']!=''){
			$access_level_num=$_POST['access_level'];
		}
		else
		{
			$access_level_num= 'Null';
		}
	$MetaKeywords=$_POST["MetaKeywords"];
	$MetaDescription=$_POST["MetaDescription"];

	$time= time();
	
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$user_guid= $db->get_var("select uuid from wi_users where ID='$UserID'");
	
	/*FTS*/
	$cat_group_name=$db->get_var("SELECT Name FROM `categorygroups` WHERE ID=".$CategoryGroupID."");
	$cat_group_id=$db->get_var("SELECT GUID FROM `categorygroups` WHERE ID=".$CategoryGroupID."");
	$user_name=$db->get_var("SELECT Name FROM `users` WHERE ID=".$UserID."");

	if($Active==1){ $status="Active"; } else { $status="Inactive"; }
	
	$fts=$Name.' '.$cat_group_name.' '.$Type.' '.$user_name.' '.$status.' '.$cat_group_id;
	/*FTS*/
	
	$Auto_Format=1;
		if(isset($_POST["Auto_Format"])) {
		$Auto_Format=0;
		}
		
	if(isset($_POST['category'])){
			$top_id=$_POST['category'];
		}
		else
		{
			$top_id=0;
		}
	if(isset($_POST['order_by']) && $_POST['order_by']!=''){
			$order_by=$_POST['order_by'];
		}
		else
		{
			$order_by=0;
		}
		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		//echo "UPDATE categories SET SiteID='".$site_id."', Modified='$time', Code='$Code', CategoryGroupID='$CategoryGroupID', Name='$Name', Active='$Active', UserID='$UserID',
//	Type='$Type',FTS='$fts',
//	Sync_Modified='$Sync_Modified', Auto_Format=$Auto_Format
//	WHERE GUID ='$guid'";
	
	if($db->query("UPDATE categories SET SiteID='".$site_id."', Modified='$time', Code='$Code', CategoryGroupID='$CategoryGroupID', Name='$Name', Active='$Active', UserID='$UserID',
	Type='$Type',FTS='$fts',TopLevelID=$top_id, Order_By_Num=$order_by, Cat_Doc_GUID='$cat_docu',
	Auto_Format=$Auto_Format, MetaKeywords='$MetaKeywords', MetaDescription = '$MetaDescription',access_level_num= $access_level_num, Site_GUID= '$site_guid', User_GUID= '$user_guid' WHERE GUID ='$guid'")) {
	$_SESSION['up_message'] = "Successfully updated";
	}
	 //$db->debug();
	}
	else
	{
		// echo "INSERT INTO categories (GUID,Created,Modified,SiteID,Name,Code,CategoryGroupID,Active,UserID,Type,FTS,Sync_Modified,Auto_Format,TopLevelID,Order_By_Num,MetaKeywords,MetaDescription,access_level_num) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$Code','$CategoryGroupID','$Active','$UserID','$Type','$fts','$Sync_Modified',$Auto_Format,$top_id,$order_by,'$MetaKeywords','$MetaDescription',$access_level_num)";
	$GUID=UniqueGuid('categories', 'GUID');
		
	 if($db->query("INSERT INTO categories (GUID,Created,Modified,SiteID,Name,Code,CategoryGroupID,Active,UserID,Type,FTS,Auto_Format,TopLevelID,Order_By_Num,MetaKeywords,MetaDescription,access_level_num, Site_GUID, User_GUID, Cat_Doc_GUID) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$Code','$CategoryGroupID','$Active','$UserID','$Type','$fts',$Auto_Format,$top_id,$order_by,'$MetaKeywords','$MetaDescription',$access_level_num, '$site_guid', '$user_guid', '$cat_docu')")) {
	
	$_SESSION['ins_message'] = "Successfully Inserted";
	header("Location:categories.php");
	}
	//$db->debug();
	
	}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$category = $db->get_row("SELECT * FROM categories where categories.GUID ='$guid'");

		$site_id=$category->SiteID;
		$Code=$category->Code;
		$CategoryGroupID=$category->CategoryGroupID;
		$Name=$category->Name;
		$Active=$category->Active;
		$UserID=$category->UserID;
		$Type=$category->Type;
		$access_level_num=$category->access_level_num;
		$Server_Number=$category->Server_Number;
		$FTS=$category->FTS;
		
		$Auto_Format=$category->Auto_Format;
		$MetaKeywords=$category->MetaKeywords;
		$MetaDescription=$category->MetaDescription;
		$category_id=$category->TopLevelID;
		$Order_By_Num=$category->Order_By_Num;
		$DocumentID=$category->Cat_Doc_GUID;
		$where_cond=" and SiteID ='".$site_id."' ";
		
// $db->debug();
}
else
{
	$guid='';
	$site_id='';
		$Code='';
		$CategoryGroupID='';
		$Name='';
		$Active=2;
		$UserID='';
		$Type='';
		$access_level_num='';
		$Server_Number='';
		$FTS='';
		
		$Auto_Format=1;
		$category_id='';
		$MetaKeywords='';
		$MetaDescription='';
		$Order_By_Num='';
		$DocumentID='';
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
			<a href="categories.php">Categories</a>
		</li>
		<li>
			<a href="#">Category</a>
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
											<label>Category Name <span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" class="textbox">

											<input type="text" name="name" class="span12" id="CategoryName" onKeyUp="generate_code('Auto_Format','CategoryName','Code')" onBlur="generate_code('Auto_Format','CategoryName','Code')" value="<?php echo $Name; ?>"/>
											
											
										</div>
										
										
										
										
										
										
										<div class="span4">
												<label>Code <span class="f_req">*</span></label>
					 							
													<input type="text" class="span12" name="Code" id="Code" <?php if($Auto_Format!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $Code;?>" />
													<span class="help-block">URL (SEO friendly)</span>
													<span class="help-block">
													<input type="checkbox" name="Auto_Format" id="Auto_Format" value="0" <?php if($Auto_Format==0) { ?> checked="checked" <?php } ?>  />
													I want to manually enter code</span>


												</div>
											</div>
										</div>
										
										
										
										<?php /*?><div class="span4">
											<label>Code <span class="f_req">*</span></label>
											<input type="text" class="span12" name="Code" id="Code" readonly="readonly" value="<?php echo $Code;?>" />
											<span class="help-block">URL (SEO friendly)</span>
										</div><?php */?>
									
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Category Group</label>
											
											<select onChange="" name="category_gropuID" id="cat_grp">
												<option value="0">-- Not specified --</option>
											<?php
												$op = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Active='1' $where_cond ORDER BY ID");
												foreach($op as $opt){
													$id=$opt->ID;
													$name=$opt->Name;
													?>
													<option <?php if($id == $CategoryGroupID) { echo "selected"; } ?> value="<?php echo $id; ?>"><?php echo $name; ?></option>	
													<?php
												}
											?>
											</select>
										</div>
									</div>
								</div>
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Top Level Category </label>
											
											<?php if(isset($_GET['GUID'])) { ?>
											<select onChange="" name="category" id="category" >
												<option value="0">-- Not specified --</option>
											<?php
											if($cat_list = $db->get_results("SELECT * FROM `categories` WHERE active='1' and CategoryGroupID = '".$CategoryGroupID."'  ORDER BY TopLevelID")) {
			foreach($cat_list as $curr_cat){
			
				$top_level=$db->get_var("select Name from `categories` where ID=".$curr_cat->TopLevelID." ");
				
				?>
				<option value='<?php echo $curr_cat->ID; ?>' <?php if($category_id==$curr_cat->ID) { ?>  selected="selected" <?php } ?> >
						<?php if($top_level){ echo $top_level.':  '; } echo $curr_cat->Name; ?>
				</option>
				<?php
			} }
											
											?>
											</select>
											
											<?php } else {?>
											
											
											<select onChange="" name="category" id="category" disabled="disabled">
												<option value="0">-- Not specified --</option>
											
											</select>
											<?php } ?>
											
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Order By </label>
											<input type="text" class="span12" name="order_by" id="order_by" value="<?php echo $Order_By_Num;?>" />
										</div>
										
									</div>
								</div>
								
								
								<div id="for_tp" <?php  if(!isset($_SESSION['site_id']) || ($_SESSION['site_id']!='29201')){ ?> style="display:none" <?php } ?> >
								
								
								
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>User Access Level </label>
											<select onChange="" name="access_level">
												<option value="">-- Select --</option>
												<option <?php if($access_level_num == '0') { echo "selected"; } ?> value="0">Public</option>
												<option <?php if($access_level_num == '1') { echo "selected"; } ?> value="1">Active</option>
												<option <?php if($access_level_num == '2') { echo "selected"; } ?> value="2">Basic</option>
												<option <?php if($access_level_num == '3') { echo "selected"; } ?> value="3">Full</option>
											</select>
										</div>
									</div>
								</div>
								
								
								
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Web page for this category </label>
											
											
											<select onChange="" name="cat_docu" id="cat_docu" >
												<option value=""></option>
											<?php
														if($doc = $db->get_row("SELECT GUID,ID,Document,Code FROM `documents` WHERE GUID='$DocumentID' ")){
														?>
														<option  value="<?php echo $doc->GUID; ?>" selected><?php echo $doc->Document." (".$doc->Code.")"; ?></option>	
														<?php
													}
													else {
													if($docs = $db->get_results("SELECT GUID,ID,Document,Code FROM `documents` WHERE 1 $where_cond ORDER BY `Document` limit 0,100 ")){
													foreach($docs as $doc) {
													?>
													<option  value="<?php echo $doc->GUID; ?>" ><?php echo $doc->Document." (".$doc->Code.")"; ?></option>
													<?php
													} } }
												?>
											</select>
											
											
											
										</div>
									</div>
								</div>
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label><span class="error_placement">Status </span> <span class="f_req">*</span></label>
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
											<label>User <span class="f_req">*</span></label>
											<select onChange="" name="userID" id="UserID">
												<option value="">-- Select User --</option>
											
											</select>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Type <span class="f_req">*</span></label>
											<select onChange="" name="reg_type">
												<option value="">-- Select --</option>
												<option <?php if($Type == 'page') { echo "selected"; } ?> value="page">page</option>
												<option <?php if($Type == 'product') { echo "selected"; } ?> value="product">product</option>
												<option <?php if($Type == 'job') { echo "selected"; } ?> value="job">job</option>
												<option <?php if($Type == 'blog') { echo "selected"; } ?> value="blog">blog</option>
												<option <?php if($Type == 'contact-option') { echo "selected"; } ?> value="contact-option">contact-option</option>
											</select>
										</div>
										<!--<div class="span4">
											<label>Server Number </label>
											<input type="text" name="server_no" class="span12" value="<?php //echo $Server_Number; ?>"/>
										</div>-->
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
			 
			 
          
			<script>
				$(document).ready(function() {
					//* regular validation
					gebo_validation.reg();
					
					$( "#cat_docu" ).combobox();
					
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
						if(site_id=='29201'){
							$('#for_tp').show();
						}
						else{
							$('#for_tp').hide();
						}
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
					 
					 $.ajax({
						type: "POST",
						url: "cat_grp_list.php",
						data: "site_id=" + site_id ,
						success: function(response){
						
							$("#cat_grp").html(response);
						}
					 });
					
				
			});
					
					$("#cat_grp").change(function(){
					var site_id=document.getElementsByName("site_id")[0].value;
					
					
					
						var cat_grp_id=$(this).val();
						
						$.ajax({
						type: "POST",
						url: "tp_cat_list.php",
						data: "cat_grp_id=" + cat_grp_id ,
						success: function(response){
							
							$("#category").html(response);
							$("#category").attr('disabled', false);
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
								category_gropuID: { required: true },
								
								status: { required: true },
								userID: { required: true },
								reg_type: { required: true },

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