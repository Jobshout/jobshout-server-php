<?php require_once("connect.php"); 
$where_cond='';
if(isset($_POST['type'])) {
$where_cond.="and type='".$_POST['type']."' ";
}
?>

		
		<option value="">-- Select Category --</option>
			<?php
			
			
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE SiteID = '".$_POST['site_id']."' $where_cond ORDER BY Name"))
			{
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE SiteID = '".$_POST['site_id']."' $where_cond and CategoryGroupID='$categorygroupId' ORDER BY Name")){
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName;  if($top_level){ echo ' ('.$top_level.')'; } ?>
				</option>
				<?php
				}
				?>
				</optgroup>
				<?php
				}
				?>
				
				<?php
			} }
			?>
			
			<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE SiteID = '".$_POST['site_id']."' $where_cond and CategoryGroupID not in (select ID from categorygroups ) ORDER BY Name")){
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
						
				?>
				<option value='<?php echo $categoryID; ?>' >
						<?php echo $categoryName; if($top_level){ echo ' ('.$top_level.')'; }  ?>
				</option>
				<?php
				}
				}
				?>
			
			
			