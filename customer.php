<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');


if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from customers where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: customer.php");
	}
}

	// to update selected catgory
if(isset($_POST['submit']))
{ 


    $SiteID=$_POST["site_id"];
	$time=time();
	$CODE=$_POST["Code"];
	$Name=addslashes($_POST["name"]);
	$CityTown=addslashes($_POST["city_town"]);
	$state=addslashes($_POST["state"]);
	$Country=addslashes($_POST["country"]);
	$ZipPostCode=$_POST["zip_postal_code"];
	$Telephone=$_POST["tele_phn"];		
	$Fax=$_POST["fax_id"];
	$Email=$_POST["email_id"];
	$zStatus=$_POST["status"];
	
	$arr_pub_date=explode('/', $_POST["Sync_Modified"]);
	$dateregistered=$arr_pub_date[1].'/'.$arr_pub_date[0].'/'.$arr_pub_date[2];
	$dateregistered=date("Y-m-d",strtotime($dateregistered));
	
	$pub_time=$_POST["pub_time"];
	if($pub_time==''){
		$pub_time=date('h:i A');
	}
	$timeregistered=date("H:i:s",strtotime($pub_time));
	
	$address1=addslashes($_POST["address_1"]);
	$address2=addslashes($_POST["address_2"]);
	$auto_format=1;
		if(isset($_POST["chk_manual"])) {
		$auto_format=0;
		}
	$site_guid= $db->get_var("select GUID from sites where ID='$SiteID'");
    /*$time_arr=explode(" ",$_POST["time_picker"]);
	if($time_arr[1]=='PM')
	{
		$time_arr1=explode(":",$time_arr[0]);
		$hour=$time_arr1[0]+12;
		$timeregistered=$hour.":".$time_arr1[1].":00";
	}
	else
	{
		$timeregistered=$time_arr[0].":00";
	}	 */
		 
		 
		 if(isset($_GET['GUID'])){
		 
		 
		
		 
		if($db->query("UPDATE customers SET  SiteID='$SiteID',Site_GUID= '$site_guid',Created='$time',Modified = '$time',Code='$CODE',Name='$Name', 	Address1='$address1',Address2='$address2',CityTown='$CityTown',StateCounty='$state', 	 	Country='$Country',ZipPostCode='$ZipPostCode',Telephone='$Telephone',Fax='$Fax',Email='$Email', zStatus='$zStatus', DateRegistered='$dateregistered', TimeRegistered='$timeregistered', auto_format_code=$auto_format where  	GUID='".$_GET['GUID']."'")) {
		
		 
	  $_SESSION['up_message'] = "Updated successfully";
	 }
	 
	/*echo  "UPDATE customers SET  SiteID='$SiteID',Created='$time',Modified = '$time',Code='$CODE',Name='$Name', 	Address1='$address1',Address2='$address2',CityTown='$CityTown',StateCounty=$'state', 	 	Country='$Country',ZipPostCode='$ZipPostCode',Telephone='$Telephone',Fax='$Fax',Email='$Email', zStatus='$zStatus', 	DateRegistered='$dateregistered',auto_format_code=$auto_format where  	GUID='".$_GET['guid']."'";*/
	 
		//$db->debug();
	
	}
	
	
	  else
	{
	$GUID=UniqueGuid('customers', 'GUID');			  
	if($db->query("INSERT INTO customers (GUID,SiteID,Site_GUID,Created,Modified,Code,Name,Address1,Address2,CityTown,StateCounty, 	Country,ZipPostCode, 	Telephone,Fax,	Email, 	zStatus,DateRegistered, TimeRegistered, auto_format_code) VALUES ('$GUID','$SiteID','$site_guid','$time','$time','$CODE' ,'$Name','$address1','$address2','$CityTown','$state','$Country','$ZipPostCode','$Telephone','$Fax','$Email','$zStatus','$dateregistered', '$timeregistered', $auto_format)")) {
	
	 	$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:customers.php");
	   }
	
	/*echo "INSERT INTO customers (GUID,SiteID,Created,Modified,Code,Name,Address1,Address2,CityTown,StateCounty, 	Country,ZipPostCode, 	Telephone,Fax,	Email, 	zStatus,DateRegistered,auto_format_code) VALUES ('$GUID','$SiteID','$time','$time','$CODE' ,'$Name','$address1','$address2','$CityTown',$'state','$Country','$ZipPostCode','$Telephone','$Fax','$Email','$zStatus','$dateregistered',$auto_format)";*/
}

	}
 if(isset($_GET['GUID'])){
 
 
	          $user3 = $db->get_row("SELECT  GUID, SiteID,Created,Modified,Code,Name,Address1,Address2,CityTown,StateCounty,Country, 	ZipPostCode,Telephone,Fax,Email,zStatus,DateRegistered, TimeRegistered, auto_format_code  FROM customers where GUID = '".$_GET['GUID']."'");
       
	    			
					$GUID=$user3->GUID;
					$site_id=$user3->SiteID;
					$CODE=$user3->Code;
					$Name=$user3->Name;
					$address1=$user3->Address1;
					$address2=$user3->Address2;
					$CityTown=$user3->CityTown;
					$state=$user3->StateCounty;
					$Country=$user3->Country;
					$ZipPostCode=$user3->ZipPostCode;
					$Telephone=$user3->Telephone;
					$Fax=$user3->Fax;
					$Email=$user3->Email;
					$zStatus=$user3->zStatus;
					$DateRegistered=date('d/m/Y',strtotime($user3->DateRegistered));
					$time_string = date('h:i A',strtotime($user3->TimeRegistered));
					$auto_format=$user3->auto_format_code;
					
					/*$TimeRegistered=$user3->TimeRegistered;*/
	       
		   
		  }
		  
		  else
		  {
		  
		           $CODE='';
				   $site_id='';
				   $Name='';
				   $address1='';
				   $address2='';
				   $GUID='';
				   $CityTown='';
				   $state='';
				   $Country='';
				   $ZipPostCode='';
				   $Telephone='';
				   $Fax='';
				   $Email='';
				   $zStatus='';
				   $DateRegistered=date('d/m/Y');
				   $time_string=date("h:i A");
				   $auto_format=1;
				   
			  /* $TimeRegistered='';*/
			  
			   
				   $where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
		  }
		    
		   
		/* $db->debug(); */ 
 ?>
<script type="text/javascript">
function generateCode()
{
	
	var status=document.getElementById('chk_manual').checked;
	if(status!=true){
	var val=document.getElementById('email_id').value;
	
	document.getElementById('Code').value=val;
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
                         <div id="jCrumbs" class="breadCrumb module">
	<ul>
		<li>
			<a href="home.php"><i class="icon-home"></i></a>
		</li>
		<li>
			<a href="index.php">Dashboard</a>
		</li>
		<li>
			<a href="customers.php">Customers</a>
		</li>
		<li>
			<a href="#">Customer</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                    
                    <div class="row-fluid">
                       <h3 class="heading"><?php if(isset($_GET['GUID'])) { echo  'Update '.$Name; } else { echo 'Add New customer'; } ?> </h3> 
                        
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br>
							<div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Customer</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Customer Details</a></li>
									<!--<li id="li3"><a href="#tab3" data-toggle="tab">Categories</a></li>-->
									<li id="li3"><a href="#tab3" data-toggle="tab">Additional Information</a></li>
								</ul>
							<form action="" method="post" class="form_validation_reg" enctype="multipart/form-data"> 
								<div class="tab-content">
								
									<div class="tab-pane active" id="tab1">

                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											
											
												
										<div class="control-group">
												<label class="control-label">Telephone<span class="f_req">*</span></label>
												<div class="controls">
													
												
												<input type="text" name="tele_phn" class="span12" value="<?php echo $Telephone; ?>"  />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Name<span class="f_req">*</span></label>
												<div class="controls">
													<input type="hidden" value="<?php echo $GUID; ?>" name="GUID" id="GUID" >
												
													<input type="text" name="name" class="span12" id="Name" value="<?php echo $Name; ?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Address 1</label>
												<div class="controls">
												
<textarea name="address_1" id="address_1" rows="5" cols="10" ><?php echo $address1; ?></textarea>													
													<!--<span class="help-block">&nbsp;</span>-->
												</div>
											</div>
											<br/>
											<div class="control-group">
												<label class="control-label">Address 2</label>
												<div class="controls">
												
<textarea name="address_2" id="address_2" rows="5" cols="10" ><?php echo $address2; ?></textarea>
													
													<!--<span class="help-block">&nbsp;</span>-->
												</div>
											</div>
												
											<div class="control-group">
												<label class="control-label">City<span class="f_req">*</span></label>
												<div class="controls">
												
<input type="text" name="city_town" class="span12" value="<?php echo $CityTown; ?>" />
<span class="help-block"></span>
									</div>
									</div>
										
										<div class="control-group">
												<label class="control-label">State</label>
												<div class="controls">
												
	<input type="text" name="state" class="span12" value="<?php echo $state; ?>" />									</div>
									</div>
									
									
									
									
										</fieldset>
									</div>
                                </div>
                               

                            </div>

                        </div>
                    </div>
                        


									</div>
									
									
									
									<div class="tab-pane" id="tab2">
										


                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											
											<div class="control-group">
												<label class="control-label">Fax</label>
												<div class="controls">
												
	<input type="text" name="fax_id" class="span12" value="<?php echo $Fax; ?>" />
	<span class="help-block"></span> </div>
									</div>
											<div class="control-group">
												<label class="control-label">Zip Postal Code<span class="f_req">*</span></label>
												<div class="controls">
												
		<input type="text" name="zip_postal_code" class="span12"   value="<?php echo $ZipPostCode; ?>"  />
		<span class="help-block"></span></div>
									</div>
									
											<div class="control-group">
												<label class="control-label">Country<span class="f_req">*</span></label>
												<div class="controls">
												
	<input type="text" name="country" class="span12"   value="<?php echo $Country; ?>"  />
	<span class="help-block"></span>
								</div>
									</div>
											
											<div class="control-group">
												<label class="control-label">Email<span class="f_req">*</span></label>
												<div class="controls">
													
											<input type="text" name="email_id" id="email_id" class="span12" onKeyUp="generateCode()" onBlur="generateCode()"  value="<?php echo $Email; ?>" /> 
											<span class="help-block"></span>
													
												</div>
												
												
																							
													

											</div>
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
                    </div>


										
									</div>
									
									
									
									
									
									
																		<div class="tab-pane" id="tab3">
										
											<div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											
											<div class="control-group">
												<label class="control-label">Status<span class="f_req">*</span> </label>
												<div class="controls">
													
													
											<label class="radio inline">
												<input type="radio" value="ACTIVE" name="status" <?php if($zStatus == 'ACTIVE' || $zStatus == '') { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="INACTIVE" name="status" <?php if($zStatus == 'INACTIVE') { echo ' checked'; } ?>/> Inactive
											</label>
												</div>
											</div><br/>
											
											
									<div class="control-group">
										
											<label class="control-label">Date<span class="f_req">*</span></label>
											<div class="controls">
											<div class="input-append date" id="dp2" >
												<input class="input-small" placeholder="DateTime" type="text" readonly="readonly"  name="Sync_Modified" id="Sync_Modified"  value="<?php echo $DateRegistered; ?>" data-date-format="dd/mm/yyyy" ><span class="add-on"><i class="splashy-calendar_day"></i></span>
											</div>
											
											<div>
									<span class="help-block">&nbsp;</span>
									<input type="text" class="span3" id="tp_2" name="pub_time" value="<?php echo $time_string; ?>" readonly="readonly" />
								<span class="help-block">&nbsp;</span>
								</div>
											
										</div>
									
								</div>	
								
								<!-- Site dropdown starts here-->
								<?php include_once("sites_dropdown.php"); ?>
								<!-- Site dropdown ends here-->
										
										<div class="control-group">
												<label class="control-label">Code<span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" class="span10" name="Code" id="Code" <?php if($auto_format!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $CODE;?>" />
													<!--<span class="help-block">URL (SEO friendly)</span>-->
													<span class="help-block">
													<input type="checkbox" name="chk_manual" id="chk_manual" value="0" <?php if($auto_format==0) { ?> checked="checked" <?php } ?>  />
													I want to manually enter code</span>

												

												</div>
											</div><br/><br/>
																			
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
                    </div>
										
									</div>
									
									
									
								</div>
								
								
								
									
								<button class="btn btn-gebo" type="submit" name="submit" id="submit" >Save changes</button>
								
								
								
								
								</form>
                        </div>
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
			<!-- timepicker -->
            <script src="lib/datepicker/bootstrap-timepicker.min.js"></script>
            <!-- validation functions -->
			
			<script>
			var val_flag=0;
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
					$('#tp_2').timepicker({
				defaultTime: '<?php echo $time_string; ?>',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown'
			});
					
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
							generateCode();
						}
					
					});
					
					$('.splashy-calendar_day').click(function(){
						$('#Sync_Modified').datepicker( "show" );
					});
					
					$(document).click(function(event){
						//console.log($(event.target).closest('div').attr('id'));
						if($(event.target).closest('div').attr('id')!='dp2') {
							$('#Sync_Modified').datepicker( "hide" );
						}
					});	
					
				});
				//* bootstrap datepicker
				gebo_datepicker = {
					init: function() {
						$('#Sync_Modified').datepicker({"autoclose": true});
					}
				};
				//* bootstrap timepicker
				/*gebo_timepicker = {
					init: function() {
						$('#tp_2').timepicker({
							defaultTime: 'current',
							minuteStep: 1,
							disableFocus: true,
							template: 'dropdown'
						});
					}
				};*/
				//* validation
				gebo_validation = {
					
					reg: function() {
						reg_validator = $('.form_validation_reg').validate({
							onkeyup: false,
							errorClass: 'error',
							validClass: 'valid',
							highlight: function(element) {
								$(element).closest('div').addClass("f_error");
								var err_div_id=$(element).closest('div.tab-pane').attr('id');
								if($("#"+err_div_id).hasClass("active")){
								//$(element).closest('div').addClass("f_error");
								val_flag=1;
								}
								
					else if(!$("#"+err_div_id).hasClass("active") && val_flag==0){
					//$(element).closest('div').addClass("f_error");
					for(var i=1; i<=3; i++) {
						if(err_div_id=="tab"+i){
							$("#tab"+i).addClass("active");
							$("#li"+i).addClass("active");

						}
						else {
							$("#tab"+i).removeClass("active");
							$("#li"+i).removeClass("active");
						}
					}
					}
							},
							unhighlight: function(element) {
								$(element).closest('div').removeClass("f_error");
								val_flag=0;
							},
							errorPlacement: function(error, element) {
								$(element).closest('div').append(error);
							},
							rules: {
							    site_id:{ required: true },
								 	Code: { required: true },
								name: { required: true },
								city_town: { required: true },
								country: { required: true },
								zip_postal_code: { required: true },
								tele_phn: { required: true },
								 	email_id: { required: true },
								 	status: { required: true },
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