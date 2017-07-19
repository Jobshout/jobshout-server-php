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
			
		$PageTitle=$_POST["PageTitle"];
		$reference=$_POST["reference"];
		$job_type=$_POST["job_type"];
		$sal_from=$_POST["sal_from"];
		$sal_to=$_POST["sal_to"];
		$sal_dur=$_POST["sal_dur"];
		$location=$_POST["location"];

		$Code=$_POST["Code"];
		$Body=$_POST["Body"];
		$MetaTagKeywords=$_POST["MetaTagKeywords"];
		$MetaTagDescription=$_POST["MetaTagDescription"];
		$UserID=$_POST["UserID"];
		
		$Type='job';
		$Published_timestamp=$_POST["Published_timestamp"];
		$Published_timestamp= strtotime($Published_timestamp);
		
		$GUID = $_POST["GUID"];
		
			
	$db->query("INSERT INTO documents (ID, SiteID, Code, Body, Reference, FFAlpha80_3, FFReal01, FFReal02, FFAlpha80_7, FFAlpha80_6, MetaTagKeywords, MetaTagDescription, UserID, PageTitle, Type, Published_timestamp, GUID, Sync_LastTime, PostedTimestamp) 
									VALUES(NULL, '".SITE_ID."', '$Code', '$Body', '$reference', '$job_type', '$sal_from', '$sal_to', '$sal_dur', '$location', '$MetaTagKeywords', '$MetaTagDescription', '$UserID', '$PageTitle', '$Type', '$Published_timestamp', '$GUID', 1, '$Published_timestamp')");
	
	$db->debug();
	$document = $db->get_row("SELECT ID,GUID FROM documents where documents.GUID ='$GUID'");

		$documentId=$document->ID;
		
		$document_GUID=$document->GUID;
	
	$time= time();
	
	$category1=$_POST["category1"];
	$category2=$_POST["category2"];
	$category3=$_POST["category3"];
	$category4=$_POST["category4"];
	
	
	$categories = array($category1,$category2,$category3,$category4);
	foreach($categories as $categoriesID){
		if($categoriesID != ''){ 
			
			$selectCategory = $db->get_row("SELECT CategoryGroupID,Server_Number,GUID FROM categories WHERE SiteID='".SITE_ID."' AND ID='$categoriesID'");
			$CategoryGroupID = $selectCategory->CategoryGroupID;
			$Server_Number = $selectCategory->Server_Number;
			$Category_GUID = $selectCategory->GUID;
			
			$dcGUID=NewGuid();
			
			
			$query= $db->query("INSERT INTO documentcategories(ID, Created, Modified, SiteID,CategoryGroupID, CategoryID,DocumentID, GUID,Server_Number,Category_GUID,Document_GUID, Sync_Modified) 
			VALUES(Null,'$time','$time','".SITE_ID."','$CategoryGroupID','$categoriesID','$documentId','$dcGUID','$Server_Number','$Category_GUID','$document_GUID','0')");
			
		}
	}
}
?>




<?php require_once('include/main-header.php'); ?>  

<script type="text/javascript">
function generate_code()
{
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
                    <div><a href="jobs.php">Back to listing</a></div><br/><br/>
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Content Editor</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Search Optimisation</a></li>
									<li id="li3"><a href="#tab3" data-toggle="tab">Categories</a></li>
									<li id="li4"><a href="#tab4" data-toggle="tab">Settings</a></li>
								</ul>
								<form action="" method="post" class="form_validation_reg">
								<div class="tab-content">
								
									<div class="tab-pane active" id="tab1">

                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<p class="f_legend">Job </p>
											<div class="control-group">
												<label class="control-label">Title<span class="f_req">*</span></label>
												<div class="controls">
													<input type="hidden" value="<?php $Guid = NewGuid();
									echo $Guid; ?>" name="GUID" class="textbox">
												
													<input type="text" class="span10" name="PageTitle" id="PageTitle" onKeyUp="generate_code()" onBlur="generate_code()" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Body<span class="f_req">*</span></label>
												<div class="controls">
												
													<textarea cols="30" rows="35" class="span10" name="Body" id="Body"></textarea>
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Reference<span class="f_req">*</span></label>
												<div class="controls">
																									
													<input type="text" class="span10" name="reference" id="reference" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Job Type<span class="f_req">*</span></label>
												<div class="controls">
													
													<select onChange="" name="job_type" id="job_type" >
		<option value="">-- Select Job Type--</option>
		
				<option value='Temporary' >Temporary</option>
				<option value='Permanent' >Permanent</option>
				<option value='Contract' >Contract</option>
				<option value='Freelance' >Freelance</option>
				<option value='Part time' >Part time</option>
				
		</select>
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Salary<span class="f_req">*</span></label>
												<div class="controls">
													<div class="span4" style="width:80px">
													<input type="text" class="span10" name="sal_from" id="sal_from"  style="width:80px;" />
													<span class="help-block">&nbsp;</span></div>
													<div class="span4" style="width:20px">&nbsp;To&nbsp;</div>
													<div class="span4" style="width:80px">
													<input type="text" class="span10" name="sal_to" id="sal_to"  style="width:80px;" />
													<span class="help-block">&nbsp;</span></div>
													<div class="span4">
													<select onChange="" name="sal_dur" id="sal_dur" >
				
				<option value='per annum' >Per Annum</option>
				<option value='per month' >Per Month</option>
				<option value='per week' >Per Week</option>
				<option value='per day' >Per Day</option>
				<option value='per hour' >Per Hour</option>
				
		</select></div>
		
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Location<span class="f_req">*</span></label>
												<div class="controls">
													
													<input type="text" class="span10" name="location" id="location"  />
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
										<select onChange="" name="UserID" id="UserID" style="width:140px;" >
		<option value="">-- Select User--</option>
		<?php
			$documentUserID = $UserID;
			$users = $db->get_results("SELECT ID,Name FROM `users` WHERE zStatus='Active' AND SiteID='".SITE_ID."' ORDER BY ID");
			foreach($users as $user){
				$UserID=$user->ID;
				$name=$user->Name;
				?>
				<option value='<?php echo $UserID; ?>' >
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
									<input class="input-small" placeholder="Published_DateTime" type="text" readonly="readonly"  name="Published_timestamp" id="Published_timestamp"  /><span class="add-on"><i class="splashy-calendar_day"></i></span><span class="help-block">&nbsp;</span>
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
											<p class="f_legend">Job </p>
											<div class="control-group">
												<label class="control-label">Code<span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" class="span10" name="Code" id="Code" readonly="readonly" />
													<span class="help-block">URL (SEO friendly)</span>

												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Meta Data Keywords</label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagKeywords" id="MetaTagKeywords" class="span10"></textarea>
													<span class="help-block">Search terms relevant to this page to find content of this page easily</span>
												</div>
												
												
																								<label class="control-label">Meta Data Description</label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagDescription" id="MetaTagDescription" class="span10"></textarea>
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
											<p class="f_legend">Page </p>
											<div class="control-group">
												<label class="control-label">Categories</label>
												<div class="controls">
													
													<select onChange="" name="category1">
		<option value="">-- Not Specified --</option>
			<?php
			
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='job' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='job' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option value='<?php echo $categoryID; ?>' >
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
			
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='job' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='job' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option value='<?php echo $categoryID; ?>' >
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
			
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='job' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='job' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option value='<?php echo $categoryID; ?>' >
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
			
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='job' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='job' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option value='<?php echo $categoryID; ?>' >
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
											<p class="f_legend">Job </p>
											<!--<div class="control-group">
												<label class="control-label">Document Type<span class="f_req">*</span> </label>
												<div class="controls">
													
													<select onChange="" name="Type" id="Type">
		<option <?php if($Type=="page") { ?> selected="selected" <?php } ?> value="page">Page</option>
				
		</select>
<span class="help-block">[ Default value : Page ]</span>
												</div>
											</div>-->
											
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
                    </div>
										</p>
									</div>
									
									
									
								</div>
								
								
								
									
								<button class="btn btn-gebo" type="submit" name="submit" >Save changes</button>
								
								
								
								
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
			
			<script>
				$(document).ready(function() {
					//* regular validation
					gebo_validation.reg();
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
								PageTitle: { required: true },
								job_type: { required: true },
								sal_from: { required: true },
								sal_to: { required: true },
								sal_dur: { required: true },
								location: { required: true },
								UserID: { required: true },
								Published_timestamp: { required: true },
								Code: { required: true },
							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
					}
				};
			</script>
			
       <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
//tinymce.PluginManager.load('moxiemanager', '/js/moxiemanager/plugin.min.js');

tinymce.init({
	selector: "textarea#Body",
   
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