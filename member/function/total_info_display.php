<?php
function get_total_paid_unpaid_members($users)
{
	$total_member = count($users);
	$users = implode(",",$users);
	if($users != ""){
		$sql = "select * from reg_fees_structure where user_id in($users) group by user_id ";
		$q = query_execute_sqli($sql);	
		$num = mysqli_num_rows($q);
		$result[0] = $num;
		$result[1] = $total_member-$num;
		
		$sql = "select COALESCE(sum(update_fees),0) tot from reg_fees_structure where user_id in($users)";
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
	return $result;
}					
?>