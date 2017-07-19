<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); 

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$chk_num=$db->get_var("select count(*) as num from uk_towns_cities where GUID='".$_GET['GUID']."'");
	if($chk_num==0){
		header("Location: location.php");
	}
}

//$db->query("ALTER TABLE uk_towns_cities ADD county varchar(255) DEFAULT Null");


// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$name=$_POST["loc_name"];
	$type=$_POST["loc_type"];
	$country=$_POST["loc_country"];
	$lat=$_POST["loc_lat"];
	$lng=$_POST["loc_lng"];
	$district=$_POST["district"];
	$postcode=$_POST["postcode"];
	$county=$_POST["county"];
	
	if(isset($_GET['GUID'])) {
		$GUID= $_GET['GUID'];
		$sql= $db->get_var("SELECT count(*) FROM uk_towns_cities WHERE name='".addslashes($name)."' and lat='$lat' and lng='$lng' and GUID <>'$GUID'");
		
		if($sql>0){
			$err_msg = "This location already exists";
		}
		else {
			if($db->query("UPDATE uk_towns_cities SET name='".addslashes($name)."', county='".addslashes($county)."', postcode='$postcode', district='".addslashes($district)."', type='$type', country='".addslashes($country)."', lat='$lat', lng='$lng' WHERE GUID ='$GUID'")) {
				
				$_SESSION['up_message'] = "Successfully updated";
			}
	 
	 	}
	}else{
		$sql= $db->get_var("SELECT count(*) FROM uk_towns_cities WHERE name='".addslashes($name)."' and lat='$lat' and lng='$lng'");
		
		if($sql>0){
			$err_msg = "This location already exists";
		}
		else {
			$GUID=UniqueGuid('uk_towns_cities', 'GUID');
			 if($db->query("INSERT INTO uk_towns_cities (GUID, name, type, country, lat, lng, postcode, district, county) VALUES ('$GUID', '".addslashes($name)."', '$type', '".addslashes($country)."', '$lat', '$lng', '$postcode', '".addslashes($district)."', '".addslashes($county)."')")) {
				$_SESSION['ins_message'] = "Successfully Inserted";
				header("Location:locations.php");
			}
		}
	}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$link = $db->get_row("SELECT * FROM uk_towns_cities where GUID ='$guid'");

		$name=$link->name;
		$type=$link->type;
		$country=$link->country;
		$lat=$link->lat;
		$lng=$link->lng;
		$district=$link->district;
		$postcode=$link->postcode;
		$county=$link->county;
}
else
{
		$guid='';
		$name='';
		$type='';
		$country='';
		$lat='';
		$lng='';
		$district='';
		$postcode=''; $county='';

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
			<a href="locations.php">Locations</a>
		</li>
		<li>
			<a href="#">Location</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
                    
					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category</h3>-->
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
											<label>Name <span class="f_req">*</span></label>
											<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" class="textbox">

											<input type="text" name="loc_name" class="span12" id="loc_name" value="<?php echo $name; ?>"/>
											
											
										</div>
										
											</div>
										</div>

								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Type <span class="f_req">*</span></label>
																						
											<select onChange="" name="loc_type" id="loc_type" >
												<option value='city' <?php if($type=='city') { ?>  selected="selected" <?php } ?> >City</option>	
												<option value='Hamlet' <?php if($type=='Hamlet') { ?>  selected="selected" <?php } ?> >Hamlet</option>	  
												<option value='Geographic' <?php if($type=='Geographic') { ?>  selected="selected" <?php } ?> >Geographic</option>
												<option value='Non-Geographic' <?php if($type=='Non-Geographic') { ?>  selected="selected" <?php } ?> >Non-Geographic</option>
												<option value='Other Settlement' <?php if($type=='Other Settlement') { ?>  selected="selected" <?php } ?> >Other Settlement</option>	
												<option value='PO Box' <?php if($type=='PO Box') { ?>  selected="selected" <?php } ?> >PO Box</option>				
												<option value='Suburban Area' <?php if($type=='Suburban Area') { ?>  selected="selected" <?php } ?> >Suburban Area</option>
												<option value="town" <?php if($type=='town') { ?>  selected="selected" <?php } ?> >Town</option>
												<option value='Village' <?php if($type=='Village') { ?>  selected="selected" <?php } ?> >Village</option>
   									<!--<option value='county' <?php if($type=='county') { ?>  selected="selected" <?php } ?> >County</option>-->
				
											</select>	
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>District </label>
											<input type="text" name="district" class="span12" id="district" value="<?php echo $district; ?>"/>
										</div>
										<div class="span4">
											<label>Postcode <span class="f_req">*</span></label>
											<input type="text" name="postcode" class="span12" id="postcode" value="<?php echo $postcode; ?>"/>
										</div>
									</div>
								</div>
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Country <span class="f_req">*</span></label>
					 							
													<input type="text" class="span12" name="loc_country" id="loc_country" value="<?php echo $country;?>" />
										</div>
										<div class="span4">
											<label>County </label>
					 						<input type="text" class="span12" name="county" id="county" value="<?php echo $county;?>" />
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
											<label>Lattitude <span class="f_req">*</span></label>
					 							
													<input type="text" class="span12" name="loc_lat" id="loc_lat" value="<?php echo $lat;?>" />
										</div>
									
										<div class="span4">
											<label>Longitude <span class="f_req">*</span></label>
					 							
													<input type="text" class="span12" name="loc_lng" id="loc_lng" value="<?php echo $lng;?>" />
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
								loc_name: { required: true },
								loc_type: { required: true },
								loc_country: { required: true },
								loc_lat: { required: true, number:true },
								loc_lng: { required: true, number:true },
								postcode: { required: true},
								Sync_Modified: { required: true },
							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please correct them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
					}
				};
			</script>
			
		</div>
	</body>
</html>