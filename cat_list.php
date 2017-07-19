<?php require_once("connect.php"); 
$where_cond=" and SiteID = '".$_POST['site_id']."' ";
if(isset($_POST['type'])) {
$where_cond.="and type='".$_POST['type']."' ";
}
?>
<select onChange="" name="category1">
		<option value="">-- Not Specified --</option>
			<?php
			
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name")) {
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE CategoryGroupID='$categorygroupId' $where_cond ")) {
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
				?>
				<option value='<?php echo $categoryID; ?>' >
						<?php if($top_level){ echo $top_level.':  '; } echo $categoryName; ?>
				</option>
				<?php
				
				} }
				?>
				</optgroup>
				<?php
			} }
		?>
		</select>
		<select onChange="" name="category2">
		<option value="">-- Not Specified --</option>
			<?php
			
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name")) {
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE CategoryGroupID='$categorygroupId' $where_cond ")) {
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
				?>
				<option value='<?php echo $categoryID; ?>' >
						<?php if($top_level){ echo $top_level.':  '; } echo $categoryName; ?>
				</option>
				<?php
				
				} }
				?>
				</optgroup>
				<?php
			} }
		?>
		</select>
		<select onChange="" name="category3">
		<option value="">-- Not Specified --</option>
			<?php
			
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name")) {
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE CategoryGroupID='$categorygroupId' $where_cond ")) {
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
				?>
				<option value='<?php echo $categoryID; ?>' >
						<?php if($top_level){ echo $top_level.':  '; } echo $categoryName; ?>
				</option>
				<?php
				
				} }
				?>
				</optgroup>
				<?php
			} }
		?>
		</select>
		<select onChange="" name="category4">
		<option value="">-- Not Specified --</option>
			<?php
			
			if($categorygroups = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE 1 $where_cond ORDER BY Name")) {
			foreach($categorygroups as $group){
				$categorygroupId = $group->ID;
				$categorygroupName = $group->Name;
				?>
				<optgroup label="<?php echo $categorygroupName; ?>">
				<?php
				if($categories = $db->get_results("SELECT ID,Name, CategoryGroupID, TopLevelID FROM `categories` WHERE CategoryGroupID='$categorygroupId' $where_cond ")) {
				foreach($categories as $category){
					$categoryID = $category->ID;
					$categoryName = $category->Name;
					$top_level=$db->get_var("select Name from `categories` where ID=".$category->TopLevelID." ");
				?>
				<option value='<?php echo $categoryID; ?>' >
						<?php if($top_level){ echo $top_level.':  '; } echo $categoryName; ?>
				</option>
				<?php
				
				} }
				?>
				</optgroup>
				<?php
			} }
		?>
		</select>
