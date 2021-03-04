<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");


if(isset($_POST['submit']))
{
	$u_name = $_REQUEST[user_name];
	$q = query_execute_sqli("select * from users where username = '$u_name' ");
	$num = mysqli_num_rows($q);
	if($num == 0)
	{
		echo "<h3>Please Enter right User Name!</h3>"; 
	}
	else
	{
		while($id_row = mysqli_fetch_array($q))
		{
			$id_user = $id_row['id_user'];
		}		
	print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=500>
		<tr><td width=200 class=\"td_title\"><strong>User Name</strong></th>
			<td width=200 class=\"td_title\"><strong>Amount</strong></th>
			<td width=200 class=\"td_title\"><strong>Date</strong></td></tr>";
		
		$query = query_execute_sqli("select * from wallet where id = '$id_user' ");
		$num = mysqli_num_rows($query);
		if($num != 0)
		{
			while($row = mysqli_fetch_array($query))
			{
				$date = $row['date'];
				$amount = $row['amount'];
				print "<tr><td width=200 class=\"td_title\">$u_name</td>
							<td width=200><small>$amount &#36;</small></td>
							<td width=200><small>$date</small></td></tr>";
			}	
			print "</table>";
		}
		else
		{
			print "<tr><td colspan=\"3\" width=400 class=\"td_title\">$j</td></tr></table>";
		}
	}
}

else{ ?> 
<table align="center" border="0" width=450>
<form name="my_form" action="index.php?page=wallet_amount" method="post">
  <tr>
    <td colspan="2" class="td_title" style="font-size:16px; color:#CC0000;"><strong>Wallet Information</strong></td>
  </tr>
  <tr>
    <td class="td_title"><p>Enter Member UserName</p></td>
    <td><p><input type="text" name="user_name" size=3 class="form-control"/></p></td>
  </tr>
  <tr>
    <td align="center" colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
    
  </tr>
  </form>
</table>
<?php  }  ?>

