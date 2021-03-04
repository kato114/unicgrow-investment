<?php
include("../config.php");
include("../function/functions.php");
$income_dates = '2014-08-01';
do
{
	$income_dates = date("Y-m-d", strtotime($income_dates."+1 day") );
	pair_point_income($income_dates);
	
}while($income_dates <= $systems_date);


function pair_point_income($date)
{
	include("setting.php");
	$done = 0;
	$income_dates = $date; //date("Y-m-d");
	$chk_query = query_execute_sqli("select * from income where date = '$income_dates' and type = $income_type[3] ");
	$chk_num = 0; //mysqli_num_rows($chk_query);
	if($chk_num == 0)
	{	
		$q = query_execute_sqli("select * from pair_point group by user_id ");
		$n = mysqli_num_rows($q);
		if($n > 0)
		{
			while($rrr = mysqli_fetch_array($q))
			{
				print $id = $rrr['user_id'];
				print " ";
				$type = get_type_user($id);
				if($type == 'B' or $type == 'C')
				{
					
					print $chk_bin = check_transfer_condition($id);	
					if($chk_bin == 1)
					{
						$income_date = date("Y-m-d", strtotime($income_dates."-1 day") );
						$query = query_execute_sqli("select * from pair_point where user_id = '$id' and date = '$income_date' ");
					print	$num = mysqli_num_rows($query);
						if($num > 0)
						{
							$done = 1;
							while($row = mysqli_fetch_array($query))
							{
								//$date = date("Y-m-d");
								$left_point = $row['left_point'];
								$right_point = $row['right_point'];
								$total_income = get_pair_point_income($left_point,$right_point);
								if($total_income > 0)
								{
									$qrrr = query_execute_sqli("select * from income where user_id = '$id' and date = '$date' and type = '$income_type[3]' ");
									$numm = mysqli_num_rows($qrrr);
									if($numm > 0)
									{
										while($rrrrr = mysqli_fetch_array($qrrr))
										{
											$paid_income = $rrrrr['amount'];
											print $left_income = $total_income-$paid_income;
											print " ";
											if($left_income > 0)
											{
												query_execute_sqli("update income set amount = '$total_income' where user_id = '$id' and date = '$date' and type = '$income_type[3]' ");
												update_member_wallet($id,$left_income,$income_type[3],$date);
											}
										}
									}	
									else
									{
										query_execute_sqli("insert into income (user_id , amount , date , type) values ('$id' , '$total_income' , '$date' , '$income_type[3]' ) ");
										update_member_wallet($id,$total_income,$income_type[3],$date);
									}	
								}	
							}
						}	
					}
					
				}print "<br>";	
			}	
		}
		if($done == 1)
			print "<font size=5 color=\"#004080\">Binary Income Successfully Distributed on Today ! </font><br>";
		else { 	print "<font size=5 color=\"#FF0000\">Alert - There Are No Binary Pair Today ! </font><br>"; }	
	}
	else { print "<font size=5 color=\"#FF0000\">Alert - Binary Income Already Distributed Today ! </font><br>"; }	
}

//pair_point_income();

function get_pair_point_income($left_point,$right_point)
{
	include("setting.php");
	
	$pc = 1;
	$max_pair = min($left_point,$right_point);
	if($max_pair >= $per_day_multiple_binary_pair)
	{
		$income = $max_pair*($pair_point_percent/100);
		if($income > $per_day_max_binary_inc_db)
			return $per_day_max_binary_inc_db;
		else
			return $income;	
	}	
	else
		return 0;
}
//print "<br>".check_transfer_condition(743);
function check_transfer_condition($id)
{
	$q = query_execute_sqli("select * from income where user_id = '$id' ");
	$num = mysqli_num_rows($q);
	if($num > 0)
	{
		$queryl = query_execute_sqli("select * from users where parent_id = '$id' and position = 0 ");
		$numl = mysqli_num_rows($queryl);
		if($numl == 0)
		{
			return 0;	
		}
		else
		{
			while($rowl = mysqli_fetch_array($queryl))
			{
				$left = $rowl['id_user'];
				$chk_left = child_investment_chk_for_binary($id,$left);
			}
			$queryr = query_execute_sqli("select * from users where parent_id = '$id' and position = 1 ");
			$numr = mysqli_num_rows($queryr);
			if($numr == 0)
			{
				return 0;	
			}
			else
			{
				while($rowr = mysqli_fetch_array($queryr))
				{
					$right = $rowr['id_user'];
					$chk_right = child_investment_chk_for_binary($id,$right);
				}
				if($chk_left == 1 and $chk_right == 1)
					return 1;
				else
					return 0;	
			}		
		}				
	}
	else
		return 1;	
}


function child_investment_chk_for_binary($real_p,$id)
{
	$child[0] = $id;
	
	$resultss = query_execute_sqli("select * from users where id_user = '$id' ");
	while($rows = mysqli_fetch_array($resultss))
	{
		$real_parent = $rows['real_parent'];
		if($real_parent == $real_p)
		{
			$q = query_execute_sqli("select * from income where user_id = '$id' ");
			$chk_inv = mysqli_num_rows($q);
			if($chk_inv > 0)
				return 1;
		}	
		
		$count = 1;
		for($i = 0; $i < $count; $i++)
		{
			$result = query_execute_sqli("select * from users where parent_id = '$child[$i]' ");
			$num = mysqli_num_rows($result);
			if($num > 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$child[] = $user_idss = $row['id_user'];
					$real_parent = $row['real_parent'];
					if($real_parent == $real_p)
					{
						$qq = query_execute_sqli("select * from reg_fees_structure where user_id = '$user_idss' ");
						$chk_invq = mysqli_num_rows($qq);
						if($chk_invq > 0)
						{
							$i = $count+100;
							return 1;
							break;
						}	
					}	
				}
			}
			$count = count($child);
		}
		return 0;
	}		
}			