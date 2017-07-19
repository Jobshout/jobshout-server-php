<?php 
session_start();
require_once("connect.php");
if($_SESSION['UserEmail'] =='') {
       header("location:index.php");
}
require_once('include/main-header.php'); ?>


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
			<a href="index.php"><i class="icon-home"></i></a>
		</li>
		<li>
			<a href="#">Search Results</a>
		</li>
		
		
	</ul>
</div>
                    </nav>
                    
                   
                    <div class="row-fluid">
                        <div class="span12">
						<?php 
						if(isset($_POST['query']) && $_POST['query']!='') { 
						$srch=$_POST['query'];
						function get_site_cond($tbl_alias){
							if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
								return $tbl_alias.".SiteID in ('".$_SESSION['site_id']."') ";
							}
							else{
								return '1';
							}
						}
						
						
						?>
                          <h3 class="heading">Search results for <strong><em><?php echo $srch; ?></em></strong></h3>
                         <?php
						 $query=$db->escape($srch);
						 //pages
						 
						 $pages_matched=$db->get_var("select count(*) as num from documents d join sites s on d.SiteID=s.ID where ".get_site_cond('d')." and d.Type='page' and ( d.Code like '%".$query."%' or d.Document like '%".$query."%' or d.Body like '%".$query."%' or d.PageTitle like '%".$query."%' or s.Name like '%".$query."%' )");
						 //jobs
						 $jobs_matched=$db->get_var("select count(*) as num from documents d join sites s on d.SiteID=s.ID left outer join wi_users u on d.UserID=u.ID where ".get_site_cond('d')." and d.Type='job' and ( d.Code like '%".$query."%' or d.Document like '%".$query."%' or d.Body like '%".$query."%' or d.PageTitle like '%".$query."%' or s.Name like '%".$query."%' or u.firstname like '%".$query."%' or u.lastname like '%".$query."%' or u.fullname like '%".$query."%' )");
						 //blogs
						 $blogs_matched=$db->get_var("select count(*) as num from documents d join sites s on d.SiteID=s.ID left outer join wi_users u on d.UserID=u.ID where ".get_site_cond('d')." and d.Type='blog' and ( d.Code like '%".$query."%' or d.Document like '%".$query."%' or d.Body like '%".$query."%' or d.PageTitle like '%".$query."%' or s.Name like '%".$query."%' or u.firstname like '%".$query."%' or u.lastname like '%".$query."%' or u.fullname like '%".$query."%' )");
						 //tokens
						 $token_matched=$db->get_var("select count(*) as num from tokens t join sites s on t.SiteID=s.ID where ".get_site_cond('t')." and ( t.Code like '%".$query."%' or t.Description like '%".$query."%' or t.TokenText like '%".$query."%' or s.Name like '%".$query."%' )");
						 //templates
						 $template_matched=$db->get_var("select count(*) as num from templates t join sites s on t.SiteID=s.ID where ".get_site_cond('t')." and t.Type=0 and ( t.Name like '%".$query."%' or t.Code like '%".$query."%' or t.Description like '%".$query."%' or t.TemplateBLOB like '%".$query."%' or s.Name like '%".$query."%' )");
						 //components
						 $component_matched=$db->get_var("select count(*) as num from templates t join sites s on t.SiteID=s.ID where ".get_site_cond('t')." and t.Type=1 and ( t.Name like '%".$query."%' or t.Code like '%".$query."%' or t.Description like '%".$query."%' or t.TemplateBLOB like '%".$query."%' or s.Name like '%".$query."%' )");
						 //categories
						 $category_matched=$db->get_var("select count(*) as num from categories c join sites s on c.SiteID=s.ID where ".get_site_cond('c')." and ( c.Code like '%".$query."%' or c.Name like '%".$query."%' or s.Name like '%".$query."%' )");
						 //category_groups
						 $cat_group_matched=$db->get_var("select count(*) as num from categorygroups cg join sites s on cg.SiteID=s.ID where ".get_site_cond('cg')." and ( cg.Code like '%".$query."%' or cg.Name like '%".$query."%' or s.Name like '%".$query."%' )");
						 ?>
						 
						<ul>
						<?php if($pages_matched>0) { ?>
						<li><?php echo $pages_matched; ?> <a target="_blank" href="pages.php?srch=<?php echo $srch; ?>">Pages</a> found</li>
						<?php } ?>
						<?php if($jobs_matched>0) { ?>
						<li><?php echo $jobs_matched; ?> <a target="_blank" href="jobs.php?srch=<?php echo $srch; ?>">Jobs</a> found</li>
						<?php } ?>
						<?php if($blogs_matched>0) { ?>
						<li><?php echo $blogs_matched; ?> <a target="_blank" href="blogs.php?srch=<?php echo $srch; ?>">Blogs</a> found</li>
						<?php } ?>
						<?php if($token_matched>0) { ?>
						<li><?php echo $token_matched; ?> <a target="_blank" href="tokens.php?srch=<?php echo $srch; ?>">Tokens</a> found</li>
						<?php } ?>
						<?php if($template_matched>0) { ?>
						<li><?php echo $template_matched; ?> <a target="_blank" href="templates.php?srch=<?php echo $srch; ?>">Templates</a> found</li>
						<?php } ?>
						<?php if($component_matched>0) { ?>
						<li><?php echo $component_matched; ?> <a target="_blank" href="components.php?srch=<?php echo $srch; ?>">Components</a> found</li>
						<?php } ?>
						<?php if($category_matched>0) { ?>
						<li><?php echo $category_matched; ?> <a target="_blank" href="categories.php?srch=<?php echo $srch; ?>">Categories</a> found</li>
						<?php } ?>
						<?php if($cat_group_matched>0) { ?>
						<li><?php echo $cat_group_matched; ?> <a target="_blank" href="cat_groups.php?srch=<?php echo $srch; ?>">Category Groups</a> found</li>
						<?php } ?>
						
						</ul> 
						 
						 
						 <?php } ?>  
                        </div>
                    </div>
                        
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
            <?php require_once('include/footer.php');?>
			<!-- datatable -->
            <script src="lib/datatables/jquery.dataTables.min.js"></script>
            <script src="lib/datatables/extras/Scroller/media/js/Scroller.min.js"></script>
			<!-- datatable functions -->
            <script src="js/gebo_datatables.js"></script>
			
            
		</div>
	</body>
</html>