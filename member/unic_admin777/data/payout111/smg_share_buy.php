<?php
include("../../../config.php");
include("../../../function/functions.php");
include("../../../function/setting.php");
include("../../../function/direct_income.php");

$unit_amount = $amount =0;
$sql = "select * from trade_buy where type=2 order by id desc limit 1";
$que = query_execute_sqli($sql);
$num = mysqli_num_rows($que);
if($num > 0){
	while($ro = mysqli_fetch_array($que)){
		$unit_amount = $ro['unit_amount'];//share rate
	}
}
mysqli_free_result($que);
/*$sql = "select t1.id_user,t4.trade_gaming 'amount' from users t1
		left join trade_buy t3 on t1.id_user = t3.user_id and t3.type = 1 and t3.date like '%$systems_date%'
		left join wallet t4 on t1.id_user = t4.id 
		where t4.trade_gaming > 0 and t3.user_id is NULL";*/
$sql = "SELECT * FROM `wallet` WHERE `id` NOT IN (SELECT user_id FROM `trade_buy` WHERE date like '%$systems_date%' group by user_id) AND trade_gaming > 0";
$que = query_execute_sqli($sql);

$num = mysqli_num_rows($que);
if($num > 0){
	while($ro = mysqli_fetch_array($que)){
		$user_id = $ro['id'];
		$amount = $ro['trade_gaming'];
		$trade_result = get_buy_trade($user_id,$systems_date_time,$amount,$panel_by=3,$by_wallet=1);
		if($trade_result['result'] and $unit_amount > 0 and $trade_result['amount'] > 0 and $trade_result['amount']>=$unit_amount ){
			get_buy_trade_byAdmin($user_id,$unit_amount,$systems_date_time,$trade_result['amount'],$panel_by=4,$by_wallet=1);
		}
	}
	print "success";
}

	


mysqli_free_result($que);



function get_buy_trade($user_id,$date,$amount,$panel_by,$by_wallet = false){//debit
	include("../../../function/setting.php");
	global $systems_date_time;
	$process = 0;
	$udate = date("Y-m-d H:i:s",strtotime($systems_date_time."+ $share_sale_day DAY"));
	$result = array("result"=>true,"amount"=>$amount);
	$trade_id = array();
	$sql = "select * from trade_buy where mode=0 and type=2 and user_id not in($user_id) order by unit_amount asc";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$remain_buy_share = 0;
			$sale_id = $row['id'];
			$sale_user_id = $row['user_id'];
			$share = $row['share'];
			$unit_amount = $sale_amount = $row['unit_amount'];
			$no_share = (int) ($amount/$unit_amount);
			
			if($no_share > 0 and $amount > 0 ){
				$share = $row['share'];
				$buy_sale = "";
				if($no_share >= $share){
					$buy_share = $share;
					$no_share = $no_share - $share;
					$trade_complete = true;//sale
				}
				else{
					$buy_share = $no_share;
					$no_share = $share - $no_share;
					$remain_buy_share = $share - $no_share;
					$trade_complete = false;
				}
				
				$trasaction_hash = get_unique_hashcode();
				$sql = "select * from trade_buy where mode=0 and id=$sale_id";
				$rqu = query_execute_sqli($sql);
				$rnum = mysqli_num_rows($rqu);
				mysqli_free_result($rqu);
				if($rnum > 0){
					$buy_amount = $cr_amount = $buy_share * $unit_amount;
					$trade_amount = $cr_amount;
					$level_amount = $trade_amount*$sale_share_deduction/100;
					$cr_amount = $trade_amount - $level_amount;
					
					//$level_amount = $trade_amount*$sale_share_deduction/100;
					//$cr_amount = $trade_amount - $level_amount;
					$wallet_bal = get_user_allwallet($user_id,'trade_gaming');
					$sql = "insert into trade_buy set user_id='$user_id',unit_amount='$unit_amount',
					total_amount='$trade_amount',share='$buy_share',date=CONCAT('$systems_date_time','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),type='1'
					,gtb_balance='$wallet_bal',panel_id=$panel_by,`bywallet`=1,`udate`='$udate',mode = 1";
					query_execute_sqli($sql);
					$buy_id = get_mysqli_insert_id();
					
					$sql = "update wallet set trade_gaming = trade_gaming - $trade_amount where id='$user_id';";
					query_execute_sqli($sql);
					insert_wallet_account($user_id , $user_id , $trade_amount , $systems_date_time , $acount_type[9] , $acount_type_desc[9], 2 , get_user_allwallet($user_id,'trade_gaming'),$wallet_type[4],$remarks = "Trade Buy share Debit SMG Wallet");
					
					$sql = "insert into trade_trasaction set buy_id='$buy_id',sale_id='$sale_id',sale_unit_amount='$sale_amount',buy_unit_amount='$unit_amount',share='$buy_share',trasaction_hash='$trasaction_hash', 	`date` =CONCAT('$date','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),level_amount='$level_amount',deduction='$sale_share_deduction',tx_unit='$unit_amount';";
					query_execute_sqli($sql);
					$trade_id[] = get_mysqli_insert_id();
					//if(!$trade_complete){
						$sql = "update trade_buy set mode=1 where id='$sale_id';";
						query_execute_sqli($sql);
					//}
					
					$sql = "update wallet set amount = amount + $cr_amount where id='$sale_user_id';";
					query_execute_sqli($sql);
					insert_wallet_account($sale_user_id , $sale_user_id , $cr_amount , $date , $acount_type[8] , $acount_type_desc[8], 1 , get_user_allwallet($sale_user_id,'amount'),$wallet_type[1],$remarks = "Trade Sale Share");
					if($by_wallet != 1){
						$sql = "update wallet set owner_share = owner_share + $buy_share where id='$user_id';";
						query_execute_sqli($sql);
						insert_wallet_account($user_id , $user_id , $buy_share , $date , $acount_type[9] , $acount_type_desc[9], 1 , get_user_allwallet($user_id,'owner_share'),$wallet_type[5],$remarks = "Trade Buy Credit Owner Wallet");
					}
					$process = 1;
					if($no_share > 0 and !$trade_complete){
						$sql = "insert into trade_buy 
						(user_id,gtb_balance,unit_amount,total_amount,share,pt_id,`date`,type,panel_id)
						select user_id,(select owner_share from wallet where id=$sale_user_id),unit_amount,(`unit_amount`*$no_share),$no_share,$sale_id,`date`,`type`,$panel_by from trade_buy where id=$sale_id;";
						query_execute_sqli($sql);
						
						$sql = "update trade_buy set share=$remain_buy_share,total_amount=unit_amount*$remain_buy_share where id='$sale_id' and $remain_buy_share > 0;";
						query_execute_sqli($sql);
						
					}
					$amount = $amount - $trade_amount;
				}
			}
			else{
				$result = array("result"=>true,"amount"=>$amount);
				return $result;
			}
		}
	}
		
	mysqli_free_result($query);
	$result = array("result"=>$amount > 0?true:false,"amount"=>$amount);
	return $result;
}

function get_buy_trade_byAdmin($user_id,$unit_amount,$date,$amount,$panel_by,$by_wallet = false){//debit
	include("../../../function/setting.php");
	global $systems_date_time;
	$udate = date("Y-m-d H:i:s",strtotime($systems_date_time."+ $share_sale_day DAY"));
	$no_share = (int) ($amount / $unit_amount);
	if($no_share < 0 or $amount < 0)return false;
	$cr_amount = $no_share * $unit_amount;
	$trade_amount = $cr_amount;
	
	$wallet_bal = get_user_allwallet($user_id,'trade_gaming');
	$sql = "insert into trade_buy set user_id='$user_id',unit_amount='$unit_amount',
	total_amount='$cr_amount',share='$no_share',date=CONCAT('$systems_date_time','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),type='1'
	,gtb_balance='$wallet_bal',panel_id=$panel_by,`bywallet`=1,`udate`='$udate'";
	query_execute_sqli($sql);
	$buy_id = get_mysqli_insert_id();
	
	$wallet_bal = get_user_allwallet(1,'amount');
	$sql = "insert into trade_buy set user_id='1',unit_amount='$unit_amount',
	total_amount='$cr_amount',share='$no_share',date=CONCAT('$systems_date_time','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),type='2'
	,gtb_balance='$wallet_bal',panel_id=$panel_by,`bywallet`=11,`mode`=1";
	query_execute_sqli($sql);
	$sale_id = get_mysqli_insert_id();
	
	$sql = "update wallet set amount = amount + $cr_amount where id='1';";
	query_execute_sqli($sql);
	insert_wallet_account(1 , 1 , $cr_amount , $date , $acount_type[8] , $acount_type_desc[8], 1 , get_user_allwallet(1,'amount'),$wallet_type[1],$remarks = "Trade Sale Share By Admin Vigilance");
	
	$sql = "update wallet set trade_gaming = trade_gaming - $cr_amount where id='$user_id';";
	query_execute_sqli($sql);
	insert_wallet_account($user_id , $user_id , $cr_amount , $systems_date_time , $acount_type[9] , $acount_type_desc[9], 2 , get_user_allwallet($user_id,'trade_gaming'),$wallet_type[4],$remarks = "Trade Buy share Debit SMG Wallet");
	
	$trasaction_hash = get_unique_hashcode();
	$sql = "insert into trade_trasaction set buy_id='$buy_id',sale_id='$sale_id',sale_unit_amount='$unit_amount',buy_unit_amount='$unit_amount',share='$no_share',trasaction_hash='$trasaction_hash', 	`date` =CONCAT('$date','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),level_amount=0,deduction=0,tx_unit='$unit_amount';";
	query_execute_sqli($sql);
	$trade_id[] = get_mysqli_insert_id();
	$sql = "update trade_buy set mode=1 where user_id='$user_id' and id=$buy_id;";
	query_execute_sqli($sql);
	if($by_wallet != 1){
		$sql = "update wallet set owner_share = owner_share + $no_share where id='$user_id';";
		query_execute_sqli($sql);
		insert_wallet_account($user_id , $user_id , $no_share , $date , $acount_type[9] , $acount_type_desc[9], 1 , get_user_allwallet($user_id,'owner_share'),$wallet_type[5],$remarks = "Trade Buy Credit Owner Wallet");
	}
	return true;
}

function get_unique_hashcode(){
	do{
		$trasaction_hash = md5(mt_rand(100000,999999));
		$sql = "select trasaction_hash from trade_trasaction where trasaction_hash='$trasaction_hash'";
		$que = query_execute_sqli($sql);
		$num2 = mysqli_num_rows($que);
		mysqli_free_result($que);
	}while($num2 > 0);
	return $trasaction_hash;
}