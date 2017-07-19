
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Updation Form</title>
<!--Load CSS Styles -->
<link rel="stylesheet" type="text/css" href="style-new.css"/>
<link href="css/jquery-ui-1.9.1.custom.css" rel="stylesheet">

<script src="js/jquery-1.8.2.js"></script>
<script src="js/jquery-ui-1.9.1.custom.js"></script>
<script src="js/timepicker.js"></script>
<script>
	$(function() {
		$( "#Published_timestamp" ).datetimepicker({
			inline: true
		});
	});
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

<style>
.dark {
background-color: #eeeeee;
}
.ui-draggable, .ui-droppable {
	background-position: top;
}
</style>
<?php
include 'connect.php';
?>
</head>

<?php
function NewGuid() { 
	$s = strtoupper(md5(uniqid(rand(),true))); 
	$guidText = 
		substr($s,0,8) . '-' . 
		substr($s,8,4) . '-' . 
		substr($s,12,4). '-' . 
		substr($s,16,4). '-' . 
		substr($s,20); 
	return $guidText;
}

if(!empty($_POST['submit']))
{
	//include("connect.php");
	
	$documentId=$_POST["documentId"];
	$document_GUID=$_POST["document_GUID"];
	$documentSiteGUID=$_POST["documentSiteGUID"];
	$SiteID='29201';
	$Code=$_POST["Code"];
	$Document=$_POST["Document"];
	$Header=$_POST["Header"];
	$Body=addslashes($_POST["Body"]);
	$MetaTagKeywords=$_POST["MetaTagKeywords"];
	$MetaTagDescription=addslashes($_POST["MetaTagDescription"]);
	$UserID=$_POST["UserID"];
	$Title=$_POST["Title"];
	$PageTitle=$_POST["PageTitle"];
	$Type=$_POST["Type"];
	$SiteName=$_POST["SiteName"];
	$Reference=$_POST["Reference"];
	$Sync_LastTime=$_POST["Sync_LastTime"];
	
	$Published_timestamp= strtotime($Published_timestamp);
	$guid= $_GET['GUID'];
	$time= time();
	
	$category1=$_POST["category1"];
	$category2=$_POST["category2"];
	$category3=$_POST["category3"];
	$category4=$_POST["category4"];
	
	$db->query("delete FROM documentcategories WHERE SiteID='$SiteID' AND DocumentID='$documentId'");
	
	$categories = array($category1,$category2,$category3,$category4);
	foreach($categories as $categoriesID){
		if($categoriesID != ''){ 
			
			$selectCategory = $db->get_row("SELECT CategoryGroupID,Server_Number,GUID FROM categories WHERE SiteID='$SiteID' AND ID='$categoriesID'");
			$CategoryGroupID = $selectCategory->CategoryGroupID;
			$Server_Number = $selectCategory->Server_Number;
			$Category_GUID = $selectCategory->GUID;
			
			$dcGUID=NewGuid();
			
			
			$query= $db->query("INSERT INTO documentcategories(ID, Created, Modified, SiteID,CategoryGroupID, CategoryID,DocumentID, GUID,Server_Number,Site_GUID,Category_GUID,Document_GUID, Sync_Modified) 
			VALUES(Null,'$time','$time','$SiteID','$CategoryGroupID','$categoriesID','$documentId','$dcGUID','$Server_Number','$documentSiteGUID','$Category_GUID','$document_GUID','0')");
			//$db->debug();
		}
	}
	$db->query("UPDATE documents SET SiteID='$SiteID', Code='$Code', Document='$Document', Header='$Header', Body='$Body', MetaTagKeywords='$MetaTagKeywords', MetaTagDescription='$MetaTagDescription', UserID='$UserID',
	Title='$Title',PageTitle='$PageTitle',
	Type='$Type',SiteName='$SiteName',Reference='$Reference',
	Sync_LastTime='$Sync_LastTime',Published_timestamp='$Published_timestamp'
	WHERE GUID ='$guid'") ;
	//$db->debug();
}
?>
<?php
$guid= $_GET['GUID'];
$document = $db->get_row("SELECT ID,SiteID,Code,Document,Header,Body,MetaTagKeywords,MetaTagDescription,UserID,Title,PageTitle,Type,SiteName,GUID,Site_GUID,Reference,Sync_LastTime,
Published_timestamp FROM documents where documents.GUID ='$guid'");

		$documentID=$document->ID;
		$SiteID=$document->SiteID;
		$Code=$document->Code;
		$Document=$document->Document;
		$Header=$document->Header;
		$Body=$document->Body;
		$MetaTagKeywords=$document->MetaTagKeywords;
		$MetaTagDescription=$document->MetaTagDescription;
		$UserID=$document->UserID;
		$Title=$document->Title;
		$PageTitle=$document->PageTitle;
		$Type=$document->Type;
		$SiteName=$document->SiteName;
		$document_GUID=$document->GUID;
		$documentSiteGUID=$document->Site_GUID;
		$Reference=$document->Reference;
		$Sync_LastTime=$document->Sync_LastTime;
		$Published_timestamp=$document->Published_timestamp;
		$date = Date('m/d/Y',$Published_timestamp);

$dc = $db->get_results("SELECT CategoryID FROM documentcategories WHERE SiteID='$SiteID' AND DocumentID='$documentID'");
//$db->debug();
if($dc != ''){
 foreach($dc as $dc){
	$dcCategoryId[]= $dc->CategoryID;
	
}
}

?>

<body>
	<div class="wide_wrap content">
		<div class="wrap padding-top-bottom">
			<div class="post_entry_wide">
				<!--Content area-->
				<h1>Updation For Site 
					<?php
						$documnetSiteId = $db->get_row("SELECT ID,SiteID FROM `documents` WHERE GUID= '" .$_GET['GUID'] ."'");
						$Siteid=$documnetSiteId->SiteID;
						if($Siteid){
						$site = $db->get_row("SELECT Name FROM `sites` WHERE `id`=".$Siteid);
						echo ':'.$site->Name; }
					?>
				</h1>
				<div id="content">
					<div class="feature" style="float:left;color:#999999">	
						<br>
<div><!---start of cat id!--->
<form action="" class="form-comment" method="post">
	
	<div class="holder">
		<label>Code</label>
		<input type="text" value="<?php echo $Code;?>" name="Code" class="text" id="Code">
	</div>
	<div class="holder">
		<label>Document</label>
		<input type="text" value="<?php echo $Document;?>" name="Document" id="Document">
	</div>
	<div class="holder">
		<label>Header</label>
		<input type="text" value="<?php echo $Header;?>" name="Header" class="textbox">
	</div>
	<div class="holder">
		<label>Body</label><br /><br />
		<textarea tabindex="10" name="Body" id="Body"><?php echo $Body;?></textarea>
	</div>
	<div class="holder">
		<label>MetaTagKeywords</label>
		<textarea rows="80" cols="160" tabindex="10" name="MetaTagKeywords" id="MetaTagKeywords"><?php echo $MetaTagKeywords;?></textarea>
	</div>
	<div class="holder">
		<label>MetaTagDescription</label>
		<textarea rows="80" cols="160" tabindex="10" name="MetaTagDescription" id="MetaTagDescription"><?php echo $MetaTagDescription;?></textarea>
	</div>
	<div class="holder">
		<label>UserID</label>
		<select onChange="" name="UserID" id="UserID">
		<option value="0">-- Select User --</option>
		<?php
			$documentUserID = $UserID;
			$users = $db->get_results("SELECT ID,Name FROM `users` WHERE zStatus='Active' AND SiteID='$Siteid' ORDER BY ID");
			foreach($users as $user){
				$UserID=$user->ID;
				$name=$user->Name;
				?>
				<option <?php if($UserID == $documentUserID) { echo "selected"; } ?> value='<?php echo $UserID; ?>' >
						<?php echo $name; ?>
				</option>
				<?php
			}
		?>
		</select>
	</div>
	
	<div class="holder">
		<label>Title</label>
		<input type="text" value="<?php echo $Title;?>" name="Title" class="textbox">
	</div>
	<div class="holder">
		<label>PageTitle</label>
		<input type="text" value="<?php echo $PageTitle;?>" name="PageTitle" class="textbox">
	</div>
	<div class="holder">
		<label>Type</label>
		<input type="text" value="<?php echo $Type;?>" name="Type">
	</div>
	<div class="holder">
		<label>SiteName</label>
		<input type="text" value="<?php echo $SiteName;?>" name="SiteName">
	</div>
	<div class="holder">
		<label>Reference</label>
		<input type="text" value="<?php echo $Reference;?>" name="Reference">
	</div>
	<div class="holder">
		<label>Sync_LastTime</label>
		<input type="text" value="<?php echo $Sync_LastTime;?>" name="Sync_LastTime" class="textbox" id="Sync_LastTime">
	</div>
	<div class="holder">
		<label>Published_timestamp</label>
		<input type="text" value="<?php echo $date; ?>" name="Published_timestamp" id="Published_timestamp">
	</div>
	<div class="holder">
		<input type="hidden" name="id" value="<?php $record['GUID'];?>" />
		<input type="hidden" name="documentId" value="<?php echo $documentID;?>" />
		<input type="hidden" name="document_GUID" value="<?php echo $document_GUID;?>" />
		<input type="hidden" name="documentSiteGUID" value="<?php echo $documentSiteGUID;?>" />
		<input type="hidden" value="<?php $Guid = NewGuid(); echo $Guid; ?>" name="dcGUID" class="textbox">
	</div>
	<div class="holder">
		<label>Categories</label>
		<select onChange="" name="category1">
		<option value="">-- Not Specified --</option>
			<?php
			$cat_no=0;
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='Page' AND SiteID='$Siteid' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='page' AND SiteID='$Siteid' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==0) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categorygroupName.':  '.$categoryName; ?>
				</option>
				<?php
				
				}
			}
		?>
		</select>
		<select onChange="" name="category2">
		<option value="">-- Not Specified --</option>
			<?php
			$cat_no=0;
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='Page' AND SiteID='$Siteid' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='page' AND SiteID='$Siteid' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==1) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categorygroupName.':  '.$categoryName; ?>
				</option>
				<?php
				
				}
			}
		?>
		</select>
		<select onChange="" name="category3">
		<option value="">-- Not Specified --</option>
			<?php
			$cat_no=0;
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='Page' AND SiteID='$Siteid' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='page' AND SiteID='$Siteid' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==2) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categorygroupName.':  '.$categoryName; ?>
				</option>
				<?php
				
				}
			}
		?>
		</select>
		<select onChange="" name="category4">
		<option value="">-- Not Specified --</option>
			<?php
			$cat_no=0;
			$categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Type='Page' AND SiteID='$Siteid' ORDER BY Name");
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				$categories = $db->get_results("SELECT ID,Name, CategoryGroupID FROM `categories` WHERE Type='page' AND SiteID='$Siteid' AND CategoryGroupID='$categorygroupId' ");
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
				?>
				<option <?php if(in_array($categoryID, $dcCategoryId)) { if($cat_no==3) { echo "selected"; } $cat_no++; } ?> value='<?php echo $categoryID; ?>' >
						<?php echo $categorygroupName.':  '.$categoryName; ?>
				</option>
				<?php
				
				}
			}
		?>
		</select>

		
	</div>
	<div class="holder">
		<input type="submit" value="Update"  name="submit" class="submit">&nbsp;&nbsp;<a href="pages.php">&nbsp;&laquo;&nbsp;Back</a>
	</div>    
</form>
</div><!----end of cat div-->

                        
					</div>
				</div>
			</div>
		</div>
	</div>

<!--End main wrapper -->
</body>
</html>