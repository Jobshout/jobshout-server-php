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
				
						$code = $_POST["code"];
						$email = $_POST["email"];
						
						$sql= $db->get_var("SELECT count(*) FROM wi_users WHERE code='$code'");
						
						if($sql != 0)
					{
						$msg_em = "This user name is already exist";
						}
						
						else {
						
						$sql= $db->get_var("SELECT count(*) FROM wi_users WHERE email='$email'");
						
						if($sql != 0)
					{
						$msg_us = "This email address is already exist";
						} 
				 
						else
					{
					
		
					if($_POST['GUID'] != '' && $_POST['code'] !='' && $_POST['firstname'] !='' && $_POST['lastname'] !='' && $_POST['gender'] !='' && $_POST['email'] !='' && $_POST['languages'] !='' && $_POST['notification'] !='' && $_POST['signature'] !='' && $_POST['fullname'] !='' && $_POST['password'] !='')
					{
					$time= date("Y-m-d H:i:s");
					$unix_timestamp= strtotime($time);
					//echo $unix_timestamp;
							
					$noti = '';
					if(isset ($_POST['notification']))
					{
						foreach($_POST['notification'] as $var)
						{
							if($noti=='')
							$noti.= $var;
							else
							$noti.= ','. $var;
							}
						}
						
							
									
					$GUID = $_POST["GUID"];
					$code = $_POST["code"];
					$firstname = $_POST["firstname"];
					$lastname = $_POST["lastname"];
					$gender = $_POST["gender"];
					$email = $_POST["email"];
					$languages = $_POST["languages"];
					$notification = $_POST["notification"];
					$signature = $_POST["signature"];
					$fullname = $_POST["fullname"];
					$password = $_POST["password"];
					
					$encrypted_mypassword=md5($password);
					
					//echo "INSERT INTO wi_users (uuid, SiteID, code, firstname, lastname, gender, email, server, created, modified, languages, notifications_code, signature, fullname, password,photo_avatar,photo_type,access_rights_code,status)  VALUES ('$GUID','".SITE_ID."','$code','$firstname','$lastname','$gender','$email','".SERVER_NUMBER."','$unix_timestamp','$unix_timestamp','$languages','$noti','$signature','$fullname','$password','','','1','1')";
					
					
					
					
			$db->query("INSERT INTO wi_users (uuid, SiteID, code, firstname, lastname, gender, email, server, created, modified, languages, notifications_code, signature, fullname, password,photo_avatar,photo_type,access_rights_code,status)  VALUES ('$GUID','".SITE_ID."','$code','$firstname','$lastname','$gender','$email','".SERVER_NUMBER."','$unix_timestamp','$unix_timestamp','$languages','$noti','$signature','$fullname','$encrypted_mypassword','','','1','1')");
			//$db->debug();
			
		
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
									//echo 'hi';
									//echo "update wi_users set photo_avatar='$content' where uuid='$GUID'";
									$db->query("update wi_users set photo_avatar='$content', photo_type='$fileType' where uuid='$GUID'");
									//$db->debug();
									//echo 'hello';
								
								
								
								}
								
								$sec_msg= "User successfully added";
								
							}
				}
			
			 }
			 
			 //echo $sql = "insert into wi_users set uuid = '$GUID', code = '$code', firstname = '$firstname', lastname = '$lastname', gender = '$gender', email = '$email', server = '".SERVER_NUMBER."', created = NOW(), languages = '$languages', signature = '$signature', fullname = '$fullname', password = '$password'";
		
		//echo "insert into wi_users set uuid = '$GUID', code = '$code', firstname = '$firstname', lastname = '$lastname', gender = '$gender', email = '$email', server = '".SERVER_NUMBER."', created = NOW(), modified = NOW(), photo_avatar = '$photo_avatar', languages = '$languages', notification_code = '$noti', signature = '$signature', fullname = '$fullname', password = '$password', access_rights_code = '$access_rights_code'";
		//($sql);
	   // $uuid= mysql_insert_id();	
		
	
}

require_once('include/main-header.php'); 
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
                        <?php require_once('include/breadcrum.php'); ?>
                    </nav>
                    
                    <div class="row-fluid">
						<div class="span12">
							<h3 class="heading">Add New User </h3>
							<div id="validation" style="padding-left: 200px;"><span style='color: red'><?php if (isset($msg_em)) echo $msg_em; ?></span></div>
							<div id="validation" style="padding-left: 200px;"><span style='color: red'><?php if (isset($msg_us)) echo $msg_us; ?></span></div>
							<div id="validation" style="padding-left: 200px;"><span style='color: red'><?php if (isset($sec_msg)) echo $sec_msg; ?></span></div>
							<div align="right"> <a href="users.php">user list</a></div>
							<div class="row-fluid">
							
							
								<div class="span8">
									<form class="form-horizontal" name="form1" id="form1" enctype="multipart/form-data" method="post" onSubmit="return(validate());">
										<fieldset>
											<div class="control-group formSep">
												<label class="control-label">Username</label>
												<div class="controls text_line">
													<input type="hidden" value="<?php $Guid = NewGuid(); echo $Guid; ?>" name="GUID" id="GUID" >
													<input type="text"  name="code" id="code" class="input-xlarge" value="">
												</div></div>
												
												
												
												
												
												
												
												
												
											<div class="control-group formSep">
												<label for="fileinput" class="control-label">User avatar</label>
												<div class="controls">
													<div data-fileupload="image" class="fileupload fileupload-new">
														<input type="hidden" />
														<div style="width: 80px; height: 80px;" class="fileupload-new thumbnail"><img src="http://www.placehold.it/80x80/EFEFEF/AAAAAA" alt="" width="80" height="80" id="usr_img" /></div>
														<div style="width: 80px; height: 80px; line-height: 80px;" class="fileupload-preview fileupload-exists thumbnail"></div>
														<span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span><input type="file" id="fileinput" name="fileinput" /></span>
														<a data-dismiss="fileupload" class="btn fileupload-exists" href="#">Remove</a>
													</div>	
												</div>
											</div>
											
											
											
											
												
												
												
												<div class="control-group formSep">
												<label class="control-label">First Name</label>
												<div class="controls text_line">
													
													<input type="text"  name="firstname" id="firstname" class="input-xlarge" value="">
												</div></div>
												
												<div class="control-group formSep">
												<label class="control-label">Last Name</label>
												<div class="controls text_line">
													
													<input type="text"  name="lastname" id="lastname" class="input-xlarge" value="">
												</div></div>
												
												
												<div class="control-group formSep">
												<label class="control-label">Gender</label>
												<div class="controls">
													<label class="radio inline">
														<input type="radio" value="male" id="s_male" name="gender"  />
														Male
													</label>
													<label class="radio inline">
														<input type="radio" value="female" id="s_female" name="gender" />
														Female
													</label>
												</div>
											</div>
												
												
												<div class="control-group formSep">
												<label class="control-label">Email</label>
												<div class="controls text_line">
													
													<input type="text"  name="email" id="email" class="input-xlarge" value="">
												</div></div>
												
												
												
												
												
												<div class="control-group formSep">
												<label class="control-label">Language(s)</label>
												<div class="controls">
													<select name="languages" id="languages"  class="span5">
														<option value="">Select</option>
														<option value="English">English</option>
														<option value="French">French</option>
														<option value="German">German</option>
														<option value="Italian">Italian</option>
														<option value="Chinese">Chinese</option>
														<option value="Spanish">Spanish</option>
													</select>
												</div>
											</div>
												
												
												
												
												<div class="control-group formSep">
												<label class="control-label">I want to receive:</label>
												<div class="controls">
											
													<label class="checkbox inline">
													
													<input type="checkbox" value="1" id="email_newsletter" name="notification[]"  />
														
														Newsletters
													</label>
													<label class="checkbox inline">
													<input type="checkbox" value="2" id="email_sysmessages" name="notification[]"  />
														
														System messages
													</label>
													<label class="checkbox inline">
													<input type="checkbox" value="3" id="email_othermessages" name="notification[]"  />
														
														Other messages
													</label>
												</div>
											</div>
											       
											
											
											
											
												<div class="control-group formSep">
												<label for="u_signature" class="control-label">Signature</label>
												<div class="controls">
													<textarea rows="4" id="signature" name="signature" class="input-xlarge"></textarea>
													<span class="help-block">Automatic resize</span>
												</div>
											</div>
											
											
											<div class="control-group formSep">
												<label class="control-label">Full Name</label>
												<div class="controls text_line">
													
													<input type="text"  name="fullname" id="fullname" class="input-xlarge" value="">
												</div></div>
												
												
												
												<div class="control-group formSep">
												<label class="control-label">Password</label>
												<div class="controls text_line">
													
													<input type="password"  name="password" id="password" class="input-xlarge" value="">
												</div></div>
												
												
												
												
												
											
																						<div class="control-group">
												<div class="controls">
													<button class="btn btn-gebo" type="submit" name="submit" id="submit">Submit</button>
												
												</div>
											</div>
										</fieldset>
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
            
          <script src="lib/datatables/jquery.dataTables.min.js"></script>
            <script src="lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
			<!-- datatable functions -->
            <script src="js/gebo_datatables.js"></script>
       
	   
	   
				   
			<script type="text/javascript">
					
					
			function validateEmail()
			{
			 
			   var emailID = document.form1.email.value;
			   var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
			   if (!filter.test(emailID))
			   {
				   document.getElementById("validation").innerHTML = "<span style='color: red'> Please enter the correct Email Id<span>" ;
				   document.form1.email.focus() ;
				   return false;
			   }
			   return( true );
			}
			
			function validate()
			{
			   if( document.form1.code.value == "" )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please enter your user name<span>" ;
				 document.form1.code.focus() ;
				 return false;
			   }
			   
			   
			   
			   if( document.form1.firstname.value == "" )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please enter your first name<span>" ;
				 document.form1.firstname.focus() ;
				 return false;
			   }
			   
			   
			    
			   if( document.form1.lastname.value == "" )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please enter your last name<span>" ;
				 document.form1.lastname.focus() ;
				 return false;
			   }
			   
			   
			    if( document.getElementById("s_male").checked!=true &&  document.getElementById("s_female").checked!=true )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please select gender <span>" ;
				 
				 return false;
			   }
			   
			   
			   
			  
			   if( document.form1.email.value == "" )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please enter  E-Mail<span>" ;
				 document.form1.email.focus() ;
				 return false;
			   }else{
				 // Put extra check for data format
				 var ret = validateEmail();
				 if( ret == false )
				 {
					  return false;
				 }
			   }
			   
			   
					   
			   if( document.form1.languages.value == "" )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please select a language<span>" ;
				 document.form1.languages.focus() ;
				 return false;
			   }
			   
			   
			   
			    if( document.getElementById("email_newsletter").checked!=true &&  document.getElementById("email_sysmessages").checked!=true &&  document.getElementById("email_othermessages").checked!=true )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please select massage you want to receive <span>" ;
				 
				 return false;
			   }
			   
			   
			   if( document.form1.signature.value == "" )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please enter signature<span>" ;
				 document.form1.signature.focus() ;
				 return false;
			   }
			   
			   
			     if( document.form1.fullname.value == "" )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please enter your full name<span>" ;
				 document.form1.fullname.focus() ;
				 return false;
			   }
			   
			   
			    if( document.form1.password.value == "" )
			   {
				 document.getElementById("validation").innerHTML = "<span style='color: red'>* Please enter password<span>" ;
				 document.form1.password.focus() ;
				 return false;
			   }
			   
			   	   
			   return( true );
			}
			
			$(document).ready(function(){
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
			});
			</script>
		
		</div>
	</body>
</html>


