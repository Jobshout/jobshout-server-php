<?php
session_start();
?><!DOCTYPE html>
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
                        <button class="close" data-dismiss="modal">�</button>
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
                        <button class="close" data-dismiss="modal">�</button>
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
									<a href="#">Jobs</a>
								</li>
								<li>
									<a href="mongo-job.php">Add a new job</a>
								</li>
							</ul>
						</div>
                    </nav>
					<div id="validation" ><span style="color:#00CC00;font-size:18px">
						<?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo $_SESSION['ins_message']; unset($_SESSION['ins_message']); }?>
					</span></div><br/>
                    <div class="row-fluid">
						<div class="span12">
							<div class="row-fluid sepH_c">
								<div class="row" style="margin-left:0px;float:right;">
									<label style="display:inline;">Search:</label>
									<input type="text" name="srch" id="srch" onKeyUp="search_keyword()">
								</div>
								<input type="hidden" name="total_pages" id="total_pages" value="">
								<table class="table table-striped table-bordered table-condensed">
									<thead>
										<tr>
											<th>ID</th>
											<th>Ref no</th>
											<th>Job title</th>
											<th>Window title</th>
											<th>Last modified</th>
											<th>Posted</th>
											<th>Status</th>
											<th>Edit</th>
											<th>Delete</th>
										</tr>
									</thead>
									<tbody class="data">
									</tbody>
								</table>
								<div>
									<div class="search-amount span6" style="margin:20px 0;"></div>
									<div class="pagination" style="float:right;">
									</div>
								</div>
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
            
            <script src="js/jquery.min.js"></script>
			<!-- smart resize event -->
			<script src="js/jquery.debouncedresize.min.js"></script>
			<!-- js cookie plugin -->
			<script src="js/jquery.cookie.min.js"></script>
			<!-- main bootstrap js -->
			<script src="bootstrap/js/bootstrap.min.js"></script>
			<!-- bootstrap plugins -->
			<script src="js/bootstrap.plugins.min.js"></script>
			<!-- code prettifier -->
			<script src="lib/google-code-prettify/prettify.min.js"></script>
			<!-- tooltips -->
			<script src="lib/qtip2/jquery.qtip.min.js"></script>
			<!-- jBreadcrumbs -->
			<script src="lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
			<!-- sticky messages -->
            <script src="lib/sticky/sticky.min.js"></script>
            <!-- common functions -->
			<script src="js/gebo_common.js"></script>
    
			<script>
				var keyword='',start=1,limit=20,counter;
				var selected_class= 'sel-1';
				function search_keyword(){
					start=1;
					selected_class='sel-1';
					var keyword= document.getElementById('srch').value;
					get_data(selected_class,keyword,start,limit);
				}
				function get_page_no(keyword,page){
					selected_class= 'sel-'+page;
					if(page==1){
						start=page;
					}else{
						start=(page - 1) * limit; 
					}
					get_data(selected_class,keyword,start,limit);
				}
				function get_data(selected_class,keyword,start,limit){
					//var keyword= document.getElementById('srch').value;
					var dataString = 'keyword='+keyword+'&start='+start+'&limit='+limit;
					jsonRow = 'returnJobs.php?'+dataString;
					$.getJSON(jsonRow,function(result){
						var total_pages= result.iTotalRecords;
						$('#total_pages').html(result.iTotalRecords);
						//pagination
							
						
						if(start===1){
							pagestart= start;
							pageend=limit;
						}else{
							pagestart= start+1;
							pageend=start+limit;
						}
						if(total_pages<pageend){
							pageend=total_pages;
						}
						var search='Showing '+pagestart+' to '+pageend+' of '+result.iTotalRecords+' entries';
						$('.search-amount').html(search);
						var lastpage = Math.ceil(total_pages/limit);	
						//console.log(lastpage);
						var pages= '<ul style="box-shadow:none;">';
							//pages += '<li><a href="javascript:void(0)" class="prev"> < Previous </a></li>';
							for (counter = 1; counter <= lastpage; counter++){
								pages += '<li><a href="javascript:void(0)" class="sel-'+counter+'" onclick="get_page_no(\''+keyword+'\','+counter+')">'+counter+'</a></li>';
							}
							//pages += '<li><a href="javascript:void(0)" class="next"> Next > </a></li>';
						pages += '</ul>';
						
						$('.pagination').html(pages);
						$("."+selected_class).addClass('selected');
						
						//data
						var html;
						$.each(result.aaData, function(i,item){
							html +='<tr>';
							html += '<td>'+item.ID+'</td>';
							html += '<td>'+item.Reference+'</td>';
							html += '<td>'+item.Document+'</td>';
							html += '<td>'+item.PageTitle+'</td>';
							html += '<td>'+item.Modified+'</td>';
							html += '<td>'+item.PostedTimestamp+'</td>';
							html += '<td>'+item.Status+'</td>';
							html += '<td><a href="mongo-job.php?GUID='+item.GUID+'" title="Edit this job" ><i class="splashy-pencil"></i><a></td>';
							html += '<td><a href="remove-mongo-job.php?GUID='+item.GUID+'" title="Delete this job" onClick="return confirm(\'Are you sure to delete\');"><i class="splashy-remove"></i><a></td>';
							html += '</tr>';
						});
						$('.data').html(html);
					});
				}
				
				$(document).ready(function() {
					//* calculate sidebar height
					gebo_sidebar.make();
					//* show all elements & remove preloader
					setTimeout('$("html").removeClass("js")',1000);
					get_data(selected_class,keyword,start,limit);
				});
			</script>
		
		</div>
	</body>
</html>