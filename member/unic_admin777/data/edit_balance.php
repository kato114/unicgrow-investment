<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
if(isset($_POST['submit']) and ($_SESSION['dccan_admin_login'] == 1))
{
	include("../function/functions.php");
	$username = $_REQUEST['username'];
	$user_id = get_new_user_id($username);
	if($user_id != 0)
	{
		$amount = $_REQUEST['amount'];
		$date = date('Y-m-d');
		query_execute_sqli("update wallet set amount = '$amount' , date = '$date' where id = '$user_id' ");
		
		query_execute_sqli("insert into account (user_id , cr , date , account) values ('$user_id' , '$amount' , '$date' , 'Amount Edit By Admin')");
		
		data_logs($id,$data_log[11][0],$data_log[11][1],$log_type[5]);
		$edit_amount = $amount;
		$username_log = $username;
		include("../function/logs_messages.php");
		data_logs($user_id,$data_log[12][0],$data_log[12][1],$log_type[4]);
		
		print "Amount Edit Successfully!";
	}
	else { print "Please Enter correct username!";	 }
}
else
{
	$username = $_REQUEST['username'];
?>
	<table width="600" border="0">
	<form name="add_funds" action="index.php?page=edit_balance" method="post">
  <tr>
    <td colspan="2" style="font-size:16px; color:#CC0000;"><strong>Add Amount Pannel</strong></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><p>Enter Username :</p></td>
    <td><p><input type="text" name="username" value="<?php print $username; ?>" style="width:150px;" class="form-control" /></p></td>
  </tr>
  <tr>
    <td><p>Amount :</p></td>
    <td><p><input type="text" name="amount" style="width:150px;" class="input-small" />&nbsp;&#36;</p></td>
  </tr>
  <tr>
    <td colspan="0"><p></p><p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Submit" class="btn btn-info" /></p></td>
  </tr>
  </form>
</table>

<?php } ?>

