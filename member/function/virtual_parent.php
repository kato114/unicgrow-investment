<?php
//include("../config.php");

function geting_virtual_parent($id)
{
	$query = query_execute_sqli("select * from users where parent_id = '$id' ");
	$number = mysqli_num_rows($query);
	return $number;
}


function geting_virtual_parent_with_position($id,$position)
{
	$query = query_execute_sqli("select * from users where parent_id = '$id' and position = '$position' ");
	$number = mysqli_num_rows($query);
	return $number;
}


function geting_all_blank_position_with_adding_position($id,$position)
{
	$result = query_execute_sqli("select * from users where parent_id = '$id' and position = '$position' ");
	$num = mysqli_num_rows($result);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$position_user_id = $row['id_user'];
		}
	}
	$all_child = geting_all_blank_position($position_user_id);	
	return $all_child;		
}


function geting_all_blank_position($id)
{
	$parent[0] = $id;
	$count = 1;
	for($i = 0; $i <$count; $i++)
	{
			$result = query_execute_sqli("select * from users where parent_id = '$parent[$i]' ");
			$num = mysqli_num_rows($result);
			if($num != 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$parent[] = $row['id_user'];
				}
				if($num == 1)
				{
					$virtual_parent[] = $parent[$i];
				}
			}
			if($num == 0)
			{
				$virtual_parent[] = $parent[$i];
			}
		$count = count($parent);
	}
	return $virtual_parent;
}

function get_virtual_posotion_fro_Reg($id)
{
	$parent[0] = $id;
	$i = 0;
	do
	{
		$result = query_execute_sqli("select * from users where parent_id = '$parent[$i]' ");
		$num = mysqli_num_rows($result);
		if($num == 2)
		{
			while($row = mysqli_fetch_array($result))
			{
				$parent[] = $row['id_user'];
			}
			$i++;
		}
		else
		{
			$res[0] = $parent[$i];
			$res[1] = $num;
		}
	}while($num == 2);
	return $res;
}

function get_virtual_parent_position($id,$position)
{
	$parent[0] = $id;
	$i = 0;
	do
	{
		$result = query_execute_sqli("select * from users where parent_id = '$parent[$i]' and position = '$position' ");
		$num = mysqli_num_rows($result);
		if($num == 1)
		{
			while($row = mysqli_fetch_array($result))
			{
				$parent[] = $row['id_user'];
				$i++;
			}
		}
		else
			return $parent[$i];
	}while($num == 1);	
}


