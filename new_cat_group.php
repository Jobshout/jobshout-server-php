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


// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$CODE=$_POST["Code"];
	$Name=$_POST["name"];
	
	$Active=$_POST["status"];
	$Sync_Modified=$_POST["Sync_Modified"];
	$Sync_Modified=strtotime($Sync_Modified);
	$time= time();
	
	$user_id=$_POST["userID"];
	
	$GUID=$_POST["GUID"];
	
	
	
	
	 $db->query("INSERT INTO categorygroups (GUID,Created,Modified,SiteID,Name,Code,Active,UserID,Sync_Modified) VALUES ('$GUID','$time','$time','".SITE_ID."','$Name','$CODE','$Active','$user_id','$Sync_Modified')");
	 //$db->debug();
	
	
	

}



require_once('include/main-header.php'); ?>
<script type="text/javascript">
function generate_code()
{
	var val=document.getElementById('CategoryName').value;
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
                    <div><a href="cat_groups.php">Back to listing</a></div><br/><br/>
                    <div class="row-fluid">
                        
                        <div class="span6">
							<h3 class="heading">Add Category Group</h3>
							<form class="form_validation_reg" method="post" action="">
								
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Category Group Name <span class="f_req">*</span></label>
											<input type="hidden" value="<?php $Guid = NewGuid(); echo $Guid; ?>" name="GUID" id="GUID" >
											<input type="text" name="name" class="span12" id="CategoryName" onKeyUp="generate_code()" onBlur="generate_code()" value=""/>
											
																					</div>
										<div class="span4">
											<label>Code <span class="f_req">*</span></label>
											<input type="text" class="span12" name="Code" id="Code" readonly="readonly" value="" />
											<span class="help-block">URL (SEO friendly)</span>
										</div>
									</div>
								</div>

								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label><span class="error_placement">Status </span> <span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" />
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" />
												Inactive
											</label>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>User <span class="f_req">*</span></label>
											<select onChange="" name="userID">
												<option value="0">-- Select User --</option>
											<?php
												$catgoriziedUserID = $UserID;
												$op = $db->get_results("SELECT ID,Name FROM `users` WHERE zStatus='Active' AND SiteID='".SITE_ID."' ORDER BY ID");
												foreach($op as $opt){
													$id=$opt->ID;
													$name=$opt->Name;
													?>
													<option value="<?php echo $id; ?>"><?php echo $name; ?></option>	
													<?php
												}
											?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Date <span class="f_req">*</span></label>
											<div class="input-append date" id="dp2" data-date-format="mm/dd/yyyy">
												<input class="input-small" placeholder="DateTime" type="text" readonly="readonly"  name="Sync_Modified"  value="" /><span class="add-on"><i class="splashy-calendar_day"></i></span><span class="help-block">&nbsp;</span>
											</div>
										</div>
									</div>
								</div>
								
								<div class="form-actions">
									<button class="btn btn-inverse" type="submit" name="submit">Save changes</button>
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
								code: { required: true },
								name: { required: true },
								userID: { required: true },
								status: { required: true },
								Sync_Modified: { required: true }
								
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