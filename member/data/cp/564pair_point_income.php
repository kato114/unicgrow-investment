<?php
include("../config.php");
include("functions.php");

 
function pair_point_income()
{
	include("setting.php");
	print $chk_start_date = date("Y-m-1");
	$chk_end_date = date("Y-m-d");
	$chk_query = query_execute_sqli("select * from income where date >= '$chk_start_date' and date <= '$chk_end_date' and type = '$income_type[3]' ");
	$chk_num = mysqli_num_rows($chk_query);
	if($chk_num == 0)
	{	
		$q = query_execute_sqli("select * from users ");
		$n = mysqli_num_rows($q);
		for($id = 1; $id <= $n; $id++)
		{
			$start_date = date("Y-m-1", strtotime("-1 month") );
			$end_date = date("Y-m-t", strtotime("-1 month") );
			$query = query_execute_sqli("select * from pair_point where user_id = '$id' and date >= '$start_date' and date <= '$end_date' ");
			$num = mysqli_num_rows($query);
			if($num != 0)
			{
				while($row = mysqli_fetch_array($query))
				{
					$date = date("Y-m-d");
					$left_point = $row['left_point'];
					$right_point = $row['right_point'];
					$income = get_pair_point_income($left_point,$right_point);
					if($income > 0)
					{
						query_execute_sqli("insert into income (user_id , amount , date , type ) values ('$id' , '$income' , '$date' , '$income_type[3]') ");
						update_member_wallet($id,$income,$data_log,$log_type);
					}
				}	
			}	
		}
		print "Binary Income Successfully Distributed of this month! ";
	}
	else { print "<font size=5 color=\"#FF0000\">Alert - Binary Income Already Distributed this month ! </font>"; }	
}

pair_point_income();

function get_pair_point_income($left_point,$right_point)
{
	include("setting.php");
	$pair = min($left_point,$right_point);
	$income = $pair*($pair_point_percent/100);
	return $income;
}