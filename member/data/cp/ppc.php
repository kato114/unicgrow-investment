<?php
include("../config.php");

function pair_point_calculation($id)
{
	$parents = get_all_parent($id);
	$count = count($parents);
	if($count >1)
	{
		for($i = 1; $i < $count-1; $i++)
		{
			$parents_id = $parents[$i];
			$user_info = all_child($parents_id);  // $child[0][1]  $child[1][1]
			$left_point = $user_info[0][1];
			$right_point = $user_info[1][1];
			$carry_forward = point_carry_forward($parents_id);
				if($carry_forward[1] == 0) { $left_point = $left_point+$carry_forward[0]; }
				if($carry_forward[1] == 1) { $right_point = $right_point+$carry_forward[0]; }
			$date = date('Y-m-d');
			if($left_point != 0 or $right_point != 0)
			{	
				//query_execute_sqli("insert into pair_point (user_id , left_point , right_point , date) values ('$id' , '$left_point' , '$right_point' , '$date') ");
				
				$start_date =  date("Y-m-1");		
				$end_date =  date("Y-m-t") ;
				$query = query_execute_sqli("select * from pair_point where date >= '$start_date' and date <= '$end_date' and user_id = '$parents_id' ");
				$num = mysqli_num_rows($query);
				if($num == 0)
				{
						
					query_execute_sqli("insert into pair_point (user_id , left_point , right_point , date) values ('$parents_id' , '$left_point' , '$right_point' , '$date') ");
						
				}
				else
				{
					query_execute_sqli("update pair_point set left_point = '$left_point' , right_point = '$right_point' , date = '$date' where date >= '$start_date' and date <= '$end_date' and user_id = '$parents_id' ");
					
				}
			
			}
		}	
	}
}

//pair_point_calculation(3);

function get_child($id)
{
	$reg_point = 0;
	$total_child = 0;
	$child[0] = $id;
	$q = query_execute_sqli("select * from users where id_user = '$child[0]' ");
	while($row = mysqli_fetch_array($q))
	{
			$reg_point = $reg_point+get_uses_points($child[0]);
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
					$reg_point = $reg_point+get_uses_points($u_id);
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

function get_uses_points($id)
{
	$point = 0;
	$previous_start_date =  date("Y-m-1");
	$previous_last_date = date("Y-m-t"); 
	$query = query_execute_sqli("select * from reg_fees_structure where user_id = '$id' and date >= '$previous_start_date' and date <= '$previous_last_date' ");
	$num = mysqli_num_rows($query);
	while($row = mysqli_fetch_array($query))
	{
		$point = $point+$row['reg_fees'];
		$point = $point+$row['update_fees'];
	}	
	return $point;
}

function all_child($id)  //give number of child which have type 'c' and currenr date joined
{
	$query = query_execute_sqli("select * from users where parent_id = '$id' and position = 0 ");
	$num = mysqli_num_rows($query);
	if($num == 0)
	{
		$child[0] = 0;
		$child[1] = 0;
		return $child;
	}
	while($row = mysqli_fetch_array($query))
	{
		$left = $row['id_user'];
		$child[0] = get_child($left);
	}
	$query = query_execute_sqli("select * from users where parent_id = '$id' and position = 1 ");
	$num1 = mysqli_num_rows($query);
	if($num1 == 0)
	{
		$child[1] = 0;
		return $child;
	}
	while($row = mysqli_fetch_array($query))
	{
		$right = $row['id_user'];
		$child[1] = get_child($right);
	}
	return $child;
}


function point_carry_forward($id)
{
	$date = date('Y-m-d');
	print $start_date =  date("Y-m-1", strtotime($date .'-1 month') ) ;			
	$end_date =  date("Y-m-t", strtotime($date .'-1 month') ) ;
	$query = query_execute_sqli("select * from pair_point where date >= '$start_date' and date <= '$end_date' and user_id = '$id' ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$child[0] = $row['left_point'];
			$child[1] = $row['right_point'];
		}
		if($child[0] < $child[1])
		{
			$max[0] = $child[1]-$child[0];
			$max[1] = 1;
		}
		else
		{
			$max[0] = $child[0]-$child[1];
			$max[1] = 0;
		}
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

point_carry_forward(1);