<?php
include('../../security_web_validation.php');
?>
<?php 
include("condition.php");
$q = query_execute_sqli("select * from wallet where id = '".$_SESSION['real_parent_id']."' ");
while($row = mysqli_fetch_array($q))
{
	$amount = $row['amount'];
}
 ?>
	
	<table align="center" width="300" border="0">
	<form name="generate_epin" action="index.php?val=add_member&open=3" method="post">
	  
	  <tr>
		<td align="center" colspan="2">Wallet Amount <?php echo $amount; ?> RC</td>
	  </tr>
	  <tr>
		<td align="center" colspan="2">Registration Fees <?php echo $fees; ?> RC</td>
	  </tr>
	  <tr>
		<td colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
		<td>Generate E-Pin</td>
		<td><input type="submit" name="submit" value="Generate" class="btn btn-info" /></td>
	  </tr>
	  </form>
	</table>
	
