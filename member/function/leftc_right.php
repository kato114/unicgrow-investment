<?php
//include("../../config.php");
include("child.php");
function insert_child_in_left_right($id)
{
	$parents = get_all_parent($id);
	$count = count($parents);
	if($count >1)
	{
		for($i = 1; $i < $count-1; $i++)
		{
			$child = all_child($parents[$i]);
			$left = $child[0];
			$right = $child[1];
			$parents[$i];
			if($left !=0 or $right !=0)
			{
				$c = carry_forward($parents[$i]);
				if($c[0] != $c[1])
				{
					$max = get_max($c);
					if($max[1] == 0) { 	$left = $left+$max[0]; }
					if($max[1] == 1) { 	$right = $right+$max[0]; }
				}
					
				$date = ggetdate($id);
				$query = query_execute_sqli("select * from left_right where date = '$date' and user_id = '$parents[$i]' ");
				$num = mysqli_num_rows($query);
				//print "nm".$num;
				if($num == 0)
				{
					
					query_execute_sqli("insert into left_right (user_id , left_child , right_child , date) values ('$parents[$i]' , '$left' , '$right' , '$date' ) ");
					
				}
				else
				{
					query_execute_sqli("update left_right set left_child = '$left' , right_child = '$right' where date = '$date' and user_id = '$parents[$i]' ");
				}
			}	
		}
	}	
}

function get_all_parent($id)
{
	$parent[0] = $id;
	$count = count($parent);
	for($i = 0; $i <$count; $i++)
	{
			$result = query_execute_sqli("select * from users where id_user = '$parent[$i]' ");
			$num = mysqli_num_rows($result);
			if($num != 0)
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

function carry_forward($id)
{
	$curr_date = date('Y-m-d');
	$query = query_execute_sqli("select * from left_right where user_id = '$id' and date != '$curr_date' ORDER BY id DESC LIMIT 1 ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$child[0] = $row['left_child'];
			$child[1] = $row['right_child'];
		}
	}
	else { 	
			$child[0] = 0;
			$child[1] = 0;
		 }
	return $child;
}
/*$id = 55;
$c = carry_forward($id);

print $c[0];
print $c[1];
if($c[0] != $c[1]){	*/
function get_max($c)
{
	if($c[0] < $c[1])
	{
 		$max[0] = $c[1]-$c[0];
		$max[1] = 1;
	}
	else
	{
		$max[0] = $c[0]-$c[1];
		$max[1] = 0;
	}
	return $max;
}		

function ggetdate($id)
{
	$q = query_execute_sqli("select * from users where id_user = '$id' ");
	{
		while($r = mysqli_fetch_array($q))
		{
			$d = $r['date'];
		}
	}
return $d;
}

/*$id = 34;
insert_child_in_left_right($id);
*/
