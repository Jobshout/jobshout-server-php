<?php
require_once("connect.php");
session_start();
if(isset($_SESSION['UserName'])){
header("Location:pages.php");
}

define("SITE_ID","29201");
define("SITE_GUID","88B9F09C-5975-4674-BCE6-D6E65EB98333");
if(isset($_POST['SignIn'])){
	$LoginEmail = $_POST['email'];	
	$LoginPassword = $_POST['pass'];
	$site_id='29201';	
	$LoginQuery = "SELECT cont.GUID, cont.Name uName,cat.ID cID,cat.Code cCode,cCat.CategoryID 
	FROM contacts cont, categories cat, contactcategories cCat WHERE cont.Code='$LoginEmail' 
	AND cont.zPassword='$LoginPassword' AND cont.zStatus='ACTIVE' AND cat.SiteID=cont.SiteID 
	AND cat.Code='registered-users' AND
	( cat.ID=cCat.CategoryID || cat.GUID=cCat.Category_GUID ) AND ( cont.ID=cCat.ContactID || cont.GUID=cCat.Contact_GUID )
	AND cont.SiteID='".SITE_ID."' AND cont.Site_GUID='".SITE_GUID."'";
	//echo $LoginQuery; exit;
	$LoginData = $db->get_row($LoginQuery);
	if($db->num_rows>0){
		$_SESSION['uCatID']=$LoginData->cID;
		$_SESSION['uCatCode']=$LoginData->cCode;
		$_SESSION['UserName'] = $LoginData->uName;
		$_SESSION['UserEmail'] = $LoginEmail;
		$_SESSION['GUID'] = $LoginData->GUID;

		header("Location:pages.php");
	}
	else
		$error = 'INVALID_LOGIN';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form action="" method="post" name="form" id="form" >
<div  >
							<?php if(isset($error) && $error != ''){ ?>
                                   <div class="error"><?php echo $error; ?></div>
                                <?php   } ?>
						</div>
<table>

<tr><td width="50%">User Name</td><td><input type="text" name="email" size="30px"></td></tr>
<tr><td>Password</td><td><input type="password" name="pass" size="30px"></td></tr>
<tr><td align="center"><input type="submit" name="SignIn" value="Login" ></td>
</tr>
</table>
</form>
</body>
</html>
