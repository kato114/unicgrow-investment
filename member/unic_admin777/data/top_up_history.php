<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");

if(isset($_POST['Submit']))
{
	$username = $_POST['username'];
	$user_id = get_new_user_id($username);
	
  if($user_id > 0)
  {
	$query = query_execute_sqli("select * from reg_fees_structure where user_id = '$user_id' ");
	
	$num = mysqli_num_rows($query);
	
	if($num > 0)
	{
		$que = query_execute_sqli("select sum(update_fees) from reg_fees_structure where user_id = '$user_id' ");
		$rows = mysqli_fetch_array($que);
		$total_commit = $rows['sum(update_fees)'];

		print "<table border=0 width=70%>
					<tr>
						<th class=\"message tip\"><p style=\"padding:4px;\">Total Commitment</p></th>
						<th class=\"message tip\"><p style=\"padding:4px;\">$total_commit</p></th>
					</tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				</table>";
						
		print "<table width=70%>
				<tr>
					<th class=\"message tip\"><p style=\"padding:5px;\">SR</p></th>
				
					<th class=\"message tip\"><p style=\"padding:5px;\">Username</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Amount</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Date</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">By User</p></th>
				  </tr>";
		$sr = 1;
		while($row = mysqli_fetch_array($query))
		{
			$username = get_user_name($row['user_id']);
			$amount = $row['update_fees'];
			$date = $row['date'];
			$by_user = $row['by_user'];

			if($by_user == 0)
				$by_user = "No Information";				
			else
				$by_user = get_user_name($by_user);
				
			print "<tr>
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$sr</p>
					</th>
				
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$username</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$amount</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$date</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$by_user</p>
					</th>
				  </tr>";
			$sr++;	  
		}
		print "</table>";			
	}		
	else
	{
		print "<br /><font style=\"color:#FF0000\">There Are No Top Up</font>";
	
	}
  }
  else
  {
  	print "<br /><font style=\"color:#FF0000\">Please Enter Correct Username</font>";
  }	
}
else
{
?>
<form method="post" action="index.php?page=top_up_history">
	<table cellpadding="0" cellspacing="0" width="50%">
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
	  </tr>
	  <tr height="120px">
			<th><p style="padding:0px">Date</p></th>
			<th>
	
					<input type="text" name="username" placeholder="Insert Username" class="form-control">
			</th>
		</tr>
		<tr>
			<th colspan="2" align="center">
				<input type="submit" value="Submit" name="Submit" class="button3" style="cursor:pointer">		
			</th>
		</tr>
	</table>
</form>	
<?php
}
?>