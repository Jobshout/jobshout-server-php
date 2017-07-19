<?php 
require_once("include/lib.inc.php");
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
	<ul >
		<li>
			<a href="#"><i class="icon-home"></i></a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
		
	</ul>
	
</div>
                    </nav>
                    
                   <?php
				   $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
				   ?>
                    <div class="row-fluid">
                        <div class="span12">
                          <h3 class="heading">
						  <?php if(isset($curr_site_name->name) && $curr_site_name->name!=''){ ?>
						  	Welcome to <?php echo "<strong><em>".$curr_site_name->name."</em></strong> "; ?>Admin area.<br />
							Please click on the links given in left column for desired action
						  <?php }
						  else { ?>
						  	Welcome to <?php echo "<strong><em>Jobshout</em></strong> "; ?>Admin area.<br />
							Please select a Site from below
						  <?php }
						  ?>
						    </h3>
                           
                        </div>
						
						 <div class="row-fluid">
                        
                        <div class="span12">
						
						<form action="" method="post" class="form_validation_reg" enctype="multipart/form-data" >
						
						<?php
											//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div >
									<div class="row-fluid">
										<div class="span4">
												<label >Select Site Name (code)<span class="f_req">*</span></label>
												
												
													<select name="site_id" id="site_id_sel" >
												<option value=""></option>
												<?php
												
													$sites=$db->get_results("select id, GUID, Name,Code from sites order by zStatus asc, Name ASC limit 0,100 ");
													foreach($sites as $site){ ?>
													<option value="<?php echo $site->GUID; ?>"><?php echo $site->Name.' ('.$site->Code.')'; ?></option>	
													<?php }
																
												?>
												</select>
													</div>
												</div>
											</div>
											<br/>
											<div >
									<button class="btn btn-gebo" type="button" name="submit" onClick="if($('.form_validation_reg').valid()){ chng_site($('#site_id_sel').val()); }" >Submit</button>
								</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div >
									<div class="row-fluid">
										<div class="span4">
												<label >Select Site Name (code)<span class="f_req">*</span></label>
												
												
													<select onChange="" name="site_id" id="site_id_sel" >
												<option value=""></option>
												<?php
												
													$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."')");
													foreach($sites as $site)
													{
													?>
													<option value="<?php echo $site->GUID; ?>"><?php echo $site->name; ?></option>	
													<?php } 
												 ?>
											</select>
													
													</div>
												</div>
											</div>
											<br/>
											<div >
									<button class="btn btn-gebo" type="button" name="submit" onClick="if($('.form_validation_reg').valid()){ chng_site($('#site_id_sel').val()); }" >Submit</button>
								</div>
											
											<?php
											}  }
										?>	
						
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
			<!-- datatable -->
            <script src="lib/datatables/jquery.dataTables.min.js"></script>
            <script src="lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
			<!-- datatable functions -->
            <script src="js/gebo_datatables.js"></script>
			<script type="text/javascript">
			$(document).ready(function() {
				gebo_validation.reg();
			});
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