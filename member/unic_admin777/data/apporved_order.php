<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/functions.php");

$tamount = 0;
$q = query_execute_sqli("select * from shopping_order where order_confirm = 1 ");
while($row = mysqli_fetch_array($q))
{
	$product_cost = $row['product_cost'];
	$tamount = $tamount+$product_cost;
}		
?>
<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=600>
<tr>
    <td class="text-center" colspan="2" align="center"><strong>Total Order</strong></td>
     <td class="text-center" colspan="3" align="center"><strong><?php print $tamount; ?> RC</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="text-center" align="center"><strong>Date</strong></td>
	<td class="text-center" align="center"><strong>Username</strong></td>
     <td class="text-center" align="center"><strong>Products Name</strong></td>
	 <td class="text-center" align="center"><strong>Products Cost</strong></td>
	  <td class="text-center" align="center"><strong>Delivered Date </strong></td>
  </tr>
  

<?php 

$q = query_execute_sqli("select * from shopping_order where order_confirm = 1 ");
while($r = mysqli_fetch_array($q))
{
	$date = $r['date'];
	$product_id = $r['product_id'];
	
	$qur = query_execute_sqli("select * from shopping where product_id = '$product_id' ");
	$num = mysqli_num_rows($q);
	if($num > 0)
	{
		while($rw = mysqli_fetch_array($qur))
		{
			$product_name = $rw['product_name'];
			$product_cost = $rw['product_cost'];
			$title = $rw['title'];
			$discription = $rw['discription'];
			$product_id = $rw['product_id'];
		}
	}		
	$prod_cost = $r['product_cost'];
	$order_confirm = $r['order_confirm'];
	$update_fees = $r['update_fees'];
	$user_id = $r['user_id'];
	$confirm_date = $r['confirm_date'];
	$username = get_user_name($user_id);
	if($order_confirm == 0)
		$ord_st = "Pending";
	else
		$ord_st = "Delivered";
	print "<tr>
		<td  align=\"center\" class=\"input-small\">$date</td>
		<td align=\"center\" class=\"input-small\">$username</td>
		<td align=\"center\" class=\"input-small\">$product_name</td>
		<td align=\"center\" class=\"input-small\">$prod_cost</td>
		<td align=\"center\" class=\"input-small\">$confirm_date</td>
	  </tr>";
}
print "</table>";


