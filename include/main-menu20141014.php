<?php 
// $curr_loc=basename($_SERVER['PHP_SELF']);
//$curr_loc="home.php";
//$curr_loc.="?";
$user=$db->get_row("select * from wi_users where code='".$_SESSION['UserEmail']."'");
 ?>
<div class="navbar-inner">
	<div class="container-fluid">
		<a class="brand" href="home.php"><i class="icon-home icon-white"></i> Jobshout</a>
		<ul class="nav user_menu pull-right">
			<!--<li class="hidden-phone hidden-tablet">
				<div class="nb_boxes clearfix">
					<a data-toggle="modal" data-backdrop="static" href="#myMail" class="label ttip_b" title="New messages">25 <i class="splashy-mail_light"></i></a>
					<a data-toggle="modal" data-backdrop="static" href="#myTasks" class="label ttip_b" title="New tasks">10 <i class="splashy-calendar_week"></i></a>
				</div>
			</li>-->
			<li class="divider-vertical hidden-phone hidden-tablet"></li>
			<li class="dropdown">
				<?php
					if(isset($_SESSION['UserEmail'])){
				?>
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['UserEmail'];?> <b class="caret"></b></a>
				<?php } ?>
				<ul class="dropdown-menu" >
				
				<li><a href="user.php?uuid=<?php echo $user->uuid; ?>">My Profile</a></li>
				<!--<li><a href="javascrip:void(0)">Another action</a></li>-->
				
				<form id="frm_site" name="frm_site" action="home.php" method="post" style="margin:0px;" >
					<input type="hidden" name="switch_site" id="switch_site"  />
				</form>
				
<?php
				
				
		
		if($user->access_rights_code>=11){
			if($sites=$db->get_results("select id, GUID, name,Code from sites order by zStatus asc, name ASC limit 0,25 ")) { ?>
		<li><a >Switch Site to</a>
						<form style="margin:0px;" id="frm_site_code">
							<input type="text" name="site_code" id="site_code" style=" width:auto;"  />
						</form>
		</li>
				<li style="max-height:350px; width:200px; overflow-x:hidden;  overflow-y:auto;" id="li_site_switch">
							
				<ul style="list-style:none; margin:0px;">
				<li> <a href="javascript:void(0)" onclick="chng_site('all')">All</a></li>
		<?php	
				foreach($sites as $site)
				{ ?>				
								
				
				<li> <a href="javascript:void(0)" onclick="chng_site('<?php echo $site->GUID; ?>')" ><?php echo $site->Code; ?></a></li>
				<?php } ?></ul></li>
				<?php } ?>
				
				<?php } else {
				
							if($sites=$db->get_results("select id, GUID, name,Code from sites where (GUID in (select uuid_site from wi_user_sites where uuid_user='".$user->uuid."') OR GUID = '".$user_details->Site_GUID."' or ID = '".$user_details->SiteID."') order by zStatus asc, name ASC limit 0,25")) {	?>
							
				<li><a >Switch Site to</a>
				<form style="margin:0px;" id="frm_site_code">
							<input type="text" name="site_code" id="site_code" style=" width:auto;"  />
						</form>
				</li>
				<li style="max-height:350px; width:200px; overflow-x:hidden;  overflow-y:auto;" id="li_site_switch">
				<ul style="list-style:none; margin:0px;">			
				<li> <a href="javascript:void(0)" onclick="chng_site('all')">All</a></li>
				<?php
				foreach($sites as $site)
				{ ?>				
								
				
				<li>
				<!--<a href="<?php //echo $curr_loc; ?>site_id=<?php //echo $site->id; ?>" >  <?php //echo $site->Code; ?></a>-->
				<a href="javascript:void(0)" onclick="chng_site('<?php echo $site->GUID; ?>')" >  <?php echo $site->Code; ?></a>
		</li>		<?php } ?>
		</ul></li>
		
		<?php } ?>
				
				<?php } ?>
				<li class="divider"></li>
				<li><a href="logout.php">Log Out</a></li>
				</ul>
			</li>
		</ul>
		<a data-target=".nav-collapse" data-toggle="collapse" class="btn_menu">
			<span class="icon-align-justify icon-white"></span>
		</a>
		
		<?php if(false){ ?>
		<nav>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-list-alt icon-white"></i> CMS <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="pages.php">Web Pages</a></li>
							<li><a href="blogs.php">Blog</a></li>
							<li><a href="blog_comments.php">Blog Comments</a></li>
							<li><a href="jobs.php">Jobs</a></li>
							<li><a href="blogs.php">Blog</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-list-alt icon-white"></i> Admin <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="sites.php">Sites</a></li>
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
		<?php } ?>
		<nav>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-file icon-white"></i> Content <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="banners.php">Banners</a></li>
							<li><a href="blogs.php">Blog</a></li>
							<li><a href="kb_articles.php">Knowledgebase Articles</a></li>
							<li><a href="pages.php">Pages</a></li>
							<li><a href="pdf_docs.php">PDF Documents</a></li>
							<li><a href="pictures.php">Images</a></li>
							<li><a href="videos.php">Videos</a></li>

						</ul>
					</li>
					
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-cog icon-white"></i> Admin <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="categories.php">Categories</a></li>
							<li><a href="cat_groups.php">Category Groups</a></li>
							<li><a href="kb_categories.php">Knowledgebase Categories</a></li>
						</ul>
					</li>
					
										<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-list-alt icon-white"></i> CRM <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="contacts.php">Contacts</a></li>
							<li><a href="customers.php">Customers</a></li>
							<li><a href="mails.php">Mailing List</a></li>
							<li><a href="campaigns.php">Campaigns</a></li>
							<li><a href="news_subs.php">Newsletter Subscribers</a></li>
							<li><a href="enquiries.php">Enquiries</a></li>
							<li><a href="links.php">Links</a></li>
							<li><a href="locations.php">Locations</a></li>
						</ul>
					</li>

					

					
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-th icon-white"></i> Recruitment <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="jobs.php">Jobs</a></li>
							<li><a href="jobapps.php">Job applications</a></li>
							<li><a href="jobbriefs.php">Job Briefs</a></li>
						</ul>
					</li>
															<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-cog icon-white"></i> Advanced <b class="caret"></b></a>
						<ul class="dropdown-menu">
						
							<li><a href="components.php">Components</a></li>
							<li><a href="templates.php">Templates</a></li>
							<li><a href="tokens.php">Tokens</a></li>
							<li><a href="sites.php">Sites</a></li>
							<li><a href="users.php">Users</a></li>
							<li><a href="user.php">Add new user</a></li>

						</ul>
					</li>
					<?php $remoteIPStr = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "";
						/*$bAllowed = ($remoteIPStr=='80.176.174.34');//Bal Home
						$bAllowed = $bAllowed || ($remoteIPStr=='122.176.247.69');//Amabala India Office
						$bAllowed = $bAllowed || ($remoteIPStr=='210.56.116.20');//Neha
						$bAllowed = $bAllowed || ($remoteIPStr=='122.176.194.61');//Tanima home
						if($bAllowed===true){*/ ?>
						
						<?php if($user->access_rights_code>=11){?>
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon-cog icon-white"></i> <?php echo $connect_to; ?>, Switch to:<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="javascript:void(0)" <?php if(isset($connect_to) && $connect_to=="Dev") { ?> style="background: #48a6d2; color:#FFFFFF" <?php } else { ?>class="chng_db" <?php } ?>>Dev</a></li>
								<li><a href="javascript:void(0)" <?php if(isset($connect_to) && $connect_to=="Staging") { ?> style="background: #48a6d2; color:#FFFFFF" <?php } else { ?>class="chng_db" <?php } ?>>Staging</a></li>
								<li><a href="javascript:void(0)" <?php if(isset($connect_to) && $connect_to=="Live") { ?> style="background: #48a6d2; color:#FFFFFF" <?php } else { ?>class="chng_db" <?php } ?>>Live</a></li>
							</ul>
							
						</li>
						<form id="frm_db" name="frm_db" action="home.php" method="post" >
								<input type="hidden" name="connect_to" id="connect_to"  />
							</form>
						<?php } ?>
						
					<?php /*}*/ ?>
					
				</ul>
			</div>
		</nav>
	</div>
</div>

 <?php /*?><script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"> </script>
   
 
    <script type="text/javascript">
        $(document).ready(function() {
            $('#site_id').change(function() {
				var site=$(this).val();
				//alert('<?php echo $curr_loc; ?>site_id='+site);
                location.href = '<?php echo $curr_loc; ?>site_id='+site;
            });
        });     
    </script><?php */?>
