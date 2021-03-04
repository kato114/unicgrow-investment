<?php

include "config.php";

	//$limitp = 1000 * 16;
	$sql = "SELECT user_id,COALESCE(sum(`left_point`),0) left_point,COALESCE(sum(`right_point`),0) right_point FROM month_pair_point1 group by user_id ";//limit $limitp,2000";
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	
	if($cnt > 0){
		while($row = mysqli_fetch_array($query))
		{
			$left_point = $row['left_point'];
			$right_point = $row['right_point'];
			$user_id = $row['user_id'];
			
			$pair_point = last_pair($user_id);
			$pair_left  = $pair_point[0];
			$pair_right  = $pair_point[1];
			$date  = $pair_point[2];
			
			$diff_l = $left_point - $pair_left;
			$diff_r = $right_point - $pair_right;
			
			$user_id,'***Left Business = $left_point','***Right Business = $right_point'
			,'***Left Pair = $pair_left','***Right Pair = $pair_right'
			,'***Left Diff = $diff_l','***Right Diff = $diff_r<br>';
			
		}
		mysqli_free_result($query);
	}
	free_object_memory();



function last_pair($user_id){
	$sql = NULL;
	$pair[0]  = 0;
	$pair[1]  = 0;
	$sql = "select * from pair_point where user_id='$user_id' order by id desc limit 1";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$pair[0] = $row['left_point'];
			$pair[1] = $row['right_point'];
			$pair[2] = $row['date'];
		}
		mysqli_free_result($query);
		
	}
	return $pair;
}
