<?php require_once("connect.php"); ?>
<option value="">-- Select Category--</option>
		<?php
			
			if($docs = $db->get_results("SELECT ID,Name FROM `categories` WHERE SiteID = '".$_POST['site_id']."'  ORDER BY ID")) {
			foreach($docs as $doc){
				$id=$doc->ID;
				$doc=$doc->Name;
				?>
				<option value='<?php echo $id; ?>' >
						<?php echo $doc; ?>
				</option>
				<?php
			} }
		?>