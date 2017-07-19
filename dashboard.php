<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Jobshout Admin Panel</title>
    
        <!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
        <!-- gebo dark theme change dark to blue brown or green-->
            <link rel="stylesheet" href="css/dark.css" id="link_theme" />
        <!-- breadcrumbs-->
            <link rel="stylesheet" href="lib/jBreadcrumbs/css/BreadCrumb.css" />
        <!-- tooltips-->
            <link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
        <!-- colorbox -->
            <link rel="stylesheet" href="lib/colorbox/colorbox.css" />    
        <!-- code prettify -->
            <link rel="stylesheet" href="lib/google-code-prettify/prettify.css" />    
        <!-- notifications -->
            <link rel="stylesheet" href="lib/sticky/sticky.css" />    
        <!-- splashy icons -->
            <link rel="stylesheet" href="img/splashy/splashy.css" />
		<!-- calendar -->
            <link rel="stylesheet" href="lib/fullcalendar/fullcalendar_gebo.css" />
            
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
            <script src="lib/flot/excanvas.min.js"></script>
        <![endif]-->
		<script>
			//* hide all elements & show preloader
			document.getElementsByTagName('html')[0].className = 'js';
		</script>
    </head>
    <body>
		
		<!-- style switcher -->
		<!-- div class="style_switcher">
            <a href="javascript:void(0)" class="blue_theme th_active" title="blue">blue</a>
            <a href="javascript:void(0)" class="dark_theme" title="dark">dark</a>
            <a href="javascript:void(0)" class="green_theme" title="green">green</a>
            <a href="javascript:void(0)" class="brown_theme" title="brown">brown</a>
        </div-->
		
		<div id="maincontainer" class="clearfix">
			<!-- header -->
            <header>
                <div class="navbar navbar-fixed-top">
                    <div class="navbar-inner">
                        <div class="container-fluid">
                            <a class="brand" href="dashboard.html"><i class="icon-home icon-white"></i> Jobshout Admin</a>
                            <ul class="nav user_menu pull-right">
                                <li class="hidden-phone hidden-tablet">
                                    <div class="nb_boxes clearfix">
                                        <a data-toggle="modal" data-backdrop="static" href="#myMail" class="label ttip_b" title="New messages">25 <i class="splashy-mail_light"></i></a>
                                        <a data-toggle="modal" data-backdrop="static" href="#myTasks" class="label ttip_b" title="New tasks">10 <i class="splashy-calendar_week"></i></a>
                                    </div>
                                </li>
                                <li class="divider-vertical hidden-phone hidden-tablet"></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Joe Blogg ( Propel london ) <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                    <li><a href="user_profile.html">My Profile</a></li>
                                    <li><a href="javascrip:void(0)">My Sites ( switch )</a></li>
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
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-list-alt icon-white"></i> CRM/ATS <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="form_elements.html">Jobs</a></li>
                                                <li><a href="form_validation.html">Candidates</a></li>
                                                <li><a href="form_validation.html">Customers</a></li>
                                                <li><a href="form_extended.html">Contacts</a></li>
                                                <li><a href="form_extended.html">Advertisers</a></li>
                                                <li><a href="notifications.html">Projects</a></li>
                                                <li><a href="notifications.html">Timeslips</a></li>
                                                <li><a href="notifications.html">Documents</a></li>
                                                <li><a href="notifications.html">Notifications</a></li>
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-th icon-white"></i> Statistics <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="icons.html">Jobs</a></li>
                                                <li><a href="alerts_btns.html">Job Applicants</a></li>
                                                <li><a href="error_404.html"> Searches</a></li>
                                                <li><a href="gallery.html">Traffic sources</a></li>
                                                <li><a href="tabs_accordion.html">Users (Consultants)</a></li>
                                                <li><a href="tabs_accordion.html">Orders</a></li>
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-wrench icon-white"></i> Activity <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="file_manager.html">Overview</a></li>
                                                <li><a href="calendar.html">Calendar</a></li>
                                                <li><a href="datatable.html">Messages</a></li>
                                                <li><a href="form_validation.html">Job Applications</a></li>
                                                <li><a href="alerts_btns.html">Blog comments</a></li>
                                                <li><a href="notifications.html">Tasks</a></li>
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-file icon-white"></i> Pages <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="icons.html"> Blog</a></li>
                                                <li><a href="search_page.html">Pages</a></li>
                                                <li><a href="user_profile.html">Tokens</a></li>
                                                <li><a href="user_profile.html">Components</a></li>
                                                <li><a href="error_404.html">Dynamic pages</a></li>
                                                 <li><a href="user_profile.html">Templates</a></li>
                                               <li><a href="gallery.html">Image Gallery</a></li>
                                                <li><a href="tabs_accordion.html">Tabs & Accordion</a></li>
                                                <li><a href="tooltips.html">Tooltips, Popovers</a></li>
                                                <li><a href="typography.html">Typography</a></li>

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
                        <button class="close" data-dismiss="modal">×</button>
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
                        <button class="close" data-dismiss="modal">×</button>
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
                    
					<div class="row-fluid">
						<div class="span12 tac">
							<ul class="ov_boxes">
								<li>
									<div class="p_bar_up p_canvas">2,4,9,7,12,8,16</div>
									<div class="ov_text">
										<strong>120</strong>
										Jobs added <small>last week</small>
									</div>
								</li>
								<li>
									<div class="p_bar_down p_canvas">20,15,18,14,10,13,9,7</div>
									<div class="ov_text">
										<strong>2000</strong>
										Jobs applications <small>last week</small>
									</div>
								</li>
								<li>
									<div class="p_line_up p_canvas">3,5,9,7,12,8,16</div>
									<div class="ov_text">
										<strong>2304</strong>
										Unique visitors (last 24h)
									</div>
								</li>
								<li>
									<div class="p_line_down p_canvas">20,16,14,18,15,14,14,13,12,10,10,8</div>
									<div class="ov_text">
										<strong>30240</strong>
										Unique visitors (last week)
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span5 ">
							<h3 class="heading">Visitors by Country <small>last week</small></h3>
							<div id="fl_2" style="height:200px;width:80%;margin:50px auto 0"></div>
						</div>
						<div class="span7">
							<div class="heading clearfix">
								<h3 class="pull-left">Conversion rate</h3>
								<span class="pull-right label label-info ttip_t" title="Here is a sample info tooltip">Info</span>
							</div>
							<div id="fl_1" style="height:270px;width:100%;margin:15px auto 0"></div>
						</div>
					</div>
                    <div class="row-fluid">
                        <div class="span6">
							<div class="heading clearfix">
								<h3 class="pull-left">Latest jobs&nbsp;<small><a href=#>(view all)</a></small></h3>
								<span class="pull-right label label-important">10</span>
							</div>
							<table class="table table-striped table-bordered mediaTable">
								<thead>
									<tr>
										<th class="optional">Ref</th>
										<th class="essential persist">Title</th>
										<th class="essential">Owner</th>
										<th class="optional">Responses</th>
										<th class="optional">Date Added</th>
										<th class="essential">Action</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>134</td>
										<td>Summer Throssell</td>
										<td>Pending</td>
										<td>24/04/2012</td>
										<td>$120.23</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>
									<tr>
										<td>133</td>
										<td>Declan Pamphlett</td>
										<td>Pending</td>
										<td>23/04/2012</td>
										<td>$320.00</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>
									<tr>
										<td>132</td>
										<td>Erin Church</td>
										<td>Pending</td>
										<td>23/04/2012</td>
										<td>$44.00</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>
									<tr>
										<td>131</td>
										<td>Koby Auld</td>
										<td>Pending</td>
										<td>22/04/2012</td>
										<td>$180.20</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>
									<tr>
										<td>130</td>
										<td>Anthony Pound</td>
										<td>Pending</td>
										<td>20/04/2012</td>
										<td>$610.42</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>									<tr>
										<td>134</td>
										<td>Summer Throssell</td>
										<td>Pending</td>
										<td>24/04/2012</td>
										<td>$120.23</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>
									<tr>
										<td>133</td>
										<td>Declan Pamphlett</td>
										<td>Pending</td>
										<td>23/04/2012</td>
										<td>$320.00</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>
									<tr>
										<td>132</td>
										<td>Erin Church</td>
										<td>Pending</td>
										<td>23/04/2012</td>
										<td>$44.00</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>
									<tr>
										<td>131</td>
										<td>Koby Auld</td>
										<td>Pending</td>
										<td>22/04/2012</td>
										<td>$180.20</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>
									<tr>
										<td>130</td>
										<td>Anthony Pound</td>
										<td>Pending</td>
										<td>20/04/2012</td>
										<td>$610.42</td>
										<td>
											<a href="#" title="Edit"><i class="splashy-document_letter_edit"></i></a>
											<a href="#" title="Accept"><i class="splashy-document_letter_okay"></i></a>
											<a href="#" title="Remove"><i class="splashy-document_letter_remove"></i></a>
										</td>
									</tr>
								</tbody>
							</table>
                        </div>
                        <div class="span6">
							<div class="heading clearfix">
								<h3 class="pull-left">Latest job applicants <small>(<a href=#>view all</a>)</small></h3>
								<span class="pull-right label label-success">10</span>
							</div>
							<div id="small_grid">
								<ul>
									<li class="thumbnail">
										<a title="Image_4 title long title long title long" href="gallery/Image04.jpg">
											<img alt="" src="gallery/Image04_tn.jpg">
										</a>
										<p>
											<span>Mr Joe Blogg<br>Online</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_5 title long title long title long" href="gallery/Image05.jpg">
											<img alt="" src="gallery/Image05_tn.jpg">
										</a>
										<p>
											<span>Mr Joe Blogg<br>24/05/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_6 title long title long title long" href="gallery/Image06.jpg">
											<img alt="" src="gallery/Image06_tn.jpg">
										</a>
										<p>
											<span>Mr Joe Blogg<br>27/06/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_7 title long title long title long" href="gallery/Image07.jpg">
											<img alt="" src="gallery/Image07_tn.jpg">
										</a>
										<p>
											<span>338KB<br>22/06/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_8 title long title long title long" href="gallery/Image08.jpg">
											<img alt="" src="gallery/Image08_tn.jpg">
										</a>
										<p>
											<span>381KB<br>25/06/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_9 title long title long title long" href="gallery/Image09.jpg">
											<img alt="" src="gallery/Image09_tn.jpg">
										</a>
										<p>
											<span>176KB<br>11/06/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_10 title long title long title long" href="gallery/Image10.jpg">
											<img alt="" src="gallery/Image10_tn.jpg">
										</a>
										<p>
											<span>380KB<br>20/06/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_11 title long title long title long" href="gallery/Image11.jpg">
											<img alt="" src="gallery/Image11_tn.jpg">
										</a>
										<p>
											<span>340KB<br>17/06/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_12 title long title long title long" href="gallery/Image12.jpg">
											<img alt="" src="gallery/Image12_tn.jpg">
										</a>
										<p>
											<span>191KB<br>27/05/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_13 title long title long title long" href="gallery/Image13.jpg">
											<img alt="" src="gallery/Image13_tn.jpg">
										</a>
										<p>
											<span>314KB<br>24/05/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_14 title long title long title long" href="gallery/Image14.jpg">
											<img alt="" src="gallery/Image14_tn.jpg">
										</a>
										<p>
											<span>141KB<br>17/06/2012</span>
										</p>
									</li>
									<li class="thumbnail">
										<a title="Image_15 title long title long title long" href="gallery/Image15.jpg">
											<img alt="" src="gallery/Image15_tn.jpg">
										</a>
										<p>
											<span>183KB<br>13/05/2012</span>
										</p>
									</li>
									 
								</ul>
							</div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span8">
							<h3 class="heading">Calendar</h3>
							<div id="calendar"></div>
                        </div>
                        <div class="span4" id="user-list">
							<h3 class="heading">Users <small>last 24 hours</small></h3>
							<div class="row-fluid">
								<div class="input-prepend">
									<span class="add-on ad-on-icon"><i class="icon-user"></i></span><input type="text" class="user-list-search search" placeholder="Search user" />
								</div>
								<ul class="nav nav-pills line_sep">
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#">Sort by <b class="caret"></b></a>
										<ul class="dropdown-menu sort-by">
											<li><a href="javascript:void(0)" class="sort" data-sort="sl_name">by name</a></li>
											<li><a href="javascript:void(0)" class="sort" data-sort="sl_status">by status</a></li>
										</ul>
									</li>
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#">Show <b class="caret"></b></a>
										<ul class="dropdown-menu filter">
											<li class="active"><a href="javascript:void(0)" id="filter-none">All</a></li>
											<li><a href="javascript:void(0)" id="filter-online">Online</a></li>
											<li><a href="javascript:void(0)" id="filter-offline">Offline</a></li>
										</ul>
									</li>
								</ul>
							</div>
							<ul class="list user_list">
								<li>
									<span class="label label-success pull-right sl_status">online</span>
									<a href="#" class="sl_name">John Doe</a><br />
									<small class="s_color sl_email">johnd@example1.com</small>
								</li>
								<li>
									<span class="label label-success pull-right sl_status">online</span>
									<a href="#" class="sl_name">Kate Miller</a><br />
									<small class="s_color sl_email">kmiller@example1.com</small>
								</li>
								<li>
									<span class="label label-important pull-right sl_status">offline</span>
									<a href="#" class="sl_name">James Vandenberg</a><br />
									<small class="s_color sl_email">jamesv@example2.com</small>
								</li>
								<li>
									<span class="label label-important pull-right sl_status">offline</span>
									<a href="#" class="sl_name">Donna Doerr</a><br />
									<small class="s_color sl_email">donnad@example3.com</small>
								</li>
								<li>
									<span class="label label-important pull-right sl_status">offline</span>
									<a href="#" class="sl_name">Perry Weitzel</a><br />
									<small class="s_color sl_email">perryw@example2.com</small>
								</li>
								<li>
									<span class="label label-success pull-right sl_status">online</span>
									<a href="#" class="sl_name">Charles Bledsoe</a><br />
									<small class="s_color sl_email">charlesb@example3.com</small>
								</li>
								<li>
									<span class="label label-important pull-right sl_status">offline</span>
									<a href="#" class="sl_name">Wendy Proto</a><br />
									<small class="s_color sl_email">wnedyp@example1.com</small>
								</li>
								<li>
									<span class="label label-success pull-right sl_status">online</span>
									<a href="#" class="sl_name">Nancy Ibrahim</a><br />
									<small class="s_color sl_email">nancyi@example2.com</small>
								</li>
								<li>
									<span class="label label-important pull-right sl_status">offline</span>
									<a href="#" class="sl_name">Eric Cantrell</a><br />
									<small class="s_color sl_email">ericc@example4.com</small>
								</li>
								<li>
									<span class="label label-success pull-right sl_status">online</span>
									<a href="#" class="sl_name">Andre Hill</a><br />
									<small class="s_color sl_email">andreh@example2.com</small>
								</li>
								<li>
									<span class="label label-success pull-right sl_status">online</span>
									<a href="#" class="sl_name">Laura Taggart</a><br />
									<small class="s_color sl_email">laurat@example3.com</small>
								</li>
								<li>
									<span class="label label-important pull-right sl_status">offline</span>
									<a href="#" class="sl_name">Doug Singer</a><br />
									<small class="s_color sl_email">dougs@example2.com</small>
								</li>
								<li>
									<span class="label label-success pull-right sl_status">online</span>
									<a href="#" class="sl_name">Douglas Arnott</a><br />
									<small class="s_color sl_email">douglasa@example1.com</small>
								</li>
								<li>
									<span class="label label-important pull-right sl_status">offline</span>
									<a href="#" class="sl_name">Lauren Henley</a><br />
									<small class="s_color sl_email">laurenh@example3.com</small>
								</li>
								<li>
									<span class="label label-important pull-right sl_status">offline</span>
									<a href="#" class="sl_name">William Jardine</a><br />
									<small class="s_color sl_email">williamj@example4.com</small>
								</li>
								<li>
									<span class="label label-success pull-right sl_status">online</span>
									<a href="#" class="sl_name">Yves Ouellet</a><br />
									<small class="s_color sl_email">yveso@example2.com</small>
								</li>
							</ul>
							<div class="pagination"><ul class="paging bottomPaging"></ul></div>
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
                                        <i class="icon-folder-close"></i> Favorites
                                    </a>
                                </div>
                                <div class="accordion-body collapse in" id="collapseOne">
                                    <div class="accordion-inner">
                                        <ul class="nav nav-list">
                                            <li class="active"><a href="javascript:void(0)">Dashboard</a></li>
                                            <li><a href="javascript:void(0)">Activity</a></li>
                                            <li><a href="javascript:void(0)">Messages</a></li>
                                            <li><a href="javascript:void(0)">Jobs</a></li>
                                            <li><a href="javascript:void(0)">Job applications</a></li>
                                            <li><a href="javascript:void(0)">Contacts</a></li>
                                            <li><a href="javascript:void(0)">Candidates</a></li>
                                            <li><a href="javascript:void(0)">Notes</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-group">
                                <div class="accordion-heading">
                                    <a href="#collapseTwo" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
                                        <i class="icon-th"></i> System manager
                                    </a>
                                </div>
                                <div class="accordion-body collapse" id="collapseTwo">
                                    <div class="accordion-inner">
                                        <ul class="nav nav-list">
                                            <li><a href="javascript:void(0)">Categories</a></li>
                                            <li><a href="javascript:void(0)">Broadbean</a></li>
                                            <li><a href="javascript:void(0)">Advertisers</a></li>
                                            <li><a href="javascript:void(0)">Documents</a></li>
                                            <li><a href="javascript:void(0)">Users</a></li>
                                            <li><a href="javascript:void(0)">Users groups</a></li>
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
                                <div class="accordion-body collapse" id="collapseFour">
                                    <div class="accordion-inner">
                                        <ul class="nav nav-list">
                                            <li><a href="javascript:void(0)">Account Settings</a></li>
                                            <li><a href="javascript:void(0)">Content blocks</a></li>
                                            <li><a href="javascript:void(0)">Tags</a></li>
                                            <li><a href="javascript:void(0)">Tokens</a></li>
                                            <li><a href="javascript:void(0)">Classes</a></li>
                                            <li><a href="javascript:void(0)">Tags</a></li>
                                            <li><a href="javascript:void(0)">IP Adress Blocking</a></li>
                                            <li class="nav-header">System</li>
                                            <li><a href="javascript:void(0)">Site information</a></li>
                                            <li><a href="javascript:void(0)">Actions</a></li>
                                            <li class="divider"></li>
                                            <li><a href="javascript:void(0)">Help</a></li>
                                        </ul> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sidebar_info">
<div style="margin-bottom: 40px;"><img src="http://static.jobshout.co.uk/images/logo1.gif"></div>                        
                            <ul class="unstyled">
                                <li>
                                    <span class="act act-warning">15</span>
                                    <strong>Jobs posted today</strong>
                                </li>
                                <li>
                                    <span class="act act-success">20</span>
                                    <strong>New job applications</strong>
                                </li>
                                <li>
                                    <span class="act act-danger">200</span>
                                    <strong>New visitors</strong>
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
			<!-- code prettifier -->
			<script src="lib/google-code-prettify/prettify.min.js"></script>
			<!-- tooltips -->
			<script src="lib/qtip2/jquery.qtip.min.js"></script>
			<!-- jBreadcrumbs -->
			<script src="lib/jBreadcrumbs/js/jquery.jBreadCrumb.1.1.min.js"></script>
			<!-- lightbox -->
            <script src="lib/colorbox/jquery.colorbox.min.js"></script>
            <!-- common functions -->
			<script src="js/gebo_common.js"></script>
			
			<script src="lib/jquery-ui/jquery-ui-1.8.20.custom.min.js"></script>
            <!-- touch events for jquery ui-->
            <script src="js/forms/jquery.ui.touch-punch.min.js"></script>
            <!-- multi-column layout -->
            <script src="js/jquery.imagesloaded.min.js"></script>
            <script src="js/jquery.wookmark.js"></script>
            <!-- responsive table -->
            <script src="js/jquery.mediaTable.min.js"></script>
            <!-- small charts -->
            <script src="js/jquery.peity.min.js"></script>
            <!-- charts -->
            <script src="lib/flot/jquery.flot.min.js"></script>
            <script src="lib/flot/jquery.flot.resize.min.js"></script>
            <script src="lib/flot/jquery.flot.pie.min.js"></script>
            <!-- calendar -->
            <script src="lib/fullcalendar/fullcalendar.min.js"></script>
            <!-- sortable/filterable list -->
            <script src="lib/list_js/list.min.js"></script>
            <script src="lib/list_js/plugins/paging/list.paging.min.js"></script>
            <!-- dashboard functions -->
            <script src="js/gebo_dashboard.js"></script>
    
			<script>
				$(document).ready(function() {
					//* calculate sidebar height
					gebo_sidebar.make();
					//* show all elements & remove preloader
					setTimeout('$("html").removeClass("js")',1000);
				});
			</script>
		
		</div>
	</body>
</html>