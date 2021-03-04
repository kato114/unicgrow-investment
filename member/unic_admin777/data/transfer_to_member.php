<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");

if(isset($_POST['submit']))
{
	include("../function/functions.php");
	$username = $_REQUEST['username'];
	$user_id = get_new_user_id($username);
	if($user_id != 0)
	{
		$amount = $_REQUEST['amount'];
		$date = date('Y-m-d');
		query_execute_sqli("update wallet set amount = '$amount' , date = '$date' where id = '$user_id' ");
		print "Amount Added Successfully!";
	}
	else { print "Please Enter correct username!";	 }
}
else
{ ?>
	<table width="600" border="0">
	<form name="add_funds" action="index.php?page=transfer_to_member" method="post">
  <tr>
    <td colspan="2"><b> Add Amount Pannel</b></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>Enter Username :</td>
    <td><p><input type="text" name="username" size="15" class="form-control" /></p></td>
  </tr>
  <tr>
    <td><p>Amount :</p></td>
    <td><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="amount" size="10" class="input-small" /> RC</p></td>
  </tr>
  <tr>
    <td colspan="2"><p align="center"><input type="submit" name="submit" value="Submit" class="btn btn-info" /></p></td>
  </tr>
  </form>
</table>

<?php } ?>

