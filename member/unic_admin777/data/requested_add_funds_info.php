<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");

$id = $_REQUEST['inf'];

$q = query_execute_sqli("select * from add_funds where id = '$id' ");
while($r = mysqli_fetch_array($q))
{
	$pay_mode = $r['pay_mode'];
	$user_name = $r['name'];
	$address = $r['address'];
	$country = $r['country'];
	$account_id = $r['account_id'];
	$amount = $r['amount'];

}

?>

<div align="left" style="padding-left:50px; padding-top:20px;"><a href="index.php?page=requested_add_funds"><img src="images/ip_icon_02_Back1.png" style="height:50px; width:50px" /></a></div><br />
<table width="400" border="0">
					  <tr>
						<td colspan="2"><font color="#2B2B57" size="+2">Add Fund Information</font></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Requested Amount</h1> </td>
						<td><strong><?php print $amount; ?></strong></td>
						</tr>
						<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Account Id</h1> </td>
						<td><?php print $account_id; ?></td>
						</tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Name</h1></td>
						<td><?php print $user_name; ?></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Address</h1></td>
						<td><?php print $address; ?></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Country</h1></td>
						<td><?php print $country; ?></td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td><h1>Currency</h1></td>
						<td>RC</td>
					  </tr>
					 
					</table>