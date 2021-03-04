<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/pair_point_calculation.php");

if(isset($_POST['submit']))
{
	$username = $_REQUEST['username'];
	$query = query_execute_sqli("select * from users where username = '$username' ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$id_user = $row['id_user'];
			$user_type = $row['type'];
		}
		if($user_type != 'C')
		{
			$w_q = query_execute_sqli("select * from wallet where id = '$id_user' ");
			while($rr = mysqli_fetch_array($w_q))
			{
				$wallet_amount = $rr['amount'];
			}
			$investment = '';
			$w_q = query_execute_sqli("select * from reg_fees_structure where user_id = '$id_user' ");
			while($rr = mysqli_fetch_array($w_q))
			{
				$investment .= "".$rr['update_fees']." &#36; on ".$rr['date']."<br>";
				
			}

			query_execute_sqli("update users set type = 'C' where id_user = '$id_user' ");
			query_execute_sqli("update wallet set amount = 0 where id = '$id_user' ");
			query_execute_sqli("update reg_fees_structure set reg_fees = 0 , update_fees = 0 , total_days = 0 where user_id = '$id_user' ");
			pair_point_calculation($id_user);
			
			$date = date('Y-m-d');
			$title_block = "block member";
			$blocked = "blocked";
			$log_username = $username;
			include("../function/logs_messages.php");
			data_logs($id_user,$data_log[17][0],$data_log[17][1],$log_type[10]);
			print "User ".$username." Blocked Successfully !";
		}
		else
		{
			print "User ".$username." already Block !";
		}
	}
	else
	{
		print "Please enter correct usernsme !";
	}
}
else
{?>

<table width="600" border="0">
<form name="franchisee" action="" method="post">
  <tr>
    <td colspan="2"><strong>Block User </strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Username :</td>
    <td><input type="text" name="username" class="form-control" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><p align="center"><input type="submit" name="submit" value="Convert" class="btn btn-info" /></p></td>
  </tr>
  </form>
</table>
<?php } ?>


