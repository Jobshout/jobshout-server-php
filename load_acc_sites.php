<?php
require_once("connect.php"); 
$usr_uuid=$_POST['usr_uuid'];
$where_cond=' 1';
if(isset($_POST['acc_uuid'])){
	$acc_site=$_POST['acc_uuid'];
	$where_cond=" uuid_site!='$acc_site'";
}

?>

<?php
$sites=$db->get_results("select GUID,name from sites where zStatus='Active' and GUID not in (select uuid_site from wi_user_sites where uuid_user='".$usr_uuid."' and $where_cond)");
foreach($sites as $site)
{
?>
	<option <?php if($acc_site==$site->GUID) { ?> selected="selected" <?php } ?> value="<?php echo $site->GUID; ?>"><?php echo $site->name; ?></option>	
<?php } ?>