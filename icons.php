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
                    
                    <nav>
                        <div id="jCrumbs" class="breadCrumb module">
                            <ul>
                                <li>
                                    <a href="#"><i class="icon-home"></i></a>
                                </li>
                                <li>
                                    <a href="#">Sports & Toys</a>
                                </li>
                                <li>
                                    <a href="#">Toys & Hobbies</a>
                                </li>
                                <li>
                                    <a href="#">Learning & Educational</a>
                                </li>
                                <li>
                                    <a href="#">Astronomy & Telescopes</a>
                                </li>
                                <li>
                                    Telescope 3735SX 
                                </li>
                            </ul>
                        </div>
                    </nav>
                    
                    <div class="row-fluid">
                        <div class="span12">
							<h3 class="heading"><a href="http://splashyfish.com/icons/">Splashy icons</a> <small>(367 icons)</small></h3>
							<div class="icon_copy sepH_c">Click on icon to generate code <span></span></div>
							<ul class="icon_list clearfix">
								<li title="splashy-add"><i class="splashy-add"></i></li>
								<li title="splashy-add_outline"><i class="splashy-add_outline"></i></li>
								<li title="splashy-add_small"><i class="splashy-add_small"></i></li>
								<li title="splashy-applications_windows"><i class="splashy-applications_windows"></i></li>
								<li title="splashy-application_windows"><i class="splashy-application_windows"></i></li>
								<li title="splashy-application_windows_add"><i class="splashy-application_windows_add"></i></li>
								<li title="splashy-application_windows_down"><i class="splashy-application_windows_down"></i></li>
								<li title="splashy-application_windows_edit"><i class="splashy-application_windows_edit"></i></li>
								<li title="splashy-application_windows_locked"><i class="splashy-application_windows_locked"></i></li>
								<li title="splashy-application_windows_new"><i class="splashy-application_windows_new"></i></li>
								<li title="splashy-application_windows_okay"><i class="splashy-application_windows_okay"></i></li>
								<li title="splashy-application_windows_remove"><i class="splashy-application_windows_remove"></i></li>
								<li title="splashy-application_windows_share"><i class="splashy-application_windows_share"></i></li>
								<li title="splashy-application_windows_up"><i class="splashy-application_windows_up"></i></li>
								<li title="splashy-application_windows_warning"><i class="splashy-application_windows_warning"></i></li>
								<li title="splashy-arrow_large_down"><i class="splashy-arrow_large_down"></i></li>
								<li title="splashy-arrow_large_down_outline"><i class="splashy-arrow_large_down_outline"></i></li>
								<li title="splashy-arrow_large_left"><i class="splashy-arrow_large_left"></i></li>
								<li title="splashy-arrow_large_left_outline"><i class="splashy-arrow_large_left_outline"></i></li>
								<li title="splashy-arrow_large_right"><i class="splashy-arrow_large_right"></i></li>
								<li title="splashy-arrow_large_right_outline"><i class="splashy-arrow_large_right_outline"></i></li>
								<li title="splashy-arrow_large_up"><i class="splashy-arrow_large_up"></i></li>
								<li title="splashy-arrow_large_up_outline"><i class="splashy-arrow_large_up_outline"></i></li>
								<li title="splashy-arrow_medium_down"><i class="splashy-arrow_medium_down"></i></li>
								<li title="splashy-arrow_medium_left"><i class="splashy-arrow_medium_left"></i></li>
								<li title="splashy-arrow_medium_lower_left"><i class="splashy-arrow_medium_lower_left"></i></li>
								<li title="splashy-arrow_medium_lower_right"><i class="splashy-arrow_medium_lower_right"></i></li>
								<li title="splashy-arrow_medium_right"><i class="splashy-arrow_medium_right"></i></li>
								<li title="splashy-arrow_medium_up"><i class="splashy-arrow_medium_up"></i></li>
								<li title="splashy-arrow_medium_upper_left"><i class="splashy-arrow_medium_upper_left"></i></li>
								<li title="splashy-arrow_medium_upper_right"><i class="splashy-arrow_medium_upper_right"></i></li>
								<li title="splashy-arrow_small_down"><i class="splashy-arrow_small_down"></i></li>
								<li title="splashy-arrow_small_left"><i class="splashy-arrow_small_left"></i></li>
								<li title="splashy-arrow_small_right"><i class="splashy-arrow_small_right"></i></li>
								<li title="splashy-arrow_small_up"><i class="splashy-arrow_small_up"></i></li>
								<li title="splashy-arrow_state_blue_collapsed"><i class="splashy-arrow_state_blue_collapsed"></i></li>
								<li title="splashy-arrow_state_blue_expanded"><i class="splashy-arrow_state_blue_expanded"></i></li>
								<li title="splashy-arrow_state_blue_left"><i class="splashy-arrow_state_blue_left"></i></li>
								<li title="splashy-arrow_state_blue_right"><i class="splashy-arrow_state_blue_right"></i></li>
								<li title="splashy-arrow_state_grey_collapsed"><i class="splashy-arrow_state_grey_collapsed"></i></li>
								<li title="splashy-arrow_state_grey_expanded"><i class="splashy-arrow_state_grey_expanded"></i></li>
								<li title="splashy-arrow_state_grey_left"><i class="splashy-arrow_state_grey_left"></i></li>
								<li title="splashy-arrow_state_grey_right"><i class="splashy-arrow_state_grey_right"></i></li>
								<li title="splashy-box"><i class="splashy-box"></i></li>
								<li title="splashy-box_add"><i class="splashy-box_add"></i></li>
								<li title="splashy-box_edit"><i class="splashy-box_edit"></i></li>
								<li title="splashy-box_locked"><i class="splashy-box_locked"></i></li>
								<li title="splashy-box_new"><i class="splashy-box_new"></i></li>
								<li title="splashy-box_okay"><i class="splashy-box_okay"></i></li>
								<li title="splashy-box_remove"><i class="splashy-box_remove"></i></li>
								<li title="splashy-box_share"><i class="splashy-box_share"></i></li>
								<li title="splashy-box_warning"><i class="splashy-box_warning"></i></li>
								<li title="splashy-breadcrumb_separator_arrow_1_dot"><i class="splashy-breadcrumb_separator_arrow_1_dot"></i></li>
								<li title="splashy-breadcrumb_separator_arrow_2_dots"><i class="splashy-breadcrumb_separator_arrow_2_dots"></i></li>
								<li title="splashy-breadcrumb_separator_arrow_full"><i class="splashy-breadcrumb_separator_arrow_full"></i></li>
								<li title="splashy-breadcrumb_separator_dark"><i class="splashy-breadcrumb_separator_dark"></i></li>
								<li title="splashy-breadcrumb_separator_light"><i class="splashy-breadcrumb_separator_light"></i></li>
								<li title="splashy-bullet_blue"><i class="splashy-bullet_blue"></i></li>
								<li title="splashy-bullet_blue_arrow"><i class="splashy-bullet_blue_arrow"></i></li>
								<li title="splashy-bullet_blue_collapse"><i class="splashy-bullet_blue_collapse"></i></li>
								<li title="splashy-bullet_blue_collapse_small"><i class="splashy-bullet_blue_collapse_small"></i></li>
								<li title="splashy-bullet_blue_expand"><i class="splashy-bullet_blue_expand"></i></li>
								<li title="splashy-bullet_blue_expand_small"><i class="splashy-bullet_blue_expand_small"></i></li>
								<li title="splashy-bullet_blue_small"><i class="splashy-bullet_blue_small"></i></li>
								<li title="splashy-calendar_day"><i class="splashy-calendar_day"></i></li>
								<li title="splashy-calendar_day_add"><i class="splashy-calendar_day_add"></i></li>
								<li title="splashy-calendar_day_down"><i class="splashy-calendar_day_down"></i></li>
								<li title="splashy-calendar_day_edit"><i class="splashy-calendar_day_edit"></i></li>
								<li title="splashy-calendar_day_event"><i class="splashy-calendar_day_event"></i></li>
								<li title="splashy-calendar_day_new"><i class="splashy-calendar_day_new"></i></li>
								<li title="splashy-calendar_day_remove"><i class="splashy-calendar_day_remove"></i></li>
								<li title="splashy-calendar_day_up"><i class="splashy-calendar_day_up"></i></li>
								<li title="splashy-calendar_month"><i class="splashy-calendar_month"></i></li>
								<li title="splashy-calendar_month_add"><i class="splashy-calendar_month_add"></i></li>
								<li title="splashy-calendar_month_down"><i class="splashy-calendar_month_down"></i></li>
								<li title="splashy-calendar_month_edit"><i class="splashy-calendar_month_edit"></i></li>
								<li title="splashy-calendar_month_new"><i class="splashy-calendar_month_new"></i></li>
								<li title="splashy-calendar_month_remove"><i class="splashy-calendar_month_remove"></i></li>
								<li title="splashy-calendar_month_up"><i class="splashy-calendar_month_up"></i></li>
								<li title="splashy-calendar_week"><i class="splashy-calendar_week"></i></li>
								<li title="splashy-calendar_week_add"><i class="splashy-calendar_week_add"></i></li>
								<li title="splashy-calendar_week_edit"><i class="splashy-calendar_week_edit"></i></li>
								<li title="splashy-calendar_week_remove"><i class="splashy-calendar_week_remove"></i></li>
								<li title="splashy-cellphone"><i class="splashy-cellphone"></i></li>
								<li title="splashy-check"><i class="splashy-check"></i></li>
								<li title="splashy-close"><i class="splashy-close"></i></li>
								<li title="splashy-comment"><i class="splashy-comment"></i></li>
								<li title="splashy-comments"><i class="splashy-comments"></i></li>
								<li title="splashy-comments_reply"><i class="splashy-comments_reply"></i></li>
								<li title="splashy-comments_small"><i class="splashy-comments_small"></i></li>
								<li title="splashy-comment_alert"><i class="splashy-comment_alert"></i></li>
								<li title="splashy-comment_new_1"><i class="splashy-comment_new_1"></i></li>
								<li title="splashy-comment_new_2"><i class="splashy-comment_new_2"></i></li>
								<li title="splashy-comment_question"><i class="splashy-comment_question"></i></li>
								<li title="splashy-comment_reply"><i class="splashy-comment_reply"></i></li>
								<li title="splashy-contact_blue"><i class="splashy-contact_blue"></i></li>
								<li title="splashy-contact_blue_add"><i class="splashy-contact_blue_add"></i></li>
								<li title="splashy-contact_blue_edit"><i class="splashy-contact_blue_edit"></i></li>
								<li title="splashy-contact_blue_new"><i class="splashy-contact_blue_new"></i></li>
								<li title="splashy-contact_blue_remove"><i class="splashy-contact_blue_remove"></i></li>
								<li title="splashy-contact_grey"><i class="splashy-contact_grey"></i></li>
								<li title="splashy-contact_grey_add"><i class="splashy-contact_grey_add"></i></li>
								<li title="splashy-contact_grey_edit"><i class="splashy-contact_grey_edit"></i></li>
								<li title="splashy-contact_grey_new"><i class="splashy-contact_grey_new"></i></li>
								<li title="splashy-contact_grey_remove"><i class="splashy-contact_grey_remove"></i></li>
								<li title="splashy-diamonds_1"><i class="splashy-diamonds_1"></i></li>
								<li title="splashy-diamonds_2"><i class="splashy-diamonds_2"></i></li>
								<li title="splashy-diamonds_3"><i class="splashy-diamonds_3"></i></li>
								<li title="splashy-diamonds_4"><i class="splashy-diamonds_4"></i></li>
								<li title="splashy-documents"><i class="splashy-documents"></i></li>
								<li title="splashy-documents_add"><i class="splashy-documents_add"></i></li>
								<li title="splashy-documents_edit"><i class="splashy-documents_edit"></i></li>
								<li title="splashy-documents_locked"><i class="splashy-documents_locked"></i></li>
								<li title="splashy-documents_new"><i class="splashy-documents_new"></i></li>
								<li title="splashy-documents_okay"><i class="splashy-documents_okay"></i></li>
								<li title="splashy-documents_remove"><i class="splashy-documents_remove"></i></li>
								<li title="splashy-documents_share"><i class="splashy-documents_share"></i></li>
								<li title="splashy-documents_warning"><i class="splashy-documents_warning"></i></li>
								<li title="splashy-document_a4"><i class="splashy-document_a4"></i></li>
								<li title="splashy-document_a4_add"><i class="splashy-document_a4_add"></i></li>
								<li title="splashy-document_a4_blank"><i class="splashy-document_a4_blank"></i></li>
								<li title="splashy-document_a4_download"><i class="splashy-document_a4_download"></i></li>
								<li title="splashy-document_a4_edit"><i class="splashy-document_a4_edit"></i></li>
								<li title="splashy-document_a4_locked"><i class="splashy-document_a4_locked"></i></li>
								<li title="splashy-document_a4_marked"><i class="splashy-document_a4_marked"></i></li>
								<li title="splashy-document_a4_new"><i class="splashy-document_a4_new"></i></li>
								<li title="splashy-document_a4_okay"><i class="splashy-document_a4_okay"></i></li>
								<li title="splashy-document_a4_remove"><i class="splashy-document_a4_remove"></i></li>
								<li title="splashy-document_a4_share"><i class="splashy-document_a4_share"></i></li>
								<li title="splashy-document_a4_upload"><i class="splashy-document_a4_upload"></i></li>
								<li title="splashy-document_a4_warning"><i class="splashy-document_a4_warning"></i></li>
								<li title="splashy-document_copy"><i class="splashy-document_copy"></i></li>
								<li title="splashy-document_letter"><i class="splashy-document_letter"></i></li>
								<li title="splashy-document_letter_add"><i class="splashy-document_letter_add"></i></li>
								<li title="splashy-document_letter_blank"><i class="splashy-document_letter_blank"></i></li>
								<li title="splashy-document_letter_download"><i class="splashy-document_letter_download"></i></li>
								<li title="splashy-document_letter_edit"><i class="splashy-document_letter_edit"></i></li>
								<li title="splashy-document_letter_locked"><i class="splashy-document_letter_locked"></i></li>
								<li title="splashy-document_letter_marked"><i class="splashy-document_letter_marked"></i></li>
								<li title="splashy-document_letter_new"><i class="splashy-document_letter_new"></i></li>
								<li title="splashy-document_letter_okay"><i class="splashy-document_letter_okay"></i></li>
								<li title="splashy-document_letter_remove"><i class="splashy-document_letter_remove"></i></li>
								<li title="splashy-document_letter_share"><i class="splashy-document_letter_share"></i></li>
								<li title="splashy-document_letter_upload"><i class="splashy-document_letter_upload"></i></li>
								<li title="splashy-document_letter_warning"><i class="splashy-document_letter_warning"></i></li>
								<li title="splashy-document_small"><i class="splashy-document_small"></i></li>
								<li title="splashy-document_small_download"><i class="splashy-document_small_download"></i></li>
								<li title="splashy-document_small_upload"><i class="splashy-document_small_upload"></i></li>
								<li title="splashy-download"><i class="splashy-download"></i></li>
								<li title="splashy-error"><i class="splashy-error"></i></li>
								<li title="splashy-error_do_not"><i class="splashy-error_do_not"></i></li>
								<li title="splashy-error_do_not_small"><i class="splashy-error_do_not_small"></i></li>
								<li title="splashy-error_small"><i class="splashy-error_small"></i></li>
								<li title="splashy-error_x"><i class="splashy-error_x"></i></li>
								<li title="splashy-fish"><i class="splashy-fish"></i></li>
								<li title="splashy-folder_classic"><i class="splashy-folder_classic"></i></li>
								<li title="splashy-folder_classic_add"><i class="splashy-folder_classic_add"></i></li>
								<li title="splashy-folder_classic_add_simple"><i class="splashy-folder_classic_add_simple"></i></li>
								<li title="splashy-folder_classic_down"><i class="splashy-folder_classic_down"></i></li>
								<li title="splashy-folder_classic_edit"><i class="splashy-folder_classic_edit"></i></li>
								<li title="splashy-folder_classic_locked"><i class="splashy-folder_classic_locked"></i></li>
								<li title="splashy-folder_classic_opened"><i class="splashy-folder_classic_opened"></i></li>
								<li title="splashy-folder_classic_opened_stuffed"><i class="splashy-folder_classic_opened_stuffed"></i></li>
								<li title="splashy-folder_classic_remove"><i class="splashy-folder_classic_remove"></i></li>
								<li title="splashy-folder_classic_remove_simple"><i class="splashy-folder_classic_remove_simple"></i></li>
								<li title="splashy-folder_classic_stuffed"><i class="splashy-folder_classic_stuffed"></i></li>
								<li title="splashy-folder_classic_stuffed_add"><i class="splashy-folder_classic_stuffed_add"></i></li>
								<li title="splashy-folder_classic_stuffed_add_simple"><i class="splashy-folder_classic_stuffed_add_simple"></i></li>
								<li title="splashy-folder_classic_stuffed_edit"><i class="splashy-folder_classic_stuffed_edit"></i></li>
								<li title="splashy-folder_classic_stuffed_locked"><i class="splashy-folder_classic_stuffed_locked"></i></li>
								<li title="splashy-folder_classic_stuffed_remove"><i class="splashy-folder_classic_stuffed_remove"></i></li>
								<li title="splashy-folder_classic_stuffed_remove_simple"><i class="splashy-folder_classic_stuffed_remove_simple"></i></li>
								<li title="splashy-folder_classic_type_document"><i class="splashy-folder_classic_type_document"></i></li>
								<li title="splashy-folder_classic_type_image"><i class="splashy-folder_classic_type_image"></i></li>
								<li title="splashy-folder_classic_type_music"><i class="splashy-folder_classic_type_music"></i></li>
								<li title="splashy-folder_classic_up"><i class="splashy-folder_classic_up"></i></li>
								<li title="splashy-folder_locked"><i class="splashy-folder_locked"></i></li>
								<li title="splashy-folder_modernist"><i class="splashy-folder_modernist"></i></li>
								<li title="splashy-folder_modernist_add"><i class="splashy-folder_modernist_add"></i></li>
								<li title="splashy-folder_modernist_add_simple"><i class="splashy-folder_modernist_add_simple"></i></li>
								<li title="splashy-folder_modernist_down"><i class="splashy-folder_modernist_down"></i></li>
								<li title="splashy-folder_modernist_edit"><i class="splashy-folder_modernist_edit"></i></li>
								<li title="splashy-folder_modernist_locked"><i class="splashy-folder_modernist_locked"></i></li>
								<li title="splashy-folder_modernist_opened"><i class="splashy-folder_modernist_opened"></i></li>
								<li title="splashy-folder_modernist_opened_stuffed"><i class="splashy-folder_modernist_opened_stuffed"></i></li>
								<li title="splashy-folder_modernist_remove"><i class="splashy-folder_modernist_remove"></i></li>
								<li title="splashy-folder_modernist_remove_simple"><i class="splashy-folder_modernist_remove_simple"></i></li>
								<li title="splashy-folder_modernist_stuffed"><i class="splashy-folder_modernist_stuffed"></i></li>
								<li title="splashy-folder_modernist_stuffed_add"><i class="splashy-folder_modernist_stuffed_add"></i></li>
								<li title="splashy-folder_modernist_stuffed_add_simple"><i class="splashy-folder_modernist_stuffed_add_simple"></i></li>
								<li title="splashy-folder_modernist_stuffed_edit"><i class="splashy-folder_modernist_stuffed_edit"></i></li>
								<li title="splashy-folder_modernist_stuffed_locked"><i class="splashy-folder_modernist_stuffed_locked"></i></li>
								<li title="splashy-folder_modernist_stuffed_remove"><i class="splashy-folder_modernist_stuffed_remove"></i></li>
								<li title="splashy-folder_modernist_stuffed_remove_simple"><i class="splashy-folder_modernist_stuffed_remove_simple"></i></li>
								<li title="splashy-folder_modernist_type_document"><i class="splashy-folder_modernist_type_document"></i></li>
								<li title="splashy-folder_modernist_type_image"><i class="splashy-folder_modernist_type_image"></i></li>
								<li title="splashy-folder_modernist_type_movie"><i class="splashy-folder_modernist_type_movie"></i></li>
								<li title="splashy-folder_modernist_type_music"><i class="splashy-folder_modernist_type_music"></i></li>
								<li title="splashy-folder_modernist_up"><i class="splashy-folder_modernist_up"></i></li>
								<li title="splashy-folder_remove"><i class="splashy-folder_remove"></i></li>
								<li title="splashy-folder_stuffed"><i class="splashy-folder_stuffed"></i></li>
								<li title="splashy-folder_stuffed_add"><i class="splashy-folder_stuffed_add"></i></li>
								<li title="splashy-folder_stuffed_locked"><i class="splashy-folder_stuffed_locked"></i></li>
								<li title="splashy-folder_stuffed_remove"><i class="splashy-folder_stuffed_remove"></i></li>
								<li title="splashy-gem_cancel_1"><i class="splashy-gem_cancel_1"></i></li>
								<li title="splashy-gem_cancel_2"><i class="splashy-gem_cancel_2"></i></li>
								<li title="splashy-gem_okay"><i class="splashy-gem_okay"></i></li>
								<li title="splashy-gem_options"><i class="splashy-gem_options"></i></li>
								<li title="splashy-gem_remove"><i class="splashy-gem_remove"></i></li>
								<li title="splashy-group_blue"><i class="splashy-group_blue"></i></li>
								<li title="splashy-group_blue_add"><i class="splashy-group_blue_add"></i></li>
								<li title="splashy-group_blue_edit"><i class="splashy-group_blue_edit"></i></li>
								<li title="splashy-group_blue_new"><i class="splashy-group_blue_new"></i></li>
								<li title="splashy-group_blue_remove"><i class="splashy-group_blue_remove"></i></li>
								<li title="splashy-group_green"><i class="splashy-group_green"></i></li>
								<li title="splashy-group_green_add"><i class="splashy-group_green_add"></i></li>
								<li title="splashy-group_green_edit"><i class="splashy-group_green_edit"></i></li>
								<li title="splashy-group_green_new"><i class="splashy-group_green_new"></i></li>
								<li title="splashy-group_green_remove"><i class="splashy-group_green_remove"></i></li>
								<li title="splashy-group_grey"><i class="splashy-group_grey"></i></li>
								<li title="splashy-group_grey_add"><i class="splashy-group_grey_add"></i></li>
								<li title="splashy-group_grey_edit"><i class="splashy-group_grey_edit"></i></li>
								<li title="splashy-group_grey_new"><i class="splashy-group_grey_new"></i></li>
								<li title="splashy-group_grey_remove"><i class="splashy-group_grey_remove"></i></li>
								<li title="splashy-hcard"><i class="splashy-hcard"></i></li>
								<li title="splashy-hcards"><i class="splashy-hcards"></i></li>
								<li title="splashy-hcards_add"><i class="splashy-hcards_add"></i></li>
								<li title="splashy-hcards_down"><i class="splashy-hcards_down"></i></li>
								<li title="splashy-hcards_edit"><i class="splashy-hcards_edit"></i></li>
								<li title="splashy-hcards_remove"><i class="splashy-hcards_remove"></i></li>
								<li title="splashy-hcards_up"><i class="splashy-hcards_up"></i></li>
								<li title="splashy-hcard_add"><i class="splashy-hcard_add"></i></li>
								<li title="splashy-hcard_download"><i class="splashy-hcard_download"></i></li>
								<li title="splashy-hcard_edit"><i class="splashy-hcard_edit"></i></li>
								<li title="splashy-hcard_new"><i class="splashy-hcard_new"></i></li>
								<li title="splashy-hcard_remove"><i class="splashy-hcard_remove"></i></li>
								<li title="splashy-hcard_up"><i class="splashy-hcard_up"></i></li>
								<li title="splashy-heart"><i class="splashy-heart"></i></li>
								<li title="splashy-heart_add"><i class="splashy-heart_add"></i></li>
								<li title="splashy-heart_edit"><i class="splashy-heart_edit"></i></li>
								<li title="splashy-heart_outline"><i class="splashy-heart_outline"></i></li>
								<li title="splashy-heart_remove"><i class="splashy-heart_remove"></i></li>
								<li title="splashy-heart_up"><i class="splashy-heart_up"></i></li>
								<li title="splashy-help"><i class="splashy-help"></i></li>
								<li title="splashy-home_green"><i class="splashy-home_green"></i></li>
								<li title="splashy-home_grey"><i class="splashy-home_grey"></i></li>
								<li title="splashy-image_cultured"><i class="splashy-image_cultured"></i></li>
								<li title="splashy-image_modernist"><i class="splashy-image_modernist"></i></li>
								<li title="splashy-information"><i class="splashy-information"></i></li>
								<li title="splashy-lock_large_locked"><i class="splashy-lock_large_locked"></i></li>
								<li title="splashy-lock_large_unlocked"><i class="splashy-lock_large_unlocked"></i></li>
								<li title="splashy-lock_small_locked"><i class="splashy-lock_small_locked"></i></li>
								<li title="splashy-lock_small_unlocked"><i class="splashy-lock_small_unlocked"></i></li>
								<li title="splashy-mail_light"><i class="splashy-mail_light"></i></li>
								<li title="splashy-mail_light_down"><i class="splashy-mail_light_down"></i></li>
								<li title="splashy-mail_light_left"><i class="splashy-mail_light_left"></i></li>
								<li title="splashy-mail_light_new_1"><i class="splashy-mail_light_new_1"></i></li>
								<li title="splashy-mail_light_new_2"><i class="splashy-mail_light_new_2"></i></li>
								<li title="splashy-mail_light_right"><i class="splashy-mail_light_right"></i></li>
								<li title="splashy-mail_light_stuffed"><i class="splashy-mail_light_stuffed"></i></li>
								<li title="splashy-mail_light_up"><i class="splashy-mail_light_up"></i></li>
								<li title="splashy-map"><i class="splashy-map"></i></li>
								<li title="splashy-marker_rounded_add"><i class="splashy-marker_rounded_add"></i></li>
								<li title="splashy-marker_rounded_blue"><i class="splashy-marker_rounded_blue"></i></li>
								<li title="splashy-marker_rounded_edit"><i class="splashy-marker_rounded_edit"></i></li>
								<li title="splashy-marker_rounded_green"><i class="splashy-marker_rounded_green"></i></li>
								<li title="splashy-marker_rounded_grey_1"><i class="splashy-marker_rounded_grey_1"></i></li>
								<li title="splashy-marker_rounded_grey_2"><i class="splashy-marker_rounded_grey_2"></i></li>
								<li title="splashy-marker_rounded_grey_3"><i class="splashy-marker_rounded_grey_3"></i></li>
								<li title="splashy-marker_rounded_grey_4"><i class="splashy-marker_rounded_grey_4"></i></li>
								<li title="splashy-marker_rounded_grey_5"><i class="splashy-marker_rounded_grey_5"></i></li>
								<li title="splashy-marker_rounded_light_blue"><i class="splashy-marker_rounded_light_blue"></i></li>
								<li title="splashy-marker_rounded_new"><i class="splashy-marker_rounded_new"></i></li>
								<li title="splashy-marker_rounded_red"><i class="splashy-marker_rounded_red"></i></li>
								<li title="splashy-marker_rounded_remove"><i class="splashy-marker_rounded_remove"></i></li>
								<li title="splashy-marker_rounded_violet"><i class="splashy-marker_rounded_violet"></i></li>
								<li title="splashy-marker_rounded_yellow"><i class="splashy-marker_rounded_yellow"></i></li>
								<li title="splashy-marker_rounded_yellow_green"><i class="splashy-marker_rounded_yellow_green"></i></li>
								<li title="splashy-marker_rounded_yellow_orange"><i class="splashy-marker_rounded_yellow_orange"></i></li>
								<li title="splashy-media_controls_dark_first"><i class="splashy-media_controls_dark_first"></i></li>
								<li title="splashy-media_controls_dark_forward"><i class="splashy-media_controls_dark_forward"></i></li>
								<li title="splashy-media_controls_dark_last"><i class="splashy-media_controls_dark_last"></i></li>
								<li title="splashy-media_controls_dark_pause"><i class="splashy-media_controls_dark_pause"></i></li>
								<li title="splashy-media_controls_dark_play"><i class="splashy-media_controls_dark_play"></i></li>
								<li title="splashy-media_controls_dark_rewind"><i class="splashy-media_controls_dark_rewind"></i></li>
								<li title="splashy-media_controls_dark_stop"><i class="splashy-media_controls_dark_stop"></i></li>
								<li title="splashy-media_controls_first_small"><i class="splashy-media_controls_first_small"></i></li>
								<li title="splashy-media_controls_forward_small"><i class="splashy-media_controls_forward_small"></i></li>
								<li title="splashy-media_controls_last_small"><i class="splashy-media_controls_last_small"></i></li>
								<li title="splashy-media_controls_pause_small"><i class="splashy-media_controls_pause_small"></i></li>
								<li title="splashy-media_controls_play_small"><i class="splashy-media_controls_play_small"></i></li>
								<li title="splashy-media_controls_rewind_small"><i class="splashy-media_controls_rewind_small"></i></li>
								<li title="splashy-media_controls_stop_small"><i class="splashy-media_controls_stop_small"></i></li>
								<li title="splashy-menu"><i class="splashy-menu"></i></li>
								<li title="splashy-menu_dropdown"><i class="splashy-menu_dropdown"></i></li>
								<li title="splashy-movie_play"><i class="splashy-movie_play"></i></li>
								<li title="splashy-music_cd_blue_note"><i class="splashy-music_cd_blue_note"></i></li>
								<li title="splashy-music_green"><i class="splashy-music_green"></i></li>
								<li title="splashy-music_grey"><i class="splashy-music_grey"></i></li>
								<li title="splashy-new_small"><i class="splashy-new_small"></i></li>
								<li title="splashy-okay"><i class="splashy-okay"></i></li>
								<li title="splashy-okay_small"><i class="splashy-okay_small"></i></li>
								<li title="splashy-pagination_1_first"><i class="splashy-pagination_1_first"></i></li>
								<li title="splashy-pagination_1_last"><i class="splashy-pagination_1_last"></i></li>
								<li title="splashy-pagination_1_next"><i class="splashy-pagination_1_next"></i></li>
								<li title="splashy-pagination_1_previous"><i class="splashy-pagination_1_previous"></i></li>
								<li title="splashy-pencil"><i class="splashy-pencil"></i></li>
								<li title="splashy-pencil_small"><i class="splashy-pencil_small"></i></li>
								<li title="splashy-printer"><i class="splashy-printer"></i></li>
								<li title="splashy-quanitity_capsule_1"><i class="splashy-quanitity_capsule_1"></i></li>
								<li title="splashy-quantity_capsule_2"><i class="splashy-quantity_capsule_2"></i></li>
								<li title="splashy-quantity_capsule_3"><i class="splashy-quantity_capsule_3"></i></li>
								<li title="splashy-quantity_capsule_4"><i class="splashy-quantity_capsule_4"></i></li>
								<li title="splashy-quantity_capsule_5"><i class="splashy-quantity_capsule_5"></i></li>
								<li title="splashy-refresh"><i class="splashy-refresh"></i></li>
								<li title="splashy-refresh_backwards"><i class="splashy-refresh_backwards"></i></li>
								<li title="splashy-refresh_forward"><i class="splashy-refresh_forward"></i></li>
								<li title="splashy-remove"><i class="splashy-remove"></i></li>
								<li title="splashy-remove_minus_sign"><i class="splashy-remove_minus_sign"></i></li>
								<li title="splashy-remove_minus_sign_outline"><i class="splashy-remove_minus_sign_outline"></i></li>
								<li title="splashy-remove_minus_sign_small"><i class="splashy-remove_minus_sign_small"></i></li>
								<li title="splashy-remove_outline"><i class="splashy-remove_outline"></i></li>
								<li title="splashy-shield"><i class="splashy-shield"></i></li>
								<li title="splashy-shield_chevrons"><i class="splashy-shield_chevrons"></i></li>
								<li title="splashy-shield_star"><i class="splashy-shield_star"></i></li>
								<li title="splashy-slider_no_pointy_thing"><i class="splashy-slider_no_pointy_thing"></i></li>
								<li title="splashy-smiley_amused"><i class="splashy-smiley_amused"></i></li>
								<li title="splashy-smiley_happy"><i class="splashy-smiley_happy"></i></li>
								<li title="splashy-smiley_surprised"><i class="splashy-smiley_surprised"></i></li>
								<li title="splashy-sprocket_dark"><i class="splashy-sprocket_dark"></i></li>
								<li title="splashy-sprocket_light"><i class="splashy-sprocket_light"></i></li>
								<li title="splashy-star_boxed_empty"><i class="splashy-star_boxed_empty"></i></li>
								<li title="splashy-star_boxed_full"><i class="splashy-star_boxed_full"></i></li>
								<li title="splashy-star_boxed_half"><i class="splashy-star_boxed_half"></i></li>
								<li title="splashy-star_empty"><i class="splashy-star_empty"></i></li>
								<li title="splashy-star_full"><i class="splashy-star_full"></i></li>
								<li title="splashy-star_half"><i class="splashy-star_half"></i></li>
								<li title="splashy-tag"><i class="splashy-tag"></i></li>
								<li title="splashy-tag_add"><i class="splashy-tag_add"></i></li>
								<li title="splashy-tag_edit"><i class="splashy-tag_edit"></i></li>
								<li title="splashy-tag_remove"><i class="splashy-tag_remove"></i></li>
								<li title="splashy-thumb_down"><i class="splashy-thumb_down"></i></li>
								<li title="splashy-thumb_up"><i class="splashy-thumb_up"></i></li>
								<li title="splashy-ticket"><i class="splashy-ticket"></i></li>
								<li title="splashy-ticket_add"><i class="splashy-ticket_add"></i></li>
								<li title="splashy-ticket_remove"><i class="splashy-ticket_remove"></i></li>
								<li title="splashy-upload"><i class="splashy-upload"></i></li>
								<li title="splashy-view_list"><i class="splashy-view_list"></i></li>
								<li title="splashy-view_list_with_thumbnail"><i class="splashy-view_list_with_thumbnail"></i></li>
								<li title="splashy-view_outline"><i class="splashy-view_outline"></i></li>
								<li title="splashy-view_outline_detail"><i class="splashy-view_outline_detail"></i></li>
								<li title="splashy-view_table"><i class="splashy-view_table"></i></li>
								<li title="splashy-view_thumbnail"><i class="splashy-view_thumbnail"></i></li>
								<li title="splashy-volume"><i class="splashy-volume"></i></li>
								<li title="splashy-volume_loud"><i class="splashy-volume_loud"></i></li>
								<li title="splashy-volume_off"><i class="splashy-volume_off"></i></li>
								<li title="splashy-volume_quiet"><i class="splashy-volume_quiet"></i></li>
								<li title="splashy-warning"><i class="splashy-warning"></i></li>
								<li title="splashy-warning_triangle"><i class="splashy-warning_triangle"></i></li>
								<li title="splashy-warning_triangle_small"><i class="splashy-warning_triangle_small"></i></li>
								<li title="splashy-zoom"><i class="splashy-zoom"></i></li>
								<li title="splashy-zoom_in"><i class="splashy-zoom_in"></i></li>
								<li title="splashy-zoom_out"><i class="splashy-zoom_out"></i></li>
							</ul>
							<h3 class="heading"><a href="http://glyphicons.com/">Glyphicons</a> <small>(120 icons)</small></h3>
							<a href="http://twitter.github.com/bootstrap/base-css.html#icons">Here</a> you can find Glyphicons preview and instruction how to use them.
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
            
            <!-- icons functions -->
            <script src="js/gebo_icons.js"></script>
    
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