 <?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');


if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from wi_mailinglists where uuid='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: mail.php");
	}
}

				if(isset($_POST['submit']))
				{	
					
						
						$Name = addslashes($_POST["Name"]);
						$description = addslashes($_POST["description"]);
						$status = $_POST["status"];
						$time = time();
						$site_id=$_POST["site_id"];
						
					 if(isset($_GET['uuid']))
					 {
					
					 if($db->query("UPDATE  wi_mailinglists SET list_name = '$Name', list_description='$description', status ='$status', Modified = '$time', SiteID=$site_id where  uuid = '".$_GET['uuid']."'")) {	
					
				 $_SESSION['up_message'] = "Updated successfully";
				 
				 }
				 //$db->debug();
	}
		else {
				//echo "INSERT INTO  wi_mailinglists (uuid, list_name, list_description, created, modified, status)  VALUES ('$uuid', '$Name', '$description', '$time', '$time', '$status')";
			$uuid=UniqueGuid('wi_mailinglists', 'uuid');	
			if($db->query("INSERT INTO  wi_mailinglists (uuid, list_name, list_description, created, modified, status, SiteID)  VALUES ('$uuid', '$Name', '$description', '$time', '$time', '$status', $site_id)")) {
			
			$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:mails.php");
			//$db->debug();
			}
		}
	}		

			
if(isset($_GET['uuid'])){

	 $user3 = $db->get_row("SELECT * FROM  wi_mailinglists where uuid = '".$_REQUEST['uuid']."'");
	 //$db->debug();
	 	$site_id=$user3->SiteID;
	 	$uuid=$user3->uuid;
		$list_name=$user3->list_name;
		$list_description=$user3->list_description;
		$status=$user3->status;
		/*if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
		{
			$num_contact=$db->get_var("select count(*) as num from wi_mailinglist_contacts where uuid_mailinglist='".$_GET['uuid']."' and uuid_contact in (select GUID from contacts where SiteID in ('".$_SESSION['site_id']."'))");
		}
		else
		{*/
			$num_contact=$db->get_var("select count(*) as num from wi_mailinglist_contacts where uuid_mailinglist='".$_GET['uuid']."' and uuid_contact in (select GUID from contacts)");
		/*}*/
	
		
	}
		else
		  {
		  $site_id='';
		   $uuid='';
		   $list_name='';
		   $list_description='';
		   $status=2;
		  $num_contact=0;
		  
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
			<a href="mails.php">Mailing Lists</a>
		</li>
		<li>
			<a href="#">Mailing List</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
					
					
					<!--<div><h3 class="heading"><?php if(isset($_GET['uuid'])) { echo "Update"; } else { echo "Add New"; } ?> Mailing List</h3></div>-->
					<div><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					</span></div>
					<div><span style="color:#FF0000;font-size:18px"><?php if (isset($msg_user)) echo $msg_user; ?></span></div>
 
 <br/>
							
                    <div class="row-fluid">
						<div class="span12">
							
							
							
							
							
									<form name="form1" id="form1" class="form-horizontal form_validation_reg" action="" enctype="multipart/form-data" method="post" >
										<fieldset>
										
											<?php
											// $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											
<div class="control-group formSep">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												<div class="controls">												
													<select name="site_id" id="site_id_sel" >
												<option value=""></option>
												<?php
												if($site=$db->get_row("select id, GUID, name,Code from sites where ID='$site_id'")){ ?>
														<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php 
												}else{
													$sites=$db->get_results("select id, GUID, name,Code from sites order by zStatus asc, Name ASC limit 0,100 ");
													foreach($sites as $site){ ?>
													<option value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php }
												}				
												?>
												</select>
													
													
												</div>
											</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="control-group formSep">
												<label class="control-label">Site Name (code)<span class="f_req">*</span></label>
												
												<div class="controls">
													<select onChange="" name="site_id" id="site_id_sel" >
												<option value=""></option>
												<?php
												if($sites=$db->get_results("select id, GUID, name,Code from sites where ID='$site_id' ")){
													foreach($sites as $site){ ?>
														<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
													<?php }
												}else {
													$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."') order by zStatus asc, Name ASC limit 0,100");
													foreach($sites as $site)
													{
													?>
													<option value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
													<?php } 
												} ?>
											</select>
																				
													
												</div>
											</div>
											
											<?php
											} else {
										?>
										<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
										<?php
										} }
										?>	
											
											
													
											<div class="control-group formSep">
												<label class="control-label">List Name<span class="f_req">*</span></label>
												<div class="controls text_line">
													<input type="hidden" value="<?php if($uuid!='') { echo $uuid; } ?>" name="uuid" id="uuid" >
													<input type="text"  name="Name" id="Name" class="input-xlarge" value="<?php echo $list_name; ?>">
													<span>&nbsp;</span>
												</div></div>
																					
												<div class="control-group formSep">
												<label for="u_signature" class="control-label">Description<span class="f_req">*</span></label>
												<div class="controls">
													<textarea rows="4" id="description" name="description" class="input-xlarge"><?php echo $list_description; ?></textarea>
													<span>&nbsp;</span>
													<span class="help-block"></span>
												</div>
											</div>
											
											<div class="control-group formSep">
												<label class="control-label">Status <span class="f_req">*</span></label>
											<div class="controls text_line">	
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($status == 1 || $status == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline"> 
												<input type="radio" value="0" name="status" <?php if($status == 0) { echo ' checked'; } ?>/>												Inactive
												
											</label>
										</div></div>
									
											
											
											
									<div class="control-group">
												<div class="controls">
													<button class="btn btn-gebo" type="submit" name="submit" id="submit">Submit</button>
														
								<?php
								if($num_contact>0)
								{
								
								?>
													<button class="btn btn-gebo" type="button" name="send_email" id="submit" onClick="window.open('email_contacts.php?list_id=<?php echo $_GET['uuid']; ?>','Send E-mail','menubar=1,resizable=1,scrollbars=1,width=800,height=600');">Email Contacts</button>
								<?php } ?>
												</div>	
												</div>
											
										
										</fieldset>
									</form>
									
								<?php
								if($num_contact>0)
								{
								
								?>
								<h3 class="heading">Contacts in <?php echo $list_name; ?></h3>
								 <table id="dt_e" class="table table-striped table-bordered" >
                                <thead>
                                    <tr>                                     
                                        <th>Name</th><th>Email</th><th>Telephone</th><th>Status</th><th>DateRegistered</th><th>Modified</th><th >Site Name</th><th >ID</th>
 
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="dataTables_empty" colspan="8">Loading data from server</td>
                                    </tr>
                                </tbody>
                            </table>
								
								<?php
								
								}
								?>	
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
		             
			  <script type="text/javascript">
			$(document).ready(function(){
			
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
								site_id: { required: true },
								Name: { required: true },
								description: { required: true },
								status: { required: true },
								
								
							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
						
						
					}
				};
			</script>
		<?php if($num_contact>0){ ?>	
			<!-- datatable -->
            <script src="lib/datatables/jquery.dataTables.min.js"></script>
            <script src="lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
			<!-- datatable functions -->
            <script>
				$(document).ready(function() {
					datatbles.dt_e();
				});
				
				datatbles = {
					
					dt_e: function(){
						if($('#dt_e').length) {
							
							var oTable;
							oTable = $('#dt_e').dataTable( {
								"bProcessing": true,
								"bServerSide": true,
								"sPaginationType": "bootstrap",
								"aaSorting": [[3,'desc'],[5,'desc']],
								"sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
								"sAjaxSource": "lib/datatables/server_mail_contacts.php",
								"aoColumnDefs": [
						<?php if($user_access_level<11 OR isset($_SESSION['site_id'])) { ?>
									{ "bSearchable": false, "bVisible": false, "aTargets": [ 6 ] },
								<?php } ?>
                    ],
								"fnServerParams": function ( aoData ) {
            aoData.push( { "name": "list_id", "value": "<?php echo $_GET['uuid']; ?>" } );
        },

								"oLanguage": {
      "sInfoFiltered": ""
    }
							} );
							
							

						}
					}
				};
			</script>
           <script src="js/datatables.js"></script>
		   <?php } ?> 
            
		</div>
	</body>
</html>
