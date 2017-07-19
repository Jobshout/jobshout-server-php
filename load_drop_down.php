<?php
require_once("connect.php"); 
$master_site=$_POST['site_uuid'];
$related_site='';
$where_cond=' 1';
if(isset($_POST['rel_uuid'])){
	$related_site=$_POST['rel_uuid'];
	$where_cond=" uuid_related_site!='$related_site'";
}

?>

<?php
$sites=$db->get_results("select GUID,name from sites where zStatus='Active' and GUID!='$master_site' and GUID not in (select uuid_related_site from wi_related_sites where $where_cond)");
foreach($sites as $site)
{
?>
	<option <?php if($related_site==$site->GUID) { ?> selected="selected" <?php } ?> value="<?php echo $site->GUID; ?>"><?php echo $site->name; ?></option>	
<?php } ?>