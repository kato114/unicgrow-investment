<?php
//include("../config.php");

/*
function check_income_condition($id)
{
	$res = 0;
	$child_type_b[0] = 0;
	$child_type_b[1] = 0;
	$child[0] = $id;
	$c = count($child);
	for($i = 0; $i < $c; $i++)
	{
		$q = query_execute_sqli("select * from users where parent_id = '$child[$i]' and position = 0 ");
		$num = mysqli_num_rows($q);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($q))
			{
				$type = $row['type'];
				if($type == 'B')
				{
					$child_type_b[0]++; 
				}	
				$child[] = $row['id_user'];
			}
		}	
		$q = query_execute_sqli("select * from users where parent_id = '$child[$i]' and position = 1 ");
		$num = mysqli_num_rows($q);
		if($num > 0)
		{	
			while($row = mysqli_fetch_array($q))
			{
				$type = $row['type'];
				if($type == 'B')
				{
					$child_type_b[1]++; 
				}	
				$child[] = $row['id_user'];
			}
		}
		$c = count($child);
		if($child_type_b[1] > 0 and $child_type_b[0] > 0)
		{
			$t = $child_type_b[0]+$child_type_b[1];
			if($t > 2)
			{
				$res = 1;
				$i = $c+2;
			}	
		}	
	}
	return $res;		
}*/		
	
function check_income_condition($id)
{
	$income = 0;
	$q = query_execute_sqli("select * from reg_fees_structure where user_id = '$id' ");
	$num = mysqli_num_rows($q);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($q))
		{
			$income = $income+$row['reg_fees']; 	
			$income = $income+$row['update_fees'];
		}	
	}
	if($income != 0)
	{
		$res = 1;
	}
	else
	{
		$res = 0;
	}		
	return $res;		
}		



/*$id = 23;
$r = check_income_condition($id);
print $r;*/