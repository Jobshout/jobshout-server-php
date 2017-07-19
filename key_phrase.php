<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); 

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$chk_num=$db->get_var("select count(*) as num from key_phrase where id='".$_GET['id']."'");
	if($chk_num==0){
		header("Location: key_phrase.php");
	}
}

if(isset($_POST['submit']))
{ 
	
	$phrase=$_POST["phrase"];
	$synonym=$_POST["synonym"];
	
	if(isset($_GET['id'])) {
		$id= $_GET['id'];
		if($db->query("UPDATE key_phrases SET phrase='".$phrase."', synonym='$synonym' WHERE id ='$id'")) {
			$_SESSION['up_message'] = "Successfully updated";
		}
	}else{
		$sql= $db->get_var("SELECT count(*) FROM key_phrases WHERE phrase='".$phrase."' and synonym='$synonym'");
		
		if($sql>0){
			$err_msg = "This key phrase already exists";
		}
		else {
			if($db->query("INSERT INTO key_phrases (phrase, synonym) VALUES ('$phrase', '$synonym')")) {
				$_SESSION['ins_message'] = "Successfully Inserted";
				header("Location:key_phrases.php");
			}
		}
	}
}
//to fetch category content
$id='';
$phrase='';
$synonym='';

if(isset($_GET['id'])) {
	$id= $_GET['id'];
	if($link = $db->get_row("SELECT * FROM key_phrases where id ='$id'")){
		$phrase=$link->phrase;
		$synonym=$link->synonym;
	}
}

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
			<a href="key_phrases.php">Key Phrases</a>
		</li>
		<li>
			<a href="#">Key Phrase</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
                    
					<div id="validation" style="padding-left: 200px;color:#FF0000;font-size:18px"><?php if (isset($err_msg)) echo $err_msg; ?></div>
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br/>
                    <div class="row-fluid">
                        
                        <div class="span6">
							
							<form class="form_validation_reg" method="post" action="">
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Phrase <span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($id!='') { echo $id; } ?>" name="id" class="textbox">

											<input type="text" name="phrase" class="span12" id="phrase" value="<?php echo $phrase; ?>"/>
											
											
										</div>
										
											</div>
										</div>

								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Synonym <span class="f_req">*</span></label>
											<input type="text" name="synonym" class="span12" id="synonym" value="<?php echo $synonym; ?>"/>
											
											
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
			
			<script>
				$(document).ready(function() {
					//* regular validation
					gebo_validation.reg();
					
				});
				
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
								phrase: { required: true },
								synonym: { required: true },
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