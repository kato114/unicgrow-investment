<?php
include("../../../config.php");
include("../../../function/functions.php");
include("../../../function/setting.php");
include("../../../function/direct_income.php");

$day = date("l",strtotime($systems_date_time));
if($day == $lottery_result_day){
	$last_week = get_pre_nxt_date(date("Y-m-d",strtotime($systems_date." +1 day")) , $lottery_result_day);
	/*$sql = "select id_user from users t1
			left join lottery_ticket t2 on t1.id_user = t2.user_id and
			DATE_FORMAT(t2.rdate,'%Y-%m-%d') between '".$last_week[0]."' and '".$last_week[1]."'
			left join wallet t3 on t1.id_user = t3.id
			where t3.amount >= $lottery_amount and t2.user_id is NULL
			group by t1.id_user";*/
	$sql = "SELECT * FROM `wallet` WHERE `id` NOT IN (SELECT user_id FROM `lottery_ticket` WHERE DATE_FORMAT(rdate,'%Y-%m-%d') between '".$last_week[0]."' and '".$last_week[1]."' group by user_id) AND `amount` >= $lottery_amount ";
	$que = query_execute_sqli($sql);
	$num = mysqli_num_rows($que);
	if($num > 0){
		while($ro = mysqli_fetch_array($que)){
			$user_id = $ro['id'];
			$sql = "update wallet set amount = amount - '$lottery_amount' where id='$user_id'";
			query_execute_sqli($sql);
			$date = date("Y-m-d H:i:s",strtotime($systems_date_time." +1 Week"));
			get_weekly_lottery_ticket($user_id,$date,$nolb=1,$type=4,$systems_date_time);
			insert_wallet_account($user_id , $user_id , $lottery_amount , $systems_date_time , $acount_type[39] ,$acount_type_desc[4], $mode=2 ,get_user_allwallet($user_id,'amount'),$wallet_type[2],$remarks = "Debit Fund For Buy Ticket");
		}
		print "Success";
	}
	else{
		print "Today Already Buy Lottery !!";
		$sql = "SELECT user_id,SUM(amount) amt,date FROM lottery_ticket WHERE li_mode=0 GROUP BY user_id";//t1.id in($trade_id) and
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num > 0){
			while($row = mysqli_fetch_array($query)){
				$user_id = $row['user_id'];
				$incomes = $row['amt'];
				$date = $row['date'];
				linkup_level_income($user_id,$date,$incomes);
				query_execute_sqli("UPDATE lottery_ticket SET li_mode = 1 WHERE li_mode=0 and user_id='$user_id' ");
			}
			print "Linkup Bonus Distributed !!";
		}
		else{
			print "Linkup Bonus Already Distributed !!";
		}
	}
	mysqli_free_result($que);
}
else{
	print "Today Can't Buy Lottery !!";
}
