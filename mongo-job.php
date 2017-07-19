<?php 
session_start();
require_once("include/functions.php");
?>
<?php
$conn = new Mongo( 'mongodb://85.92.89.214:27017/' );
$mon_db= $conn->cvscreen;
$collection = $mon_db->documents;
$SiteID=2970;

if(isset($_POST['submit']))
{
	$latitude=0;
	$longitude=0;
		
	if(isset($_POST["WindowTitle"]) && $_POST["WindowTitle"]!='') {
		$new_WindowTitle=addslashes($_POST["WindowTitle"]);
	}else{
		$new_WindowTitle=addslashes($_POST["Document"]);
	}
	$new_PageTitle=addslashes($_POST["PageTitle"]);
	$reference=$_POST["reference"];
	$job_type=$_POST["job_type"];
	$sal_from=$_POST["sal_from"];
	$sal_to=$_POST["sal_to"];
	$sal_dur=$_POST["sal_dur"];
	$new_location=addslashes($_POST["FFAlpha80_1"]);
	$post_code=$_POST["post_code"];
	//$template=$_POST["template"];
	if($_POST["latt"]!=''){
	$latitude=$_POST["latt"];
	}
	if($_POST["long"]!=''){
	$longitude=$_POST["long"];
	}
	//$site_id=$_POST["site_id"];
	
	$auto_format=1;
	if(isset($_POST["chk_manual"])) {
	$auto_format=0;
	}

	$Code=$_POST["Code"];
	$new_Body=addslashes($_POST["Body"]);
	$new_Document=addslashes($_POST["Document"]);
	$new_MetaTagKeywords=addslashes($_POST["MetaTagKeywords"]);
	$new_MetaTagDescription=addslashes($_POST["MetaTagDescription"]);
	//$UserID=$_POST["UserID"];
	//$Active=$_POST["status"];
	$job_status=$_POST["job_status"];
	$time= time();
	
	$Type='job';
	$arr_pub_date=explode('/', $_POST["Published_timestamp"]);
	$Published_timestamp=$arr_pub_date[1].'/'.$arr_pub_date[0].'/'.$arr_pub_date[2];
	$pub_time=$_POST["pub_time"];
	if($pub_time==''){
		$pub_time=date('h:i A');
	}
	$pub_time_string=$Published_timestamp." ".$pub_time;
	$Published_timestamp= strtotime($pub_time_string);
	
	$GUID = $_POST["GUID"];
	
	if(isset($_GET['GUID'])) {
		$update_data= array("Modified" => $time, "Document" => $new_Document, "Code" => $Code, "Body" => $new_Body, "Reference" => $reference, "FFAlpha80_3" => $job_type, "FFReal01" => $sal_from, "FFReal02" => $sal_to, "FFAlpha80_7" => $sal_dur, "FFAlpha80_1" => $new_location, "MetaTagKeywords" => $new_MetaTagKeywords, "MetaTagDescription" => $new_MetaTagDescription, "Title" => $new_PageTitle, "PageTitle" => $new_WindowTitle, "PostedTimestamp" => $Published_timestamp, "Published_timestamp" => $Published_timestamp, "AutoFormatTitle" => $auto_format, "post_code" => $post_code, "latitude" => $latitude, "longitude" => $longitude);
		$collection->update(array("GUID" => $GUID), array('$set' => $update_data));
		$_SESSION['up_message'] = "Updated successfully";
	}else {
		$get_Ids=$collection->find()->sort(array("ID" => -1))->limit(1);
		if($get_Ids->count() >0){
			foreach($get_Ids as $get_Id){
				$ID= $get_Id['ID'] + 1;
			}
		}else{
			$ID= 1;
		}
		$insert_data= array("ID" => $ID, "SiteID" => $SiteID, "Type" => $Type, "Status" => 1, "GUID" =>$_POST["GUID"], "Created" => $time, "Modified" => $time, "Document" => $new_Document, "Code" => $Code, "Body" => $new_Body, "Reference" => $reference, "FFAlpha80_3" => $job_type, "FFReal01" => $sal_from, "FFReal02" => $sal_to, "FFAlpha80_7" => $sal_dur, "FFAlpha80_1" => $new_location, "MetaTagKeywords" => $new_MetaTagKeywords ,"MetaTagDescription" => $new_MetaTagDescription, "Title" => $new_PageTitle, "PageTitle" => $new_WindowTitle, "PostedTimestamp" => $Published_timestamp, "Published_timestamp" => $Published_timestamp, "AutoFormatTitle" => $auto_format, "post_code" => $post_code, "latitude" => $latitude, "longitude" => $longitude);
		$collection->insert($insert_data);
		$_SESSION['ins_message'] = "Inserted successfully ";
	 	header("Location:mongo-jobs.php");
	}
}

if(isset($_GET['GUID'])) {
$guid= $_GET['GUID'];
$document = $collection->findOne(array("GUID" => $guid));
$documentID=$document['ID'];
		$site_id=$document['SiteID'];
		$Code=$document['Code'];
		$Document=$document['Document'];
		$Body=$document['Body'];
		$MetaTagKeywords=$document['MetaTagKeywords'];
		$MetaTagDescription=$document['MetaTagDescription'];
		//$documentUserID=$document['UserID'];
		$PageTitle=$document['Title'];
		$WindowTitle=$document['PageTitle'];
		$auto_format=$document['AutoFormatTitle'];	
		$document_GUID=$document['GUID'];
		$Reference=$document['Reference'];
		
		$job_type=$document['FFAlpha80_3'];
		$sal_from=$document['FFReal01'];
		$sal_to=$document['FFReal02'];
		$sal_dur=$document['FFAlpha80_7'];
		$location=$document['FFAlpha80_1'];
		$post_code=$document['post_code'];
		$latitude=$document['latitude'];
		if($latitude==0) { $latitude=''; }
		$longitude=$document['longitude'];
		if($longitude==0) { $longitude=''; }
		//$zStatus=$document['status'];
		//$publish=$document['PublishCode'];
		//$Sync_LastTime=$document['Sync_LastTime'];
		$Published_timestamp=$document['PostedTimestamp'];
		$date = Date('d/m/Y',$Published_timestamp);
		$time_string = date('h:i A',$Published_timestamp);

		//$doc_template=$document['Template'];
		$where_cond=" and SiteID ='".$site_id."' ";
}else{
$guid= '';
		$documentID='';
		$Document='';
		$site_id='';
		$Code='';
		
		$Body='';
		$MetaTagKeywords='';
		$MetaTagDescription='';
		$documentUserID='';
		$PageTitle='';
		$WindowTitle='';
		$auto_format=1;	
		$document_GUID='';
		$Reference='';
		
		$job_type='';
		$sal_from='';
		$sal_to='';
		$sal_dur='';
		$location='';
		$post_code='';
		$latitude='';
		$longitude='';
		
		$Sync_LastTime='';
		$Published_timestamp='';
		$date = '';
		$time_string=date("h:i A");
		$zStatus=2;
		$publish=2;
		$doc_template='';
		$dcCategoryId[]= '';
		
		$where_cond='';

}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Gebo Admin Panel</title>
    
        <!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
        <!-- gebo blue theme-->
            <link rel="stylesheet" href="css/blue.css" />
        <!-- breadcrumbs-->
            <link rel="stylesheet" href="lib/jBreadcrumbs/css/BreadCrumb.css" />
        <!-- tooltips-->
            <link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
        <!-- notifications -->
            <link rel="stylesheet" href="lib/sticky/sticky.css" />
        <!-- code prettify -->
            <link rel="stylesheet" href="lib/google-code-prettify/prettify.css" />    
        <!-- notifications -->
            <link rel="stylesheet" href="lib/sticky/sticky.css" />    
        <!-- splashy icons -->
            <link rel="stylesheet" href="img/splashy/splashy.css" />
		<!-- datepicker -->
            <link rel="stylesheet" href="lib/datepicker/datepicker.css" />
        <!-- main styles -->
            <link rel="stylesheet" href="css/style.css" />
			
            <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans" />
	
        <!-- Favicon -->
            <link rel="shortcut icon" href="favicon.ico" />
		
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/ie.css" />
        <![endif]-->
        	
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
		<script>
			//* hide all elements & show preloader
			document.getElementsByTagName('html')[0].className = 'js';
		</script>
		<style>
			.table th{
				padding:10px;text-align:center;
			}
			.selected{
			    background-color: #F5F5F5;
			}
		</style>
    </head>
    <body>
		<div id="maincontainer" class="clearfix">
			<!-- header -->
            <header>
                <div class="navbar navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container-fluid">
                            <a class="brand" href="dashboard.html"><i class="icon-home icon-white"></i> Gebo Admin</a>
                            <ul class="nav user_menu pull-right">
                                <li class="hidden-phone hidden-tablet">
                                    <div class="nb_boxes clearfix">
                                        <a data-toggle="modal" data-backdrop="static" href="#myMail" class="label ttip_b" title="New messages">25 <i class="splashy-mail_light"></i></a>
                                        <a data-toggle="modal" data-backdrop="static" href="#myTasks" class="label ttip_b" title="New tasks">10 <i class="splashy-calendar_week"></i></a>
                                    </div>
                                </li>
                                <li class="divider-vertical hidden-phone hidden-tablet"></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Johny Smith <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                    <li><a href="user_profile.html">My Profile</a></li>
                                    <li><a href="javascrip:void(0)">Another action</a></li>
                                    <li class="divider"></li>
                                    <li><a href="login.html">Log Out</a></li>
                                    </ul>
                                </li>
                            </ul>
							<a data-target=".nav-collapse" data-toggle="collapse" class="btn_menu">
								<span class="icon-align-justify icon-white"></span>
							</a>
                            <nav>
                                <div class="nav-collapse">
                                    <ul class="nav">
                                        <li class="dropdown">
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-list-alt icon-white"></i> Forms <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="form_elements.html">Form elements</a></li>
                                                <li><a href="form_extended.html">Extended form elements</a></li>
                                                <li><a href="form_validation.html">Form Validation</a></li>
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-th icon-white"></i> Components <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="alerts_btns.html">Alerts & Buttons</a></li>
                                                <li><a href="icons.html">Icons</a></li>
                                                <li><a href="notifications.html">Notifications</a></li>
                                                
                                                <li><a href="tables.html">Tables</a></li>
                                                <li><a href="tabs_accordion.html">Tabs & Accordion</a></li>
                                                <li><a href="tooltips.html">Tooltips, Popovers</a></li>
                                                <li><a href="typography.html">Typography</a></li>
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-wrench icon-white"></i> Plugins <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="charts.html">Charts</a></li>
                                                <li><a href="calendar.html">Calendar</a></li>
                                                <li><a href="datatable.html">Datatable</a></li>
                                                <li><a href="file_manager.html">File Manager</a></li>
                                                <li><a href="floating_header.html">Floating List Header</a></li>
                                                <li><a href="google_maps.html">Google Maps</a></li>
                                                <li><a href="gallery.html">Gallery Grid</a></li>
                                                <li><a href="wizard.html">Wizard</a></li>
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-file icon-white"></i> Pages <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="error_404.html"> Error 404</a></li>
                                                <li><a href="search_page.html">Search page</a></li>
                                                <li><a href="user_profile.html">User profile</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                        </li>
                                        <li>
                                            <a href="doc.html"><i class="icon-book icon-white"></i> Help</a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="modal hide fade" id="myMail">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">?</button>
                        <h3>New messages</h3>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">In this table jquery plugin turns a table row into a clickable link.</div>
                        <table class="table table-condensed table-striped" data-rowlink="a">
                            <thead>
                                <tr>
                                    <th>Sender</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Size</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Declan Pamphlett</td>
                                    <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                                    <td>23/05/2012</td>
                                    <td>25KB</td>
                                </tr>
                                <tr>
                                    <td>Erin Church</td>
                                    <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                                    <td>24/05/2012</td>
                                    <td>15KB</td>
                                </tr>
                                <tr>
                                    <td>Koby Auld</td>
                                    <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                                    <td>25/05/2012</td>
                                    <td>28KB</td>
                                </tr>
                                <tr>
                                    <td>Anthony Pound</td>
                                    <td><a href="javascript:void(0)">Lorem ipsum dolor sit amet</a></td>
                                    <td>25/05/2012</td>
                                    <td>33KB</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0)" class="btn">Go to mailbox</a>
                    </div>
                </div>
                <div class="modal hide fade" id="myTasks">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">?</button>
                        <h3>New Tasks</h3>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">In this table jquery plugin turns a table row into a clickable link.</div>
                        <table class="table table-condensed table-striped" data-rowlink="a">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Summary</th>
                                    <th>Updated</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>P-23</td>
                                    <td><a href="javascript:void(0)">Admin should not break if URL&hellip;</a></td>
                                    <td>23/05/2012</td>
                                    <td class="tac"><span class="label label-important">High</span></td>
                                    <td>Open</td>
                                </tr>
                                <tr>
                                    <td>P-18</td>
                                    <td><a href="javascript:void(0)">Displaying submenus in custom&hellip;</a></td>
                                    <td>22/05/2012</td>
                                    <td class="tac"><span class="label label-warning">Medium</span></td>
                                    <td>Reopen</td>
                                </tr>
                                <tr>
                                    <td>P-25</td>
                                    <td><a href="javascript:void(0)">Featured image on post types&hellip;</a></td>
                                    <td>22/05/2012</td>
                                    <td class="tac"><span class="label label-success">Low</span></td>
                                    <td>Updated</td>
                                </tr>
                                <tr>
                                    <td>P-10</td>
                                    <td><a href="javascript:void(0)">Multiple feed fixes and&hellip;</a></td>
                                    <td>17/05/2012</td>
                                    <td class="tac"><span class="label label-warning">Medium</span></td>
                                    <td>Open</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0)" class="btn">Go to task manager</a>
                    </div>
                </div>
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
									<a href="mongo-jobs.php">Jobs</a>
								</li>
								<li>
									<a href="#">Job</a>
								</li>
							</ul>
						</div>
                    </nav>
                    
					
					<!--<div><h3 class="heading"><?php //if(isset($_GET['GUID'])) { echo "Update"; } else { echo "Add New"; } ?> Job</h3></div><br/>-->
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
					<?php if(isset($_SESSION['up_message']) && $_SESSION['up_message']!=''){ echo $_SESSION['up_message']; $_SESSION['up_message']=''; }?>
					</span></div><br>
					
                    <div class="row-fluid">
						<div class="">
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li id="li1" class="active"><a href="#tab1" data-toggle="tab">Job Editor</a></li>
									<li id="li2"><a href="#tab2" data-toggle="tab">Search Optimisation</a></li>
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
											<?php //include_once("sites_dropdown.php"); ?>
											<!-- Site dropdown ends here-->
										
										<div class="control-group">
												<label class="control-label">Title<span class="f_req">*</span></label>
												<div class="controls">
													
												
													<input type="text" class="span10" name="Document" id="Document" onKeyUp="generate_code('chk_manual','Document','Code')" onBlur="generate_code('chk_manual','Document','Code')" value="<?php echo $Document;?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Sub Title</label>
												<div class="controls">
													<input type="hidden" value="<?php if($guid!='') { echo $guid; } else { $Guid = NewGuid();
													echo $Guid; } ?>" name="GUID" class="textbox">
												
													<input type="text" class="span10" name="PageTitle" id="PageTitle" value="<?php echo $PageTitle;?>"  />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Body<span class="f_req">*</span></label>
												<div class="controls">
												
													<textarea cols="30" rows="35" class="span10" name="Body" id="Body"><?php echo $Body;?></textarea>
													
													<span class="help-block">&nbsp;</span>
												</div>
											</div>
																						
											

																						<div class="control-group">
												<label class="control-label">Reference</label>
												<div class="controls">
																									
													<input type="text" class="span10" name="reference" id="reference" value="<?php echo $Reference;?>" />
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>

											<div class="control-group">
												<label class="control-label">Job Type<span class="f_req">*</span></label>
												<div class="controls">
													
													<select onChange="" name="job_type" id="job_type" >
														<option value="">-- Select Job Type--</option>
													
														<option <?php if($job_type == 'Temporary') { echo "selected"; } ?> value='Temporary' >Temporary</option>
														<option <?php if($job_type == 'Permanent') { echo "selected"; } ?> value='Permanent' >Permanent</option>
														<option <?php if($job_type == 'Contract') { echo "selected"; } ?> value='Contract' >Contract</option>
														<option <?php if($job_type == 'Freelance') { echo "selected"; } ?> value='Freelance' >Freelance</option>
														<option <?php if($job_type == 'Part time') { echo "selected"; } ?> value='Part time' >Part time</option>
															
													</select>
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Salary<span class="f_req">*</span></label>
												<div class="controls">
													<div class="span4" style="width:80px">
													<input type="text" class="span10" name="sal_from" id="sal_from" value="<?php echo $sal_from;?>"  style="width:80px;" />
													<span class="help-block">&nbsp;</span></div>
													<div class="span4" style="width:20px">&nbsp;To&nbsp;</div>
													<div class="span4" style="width:80px">
													<input type="text" class="span10" name="sal_to" id="sal_to" value="<?php echo $sal_to;?>" style="width:80px;" />
													<span class="help-block">&nbsp;</span></div>
													<div class="span4">
													<select onChange="" name="sal_dur" id="sal_dur" >
				
				<option <?php if($sal_dur == 'per annum') { echo "selected"; } ?> value='per annum' >Per Annum</option>
				<option <?php if($sal_dur == 'per month') { echo "selected"; } ?> value='per month' >Per Month</option>
				<option <?php if($sal_dur == 'per week') { echo "selected"; } ?> value='per week' >Per Week</option>
				<option <?php if($sal_dur == 'per day') { echo "selected"; } ?> value='per day' >Per Day</option>
				<option <?php if($sal_dur == 'per hour') { echo "selected"; } ?> value='per hour' >Per Hour</option>
				
		</select></div>
		
													<span class="help-block">&nbsp;</span>
													
												</div>
											</div>
											<div class="control-group">
												<label class="control-label">Location<span class="f_req">*</span></label>
												
												
													<div class="span4" style="width:130px">
													<input type="text" class="span10" name="FFAlpha80_1" id="FFAlpha80_1" value="<?php echo $location;?>" style="width:130px;" />
													<span class="help-block">&nbsp;</span></div>
													<label class="control-label">PostZipCode</label>
													<div class="span4" style="width:130px">
													<input type="text" class="span10" name="post_code" id="post_code" value="<?php echo $post_code;?>" style="width:130px;" />
													<span class="help-block">&nbsp;</span>
													</div>
													
												
											</div>
											
											<div class="control-group">
												
												
												<label class="control-label">Lattitude</label>
													<div class="span4" style="width:130px">
													<input type="text" class="span10" name="latt" id="latt" value="<?php echo $latitude;?>" style="width:130px;" />
													<span class="help-block">&nbsp;</span></div>
													<label class="control-label">Longitude</label>
													<div class="span4" style="width:130px">
													<input type="text" class="span10" name="long" id="long" value="<?php echo $longitude;?>" style="width:130px;" />
													<span class="help-block">&nbsp;</span>
													</div>
													
												
											</div>
											
											
											
										<div class="control-group">
												<label class="control-label">Job Status <span class="f_req">*</span></label>
											<div class="controls">	
											<label class="radio inline">
												<input type="radio" value="1" name="job_status" <?php if($publish == 1) { echo ' checked'; } ?>/>
												Vacant
											</label>
											<label class="radio inline"> 
												<input type="radio" value="0" name="job_status" <?php if($publish == 0) { echo ' checked'; } ?>/>												Filled
												
											</label>
										</div></div>
											
										</fieldset>
									</div>
                                </div>

                                <div class="span3">
									<div class="well form-inline">
										<p class="f_legend">Posted By</p>
																			
								<div class="input-append date" id="dp2">
									<input class="input-small" placeholder="Posted Date" type="text" readonly="readonly"  name="Published_timestamp" id="Published_timestamp" value="<?php echo $date; ?>"  data-date-format="dd/mm/yyyy" /><span class="add-on"><i class="splashy-calendar_day"></i></span>
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
												<label class="control-label">Window Title</label>
												<div class="controls">
													
												
													<input type="text" class="span10" name="WindowTitle" id="WindowTitle" value="<?php echo $WindowTitle;?>" />
													<span class="help-block">&nbsp;</span>
													
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
                <a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
                <div class="sidebar">
                    <div class="sidebar_inner">
                        <form action="search_page.html" class="input-append" method="post" >
                            <input autocomplete="off" name="query" class="search_query input-medium" size="16" type="text" placeholder="Search..." /><button type="submit" class="btn"><i class="icon-search"></i></button>
                        </form>
                        <div id="side_accordion" class="accordion">
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseOne" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                        <i class="icon-folder-close"></i> Content
                                    </a>
                                </div>
                                <div class="accordion-body collapse" id="collapseOne">
                                    <div class="accordion-inner">
                                        <ul class="nav nav-list">
                                            <li><a href="javascript:void(0)">Articles</a></li>
                                            <li><a href="javascript:void(0)">News</a></li>
                                            <li><a href="javascript:void(0)">Newsletters</a></li>
                                            <li><a href="javascript:void(0)">Comments</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseTwo" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                        <i class="icon-th"></i> Modules
                                    </a>
                                </div>
                                <div class="accordion-body collapse" id="collapseTwo">
                                    <div class="accordion-inner">
                                        <ul class="nav nav-list">
                                            <li><a href="javascript:void(0)">Content blocks</a></li>
                                            <li><a href="javascript:void(0)">Tags</a></li>
                                            <li><a href="javascript:void(0)">Blog</a></li>
                                            <li><a href="javascript:void(0)">FAQ</a></li>
                                            <li><a href="javascript:void(0)">Formbuilder</a></li>
                                            <li><a href="javascript:void(0)">Location</a></li>
                                            <li><a href="javascript:void(0)">Profiles</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseThree" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                        <i class="icon-user"></i> Account manager
                                    </a>
                                </div>
                                <div class="accordion-body collapse" id="collapseThree">
                                    <div class="accordion-inner">
                                        <ul class="nav nav-list">
                                            <li><a href="javascript:void(0)">Members</a></li>
                                            <li><a href="javascript:void(0)">Members groups</a></li>
                                            <li><a href="javascript:void(0)">Users</a></li>
                                            <li><a href="javascript:void(0)">Users groups</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseFour" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                        <i class="icon-cog"></i> Configuration
                                    </a>
                                </div>
                                <div class="accordion-body collapse in" id="collapseFour">
                                    <div class="accordion-inner">
                                        <ul class="nav nav-list">
                                            <li class="nav-header">People</li>
                                            <li class="active"><a href="javascript:void(0)">Account Settings</a></li>
                                            <li><a href="javascript:void(0)">IP Adress Blocking</a></li>
                                            <li class="nav-header">System</li>
                                            <li><a href="javascript:void(0)">Site information</a></li>
                                            <li><a href="javascript:void(0)">Actions</a></li>
                                            <li><a href="javascript:void(0)">Cron</a></li>
                                            <li class="divider"></li>
                                            <li><a href="javascript:void(0)">Help</a></li>
                                        </ul> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar_info">
                            <ul class="unstyled">
                                <li>
                                    <span class="act act-warning">65</span>
                                    <strong>New comments</strong>
                                </li>
                                <li>
                                    <span class="act act-success">10</span>
                                    <strong>New articles</strong>
                                </li>
                                <li>
                                    <span class="act act-danger">85</span>
                                    <strong>New registrations</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
			</aside>
            
            <?php require_once('include/footer.php');?>
			
			       
            

            <!-- datepicker -->
            <script src="lib/datepicker/bootstrap-datepicker.min.js"></script>
			<!-- timepicker -->
            <script src="lib/datepicker/bootstrap-timepicker.min.js"></script>
			
			<script>
			var val_flag=0;
				$(document).ready(function() {
					//* regular validation
					$.validator.setDefaults({
					ignore: ""
				});
				
					gebo_validation.reg();
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
							generate_code('chk_manual','Document','Code');
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
					
			$('#submit').click(function(){
				tinyMCE.triggerSave();
			});
			
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
								
								val_flag=1;
								}
								
					else if(!$("#"+err_div_id).hasClass("active") && val_flag==0){
					//$(element).closest('div').addClass("f_error");
					for(var i=1; i<=4; i++) {
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
								//site_id: { required: true },
								Document: { required: true },
								Body: { required: true },
								job_type: { required: true },
								sal_from: { required: true },
								sal_to: { required: true },
								sal_dur: { required: true },
								FFAlpha80_1: { required: true },
								//UserID: { required: true },
								Published_timestamp: { required: true },
								Code: { required: true },
								//status: { required: true },
								job_status: { required: true }
								
							},
							invalidHandler: function(form, validator) {
								$.sticky("There are some errors. Please corect them and submit again.", {autoclose : 5000, position: "top-right", type: "st-error" });
							}
						})
					}
				};
			</script>
			
			
       <script type="text/javascript" src="tinymce/tinymce.min.js"></script>
<script type="text/javascript">
//tinymce.PluginManager.load('moxiemanager', '/js/moxiemanager/plugin.min.js');

tinymce.init({
	selector: "textarea#Body",
   
	plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste "
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
	autosave_ask_before_unload: false
});


</script>


</div>
	</body>
</html>