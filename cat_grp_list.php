<?php require_once("connect.php"); 
$where_cond=" and SiteID = '".$_POST['site_id']."' ";

?>
<option value="0">-- Not specified --</option>
											<?php
												$op = $db->get_results("SELECT ID,Name FROM `categorygroups` WHERE Active='1' $where_cond ORDER BY ID");
												foreach($op as $opt){
													$id=$opt->ID;
													$name=$opt->Name;
													?>
													<option value="<?php echo $id; ?>"><?php echo $name; ?></option>	
													<?php
												}
											?>
