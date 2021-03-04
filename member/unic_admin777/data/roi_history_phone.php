<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");

if(isset($_POST['Submit']))
{
	$date = $_POST['date'];
	
	$query = query_execute_sqli("select t1.date as inc_date, t1.amount , t2.* from income as t1 inner join users as t2 on t1.user_id = t2.id_user and t1.date = '$date' and t1.type = 2 group by t2.phone_no");
	
	$num = mysqli_num_rows($query);
	
	if($num > 0)
	{
		$que = query_execute_sqli("select sum(amount) from income where date = '$date' and type = 2 ");
		$rows = mysqli_fetch_array($que);
		$total_roi = $rows['sum(amount)'];

		print "<table border=0 width=80%>
					<tr>
						<th class=\"message tip\"><p style=\"padding:4px;\">Total Roi</p></th>
						<th class=\"message tip\"><p style=\"padding:4px;\">$total_roi</p></th>
					</tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				</table>";
						
		print "<table width=80%>
				<tr>
					<th class=\"message tip\"><p style=\"padding:5px;\">SR</p></th>
				
					<th class=\"message tip\"><p style=\"padding:5px;\">Username</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Name</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Phone</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Date</p></th>
				  </tr>";
		$sr = 1;
		while($row = mysqli_fetch_array($query))
		{
			$username = $row['username'];
			$phone = $row['phone_no'];
			$name = $row['f_name'].' '.$row['l_name'];
			$date = date('Y-M-d',strtotime($row['inc_date']));
			print "<tr>
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$sr</p>
					</th>
				
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$username</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$name</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$phone</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$date</p>
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
<form method="post" action="index.php?page=roi_history_phone">
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