<span style="float: right; position: static; margin-right: 4000px;">
		<?php 
		if(isset($_SESSION['site_id']) && strpos($_SESSION['site_id'], ",") === false){
			if($curr_site_name=$db->get_row("select name,WebsiteAddress from sites where id='".$_SESSION['site_id']."'")){
			echo "Current Site : <strong><em><a href='".$curr_site_name->WebsiteAddress."' title='_blank'>".$curr_site_name->name."</a></em></strong>";
			}else{
				echo "<a>No site selected</a>";
			}
		}
		else{
			echo "<a>No site selected</a>";
		}
		echo " | ";
		if(isset($connect_to)!= ''){ echo "<a>Current Database : <strong><em>".$connect_to."</em></strong></a>"; }
		?>
		</span>