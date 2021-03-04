<?php
include('../../security_web_validation.php');
?>
<style>
	.input-medium {
    	width: auto;
}
</style>
<?php
include("../function/functions.php");
if(isset($_POST['Submit']))
{
	$start_date = $_REQUEST['start_date'];
	$end_date = $_REQUEST['end_date'];
	$member_business_level = $_REQUEST['business'];
	
	$sql = "select t1.username,t2.user_id ,t1.f_name,t1.l_name
			from users as t1
			inner join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			where t2.user_id > 0 
			group by t1.id_user,t2.user_id 
			order by t2.user_id ";
	/*$sql = "select t1.username,t2.user_id 
			from users as t1
			,reg_fees_structure as t2
			where t2.user_id > 0 and t1.id_user = t2.user_id 
			group by t1.id_user,t2.user_id 
			order by t2.user_id ";*/
	$query = query_execute_sqli($sql);
	$i = 0;
	$error = 0;
	print "<table width=85% style=\"padding-left:0px;\">
			<tr><th colspan=5>&nbsp;</th></tr>
			<tr>
				<th class=\"message tip\"><p style=\"padding:5px;\">S.No.</p></th>
				
				<th class=\"message tip\"><p style=\"padding:5px;\">Username</p></th>
				
				<th class=\"message tip\"><p style=\"padding:5px;\">Full Name</p></th>
							
				<th class=\"message tip\"><p style=\"padding:5px;\">Left Business</p></th>
				
				<th class=\"message tip\"><p style=\"padding:5px;\">Right Business</p></th>
				
				<th class=\"message tip\"><p style=\"padding:5px;\">Start Date</p></th>
				
				<th class=\"message tip\"><p style=\"padding:5px;\">End Date</p></th>
			
			 </tr>";
	 $s_no = 1;							
	while($row = mysqli_fetch_array($query))
	{	
		$left_business = 0;
		$right_business = 0;
		$id = $row['user_id'];
		$full_name = $row['f_name'].' '.$row['l_name'];
		
		if($id > 0)
		{
				$business = give_all_children($id,$start_date,$end_date);
				$left_business = $business[0];
				$right_business = $business[1];
			
				if($left_business >=$member_business_level and $right_business >=$member_business_level)
				{	
					$username = $row['username'];
					$error = 1;
					print "<tr>
								<th class=\"input-medium\">
									<p style=\"padding:5px;\">$s_no</p>
								</th>
					
								<th class=\"input-medium\">
									<p style=\"padding:5px;\">$username</p>
								</th>
								
								<th class=\"input-medium\">
									<p style=\"padding:5px;\">$full_name</p>
								</th>
							
								<th class=\"input-medium\">
									<p style=\"padding:5px;\">$left_business</p>
								</th>
							
								<th class=\"input-medium\">
									<p style=\"padding:5px;\">$right_business</p>
								</th>
								
								<th class=\"input-medium\">
									<p style=\"padding:5px;\">$start_date</p>
								</th>
								
								<th class=\"input-medium\">
									<p style=\"padding:5px;\">$end_date</p>
								</th>
							</tr>";
							$s_no++;
				}
		}
		
	}
	if($error == 0)
	{
			print "<tr>
								<th class=\"input-medium\" colspan=\"6\">
									<p style=\"padding:5px;\">
										<font style=\"color:#FF0000\">There Are No Top Up</font>
									</p>
								</th>";
	}
	print "</table>";
	
	
	
}
else
{
?>
<form method="post" action="">
	<table cellpadding="0" cellspacing="0" width="75%" border="0">
		<tr>
			<td colspan="6">&nbsp;</td>
	  </tr>
	  <tr height="120px">
	  		<th><p style="padding:0px">Business Amount</p></th>
			<th>
				<input type="text" name="business" placeholder="Business Amount" class="input-small">
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
	
	$sql = "select t1.id_user,sum(t2.update_fees) as totl_business
			from users as t1
			left join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			where t1.parent_id = '$id' and t1.position = 0 
			group by t2.user_id,t1.id_user";
	/*$sql = "select t1.id_user,sum(t2.update_fees) as totl_business
			from users as t1
			, reg_fees_structure as t2
			where t1.parent_id = '$id' and t1.position = 0 and
			 t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			group by t2.user_id,t1.id_user";*/
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num == 0)
	{
		$children[0] = 0;
	}
	else
	{	$i = 0;
		while($row = mysqli_fetch_array($query))
		{
			$left = $row['id_user'];
			$amount = $row['totl_business'];
			$children[0] = get_all_child($left,$start_date,$end_date,$amount);
		}
	}
	
	// Right business
	$sql = "select t1.id_user,sum(t2.update_fees) as totl_business
			from users as t1
			left join reg_fees_structure as t2
			on t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			where t1.parent_id = '$id' and t1.position = 1 
			group by t2.user_id,t1.id_user";
	/*$sql = "select t1.id_user,sum(t2.update_fees) as totl_business
			from users as t1
			, reg_fees_structure as t2
			where t1.parent_id = '$id' and t1.position = 1 and t1.id_user = t2.user_id
			AND t2.date >= '$start_date'
			AND t2.date <= '$end_date' 
			group by t2.user_id,t1.id_user";*/
	$query = query_execute_sqli($sql);
	$num1 = mysqli_num_rows($query);
	if($num1 == 0)
	{
		$children[0] = 0;
	}
	else
	{	
		while($row = mysqli_fetch_array($query))
		{
			$right = $row['id_user'];
			$amount = $row['totl_business'];
			$children[1] = get_all_child($right,$start_date,$end_date,$amount);
		}
	}
	return $children;
}


function get_all_child($id,$start_date,$end_date,$amount)  // get all child in id network
{
 	$child[0] = $id;
	$block = 1;
	$count = count($child);
	for($i = 0; $i <$count; $i++)
	{
		$idds = $child[$i];
	
	$sql = "select t1.id_user,t1.type,t2.user_id,
				sum(t2.update_fees) as totl_business
				from users as t1
				left join reg_fees_structure as t2
				on t1.id_user = t2.user_id
				AND t2.date >= '$start_date'
				AND t2.date <= '$end_date' 
				where t1.parent_id = '$idds' 
				group by t2.user_id,t1.id_user";
	/*$sql = "select t1.id_user,t1.type,t2.user_id,
				sum(t2.update_fees) as totl_business
				from users as t1
				, reg_fees_structure as t2
				where t1.parent_id = '$idds' and t1.id_user = t2.user_id
				AND t2.date >= '$start_date'
				AND t2.date <= '$end_date' 
				group by t2.user_id,t1.id_user";*/
	
			$result = query_execute_sqli($sql);
			$num = mysqli_num_rows($result);
			if($num != 0)
			{
				while($row = mysqli_fetch_array($result))
				{ 						
					$child[$block] = $row['id_user'];
					$amount = $amount + $row['totl_business'];
					$block++;
				}
				$count = count($child);
				
			}
	}
	
	return $amount;
}
?>