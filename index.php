<?php

if(isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='hh4.jobshout.co.uk') {
       header("location: http://cms.jobshout.com/");
	   exit;
}

require_once("connect.php");
require_once("constants.php");
require_once("include/functions.php");
include_once("../include/config_mailer.php");

session_start();

if(isset($_SESSION['UserEmail']) && $_SESSION['UserEmail']!='') {
       header("location:home.php");
	   exit;
}


if(isset($_POST['SignIn'])){

//$encrypted_mypassword=md5($password);
	$LoginEmail = $_POST['username'];	
	$LoginPassword = $_POST['password'];
	$site_code = $_POST['site_code'];	
	
	$pass=$LoginPassword;
	$encrypted_mypassword=md5($LoginPassword);
	
	$LoginQuery = "SELECT * FROM wi_users WHERE code='$LoginEmail' AND password='$encrypted_mypassword' and status='1'";
	//echo $LoginQuery; exit;

	$LoginData = $db->get_row($LoginQuery);
		
	// $db->debug();
	if($db->num_rows>0){
		$user_guid=$LoginData->uuid;
	
		$site_details=$db->get_row("select * from sites where code='$site_code' and (GUID in (select uuid_site from wi_user_sites where uuid_user='$user_guid') OR GUID = '".$LoginData->Site_GUID."' or ID = '".$LoginData->SiteID."') ");
		
		if($LoginData->access_rights_code<11 && $site_code!='' && !($site_details)){
			$error = 'You don\'t have access to this site. Try again with different site.';
		}
		else{
	
			if($site_code=='' && isset($_COOKIE['sitecode']) && $_COOKIE['sitecode']!=''){
				$site_code = $_COOKIE['sitecode'];
			}
		
			if (isset($_POST['rememberme'])) {
				/* Set cookie to last 1 year */
				/*setcookie('username', $LoginEmail, time()+60*60*24*365);
				setcookie('password', $pass, time()+60*60*24*365);*/
				//setcookie('sitecode', $site_code, time()+60*60*24*365);
			
			} else {
				/* Cookie expires when browser closes */
			   /* setcookie('username', $LoginEmail, time() - 3600);
				setcookie('password', $pass, time() - 3600);*/
				//setcookie('sitecode', $site_code, time() - 3600);
			}
		
			
			
			$_SESSION['UserEmail'] = $LoginEmail;
			$_SESSION['isLoggedIn'] = true;
			
			$site_id='';
			if($site_code!=''){
				if($LoginData->access_rights_code>=11){
					$site_details=$db->get_row("select * from sites where code='$site_code'");
				}
				else{
					$site_details=$db->get_row("select * from sites where code='$site_code' and (GUID in (select uuid_site from wi_user_sites where uuid_user='$user_guid') OR GUID = '".$LoginData->Site_GUID."' or ID = '".$LoginData->SiteID."') ");
				}
				if($site_details){
					$site_id= $site_details->ID;
					$site_rootpath= $site_details->RootDirectory;
					setcookie('sitecode', $site_code, time()+60*60*24*365);
					if($site_rootpath!=''){
						setcookie('sitedir', $site_rootpath, time()+60*60*24*365);
					}
					else{
						setcookie('sitedir', $site_code, time()+60*60*24*365);
					}
					$_SESSION['site_id']=$site_id;	
				}
			}
			if($site_id=='' && ($LoginData->Site_GUID!='' || $LoginData->SiteID!='')){
				
				if($sql_site=$db->get_row("select * from sites where GUID = '".$LoginData->Site_GUID."' or ID = '".$LoginData->SiteID."'")){
					$site_id= $sql_site->ID;
					$site_rootpath= $sql_site->RootDirectory;
					$site_code= $sql_site->Code;
					setcookie('sitecode', $site_code, time()+60*60*24*365);
					if($site_rootpath!=''){
						setcookie('sitedir', $site_rootpath, time()+60*60*24*365);
					}
					else{
						setcookie('sitedir', $site_code, time()+60*60*24*365);
					}
					$_SESSION['site_id']=$site_id;	
				}	
			}
			elseif($site_id==''){
				
				if($sql_site=$db->get_results("select id from sites where GUID in (select uuid_site from wi_user_sites where uuid_user='$user_guid')")){		
					foreach($sql_site as $site){
						$site_arr[]=$site->id;
					}
					$_SESSION['site_id']=implode("','",$site_arr);
				}
			}
			if(isset($_POST['referrer']) && $_POST['referrer']!=''){
				$base_name= basename($_POST['referrer']);
				$first_pos = strpos($base_name, "?");
				$prev_section =substr($base_name,0, $first_pos);
				if($prev_section=="reset-password.php"){
					header("Location:home.php");
				}else{
					header("Location:".$_POST['referrer']);
				}
			}
			else{
				header("Location:home.php");
			}
		}
		
	}else{
		$error = 'Invalid username or password';
	}
}

elseif(isset($_POST['register']))
{

	$GUID = UniqueGuid('wi_users', 'uuid');
	$code = $_POST["reg_uname"];
	$firstname =$_POST["reg_fname"];
	$lastname = $_POST["reg_lname"];
	$gender = '';
	$email = $_POST["reg_email"];
	$languages = '';
	$noti = '';
	$signature = '';
	$fullname = '';
	$password = $_POST["reg_pass"];
	$site_id = $_POST["site_id"];

	
	$encrypted_mypassword=md5($password);
	
	$time= date("Y-m-d H:i:s");
	$unix_timestamp= strtotime($time);
	
	$sql= $db->get_var("SELECT count(*) FROM wi_users WHERE code='$code'");
						
	if($sql != 0)
	{
		$reg_message = "Please enter another username";
	}
						
	else 
	{						
		$sql= $db->get_var("SELECT count(*) FROM wi_users WHERE email='$email'");
						
		if($sql != 0)
		{
			$reg_message = "Please enter another email address";
		} 				 
		else
		{
			$site_guid=$db->get_var("select GUID from sites where id=$site_id");
			$token_guid=UniqueGuid('wi_tokens', 'uuid');	
			if($db->query("INSERT INTO wi_users (uuid, SiteID, Site_GUID, code, firstname, lastname, gender, email, server, created, modified, languages, notifications_code, signature, fullname, password,photo_avatar,photo_type,access_rights_code,status)  VALUES ('$GUID','".$site_id."', '".$site_guid."', '$code', '$firstname', '$lastname', '$gender','$email','".SERVER_NUMBER."','$unix_timestamp','$unix_timestamp','$languages','$noti','$signature','$fullname','$encrypted_mypassword','','','1',0)")){
			
				$db->query("INSERT INTO wi_tokens (uuid, user_uuid, status)  VALUES ('$token_guid','$GUID',0)");
				$uuid=UniqueGuid('wi_user_sites', 'uuid');	
				
				$db->query("insert into wi_user_sites(uuid, uuid_user, uuid_site, created, modified, server) values('$uuid','$GUID','$site_guid','$unix_timestamp','$unix_timestamp',4)");
				
				
				
				$mail->AddAddress($email);
				$mail->SetFrom('nehak189@gmail.com', 'Jobshout');
				$mail->Subject = "Registration Confirmation Mail";
				$message="You are registered successfully.<br /> To activate your account, click on the following link <br />
				<a href='".SITE_WS_PATH."/activate_user.php?token=".$token_guid."'>Activate your account</a>";
				$mail->MsgHTML($message);
				$mail->Send();
				$mail->ClearAddresses();
				
				//$admin_mail=$db->get_var("select email from wi_users where access_rights_code=11");
				$admin_mail='jobshout421@googlemail.com';
				$site_name=$db->get_var("select name from sites where id=$site_id");
				$mail->AddAddress($admin_mail);
				$mail->SetFrom('nehak189@gmail.com', 'Jobshout');
				$mail->Subject = "New user has been registered";
				$message1="Dear Admin, <br> ";
				  $message1.="A new user has requested for the admin access of <b>".$site_name."</b><br>";
				  $message1.="User name   : ".$code."  <br>";
				  $message1.="Email address: ".$email."  <br>";
				  $message1.="Site: ".$site_name."  <br>";
				  $message1.=" User Profile: <a href='".SITE_WS_PATH."/user.php?uuid=".$GUID."'>Click here</a>";
				$mail->MsgHTML($message1);
				
				//$message="<b>".$code."</b> has requested for the admin access of <b>".$site_name."</b>";
				//$mail->MsgHTML($message);
				$mail->Send();
				$mail->ClearAddresses();

				$reg_success_message="You are registered successfully !";
			}
		}
	}

}

elseif(isset($_POST['req_password']))
{
	
	$email = $_POST["req_email"];
		
	$token_guid=UniqueGuid('wi_tokens', 'uuid');	
	if($user_uuid= $db->get_var("SELECT uuid FROM wi_users WHERE email='$email'")){
		
		$db->query("INSERT INTO wi_tokens (uuid, user_uuid, status)  VALUES ('$token_guid', '$user_uuid', 1)");
			
		include("include/config_mailer.php");
		
		$mail->AddAddress($email);
				$admin_mail='jobshout421@googlemail.com';
				$mail->AddAddress($admin_mail);
		$mail->SetFrom('cms@jobshout.com', 'Jobshout');
		$mail->Subject = "Reset your password";
		$message="You can reset your password from 
		<a href='".SITE_WS_PATH."/reset-password.php?token=".$token_guid."'>here</a>";
		$mail->MsgHTML($message);
		$mail->Send();
		$mail->ClearAddresses();
	
		$req_message="Please check your mails for the link to reset your password !";
	}
	else{
		$req_message="You are not registered. Please register first !";
	}
		
}
?>

<?php require_once("index-header.php"); ?>
		
		<script src="js/jquery.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			<?php if(isset($reg_message) || isset($reg_success_message)) { ?>
			$('#login_form').hide();
			$('#reg_form').show();
			$('.links_btm .linkform').toggle();
			<?php } ?>
			<?php if(isset($req_message)) { ?>
			$('#login_form').hide();
			$('#reg_form').hide();
			$('#pass_form').show();
			$('.links_btm .linkform').toggle();
			<?php } ?>
		});
		</script>
		
<style>
label.error{
margin-top:4px;
display: block;
font-size: 11px;
font-weight: 700;
color: #C62626;
}
.login_box{
height:auto!important;
}
.custom-combobox-input {
width:auto;
}
</style>
		
    </head>
    <body class="login_page">
		
		<div class="login_box" >
			
			<form action="" method="post" id="login_form">
			<input type="hidden" id="referrer" name="referrer" value="<?php if(isset($_SERVER['HTTP_REFERER'])){ echo $_SERVER['HTTP_REFERER']; } ?>" />
				<div class="top_b">Sign in to Jobshout Admin</div>    
				<div class="alert alert-info alert-login">
					Note this is a secure area. Please check <b>Remember me</b> only from your personal computer or device!
				</div>
				
				<div class="cnt_b">
				<?php if(isset($_SESSION['activate_msg']) && $_SESSION['activate_msg']!='') { ?>
				<div class="formRow" style="color:#009900">
				<?php echo $_SESSION['activate_msg']; ?>
				</div>
				<?php $_SESSION['activate_msg']=''; } ?>
				
				<?php if(isset($error) && $error!='') { ?>
				<div class="formRow" style="color:#FF0000">
				<?php echo $error; ?>
				</div>
				<?php $error=''; } ?>

				
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span><input type="text" id="username" name="username" placeholder="Username" value="<?php //if(isset($_COOKIE['username'])){ echo $_COOKIE['username']; } ?>" />
						</div>
					</div>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span><input type="password" id="password" name="password" placeholder="Password" value="<?php //if(isset($_COOKIE['password'])){ echo $_COOKIE['password']; } ?>" />
						</div>
					</div>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span><input type="text" id="site_code" name="site_code" placeholder="Site Code" value="<?php if(isset($_COOKIE['sitecode'])){ echo $_COOKIE['sitecode']; } ?>" />
						</div>
					</div>
					<div class="formRow clearfix">
						<label class="checkbox"><input type="checkbox" name="rememberme"  /> Remember me</label>
					</div>
				</div>
				<div class="btm_b clearfix">
					<button class="btn btn-inverse pull-right" type="submit" name="SignIn">Sign In</button>
					<span class="link_reg"><a href="#reg_form">Not registered? Sign up here</a></span>
				</div>  
			</form>
			
			<form action="" method="post" id="pass_form" style="display:none">
				<div class="top_b">Can't sign in?</div>    
					<div class="alert alert-info alert-login">
					Please enter your email address. You will receive a link to create a new password via email.
				</div>
				<div class="cnt_b">
				<?php if(isset($req_message) && $req_message!='') { ?>
				<div class="formRow" style="color:#009900"><?php echo $req_message;?></div>				
				<?php $req_message=''; } ?>
					<div class="formRow clearfix">
						<div class="input-prepend">
							<span class="add-on">@</span><input type="text" placeholder="Your email address" name="req_email" id="req_email" />
						</div>
					</div>
				</div>
				<div class="btm_b tac">
					<button class="btn btn-inverse" type="submit" name="req_password">Request New Password</button>
				</div>  
			</form>
			
			<form action="" method="post" id="reg_form" style="display:none">
				<div class="top_b">Sign up to Jobshout Admin</div>
				
				<div class="alert alert-login">
					By filling in the form bellow and clicking the "Sign Up" button, you accept and agree to <a data-toggle="modal" href="#terms">Terms of Service</a>.
				</div>
				<div id="terms" class="modal hide fade" style="display:none">
					<div class="modal-header">
						<a class="close" data-dismiss="modal">×</a>
						<h3>Terms and Conditions</h3>
					</div>
					<div class="modal-body">
						<p>
							Nulla sollicitudin pulvinar enim, vitae mattis velit venenatis vel. Nullam dapibus est quis lacus tristique consectetur. Morbi posuere vestibulum neque, quis dictum odio facilisis placerat. Sed vel diam ultricies tortor egestas vulputate. Aliquam lobortis felis at ligula elementum volutpat. Ut accumsan sollicitudin neque vitae bibendum. Suspendisse id ullamcorper tellus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum at augue lorem, at sagittis dolor. Curabitur lobortis justo ut urna gravida scelerisque. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Aliquam vitae ligula elit.
							Pellentesque tincidunt mollis erat ac iaculis. Morbi odio quam, suscipit at sagittis eget, commodo ut justo. Vestibulum auctor nibh id diam placerat dapibus. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Suspendisse vel nunc sed tellus rhoncus consectetur nec quis nunc. Donec ultricies aliquam turpis in rhoncus. Maecenas convallis lorem ut nisl posuere tristique. Suspendisse auctor nibh in velit hendrerit rhoncus. Fusce at libero velit. Integer eleifend sem a orci blandit id condimentum ipsum vehicula. Quisque vehicula erat non diam pellentesque sed volutpat purus congue. Duis feugiat, nisl in scelerisque congue, odio ipsum cursus erat, sit amet blandit risus enim quis ante. Pellentesque sollicitudin consectetur risus, sed rutrum ipsum vulputate id. Sed sed blandit sem. Integer eleifend pretium metus, id mattis lorem tincidunt vitae. Donec aliquam lorem eu odio facilisis eu tempus augue volutpat.
						</p>
					</div>
					<div class="modal-footer">
						<a data-dismiss="modal" class="btn" href="#">Close</a>
					</div>
				</div>
				<div class="cnt_b">
					<?php if(isset($reg_message) && $reg_message!='') { ?>
				<div class="formRow" style="color:#FF0000"><?php echo $reg_message;?></div>				
				<?php $reg_message=''; } ?>
				<?php if(isset($reg_success_message) && $reg_success_message!='') { ?>
				<div class="formRow" style="color:green"><?php echo $reg_success_message;?></div>				
				<?php $reg_success_message=''; } ?>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span><span><input type="text" placeholder="Username" name="reg_uname" /></span>
							
						</div>
					</div>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span><span><input type="password" placeholder="Password" name="reg_pass" /></span>
						</div>
					</div>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on">@</span><span><input type="text" placeholder="Your email address" name="reg_email" id="reg_email" /></span>
						</div>
						<small>The e-mail address is not made public and will only be used if you wish to receive a new password.</small>
					</div>
					
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span><span><input type="text" placeholder="First Name" name="reg_fname" id="reg_fname" style="width:100px;" /></span>
							<span><input type="text" placeholder="Last Name" name="reg_lname" id="reg_lname" style="width:95px; border-radius:3px;" /></span>
						</div>
						
						<div class="formRow">
						<div class="input-prepend">
							<span >Site Name</span><span><div class="ui-widget" ><select onChange="" name="site_id" id="site_id" >
												<option value=""></option>
							<?php
							$sites=$db->get_results("select id,name from sites order by zStatus asc, name ASC limit 0,100");
							foreach($sites as $site)
							{
							?>
							<option value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
							<?php } ?>
							</select></div></span>
						</div>
						
					</div>
						
					</div>
					 
				</div>
				<div class="btm_b tac">
					<button class="btn btn-inverse" type="submit" name="register">Sign Up</button>
				</div>  
			</form>
			
		</div>
		
		<div class="links_b links_btm clearfix">
			<span class="linkform"><a href="#pass_form">Forgot password?</a></span>
			<span class="linkform" style="display:none">Never mind, <a href="#login_form">send me back to the sign-in screen</a></span>
		</div> 
		 
   <?php require_once("index-footer.php"); ?>   
     
        <script>
            $(document).ready(function(){
                
				 $.validator.addMethod("confirmemail", 
                           function(value, element) {
                              if($("#reg_con_email").val()==$("#reg_email").val()){
							  return true;
							  }
							  else
							  {
							  return false;
							  }
                           }, 
                           "Emails do not match"    ); 
						   
				
				
				
				//* boxes animation
				$form_wrapper = $('.login_box');
                var $currentForm = $form_wrapper.find('form:visible');
                $($form_wrapper).css({
                    'height' : $currentForm.height()
                });
                $('.linkform a,.link_reg a').on('click',function(e){
					var $link	= $(this);
					var target	= $link.attr('href');
                    $($currentForm).fadeOut(400,function(){
						$currentForm = $(target);
						$form_wrapper.stop().animate({
                            height	: $currentForm.actual('height')
                        },500,function(){
                            $currentForm.fadeIn(400);
                            $('.links_btm .linkform').toggle();
                        });
					});
					e.preventDefault();
				});
				
				//* validation
				$('#login_form').validate({
					onkeyup: false,
					errorClass: 'error',
					validClass: 'valid',
					rules: {
						username: { required: true, minlength: 3 },
						password: { required: true, minlength: 3 }
					},
					highlight: function(element) {
						$(element).closest('div').addClass("f_error");
					},
					unhighlight: function(element) {
						$(element).closest('div').removeClass("f_error");
					},
					errorPlacement: function(error, element) {
						$(element).closest('div').append(error);
					}
				});
				
				$('#reg_form').validate({
					onkeyup: false,
					errorClass: 'error',
					validClass: 'valid',
					rules: {
						reg_uname: { required: true, minlength: 3 },
						reg_pass: { required: true, minlength: 3 },
						reg_email: { required: true, email:true },
						reg_fname: { required: true},
						/*reg_con_email: { required: true, isemail:true, confirmemail:true },*/
						site_id: { required: true, }
					},
					highlight: function(element) {
						$(element).closest('span').addClass("f_error");
					},
					unhighlight: function(element) {
						$(element).closest('span').removeClass("f_error");
					},
					errorPlacement: function(error, element) {
						$(element).closest('div').append(error);
					}
				});
				
				$('#pass_form').validate({
					onkeyup: false,
					errorClass: 'error',
					validClass: 'valid',
					rules: {
						req_email: { required: true, email:true },
						
					},
					highlight: function(element) {
						$(element).closest('div').addClass("f_error");
					},
					unhighlight: function(element) {
						$(element).closest('div').removeClass("f_error");
					},
					errorPlacement: function(error, element) {
						$(element).closest('div').append(error);
					}
				});
				
            });
        </script>
		
		<script src="js/jquery-ui-1.9.1.custom.js"></script>
		
		<script>
			var xhr;
			(function( $ ) {
			$.widget( "custom.combobox", {
				_create: function() {
				this.wrapper = $( "<span>" )
				.addClass( "custom-combobox" )
				.insertAfter( this.element );

				this.element.hide();
				this._createAutocomplete();
				this._createShowAllButton();
				},

				_createAutocomplete: function() {
				var ele_select= this.element;
				var selected = this.element.children( ":selected" ),
				value = selected.val() ? selected.text() : "";

				this.input = $( "<input>" )
				.appendTo( this.wrapper )
				.val( value )
				.attr( "title", "" )
				.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
				.autocomplete({
				delay: 0,
				minLength: 0,
				source: $.proxy( this, "_source" )
				})
				.tooltip({
				tooltipClass: "ui-state-highlight"
				});

				this._on( this.input, {
				autocompleteselect: function( event, ui ) {
				//alert("show all");
				ui.item.option.selected = true;

				this._trigger( "select", event, {
				  item: ui.item.option
				});
				
				ele_select.trigger('change');

				},

				autocompletechange: "_removeIfInvalid"
				});
				},

				_createShowAllButton: function() {
				var input = this.input,
				wasOpen = false;

				$( "<a>" )
				.attr( "tabIndex", -1 )
				.attr( "title", "Show All Items" )
				.tooltip()
				.appendTo( this.wrapper )
				.button({
				icons: {
				  primary: "ui-icon-triangle-1-s"
				},
				text: false
				})
				.removeClass( "ui-corner-all" )
				.addClass( "custom-combobox-toggle ui-corner-right" )
				.mousedown(function() {
				wasOpen = input.autocomplete( "widget" ).is( ":visible" );
				})
				.click(function() {
				input.focus();

				// Close if already visible
				if ( wasOpen ) {
				  return;
				}

				// Pass last search string as value to search for, displaying last results
				input.autocomplete( "search", 'SHOWALL' );
				});
				},

				_source: function( request, response ) {
				//var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				var ele_select= this.element;
				if(request.term=='SHOWALL'){
				response(ele_select.children( "option" ).map(function() {
				var text = $( this ).text();
				var value= $( this ).val();
				//if ( this.value && ( !request.term || matcher.test(text) ) )
				return {
				  label: text,
				  value: text,
				  option: this
				};
				}) );

				}
				else
				{
				var jsonRow = 'sites_list.php?srch='+request.term;



				//alert(jsonRowURLStr);
				if(xhr) xhr.abort();
				xhr=$.getJSON(jsonRow,function(result){

				if(result){
					var html='<option value=""></option>';

					$.each(result, function(i,item)
					{
						html += '<option value="'+item.id+'">'+item.value+'</option>';
					});
					ele_select.html(html);
					
					
					response(ele_select.children( "option" ).map(function() {
				var text = $( this ).text();
				var value= $( this ).val();
				//if ( this.value && ( !request.term || matcher.test(text) ) )
				return {
				  label: text,
				  value: text,
				  option: this
				};
				}) );
					
					
				}
				});

				} 

				},

				_removeIfInvalid: function( event, ui ) {

					// Selected an item, nothing to do
					if ( ui.item ) {

					return;
					}

					// Search for a match (case-insensitive)
					var value = this.input.val(),
					valueLowerCase = value.toLowerCase(),
					valid = false;
					var ele_select= this.element;
					this.element.children( "option" ).each(function() {
					if ( $( this ).text().toLowerCase() === valueLowerCase ) {
					this.selected = valid = true;	
					
					ele_select.trigger('change');

					return false;
					}
					});

					// Found a match, nothing to do
					if ( valid ) {
					return;
					}

					// Remove invalid value
					this.input
					.val( "" )
					.attr( "title", value + " didn't match any item" )
					.tooltip( "open" );
					this.element.val( "" );
					this._delay(function() {
					this.input.tooltip( "close" ).attr( "title", "" );
					}, 2500 );
					this.input.data( "ui-autocomplete" ).term = "";
				},

				_destroy: function() {
					this.wrapper.remove();
					this.element.show();
				}
			});
			})( jQuery );

			$(function() {
				$( "#site_id" ).combobox();
			});

			</script>
    </body>
</html>
