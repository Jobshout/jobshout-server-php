<?php 
require_once("include/lib.inc.php");

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from sites where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and ID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: site.php");
	}
}


if(isset($_POST['submit']))
{ 

    $SiteName=$_POST["name"];
	$time=time();
	$Code=$_POST["Code"];
	$host=$_POST["host"];
	$rootFolder=$_POST["rootFolder"];
	$adminEmail=$_POST["adminEmail"];
	$dns=$_POST["dns"];
	$siteType=$_POST["siteType"];
	$versionNumber=$_POST["versionNumber"];	
	/*$stagingRootDir=$_POST["stagingRootDir"];		
	$serverType=$_POST["serverType"];
	$stagingServerType=$_POST["stagingServerType"];
	$buildNumber=$_POST["buildNumber"];*/
	
	$arr_Created=explode('/', $_POST["Created"]);
	$Created=$arr_Created[1].'/'.$arr_Created[0].'/'.$arr_Created[2];
	$created_time=$_POST["created_time"];
	if($created_time==''){
		$created_time=date('h:i A');
	}
	$Created=$Created." ".$created_time;
	$Created=strtotime($Created);
	$arr_Modified=explode('/', $_POST["Modified"]);
	$Modified=$arr_Modified[1].'/'.$arr_Modified[0].'/'.$arr_Modified[2];
	$modified_time=$_POST["modified_time"];
	if($modified_time==''){
		$modified_time=date('h:i A');
	}
	$Modified=$Modified." ".$modified_time;
	$Modified=strtotime($Modified);
	
	$auto_format=1;
		if(isset($_POST["Auto_Format"])) {
		$auto_format=0;
		}
	
	if(isset($_GET['GUID'])){		
		$GUID=$_GET["GUID"];
		 if($db->query("UPDATE sites SET  Name='$SiteName', Code='$Code', Host='$host', zStatus='$siteType', RootDirectory='$rootFolder', WebsiteAddress='$dns', AdminEmail='$adminEmail', Created='$Created', Modified='$Modified' where GUID='".$_GET['GUID']."'")){
		 
			 $_SESSION['up_message'] = "Updated successfully";
		} 
	} else {
		
		$GUID=UniqueGuid('sites', 'GUID');
		 if($db->query("INSERT INTO sites (GUID, Name, Code, Host, zStatus, RootDirectory, WebsiteAddress, AdminEmail, Created, Modified) VALUES ('$GUID', '$SiteName', '$Code', '$host', '$siteType', '$rootFolder', '$dns', '$adminEmail', '$Created', '$Modified')")){
		 
			 $_SESSION['ins_message'] = "Inserted successfully ";
			 header("Location:sites.php");
		 }
	}

}
	
if(isset($_GET['GUID'])){
	$guid=$_GET['GUID'];
	$site = $db->get_row("SELECT * FROM sites where  GUID = '".$_GET['GUID']."'");
	$id= $site->ID;
	$Name= $site->Name;
	$Code= $site->Code;
	$RootPath= $site->RootPath;
	$zStatus= $site->zStatus;
	$Host= $site->Host;
	$RootDirectory= $site->RootDirectory;
	$WebmasterEmail= $site->WebmasterEmail;
	$WebsiteAddress= $site->WebsiteAddress;
	$AdminEmail= $site->AdminEmail;
	$Server_Number= $site->Server_Number;
	/*$SiteServerType= $site->SiteServerType;
	$StagingServerType= $site->StagingServerType;
	$StagingRootDirectory= $site->StagingRootDirectory;
	$BuildNumber= $site->BuildNumber;*/
	$Created=date("d/m/Y",$site->Created);
	$created_time = date('h:i A',$site->Created);
	$Modified=date("d/m/Y",$site->Modified);
	$modified_time = date('h:i A',$site->Modified);
	$versionNumber=5;
	$Auto_Format=0;
	$Type='';
}else{
	$id='';
	$guid='';
	$Name= '';
	$Code= '';
	$RootPath= '';
	$zStatus= '';
	$Host= '';
	$RootDirectory= '';
	$WebmasterEmail= '';
	$WebsiteAddress= '';
	$AdminEmail= '';
	$Server_Number='';
	$Created= '';
	$Modified= '';
	/*$SiteServerType= '';
	$StagingServerType= '';
	$StagingRootDirectory= '';
	$BuildNumber= '';*/
	$created_time = date('h:i A');
	$modified_time = date('h:i A');
	$versionNumber='';
	$Type='';
	$Auto_Format=0;
}
require_once('include/main-header.php'); ?>


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
									<a href="sites.php">Sites</a>
								</li>
								<li>
									<a href="#">Site</a>

								</li>
								
								<?php include_once("include/curr_selection.php"); ?>
							</ul>
						</div>
                    </nav>
                    
					<div id="validation" ><span style="color:#00CC00;font-size:18px"><?php if (isset($sec_msg)) echo $sec_msg; ?></span></div><br/>
                    <div class="row-fluid">
                        
                        <div class="span6">
							
							<form class="form_validation_reg" method="post" action="">
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Site Name <span class="f_req">*</span></label>
<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" id="GUID" class="textbox">
											<input type="text" name="name" class="span12" id="SiteName" onKeyUp="generate_code('Auto_Format','SiteName','Code')" onBlur="generate_code('Auto_Format','SiteName','Code')" value="<?php echo $Name;?>"/>
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
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Host <span class="f_req">*</span></label>
											<input type="text" name="host" class="span12" id="host" value="<?php echo $Host;?>"/>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Root Directory</label>
											<input type="text" name="rootFolder" class="span12" id="rootFolder" value="<?php echo $RootDirectory;?>"/>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Admin Email </label>
											<input type="text" name="adminEmail" class="span12" id="adminEmail" value="<?php echo $AdminEmail;?>"/>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>DNS Server Link </label>
											<input type="text" name="dns" class="span12" id="dns" value="<?php echo $WebsiteAddress;?>"/>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Site Type <span class="f_req">*</span></label>
											<select onChange="" name="siteType">
												<option value="">-- Select --</option>
												<option <?php if($zStatus == 'demo') { echo "selected"; } ?> value="demo">Demo</option>
												<option <?php if($zStatus == 'Active') { echo "selected"; } ?> value="Active">Active</option>
												<option <?php if($zStatus == 'ToBeActivated') { echo "selected"; } ?> value="ToBeActivated">ToBeActivated</option>
												<option <?php if($zStatus == 'Development') { echo "selected"; } ?> value="Development">Development</option>
												<option <?php if($zStatus == 'Staging') { echo "selected"; } ?> value="Staging">Staging</option>
											</select>
										</div>
									</div>
								</div>
								<!--<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Site Server Type </label>
											<select onChange="" name="serverType">
												<option value="">-- Select --</option>
												<option <?php //if($SiteServerType == 'Nginx') { echo "selected"; } ?> value="Nginx">Nginx</option>
												<option <?php //if($SiteServerType == 'Apache') { echo "selected"; } ?> value="Apache">Apache</option>
												<option <?php //if($SiteServerType == 'IIS') { echo "selected"; } ?> value="IIS">IIS</option>
											</select>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label>Staging Server Type </label>
											<select onChange="" name="stagingServerType">
												<option value="">-- Select --</option>
												<option <?php //if($StagingServerType == 'Nginx') { echo "selected"; } ?> value="Nginx">Nginx</option>
												<option <?php //if($StagingServerType == 'Apache') { echo "selected"; } ?> value="Apache">Apache</option>
												<option <?php //if($StagingServerType == 'IIS') { echo "selected"; } ?> value="IIS">IIS</option>
											</select>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Staging Root Directory</label>
											<input type="text" name="stagingRootDir" class="span12" id="stagingRootDir" value="<?php //echo $StagingRootDirectory; ?>"/>
										</div>
									</div>
								</div>-->
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Site Version Number</label>
											<input type="text" name="versionNumber" class="span12" id="versionNumber" value="<?php echo $versionNumber; ?>"/>
										</div>
										<!--<div class="span4">
											<label>Build Number</label>
											<input type="text" name="buildNumber" class="span12" id="buildNumber" value="<?php //echo $BuildNumber; ?>"/>
										</div>-->
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label> Created On <span class="f_req">*</span></label>
											<div class="input-append date" id="dp1" >
												<input class="input-small" placeholder="DateTime" type="text" readonly="readonly"  name="Created" id="Created"  value="<?php echo $Created;?>" data-date-format="dd/mm/yyyy" /><span class="add-on"><i class="splashy-calendar_day"></i></span>
											</div>
											
											
										</div>
										<div class="span4">
									<label>&nbsp;</label>
									<input type="text" class="span6" id="tp_1" name="created_time" value="<?php echo $created_time; ?>" readonly="readonly" placeholder="Published Time" />
								
								</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label> Last Modified Date <span class="f_req">*</span></label>
											<div class="input-append date" id="dp2" >
												<input class="input-small" placeholder="DateTime" type="text" readonly="readonly"  name="Modified" id="Modified"  value="<?php echo $Modified;?>" data-date-format="dd/mm/yyyy" /><span class="add-on"><i class="splashy-calendar_day"></i></span>
											</div>
	
										</div>
										<div class="span4">
									<label>&nbsp;</label>
									<input type="text" class="span6" id="tp_2" name="modified_time" value="<?php echo $modified_time; ?>" readonly="readonly" placeholder="Published Time" />
								<span class="help-block">&nbsp;</span>
								</div>
									</div>
								</div>
								<?php if(isset($_GET['GUID'])) { ?>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span12">
											<label> Related Sites List <span class="f_req">*</span></label>
											<table id="rel_images" class="items table table-condensed table-striped" data-provides="rowlink">
												<thead>
												  <tr class="item">
													  <th width="80%">Site</th>
													  <th colspan="2" align="center">Actions</th>
												  </tr>
											  </thead>
											  <tbody>
											  
												<?php 
												if($RelatedSites = $db->get_results("SELECT * FROM sites where GUID in ( select uuid_related_site from wi_related_sites where  uuid_master_site = '$guid')")){												
												foreach($RelatedSites as $site){
												?>
												<tr class="item-row" >
													<td class="item-id">
														<span class="site_id" ><?php echo $site->Name.' ('.$site->Code.')';?></span>
														<div class="ui-widget" style="display:none;" >
														<select name="site_id_sel" id="site_id_sel" class="rid input-sm form-control" >
															
																<option value="<?php echo $site->ID; ?>"><?php echo $site->Name.' ('.$site->Code.')'; ?></option>	
														</select>
														</div>
														<input type="hidden" value="<?php echo $site->GUID;?>" class="h_rid" >
													</td>
													<td>
														<a href="javascript:void(0)" class="editlink">Edit</a>
														<a href="javascript:void(0)" class="savelink" style="display:none">Save</a>
													</td>	
													<td>
														<a href="javascript:void(0)" class="removelink" >Remove</a>
														<a href="javascript:void(0)" class="cancellink" style="display:none">Cancel</a>
													</td>	
												</tr>
												<?php }
												} ?>
												  <tr id="hiderow">
													<td colspan="4"><a href='javascript:void(0)'></a><a class="rel_addrow" href="javascript:void(0)" title="Add a row">Add related site</a></td>
												  </tr>
											  </tbody>
											  </table>	<br/>
										</div>
									</div>
								</div>
								<?php } ?>
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
			<!-- timepicker -->
            <script src="lib/datepicker/bootstrap-timepicker.min.js"></script>
            
			
			<script>
				function bind() {
				  $(".savelink").unbind();
				  $(".editlink").unbind();
				  $(".cancellink").unbind();
				  $(".removelink").unbind();
				  
				  $(".savelink").click(save);
				  $(".editlink").click(edit);
				  $(".cancellink").click(cancel);
				  $(".removelink").click(remove);
				  
				}
				
				//save
				function save() {
					var row = $(this).parents('.item-row');
					var site_uuid=$('#GUID').val();
					var site_id='<?php echo $id; ?>';
					var id=row.find('.rid').val();
					var old_id=row.find('.h_rid').val();
					
					if(id==site_id){
						alert('Please Select a Site other than the current site');
					}
					else if(id!='')
					{
						var dataString = 'site_uuid='+site_uuid+'&id='+id+'&old_id='+old_id;
						$.ajax({
							type: "POST",
							url: "saverelatedsite.php",
							data: dataString,
							dataType: 'json',
							success: function(response)
							{
								if(response.succ){
									alert("Site added successfully");
									row.find('.site_id').html(response.site_name);
									row.find('.h_rid').val(response.uuid);
									
									row.find('.ui-widget').hide();
									row.find('.site_id').show();
									
									row.find('.savelink').hide();
									row.find('.editlink').show();
									row.find('.cancellink').hide();
									row.find('.removelink').show();
									bind();
								}else{
									alert("This Site is already in relation with present site.");
									if(response.id){
										row.find('.rid').html('<option value="'+response.id+'">'+response.site_name+'</option>');
										row.find('.rid').combobox( "destroy" );
										row.find('.rid').combobox();
									}
								}
							}
						});
					}
					else
					{
					alert('Please Select a Site');
					}
				}
				
				//cancel
				function cancel() {
					var row = $(this).parents('.item-row');
					var id=row.find('.h_rid').val();
					if(id!=''){						
						row.find('.site_id').show();
						row.find('.ui-widget').hide();
						row.find('.savelink').hide();
						row.find('.editlink').show();
						row.find('.cancellink').hide();
						row.find('.removelink').show();
					}
					else
					{						
						row.remove();
					}
				}
				
				//edit
				function edit() {
					$("#rel_images a.cancellink").each(function(){
						$(this).trigger('click');
					});
					var row = $(this).parents('.item-row');					
					row.find('.site_id').hide();									
					row.find('.ui-widget').show();					
					row.find('.rid').combobox( "destroy" );
					row.find('.rid').combobox();
					row.find('.savelink').show();
					row.find('.editlink').hide();
					row.find('.cancellink').show();
					row.find('.removelink').hide();
					
					row.find('.custom-combobox-input').focus();					
				}
				
				//remove
				function remove() {

					var row = $(this).parents('.item-row');
					var related_site_guid=row.find('.rid').val();
					var site_guid=$('#GUID').val();
					
					var dataString = 'delrelatedsite_guid='+related_site_guid+'&delmaster_guid='+site_guid;
					$.ajax({
						type: "POST",
						url: "deleterelatedsite.php",
						data: dataString,
						success: function(response)
						{
							if(response){
								alert("Site removed successfully");
								row.remove();
							}
							else{
								alert("Problem removing Site");
							}
						}
					});
				}
				
				$(document).ready(function() {
					bind();
					$( ".rid" ).combobox();
					$(".rel_addrow").click(function(){
						$("#rel_images a.cancellink").each(function(){
							$(this).trigger('click');
						});
						$("#hiderow").before('<tr class="item-row"><td class="item-id"><span class="site_id" style="display:none;" ></span><div class="ui-widget" ><select class="rid input-sm form-control" ><option value=""></option></select></div><input type="hidden" value="" class="h_rid" ></td><td ><a href="javascript:void(0)" class="editlink" style="display:none">Edit</a><a href="javascript:void(0)" class="savelink" >Save</a></td><td><a href="javascript:void(0)" class="removelink" style="display:none" >Remove</a><a href="javascript:void(0)" class="cancellink" >Cancel</a></td></tr>');
						var curr_row = $("#hiderow").prev();
						curr_row.find('.rid').combobox();
						curr_row.find('.custom-combobox-input').autocomplete( "search", '' );
						curr_row.find('.custom-combobox-input').focus();
						bind();
					});
					
					//* regular validation
					gebo_validation.reg();
					//* datepicker
					gebo_datepicker.init();
					
					$('#tp_1').timepicker({
				defaultTime: '<?php echo $created_time; ?>',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown'
			});
			
			$('#tp_2').timepicker({
				defaultTime: '<?php echo $modified_time; ?>',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown'
			});
					
					
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
							generate_code('Auto_Format','SiteName','Code');
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
					
					$('#dp1 i.splashy-calendar_day').click(function(){
						$('#Created').datepicker( "show" );
					});
					$('#dp2 i.splashy-calendar_day').click(function(){
						$('#Modified').datepicker( "show" );
					});
					
					$(document).click(function(event){
						//console.log($(event.target).closest('div').attr('id'));
						if($(event.target).closest('div').attr('id')!='dp1') {
							$('#Created').datepicker( "hide" );
						}
						if($(event.target).closest('div').attr('id')!='dp2') {
							$('#Modified').datepicker( "hide" );
						}
					});	
								
			});
					
				//* bootstrap datepicker
				gebo_datepicker = {
					init: function() {
						//$('#Created').datepicker({"autoclose": true});
						//$('#Modified').datepicker({"autoclose": true});
						
						
						$('#Created').datepicker({"autoclose": true}).on('changeDate', function(ev){
							var arrStartDate= $('#Created').val().split('/');
							var dateText = new Date(arrStartDate[1]+'/'+arrStartDate[0]+'/'+arrStartDate[2]);
							console.log(dateText);
							var endDateTextBox = $('#Modified');
							if (endDateTextBox.val() != '') {
								var testStartDate = new Date(dateText);
								var arrEndDate= endDateTextBox.val().split('/');
								var testEndDate = new Date(arrEndDate[1]+'/'+arrEndDate[0]+'/'+arrEndDate[2]);
								console.log(testEndDate);
								if (testStartDate > testEndDate) {
									endDateTextBox.val($('#Created').val());
								}
							}
							else {
								endDateTextBox.val($('#Created').val());
							};
							$('#Modified').datepicker('setStartDate', dateText);
							
						});
						$('#Modified').datepicker({"autoclose": true}).on('changeDate', function(ev){
							var arrEndDate= $('#Modified').val().split('/');
							var dateText = new Date(arrEndDate[1]+'/'+arrEndDate[0]+'/'+arrEndDate[2]);
							console.log(dateText);
							var startDateTextBox = $('#Created');
							if (startDateTextBox.val() != '') {
								var arrStartDate= startDateTextBox.val().split('/');
								var testStartDate = new Date(arrStartDate[1]+'/'+arrStartDate[0]+'/'+arrStartDate[2]);
								console.log(testStartDate);
								var testEndDate = new Date(dateText);
								if (testStartDate > testEndDate) {
									startDateTextBox.val($('#Modified').val());
								}
							}
							else {
								startDateTextBox.val($('#Modified').val());
							};
							$('#Created').datepicker('setEndDate', dateText);
							
						});
						
						var arrEndDate= $('#Modified').val().split('/');
						var dateText = new Date(arrEndDate[1]+'/'+arrEndDate[0]+'/'+arrEndDate[2]);
						$('#Created').datepicker('setEndDate', dateText);
						
						var arrStartDate= $('#Created').val().split('/');
						var dateText = new Date(arrStartDate[1]+'/'+arrStartDate[0]+'/'+arrStartDate[2]);
						$('#Modified').datepicker('setStartDate', dateText);
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
								name: { required: true },
								Code: { required: true },
								host: { required: true },
								
								/*rootFolder: { required: true },
								adminEmail: { required: true },
								dns: { required: true },
								serverType: { required: true },*/
								siteType: { required: true },
								/*stagingServerType: { required: true },
								stagingRootDir: { required: true },*/
								/*versionNumber: { required: true },*/
								/*buildNumber: { required: true },*/
								Created: { required: true },
								Modified: { required: true },
								
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