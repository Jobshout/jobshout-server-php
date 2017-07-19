
<a href="javascript:void(0)" class="sidebar_switch on_switch ttip_r" title="Hide Sidebar">Sidebar switch</a>
<div class="sidebar">
	<div class="sidebar_inner">
		<form action="search_page.html" class="input-append" method="post" >
			<input autocomplete="off" name="query" class="search_query input-medium" size="16" type="text" placeholder="Search..." /><button type="submit" class="btn"><i class="icon-search"></i></button>
		</form>
		<?php $curr_file=basename($_SERVER['PHP_SELF']); ?>
		<div id="side_accordion" class="accordion">
			<div class="accordion-group">
				<div <?php if($curr_file!="users.php" && $curr_file!="user.php") { echo 'class="accordion-heading sdb_h_active"'; } else { echo 'class="accordion-heading"'; } ?>>
					<a href="#collapseOne" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-folder-close"></i> Content
					</a>
				</div>
				<div <?php if($curr_file!="users.php" && $curr_file!="user.php") { echo 'class="accordion-body collapse in"'; } else { echo 'class="accordion-body collapse"'; } ?> id="collapseOne">
					<div class="accordion-inner">
						<ul class="nav nav-list">
							<li <?php if($curr_file=="pages.php" || $curr_file=="page.php") { ?> class="active" <?php }?>><a href="pages.php">Pages</a></li>
							<li <?php if($curr_file=="blogs.php" || $curr_file=="blog.php") { ?> class="active" <?php }?>><a href="blogs.php">Blog</a></li>
							<li <?php if($curr_file=="jobs.php" || $curr_file=="job.php") { ?> class="active" <?php }?>><a href="jobs.php">Jobs</a></li>
							<li <?php if($curr_file=="categories.php" || $curr_file=="category.php") { ?> class="active" <?php }?>><a href="categories.php">Categories</a></li>
							<li <?php if($curr_file=="cat_groups.php" || $curr_file=="cat_group.php") { ?> class="active" <?php }?>><a href="cat_groups.php">Category Groups</a></li>
							<li <?php if($curr_file=="videos.php" || $curr_file=="video.php") { ?> class="active" <?php }?>><a href="videos.php">Videos</a></li>
							<li <?php if($curr_file=="pictures.php" || $curr_file=="picture.php") { ?> class="active" <?php }?>><a href="pictures.php">Pictures</a></li>
							<li <?php if($curr_file=="pdf_docs.php" || $curr_file=="pdf_doc.php") { ?> class="active" <?php }?>><a href="pdf_docs.php">PDF Documents</a></li>
							<li <?php if($curr_file=="banners.php" || $curr_file=="banner.php") { ?> class="active" <?php }?>><a href="banners.php">Banners</a></li>
							
							<li <?php if($curr_file=="jobapps.php" || $curr_file=="jobapp.php") { ?> class="active" <?php }?>><a href="jobapps.php">Job applications</a></li>
							<li <?php if($curr_file=="jobbriefs.php" || $curr_file=="jobbrief.php") { ?> class="active" <?php }?>><a href="jobbriefs.php">Job Briefs</a></li>
							<li <?php if($curr_file=="contacts.php" || $curr_file=="contact.php") { ?> class="active" <?php }?>><a href="contacts.php">Contacts</a></li>
							<li <?php if($curr_file=="customers.php" || $curr_file=="customer.php") { ?> class="active" <?php }?>><a href="customers.php">Customers</a></li>
							<li <?php if($curr_file=="mails.php" || $curr_file=="mail.php") { ?> class="active" <?php }?>><a href="mails.php">Mailing List</a></li>
							<li <?php if($curr_file=="campaigns.php" || $curr_file=="campaign.php") { ?> class="active" <?php }?>><a href="campaigns.php">Campaigns</a></li>
							<li <?php if($curr_file=="tokens.php" || $curr_file=="token.php") { ?> class="active" <?php }?>><a href="tokens.php">Tokens</a></li>
							<li <?php if($curr_file=="kb_categories.php" || $curr_file=="kb_category.php") { ?> class="active" <?php }?>><a href="kb_categories.php">KB Categories</a></li>
							<li <?php if($curr_file=="kb_articles.php" || $curr_file=="kb_article.php") { ?> class="active" <?php }?>><a href="kb_articles.php">KB Articles</a></li>
							<li <?php if($curr_file=="news_subs.php" || $curr_file=="news_sub.php") { ?> class="active" <?php }?>><a href="news_subs.php">Newsletter Subscribers</a></li>
							<li <?php if($curr_file=="enquiries.php" || $curr_file=="enquiry.php") { ?> class="active" <?php }?>><a href="enquiries.php">Enquiries</a></li>
							<li <?php if($curr_file=="templates.php" || $curr_file=="template.php") { ?> class="active" <?php }?>><a href="templates.php">Templates</a></li>
							<li <?php if($curr_file=="components.php" || $curr_file=="component.php") { ?> class="active" <?php }?>><a href="components.php">Components</a></li>
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
				<div <?php if($curr_file=="users.php" || $curr_file=="user.php") { echo 'class="accordion-heading sdb_h_active"'; } else { echo 'class="accordion-heading"'; } ?>>
					<a href="#collapseThree" data-parent="#side_accordion" data-toggle="collapse" class="accordion-toggle">
						<i class="icon-user"></i> Account manager
					</a>
				</div>
				<div <?php if($curr_file=="users.php" || $curr_file=="user.php") { echo 'class="accordion-body collapse in"'; } else { echo 'class="accordion-body collapse"'; } ?> id="collapseThree">
					<div class="accordion-inner">
						<ul class="nav nav-list">
						<?php
						$user=$db->get_row("select access_rights_code, uuid from wi_users where code='".$_SESSION['UserEmail']."'");
						if($user->access_rights_code>=11) {
						?>
							<li <?php if($curr_file=="users.php") { ?> class="active" <?php }?>><a href="users.php">Users</a></li>
							<li <?php if($curr_file=="user.php") { ?> class="active" <?php }?>><a href="user.php">Add new user</a></li>
							<?php } else { ?>
							<li <?php if($curr_file=="user.php") { ?> class="active" <?php }?>><a href="user.php?uuid=<?php echo $user->uuid; ?>">Update my account</a></li>
							<?php } ?>
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