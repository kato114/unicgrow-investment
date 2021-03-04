<?php
ini_set('display_errors','on');

// TRUNCATE `withdrawal_crown_wallet`;
include("../../config.php");
include("../pair_point_calc.php");
include("../functions.php");
//set_business($user_id = '54702' , $business = '25000' , $date = '2019-03-15');
 
function  set_business($id,$business,$date){
	include("../setting.php");
	$parents = get_all_parent($id);
	$cnt_parent = count($parents[0]); 
	$cdate = date("Y-m-d");
	$new_amount = $amount = $business;
	for($i = 1; $i < $cnt_parent ; $i++){
		$amount = $new_amount;
		$pair_field = '';
		switch($parents[1][$i])
		{
			case 0 : $pair_field = 'left_point';$total_bus_field = 'cf_left';$pair_bm = 'lb_member';
					  break;
			case 1 : $pair_field = 'right_point';$total_bus_field = 'cf_right';$pair_bm = 'rb_member';
					  break;
		}
		$chk = chk_pair_poin_id_exist($parents[0][$i]);
		$user_id = $parents[0][$i];
		$new_pl_id = $parents[4][$i];
		$sql = "select * from pair_point where user_id='$user_id' and (FIND_IN_SET($id,lb_member) or FIND_IN_SET($id,rb_member)) and (date='$date' or date='$cdate')";
		$quw = query_execute_sqli($sql);
		$process = 1;
		if(mysqli_num_rows($quw) > 0)$process = 0;
		mysqli_free_result($quw);
		if($process == 0)continue;
		if(($parents[2][$i] >=1 and $parents[3][$i] > 1) or ($parents[2][$i] > 1 and $parents[3][$i] >= 1)){
			$udirect_l = $parents[2][$i];
			$udirect_r = $parents[3][$i];
		}
		else{
			$sql = "select * from users where id_user='$user_id'";
			$qul = query_execute_sqli($sql);
			$row = mysqli_fetch_array($qul);
			$udirect_l = $row['l_lps'];
			$udirect_r = $row['r_lps'];
			$pair_lapse_amount = $row['pair_lapse'];
			if($real_p == $user_id){
				if($pair_lapse_amount > 0){
					if($amount >= $pair_lapse_amount){
						$amount = $amount - $pair_lapse_amount;
						query_execute_sqli("update users set pair_lapse = 0,pair_lapse_date='$date' 
						where id_user=$user_id");
					}
					else{
						$amount = $pair_lapse_amount - $amount;
						query_execute_sqli("update users set pair_lapse = $amount,pair_lapse_date='$date' 
						where id_user=$user_id");
					}
				}
			}
		}
		if($user_id > 0){
			if($chk == 0){// id dos'nt exist with date
				$sql_insert = "insert into pair_point (user_id, $pair_field,$total_bus_field ,date,reg_id) values('$user_id','$amount','$new_amount','$date','$new_pl_id')";
				query_execute_sqli($sql_insert);
			}
			else{
				$chk_2 = chk_pair_poin_id_exist_with_date($parents[0][$i],$pair_field,$cdate);
				if($chk_2[0][0] == 1){// id exist with date
					$pair_amount = $chk_2[0][1];
					$pair_id = $chk_2[0][2];
					$point = $amount+$pair_amount;
					$sql_update = "update pair_point set  $pair_field = $point ,$total_bus_field=$total_bus_field+'$new_amount',reg_id='$new_pl_id'
					where id = '$pair_id' and user_id = '$user_id' ";
					query_execute_sqli($sql_update);					
				}
				if($chk_2[0][0] == 0){// carry forward
					$carry_forward = point_carry_forward($user_id,$cdate,$udirect_l,$udirect_r,$new_pl_id);
					$left_point = 	$carry_forward[0];
					$right_point = 	$carry_forward[1];
					
					$cf_left = 0;
					$cf_right = 0;
					if($pair_field == 'right_point')
					{
						$right_point = $amount + $right_point;
						$left_point = $left_point;
						$cf_right = $new_amount;
					}
					
					if($pair_field == 'left_point')
					{
						$left_point = $amount + $left_point;
						$right_point = $right_point;
						$cf_left = $new_amount;
					}
					$insert_left_point = $left_point;
					$insert_right_point = $right_point;
					
					$sql = "insert into pair_point (user_id, left_point,right_point,cf_left,cf_right,date,reg_id) 								values('$user_id','$insert_left_point','$insert_right_point','$cf_left',
							'$cf_right','$cdate','$new_pl_id')";
					query_execute_sqli($sql);
				}
			}
			if($real_p == $user_id){
				if($udirect_l < 2 or $udirect_r < 2){
					$ubc = $new_amount / $per_day_multiple_pair;
					if($ubc > 2)$ubc = 2; 
					query_execute_sqli("update users set r_lps = r_lps + $ubc,binary_qdate='$cdate' where id_user=$user_id and r_lps < 2");
					query_execute_sqli("update users set l_lps = l_lps + $ubc,binary_qdate='$cdate' where id_user=$user_id and l_lps < 2");
				}
			}
			$sql = "UPDATE pair_point SET 
					$pair_bm = 
					CASE  
						WHEN $pair_bm  = '' THEN '$id' 
						WHEN $pair_bm  <> '' THEN CONCAT('$id', ',' , $pair_bm) 
					END
					WHERE user_id = '$user_id' order by id desc limit 1";
			query_execute_sqli($sql);
		}
	}
}
	