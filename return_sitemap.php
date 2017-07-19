<?php
session_start();
require_once("connect.php");

$id=$_POST['id'];
$guid=$_POST['guid'];
	
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

$db->query('SET NAMES utf8');
if($_POST['str']=='cat_grp'){
	if($categories= $db->get_results("select * from categories where $sWhere and (CategoryGroupID='".$id."' or CategoryGroup_GUID='".$guid."') order by Name asc")){
		foreach($categories as $category){									
			if($documents= $db->get_var("select count(documents.GUID) from documents join documentcategories on (documents.ID=documentcategories.DocumentID or documents.GUID=documentcategories.Document_GUID) where $docWhere and (documentcategories.CategoryID='".$category->ID."' or documentcategories.Category_GUID='".$category->GUID."')")){
			echo "<li class='cats'><a href='javascript:void(0)' onclick='show_hide(\"docs_".$category->ID."\", $(this), \"cat\", \"".$category->ID."\", \"".$category->GUID."\")' ><i class='splashy-bullet_blue_expand'></i></a><a title='".$category->Name."' href='category.php?GUID=".$category->GUID."' target='_blank'>".$category->Name."</a>";
				echo "<ul class='docs_".$category->ID." sitemap' style='display:none'>";				
				echo "</ul>";
			}
			else{
				echo "<li class='cats'><a href='javascript:void(0)' onclick='show_hide(\"docs_".$category->ID."\")' ><i class='splashy-bullet_blue_small'></i></a><a title='".$category->Name."' href='category.php?GUID=".$category->GUID."' target='_blank'>".$category->Name."</a>";
			}
			echo "</li>";								
		}								
	}
}
elseif($_POST['str']=='cat'){
	if($documents= $db->get_results("select documents.* from documents join documentcategories on (documents.ID=documentcategories.DocumentID or documents.GUID=documentcategories.Document_GUID) where $docWhere and (documentcategories.CategoryID='".$id."' or documentcategories.Category_GUID='".$guid."') order by Document asc")){
		foreach($documents as $document){
			if(strtolower($document->Type)=='page'){
				echo "<li class='doc'><a href='javascript:void(0)' ><i class='splashy-bullet_blue_small'></i></a><a title='".$document->Document."' href='page.php?GUID=".$document->GUID."' target='_blank'>".$document->Document."</a></li>";
			}
			elseif(strtolower($document->Type)=='job'){
				echo "<li class='doc'><a href='javascript:void(0)' ><i class='splashy-bullet_blue_small'></i></a><a title='".$document->Document."' title='".$document->Document."' href='job.php?GUID=".$document->GUID."' target='_blank'>".$document->Document."</a></li>";
			}
			elseif(strtolower($document->Type)=='blog'){
				echo "<li class='doc'><a href='javascript:void(0)' ><i class='splashy-bullet_blue_small'></i></a><a title='".$document->Document."' href='blog.php?GUID=".$document->GUID."' target='_blank'>".$document->Document."</a></li>";
			}
		}
	}

}

?>