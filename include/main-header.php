<?php

$user_details=$db->get_row("select * from wi_users where code='".$_SESSION['UserEmail']."'");
$user_access_level= $user_details->access_rights_code;
$login_user_uuid= $user_details->uuid;

if(isset($_POST['switch_site']) && $_POST['switch_site']!=''){
	if(isset($_SESSION['last_search'])){ unset($_SESSION['last_search']); }
	if($_POST['switch_site'] == 'all'){
		
		if($user_access_level>=11){
			unset($_SESSION['site_id']);
			setcookie("sitecode", "", time()-3600);
			setcookie('sitedir', "", time()-3600);
		}else{
			if($sites=$db->get_results("select ID, GUID, Name, Code from sites where (GUID in (select uuid_site from wi_user_sites where uuid_user='".$login_user_uuid."') OR GUID = '".$user_details->Site_GUID."' or ID = '".$user_details->SiteID."') order by Code ASC limit 0,25")){
				foreach($sites as $site){
					$site_arr[]=$site->ID;
				}
				$_SESSION['site_id']=implode("','",$site_arr);
			}
		}
	}else{
		if($switch_site=$db->get_row("select ID, Code, RootDirectory, WebsiteAddress from sites where GUID='".$_POST['switch_site']."'")) {
			$_SESSION['site_id']=$switch_site->ID;
			setcookie('sitecode', $switch_site->Code, time()+60*60*24*365);
			if($switch_site->RootDirectory==''){
				setcookie('sitedir', $switch_site->Code, time()+60*60*24*365);
			} else {
				setcookie('sitedir', $switch_site->RootDirectory, time()+60*60*24*365);
			}

		}
	}
}
//elseif(isset($_COOKIE['sitecode']) && (!isset($_SESSION['site_id']) || strpos($_SESSION['site_id'], ",") === false)){
elseif(isset($_COOKIE['sitecode']) && (!isset($_SESSION['site_id']) || strpos($_SESSION['site_id'], ",") !== false)){
	if($switch_site=$db->get_var("select ID from sites where Code='".$_COOKIE['sitecode']."'")) {
		if($user_access_level>=11){
			$_SESSION['site_id']=$switch_site;
		}
		elseif(isset($_SESSION['site_id']) && in_array($switch_site, explode(',',$_SESSION['site_id']))){
			$_SESSION['site_id']=$switch_site;
		}
	}
}

header('Content-Type: text/html; charset=utf-8'); //header('Content-Type: text/html; charset=ISO-8859'); 
//if(!isset($WindowTitle) || $WindowTitle=='') $WindowTitle = "Jobshout";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php if(isset($WindowTitle) && $WindowTitle!='') { echo $WindowTitle; } else { echo "Jobshout"; }?></title>
    
        
			<?php if(isset($_GET['css']) && $_GET['css']!='') { ?>
			<!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.green.css" />
            <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
			 <!-- jQuery UI theme-->
            <link rel="stylesheet" href="lib/jquery-ui/css/Aristo/Aristo.css" />
			<link rel="stylesheet" href="css/<?php echo $_GET['css']; ?>.css" />
			<!-- main styles -->
            <link rel="stylesheet" href="css/style-green.css" />
			<?php } else { ?>
			<!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
			 <!-- jQuery UI theme-->
            <link rel="stylesheet" href="lib/jquery-ui/css/Aristo/Aristo.css" />
        <!-- gebo blue theme-->
            <link rel="stylesheet" href="css/blue.css" />
			<!-- main styles -->
            <link rel="stylesheet" href="css/style.css" />
			<?php } ?>
        <!-- breadcrumbs-->
            <link rel="stylesheet" href="lib/jBreadcrumbs/css/BreadCrumb.css" />
        <!-- tooltips-->
            <link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
        <!-- code prettify -->
            <link rel="stylesheet" href="lib/google-code-prettify/prettify.css" />    
        <!-- notifications -->
            <link rel="stylesheet" href="lib/sticky/sticky.css" />    
        <!-- splashy icons -->
            <link rel="stylesheet" href="img/splashy/splashy.css" />
			<!-- datepicker -->
            <link rel="stylesheet" href="lib/datepicker/datepicker.css" />
            
        <!-- main styles -->
            <!--<link rel="stylesheet" href="css/style.css" />-->
			
            <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans" />
			<link rel="stylesheet" href="css/jquery-impromptu.css" />
        <!-- Favicon -->
            <link rel="shortcut icon" href="favicon.ico" />
		
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="css/ie.css" />
        <![endif]-->
        	
        <!--[if lt IE 9]>
            <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script src="lib/flot/excanvas.min.js"></script>
        <![endif]-->
		<script>
			//* hide all elements & show preloader
			document.getElementsByTagName('html')[0].className = 'js';
		</script>