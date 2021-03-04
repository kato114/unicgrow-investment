<?php
include("../../../config.php");
include("../../../function/functions.php");
include("../../../function/setting.php");
$day = date("l",strtotime($systems_date_time));
if($day == $lottery_result_day){
	$last_week = get_pre_nxt_date(date("Y-m-d",strtotime($systems_date." +1 day")) , $lottery_result_day);
	/*$sql = "select id_user,t3.amount,t3.trade_gaming from users t1
			left join lottery_ticket t2 on t1.id_user = t2.user_id and
			DATE_FORMAT(t2.rdate,'%Y-%m-%d') between '".$last_week[0]."' and '".$last_week[1]."'
			left join wallet t3 on t1.id_user = t3.id
			where t3.amount < $lottery_amount and (t3.amount+t3.trade_gaming >=$lottery_amount)
			and t2.user_id is NULL
			group by t1.id_user";*/
	$sql = "SELECT * FROM `wallet` WHERE `id` NOT IN (SELECT user_id FROM `lottery_ticket` WHERE DATE_FORMAT(rdate,'%Y-%m-%d') between '".$last_week[0]."' and '".$last_week[1]."' group by user_id) AND `amount` < $lottery_amount and (amount+trade_gaming >=$lottery_amount)";
	$que = query_execute_sqli($sql);
	$num = mysqli_num_rows($que);
	if($num > 0){
		while($ro = mysqli_fetch_array($que)){
			$user_id = $ro['id'];
			$cash_wal = $ro['amount'];
			
			$new_amt = $lottery_amount - $cash_wal;
			$sql = "update wallet set amount = amount + '$new_amt' where id='$user_id'";
			query_execute_sqli($sql);
			$sql = "update wallet set trade_gaming = trade_gaming - '$new_amt' where id='$user_id'";
			query_execute_sqli($sql);
			insert_wallet_account($user_id , $user_id , $new_amt , $systems_date_time , $acount_type[18] ,$acount_type_desc[18], $mode=1 ,get_user_allwallet($user_id,'amount'),$wallet_type[1],$remarks = "Credit Deposit Wallet For SMG WALLET Transfer Auto");
			insert_wallet_account($user_id , $user_id , $new_amt , $systems_date_time , $acount_type[19] ,$acount_type_desc[19], $mode=2 ,get_user_allwallet($user_id,'trade_gaming'),$wallet_type[4],$remarks = "Debit SMG WALLET For Deposit Wallet Transfer Auto");
		}
		print "Wallet Transfer To $num Member Success";
	}
	mysqli_free_result($que);
}
else{
	print "Today Can't Transfer SMG Wallet To Cash !!";
}

