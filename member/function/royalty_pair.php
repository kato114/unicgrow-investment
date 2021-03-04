<?php
include("../config.php");
function total_pair_in_year($id)
{
	$royalty_child = 0;
	$curr_date = date('Y-m-d');
	$start = get_user_date($id);
	$end = strftime("%Y-%m-%d", strtotime("$start +1 year"));
	while($end < $curr_date) 
	{
			$start_date = $start = $end;
			$end = $end_date = strftime("%Y-%m-%d", strtotime("$start +1 year"));
	}
	$q = query_execute_sqli("select * from left_right where user_id = '$id' and date >= '$start' and date < '$end' ");
	$num = mysqli_num_rows($q);
	while($row = mysqli_fetch_array($q))
	{
		$pair = min($row['left_child'],$row['right_child']);
		$royalty_pair = $royalty_pair+$pair;
	}
	$amount = user_royalty_income($royalty_pair);
print $royalty_pair."  ".$amount;
}

function get_user_date($id)
{
	$q = query_execute_sqli("select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($q))
	{
		$user_date = $row['date'];
	}
	return $user_date;
}

function user_royalty_income($pair)
{
	include("setting.php");
	for($i = 0; $i<5; $i++)
	{
		if($pair_royalty[$i] >= $pair)
		{
			if($pair_royalty[$i] == $pair)
			{
				$amount = $royalty_income[$i];
				return $amount;
			}
			else
			{
				$amount = $royalty_income[$i-1];
				return $amount;
			}
		}
	}
	if($pair_royalty[4] < $pair)
	{
		$amount = $royalty_income[4];
		return $amount;
	}	
	
	return $amount;	
}

total_pair_in_year(1);

