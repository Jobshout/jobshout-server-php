<?php 

require_once("include/lib.inc.php");
require_once('include/main-header.php'); 

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from pictures where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: picture.php");
	}
}



// to update selected catgory
if(isset($_POST['submit']))
{ 
	$site_id=$_POST["site_id"];
	$CODE=$_POST["code"];
	$document='';
	if(isset($_POST["docu"])) {
	$document=$_POST["docu"];
	}
	if($document==''){ $document=0; }
	
	$Active=$_POST["status"];
	
	$time= time();
	
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	
	
	
	if(isset($_POST['order_by']) && $_POST['order_by']!=''){
			$order_by=$_POST['order_by'];
		}
		else
		{
			$order_by=0;
		}
	
	
	$insert=true; 
	$update=true; 
	$insert_cat=true;
	$update_pic=true;
	
	if(isset($_GET['GUID'])) {
		$GUID= $_GET['GUID'];
	
	/*if(($CODE=='temp-image' || $CODE=='main-content') && $document!='' && $check_code=$db->get_row("select * from pictures where DocumentID='$document' and Code='$CODE' and SiteID='".$site_id."' and GUID<>'$GUID'")){
				$error_msg="This picture code already exists for the selected document";
			}
	else{*/
	//echo "update categorygroups set SiteID='".$site_id."', Modified='$time', Name='$Name',Code='$CODE',Active='$Active',UserID='$user_id',Sync_Modified='$Sync_Modified',Type='$Type', Auto_Format=$Auto_Format where GUID='$guid'";
	
	 $update=$db->query("update pictures set SiteID='".$site_id."', Modified='$time',Code='$CODE',Status='$Active',DocumentID='$document',Order_By=$order_by, Site_GUID= '$site_guid' where GUID='$GUID'");
	/* }*/
	 //$db->debug();
	}
	else {
		$GUID=UniqueGuid('pictures', 'GUID');
			/*if(($CODE=='temp-image' || $CODE=='main-content') && $document!='' && $check_code=$db->get_row("select * from pictures where DocumentID='$document' and Code='$CODE' and SiteID='".$site_id."'")){
				$error_msg="This picture code already exists for the selected document";
			}
			else{*/
	
			//echo "INSERT INTO categorygroups (GUID,Created,Modified,SiteID,Name,Code,Active,UserID,Sync_Modified,Type,Auto_Format) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$CODE','$Active','$user_id','$Sync_Modified','$Type',$Auto_Format)";
			
		 $insert=$db->query("INSERT INTO pictures (GUID,Created,Modified,SiteID,Name,Code,Status,Type,DocumentID,Picture,Order_By,Site_GUID) VALUES ('$GUID','$time','$time','".$site_id."','','$CODE','$Active','','$document','',$order_by,'$site_guid')");
		 /*}*/
		 //$db->debug();
	}
	
	if($insert || $update) {
	
	$db->query("delete FROM picturecategories WHERE Picture_GUID='$GUID'");
	
	if(isset($_POST["cat"]))
	{
		$category=$_POST["cat"];
		if(is_array($category)){
		foreach($category as $cat){
		 $cat_uuid= $db->get_var("SELECT GUID FROM `categories` WHERE SiteID='".$site_id."' AND ID='".$cat."' ");
			 
				// $db->query("update pictures set Category_GUID='$categories->GUID' where GUID='".$GUID."'");
				$Guid= UniqueGuid('picturecategories', 'GUID');
				$insert_cat = $db->query("INSERT INTO picturecategories (GUID,Picture_GUID,Category_GUID) VALUES ('$Guid','$GUID','$cat_uuid')");
				 //$db->debug();
			 }
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
		
		
		$update_pic=$db->query("update pictures set Name='$fileName', Picture='$content', Type='$fileType' where GUID='".$GUID."'");
		//$db->debug();
		
	} 
	
	}
	
	if(!isset($_GET['GUID']) && $insert && $insert_cat && $update_pic) {
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:pictures.php");
	 }
	 elseif(isset($_GET['GUID']) && $update && $insert_cat && $update_pic) {
	 	 $_SESSION['up_message'] = "Updated successfully";
	 }

}

if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
$pict = $db->get_row("SELECT * FROM pictures where GUID ='$guid'");

		$site_id=$pict->SiteID;
		$Code=$pict->Code;
		$Name=$pict->Name;			
		$Active=$pict->Status;
		$doc_id=$pict->DocumentID;		
		$Order_By_Num=$pict->Order_By;
		$mime = $pict->Type;
		$pictre= $pict->Picture;
		if($mime!='' && $pictre!='') {
		$b64Src = "data:".$mime.";base64," . base64_encode($pictre);
		}
		else{
		$b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
		}
				
		$where_cond=" and SiteID ='".$site_id."' ";
		
		$dc = $db->get_results("SELECT Category_GUID FROM picturecategories WHERE Picture_GUID='$guid'");
$cat_ids=array();
//$db->debug();
if($dc != ''){
 foreach($dc as $dc1){
 	$category= $db->get_var("SELECT ID FROM `categories` WHERE SiteID='".$site_id."' AND GUID='".$dc1->Category_GUID."' ");
	$cat_ids[]= $category;
	
}
}
		
	}
	else
	{
		$guid='';
		$site_id='';
		$Code='';
		$Name='';				
		$Active=2;
		$doc_id='';
		$Order_By_Num='';
		$mime = '';
		$pictre= '';		
		$b64Src = "http://www.placehold.it/80x80/EFEFEF/AAAAAA";
		$cat_ids=array();
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
			<a href="pictures.php">Pictures</a>
		</li>
		<li>
			<a href="#">Picture</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                    
					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category Group</h3>-->
						<div id="validation" style="padding-left: 200px;color:#FF0000;font-size:18px"><?php if (isset($error_msg)) echo $error_msg; ?></div>
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br/>
                    <div class="row-fluid">
                        
                        <div class="span6">
							
							<form class="form_validation_reg" method="post" action="" enctype="multipart/form-data">
								
								<?php
											//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
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
											<label>Code <span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" class="textbox">
											<input type="text" name="code" class="span12" id="code" value="<?php echo $Code; ?>"/>
											
											
											
											
											
																					</div>
										
									</div>
<div id="for_tp" <?php  if(!isset($_SESSION['site_id']) || ($_SESSION['site_id']!='29201')){ ?> style="display:none" <?php } ?> >
<ul>
<li>Enter <b>temp-image</b> for Banner Image</li>
<li>Enter <b>main-content</b> for Main Image</li>
<li>Enter <b>image-box</b> for Right Side Images</li>
</ul>
</div>

<div id="for_vh" <?php  if(!isset($_SESSION['site_id']) || ($_SESSION['site_id']!='244329095')){ ?> style="display:none" <?php } ?> >
<ul>
<li>Enter <b>thumb-image</b> for Thumbnail Image</li>
<li>Enter <b>main-content</b> for Content page Image</li>
</ul>
</div>
								</div>
								
								
								<div class="control-group formSep">
												<label for="fileinput" class="control-label">Picture <span class="f_req">*</span></label>
												<div class="controls">
													<div data-fileupload="image" class="fileupload fileupload-new">
														<input type="hidden" />
														<div style="width: 80px; height: 80px;" class="fileupload-new thumbnail"><img src="<?php echo $b64Src; ?>" alt="" width="80" height="80" id="usr_img" /></div>
														<div style="width: 80px; height: 80px; line-height: 80px;" class="fileupload-preview fileupload-exists thumbnail"></div>
														<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="fileinput" name="fileinput" /></span>
														<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
													</div>	
												</div>
												<?php if($pictre!='' && $mime!='') { ?>
												<a target="_blank" href="download_image.php?GUID=<?php echo $_GET['GUID']; ?>" title="Download Image" ><i class="splashy-download"></i><?php echo $Name; ?></a>
												<?php } ?>
											</div>
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label>Category </label>
											<select multiple="multiple" onChange="" name="cat[]" id="cat" style="width:500px" size="6">
												<option value="">-- Select Category --</option>
			<?php
			if(isset($_GET['GUID']) || isset($_SESSION['site_id'])) {
			
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name"))
			{
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID='$categorygroupId' $where_cond ORDER BY Name")){
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $cat_ids)) { echo "selected"; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName;  if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				?>
				</optgroup>
				<?php
				}
				?>
				
				<?php
			} }
			?>
			
			<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE 1 and CategoryGroupID not in (select ID from categorygroups ) $where_cond ORDER BY Name")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option <?php if(in_array($categoryID, $cat_ids)) { echo "selected"; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; }  ?>
				</option>
				<?php
				}
				}
				?>
			
			
			<?php } 
		?>
											</select>
										</div>
									</div>
									<ul>
										<li><b>(Ctrl + Click to select multiple Categories)</b></li>
									</ul>
								</div>
								
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Document Search </label>
											<input type="text" class="span12" name="document" id="document" value="" />
										</div>
										<div class="span6">
											<label>Related Documents </label>
											<select onChange="" name="docu" id="docu">
											<?php
												if($doc_id && isset($_GET['GUID'])) {
												
												if($doc = $db->get_row("SELECT ID,Document,Code FROM `documents` WHERE 1 $where_cond AND ID='$doc_id' ")){
												
													?>
													<option value="<?php echo $doc->ID; ?>"><?php echo $doc->Document." (".$doc->Code.")"; ?></option>	
													<?php
												}}
											?>
											
											</select>
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
												
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label><span class="error_placement">Status </span> <span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($Active == 1 || $Active == 2) { echo ' checked'; } ?> />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" <?php if($Active == 0) { echo ' checked'; } ?> />
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
					//search documents
					$('#document').blur(function() {
						if($('#document').val()!=''){
						$.__contactSearch();
						}
					});
					$.__contactSearch = function() {

						$.ajax({
						  url: "search-documents.php",
						  data: { 'keyword': $("#document").val() },
						  cache: false
						}).done(function( html ) {
							$("#docu").empty();
							$("#docu").append(html);
						});
						$('#docu').show();
					}
					//* regular validation
					gebo_validation.reg();
					
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
					
					
					
					$("#site_id_sel").change(function(){
						var site_id=$(this).val();
						if(site_id=='29201'){
							$('#for_tp').show();
						}
						else{
							$('#for_tp').hide();
						}
						if(site_id=='244329095'){
							$('#for_vh').show();
						}
						else{
							$('#for_vh').hide();
						}
						$.ajax({
						type: "POST",
						url: "doc_list.php",
						data: "site_id=" + site_id ,
						success: function(response){
							
							$("#docu").html(response);
						}
					 });
					 
					 $.ajax({
						type: "POST",
						url: "categories_list.php",
						data: "site_id=" + site_id ,
						success: function(response){
							
							$("#cat").html(response);
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
								code: { required: true },
								
								status: { required: true },
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