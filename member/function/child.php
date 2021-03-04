<?php
//include("../../config.php");



//$id =9;

function position($id)			// get position of given id
{
$res = query_execute_sqli("SELECT  position FROM users WHERE id_user = '$id' ");
$row = mysqli_fetch_array($res);
return $row['position'];
}

function parent_id($id)  		//get parent id of given id
{
		$res = query_execute_sqli("SELECT * FROM users WHERE id_user = '$id' ");
		$row = mysqli_fetch_array($res);
		return $row['parent_id'];
}



function userid_position($id,$level) // Id, First Parent and Positions is allready in id.
{
$parent_id[0][0] = $as = $id;
$parent_id[0][1] = position($id);
$i=1;
	while($as !=0 )
	{
		if($level != 0)
		{
			if($i == $level+2){
			return $parent_id;
			exit; }
		}
		$parent_id[$i][0] = $as = parent_id($parent_id[$i-1][0]);
		$parent_id[$i][1] = position($parent_id[$i][0]);
		$i++; 
	}
	$a = count($parent_id);
	for($i =0;$i<$a-1;$i++)
	{
		$par[$i][0] = $parent_id[$i+1][0];
		$par[$i][1] = $parent_id[$i][1];
	}
	return $par;	//return all parenta and its positions
}



function get_child($id)
{
	$child[0] = $id;
	$count = count($child);
	$q = query_execute_sqli("select * from users where id_user = '$child[0]' ");
	while($row = mysqli_fetch_array($q))
	{
			$db_date = $row['activate_date'];
			$db_type = $row['type'];
			$date = date('Y-m-d');
			$j=0;
			if($db_date == $date and $db_type == 'B') { $per_day_child[$j] = $row['id_user']; $j++; }
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
					$db_date = $row['activate_date'];
					$db_type = $row['type'];
					$child[] = $row['id_user'];
					$date = date('Y-m-d');
					if($db_date == $date and $db_type == 'B') { $per_day_child[$j] = $row['id_user']; $j++; }
				}
			}
		$count = count($child);
	}
	$c = count($per_day_child);
	return $c;
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

/*$child = all_child(1);
print "left clild".$child[0]."  right child".$child[1];
*/

