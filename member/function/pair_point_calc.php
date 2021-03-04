<?php
/*ini_set("display_errors",'on');
session_start();

require_once("../config.php");
include("setting.php");
include("functions.php");
include("direct_income.php");
pair_point_calculation(3,$systems_date,$pleg = true);*/
function pair_point_calculation($id,$date,$pleg = false)
{
	include("setting.php");
	$week_pn = get_pre_nxt_date($date , $binary_froward_day);
	$previous_date = $week_pn[0];
	$next_date = $week_pn[1];
	//set_Next_Roi_Date($id);
	if(!$pleg){
		$sql = "select t1.* from reg_fees_structure t1 where t1.user_id = '$id' and t1.request_crowd > 0";
		$cnt = mysqli_num_rows(query_execute_sqli($sql));
		if($cnt == 1){
			$carry_forward = point_carry_forward($id,$date,$udirect_l,$udirect_r,$new_pl_id=0,$previous_date,$next_date);
			$left_point = 	$carry_forward[0];
			$right_point = 	$carry_forward[1];
			if($left_point > 0 or $right_point > 0){
				$chk_pair = chk_pair_poin_id_exist_with_date($id,'left_point',$previous_date,$next_date);
				if($chk_pair[0][0] == 0){
					$sql = "insert into pair_point (user_id, left_point,right_point,date) 
					values('$id','$left_point','$right_point','$date')";
				}
				query_execute_sqli($sql);
			}
			
		}
	}
	$pre_pv = 0;
	
	
	//check upgrade when yes then booster is not getting by member end
	$sql = "select t1.* from reg_fees_structure t1
			where t1.user_id = '$id' and t1.date = '$date' and t1.request_crowd > 0 order by id desc limit 1";
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	if($cnt > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$new_amount = $row['request_crowd'];
		}
		$new_amount = $new_amount;//deduct pre top pv
		
		$real_p = real_parent($id);
		$parents = get_all_parent($id);
		$cnt_parent = count($parents[0]); 
		for($i = 1; $i < $cnt_parent ; $i++)
		{
			$amount = $new_amount;
			$pair_field = '';
			
			if($parents[5][$i] != 'B')continue;
			switch($parents[1][$i])
			{
				case 0 : $pair_field = 'left_point';$total_bus_field = 'cf_left';$pair_bm = 'lb_member';
						 $pair_dbm = 'dlb_member';
						  break;
				case 1 : $pair_field = 'right_point';$total_bus_field = 'cf_right';$pair_bm = 'rb_member';
						 $pair_dbm = 'drb_member';
						  break;
			}
			$chk = chk_pair_poin_id_exist($parents[0][$i]);
			$user_id = $parents[0][$i];
			
			$new_pl_id = $parents[4][$i];
			$chk_abp_wle = true;
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
				$pair_lapse_amount = 0;
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
			
			
			$amount = round($amount);
			if($user_id > 0){
				if($chk == 0 and $amount > 0){// id dos'nt exist with date
					$sql_insert = "insert into pair_point (user_id, $pair_field,$total_bus_field ,date,reg_id) values('$user_id','$amount','$new_amount','$date','$new_pl_id')";
					query_execute_sqli($sql_insert);
				}
				else{
					$chk_2 = chk_pair_poin_id_exist_with_date($parents[0][$i],$pair_field,$previous_date,$next_date);
					if($chk_2[0][0] == 1 and $amount > 0){// id exist with date
						$pair_amount = $chk_2[0][1];
						$pair_id = $chk_2[0][2];
						$point = $amount+$pair_amount;
						$sql_update = "update pair_point set  $pair_field = $point ,$total_bus_field=$total_bus_field+'$new_amount',reg_id='$new_pl_id',date = '$date'
						where id = '$pair_id' and user_id = '$user_id' ";
						query_execute_sqli($sql_update);					
					}
					if($chk_2[0][0] == 0 and $amount > 0){// carry forward
						$carry_forward = point_carry_forward($user_id,$date,$udirect_l,$udirect_r,$new_pl_id,$previous_date,$next_date);
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
								'$cf_right','$date','$new_pl_id')";
						query_execute_sqli($sql);
					}
				}
				if($real_p == $user_id){
					if($udirect_l < 2 or $udirect_r < 2){
						if($parents[1][$i] == 1){
							query_execute_sqli("update users set r_lps = 2 where id_user=$user_id and r_lps < 2");
						}
						elseif($parents[1][$i] == 0){
							query_execute_sqli("update users set l_lps = 2 where id_user=$user_id and l_lps < 2");
						}
					}
				}		
				if($chk_abp_wle){
					$sql = "UPDATE pair_point SET 
						$pair_bm = 
						CASE  
							WHEN $pair_bm  = '' THEN '$id' 
							WHEN $pair_bm  <> '' THEN CONCAT('$id', ',' , $pair_bm) 
						END
						WHERE user_id = '$user_id' order by id desc limit 1";
				}
				else{
					$sql = "UPDATE pair_point SET 
						$pair_dbm = 
						CASE  
							WHEN $pair_dbm  = '' THEN '$id' 
							WHEN $pair_dbm  <> '' THEN CONCAT('$id', ',' , $pair_dbm) 
						END
						WHERE user_id = '$user_id' order by id desc limit 1";
				}
				query_execute_sqli($sql);
				
				//Set_member_qualification($user_id,$club_business,$date);
			}
		}
	}

}

function get_all_parent($id)
{	
	require_once "functions.php";
	$parent[0][0] = $id;
	$pos = get_user_pos($id);
	if($pos == 'Left')
	{
		$pos = 0;
	}
	if($pos == 'Right')
	{
		$pos = 1;
	}
	$parent[1][0] = $pos;
	$count = count($parent[0]);
	$user_id =  $parent[0][0];
	$sql = "select t1.*,t2.l_lps,t2.r_lps,t2.type `global_type` from 
			(	select user_id,
				case 
					when FIND_IN_SET($user_id,left_network) then 0 
					when FIND_IN_SET($user_id,right_network) then 1 
				end position 
				from network_users 
				where FIND_IN_SET($user_id,left_network) or FIND_IN_SET($user_id,right_network)
			) t1
			left join users t2 on t1.user_id = t2.id_user order by t1.user_id";
	
	$result = query_execute_sqli($sql);
	$num = mysqli_num_rows($result);
	if($num > 0)
	{	$i = 1;
		while($row = mysqli_fetch_array($result))
		{
			
			$parent[0][$i] = $row['user_id'];
			$parent[1][$i] = $row['position'];
			$parent[2][$i] = $row['l_lps'];
			$parent[3][$i] = $row['r_lps'];
			$sql = "select COALESCE(max(invest_type),0) from reg_fees_structure where user_id='".$row['user_id']."' and invest_type > 0";
			$query = query_execute_sqli($sql);
			$plan_id = mysqli_fetch_array($query)[0];
			mysqli_free_result($query);
			$parent[4][$i] = $plan_id;
			$parent[5][$i] = $row['global_type'];
			$i++;
		}
	}
	mysqli_free_result($result);
	return $parent;
}
function get_all_parent_old($id)
{	
	require_once "functions.php";
	$parent[0][0] = $id;
	$pos = get_user_pos($id);
	if($pos == 'Left')
	{
		$pos = 0;
	}
	if($pos == 'Right')
	{
		$pos = 1;
	}
	$parent[1][0] = $pos;
	$count = count($parent[0]);
	$j = 1;
	for($i = 0; $i <$count; $i++)
	{ 
		$user_id =  $parent[0][$i];
		$sql = "select * from users where id_user = '$user_id' ";
		$result = query_execute_sqli($sql);
		$num = mysqli_num_rows($result);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($result))
			{
				$parent[0][$j] = $row['parent_id'];
				$parent[1][$j] = $row['position'];
				$parent[2][$j] = $row['l_lps'];
				$parent[3][$j] = $row['r_lps'];
				$j++;
			}
		}
		$count = count($parent[0]);
	}
	return $parent;
}

function chk_pair_poin_id_exist($id)
{
	 $sql = "select * from pair_point where user_id = '$id'";
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	if($cnt == 0)
	return 0;
	else
	return 1;
}

function chk_pair_poin_id_exist_with_date($id,$pair_field,$previous_date,$next_date)
{
	$sql = "select * from pair_point where user_id = '$id' and `date` between '$previous_date' and '$next_date'";
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	$user_info = array();
	if($cnt == 0)
	{
			$user_info[0][0] = 0;
			$user_info[0][1] = 0;
			$user_info[0][2] = 0;
		
	}
	else
	{
		$user_info[0][0] = 1;
		while($row = mysqli_fetch_array($query))
		{
			$user_info[0][1] = $row[$pair_field];
			$user_info[0][2] = $row['id'];
		}
	}
	return $user_info;	
}


function point_carry_forward($id,$date,$udirect_l,$udirect_r,$capping_id=0,$previous_date,$next_date)
{
	include("setting.php");
	$date = $date; //date("Y-m-d") ;
	$sql = "select * from pair_point where date < '$date' and user_id = '$id' group by date order by id desc limit 1 ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num != 0 /*and check_member_qualify_binary($id,$date)*/){
		while($row = mysqli_fetch_array($query)){
			$child[0] = $row['left_point'];
			$child[1] = $row['right_point'];
			$pair_mode = $row['pair_mode'];
		}
		
		$total_pair = 0;
		$sql = "select * from users where id_user='$id'";
		$qul = query_execute_sqli($sql);
		$row = mysqli_fetch_array($qul);
		$ulapse_l = $row['l_lps'];
		$ulapse_r = $row['r_lps'];
		$topup_comp = $row['package'];
		$max_pair = min($child[0],$child[1]);
		if((($ulapse_l >= 1 and $ulapse_r > 1) or ($ulapse_l > 1 and $ulapse_r >= 1)) and $topup_comp==2 and $pair_mode == 0 and $max_pair >= $min_matching_business){
			$pc = 1;
			
			/*do
			{
				$pair_calc = $per_day_multiple_pair*$pc;
				$pc++;
			}
			while($pair_calc <= $max_pair);*/
			$total_pair = $max_pair;//$pair_calc-$per_day_multiple_pair;
		}
		
		$max[0] = $child[0]-$total_pair;
		$max[1] = $child[1]-$total_pair;
	}
	else { 	
		$max[0] = 0;
		$max[1] = 0;
	}
	return $max;
}

function add_member_total_business($user_id,$amount,$pos,$date){
	$s_date = date("Y-m-01",strtotime($date));
	$e_date = date("Y-m-t",strtotime($date));
	switch($pos){
		case 0 : $pair_field = 'left_point';
				  break;
		case 1 : $pair_field = 'right_point';
				  break;
	}
	$sql = "select * from month_pair_point where user_id='$user_id' and date between '$s_date' and '$e_date'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$pair_id = $row['id'];
		}
		$sql = "update month_pair_point set  $pair_field = $pair_field + $amount 
				where id = '$pair_id' and user_id = '$user_id' ";
	}
	else{
		$sql = "insert into month_pair_point (user_id, $pair_field, date) values('$user_id','$amount','$date')";
	}
	query_execute_sqli($sql);
}
function Set_member_qualification($user_id,$club_business,$date){
	$sql = "select t1.*,
			case 
				WHEN least(t1.`left_point`,t1.`right_point`) >= $club_business[0] THEN 3
				WHEN least(t1.`left_point`,t1.`right_point`) >= $club_business[1] THEN 2
				WHEN least(t1.`left_point`,t1.`right_point`) >= $club_business[2] THEN 1
				ELSE 0
			end club_type	
			from month_pair_point t1
			inner join users t2 on t1.user_id = t2.id_user 
			where least(t1.`left_point`,t1.`right_point`) > 0 and t2.type='B' and t1.user_id='$user_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$club_type = $row['club_type'];
	}
	query_execute_sqli("update users set matching_qualification= $club_type where id_user=$user_id");
	$s_date = date("Y-m-01",strtotime($date));
	$e_date = date("Y-m-t",strtotime($date));
	$sql = "update month_pair_point set  matching_qualification = $club_type 
			where user_id = '$user_id' and date between '$s_date' and '$e_date' ";
	query_execute_sqli($sql);
}
function set_Next_Roi_Date($id){
	$sql = "select * from reg_fees_structure where user_id='$id' order by id desc limit 1";
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	if($cnt > 0){
		while($row = mysqli_fetch_array($query))
		{
			$start_date = $row['start_date'];
			$insert_id = $row['id'];
		}
		$ne_date = array();
		for($m = 0; $m < 15; $m++){
			$ne_date[] = date("Y-m-d",strtotime($start_date."+$m MONTH"));
		}
		$ne_date = implode(",",$ne_date);
		$sql = "UPDATE reg_fees_structure SET roi_date = '$ne_date' WHERE id=$insert_id";
		query_execute_sqli($sql);
	}
}

?>