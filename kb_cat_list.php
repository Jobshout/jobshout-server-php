<?php require_once("connect.php"); 
$where_cond=" and SiteID = '".$_POST['site_id']."' ";

?>
<select name="category[]" id="category" size="5" multiple="multiple">
													
													<?php
													if($categories=$db->get_results("select * from kb_categories where status='1' $where_cond ")){
													foreach($categories as $category){
													?>
													<option value="<?php echo $category->uuid; ?>"><?php echo $category->name; ?></option>
													
													<?php } } ?>
													</select>
