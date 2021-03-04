<?php

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
		$left_info = get_child($left);
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
		$right_info = get_child($right);
	}
	$all_info[0] = $left_info[0];
	$all_info[0] = $left_info[1];
	$all_info[0] = $left_info[2];
	$all_info[0] = $right_info[0];
	$all_info[0] = $right_info[1];
	$all_info[0] = $right_info[2];
	
	return $all_info;
}


function get_child($id)
{
	$info[0] = 0; // free
	$info[1] = 0; // paid
	$info[2] = 0; // block
	$child[0] = $id;
	$count = count($child);
	$q = query_execute_sqli("select * from users where id_user = '$child[0]' ");
	while($row = mysqli_fetch_array($q))
	{
		$num = get_paid_free($row['id_user']);
		$db_type = $row['type'];
		if($db_type == 'C') { $info[2] = $info[2]+1; }
		elseif($num != 0) { $info[1] = $info[1]+1; }
		else { $info[0] = $info[0]+1; }
	}
	for($i = 0; $i <$count; $i++)
	{
		$child_id = $child[$i];
		$result = query_execute_sqli("select * from users where parent_id = '$child_id' ");
		$num = mysqli_num_rows($result);
		if($num != 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$num = get_paid_free($row['id_user']);
				$db_type = $row['type'];
				$child[] = $row['id_user'];
				if($db_type == 'C') { $info[2] = $info[2]+1; }
				elseif($num != 0) { $info[1] = $info[1]+1; }
				else { $info[0] = $info[0]+1; }
			}
		}
		$count = count($child);
	}
	return $info;
}

function get_paid_free($id)
{
	$date = date('Y-m-d');
	$query = query_execute_sqli("select * from reg_fees_structure where user_id = '$id' and date <= '$date' and end_date >= '$date' ");
	$num = mysqli_num_rows($query);
	return $num;	
}	