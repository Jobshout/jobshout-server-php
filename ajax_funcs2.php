<?php 
require_once('../includes/midas.inc.php');
$cat_id=$_GET['cat_id'];
switch($operation)
{
case 'a':
 $sql_cat="select * from eu_category where cat_parent_id='".$cat_id."'";
 $result=db_query($sql_cat);
 $str_subct='<select name="sub_cat" onchange=chk(this.value,\'b\')>';
  $str_subct.='<option value="">select any one</option>';
while($row = mysql_fetch_array($result))
{
	$str_subct.='<option value="'.$row['cat_id'].'">'.$row['cat_name'].'</option>';
}
$str_subct.='</select>';
echo $str_subct;
 
break;
case 'b':
 $sql_product="select * from eu_products where prd_cat_id='".$cat_id."'";
$result=db_query($sql_product);
//$str_product='<input name="Product" type="checkbox" value="" />';
echo "<table border='1'>
<tr>
<th>Product Name</th>
<th></th>
<th>Select</th>
<th>Enter product Link</th>

</tr>";
while($row = mysql_fetch_array($result))
{    
    $str_product.='<tr>';
	//$str_product.='<td width="141" class="tdLabel">'.'Product Name:'.'</td>';
	$str_product.='<td class="tdLabel" >'.$row['prd_name'].'<td>';
    $str_product.='<td>'.'<input name="product_id[]" type="checkbox" value="'.$prd_id.'"/>'.'</td>';
	
	//$str_product.='<td class="tdLabel" >'.'eneter product Link'.'<td>';
	$str_product.='<td class="tdLabel" >'.'<input type="text" name="product_link[]" />'.'<td>';
	$str_product.='</br>';
	$str_product.='</tr>';
	
}
echo $str_product;
echo "</table>";
break;
default:
  break;
}
?>




