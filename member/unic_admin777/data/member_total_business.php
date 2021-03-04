<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");

if(isset($_POST['Submit']))
{
	$user_id = $_POST['username'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	//$user_id = get_new_user_id($username);
	
	$sql = "SELECT t2.date AS reg_date, t1.username, t2.update_fees, t1.position
			FROM users AS t1
			INNER JOIN reg_fees_structure AS t2 ON t1.id_user = t2.user_id
			AND t1.real_parent = (
			SELECT t3.id_user
			FROM users AS t3
			WHERE t3.username = '$user_id' )
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date'";
	
	$query = query_execute_sqli($sql);
	
	$num = mysqli_num_rows($query);
	
	if($num > 0)
	{
		print "<table border=0 width=70%>
					<tr>
						<th class=\"message tip\"><p style=\"padding:4px;\">Total Left</p></th>
						<th class=\"message tip\"><p style=\"padding:4px;\">Total Right</p></th>
					</tr>
					
					<tr>
						<th class=\"input-medium\"><p style=\"padding:4px;\">".member_total_business_l_r(0,$user_id , $start_date, $end_date)."</p></th>
						<th class=\"input-medium\"><p style=\"padding:4px;\">".member_total_business_l_r(1,$user_id , $start_date, $end_date)."</p></th>
					</tr>
					<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				</table>";
						
		print "<table width=70%>
				<tr>
					<th class=\"message tip\" colspan=3><p style=\"padding:4px;\">Left</p></th>
					<th class=\"message tip\" colspan=3><p style=\"padding:4px;\">Right</p></th>
				</tr>
				
				<tr>
					<th class=\"message tip\"><p style=\"padding:5px;\">Username</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Amount</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Date</p></th>
					
				
					<th class=\"message tip\"><p style=\"padding:5px;\">Username</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Amount</p></th>
					
					<th class=\"message tip\"><p style=\"padding:5px;\">Date</p></th>
				  </tr>";
		$sr = 1;
		while($row = mysqli_fetch_array($query))
		{
			$position = $row['position'];
			if($position == 0)
			{
				$amount_left = $row['update_fees'];
				$username_left = $row['username'];
				$date_left = $row['reg_date'];
				$date_right = '';
				$username_right = '';
				$amount_right = '';
			}
			else
			{
				$amount_right = $row['update_fees'];
				$username_right = $row['username'];
				$date_right = $row['reg_date'];
				$username_left = '';
				$amount_left = '';
				$date_left = '';
			}
			
			print "<tr>
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$username_left</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$amount_left</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$date_left</p>
					</th>
					
				
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$username_right</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$amount_right</p>
					</th>
					
					<th class=\"input-medium\">
						<p style=\"padding:5px;\">$date_right</p>
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
<form method="post" action="index.php?page=member_total_business">
	<table cellpadding="0" cellspacing="0" width="90%" border="0">
		<tr>
			<td colspan="6">&nbsp;</td>
	  </tr>
	  <tr height="120px">
			<th><p style="padding:0px">Username</p></th>
			<th>
				<input type="text" name="username" placeholder="Insert Username" class="input-small">
			</th>
			
			<th><p style="padding:0px">Start Date</p></th>
			<th>
				<input type="text" name="start_date" placeholder="Start Date" class="input-small flexy_datepicker_input">
			</th>
			
			<th><p style="padding:0px">End Date</p></th>
			<th>
				<input type="text" name="end_date" placeholder="End Date" class="input-small flexy_datepicker_input">
			</th>
		</tr>
		<tr>
			<th colspan="6" align="right">
				<input type="submit" value="Submit" name="Submit" class="button3" style="cursor:pointer">		
			</th>
		</tr>
	</table>
</form>	
<?php
}

function member_total_business_l_r($pos, $user_id , $start_date, $end_date)
{
	$sql = "SELECT sum(t2.update_fees) as totl_business
			FROM users AS t1
			INNER JOIN reg_fees_structure AS t2 ON t1.id_user = t2.user_id
			AND t1.real_parent = (
			SELECT t3.id_user
			FROM users AS t3
			WHERE t3.username = '$user_id' )
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date'
			AND t1.position = $pos";
	$query = query_execute_sqli($sql);		
	while($row = mysqli_fetch_array($query))
	{
		$totl_business = $row['totl_business'];
	}
	return $totl_business;
}

?>