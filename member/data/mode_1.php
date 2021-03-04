<?php
include('../security_web_validation.php');
?>
<?php 
session_start();
include("condition.php");
$id = $_SESSION['mlmproject_user_id'];
$user_fees = $_SESSION['dccan_fees'];
$q = query_execute_sqli("select * from wallet where id = '$id' ");
while($row = mysqli_fetch_array($q))
{
	$amount = $row['amount'];
}

?>

	<table align="center" width="300" border="0">
	<form name="generate_epin" action="index.php?val=<?php print $_REQUEST['val']; ?>&open=<?php print $_REQUEST['open']; ?>" method="post">
	  <input type="hidden" name="user_fees" value="<?php echo $user_fees; ?>" />
	  <tr>
		<td align="center" colspan="2">Wallet Amount <?php echo $amount; ?>&#36; </td>
	  </tr>
	  <tr>
		<td align="center" colspan="2">Registration Fees <?php echo $user_fees; ?>&#36; </td>
	  </tr>
	  <tr>
		<td colspan="2">&nbsp;</td>
	  </tr>
	  <tr>
		<td>Generate E-Pin</td>
		<td><input type="submit" name="submit" value="Generate" class="normal-button" /></td>
	  </tr>
	  </form>
	</table>
	
