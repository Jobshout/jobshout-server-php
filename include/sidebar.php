<?php 
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!=''){
	$cWhere = "and SiteID in ('".$_SESSION['site_id']."')";
}else{
	$cWhere = "";
}
?>
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
<div class="sidebar">
	<div class="sidebar_inner">
	
		<form action="search_results.php" class="input-append" id="srch_frm" method="post" >
		<div>
			<input autocomplete="off" name="query" class="input-medium" size="16" type="text" placeholder="Search..." /><button type="submit" class="btn"><i class="icon-search"></i></button>
			</div>
		</form>
		
		<?php $curr_file=basename($_SERVER['PHP_SELF']); ?>
		<div id="side_accordion" class="accordion">
		
					<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseDashboard" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-th"></i> Dashboard
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseDashboard">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<li <?php if($curr_file=="home.php" ) { ?> class="active" <?php }?>><a href="home.php">Home</a></li>
							<li <?php if($curr_file=="blogs.php" || $curr_file=="blogs.php") { ?> class="active" <?php }?>><a href="blogs.php">Blog Posts</a></li>
							<li <?php if($curr_file=="jobs.php" || $curr_file=="jobs.php") { ?> class="active" <?php }?>><a href="jobs.php">Vacancies</a></li>
							<li <?php if($curr_file=="pages.php" || $curr_file=="page.php") { ?> class="active" <?php }?>><a href="pages.php">Web Pages</a></li>
							<li <?php if($curr_file=="notification.php") { ?> class="active" <?php }?>><a href="notification.php">Website Search Results</a></li>
							<!--<li><a href="#">Reports</a></li>
							<li <?php if($curr_file=="chart_country_visits.php" ) { ?> class="active" <?php }?>><a href="chart_country_visits.php">Reports</a></li>-->
						</ul>
					</div>
				</div>
			</div>
			

		
			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseWebContent" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-file"></i> Web Content
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseWebContent">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<li <?php if($curr_file=="banners.php" || $curr_file=="banners.php") { ?> class="active" <?php }?>><a href="banners.php">Banners</a></li>
							<li <?php if($curr_file=="pages.php" || $curr_file=="page.php") { ?> class="active" <?php }?>><a href="pages.php">Web Pages</a></li>
							<?php if($cat_group_id= $db->get_var("select ID from categorygroups where Code='profiles' $cWhere")){ 
							if($category= $db->get_row("select ID, GUID from categories where (CategoryGroupID='".$cat_group_id."') and (TopLevelID='0' and TopLevel_GUID='') $cWhere")){?>
							<li><a href="pages.php?cat_id=<?php echo $category->ID;?>&cat_guid=<?php echo $category->GUID;?>">Talent Search</a></li>
							<?php } 
							} ?>
							<li <?php if($curr_file=="categories.php" || $curr_file=="category.php") { ?> class="active" <?php }?>><a href="categories.php">Web Categories</a></li>
							<li <?php if($curr_file=="cat_groups.php" || $curr_file=="cat_group.php") { ?> class="active" <?php }?>><a href="cat_groups.php">Web Category Groups</a></li>
							<li <?php if($curr_file=="pdf_docs.php" || $curr_file=="pdf_doc.php") { ?> class="active" <?php }?>><a href="pdf_docs.php">PDF Documents</a></li>
							<li <?php if($curr_file=="browse_by_categories.php") { ?> class="active" <?php }?>><a href="browse_by_categories.php">View Content by Category</a></li>

						</ul>
					</div>
				</div>
			</div>

			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseBlogPosts" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-list-alt"></i> Blog Posts
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseBlogPosts">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<li <?php if($curr_file=="blogs.php" || $curr_file=="blog.php") { ?> class="active" <?php }?>><a href="blogs.php">Blog Posts</a></li>
							<li <?php if($curr_file=="blog_comments.php" ) { ?> class="active" <?php }?>><a href="blog_comments.php">Post Comments</a></li>
						</ul>
					</div>
				</div>
			</div>
			
			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseJobboard" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-th"></i> Job board
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseJobboard">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<li <?php if($curr_file=="jobapps.php" || $curr_file=="jobapp.php") { ?> class="active" <?php }?>><a href="jobapps.php">Job Applications</a></li>
							<li <?php if($curr_file=="jobbriefs.php" || $curr_file=="jobbrief.php") { ?> class="active" <?php }?>><a href="jobbriefs.php">Job Briefs</a></li>
							<li <?php if($curr_file=="key_phrase.php" || $curr_file=="key_phrase.php") { ?> class="active" <?php }?>><a href="key_phrases.php">Phrases for Jobs</a></li>
							<li <?php if($curr_file=="jobs.php" || $curr_file=="jobs.php") { ?> class="active" <?php }?>><a href="jobs.php">Vacancies</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseCRM" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-list-alt"></i> CRM Database
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseCRM">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<li <?php if($curr_file=="enquiries.php" || $curr_file=="enquiry.php") { ?> class="active" <?php }?>><a href="enquiries.php">Enquiries</a></li>
							<li><a href="#">Leads</a></li>
							<!--<li <?php if($curr_file=="leads.php" || $curr_file=="leads.php") { ?> class="active" <?php }?>><a href="leads.php">Leads</a></li>-->
							<li <?php if($curr_file=="customers.php" || $curr_file=="customer.php") { ?> class="active" <?php }?>><a href="customers.php">Customers</a></li>
							<li <?php if($curr_file=="contacts.php" || $curr_file=="contact.php") { ?> class="active" <?php }?>><a href="contacts.php">Contacts</a></li>
							<li <?php if($curr_file=="news_subs.php" || $curr_file=="news_sub.php") { ?> class="active" <?php }?>><a href="news_subs.php">Newsletter Subscribers</a></li>
							<li <?php if($curr_file=="mails.php" || $curr_file=="mail.php") { ?> class="active" <?php }?>><a href="mails.php">Mailing List</a></li>
							<li <?php if($curr_file=="campaigns.php" || $curr_file=="campaign.php") { ?> class="active" <?php }?>><a href="campaigns.php">Campaigns</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseMedia" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-folder-close"></i> Media
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseMedia">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<li <?php if($curr_file=="pictures.php" || $curr_file=="pictures.php") { ?> class="active" <?php }?>><a href="pictures.php">Images</a></li>
							<li <?php if($curr_file=="videos.php" || $curr_file=="video.php") { ?> class="active" <?php }?>><a href="videos.php">Videos</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseKB" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-folder-close"></i> Knowledge Base
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseKB">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<li <?php if($curr_file=="kb_categories.php" || $curr_file=="kb_category.php") { ?> class="active" <?php }?>><a href="kb_categories.php">KB Categories</a></li>
							<li <?php if($curr_file=="kb_articles.php" || $curr_file=="kb_article.php") { ?> class="active" <?php }?>><a href="kb_articles.php">KB Articles</a></li>
						</ul>
					</div>
				</div>
			</div>

			<!--<div class="accordion-group">
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
			</div>-->
			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseWebUsers" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-user"></i> Web Users
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseWebUsers">
					<div class="accordion-inner">
						<ul class="nav nav-list">
						<?php
						$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user->access_rights_code>=2) {
						?>
							<li ><a href="users.php">Web Users</a></li>
							<li ><a href="user.php">Add New Web User</a></li>
							<li ><a href="#">Web User Groups</a></li>
							<?php } elseif($user->access_rights_code==1) { ?>
							<li ><a href="user.php?uuid=<?php echo $user->uuid; ?>">My Account</a></li>
							<?php } ?>
													</ul>
					</div>
				</div>
			</div>
			
						<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseSettings" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-cog"></i> Settings
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseSettings">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<li <?php if($curr_file=="banners.php" || $curr_file=="banner.php") { ?> class="active" <?php }?>><a href="banners.php">Banners</a></li>
							<li <?php if($curr_file=="links.php" || $curr_file=="links.php") { ?> class="active" <?php }?>><a href="links.php">Bookmarks</a></li>
							<li <?php if($curr_file=="components.php" || $curr_file=="component.php") { ?> class="active" <?php }?>><a href="components.php">Components</a></li>
							<li <?php if($curr_file=="pictures.php" || $curr_file=="picture.php") { ?> class="active" <?php }?>><a href="pictures.php">Images</a></li>
							<li <?php if($curr_file=="meta_data_list.php" || $curr_file=="meta_data_editor.php") { ?> class="active" <?php }?>><a href="meta_data_list.php">Global Metadata Editor</a></li>
							<li <?php if($curr_file=="office_locations.php" || $curr_file=="office-location.php") { ?> class="active" <?php }?>><a href="office_locations.php">Office Locations</a></li>
							<li <?php if($curr_file=="site_settings.php" || $curr_file=="site_setting.php") { ?> class="active" <?php }?>><a href="site_settings.php">SMTP Settings</a></li>
							<li <?php if($curr_file=="tokens.php" || $curr_file=="token.php") { ?> class="active" <?php }?>><a href="tokens.php">Tokens</a></li>
							<li <?php if($curr_file=="templates.php" || $curr_file=="template.php") { ?> class="active" <?php }?>><a href="templates.php">Templates</a></li>
							<li <?php if($curr_file=="locations.php" || $curr_file=="location.php") { ?> class="active" <?php }?>><a href="locations.php">UK Locations</a></li>
							<li <?php if($curr_file=="sites.php" || $curr_file=="site.php") { ?> class="active" <?php }?>><a href="sites.php">Web Sites</a></li>
						</ul>
					</div>
				</div>
			</div>

			<!--<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapseFour" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-cog"></i> Configuration
					</a>
				</div>
				<div class="accordion-body collapse" id="collapseFour">
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
			</div>-->
		</div>
		<!--<div class="sidebar_info">
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
		</div>-->
	</div>
</div>