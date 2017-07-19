<?php 
session_start();
if($_SESSION['UserEmail'] =='') {
       header("location:index.php");
}

if(isset($_POST['submit'])){
$m = new Mongo( 'mongodb://85.92.89.214:27017/' );
$db = $m->selectDB( "test" );
$collection = $db->selectCollection( "customer" );

$customer = $_POST['Url'];
$customer_details = file_get_contents($customer);
$customer_detail = json_decode($customer_details);
$customer_uuid = $customer_detail->{'uuid'};
$check_customer = $collection->findOne(array("uuid" => $customer_uuid));
if($check_customer){
$sec_msg= "Customer already exists";
}else{
$collection->insert($customer_detail);
$sec_msg= "Customer details are successfully added";
}
// $collection->insert($customer_detail);
// $joe = $collection->findOne(array("_id" => $customer['_id']));
// $sec_msg= "Customer successfully added";
}

require_once("connect.php");
require_once("constants.php");
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
                        <div id="jCrumbs" class="breadCrumb module">
							<ul>
								<li>
									<a href="home.php"><i class="icon-home"></i></a>
								</li>
								<li>
									<a href="index.php">Dashboard</a>
								</li>
								<li>
									<a href="#">Save in Mongo</a>
								</li>
							</ul>
						</div>
               
                    </nav>
                    
					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category</h3>-->
                    <div class="row-fluid">
                        <div id="validation" style="padding-left: 200px;"><span style='color: red'><?php if (isset($msg_em)) echo $msg_em; ?></span></div>
							<div id="validation" style="padding-left: 200px;"><span style='color: red'><?php if (isset($msg_us)) echo $msg_us; ?></span></div>
							<div id="validation" style="padding-left: 200px;"><span style='color: green'><?php if (isset($sec_msg)) echo $sec_msg; ?></span></div>
							
                        <div class="span6">
							<form class="form_validation_reg" method="post" action="">
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Url <span class="f_req">*</span></label>
											<input type="text" name="Url" class="span12" id="Url" value=""/>
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
            <!-- validation -->
            <script src="lib/validation/jquery.validate.min.js"></script>
            <!-- validation functions -->
			
			<script>
				$(document).ready(function() {
					//* regular validation
					gebo_validation.reg();
					
					
				
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
								Code: { required: true },
								name: { required: true },
								category_gropuID: { required: true },
								userID: { required: true },
								status: { required: true },
								userID: { required: true },
								reg_type: { required: true },
								
								
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