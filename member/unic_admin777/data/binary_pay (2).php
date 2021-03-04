<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");

if(isset($_POST['Submit']))
{
	$post_date = $_POST['date'];
	
	$query = query_execute_sqli("select * from income where date = '$post_date' and type = 3");
	
	$num = mysqli_num_rows($query);
	
	if($num > 0)
	{
		$que = query_execute_sqli("select sum(amount) from income where date = '$post_date' and type = 3 ");
		$rows = mysqli_fetch_array($que);
		$total_commit = $rows[0];

		print "<table border=0 width=70%>
					<tr>
						<th class=\"message tip\"><p style=\"padding:4px;\">Total Binary</p></th>
						<th class=\"message tip\"><p style=\"padding:4px;\">$total_commit</p></th>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				</table>";
						
		print "<table width=70%>
				<tr>
					<th class=\"message tip\">
						<p style=\"padding:5px;\">SR</p>
					</th>
				
					<th class=\"message tip\">
						<p style=\"padding:5px;\">Username</p>
					</th>
					
					<th class=\"message tip\">
						<p style=\"padding:5px;\">Amount</p>
					</th>
				  </tr>";
		$sr = 1;
		while($row = mysqli_fetch_array($query))
		{
			$username = get_user_name($row['user_id']);
			$amount = $row['amount'];
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
				  </tr>";
			$sr++;	  
		}
		print "</table>";			
	}		
	else
	{
		print "<font style=\"color:#FF0000\">There Are No Binary Income</font>";
	
	}
}
else
{
?>
<form method="post" action="index.php?page=binary_history">
	<table cellpadding="0" cellspacing="0" width="50%">
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
	  </tr>
	  <tr height="120px">
			<th><p style="padding:0px">Date</p></th>
			<th>
	
					<input type="text" name="date" placeholder="Insert Date" class="input-medium flexy_datepicker_input">
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