<?php require_once("connect.php"); ?>

		<?php
			
			if($docs = $db->get_results("SELECT ID,Document, Code FROM `documents` WHERE SiteID = '".$_REQUEST['site_id']."'  ORDER BY ID")) {
			foreach($docs as $doc){
				$id=$doc->ID;
				$doc_name=$doc->Document;
				
				$top_level_cat='';
				$cat_group='';
				if($top_level_id=$db->get_row("select TopLevelID, CategoryGroupID from `categories` where code='".$doc->Code."'")){
					if($top_level_id->TopLevelID!=0) {
					$top_level_cat=$db->get_var("select Name from `categories` where ID=".$top_level_id->TopLevelID." ");
					}
					$cat_group=$db->get_var("select Name from categorygroups where ID=".$top_level_id->CategoryGroupID."");
				}
				?>
				<option value='<?php echo $id; ?>' >
						<?php if(isset($top_level_cat) && $top_level_cat!=''){ echo $top_level_cat.'('.$cat_group.')'.':  '; } elseif($cat_group!=''){ echo $cat_group.':  '; } echo $doc_name; ?>
				</option>
				<?php
			} }
		?>