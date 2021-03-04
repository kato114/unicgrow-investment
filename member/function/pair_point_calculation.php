<?php
/*$db_host = "localhost";
$db_username = "mahendra";
$db_password = "mahendra123";
$db = "fxnet_business_neeraj";
$con=mysqli_connect($db_host,$db_username,$db_password);
mysqli_select_db($db,$con);
$i = 0;
$downline = array();
$id_chk = query_execute_sqli("select * from reg_fees_structure where date = '2014-09-11'");
while($row = mysqli_fetch_array($id_chk))
{
	$id_m = $row['user_id'];
	$date_m = $row['date'];
	pair_point_calculation($id_m,$date_m);
	
}*/

//include("../config.php");
/*$query = query_execute_sqli("select * from reg_fees_structure where date > '2014-08-17' ");
	while($row = mysqli_fetch_array($query))
	{
			$user_id = $row['user_id'];
			$date = $row['date'];
		pair_point_calculation($user_id,$date);
		print $user_id;
	}
	
*/
//pair_point_calculation(164,'2014-08-11');



function pair_point_calculation($id,$date)
{
	$parents = get_all_parent($id);
	$count = count($parents);
	if($count >1)
	{
		for($i = 1; $i < $count-1; $i++)
		{
			$parents_id = $parents[$i];
			$user_info = all_child($parents_id,$date);	// $child[0][1]  $child[1][1]
			$left_point = $user_info[0][1];
			$right_point = $user_info[1][1];
			$carry_forward = point_carry_forward($parents_id,$date);
			$left_point = $left_point+$carry_forward[0];
			$right_point = $right_point+$carry_forward[1];

			$date = $date; //date('Y-m-d');
				
				$query = query_execute_sqli("select * from pair_point where date = '$date' and user_id = '$parents_id' ");
				$num = mysqli_num_rows($query);
				if($num == 0)
				{
					query_execute_sqli("insert into pair_point (user_id , left_point , right_point , date) values ('$parents_id' , '$left_point' , '$right_point' , '$date') ");
				}
				else
				{
					query_execute_sqli("update pair_point set left_point = '$left_point' , right_point = '$right_point' , date = '$date' where date = '$date' and user_id = '$parents_id' ");
				}
			
		}
	}
}


function get_child($id,$date)
{
	$reg_point = 0;
	$total_child = 0;
	$child[0] = $id;
	$q = query_execute_sqli("select * from users where id_user = '$child[0]' ");
	while($row = mysqli_fetch_array($q))
	{
			$reg_point = $reg_point+get_uses_points($child[0],$date);
			$total_child++;
	}
	$count = count($child);
	for($i = 0; $i <$count; $i++)
	{
		$child_id = $child[$i];
		$query = query_execute_sqli("select * from users where parent_id = '$child_id' ");
		$num = mysqli_num_rows($query);
		if($num != 0)
		{
			while($row = mysqli_fetch_array($query))
			{
				$u_id = $row['id_user'];
				$reg_point = $reg_point+get_uses_points($u_id,$date);
				$child[] = $u_id;
				$total_child++;
			}
		}
		$count = count($child);
	}
	$result[0] = $total_child;
	$result[1] = $reg_point;
	return $result;
}

function get_uses_points($id,$date)
{
	include("setting.php");
	$point = 0;
	$date =  $date; //date("Y-m-d");
	$query = query_execute_sqli("select * from reg_fees_structure where user_id = '$id' and date = '$date' ");
	$num = mysqli_num_rows($query);
	while($row = mysqli_fetch_array($query))
	{
		$reg_c = $row['reg_fees'];
		$update_c = $row['update_fees'];
		if($reg_c >= 1)
			$point = $point+$row['reg_fees'];
		if($update_c >= 1)
			$point = $point+$row['update_fees'];
	}	
	return $point;
}

function all_child($id,$date)  //give number of child which have type 'b' and currenr date joined
{
	$query = query_execute_sqli("select * from users where parent_id = '$id' and position = 0 ");
	$num = mysqli_num_rows($query);
	if($num == 0)
	{
		$child[0] = 0;
		//$child[1] = 0;
		//return $child;
	}
	else
	{
		while($row = mysqli_fetch_array($query))
		{
			$left = $row['id_user'];
			$child[0] = get_child($left,$date);
		}
	}	
	$query = query_execute_sqli("select * from users where parent_id = '$id' and position = 1 ");
	$num1 = mysqli_num_rows($query);
	if($num1 == 0)
	{
		$child[1] = 0;
		//return $child;
	}
	else
	{
		while($row = mysqli_fetch_array($query))
		{
			$right = $row['id_user'];
			$child[1] = get_child($right,$date);
		}
	}	
	return $child;
}


function point_carry_forward($id,$date)
{
	include("setting.php");
	$date = $date; //date("Y-m-d") ;	
	$query = query_execute_sqli("select * from pair_point where date < '$date' and user_id = '$id' order by id desc limit 1 ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$child[0] = $row['left_point'];
			$child[1] = $row['right_point'];
		}
		$pc = 1;
		$max_pair = min($child[0],$child[1]);
		do
		{
			$pair_calc = $per_day_multiple_pair*$pc;
			$pc++;
		}
		while($pair_calc <= $max_pair);
		$total_pair = $pair_calc-$per_day_multiple_pair;
		
		
		$max[0] = $child[0]-$total_pair;
		$max[1] = $child[1]-$total_pair;
			
	}
	else { 	
			$max[0] = 0;
			$max[1] = 0;
		 }
	return $max;
}


function get_all_parent($id)
{
	$parent[0] = $id;
	$count = count($parent);
	for($i = 0; $i <$count; $i++)
	{
			$result = query_execute_sqli("select * from users where id_user = '$parent[$i]' ");
			$num = mysqli_num_rows($result);
			if($num > 0)
			{
				while($row = mysqli_fetch_array($result))
				{
				$parent[] = $row['parent_id'];
				}
			}
		$count = count($parent);
	}
	return $parent;
}

/*pair_point_calculation(20);
$r = point_carry_forward(20);
print $r[0]."  ".$r[1];*/

?>