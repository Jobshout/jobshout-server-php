<?php
session_start();
require_once("connect.php");
if (isset($_POST['main_site'])) {
$cond= " 1 and ";
if(isset($_SESSION['site_id']) && $_SESSION['site_id']!='') {
	$cond= " id in ('".$_SESSION['site_id']."') and ";
}

					$sites=$db->get_results("select id,name,GUID from sites where $cond zStatus='Active' and id<>'".$_POST['main_site']."'");
					foreach($sites as $site)
					{
					?>
					<option value="<?php echo $site->id; ?>"><?php echo $site->name; ?></option>	
					<?php } 
    
} //isset
?>