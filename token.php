<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); 

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from tokens where GUID='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: token.php");
	}
}



if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$token = $db->get_row("SELECT * FROM tokens where GUID ='$guid'");

		$token_id=$token->ID;
		$site_id=$token->SiteID;
		$Code=$token->Code;
		$descr=$token->Description;
		$value=$token->TokenText;
}

// to update selected catgory
if(isset($_POST['submit']))
{ 
	
	$site_id=$_POST["site_id"];
	$new_code=$_POST["Code"];
	$new_descr=addslashes($_POST["description"]);
	$new_value=addslashes($_POST["TokenText"]);
	$Active=$_POST["status"];
	$enable_WYSIWYG=$_POST["en_dis_editor"];
	$UserID=$_POST["userID"];
		
	$time= time();
	
	$site_guid= $db->get_var("select GUID from sites where ID='$site_id'");
	$user_guid= $db->get_var("select uuid from wi_users where ID='$UserID'");
	
	/*FTS*/
	$fts=strip_tags(addslashes($_POST["TokenText"]));
	/*FTS*/
	
	
		
	
		
	if(isset($_GET['GUID'])) {
		$guid= $_GET['GUID'];
		
	
	if($db->query("UPDATE tokens SET SiteID='".$site_id."', Modified='$time', Code='$new_code', Description='$new_descr', TokenText='$new_value', zStatus='$Active', UserID='$UserID', TokenContent='$fts', enable_WYSIWYG='$enable_WYSIWYG', Site_GUID= '$site_guid', User_GUID= '$user_guid'
	WHERE GUID ='$guid'")) {
		
		if(isset($_POST['rlbk_update_ids']) && $_POST['rlbk_update_ids']!=''){
		
			$arr_rlbk_uuids=explode(",", $_POST['rlbk_update_ids']);
			$arr_rlbk_cols=explode(",", $_POST['rlbk_update_col']);
			$db->select($history_db_name);
			for($i=0; $i<count($arr_rlbk_uuids); $i++){
				$delete = $db->query("update tokens set ".$arr_rlbk_cols[$i]."='' where Update_GUID='".$arr_rlbk_uuids[$i]."'");
			}
			
			$delete = $db->query("delete from tokens where Description='' and TokenText=''");
			//$db->debug();
			$db->select($db_name);
		}
		
		$changed_time=time();
		$tokenID=$token_id;
		$old_description='';
		$old_value='';
		$old_code='';
		
		if(stripslashes($descr)!=$_POST["description"]){
			$old_description=$descr;
		}
		if(stripslashes($value)!=$_POST["TokenText"]){
			$old_value=$value;
		}
		if($Code!=$_POST["Code"]){
			$old_code=$Code;
		}
		
		if($old_description!='' || $old_value!='' || $old_code!=''){
			
			$db->select($history_db_name);
			
			$db->query("INSERT INTO tokens (Update_GUID, GUID, SystemID, ID, Created, SiteID, Description, TokenText, Code, User_GUID)
				VALUES (UUID(),'".$_GET['GUID']."','".$_GET['GUID']."','$tokenID', '$changed_time', '".$site_id."', '".addslashes($old_description)."', '".addslashes($old_value)."', '$old_code', '$login_user_uuid')");
			//$db->debug();
			$db->select($db_name);
		}
		
	$_SESSION['up_message'] = "Updated successfully";
	
	
	}
	
	 //$db->debug();
	}
	else
	{
		//echo "INSERT INTO categories (GUID,Created,Modified,SiteID,Name,Code,CategoryGroupID,Active,UserID,Type,FTS,Sync_Modified,Auto_Format) VALUES ('$GUID','$time','$time','".$site_id."','$Name','$Code','$CategoryGroupID','$Active','$UserID','$Type','$fts','$Sync_Modified',$Auto_Format)";
		
	$GUID = UniqueGuid('tokens', 'GUID');		
	 if($db->query("INSERT INTO tokens (GUID, SystemID, Created, Modified, SiteID, Code, Description, TokenText, zStatus, UserID, TokenContent, enable_WYSIWYG, Site_GUID, User_GUID) VALUES ('$GUID', '$GUID', '$time', '$time', '".$site_id."', '$new_code', '$new_descr', '$new_value', '$Active', '$UserID', '$fts', '$enable_WYSIWYG', '$site_guid',  '$user_guid')")) {
	
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:tokens.php");
	}
	//$db->debug();
	
	}
}
//to fetch category content
if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$token = $db->get_row("SELECT * FROM tokens where GUID ='$guid'");

		$token_id=$token->ID;
		$site_id=$token->SiteID;
		$Code=$token->Code;
		$descr=$token->Description;
		$value=$token->TokenText;
		$Active=$token->zStatus;
		$enable_WYSIWYG=$token->enable_WYSIWYG;
		$UserID=$token->UserID;
		
		
		$where_cond=" and SiteID ='".$site_id."' ";
		
// $db->debug();
}
else
{
	$guid='';
	$token_id='';
	$site_id='';
		$Code='';
		$descr='';
		$value='';
		$Active=2;
		$enable_WYSIWYG=0;
		$UserID='';
		
		
		$where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
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
			<a href="tokens.php">Tokens</a>
		</li>
		<li>
			<a href="#">Token</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
               
                    </nav>
                    
					<!--<h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add"; } ?> Category</h3>-->
							<div id="validation" ><span style="color:#00CC00;font-size:18px">
							<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
							</span></div><br/>
                    <div class="row-fluid">
                        <form class="form_validation_reg" method="post" action="">
                        <div class="span7">
							
							
							
									<?php
											// $user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
											if($user_access_level>=11 && !isset($_SESSION['site_id'])) {
											?>
											<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
												<label >Site Name (code)<span class="f_req">*</span></label>
																								
													<select onChange="" name="site_id" id="site_id_sel"  style="width:350px">
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
											</div>
											<?php
											}
										 elseif(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
	 									{
											$site_arr=explode("','",$_SESSION['site_id']);
											if(count($site_arr)>1) {
											?>
											<div class="formSep">
									<div class="row-fluid">
										<div class="span4">
												<label >Site Name (code)<span class="f_req">*</span></label>
												
												
													<select onChange="" name="site_id" id="site_id_sel" >
													<option value=""></option>
														<?php
														if($sites=$db->get_results("select id, GUID, name,Code from sites where ID='$site_id' ")){
															foreach($sites as $site){ ?>
																<option <?php if($site_id==$site->id) { ?> selected="selected" <?php } ?> value="<?php echo $site->id; ?>"><?php echo $site->name.' ('.$site->Code.')'; ?></option>	
															<?php }
														}else {
															$sites=$db->get_results("select id,name from sites where id in ('".$_SESSION['site_id']."') order by zStatus asc, Name ASC limit 0,100 ");
															foreach($sites as $site)
															{
															?>
															<option value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
															<?php } 
														} ?>
													</select>
													
													</div>
												</div>
											</div>
											
											<?php
											} else {
										?>
										<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
										<?php
										} }
										?>	
							
							
								<div class="formSep">
									<div class="row-fluid">
										
										<div class="span4">
												<label>Code <span class="f_req">*</span></label>
												<input type="hidden" value="<?php if($guid!='') { echo $guid; } else { $Guid = NewGuid();
									echo $Guid; } ?>" name="GUID" class="textbox">				 							
													<input type="text" class="span12" name="Code" id="Code" value="<?php echo $Code;?>" />
												</div>
											</div>
										</div>
										
									<div class="formSep">
									<div class="row-fluid">
										
										<div class="controls">
												<label>Description </label>				 							
													<textarea cols="60" rows="5" name="description" id="Description" style="width:500px" ><?php echo $descr;?></textarea>
													
												</div>
											</div>
										</div>
										
									<div class="formSep">
									<div class="row-fluid">
										
										<div class="controls">
												<label>Token Text <span class="f_req">*</span></label>				 							
													
													<textarea cols="60" rows="35" name="TokenText" <?php if($enable_WYSIWYG==1) { ?> id="TokenText" <?php } ?> style="width:500px" ><?php echo $value;?></textarea>
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
										</div>	

								<div class="formSep">
									<div class="row-fluid">
										<div class="span10">
											<label><span class="error_placement">Status </span> <span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($Active == 1 || $Active == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline">
												<input type="radio" value="0" name="status" <?php if($Active == 0) { echo ' checked'; } ?>/>
												Inactive
											</label>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
										<div class="span8">
											<label><span class="error_placement">Enable/Disable WYSIWYG </span> <span class="f_req">*</span></label>
											<label class="radio inline">
												<input type="radio" value="0" name="en_dis_editor" <?php if($enable_WYSIWYG == 0) { echo ' checked'; } ?>/>
												Disable
											</label>
											<label class="radio inline">
												<input type="radio" value="1" name="en_dis_editor" <?php if($enable_WYSIWYG == 1) { echo ' checked'; } ?>/>
												Enable
											</label>
										</div>
									</div>
								</div>
								
								<div class="formSep">
									<div class="row-fluid">
									  <div class="span8">
											<label>User <span class="f_req">*</span></label>
											<select onChange="" name="userID" id="UserID">
												<option value="">-- Select User --</option>
											
											</select>
										</div>
									</div>
								</div>
								
								
							
								<div class="form-actions">
									<button class="btn btn-gebo" type="submit" name="submit" id="submit">Save changes</button>
									<!--<button class="btn" onclick="window.location.href='categories.php'">Cancel</button>-->
								</div>
							
                        </div>
						<?php	if(isset($_GET['GUID'])) { ?>
						<?php
							$db->select($history_db_name);
							$last_changes=$db->get_results("select * from tokens where ID='$token_id' and GUID='".$_GET['GUID']."' order by Created desc limit 0,10");
							
							$db->select($db_name);
							
							if(count($last_changes)>0){
						?>
						<div class="span4">
							<div class="well form-inline">
								<p class="f_legend">Last Modified</p>
								<div class="controls">
									<ul>
									<?php
										foreach($last_changes as $last_change){
										
										$change_by_user=$login_user_code;
										
										if($last_change->Description!='') {
									?>
										
										<li>Description : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y h:i:s a",$last_change->Created); ?>)												
											<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('Description', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
										</li>
									<?php } 
									
									if($last_change->TokenText!='') {
									?>
										<li>Token Text : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y h:i:s a",$last_change->Created); ?>)												
											<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('TokenText', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
										</li>
									<?php
										}
										if($last_change->Code!='') {
									?>
										<li>Code : <?php echo $change_by_user; ?> (<?php echo date("d/m/Y h:i:s a",$last_change->Created); ?>)												
											<button class="btn btn-success" type="button" name="rollback" onClick="roll_back('Code', '<?php echo $last_change->Update_GUID; ?>', '<?php echo $last_change->ID; ?>', '<?php echo $last_change->Created; ?>', this)" >Rollback</button>
										</li>
									<?php
										}
									}
									?>
										
									</ul>
									<input type="hidden" name="rlbk_update_ids" id="rlbk_update_ids" >
									<input type="hidden" name="rlbk_update_col" id="rlbk_update_col" >
								</div>
							</div>
						</div>
						<?php } 
						} ?>
						
						</form>
                    </div>
                        
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
            <?php require_once('include/footer.php');?>
			 
		
			
			<script>
			 function roll_back(col, uuid, token_id, datetime, btn){			  		
				var	jsonRow= "get_token_history.php?uuid="+uuid+"&token_id="+token_id+"&col="+col+"&datetime="+datetime+"&action="+$(btn).html();
				
				$.getJSON(jsonRow,function(result){
					if(result){
						$.each(result, function(i,item)
						{
							var field= col;
							//alert(field);
							var content= item.content;
							$('#'+field).val(content);	
							if($(btn).html()=='Rollback'){				
								$(btn).html('Cancel');
								if($('#rlbk_update_ids').val()==''){
									$('#rlbk_update_ids').val(item.uuid);
									$('#rlbk_update_col').val(col);
								}
								else{
									$('#rlbk_update_ids').val($('#rlbk_update_ids').val()+','+item.uuid);
									$('#rlbk_update_col').val($('#rlbk_update_col').val()+','+col);
								}
							}
							else if($(btn).html()=='Cancel'){
								$(btn).html('Rollback');
								var arr_uuids=	$('#rlbk_update_ids').val().split(",");
								var arr_cols=	$('#rlbk_update_col').val().split(",");
								 $('#rlbk_update_ids').val('');
								 $('#rlbk_update_col').val('');
								 for(var i=0; i<arr_uuids.length; i++){
									if(arr_uuids[i]!=item.uuid){
										if($('#rlbk_update_ids').val()==''){
											$('#rlbk_update_ids').val(arr_uuids[i]);
											$('#rlbk_update_col').val(arr_cols[i]);
										}
										else{
											$('#rlbk_update_ids').val($('#rlbk_update_ids').val()+','+arr_uuids[i]);
											$('#rlbk_update_col').val($('#rlbk_update_col').val()+','+arr_cols[i]);
										}
									}
								 }
							}
						});
					}
				});				
			}
			
				$(document).ready(function() {
					//* regular validation
					
					gebo_validation.reg();
					
					
					
					$('#Code').keypress(function(e){
						var k = e.which;
    					/* numeric inputs can come from the keypad or the numeric row at the top */
   						 if ( (k<48 || k>57) && (k<65 || k>90) && (k<97 || k>122) && (k!=45) && (k!=95) && (k!=8) && (k!=0)) {
        					e.preventDefault();
							alert("Allowed characters are A-Z, a-z, 0-9, _, -");
        					return false;
    					}
					
					});
					
					var site_id=$('[name="site_id"]').val();
					var usr_id='<?php echo $UserID; ?>';
					var	login_usr_id='<?php echo $user_details->ID; ?>';
					$.ajax({
					type: "POST",
					url: "user_list.php",
					data: {'site_id' : site_id, 'usr_id' : usr_id, 'login_usr_id' : login_usr_id},
					success: function(response){
						
						$("#UserID").html(response);
					}
					});
					
					$("#site_id_sel").change(function(){
						var site_id=$(this).val();
						
						var usr_id='<?php echo $UserID; ?>';
						var	login_usr_id='<?php echo $user_details->ID; ?>';
						$.ajax({
						type: "POST",
						url: "user_list.php",
						data: {'site_id' : site_id, 'usr_id' : usr_id, 'login_usr_id' : login_usr_id},
						success: function(response){
							
							$("#UserID").html(response);
						}
					 });				
					});
					
					$('#submit').click(function(){
						tinyMCE.triggerSave();
					});
					
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
								site_id: { required: true },
								Code: { required: true },
								
								TokenText: { required: true },
								userID: { required: true },
								status: { required: true },
								en_dis_editor: { required: true },
								
							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
					}
				};
			</script>

<?php
//BSW 20140805 2:13AM handles images paths correctly now

$pSiteRootFolderPath="";

if((!isset($_COOKIE['sitecode']) || $_COOKIE['sitecode']=='') && $site_id!='') { 
$pSiteRoot=$db->get_row("SELECT Code, RootDirectory FROM sites where ID ='".$site_id."' ");

if($pSiteRoot->RootDirectory!='')
{
$pSiteRootFolderPath=$pSiteRoot->RootDirectory;
}
else{
$pSiteRootFolderPath=$pSiteRoot->Code;
}

}

?>			

<script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
var tiny_options=new Array();
tiny_options['selector']= "textarea#TokenText";
tiny_options['theme']= "modern";
tiny_options['plugins']= "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table contextmenu directionality emoticons template paste textcolor moxiemanager";
tiny_options['theme_advanced_buttons1']= "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect";
tiny_options['theme_advanced_buttons2']= "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor";
tiny_options['theme_advanced_buttons3']= "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen";
tiny_options['theme_advanced_buttons4']= "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak";
tiny_options['theme_advanced_toolbar_location']= "top";
tiny_options['theme_advanced_toolbar_align']= "left";
tiny_options['theme_advanced_statusbar_location']= "bottom";
tiny_options['theme_advanced_resizing']= true;
tiny_options['relative_urls']=false;
tiny_options['remove_script_host']=false;
tiny_options['document_base_url']='http://cdn.jobshout.com/';
<?php if(isset($_COOKIE['sitedir']) && $_COOKIE['sitedir']!='') { ?>
tiny_options['moxiemanager_rootpath']= "/vhosts/<?php echo $_COOKIE['sitedir']; ?>/";
tiny_options['moxiemanager_path']= "/vhosts/<?php echo $_COOKIE['sitedir']; ?>/";
<?php } elseif($pSiteRootFolderPath!='') { ?>
tiny_options['moxiemanager_rootpath']= "/vhosts";
tiny_options['moxiemanager_path']= "/vhosts/<?php echo $pSiteRootFolderPath; ?>/";
<?php } ?>
tinymce.init(tiny_options);



</script>


			
		</div>
	</body>
</html>