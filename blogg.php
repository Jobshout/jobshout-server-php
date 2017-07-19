<?php 
session_start();
if($_SESSION['UserEmail'] =='') {
       header("location:index.php");
}
require_once("connect.php"); 
require_once("constants.php");

function NewGuid() { 
	$s = strtoupper(md5(uniqid(rand(),true))); 
	$guidText = 
		substr($s,0,8) . '-' . 
		substr($s,8,4) . '-' . 
		substr($s,12,4). '-' . 
		substr($s,16,4). '-' . 
		substr($s,20); 
	return $guidText;
}

if(isset($_POST['submit']))
{
		
	$GUID = $_POST["GUID"];
	$Code=$_POST["Code"];
	//$Document=$_POST["Document"];
	//$Header=$_POST["Header"];
	$Body=addslashes($_POST["Body"]);
	$MetaTagKeywords=addslashes($_POST["MetaTagKeywords"]);
	$MetaTagDescription=addslashes($_POST["MetaTagDescription"]);
	$UserID=$_POST["UserID"];
	//$Title=$_POST["Title"];
	$PageTitle=$_POST["PageTitle"];
	$Type="Blog";
	//$SiteName=$_POST["SiteName"];
	//$Reference=$_POST["Reference"];
	//$Sync_LastTime=$_POST["Sync_LastTime"];
	$Published_timestamp=$_POST["Published_timestamp"];
	$Published_timestamp= strtotime($Published_timestamp);
	
	$time= time();
	
	$auto_format=1;
		if(isset($_POST["chk_manual"])) {
		$auto_format=0;
		}
	
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		$db->query("UPDATE documents SET SiteID='".SITE_ID."', Code='$Code', Body='$Body', MetaTagKeywords='$MetaTagKeywords', MetaTagDescription='$MetaTagDescription', UserID='$UserID',
	PageTitle='$PageTitle',
	Type='$Type',
	Published_timestamp='$Published_timestamp', AutoFormatTitle=$auto_format
	WHERE GUID ='$guid'") ;
	$sec_msg = "Successfully updated";
	}
	else {
	/*
	echo "INSERT INTO documents (ID,SiteID,Code,Body,MetaTagKeywords,MetaTagDescription,UserID,PageTitle,Type,Published_timestamp,GUID,Sync_LastTime,AutoFormatTitle,PostedTimestamp
	
	
									VALUES(NULL,'".SITE_ID."','$Code','$Body','$MetaTagKeywords','$MetaTagDescription','$UserID','$PageTitle','$Type','$Published_timestamp','$GUID',1,$auto_format,'$Published_timestamp')";*/
									
	 
		$db->query("INSERT INTO documents (ID,SiteID,Code,Body,MetaTagKeywords,MetaTagDescription,UserID,PageTitle,Type,Published_timestamp,GUID,Sync_LastTime,AutoFormatTitle,PostedTimestamp) 
									VALUES(NULL,'".SITE_ID."','$Code','$Body','$MetaTagKeywords','$MetaTagDescription','$UserID','$PageTitle','$Type','$Published_timestamp','$GUID',1,$auto_format,'$Published_timestamp')");
						$sec_msg = "Successfully inserted";
								/*	$db->debug();*/
	}
	
	
	$document = $db->get_row("SELECT ID,Site_GUID,GUID FROM documents where documents.GUID ='$GUID'");

		$documentId=$document->ID;
		$documentSiteGUID=$document->Site_GUID;
		$document_GUID=$document->GUID;
	
	
	$category1=$_POST["category1"];
	$category2=$_POST["category2"];
	$category3=$_POST["category3"];
	$category4=$_POST["category4"];
	
	$db->query("delete FROM documentcategories WHERE SiteID='".SITE_ID."' AND DocumentID='$documentId'");
	
	$categories = array($category1,$category2,$category3,$category4);
	foreach($categories as $categoriesID){
		if($categoriesID != ''){ 
			
			$selectCategory = $db->get_row("SELECT CategoryGroupID,Server_Number,GUID FROM categories WHERE SiteID='".SITE_ID."' AND ID='$categoriesID'");
			$CategoryGroupID = $selectCategory->CategoryGroupID;
			$Server_Number = $selectCategory->Server_Number;
			$Category_GUID = $selectCategory->GUID;
			
			$dcGUID=NewGuid();
			
			
			$query= $db->query("INSERT INTO documentcategories(ID, Created, Modified, SiteID,CategoryGroupID, CategoryID,DocumentID, GUID,Server_Number,Site_GUID,Category_GUID,Document_GUID, Sync_Modified) 
			VALUES(Null,'$time','$time','".SITE_ID."','$CategoryGroupID','$categoriesID','$documentId','$dcGUID','$Server_Number','$documentSiteGUID','$Category_GUID','$document_GUID','0')");
			//$db->debug();
			
		}
	}
	//$db->debug();
}
?>

<?php
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$document = $db->get_row("SELECT ID,SiteID,Code,Document,Header,Body,MetaTagKeywords,MetaTagDescription,UserID,Title,PageTitle,Type,SiteName,GUID,Site_GUID,Reference,Sync_LastTime, AutoFormatTitle,
Published_timestamp FROM documents where documents.GUID ='$guid'");

		$documentID=$document->ID;
		
		$Code=$document->Code;
		$Document=$document->Document;
		$Header=$document->Header;
		$Body=$document->Body;
		$MetaTagKeywords=$document->MetaTagKeywords;
		$MetaTagDescription=$document->MetaTagDescription;
		$UserID=$document->UserID;
		$Title=$document->Title;
		$PageTitle=$document->PageTitle;
		$Type=$document->Type;
		$SiteName=$document->SiteName;
		$document_GUID=$document->GUID;
		$documentSiteGUID=$document->Site_GUID;
		$Reference=$document->Reference;
		$Sync_LastTime=$document->Sync_LastTime;
		$Published_timestamp=$document->Published_timestamp;
		$date = Date('m/d/Y',$Published_timestamp);
		$auto_format=$document->AutoFormatTitle;

$dc = $db->get_results("SELECT CategoryID FROM documentcategories WHERE SiteID='".SITE_ID."' AND DocumentID='$documentID'");
//$db->debug();
if($dc != ''){
 foreach($dc as $dc){
	$dcCategoryId[]= $dc->CategoryID;
	
}
}
}
else {
	$documentID='';
		$guid= '';
		$Code='';
		$Document='';
		$Header='';
		$Body='';
		$MetaTagKeywords='';
		$MetaTagDescription='';
		$UserID='';
		$Title='';
		$PageTitle='';
		$Type='';
		$SiteName='';
		$document_GUID='';
		$documentSiteGUID='';
		$Reference='';
		$Sync_LastTime='';
		$Published_timestamp='';
		$date = '';
		$auto_format=1;

}

?>



<?php require_once('include/main-header.php'); ?>  

<script type="text/javascript">
function generate_code()
{
	var status=document.getElementById('chk_manual').checked;
	if(status!=true){
		var val=document.getElementById('PageTitle').value;
		var patt=/[^A-Za-z0-9_-]/g;
		var result=val.replace(patt,' ');
		result=result.replace(/\s+/g, ' ');
		result = result.replace(/^\s+|\s+$/g,'');
		result=result.replace(/\s/g, '-');
		result=result.toLowerCase();
		//alert(result);	
		document.getElementById('Code').value=result;
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
                        <?php require_once('include/breadcrum.php');?>
                    </nav>
                     <div><a href="blogs.php">Back to listing</a></div><br/><br/>
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Content Editor</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Search Optimisation</a></li>
									<li id="li3"><a href="#tab3" data-toggle="tab">Categories</a></li>
									<li id="li4"><a href="#tab4" data-toggle="tab">Settings</a></li>
								</ul>
								<form action="" method="post" class="form_validation_reg" >
								<div class="tab-content">
								
									<div class="tab-pane active" id="tab1">

                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<p class="f_legend">Bolg </p>
											
											<div id="validation" style="padding-left: 200px;"><span style='color: red'><?php if (isset($sec_msg)) echo $sec_msg; ?></span></div>
											<?php
											$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user->access_rights_code>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="control-group">
												<label class="control-label">Site Id<span class="f_req">*</span></label>
												<div class="controls">
												
													<select name="site_id" id="site_id_sel" >
												<option value="">-- Select Site --</option>
							<?php
							$sites=$db->get_results("select id,name from sites");
							foreach($sites as $site)
							{
							?>
							<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
							<?php } ?>
							</select>
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="control-group">
												<label class="control-label">Site Id<span class="f_req">*</span></label>
												<div class="controls">
												
													<select onChange="" name="site_id" id="site_id_sel" >
												<option value="">-- Select Site --</option>
							<?php
							$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."')");
							foreach($sites as $site)
							{
							?>
							<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
							<?php } ?>
							</select>
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											
											<?php
											} else {
										?>
										<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
										<?php
										} }
										?>	
										
											<div class="control-group">
												<label class="control-label">Title<span class="f_req">*</span></label>
												<div class="controls">
													<input type="hidden" value="<?php if($guid!='') { echo $guid; } else { $Guid = NewGuid();
									echo $Guid; } ?>" name="GUID" class="textbox">
												
													<input type="text" class="span10" name="PageTitle" id="PageTitle" onKeyUp="generate_code()" onBlur="generate_code()"  value="<?php echo $PageTitle;?>" />

													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Body<span class="f_req">*</span></label>
												<div class="controls">
												
													<textarea cols="30" rows="35" class="span10" name="Body" id="wysiwg_full"><?php echo $Body;?></textarea>
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
										</fieldset>
									</div>
                                </div>

                                <div class="span3">
									<div class="well form-inline">
										<p class="f_legend">Published By</p>
										<div class="controls">
										<select onChange="" name="UserID" id="UserID" placeholder="Published_DateTime" style="width:140px;" >
		<option value="0">-- Select User--</option>
		<?php
			$documentUserID = $UserID;
			$users = $db->get_results("SELECT ID,Name FROM `users` WHERE zStatus='Active' AND SiteID='".SITE_ID."' ORDER BY ID");
			foreach($users as $user){
				$UserID=$user->ID;
				$name=$user->Name;
				?>
				<option <?php if($UserID == $documentUserID) { echo "selected"; } ?> value='<?php echo $UserID; ?>' >
						<?php echo $name; ?>
				</option>
				<?php
			}
		?>
		</select>
										&nbsp;At
										<span class="help-block">&nbsp;</span>
										</div><br />
										
										<div class="input-append date" id="dp2" data-date-format="mm/dd/yyyy">
									<input class="input-small" placeholder="Published_DateTime" type="text" readonly="readonly"  name="Published_timestamp"  value="<?php echo $date; ?>" /><span class="add-on"><i class="splashy-calendar_day"></i></span><span class="help-block">&nbsp;</span>
								</div>

																			
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
											<p class="f_legend">Blog </p>
											<div class="control-group">
												<label class="control-label">Code<span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" class="span10" name="Code" id="Code" <?php if($auto_format!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $Code;?>" />
													<span class="help-block">URL (SEO friendly)</span>
													<span class="help-block">
													<input type="checkbox" name="chk_manual" id="chk_manual" value="0" <?php if($auto_format==0) { ?> checked="checked" <?php } ?>  />
													I want to manually enter code</span>

												

												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Meta Data Keywords<span class="f_req">*</span></label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagKeywords" class="span10"><?php echo $MetaTagKeywords;?></textarea>
													<span class="help-block">Search terms relevant to this page to find content of this page easily</span>
												</div>
												
												
																								<label class="control-label">Meta Data Description<span class="f_req">*</span></label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagDescription" class="span10"><?php echo $MetaTagDescription;?></textarea>
													<span class="help-block">Overview which describes this page (About 300 words)</span>
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
											<p class="f_legend">Blog </p>
											<div class="control-group">
												<label class="control-label">Categories</label>
												<div class="controls">
													
													<select onChange="" name="category1">
		<option value="">-- Not Specified --</option>
			<?php
			$cat_no=0;
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='Blog' AND SiteID='".SITE_ID."' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='Blog' AND SiteID='".SITE_ID."' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==0) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categorygroupName.':  '.$categoryName; ?>
				</option>
				<?php
				
				}
			}
		?>
		</select>
		<select onChange="" name="category2">
		<option value="">-- Not Specified --</option>
			<?php
			$cat_no=0;
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='Blog' AND SiteID='".SITE_ID."' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='Blog' AND SiteID='".SITE_ID."' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==1) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categorygroupName.':  '.$categoryName; ?>
				</option>
				<?php
				
				}
			}
		?>
		</select>
		<select onChange="" name="category3">
		<option value="">-- Not Specified --</option>
			<?php
			$cat_no=0;
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='Blog' AND SiteID='".SITE_ID."' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='Blog' AND SiteID='".SITE_ID."' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==2) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categorygroupName.':  '.$categoryName; ?>
				</option>
				<?php
				
				}
			}
		?>
		</select>
		<select onChange="" name="category4">
		<option value="">-- Not Specified --</option>
			<?php
			$cat_no=0;
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='Blog' AND SiteID='".SITE_ID."' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='Blog' AND SiteID='".SITE_ID."' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==3) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categorygroupName.':  '.$categoryName; ?>
				</option>
				<?php
				
				}
			}
		?>
		</select>

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
									
									
									
																		<div class="tab-pane" id="tab4">
										<p>
											<div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<p class="f_legend">Blog </p>
											
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
                    </div>
										</p>
									</div>
									
									
									
								</div>
								
								
								
									
								<button class="btn btn-gebo" type="submit" name="submit">Save changes</button>
								
								
								
								
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
		            <!-- validation -->
            <script src="lib/validation/jquery.validate.min.js"></script>
            <!-- validation functions -->
            <script src="js/page_validation.js"></script>
			
			  <script type="text/javascript">
			$(document).ready(function() {
				//* datepicker
				gebo_datepicker.init();
				
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
							generate_code();
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
				
			});
		
			//* bootstrap datepicker
			gebo_datepicker = {
				init: function() {
					$('#dp2').datepicker();
				}
			};

			</script>
			
			<script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
//tinymce.PluginManager.load('moxiemanager', '/js/moxiemanager/plugin.min.js');

tinymce.init({
	selector: "textarea#wysiwg_full",
   
	plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste "
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
	autosave_ask_before_unload: false
});

tinymce.init({
	selector: "textarea#art_text1",
   
	plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste "
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
	autosave_ask_before_unload: false
});
</script>


</div>
	</body>
</html>