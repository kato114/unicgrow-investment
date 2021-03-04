<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
if(isset($_REQUEST['Submit']))
{
	$start_date = $_REQUEST['start_date'];
	$end_date = $_REQUEST['end_date'];
	$username = $_REQUEST['member'];
	$member_business_level = 0;
	
	$sql = "select t1.username,t1.id_user,t2.user_id 
				from users as t1 
				left join reg_fees_structure as t2 
				on t1.id_user = t2.user_id
				where t1.username = '$username' 
				group by t1.id_user,t2.user_id 
				";
	
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		$i = 0;
		$error = 0;
							
		while($row = mysqli_fetch_array($query))
		{	
			$left_business = 0;
			$right_business = 0;
			$id = $row['id_user'];
			
			if($id > 0 and $id != NULL)
			{
					$business = give_all_children($id,$start_date,$end_date);
					$left_business = $business[0];
					$right_business = $business[1];
					$left_cnt = count($left_business);
					$right_cnt = count($right_business);
					$tot_left_business = $left_business[0][4];
					$tot_right_business = $right_business[0][4];
					print "<table width=100% style=\"padding-left:0px;\">
					 <tr>
						<th class=\"message tip\" colspan=2><p style=\"padding:5px;\">Start Date</p></th>
						<th class=\"message tip\" colspan=2><p style=\"padding:5px;\">$start_date</p></th>
						<th class=\"message tip\" colspan=2><p style=\"padding:5px;\">End Date</p></th>
						<th class=\"message tip\" colspan=2><p style=\"padding:5px;\">$end_date</p></th>
									</tr>
					<tr><th colspan=8>&nbsp;</th></tr>
					
					<tr>
						<th class=\"message tip\" colspan=2>
							<p style=\"padding:5px;\">Total Left Business</p>
						</th>
						<th class=\"message tip\" colspan=2>
							<p style=\"padding:5px;\">$tot_left_business</p>
						</th>
						<th class=\"message tip\" colspan=2>
							<p style=\"padding:5px;\">Total Right Business</p>
						</th>
						<th class=\"message tip\" colspan=2>
							<p style=\"padding:5px;\">$tot_right_business</p>
						</th>
					 </tr>
					
					<tr>";
		 print "<tr>";		
					if($left_cnt >0)
					{	
						print "<td colspan=\"4\" valign=\"top\">
								<table width=100% style=\"padding-left:0px;\">
								
									<th class=\"message tip\"><p style=\"padding:5px;\">Username</p></th>
									<th class=\"message tip\"><p style=\"padding:5px;\">Business</p></th>												
									<th class=\"message tip\"><p style=\"padding:5px;\">Topup Date</p></th>
								 </tr>";
						$tot_business = 0;
						for($jj = 0; $jj<$left_cnt; $jj++)
						{
						$username = $left_business[$jj][0];
						$business = $left_business[$jj][1];
						$topup_date = $left_business[$jj][2];
						$li = $left_business[$jj][3];
						$error = 1;
							if($business != '' or $business != 0)
							{
								print "<tr>
											<th class=\"input-medium\">
												<p style=\"padding:5px;\">$username&nbsp;$li</p>
											</th>
										
											<th class=\"input-medium\">
												<p style=\"padding:5px;\">$business</p>
											</th>
										
											<th class=\"input-medium\">
												<p style=\"padding:5px;\">$topup_date</p>
											</th>
										</tr>";
									//	$tot_business = $tot_business+$business;
							}
						}
						/*print "
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td colspan=\"2\" class=\"input-medium\">Total Left Business</td>
									<td colspan=\"2\" class=\"input-medium\">$tot_business</td>
								<tr>";*/
						print "</table></td>";
					}
					else
					{
				
					}
					if($right_cnt >0)
					{	
						print "<td colspan=\"4\" valign=\"top\">
									<table width=100% style=\"padding-left:0px;\">
								<tr>
									<th class=\"message tip\"><p style=\"padding:5px;\">Username</p></th>
									<th class=\"message tip\"><p style=\"padding:5px;\">Business</p></th>												
									<th class=\"message tip\"><p style=\"padding:5px;\">Topup Date</p></th>
								 </tr>";
						$tot_business = 0;
						for($jj = 0; $jj<$right_cnt; $jj++)
						{
						$username = $right_business[$jj][0];
						$business = $right_business[$jj][1];
						$topup_date = $right_business[$jj][2];
						$ri = $right_business[$jj][3];
						$error = 1;
							if($business != '' or $business != 0)
							{
								print "<tr>
											<th class=\"input-medium\">
												<p style=\"padding:5px;\">$username &nbsp;$ri</p>
											</th>
										
											<th class=\"input-medium\">
												<p style=\"padding:5px;\">$business</p>
											</th>
										
											<th class=\"input-medium\">
												<p style=\"padding:5px;\">$topup_date</p>
											</th>
										</tr>";
										$tot_business = $tot_business+$business;
							}
						}
						/*print "
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td colspan=\"1\" class=\"input-medium\">Total Right Business</td>
									<td colspan=\"2\" class=\"input-medium\">$tot_business</td>
								<tr>";*/
							print "</table></td>";
					}
					
			}
			
		}
		 print "</tr></table>";
		if($error == 0)
		{
				print "<tr>
									<th class=\"input-medium\" colspan=\"6\">
										<p style=\"padding:5px;\">
											<font style=\"color:#FF0000\">There Are No Top Up</font>
										</p>
									</th>";
		}
		//print "</table>";
	}
	else
	{
		$_SESSION['error_member'] = "<p style=\"padding:5px;\">
			<font style=\"color:#FF0000\">Please Fill Correct Member</font>
		</p>";
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=member_business\"";
		echo "</script>";
	}
}
else
{
print $_SESSION['error_member'];
unset($_SESSION['error_member']);
?>
<form method="post" action="">
	<table cellpadding="0" cellspacing="0" width="75%" border="0">
		<tr>
			<td colspan="6">&nbsp;</td>
	  </tr>
	  <tr height="120px">
	  		<th><p style="padding:0px">Member</p></th>
			<th>
				<input type="text" name="member" placeholder="Member" class="input-small">
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
			<th colspan="6" align="right" style="padding-right:20px;">
				<input type="submit" value="Submit" name="Submit" class="button3" style="cursor:pointer">		
			</th>
		</tr>
	</table>
</form>	
<?php
}

function give_all_children($id,$start_date,$end_date)  //give all children
{
	// Left business
	
	$sql = "select t1.id_user
			from users as t1
			left join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			where t1.parent_id = '$id' and t1.position = 0 
			group by t2.user_id,t1.id_user";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num == 0)
	{
		$children[0] = 0;
	}
	else
	{	
		while($row = mysqli_fetch_array($query))
		{
			$left = $row['id_user'];
			$children[0] = get_all_child($left,$start_date,$end_date);
		}
	}
	
	// Right business
	$sql = "select t1.id_user
			from users as t1
			left join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			where t1.parent_id = '$id' and t1.position = 1 
			group by t2.user_id,t1.id_user";
	$query = query_execute_sqli($sql);
	$num1 = mysqli_num_rows($query);
	if($num1 == 0)
	{
		$children[1] = 0;
	}
	else
	{	
		while($row = mysqli_fetch_array($query))
		{
			$right = $row['id_user'];
			$children[1] = get_all_child($right,$start_date,$end_date);
		}
	}
	return $children;
}


function get_all_child($id,$start_date,$end_date)  // get all child in id network
{
 	/*$child[0][0] = get_user_name($id);
	$child[0][1] = $amount;
	$child[0][2] = $topup_date;*/
	$tot = 0;
	$sql = "select t1.username,t1.id_user,t2.user_id,
			t2.update_fees as business,t2.date as topup_date
			from users as t1
			inner join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date'
			where t1.id_user = '$id'";
	$quer = query_execute_sqli($sql);
	$num = mysqli_num_rows($quer);
	$block = 0;
	if($num > 0){
		while($rr = mysqli_fetch_array($quer))
		{
			$child[$block][0] = get_user_name($rr['id_user']);
			$child[$block][1] = $rr['business'];
			$child[$block][2] = $rr['topup_date'];
			$child[$block][3] = $rr['id_user'];
			$tot = $tot + $child[$block][1];
			$block++;
		}
	}
	else
	{
		$child[$block][0] = get_user_name($id);
		$child[$block][1] = 0;
		$child[$block][2] = '';
		$child[$block][3] = $id;
		$tot = $tot + $child[$block][1];
		$block = 1;
	}
	$temp_child[0] = $id;
	$count = count($temp_child);
	for($i = 0; $i <$count; $i++)
	{
		$idds = $temp_child[$i];
		/*$sql = "select t1.id_user,t1.username,t2.user_id,
			sum(t2.update_fees) as business,t2.date as topup_date
			from users as t1
			left join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date'
			where t1.parent_id = '$idds'
			group by t2.user_id,t1.id_user";*/
			
		$sql = "select t1.id_user,t1.username,t2.user_id,
				sum(t2.update_fees) as business,t2.date as topup_date
				from users as t1
				left join reg_fees_structure as t2
				on t1.id_user = t2.user_id
				AND t2.date >= '$start_date'
				AND t2.date <= '$end_date' 
				where t1.parent_id = '$idds' 
				group by t2.user_id,t1.id_user";
	
			$result = query_execute_sqli($sql);
			$num = mysqli_num_rows($result);
			if($num != 0)
			{
				while($row = mysqli_fetch_array($result))
				{ 						
					$child[$block][0] = $row['username'];
					$child[$block][1] = $row['business'];
					$child[$block][2] = $row['topup_date'];
					$child[$block][3] = $row['id_user'];
					$tot = $tot + $child[$block][1];
					$temp_child[$block] = $row['id_user'];
					
				//print	$temp_child[$block]."&nbsp;&nbsp;".$child[$block][1];
				//	print "<br>";
					$block++;
				}
				
				$count = count($temp_child);
			}
	}
	$child[0][4] = $tot;
	//print $count;
	return $child;
}

?>