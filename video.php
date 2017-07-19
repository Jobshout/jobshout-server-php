<?php
require_once("include/lib.inc.php");

require_once('include/main-header.php');

if(isset($_GET['UUID']) && $_GET['UUID']!=''){
	$query_chk="select count(*) as num from videos where uuid='".$_GET['UUID']."'";
	if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
		$query_chk.=" and SiteID in ('".$_SESSION['site_id']."') ";
	}
	$chk_num=$db->get_var($query_chk);
	if($chk_num==0){
		header("Location: video.php");
	}
}


				if(isset($_POST['submit']))
			
				{
				
				
				/*if($_FILES['video_src']['name']!='')
				 {
					$file_name_arr=explode(".",$_FILES['video_src']['name']);
					$file_name_ext=$file_name_arr[count($file_name_arr)-1];
					
					  $bnr_picture_name = $file_name_arr[0] .'-'.md5(uniqid(rand(), true)).'.'.$file_name_ext;
					  copy($_FILES['video_src']['tmp_name'],  'videos/'.$bnr_picture_name);
				  }*/
				
					/*if($_POST['GUID'] != '' && $_POST['title'] !='' && $_POST['video_src'] !='' && $_POST['url'] !='' && $_POST['video_tags'] !='' && $_POST['categories'] !='')
					{*/
					/*$time= date("Y-m-d H:i:s");
					$unix_timestamp= strtotime($time);*/
					//echo $unix_timestamp;
					
				    $time= date("Y-m-d H:i:s");
					$unix_timestamp= strtotime($time);
					
					$title = addslashes($_POST["title"]);
					$video_src = addslashes(str_replace("http://cdn.jobshout.com","",$_POST['video_src']));
					//$url = $_POST["url"];
					$video_tags = addslashes($_POST["video_tags"]);
					/*$category_gropuID = $_POST["category_gropuID"];*/
					//$fts = $_POST["fts"];
					$Active = $_POST["status"];
				    //$category_gropuID=$_POST["category_gropuID"];
					$zStatus=$_POST["status"];
					$arr_pub_date=explode('/', $_POST["Sync_Modified"]);
					$Published_timestamp=$arr_pub_date[1].'/'.$arr_pub_date[0].'/'.$arr_pub_date[2];
					$pub_time=$_POST["pub_time"];
					if($pub_time==''){
						$pub_time=date('h:i A');
					}
					$pub_time_string=$Published_timestamp." ".$pub_time;
					$published_timestamp=strtotime($pub_time_string);
					$site_id=$_POST["site_id"];
					
					
					/*FTS*/
 //$cat_group_name=$db->get_var("SELECT Name FROM `categorygroups` WHERE ID=".$category_gropuID."");
 $cat_group_name='';

 if($Active==1){ $status="Active"; } else { $status="Inactive"; }
 
 $fts=$title.' '.$cat_group_name.' '.$video_src.' '.$video_tags;
 
 
 
        if(isset($_GET['UUID'])){
				$UUID = $_GET["UUID"];	
				//echo "UPDATE videos set SiteID=$site_id,categories = '$category_gropuID', title = '$title', video_src = '$video_src', video_tags = '$video_tags', active = '$Active', modified = '$unix_timestamp' where uuid = '$UUID' ";
					
			if($db->query("UPDATE videos set SiteID=$site_id, title = '$title', video_src = '$video_src', video_tags = '$video_tags', active = '$Active',published_timestamp='$published_timestamp', modified = '$unix_timestamp' where uuid = '$UUID' ")) {
						$_SESSION['up_message']= "Updated successfully ";
						
						}
								
							}
				
  
  
           else
				{
            		//echo "INSERT INTO videos (uuid,SiteID, categories, title, published_timestamp, video_src, url, video_tags, fts, active, created, modified)  VALUES ('$UUID','".$site_id."','$category_gropuID','$title','$unix_timestamp','$video_src','','$video_tags','$fts','$Active','$unix_timestamp', '$unix_timestamp')";
				$UUID = UniqueGuid('videos', 'uuid');
				
			if($db->query("INSERT INTO videos (uuid,SiteID, title, video_src, url, video_tags, fts, active,published_timestamp, created, modified, categories)  VALUES ('$UUID','".$site_id."','$title','$video_src','','$video_tags','$fts','$Active','$published_timestamp','$unix_timestamp','$unix_timestamp', '')")) {        
			
			//$db->debug();
			$_SESSION['ins_message']= "Inserted successfully";
			header("Location:videos.php");
			
			}
							
				}
				
			}
				
				 if(isset($_GET['UUID'])){
				 
				 
				 //echo "SELECT uuid, categories, title, video_src, video_tags, active FROM videos where uuid = '".$_REQUEST['GUID']."'";
			
$user3 = $db->get_row("SELECT SiteId,uuid, categories, title, video_src, video_tags, active,published_timestamp FROM videos where uuid = '".$_GET['UUID']."'");
					$site_id=$user3->SiteId;
                    $uuid=$user3->uuid;
					$categories=$user3->categories;
					$title=$user3->title;
					$video_src=$user3->video_src;
					$video_tags=$user3->video_tags;
					$active=$user3->active;
					$CategoryGroupID=$user3->categories;
					$zStatus=$user3->active;
					$published_timestamp=date("d/m/Y",$user3->published_timestamp);
					$time_string = date('h:i A',$user3->published_timestamp);
					$where_cond=" and SiteID ='".$site_id."' ";
					
					
					}
					 else
		  {
		  		   $site_id='';
		           $uuid='';
				   $categories='';
				   $title='';
				   $video_src='';
				   $video_tags='';
				   $Telephone='';
				   $active='';
			       $CategoryGroupID=''; 
				   $zStatus=2;
				   $published_timestamp=date('d/m/Y');;
				   $time_string=date("h:i A");
				   
				   $where_cond='';
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$where_cond=" and SiteID in ('".$_SESSION['site_id']."') ";
	}

		  }
		    
//$db->debug();
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
			<a href="videos.php">Videos</a>
		</li>
		<li>
			<a href="#">Video</a>
		</li>
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                   
					<!--<h3 class="heading"><?php //if(isset($_GET['UUID'])) { echo 'Update Video'; } else { echo 'Add New Video'; } ?> </h3>-->
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					</span></div><br/>
							<br>
                    <div class="row-fluid">
					
						<div class="span12">
							
							
							
							<div class="row-fluid">
							
							
								<div class="span8">
									<form class="form-horizontal form_validation_reg" name="form1" id="form1" enctype="multipart/form-data" method="post" >
									
								
										<fieldset>
										
										
							<?php
											//$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
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
											
											<?php
											} else {
										?>
										<input type="hidden" name="site_id" id="site_id" value="<?php if($site_id!='') { echo $site_id; } else { echo $_SESSION['site_id']; } ?>" >
										<?php
										} }
										?>	
										
										
												<!--<div class="control-group formSep">
												<label class="control-label">Categories</label>
												<div class="controls">
												
												 <select onChange="" name="category_gropuID" id="cat_grp" >
													<option value="0"  >--Not Specified--</option>
												  
												   </select>
																						
											</div></div>-->
											<div class="control-group formSep">
												<label class="control-label">Title<span class="f_req">*</span></label>
												<div class="controls text_line">
													<input type="hidden" value="<?php if($uuid!=''){ echo $uuid; } ?>" name="UUID" id="UUID" >
													<input type="text"  name="title" id="title" class="input-xlarge" value="<?php echo $title; ?>">
												</div></div>
												
												<div class="control-group formSep">
												<label class="control-label">Source<span class="f_req">*</span></label>
												<div class="controls text_line">
													
													<textarea name="video_src" id="video_src" rows="5" cols="15"><?php echo $video_src ; ?></textarea>
												</div></div>
												
												
												<!--
												<div class="control-group formSep">
												<label class="control-label">Upload Video</label>
												<div class="controls text_line">
												<input name="video_src" type="file" id="video_src" >
												</div></div>-->
												
												
												
																							
												<div class="control-group formSep">
												<label class="control-label">Tags<span class="f_req">*</span></label>
												<div class="controls text_line">
													
													<textarea  name="video_tags" id="video_tags"  rows="3" cols="20" ><?php echo $video_tags ; ?></textarea>
												</div></div>
												
												
										<div class="control-group formSep">
												<label class="control-label">Status <span class="f_req">*</span></label>
											<div class="controls text_line">	
											<label class="radio inline">
												<input type="radio" value="1" name="status" <?php if($zStatus == 1 || $zStatus == 2) { echo ' checked'; } ?>/>
												Active
											</label>
											<label class="radio inline"> 
												<input type="radio" value="0" name="status" <?php if($zStatus == 0) { echo ' checked'; } ?>/>												Inactive
												
											</label>
										</div></div>
									
												
												
												<div class="control-group formSep">
												<label class="control-label">Published<span class="f_req">*</span></label>
												<div class="controls text_line">
												<div class="input-append date" id="dp2">
									<input class="input-small"  type="text" readonly="readonly"  name="Sync_Modified" id="Sync_Modified" value="<?php echo $published_timestamp; ?>" data-date-format="dd/mm/yyyy"  /><span class="add-on"><i class="splashy-calendar_day"></i></span>
								</div>
								
								<div>
									<span class="help-block">&nbsp;</span>
									<input type="text" class="span3" id="tp_2" name="pub_time" value="<?php echo $time_string; ?>" readonly="readonly" placeholder="Published Time" />
								<span class="help-block">&nbsp;</span>
								</div>
													
													
												</div></div>
												
												
												
												
												<div class="form-actions">
													<button class="btn btn-gebo" type="submit" name="submit" id="submit">Submit</button>
												
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
			 
			  <!-- datepicker -->
            <script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
			<!-- timepicker -->
            <script src="lib/datepicker/bootstrap-timepicker.min.js"></script>


  <script>
				$(document).ready(function() {
					//* regular validation
					
					gebo_validation.reg();
					//* datepicker
				
					gebo_datepicker.init();
					
					$('#tp_2').timepicker({
				defaultTime: '<?php echo $time_string; ?>',
				minuteStep: 1,
				disableFocus: true,
				template: 'dropdown'
			});

			$('#submit').click(function(){
				tinyMCE.triggerSave();
			});	
			
			$('.splashy-calendar_day').click(function(){
				$('#Sync_Modified').datepicker( "show" );
			});
			
			$(document).click(function(event){
				//console.log($(event.target).closest('div').attr('id'));
				if($(event.target).closest('div').attr('id')!='dp2') {
					$('#Sync_Modified').datepicker( "hide" );
				}
			});	
			
			$("#site_id_sel").change(function(){
					var site_id=$(this).val();
						
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
					});
			
			
				});
				
				
					
					
				gebo_datepicker = {
				init: function() {
					$('#Sync_Modified').datepicker({"autoclose": true});	
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
							},
							unhighlight: function(element) {
								$(element).closest('div').removeClass("f_error");
							},
							errorPlacement: function(error, element) {
								$(element).closest('div').append(error);
							},
							rules: {
							 category_gropuID: { required: true },
								 title: { required: true },
								  site_id: { required: true },
								   
								video_src: { required: true },
								 video_tags: { required: true },
								status:{ required: true },
								Sync_Modified:{ required: true }
								
								
								
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
tiny_options['selector']= "textarea#video_src";
tiny_options['theme']= "modern";
tiny_options['plugins']= ["advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",       
        "emoticons template paste ",       
        " media moxiemanager",
		"print preview hr anchor pagebreak"       
    ];
tiny_options['toolbar1']= " preview | media";

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

/*tinymce.init({
	selector: "textarea#video_src",
   theme: "modern",
	plugins: [
	
		"advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
       
        "emoticons template paste ",
       
        " media",
		"print preview hr anchor pagebreak"
       
    ],
    toolbar1: " preview | media",

});*/
</script>
            
		</div>
	</body>
</html>
