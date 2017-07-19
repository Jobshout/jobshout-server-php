<?php 
require_once("include/lib.inc.php");
require_once('include/main-header.php'); 
?>
<style>
.sitemap {
	list-style: outside none disc;
}
.sitemap li {
	list-style: none;
}
.sitemap .cat_groups {
	font-size: 15px;
}
.sitemap .cats {
	font-size: 14px;
}
.sitemap .doc {
	font-size: 13px;
}
.plus-bx,.splashy-bullet_blue_expand{
	background:url(img/plus.jpg) no-repeat left 3px;
	padding-left:8px;
}
.minus-bx,.splashy-bullet_blue_collapse{
	background:url(img/minus.jpg) no-repeat left 3px;
	padding-left:8px;
}
.sub-cat-arrow, .splashy-bullet_blue_small{
	background:url(img/right-arrow.jpg) no-repeat 2px 5px;
}
</style>
<?php
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='')
{
	$sWhere = "SiteID in ('".$_SESSION['site_id']."')";
	$docWhere = "documents.SiteID in ('".$_SESSION['site_id']."') and documentcategories.SiteID in ('".$_SESSION['site_id']."')";
}
else
{
	$sWhere = "1";
	$docWhere = "1";
}

function get_cats($id,$guid){
	global $db;
	global $sWhere;
	$cat_html='';
	if($categories= $db->get_results("select * from categories where $sWhere and (TopLevelID='".$id."') order by Name asc")){		
		foreach($categories as $category){
			
			$cat_doc_html=get_cats($category->ID, $category->GUID);	
			$doc_html=get_docs($category->ID, $category->GUID);
			if($cat_doc_html!='' || $doc_html!=''){
				$cat_html.="<li class='cats'><a href='javascript:void(0)' onclick='show_hide(\"cats_".$category->ID."\", $(this), \"cat_grp\", \"".$category->ID."\", \"".$category->GUID."\")' ><i class='splashy-bullet_blue_collapse'></i></a><a title='".$category->Name."' href='category.php?GUID=".$category->GUID."' target='_blank'>".$category->Name."</a>";
				$cat_html.="<ul class='cats_".$category->ID." sitemap' >";
				$cat_html.=$cat_doc_html.$doc_html;
				// $cat_html.=$cat_doc_html;
				$cat_html.="</ul>";
				$cat_html.="</li>";	
			}			
		}						
	}
	return $cat_html;
}

function get_docs($id,$guid){
	global $db;
	global $docWhere;
	$doc_html='';
	if($documents= $db->get_var("select count(documents.GUID) from documents join documentcategories on (documents.GUID=documentcategories.Document_GUID) where $docWhere and (documentcategories.CategoryID='".$id."') and documents.Type='page'")){
		$doc_html.="<li class='doc'><a href='javascript:void(0)' ><i class='splashy-bullet_blue_small'></i></a><a title='Pages(".$documents.")' href='pages.php?cat_id=".$id."&cat_guid=".$guid."' target='_blank'>Pages(".$documents.")</a></li>";
	}
	if($documents= $db->get_var("select count(documents.GUID) from documents join documentcategories on (documents.GUID=documentcategories.Document_GUID) where $docWhere and (documentcategories.CategoryID='".$id."') and documents.Type='job'")){
		$doc_html.="<li class='doc'><a href='javascript:void(0)' ><i class='splashy-bullet_blue_small'></i></a><a title='Jobs(".$documents.")' href='jobs.php?cat_id=".$id."&cat_guid=".$guid."' target='_blank'>Jobs(".$documents.")</a></li>";
	}
	if($documents= $db->get_var("select count(documents.GUID) from documents join documentcategories on (documents.GUID=documentcategories.Document_GUID ) where $docWhere and (documentcategories.CategoryID='".$id."') and documents.Type='blog'")){
		$doc_html.="<li class='doc'><a href='javascript:void(0)' ><i class='splashy-bullet_blue_small'></i></a><a title='Blog(".$documents.")' href='blogs.php?cat_id=".$id."&cat_guid=".$guid."' target='_blank'>Blog(".$documents.")</a></li>";
	}
	return $doc_html;
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
			<a href="#">Browse Content by Categories</a>
		</li>
		 
		
		<?php include_once("include/curr_selection.php"); ?>
	</ul>
</div>
                    </nav>
                   
                   <div id="validation" ><span style="color:#00CC00;font-size:18px">
                   <?php if(isset($_SESSION['ins_message']) && $_SESSION['ins_message']!=''){ echo $_SESSION['ins_message']; unset($_SESSION['ins_message']); }?>
					</span></div><br/>
					
					 <div class="row-fluid">
                        <div class="span12">
					
					<?php
					
					 
					
					echo "<ul class='sitemap'>";
					
					$db->query('SET NAMES utf8');
					if($cat_groups= $db->get_results("select * from categorygroups where $sWhere order by Name asc")){						
						foreach($cat_groups as $cat_group){							
							if($categories= $db->get_results("select * from categories where $sWhere and (CategoryGroupID='".$cat_group->ID."') and (TopLevelID='0' and TopLevel_GUID='') order by Name asc")){
								
								$cat_html='';
								foreach($categories as $category){
									
									$cat_doc_html=get_cats($category->ID, $category->GUID);	
									$doc_html=get_docs($category->ID, $category->GUID);
									
									if($cat_doc_html!='' || $doc_html!=''){
										$cat_html.="<li class='cats'><a href='javascript:void(0)' onclick='show_hide(\"cats_".$category->ID."\", $(this))' ><i class='splashy-bullet_blue_collapse'></i></a><a title='".$category->Name."' href='category.php?GUID=".$category->GUID."' target='_blank'>".$category->Name."</a>";
										$cat_html.="<ul class='cats_".$category->ID." sitemap' >";
										$cat_html.=$cat_doc_html.$doc_html;
										// $cat_html.=$cat_doc_html;
										$cat_html.="</ul>";
										$cat_html.="</li>";
									}									
								}
								if($cat_html!=''){
									echo "<li class='cat_groups'><a href='javascript:void(0)' onclick='show_hide(\"cats_grp_".$cat_group->ID."\", $(this))' ><i class='splashy-bullet_blue_collapse'></i></a>".$cat_group->Name."";
									echo "<ul class='cats_grp_".$cat_group->ID." sitemap' >";
									echo $cat_html;
									echo "</ul>";
									echo "</li>";
								}
													
							}
						}
						echo "<li>&nbsp;</li>";
					}
					
					if($categories= $db->get_results("select * from categories where $sWhere and (CategoryGroupID='0' and CategoryGroup_GUID='') and (TopLevelID='0' and TopLevel_GUID='') order by Name asc")){
								
						$cat_html='';
						foreach($categories as $category){
							
							$cat_doc_html=get_cats($category->ID, $category->GUID);	
							$doc_html=get_docs($category->ID, $category->GUID);
							
							if($cat_doc_html!='' || $doc_html!=''){
								$cat_html.="<li class='cats'><a href='javascript:void(0)' onclick='show_hide(\"cats_".$category->ID."\", $(this))' ><i class='splashy-bullet_blue_collapse'></i></a><a title='".$category->Name."' href='cat_group.php?GUID=".$category->GUID."' target='_blank'>".$category->Name."</a>";
								$cat_html.="<ul class='cats_".$category->ID." sitemap' >";
								$cat_html.=$cat_doc_html.$doc_html;
								$cat_html.="</ul>";
								$cat_html.="</li>";
							}									
						}
						if($cat_html!=''){
							echo "<li class='cat_groups'><a href='javascript:void(0)' onclick='show_hide(\"cats_grp_".$cat_group->ID."\", $(this))' ><i class='splashy-bullet_blue_collapse'></i></a>".$cat_group->Name."";
							echo "<ul class='cats_grp_".$cat_group->ID." sitemap' >";
							echo $cat_html;
							echo "</ul>";
							echo "</li>";
						}
											
					}

					echo "</ul>";
					
					?>
					
					</div>
					</div>
                     
                    
                        
                </div>
            </div>
            
			<!-- sidebar -->
            <aside>
                <?php require_once('include/sidebar.php');?>
			</aside>
            
			<?php require_once('include/footer.php');?>
			<script type="text/javascript">
			function show_hide(class_name, ref){
				if($('.'+class_name).html().length){
					$('.'+class_name).slideToggle();
					ref.find('i').toggleClass('splashy-bullet_blue_expand');
					ref.find('i').toggleClass('splashy-bullet_blue_collapse');
				}				
			}
			</script>

		</div>
	</body>
</html>