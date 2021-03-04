<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
?>
<h1>Update Investment</h1>
<?php

if(isset($_POST['submit']))
{
		$username = $_REQUEST['username'];
		$query = query_execute_sqli("select * from users where username = '$username' ");
		$num = mysqli_num_rows($query);
		if($num != 0)
		{
			
			
			while($row = mysqli_fetch_array($query))
			{
				$id_user = $row['id_user'];
				
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
				
				query_execute_sqli("update wallet set amount = 0 where id = '$id_user' ");
				query_execute_sqli("update reg_fees_structure set reg_fees = 0 , update_fees = 0 , total_days = 0 where user_id = '$id_user' ");
				
				$date = date('Y-m-d');
				$title_block = "Update Investment";
				$blocked = "update";
				include("../function/logs_messages.php");
				data_logs($id_user,$data_log[17][0],$data_log[17][1],$log_type[10]);
				
				print "Wallet Amount : $wallet_amount <br>
						Investment : $investment All Amount has been changed to 0";	
			}
		}
		else
		{
			print "<font color=\"#FF0000\" size=\"+2\">Please Enter Correct Username !</font>"; 
		}	
}
else
{
	?> 
		<table width="600" border="0">
		<form name="parent" action="index.php?page=update_investment" method="post">
   
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="form_label"><p>User Name</p> </td>
    <td><p><input type="text" name="username" class="form-control"  /></p></td>
  </tr>
  <tr>
    <td align="right" colspan="2"><p align="center"><input type="submit" name="submit" value="Update" class="btn btn-info"   /></p></td>
  </tr>
  </form>
</table>
	<?php
}	