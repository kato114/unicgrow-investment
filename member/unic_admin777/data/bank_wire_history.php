<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");

if(isset($_POST['Submit']))
{
	$post_date = $_POST['date'];
	
	$query = query_execute_sqli("select * from paid_unpaid where paid_date = '$post_date' and paid = 1");
	
	$num = mysqli_num_rows($query);
	
	if($num > 0)
	{
		print "<table width=70%>
				<tr>
					<th class=\"message tip\"><p style=\"padding:5px;\">SR</p></th>
				
					<th class=\"message tip\"><p style=\"padding:5px;\">Username</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Amount</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Withdrawal Date</p></th>
					<th class=\"message tip\"><p style=\"padding:5px;\">Accept Date</p></th>
				  </tr>";
		$sr = 1;
		while($row = mysqli_fetch_array($query))
		{
			$username = get_user_name($row['user_id']);
			$amount = $row['amount'];
			$request_date = $row['request_date'];
			$paid_date = $row['paid_date'];
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
						<p style=\"padding:5px;\">$request_date</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$paid_date</p>
					</th>
				  </tr>";
			$sr++;	  
		}
		print "</table>";			
	}		
	else
	{
		print "<br /><font style=\"color:#FF0000\">There Are No Withdrawal</font>";
	
	}
}
else
{
?>
<form method="post" action="index.php?page=bank_wire_history">
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