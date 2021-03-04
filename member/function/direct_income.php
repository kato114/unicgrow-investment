<?php
function get_direct_income($id,$date,$inv_id,$registration_fees=false){
	include("setting.php");
	$real_parent = real_par($id);
	$query = query_execute_sqli("select * from reg_fees_structure where user_id = '$id' and mode=1 and update_fees > 0 order by id desc limit 1 ");
	while($row = mysqli_fetch_array($query))
	{
		$registration_fees = $row['update_fees']; 
		$invest_type_id = $row['invest_type'];
	}
	mysqli_free_result($query);
	
	$income = $registration_fees * $set_referral_bonus[$invest_type_id-1] / 100;
	$query = query_execute_sqli("select * from reg_fees_structure where user_id = '$real_parent' and mode=1 order by id desc limit 1 ");
	$num = mysqli_num_rows($query);
	mysqli_free_result($query);
	
	if($income > 0 and $real_parent > 0 and get_type_user($real_parent) == "B" and $num > 0){
		$sql = "insert into income (user_id , amount , date , type , incomed_id,token_rate ) values ('$real_parent' , '$income' , '$date' , '$income_type[3]','$inv_id','$token_rate') ";
		query_execute_sqli($sql);
		$m_wallet = round($income / $token_rate,2);
		query_execute_sqli("UPDATE wallet SET amount = amount + '$m_wallet' , date = '$date' WHERE id = '$real_parent' ");
		insert_wallet_account($real_parent , $id , $m_wallet , $date , $acount_type[1] , $acount_type_desc[1], 1 , get_user_allwallet($real_parent,'amount'),$wallet_type[1],$remarks = "Referral Income");
		/*$m_wallet = $income * $wallet_dividend[1] /100;
		query_execute_sqli("UPDATE wallet SET trade_gaming = trade_gaming + '$m_wallet' , date = '$date' WHERE id = '$real_parent' ");
		insert_wallet_account($real_parent , $id , $m_wallet , $date , $acount_type[1] , $acount_type_desc[1], 1 , get_user_allwallet($real_parent,'trade_gaming'),$wallet_type[4],$remarks = "Referral Income");*/
	} 
}

function get_level_income($request_user_id,$systems_date_time,$insert_id){
	global $systems_date_time;global $systems_date;
	include("setting.php");
	$user_id = $request_user_id;
	$query = query_execute_sqli("select * from reg_fees_structure where user_id = '$user_id' and mode in(1,2) and update_fees > 0 order by id desc limit 1 ");
	while($row = mysqli_fetch_array($query)){
		$bonus = $row['update_fees'];
	}
	$num = mysqli_num_rows($query);
	mysqli_free_result($query);
	for($i = 0; $i < count($level_income_setting); $i++){
		$user_id = real_par($user_id);
		$income = $bonus * $level_income_setting[$i]/100;
		
		$query = query_execute_sqli("select * from reg_fees_structure where user_id = '$user_id' and mode in(1,2) and update_fees > 0 order by id desc limit 1 ");
		$num = mysqli_num_rows($query);
		mysqli_free_result($query);
		
		if($income > 0 and get_type_user($user_id) == "B" and $num > 0 ){
			$income = $income;
			$sql = "insert into income (user_id , amount , date , type , incomed_id, level,token_rate ) values ('$user_id' , '$income' , '$systems_date_time' , '$income_type[2]','$insert_id', ".($i+1).",$token_rate)";
			query_execute_sqli($sql);
			$m_wallet = $income;
			update_member_wallet($user_id,$income,$income_type[2]);
			insert_wallet_account($user_id , $ruser_id , $m_wallet , $systems_date_time , $acount_type[1] , $acount_type_desc[1], 1 , get_user_allwallet($user_id,'amount'),$wallet_type[1],$remarks = "Level Bonus");
		}
	}
}
function set_level_binary_bonus($user_id,$bonus,$date){
    include("setting.php");
	$income_date_dist = date("Y-m-d", strtotime($date."-1 day") );
	$sys_date = $income_dates = $date; //date("Y-m-d");
	$week_pn = get_pre_nxt_date($date , $binary_pay_day);
	$previous_date = $week_pn[0];
	$next_date = $week_pn[1];
	$ruser_id = $user_id;
	for($i = 1; $i < 4; $i++){
		$user_id = real_par($user_id);
		$income = $bonus * $level_income[$i-1]/100;
		$uap = user_active_plan($user_id);
		
		$query = query_execute_sqli("select * from reg_fees_structure where user_id = '$user_id' and mode=1 and update_fees > 0 order by id desc limit 1 ");
		$num = mysqli_num_rows($query);
		mysqli_free_result($query);
		
		if($income > 0 and get_type_user($user_id) == "B" and $num > 0 ){
			$income = $income;
			$sql = "insert into income (user_id , amount , date , type , incomed_id, level,token_rate ) values ('$user_id' , '$income' , '$date' , '$income_type[6]','$ruser_id', $i,$token_rate)";
			query_execute_sqli($sql);
			$m_wallet = $income / $token_rate;
			query_execute_sqli("UPDATE wallet SET amount = amount + '$m_wallet' , date = '$date' WHERE id = '$user_id' ");
			insert_wallet_account($user_id , $ruser_id , $m_wallet , $date , $acount_type[3] , $acount_type_desc[3], 1 , get_user_allwallet($user_id,'amount'),$wallet_type[1],$remarks = "Level Growth Bonus");
		}
	}
}


function check_member_active_by_package($user_id){
	$active = false;
	$sql = "select * from reg_fees_structure where user_id = '$user_id' and mode=1 order by invest_type desc limit 1 ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		$active = true;
	}
	mysqli_free_result($query);
	return $active;
}

function check_member_qualify_binary($user_id,$date){
	$qualify = false;
	$sql = "select * from users where id_user = '$user_id' and ((l_lps > 1 and r_lps >=1) or (l_lps >= 1 and r_lps >1)) and step=1 ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		$qualify = true;
	}
	mysqli_free_result($query);
	return $qualify;
}