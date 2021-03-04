<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");

if(isset($_POST['Submit']))
{
	$date = $_POST['date'];
	
	$query = query_execute_sqli("select * from income where date = '$date' and type = 2 ");
	
	$num = mysqli_num_rows($query);
	
	if($num > 0)
	{
		$que = query_execute_sqli("select sum(amount) from income where date = '$date' and type = 2 ");
		$rows = mysqli_fetch_array($que);
		$total_roi = $rows['sum(amount)'];

		print "<table border=0 width=70%>
					<tr>
						<th class=\"message tip\"><p style=\"padding:4px;\">Total Roi</p></th>
						<th class=\"message tip\"><p style=\"padding:4px;\">$total_roi</p></th>
					</tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				</table>";
						
		print "<table width=70%>
				<tr>
					<th class=\"message tip\"><p style=\"padding:5px;\">SR</p></th>
				
					<th class=\"message tip\"><p style=\"padding:5px;\">Username</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Amount</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Date</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Phone</p></th>
				  </tr>";
		$sr = 1;
		while($row = mysqli_fetch_array($query))
		{
			$username = get_user_name($row['user_id']);
			$phone_no = get_user_phone($row['user_id']);
			$amount = $row['amount'];
			$date = $row['date'];
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
						<p style=\"padding:5px;\">$phone_no</p>
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
?>
<form method="post" action="index.php?page=roi_history">
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