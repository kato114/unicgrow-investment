<?php
/*include("../config.php");
print_r($r = geting_best_position(1,0));*/
function geting_best_position($id,$position)
{
	$sql = "SELECT * FROM users WHERE parent_id = '$id' AND position = '$position'";
	$result = query_execute_sqli($sql);
	$num = mysqli_num_rows($result);
	mysqli_free_result($result);
	if($num > 0)
	{
		/*while($row = mysqli_fetch_array($result))
		{
			$mid = $row['id_user'];
			mysqli_free_result($result);
			return geting_best_position($mid,$position);
		}*/
		$best_child = get_chld_best_pos($id,$position);
	}
	else {
		$best_child[0] = $id;
		$best_child[1] = $position;
	}
	
	return $best_child;
}
function get_chld_best_pos($user_id,$position)
{
	$parent = array();
	$parent[0] = $user_id;
	$count = 1;
	for($i = 0; $i < $count; $i++)
	{
		$sql = "SELECT * FROM users WHERE parent_id = '$parent[$i]' AND position = '$position'";
		$result = query_execute_sqli($sql);
		$num = mysqli_num_rows($result);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$parent[] = $row['id_user'];
			}
			mysqli_free_result($result);
			$count = count($parent);
		}
		else
		{
			$virtual_parents[0] = $parent[$i];
			$virtual_parents[1] = $position;
			mysqli_free_result($result);
			break;
		}
	}
	return $virtual_parents;
}


function check_virtual_parent_position($id,$position)
{
	$result = query_execute_sqli("select * from users where parent_id = '$id' and position = '$position' ");
	$num = mysqli_num_rows($result);
	return $num;		
}
function set_left_right_network($user_id,$position,$virtual_p){
	global $con;
	query_execute_sqli("INSERT INTO network_users (user_id) VALUES ('$user_id')");
	if($position == 0){
		$network_field = "left_network";
	}
	else{ $network_field = "right_network"; }
	
	
	if($user_id > 0){
		$sql = "UPDATE network_users SET 
		$network_field = 
		CASE  
			WHEN $network_field = '' THEN '$user_id' 
			WHEN $network_field <> '' THEN CONCAT('$user_id', ',' , $network_field) 
		END
		 , date = NOW() WHERE user_id = '$virtual_p'";
		query_execute_sqli($sql);
		
		
		$SQL_LEFT = "UPDATE network_users SET 
		left_network = 
		CASE
			WHEN FIND_IN_SET($virtual_p,left_network) THEN
			CASE
				WHEN left_network = '' THEN '$user_id' 
				WHEN left_network <> '' THEN CONCAT('$user_id', ',' , left_network) 
			END
			else left_network
		END ,
		right_network = 
		CASE
			WHEN FIND_IN_SET($virtual_p,right_network) THEN
			CASE
				WHEN right_network = '' THEN '$user_id' 
				WHEN right_network <> '' THEN CONCAT('$user_id', ',' , right_network) 
			END
			else right_network
		END
		WHERE FIND_IN_SET($virtual_p,left_network) OR FIND_IN_SET($virtual_p,right_network)";
		query_execute_sqli($SQL_LEFT);
	}
	$record_aff = mysqli_affected_rows($con);
	$virtual_pp = $virtual_p;
	if($record_aff == 0 and $user_id > 0){
		$parents = get_all_parent($virtual_p);
		$cnt_parent = count($parents[0]); 
		for($i = 1; $i < $cnt_parent ; $i++)
		{
			$virtual_p = $parents[0][$i];
			$network_field = '';
			switch($parents[1][$i])
			{
				case 0 : $network_field = 'left_network';
						  break;
				case 1 : $network_field = 'right_network';
						  break;
			}
			$sql = "UPDATE network_users SET 
			$network_field = 
			CASE  
				WHEN $network_field = '' THEN '$user_id' 
				WHEN $network_field <> '' THEN CONCAT('$user_id', ',' , $network_field) 
			END
			 , date = NOW() WHERE user_id = '$virtual_p'";
			query_execute_sqli($sql);
		}
		$sql = "insert into network_info set user_id='$user_id', parent_id = '$virtual_pp' ";
		query_execute_sqli($sql);
	}
}
function set_user_level($user_id){
	$upline = array();
	$member_id = $upline[] = real_parent($user_id);
	$sql = "select user_id from level_member where FIND_IN_SET($member_id,`member`) order by level asc";
	$q = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($q)){
		$upline[] = $row['user_id'];
	}
	mysqli_free_result($q);
	$sql = "insert into level_member set user_id=$user_id,`level`=1";
	query_execute_sqli($sql);
	for($i = 0; $i < count($upline); $i++){
		$upline_id = $upline[$i];
		if($upline_id == 0)continue;
		$sql = "SELECT * from level_member where user_id='$upline_id' and level=".($i+1);
		$query = query_execute_sqli($sql);
		if(mysqli_num_rows($query) == 0){
			$sql = "insert into level_member set user_id=$upline_id,`member`=$user_id,`level`=".($i+1);
		}
		else{
			$sql = "update level_member set `member` = 
					case 
						when member <> '' then CONCAT($user_id,',',`member`)
						else  $user_id
					end
					where user_id='$upline_id' and `level`=".($i+1);
		}
		mysqli_free_result($query);
		query_execute_sqli($sql);
		//$SQLO = "UPDATE users SET level = ".($i+1)." WHERE id_user IN ($upline_id)";
		//query_execute_sqli($SQLO);
	}
}
