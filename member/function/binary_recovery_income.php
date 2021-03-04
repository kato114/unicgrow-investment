<?php
//ini_set("display_errors",'on');
//include("../config.php");
/*include("../function/functions.php");*/


function pair_point_income($date)
{
	include("setting.php");
	$done = 0;
	$sys_date = $income_dates = $date; //date("Y-m-d");
	/*$chk_query = query_execute_sqli("select * from income where date = '$income_dates' and type = '$income_type[3]' ");*/
	$chk_num = 0;
	$done = '';
	if($chk_num == 0)
	{	
		$income_date_dist = date("Y-m-d", strtotime($income_dates."-1 day") );
		$income_query = "select user_id from income where type='$income_type[3]' and date='$date' ";
		$sql = "select t1.* from pair_point as t1
						left join reg_fees_structure as t2 
						on t1.user_id = t2.user_id
						where least(left_point,right_point) > 0 and t1.date = '$income_date_dist' and t1.user_id NOT IN($income_query)  group by t1.user_id ";
		$q = query_execute_sqli($sql);
		$n = mysqli_num_rows($q);
		if($n > 0)
		{
			while($rrr = mysqli_fetch_array($q))
			{
				$id = $rrr['user_id'];
				$type = get_type_user($id);
				if($type == 'B')
				{
					$chk_first_inc = query_execute_sqli("select * from income where user_id = '$id' and type = 3");
					$num_firt_inc = mysqli_num_rows($chk_first_inc);
					$one_ratio_2 = 1;
					$deduct_value = 0;
					if($num_firt_inc == 0)
					{
						$one_ratio_2 = check_two_ratio_one_condition($id);
						$deduct_value = 1;
					}
	
					$binary_check = check_user_for_binary_income($id);
					if($binary_check == 1 and $one_ratio_2 == 1)
					{
						$income_date = date("Y-m-d", strtotime($income_dates."-1 day") );
						$sql = "select * from pair_point where user_id = '$id' and date = '$income_date' ";
						$query = query_execute_sqli($sql);
						$num = mysqli_num_rows($query);
						
						if($num != 0)
						{
							
							while($row = mysqli_fetch_array($query))
							
							{
							
								//$date = date("Y-m-d");
								$left_point = $row['left_point'];
								$right_point = $row['right_point'];
								$income = get_pair_point_income($id,$left_point,$right_point,$sys_date,$deduct_value);					
								if($income > 0)
								{
									$s_no++;
									$done = 1;
									$Full_name = get_full_name($id);
									$username = get_user_name($id);
									$phone = get_user_phone($id);
									$tr .= "<tr>
												<td class=\"input-small\">$s_no</td>
												<td class=\"input-small\">
												$username<input type=\"hidden\" name=\"reco_id[]\" value=\"$id\">
												</td>
												<td class=\"input-small\">$Full_name</td>
												<td class=\"input-small\">$phone</td>
												<td class=\"input-small\">
												$income<input type=\"hidden\" name=\"reco_aot[]\" value=\"$income\">
												</td>
												<td  class=\"input-small\">$date</td>
											<tr>";
											
									
								}
							}	
						}
					}	
				}
			}	
		}
		if($done == 1)
		{	print "<form method=\"post\">";
			print "	<table> <tr>
						<td class=\"message tip\">S.No.</td>
						<td class=\"message tip\">Username</td>
						<td class=\"message tip\">Full Name</td>
						<td class=\"message tip\">Phone</td>
						<td class=\"message tip\">Income</td>
						<td class=\"message tip\">Date</td>
					</tr>";
			print $tr;
			
			print "</table>";
			print "
			<input type=\"hidden\" name=\"date\" value=\"$date\">
			<input type=\"submit\" name=\"paid_continue\" value=\"Continue\" class=\"normal-button\"  /></form>";
		}
		else { 	print "<font size=5 color=\"#FF0000\">Alert -Binary Income Already Distributed To All Members ! </font>"; }	
	}
	else { print "<font size=5 color=\"#FF0000\">Alert - Binary Income Already Distributed on today ! </font>"; }	
}

//pair_point_income();

function get_pair_point_income($id,$left_point,$right_point,$date, $deduct_value)
{
	include("setting.php");
	$income = 0;
	
	$pc = 1;
	$max_pair = min($left_point,$right_point);
	do
	{
		$pair_calc = $per_day_multiple_pair*$pc;
		$pc++;
	}
	while($pair_calc <= $max_pair);
	$total_pair = $pair_calc-$per_day_multiple_pair;
	
	$income = $total_pair*($pair_point_percent/100);

	if($deduct_value == 1)
	{
		$income = deduct_user_balance($income, $id);
	}
	
	$sql = "select sum(rfs.update_fees) as tot_inv 
			from reg_fees_structure as rfs where rfs.user_id='$id' 
			group by rfs.user_id
			having tot_inv>0";
	$result = query_execute_sqli($sql);
	$rows = mysqli_fetch_array($result);
	$tot_inv = $rows[0];
	
	$sql = "select * from plan_setting";
	$result = query_execute_sqli($sql);
	while($rr = mysqli_fetch_array($result))
	{
		$ids[] = $rr['id'];
		$amnt[] = $rr['amount'];
	}
	$cnt_id = count($ids);
	for($i = 0; $i <= $cnt_id; $i++)
	{
		if($i == 0)
		{
			if($amnt[$i]>=$tot_inv)
			{
				$package_id = $ids[$i];
				break;
			}
		}
		elseif($i == $cnt_id)
		{	
			if($amnt[$i-1]<=$tot_inv)
			$package_id = $ids[$i-1];
			break;
		}
		else
		{	
			if($amnt[$i-1] <= $tot_inv and $amnt[$i] > $tot_inv)
			{
				$package_id = $ids[$i-1];
				break;
			}
		}
	}
	/*print $id."&nbsp;&nbsp;".$tot_inv."&nbsp;&nbsp;".$package_id."<br>";*/
	

	if($package_id == 1){
	$caping = $forex_caping_plan[1];
	}
	elseif($package_id == 2){
	$caping = $forex_caping_plan[2];
	}
	elseif($package_id == 3){
	$caping = $forex_caping_plan[3];
	}
	elseif($package_id == 4){
	$caping = $forex_caping_plan[4];
	}
	else
	{
	$caping = $forex_caping_plan[4];
	}
	if($caping < $income)
		return $caping;
	else
		return $income;
}

function check_user_for_binary_income($id)
{
	$chk_query = query_execute_sqli("select SUM(update_fees) from reg_fees_structure where user_id = '$id' ");
	$chk_num = mysqli_num_rows($chk_query);
	if($chk_num > 0)
	{	
		while($row = mysqli_fetch_array($chk_query))
		{
			$total_invest_ment = $row[0];
			if($total_invest_ment >= 10)
				return 1;
			else
				return 0;	
		}
	}
	else
		return 0;		
}


function check_two_ratio_one_condition($id)
{
	include("setting.php");
	
	$query = query_execute_sqli("select sum(left_point) as lp, sum(right_point) as rp from pair_point where user_id = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$lp = $row['lp'];
		$rp = $row['rp'];
		
		$max = max($lp , $rp);
		$min_p = min($lp , $rp);
		$max_p = $max-100;
		
 		$p_total_1 = $max_p/$per_day_multiple_pair;
		$p_total_2 = $min_p/$per_day_multiple_pair;	
		
		if($p_total_1 >= 2 and $p_total_2 >= 1 or $p_total_2 >= 2 and $p_total_1 >= 1)
		{
			return 1;
		}
		else{
			return 0;
		}
	}
}

function deduct_user_balance($total_inc, $id_user)
{
	include 'setting.php';
	
	if($total_inc > 0)
	{
		$sql = "select deduct_amount from wallet where id = '$id_user' ";
		$query = query_execute_sqli($sql);
		$row = mysqli_fetch_array($query);
		$wallet = $row[0];
		
		if($wallet >= $binary_deduct_amount)
		{
			$inc = $total_inc;
		}
		else
		{
			if($total_inc >= $binary_deduct_amount)
			{
				if($wallet == 0)
				{
					query_execute_sqli("update wallet set deduct_amount = '$binary_deduct_amount' where id = '$id_user' ");
					$inc = $total_inc-$binary_deduct_amount;
				}
				elseif($wallet != 0)
				{
					$totl_left = $binary_deduct_amount-$wallet;
					query_execute_sqli("update wallet set deduct_amount = deduct_amount+'$totl_left' where id = '$id_user' ");
					$inc = $total_inc-$totl_left;
				}
			}	
			else
			{
				if($wallet == 0)
				{
					query_execute_sqli("update wallet set deduct_amount = '$total_inc' where id = '$id_user' ");
					$inc = 0;
				}
				elseif($wallet != 0)
				{
					$totl_left = $binary_deduct_amount-$wallet;
					if($totl_left >= $total_inc){
						$inc_up = $total_inc;
						$inc = 0;
					}							
					else{
						$inc_up = $total_inc-$totl_left;
						$inc = $total_inc-$totl_left;
					}	
					
					query_execute_sqli("update wallet set deduct_amount = deduct_amount+'$inc_up' where id = '$id_user' ");
				}
				
			}	 
		}	
	}
	else
		$inc = 0;	
	
	return $inc;	
}

function exist_income($id,$date,$type)
{
	$sql = "select * from income where user_id='$id' and date='$date' and type='$type' ";
	/*$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	return $num;*/
}