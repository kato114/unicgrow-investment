<?php
/*include "../config.php";
print_r(get_all_total_child($id=2,$main_id=1));*/
function give_total_children($id)  //give all children
{
	$query = query_execute_sqli("select * from users where parent_id = '$id' and position = 0 ");
	$num = mysqli_num_rows($query);
	if($num == 0)
	{
		$children[0] = array(0,0,0,0,0);
	}
	while($row = mysqli_fetch_array($query))
	{
		$left = $row['id_user'];
		$children[0] = get_all_total_child($left,$id);
	}
	
	$query = query_execute_sqli("select * from users where parent_id = '$id' and position = 1 ");
	$num1 = mysqli_num_rows($query);
	if($num1 == 0)
	{
		$children[1] = array(0,0,0,0,0);
	}
	while($row = mysqli_fetch_array($query))
	{
		$right = $row['id_user'];
		$children[1] = get_all_total_child($right,$id);
	}
	return $children;
}



function get_all_total_child($id,$main_id){  // get all child in id network
	//$sql = "select get_chield_by_parent($id)";
	$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id IN ($id)";
	$results = rtrim(mysqli_fetch_array(query_execute_sqli($sql))[0],',');
	$results = trim($results,",");
	if($results != ""){
		$results = explode(",",$results);
		$users = implode(",",$results).",$id";
		$total_member = $count = count($results)+1;
	}
	else{
		$users = $id;
		$total_member = $count = count($results);
	}
	
	
	if($users != ""){
		$result = array();
		$sql = "select * from reg_fees_structure where user_id in($users) group by user_id ";
		$q = query_execute_sqli($sql);	
		$num = mysqli_num_rows($q);
		$result[0] = $num; //Total Paid Member
		$result[1] = $total_member-$num;  //Total Unpaid Member
		$sql = "select COALESCE(sum(request_crowd),0) tot from reg_fees_structure where user_id IN ($users) and boost_id = 0";
		$user_query = query_execute_sqli($sql);	
		while($r1 = mysqli_fetch_array($user_query)){
			$t_invst = $r1[0];
		}	
		$result[2] = $t_invst;
	}
	else{
		$result[0] = 0;
		$result[1] = 0;
		$result[2] = 0;
	}
	$result[3] = $count;
	$sql = "SELECT COALESCE(update_fees,0) amount,date FROM reg_fees_structure 
		WHERE user_id = '$main_id' and boost_id = 0 ORDER BY id DESC LIMIT 1"; 
	$inv_query = query_execute_sqli($sql);
	while($r1 = mysqli_fetch_array($inv_query)){
		$result[4] = $r1[0];
		$result[5] = $r1[1];
	}
	return $result;
}
