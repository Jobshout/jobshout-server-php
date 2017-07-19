<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php');

if(isset($_GET['GUID']) && $_GET['GUID']!=''){
	$query_chk="select count(*) as num from kb_article where uuid='".$_GET['GUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: kb_article.php");
	}
}

if(isset($_POST['submit']))
{
			
		
		$Code=$_POST["Code"];
		$site_id=$_POST["site_id"];
		$title=addslashes($_POST["title"]);
		$Body=addslashes(str_replace("http://cdn.jobshout.com","",$_POST["Body"]));
		$MetaTagKeywords=addslashes($_POST["MetaTagKeywords"]);
		$MetaTagDescription=addslashes($_POST["MetaTagDescription"]);
		$UserID=$_POST["UserID"];
		$rev_no=$_POST["rev_no"];
		$status=$_POST["status"];
		$time= time();
		
		$auto_format=1;
		if(isset($_POST["chk_manual"])) {
		$auto_format=0;
		}
		
		$arr_pub_date=explode('/', $_POST["Published_timestamp"]);
		$Published_timestamp=$arr_pub_date[1].'/'.$arr_pub_date[0].'/'.$arr_pub_date[2];
		$pub_time=$_POST["pub_time"];
		if($pub_time==''){
			$pub_time=date('h:i A');
		}
		$pub_time_string=$Published_timestamp." ".$pub_time;		
		$Published_timestamp= strtotime($pub_time_string);
		
		
		$insert=true; 
	$update=true; 
	$insert_cat=true; 
	

	if(isset($_GET['GUID'])) {
	
	$GUID= $_GET['GUID'];
		
		$update = $db->query("UPDATE kb_article SET SiteID='".$site_id."', article_title='$title', code='$Code', article_content_html='$Body', article_content='".strip_tags($Body)."', MetaTagKeywords='$MetaTagKeywords', MetaTagDescription='$MetaTagDescription', UserID='$UserID', Modified='$time', revision_number='$rev_no',
	published_timestamp='$Published_timestamp', auto_format_code=$auto_format, status=$status
	WHERE uuid ='$GUID'");	
						
		}
		else
		{
		$GUID=UniqueGuid('kb_article', 'uuid');	
			$insert = $db->query("INSERT INTO kb_article (ID, SiteID, Created, Modified, article_title, code, article_content_html, article_content, MetaTagKeywords, MetaTagDescription, UserID, Published_timestamp, uuid, auto_format_code, revision_number, status) 
									VALUES(NULL, '".$site_id. "', '$time', '$time', '$title', '$Code', '$Body', '".strip_tags($Body)."','$MetaTagKeywords', '$MetaTagDescription', '$UserID', '$Published_timestamp','$GUID', $auto_format, '$rev_no', '$status')");
									//$db->debug();
	
		}
	
	if($insert || $update) {
	
	$db->query("delete FROM kb_article_categories WHERE SiteID='".$site_id."' AND article_uuid='$GUID'");
	
	if(isset($_POST["category"])) {
		$categories = $_POST["category"];
		if(is_array($categories)) {
			foreach($categories as $categoriesID){
				if($categoriesID != ''){ 
		
					$dcGUID=UniqueGuid('kb_article_categories', 'uuid');	
								
					$insert_cat= $db->query("INSERT INTO kb_article_categories(Created, Modified, SiteID, uuid, article_uuid, category_uuid) 
					VALUES('$time', '$time', '".$site_id."', '$dcGUID', '$GUID', '$categoriesID')");				
				}
			}
		}
	}
	}
	
	if(!isset($_GET['GUID']) && $insert && $insert_cat) {
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:kb_articles.php");
	 }
	 elseif(isset($_GET['GUID']) && $update && $insert_cat) {
	 	 $_SESSION['up_message'] = "Updated successfully";
	 }
	
}


if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$document = $db->get_row("SELECT * FROM kb_article where uuid ='$guid'");

		$documentID=$document->ID;
		$site_id=$document->SiteID;
		$Code=$document->code;
		$title=$document->article_title;
		$rev_no=$document->revision_number;
		$Body=$document->article_content_html;
		$MetaTagKeywords=$document->MetaTagKeywords;
		$MetaTagDescription=$document->MetaTagDescription;
		$UserID=$document->UserID;
		$status=$document->status;
		$document_GUID=$document->uuid;

		$Published_timestamp=$document->published_timestamp;
		$date = Date('d/m/Y',$Published_timestamp);
		$time_string = date('h:i A',$Published_timestamp);
		$auto_format=$document->auto_format_code;
		
		$where_cond=" and SiteID ='".$site_id."' ";

$dc = $db->get_results("SELECT category_uuid FROM kb_article_categories WHERE SiteID='".$site_id."' AND article_uuid='$guid'");
//$db->debug();
if($dc != ''){
 foreach($dc as $dc){
	$dcCategoryId[]= $dc->category_uuid;
	
}
}
}
else {
		$guid= '';
		$documentID='';
		$site_id='';
		$Code='';
		$title='';
		$rev_no='';
		$Body='';
		$MetaTagKeywords='';
		$MetaTagDescription='';
		$UserID='';
		$status=2;
		$document_GUID='';

		$date = date('d/m/Y');
		$time_string=date("h:i A");
		$auto_format=1;
		$dcCategoryId[]='';
		
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
			<a href="kb_articles.php">KB Articles</a>
		</li>
		<li>
			<a href="#">KB Article</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                    
					
					<!--<div><h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add New"; } ?> Page</h3></div><br/>-->
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					 </span></div><br>
					
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Article Editor</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Search Optimisation</a></li>
									<li id="li3"><a href="#tab3" data-toggle="tab">Categories</a></li>
									
								</ul>
								<form action="" method="post" class="form_validation_reg">
								<div class="tab-content">
								
									<div class="tab-pane active" id="tab1">

                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											<!-- Site dropdown starts here-->
											<?php include_once("sites_dropdown.php"); ?>
											<!-- Site dropdown ends here-->
												
										<div class="control-group">
												<label class="control-label">Title<span class="f_req">*</span></label>
												<div class="controls">
													<input type="hidden" value="<?php if($guid!='') { echo $guid; } ?>" name="GUID" class="textbox">
												
													<input type="text" class="span10" name="title" id="title" onKeyUp="generate_code('chk_manual','title','Code')" onBlur="generate_code('chk_manual','title','Code')" value="<?php echo $title;?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											
											
											<div class="control-group">
												<label class="control-label">Content<span class="f_req">*</span></label>
												<div class="controls">
												
													<textarea cols="30" rows="35" class="span10" name="Body" id="Body"><?php echo $Body;?></textarea>
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Revision Number<span class="f_req">*</span></label>
												<div class="controls">
												
													<input type="text" class="span2" name="rev_no" id="rev_no"  value="<?php echo $rev_no;?>" />
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
											
											<div class="control-group">
												<label class="control-label">Status<span class="f_req">*</span></label>
											<div class="controls">	
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($status == 1 || $status == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline"> 
												<input type="radio" value="0" name="status" <?php if($status == 0) { echo ' checked'; } ?>/>												Inactive
												
											</label>
										</div></div>
										
										</fieldset>
									</div>
                                </div>

                                <div class="span3">
									<div class="well form-inline">
										<p class="f_legend">Published By</p>
										<div class="controls">
										<select onChange="" name="UserID" id="UserID" style="width:140px;" >
		<option value="">-- Select User --</option>
		
		</select>
										&nbsp;At 
										<span class="help-block">&nbsp;</span>
										</div><br />
										
										<div class="input-append date" id="dp2" >
									<input class="input-small" placeholder="Published Date" type="text" readonly="readonly"  name="Published_timestamp" id="Published_timestamp" value="<?php echo $date; ?>" data-date-format="dd/mm/yyyy"  /><span class="add-on"><i class="splashy-calendar_day"></i></span>
								</div>
								
								<div>
									<span class="help-block">&nbsp;</span>
									<input type="text" class="span8" id="tp_2" name="pub_time" value="<?php echo $time_string; ?>" readonly="readonly" placeholder="Published Time" />
								<span class="help-block">&nbsp;</span>
								</div>

																			
									</div>
                                </div>


                            </div>

                        </div>
                    </div>
                        


									</div>
									
									
									
									<div class="tab-pane" id="tab2">
										<p>


                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											
											<div class="control-group">
												<label class="control-label">Code<span class="f_req">*</span></label>
												<div class="controls">
													<input type="text" class="span10" name="Code" id="Code" <?php if($auto_format!=0) { ?> readonly="readonly" <?php } ?> value="<?php echo $Code;?>" />
													<span class="help-block">URL (SEO friendly)</span>
													<span class="help-block">
													<input type="checkbox" name="chk_manual" id="chk_manual" value="0" <?php if($auto_format==0) { ?> checked="checked" <?php } ?>  />
													I want to manually enter code</span>

												

												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Meta Data Keywords</label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagKeywords" id="MetaTagKeywords" class="span10"><?php echo $MetaTagKeywords;?></textarea>
													<span class="help-block">Search terms relevant to this page to find content of this page easily</span>
												</div>
												
												
																								<label class="control-label">Meta Data Description</label>
												<div class="controls">
													<textarea cols="30" rows="5" name="MetaTagDescription" id="MetaTagDescription" class="span10"><?php echo $MetaTagDescription;?></textarea>
													<span class="help-block">Overview which describes this page (About 300 words)</span>
												</div>

											</div>
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
                    </div>


										</p>
									</div>
									
									
									<div class="tab-pane" id="tab3">
										<p>
											 <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span9">
									<div class="form-horizontal well">
										<fieldset>
											
											<div class="control-group">
												<label class="control-label">Categories</label>
												<div class="controls" id="cats">
													<select name="category[]" id="category" size="5" multiple="multiple">
													
													<?php
													if($categories=$db->get_results("select * from kb_categories where status='1' $where_cond ")){
													foreach($categories as $category){
													?>
													<option <?php if(in_array($category->uuid, $dcCategoryId)) { echo "selected"; } ?> value="<?php echo $category->uuid; ?>"><?php echo $category->name; ?></option>
													
													<?php } } ?>
													</select>
												</div>
											</div>
											
										</fieldset>
									</div>
                                </div>

                            </div>

                        </div>
                    </div>
										</p>
									</div>
									

								</div>
								
								
								
									
								<button class="btn btn-gebo" type="submit" name="submit" id="submit" >Save changes</button>
								
								
								
								
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
			
			       
            

            <!-- datepicker -->
            <script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
			<!-- timepicker -->
			<script src="lib/datepicker/bootstrap-timepicker.min.js"></script>
			
			  <script type="text/javascript">
			  var val_flag=0;
			$(document).ready(function() {
				//* datepicker
				gebo_datepicker.init();
				$('#tp_2').timepicker({
				defaultTime: '<?php echo $time_string; ?>',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown'
			}); 
			
				
				$("#chk_manual").click(function(){
						var status=$(this).attr("checked");
						if(status=="checked"){
							$('#Code').attr("readonly",false);
							$('#Code').val("");
						}
						else
						{
							$('#Code').attr("readonly",true);
							$('#Code').val("");
							generate_code('chk_manual','title','Code');
						}
					
					});
					
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
						site_id=$(this).val();
						
						if(site_id!=''){
							$.ajax({
								type: "POST",
								url: "return_sitecode.php",
								data: {'site_id' : site_id},
								success: function(response){						
									//$("#UserID").html(response);
									var i, t = tinyMCE.editors;
									for (i in t){
										if (t.hasOwnProperty(i)){
											t[i].remove();
										}
									}
									tiny_options['moxiemanager_rootpath']= "/wwwroot/"+response+"/";
									tiny_options['moxiemanager_path']= "/wwwroot/"+response+"/";
									tinymce.init(tiny_options);
								}
							 });
						}
						
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
					 
					 $.ajax({
						type: "POST",
						url: "kb_cat_list.php",
						data: {'site_id' : site_id, 'type' : 'page'},
						success: function(response){
						
							$("#cats").html(response);
						}
					 });
					
				
			});
			
			$('#submit').click(function(){
				tinyMCE.triggerSave();
				
			});
			
		//* regular validation
		gebo_validation.reg();
		
		$('.splashy-calendar_day').click(function(){
			$('#Published_timestamp').datepicker( "show" );
		});
		
		$(document).click(function(event){
			//console.log($(event.target).closest('div').attr('id'));
			if($(event.target).closest('div').attr('id')!='dp2') {
				$('#Published_timestamp').datepicker( "hide" );
			}
		});
		
			
			});
		
			//* bootstrap datepicker
			gebo_datepicker = {
				init: function() {
					$('#Published_timestamp').datepicker({"autoclose": true});
					/*$(document).click(function(){
			if($('#Published_timestamp').datepicker( "widget" ).is(":visible")) {
				$('#Published_timestamp').datepicker( "hide" );
			}
		});*/
				}
			};
			
			//* validation
	gebo_validation = {
		
        reg: function() {
            reg_validator = $('.form_validation_reg').validate({
			
				onkeyup: false,
				errorClass: 'error',
				validClass: 'valid',
				highlight: function(element) {
					$(element).closest('div').addClass("f_error");
					var err_div_id=$(element).closest('div.tab-pane').attr('id');
								if($("#"+err_div_id).hasClass("active")){
								//$(element).closest('div').addClass("f_error");
								val_flag=1;
								}
								
					else if(!$("#"+err_div_id).hasClass("active") && val_flag==0){
					//$(element).closest('div').addClass("f_error");
					for(var i=1; i<=3; i++) {
						if(err_div_id=="tab"+i){
							$("#tab"+i).addClass("active");
							$("#li"+i).addClass("active");

						}
						else {
							$("#tab"+i).removeClass("active");
							$("#li"+i).removeClass("active");
						}
					}
					}
						


				},
				unhighlight: function(element) {
					$(element).closest('div').removeClass("f_error");
					val_flag=0;
				},
                errorPlacement: function(error, element) {
                    $(element).closest('div').append(error);
                },
                rules: {
					site_id: { required: true },
					title: { required: true },
					Body: { required: true },
					UserID: { required: true },
					Published_timestamp: { required: true },
					Code: { required: true},
					rev_no: { required: true},			
					status: { required: true }
					
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
//tinymce.PluginManager.load('moxiemanager', '/js/moxiemanager/plugin.min.js');

var tiny_options=new Array();
tiny_options['selector']= "textarea#Body";
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
tiny_options['relative_urls']= true;
tiny_options['remove_script_host']= false;
<?php if(isset($_COOKIE['sitedir']) && $_COOKIE['sitedir']!='') { ?>
tiny_options['moxiemanager_rootpath']= "/vhosts/<?php echo $_COOKIE['sitedir']; ?>/";
tiny_options['moxiemanager_path']= "/vhosts/<?php echo $_COOKIE['sitedir']; ?>/";
<?php } elseif($pSiteRootFolderPath!='') { ?>
tiny_options['moxiemanager_rootpath']= "/vhosts/<?php echo $pSiteRootFolderPath; ?>/";
tiny_options['moxiemanager_path']= "/vhosts/<?php echo $pSiteRootFolderPath; ?>/";
<?php } ?>
tinymce.init(tiny_options);


</script>


</div>
	</body>
</html>