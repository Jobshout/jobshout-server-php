<?php
require_once("connect.php");
require_once("constants.php");
require_once('include/class.phpmailer.php');

session_start();

if(isset($_POST['submit'])){

//$encrypted_mypassword=md5($password);
	$token_id=$_POST['token_id'];
	$Password = $_POST['password'];
	$encrypted_mypassword=md5($Password);
	$user=$db->get_row("SELECT * FROM wi_tokens WHERE uuid='$token_id'");
	if(count($user)>0){
		if($user->status==1){
			$db->query("update wi_users set password='$encrypted_mypassword' where uuid='".$user->user_uuid."'");
			//$db->debug();
			$db->query("update wi_tokens set status=0 where uuid='".$token_id."'");
			//$db->debug();
			$_SESSION['activate_msg']="Your Password has been changed successfully. You can now login with your new password.";
		}
		else
		{
			$msg_expire="This link has been expired.";
		}
	} 
	header("Location:index.php");
}
else
{
	$token_id=$_GET['token'];
	$user=$db->get_row("SELECT * FROM wi_tokens WHERE uuid='$token_id'");
	if(count($user)>0){
		if($user->status==0){
			$msg_expire="This link has been expired.";
		}
	}
}

?>



<?php require_once("index-header.php"); ?>
		
		<script src="js/jquery.min.js"></script>
		
		
    </head>
    <body class="login_page">
		
		<div class="login_box">
		
		<?php if(isset($msg_expire)) { ?>
		<div class="alert alert-info alert-login">
						<?php echo $msg_expire; ?>
					</div>
					<br/>
					<?php } else { ?>
			
			<form action="" method="post" id="login_form">
				<div class="top_b">Reset your Password</div>    
				<div class="alert alert-info alert-login">
					Enter your new password below
				</div>
				
				<div class="cnt_b">

					<div class="formRow">
						<div class="input-prepend">
						<input type="hidden" name="token_id" id="token_id" value="<?php echo $_GET['token']; ?>">
							<span class="add-on"><i class="icon-lock"></i></span><input type="password" id="password" name="password" placeholder="Password"  />
						</div>
					</div>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span><input type="password" id="con_password" name="con_password" placeholder="Confirm Password"  />
						</div>
					</div>
					
				</div>
				<div class="btm_b clearfix">
					<button class="btn btn-inverse pull-right" type="submit" name="submit">Submit</button>
					
				</div>  
			</form>
			
			<?php } ?>
			
			
			
		</div>
		
		
		 
   <?php require_once("index-footer.php"); ?>   
     
        <script>
            $(document).ready(function(){
                
				 $.validator.addMethod("confirmpass", 
                           function(value, element) {
                              if($("#password").val()==$("#con_password").val()){
							  return true;
							  }
							  else
							  {
							  return false;
							  }
                           }, 
                           "Passwords do not match"    ); 
						   
				 
				
				
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
						
						password: { required: true, minlength: 3 },
						con_password: { required: true, minlength: 3, confirmpass: true }
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
    </body>
</html>
