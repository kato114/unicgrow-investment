<?php
include("../../../config.php");
include("../../../function/functions.php");
include("../../../function/setting.php");
$unit_amount =1;
$sql = "select * from trade_buy where type=2 order by id desc limit 1";
$que = query_execute_sqli($sql);

if($num > 0){
	while($ro = mysqli_fetch_array($que)){
		$unit_amount = $ro['unit_amount'];//share rate
	}
}
mysqli_free_result($que);

if($unit_amount > 0){
	$trade_for = 1;//buy share
	$ac_type = $acount_type[9];
	$ac_type_desc = $acount_type_desc[9];
	$sql = "select t1.id_user,t4.trade_gaming 'amount' from users t1
			left join reg_fees_structure t2 on t1.id_user = t2.user_id 
			left join trade_buy t3 on t1.id_user = t3.user_id and t3.type = 1
			left join wallet t4 on t1.id_user = t4.id 
			where t4.trade_gaming > 0 and DATE_ADD(t2.date,INTERVAL 7 DAY) < '$systems_date'  and t3.user_id is NULL";
	$sql = "select t1.id_user,t4.trade_gaming 'amount' from users t1
			left join trade_buy t3 on t1.id_user = t3.user_id and t3.type = 1
			left join wallet t4 on t1.id_user = t4.id 
			where t4.trade_gaming > 0 and t3.user_id is NULL";
	$que = query_execute_sqli($sql);
	$num = mysqli_num_rows($que);
	if($num > 0){
		while($ro = mysqli_fetch_array($que)){
			$user_id = $ro['id_user'];
			$amount = $ro['amount'];
			$no_share = (int) ($amount / $unit_amount);
			$investment = $unit_amount*$no_share;
			
			
			$sql = "update wallet set trade_gaming = trade_gaming - $investment where id='$user_id';";
			query_execute_sqli($sql);
			$num = query_affected_rows();
			if($num > 0){
				insert_wallet_account($user_id , $user_id , $investment , $systems_date_time , $ac_type , $ac_type_desc, 2 , get_user_allwallet($user_id,'trade_gaming'),$wallet_type[4],$remarks = "Trade Buy share");
				$sql = "insert into trade_buy set user_id='$user_id',unit_amount='$unit_amount',
					total_amount='$investment',share='$no_share',date=CONCAT('$systems_date_time','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),type='$trade_for'
					,gtb_balance='$amount'";
				query_execute_sqli($sql);
				$gmi = get_mysqli_insert_id();
				$trade_result = get_buy_trade($user_id,$unit_amount,$systems_date_time,$gmi,$no_share,$panel_by=2);
				if(!$trade_result['result']){
					get_buy_trade_byAdmin($user_id,$unit_amount,$systems_date_time,$gmi,$trade_result['total_share'],$panel_by=2);
				}
			}
		}
		print "success";
	}
	mysqli_free_result($que);
}


function get_buy_trade($user_id,$unit_amount,$date,$buy_id,$no_share,$panel_by){//debit
	include("setting.php");
	$trade_id = array();
	$process = 0;
	$total_share_buy = $old_no_share = $no_share;
	$result = array("result"=>false,"total_share"=>$total_share_buy);
	$sql = "select * from trade_buy where mode=0 and type=2 and unit_amount <= '$unit_amount' and user_id not in($user_id) order by date asc";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$remain_buy_share = 0;
			$sale_id = $row['id'];
			$sale_user_id = $row['user_id'];
			$sale_amount = $row['unit_amount'];
			$share = $row['share'];
			$buy_sale = "";
			if($no_share >= $share){
				$buy_share = $share;
				$no_share = $no_share - $share;
				$buy_sale = 1;//buy
			}
			else{
				$buy_share = $no_share;
				$no_share = $share - $no_share;
				$remain_buy_share = $share - $no_share;
				$buy_sale = 2;//sale
			}
			$trasaction_hash = get_unique_hashcode();
			$sql = "select * from trade_buy where mode=0 and id=$sale_id";
			$rqu = query_execute_sqli($sql);
			$rnum = mysqli_num_rows($rqu);
			mysqli_free_result($rqu);
			if($rnum > 0){
				$total_share_buy -= $buy_share;
				$cr_amount = $buy_share * $unit_amount;
				$trade_amount = $cr_amount;
				$level_amount = $trade_amount*$sale_share_deduction/100;
				$cr_amount = $trade_amount - $level_amount;
				$sql = "insert into trade_trasaction set buy_id='$buy_id',sale_id='$sale_id',sale_unit_amount='$sale_amount',buy_unit_amount='$unit_amount',share='$buy_share',trasaction_hash='$trasaction_hash', 	`date` =CONCAT('$date','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),level_amount=$level_amount,deduction=$sale_share_deduction;";
				query_execute_sqli($sql);
				$trade_id[] = get_mysqli_insert_id();
				$sql = "update trade_buy set mode=1 where id='$sale_id';";
				query_execute_sqli($sql);
				
				$sql = "update wallet set amount = amount + $cr_amount where id='$sale_user_id';";
				query_execute_sqli($sql);
				insert_wallet_account($sale_user_id , $sale_user_id , $cr_amount , $date , $acount_type[8] , $acount_type_desc[8], 1 , get_user_allwallet($sale_user_id,'amount'),$wallet_type[1],$remarks = "Trade Sale Share");
				
				$sql = "update wallet set owner_share = owner_share + $buy_share where id='$user_id';";
				query_execute_sqli($sql);
				insert_wallet_account($user_id , $user_id , $buy_share , $date , $acount_type[9] , $acount_type_desc[9], 1 , get_user_allwallet($user_id,'owner_share'),$wallet_type[5],$remarks = "Trade Buy Credit Owner Wallet");
				$process = 1;
				if($no_share > 0 and $buy_sale == 2){
					$sql = "insert into trade_buy 
					(user_id,gtb_balance,unit_amount,total_amount,share,pt_id,`date`,type,panel_id)
					select user_id,(select owner_share from wallet where id=$sale_user_id),unit_amount,(`unit_amount`*$no_share),$no_share,$sale_id,`date`,`type`,$panel_by from trade_buy where id=$sale_id;";
					query_execute_sqli($sql);
					
					$sql = "update trade_buy set share=$remain_buy_share,total_amount=unit_amount*$remain_buy_share where id='$sale_id' and $remain_buy_share > 0;";
					query_execute_sqli($sql);
					$no_share=0;break; 
				}
				if($no_share < 0)break; 
			}
		}
		
		$update_share = "";
		if($process == 1){
			if($no_share > 0){
				$sql = "insert into trade_buy 	
				(user_id,gtb_balance,unit_amount,total_amount,share,pt_id,`date`,type,panel_id)
				select user_id,(select trade_gaming from wallet where id='$user_id'),unit_amount,(`unit_amount`*$no_share),$no_share,$buy_id,`date`,`type`,$panel_by from trade_buy where id=$buy_id;";
				query_execute_sqli($sql);
			}
			$update_buy = "";
			$total_buy = $old_no_share - $total_share_sale;
			if($total_buy > 0){$update_buy = ",share=$total_buy,total_amount=unit_amount*$total_buy";}
			$sql = "update trade_buy set mode=1 $update_buy where id='$buy_id';";
			query_execute_sqli($sql);
			$result = array("result"=>true,"total_share"=>$total_share_buy);
		}
	}
	mysqli_free_result($query);
	//if(!empty($trade_id)){ trade_level_income($trade_id,$date);}
	return $result;
}

function get_buy_trade_byAdmin($user_id,$unit_amount,$date,$buy_id,$no_share,$panel_by){//debit
	include("setting.php");
	$trade_id = array();
	$process = 0;
	$total_share_buy = $old_no_share = $no_share;
	$cr_amount = $no_share * $unit_amount;
	$trade_amount = $cr_amount;
	$trasaction_hash = get_unique_hashcode();
	$sql = "insert into trade_trasaction set buy_id='$buy_id',sale_id='0',sale_unit_amount='$unit_amount',buy_unit_amount='$unit_amount',share='$no_share',trasaction_hash='$trasaction_hash', 	`date` =CONCAT('$date','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),level_amount=0,deduction=0;";
	query_execute_sqli($sql);
	$trade_id[] = get_mysqli_insert_id();
	$sql = "update trade_buy set mode=1 where user_id='$user_id' and type=1;";
	query_execute_sqli($sql);
	$sql = "update wallet set owner_share = owner_share + $no_share where id='$user_id';";
	query_execute_sqli($sql);
	insert_wallet_account($user_id , $user_id , $no_share , $date , $acount_type[9] , $acount_type_desc[9], 1 , get_user_allwallet($user_id,'owner_share'),$wallet_type[5],$remarks = "Trade Buy Credit Owner Wallet");
	
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