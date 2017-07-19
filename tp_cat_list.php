<?php require_once("connect.php"); ?>
<option value="0">-- Not specified --</option>
		<?php
			
			if($users = $db->get_results("SELECT * FROM `categories` WHERE active='1' and CategoryGroupID = '".$_POST['cat_grp_id']."'  ORDER BY TopLevelID")) {
			foreach($users as $user){
				$top_level=$db->get_var("select Name from `categories` where ID=".$user->TopLevelID." ");
				?>
				<option value='<?php echo $user->ID; ?>' >
						<?php if($top_level){ echo $top_level.':  '; } echo $user->Name; ?>
				</option>
				<?php
			} }
		?>