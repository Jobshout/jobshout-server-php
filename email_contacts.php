 <?php 
session_start();
if($_SESSION['UserEmail'] =='') {
       header("location:index.php");
}
require_once("connect.php"); 
require_once("constants.php");
date_default_timezone_set('Europe/London');
require_once("../include/config_mailer.php");
require_once('include/main-header.php');

	
				
if(isset($_POST['send']))
{	
	$to_emails=explode(",",$_POST["to"]);
	$to_names=explode(",",$_POST["to_name"]);
	$from=$_POST["from"];
	$subject=$_POST["subject"];	
	$mail_content=$_POST["mail_body"];
	
include_once("../include/config_mailer.php");
	
	for($i=0; $i<count($to_emails);$i++){
		$mail->AddAddress($to_emails[$i],$to_names[$i]);
	}
	
	$mail->SetFrom($from, 'Tenthmatrix');
	$mail->Subject = $subject;
	$message=$mail_content;
	$mail->MsgHTML($message);
	$mail->Send();
	$mail->ClearAddresses();
	
	$succ_msg="Mail sent successfully !";					
}		

			

$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
}
 ?>
    </head>
    <body>
		<div id="maincontainer" class="clearfix">
			
            
            <!-- main content -->
            <div id="contentwrapper">
                <div class="main_content">
                   
					
					<h3 class="heading">Email Contacts</h3>
					
					<div ><span style="color:#00CC00;font-size:18px;">
					<?php if(isset($succ_msg) && $succ_msg!=''){ echo $succ_msg; }?>
					</span></div>
					
 
 <br/>
							
                    <div class="row-fluid">
						<div class="span12">
							
							
							
							
							
									<form name="form1" id="form1" class="form-horizontal form_validation_reg" action="" enctype="multipart/form-data" method="post" >
										<fieldset>
										
													
											<div class="control-group formSep">
												<label class="control-label">To<span class="f_req">*</span></label>
												<div class="controls text_line">
													<?php
													$emails='';
													$names='';
													if($sql_contacts=$db->get_results("select Email,Name from contacts where GUID in (select uuid_contact from  wi_mailinglist_contacts where uuid_mailinglist = '".$_GET['list_id']."' ) $where_cond")){
													foreach($sql_contacts as $contact){
														if($emails==''){
															$emails.=$contact->Email;
															$names.=$contact->Name;
														}
														else{
															$emails.=", ".$contact->Email;
															$names.=", ".$contact->Name;
														}
													
													}
													}
													?>
													
													<textarea name="to" id="to" class="input-xlarge" rows="3" readonly="readonly" style="width:500px"><?php echo $emails; ?></textarea>
													<input type="hidden"  name="to_name" id="to_name" class="input-xlarge" value="<?php echo $names; ?>">
													<span>&nbsp;</span>
												</div></div>
																					
												<div class="control-group formSep">
												<label for="u_signature" class="control-label">From<span class="f_req">*</span></label>
												<div class="controls">
													<input type="text"  name="from" id="from" class="input-xlarge" value="list@tenthmatrix.co.uk">
													<span>&nbsp;</span>
													<span class="help-block"></span>
												</div>
											</div>
											
											<div class="control-group formSep">
												<label class="control-label">Subject <span class="f_req">*</span></label>
											<div class="controls">
													<input type="text"  name="subject" id="subject" class="input-xlarge" value="">
													<span>&nbsp;</span>
													<span class="help-block"></span>
												</div>
												</div>
												
											<div class="control-group formSep">
												<label class="control-label">Template </label>
											<div class="controls">
													<select onChange="" name="template" id="template">
												<option value="">-- Select Mail Template --</option>
												<?php
												if($sql_temp=$db->get_results("select GUID,Name from templates where Class='Email' $where_cond and status='1'")){
												foreach($sql_temp as $temp){
												?>
												<option value="<?php echo $temp->GUID; ?>"><?php echo $temp->Name; ?></option>
												<?php
												}
												}
												?>
												</select>
													<span>&nbsp;</span>
													<span class="help-block"></span>
												</div>
												</div>
									
											<div class="control-group formSep">
												<label for="u_signature" class="control-label">Body<span class="f_req">*</span></label>
												<div class="controls">
													<textarea rows="4" id="mail_body" name="mail_body" class="input-xlarge"></textarea>
													<span>&nbsp;</span>
													<span class="help-block"></span>
												</div>
											</div>
											
											
									<div class="control-group">
												<div class="controls">
													<button class="btn btn-gebo" type="submit" name="send" id="submit">Send</button>
													<button class="btn btn-gebo" type="button" name="cancel" id="submit" onClick="window.close()">Cancel</button>
												</div>	
												</div>
											
										
										</fieldset>
									</form>
									
								
												</div>											
												
					  </div>
				  </div>
			  </div>
					</div>
                        
               
            
			
			
			 <?php require_once('include/footer.php');?>
          
			  
		             
            <script src="lib/validation/jquery.validate.min.js"></script>
           
			
			  <script type="text/javascript">
			$(document).ready(function(){
			
			$('#submit').click(function(){
				tinyMCE.triggerSave();
				
			});
			
			$('#template').change(function(){
				var temp_id=$(this).val();
				if(temp_id!=''){
					$.ajax({
						type: "POST",
						url: "get_template.php",
						data: "temp_id=" + temp_id ,
						success: function(response){
							//alert(response);
							//$("#mail_body").html(response);
							tinyMCE.activeEditor.setContent(response);
						}
					 	});	
				}
			});
			
				$.validator.setDefaults({
        ignore: ""
    });
			gebo_validation.reg();
					//* datepicker
								
			});
			//* validation
				gebo_validation = {
					
					reg: function() {
						reg_validator = $('#form1').validate({
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
								to: { required: true },
								from: { required: true },
								subject: { required: true },
								mail_body: { required: true },
								
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
	selector: "textarea#mail_body",
   
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
