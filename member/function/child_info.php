<?php

//include("../config.php");
function get_all_child_information($id)  // get all child in id network
{
	include("functions.php");
	$child[0] = $id;
	$count = count($child);
	$j = 1;
	for($i = 0; $i <$count; $i++)
	{
	
			$result = query_execute_sqli("select * from users where parent_id = '$child[$i]' ");
			$num = mysqli_num_rows($result);
			if($num != 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$child[] = $row['id_user'];
					$info[$j][0] = get_user_name($row['id_user']);
					$info[$j][1] = $row['f_name']." ".$row['l_name'];
					$info[$j][2] = get_user_name($row['real_parent']);
					$info[$j][3] = get_user_name($row['parent_id']);
					$info[$j][4] = $row['date'];
					$info[$j][5] = get_user_pos($row['id_user']);
					$info[$j][6] = get_user_type($row['id_user']);
					$j++;
				}
			}
		$count = count($child);
	}
	return $info;
}

/*function get_username($id)
{
	$query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$username = $row['username'];
		return $username;
	}	
}

function get_user_type($id)
{
	$query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$type = $row['type'];
		if($type == 'A') { $status = "Deactive"; }
		if($type == 'B') { $status = "Light"; }
		if($type == 'B') { $status = "Activate"; }
		return $status;
	}	
}
$f = 1;
$e = get_all_child_information($f);
$c = count($e);
for($i = 0; $i <$c; $i++)
	{
		print $e[$i][0];
		print $e[$i][1];
		print $e[$i][2];print $e[$i][3];
		print $e[$i][4];
		print $e[$i][5]; }*/