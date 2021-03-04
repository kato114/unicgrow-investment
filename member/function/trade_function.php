<?php
/*error_reporting(1);
include "../config.php";
include "../function/functions.php";
get_sale_trade($user_id=2,$unit_amount=1.99,$systems_date_time,$sale_id=1,$no_share=1,$panel_by=0);*/

function get_buy_trade($user_id,$unit_amount1,$date,$buy_id,$no_share,$panel_by,$by_wallet=false){//debit
	include("setting.php");
	$trade_id = array();
	$process = 0;
	$total_share_buy = $old_no_share = $no_share;
	$total_tx_amt = 0;
	$total_tx_share = 0;
	$result = array("result"=>false,"total_share"=>$total_share_buy,'total_tx_amt'=>$total_tx_amt,'total_tx_share'=>$total_tx_share);
	$unit_amount2 = floatval($unit_amount1);
	$sql = "select * from trade_buy where mode=0 and type=2 and unit_amount <= $unit_amount2 
			and user_id not in($user_id) order by unit_amount asc,id asc";//date asc,
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$remain_buy_share = 0;
			$sale_id = $row['id'];
			$sale_user_id = $row['user_id'];
			$sale_amount = $row['unit_amount'];
			$share = $row['share'];
			$wfield = $by_wallet == 1 ? 'trade_gaming' : 'amount';
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
				$cr_amount = $buy_share * $sale_amount;
				$total_tx_amt += $cr_amount;
				$total_tx_share += $buy_share;
				$trade_amount = $cr_amount;
				$level_amount = $trade_amount*$sale_share_deduction/100;
				$cr_amount = $trade_amount - $level_amount;
				
				$sql = "insert into trade_trasaction set buy_id='$buy_id',sale_id='$sale_id',sale_unit_amount='$sale_amount',buy_unit_amount='$unit_amount2',share='$buy_share',trasaction_hash='$trasaction_hash', 	`date` =CONCAT('$date','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),level_amount=$level_amount,deduction=$sale_share_deduction,tx_unit='$sale_amount';";
				query_execute_sqli($sql);
				$trade_id[] = get_mysqli_insert_id();
				$sql = "update trade_buy set mode=1 where id='$sale_id';";
				query_execute_sqli($sql);
				
				$sql = "update wallet set amount = amount + $cr_amount where id='$sale_user_id';";
				query_execute_sqli($sql);
				insert_wallet_account($sale_user_id , $sale_user_id , $cr_amount , $date , $acount_type[8] , $acount_type_desc[8], 1 , get_user_allwallet($sale_user_id,'amount'),$wallet_type[1],$remarks = "Trade Sale Share");
				if($by_wallet != 1){
					$sql = "update wallet set owner_share = owner_share + $buy_share where id='$user_id';";
					query_execute_sqli($sql);
					insert_wallet_account($user_id , $user_id , $buy_share , $date , $acount_type[9] , $acount_type_desc[9], 1 , get_user_allwallet($user_id,'owner_share'),$wallet_type[5],$remarks = "Trade Buy Credit Owner Wallet");
				}
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
			else{
				return $result = array("result"=>false,"total_share"=>$total_share_buy,"error"=>true,'total_tx_amt'=>$total_tx_amt,'total_tx_share'=>$total_tx_share);
			}
		}
		
		$update_share = "";
		if($process == 1){
			if($no_share > 0){
				$sql = "insert into trade_buy 	
				(user_id,gtb_balance,unit_amount,total_amount,share,pt_id,`date`,type,panel_id,`bywallet`,`hmode`,`udate`)
				select user_id,(select trade_gaming from wallet where id='$user_id'),unit_amount,(`unit_amount`*$no_share),$no_share,$buy_id,`date`,`type`,$panel_by,`bywallet`,`hmode`,`udate` from trade_buy where id=$buy_id;";
				query_execute_sqli($sql);
			}
			$update_buy = "";
			$total_buy = $old_no_share - $total_share_buy;
			if($total_buy > 0){
				$update_buy = ",share=$total_buy,total_amount=unit_amount*$total_buy";
			}
			$sql = "update trade_buy set mode=1 $update_buy where id='$buy_id';";
			query_execute_sqli($sql);
			$result = array("result"=>true,"total_share"=>$total_share_buy,'total_tx_amt'=>$total_tx_amt,'total_tx_share'=>$total_tx_share);
		}
	}
	mysqli_free_result($query);
	if(!empty($trade_id)){ trade_level_income($trade_id,$date);}
	return $result;
}

function get_sale_trade($user_id,$unit_amount3,$date,$sale_id,$no_share,$panel_by){//credit
	include "setting.php";
	$process = 0;
	$trade_id = array();
	$total_share_sale = $old_no_share = $no_share;
	$total_tx_amt = 0;
	$total_tx_share = 0;
	$result = array("result"=>false,"total_share"=>$total_share_sale,'total_tx_amt'=>$total_tx_amt,'total_tx_share'=>$total_tx_share);
	$unit_amount4 = floatval($unit_amount3);
	$sql = "select * from trade_buy where mode=0 and type=1 and unit_amount >= $unit_amount4 and 
			user_id not in($user_id)  order by unit_amount desc,id asc";//date asc,
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$remain_sale_share = 0;
			$buy_id = $row['id'];
			$buy_user_id = $row['user_id'];
			$buy_amount = $row['unit_amount'];
			$share = $row['share'];
			$bywallet = $row['bywallet'];
			$wfield = $by_wallet == 1 ? 'trade_gaming' : 'amount';
			$buy_sale = "";
			if($no_share >= $share){
				$sale_share = $share;
				$no_share = $no_share - $share;
				$buy_sale = 2;//sale
			}
			else{
				$sale_share = $no_share;
				$no_share = $share - $no_share;
				$remain_sale_share = $share - $no_share;
				$buy_sale = 1;//buy
			}
			$trasaction_hash = get_unique_hashcode();
			$sql = "select * from trade_buy where mode=0 and id=$buy_id";
			$rqu = query_execute_sqli($sql);
			$rnum = mysqli_num_rows($rqu);
			mysqli_free_result($rqu);
			if($rnum > 0){
				$total_share_sale -= $sale_share;
				$cr_amount = $sale_share;
				$trade_amount = $sale_share*$buy_amount;
				$total_tx_amt += $trade_amount;
				$total_tx_share += $sale_share;
				$level_amount = $trade_amount*$sale_share_deduction/100;
				$trade_amount = $trade_amount - $level_amount;
				
				$sql = "insert into trade_trasaction set buy_id='$buy_id',sale_id='$sale_id',sale_unit_amount='$unit_amount4',buy_unit_amount='$buy_amount',share='$sale_share',trasaction_hash='$trasaction_hash', 	date =CONCAT('$date','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),level_amount=$level_amount,deduction=$sale_share_deduction,tx_unit='$buy_amount';";
				query_execute_sqli($sql);
				$trade_id[] = get_mysqli_insert_id();
				$sql = "update trade_buy set mode=1 where id='$buy_id';";
				query_execute_sqli($sql);
				$sql = "update wallet set amount = amount + ".($trade_amount)." where id='$user_id';";
				query_execute_sqli($sql);
				insert_wallet_account($user_id , $user_id , ($trade_amount) , $date , $acount_type[8] , $acount_type_desc[8], 1 , get_user_allwallet($user_id,'amount'),$wallet_type[1],$remarks = "Trade Sale Credit Cash Wallet");
				if($bywallet != 1){
					$sql = "update wallet set owner_share = owner_share + $sale_share where id='$buy_user_id';";
					query_execute_sqli($sql);
					insert_wallet_account($buy_user_id , $buy_user_id , $sale_share , $date , $acount_type[9] , $acount_type_desc[9], 1 , get_user_allwallet($buy_user_id,'owner_share'),$wallet_type[5],$remarks = "Trade Buy  Credit Owner Wallet");
				}
				$process = 1;
				if($no_share > 0 and $buy_sale == 1){
					$sql = "insert into trade_buy 
					(user_id,gtb_balance,unit_amount,total_amount,share,pt_id,`date`,type,panel_id,`bywallet`,`hmode`,`udate`)
					select user_id,(select trade_gaming from wallet where id=$buy_user_id),unit_amount,(`unit_amount`*$no_share),$no_share,$buy_id,`date`,`type`,$panel_by,`bywallet`,`hmode`,`udate` from trade_buy where id=$buy_id;";
					query_execute_sqli($sql);
					$sql = "update trade_buy set share=$remain_sale_share,total_amount=unit_amount*$remain_sale_share where id='$buy_id';";
					query_execute_sqli($sql);
					$no_share=0;break; 
				}
				if($no_share < 0)break; 
			}
			else{
				return $result = array("result"=>false,"total_share"=>$total_share_sale,"error"=>true,'total_tx_amt'=>$total_tx_amt,'total_tx_share'=>$total_tx_share);
			}
		}
		
		$update_share = "";
		if($process == 1){
			if($no_share > 0){
				$sql = "insert into trade_buy 
				(user_id,gtb_balance,unit_amount,total_amount,share,pt_id,`date`,type,panel_id)
				select user_id,(select trade_gaming from wallet where id='$user_id'),unit_amount,(`unit_amount`*$no_share),$no_share,$sale_id,`date`,`type`,$panel_by from trade_buy where id=$sale_id;";
				query_execute_sqli($sql);
			}
			$update_sale = "";
			$total_sale = $old_no_share - $total_share_sale;
			if($total_sale > 0){
				$update_sale = ",share=$total_sale,total_amount=unit_amount*$total_sale";}
				$sql = "update trade_buy set mode=1 $update_sale where id='$sale_id';";
				query_execute_sqli($sql);
				$result = array("result"=>true,"total_share"=>$total_share_sale,'total_tx_amt'=>$total_tx_amt,'total_tx_share'=>$total_tx_share);
		}
	}
	mysqli_free_result($query);
	if(!empty($trade_id)){ trade_level_income($trade_id,$date);}
	return $result;
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
function get_live_trade(){
?>
<div id="live_trade"><B class='text-danger'>Page Loading...</B></div>
<script>
$(document).ready(function() {
   $.ajaxSetup({ cache: false });
   var container = $("#live_trade");
	container.load('function/live_trade.php');
	var refreshId = setInterval(function(){
		container.load('function/live_trade.php');
	}, 9000);
});
</script>
 <?php 
}
function trade_level_income( $trade_id = array(),$date = false ){
	include("setting.php");

	$trade_id = implode(",",$trade_id);
	$sql = "select t1.id,t1.level_amount,t2.user_id from trade_trasaction t1
			left join trade_buy t2 on t1.sale_id = t2.id
			where t1.id in($trade_id) and t1.level_mode=0";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$sale_user_id = $user_id = $row['user_id'];
			$level_amount = $row['level_amount'];
			for($i = 1; $i <= count($trade_level_percent); $i++){
				$user_id = real_par($user_id);
				$income = $level_amount * $trade_level_percent[$i-1] / 100;
				$wle = week_lottery_exist($user_id,$date);
				$income = $income / $wle;
				if($income > 0 and $user_id > 0 and get_type_user($user_id) == "B" and check_member_active_by_package($user_id)){
					$sql = "insert into income (user_id , amount , date , type , incomed_id,plan,level ) values ('$user_id' , '$income' , '$date' , '$income_type[9]','$sale_user_id',$id,".($i).") ";
					query_execute_sqli($sql);
					$m_wallet = $income * $wallet_dividend[0] /100;
					query_execute_sqli("UPDATE wallet SET amount = amount + '$m_wallet' , date = '$date' WHERE id = '$user_id' ");
					insert_wallet_account($user_id , $sale_user_id , $m_wallet , $date , $acount_type[12] , $acount_type_desc[12], 1 , get_user_allwallet($user_id,'amount'),$wallet_type[1],$remarks = "Trade Level Income");
					$m_wallet = $income * $wallet_dividend[1] /100;
					query_execute_sqli("UPDATE wallet SET trade_gaming = trade_gaming + '$m_wallet' , date = '$date' WHERE id = '$user_id' ");
					insert_wallet_account($user_id , $sale_user_id , $m_wallet , $date , $acount_type[12] , $acount_type_desc[12], 1 , get_user_allwallet($user_id,'trade_gaming'),$wallet_type[4],$remarks = "Trade Level Income");
				}
			}
			$income = $level_amount * $admin_trade_level_percent / 100;
			if($income > 0){
				$user_id = 1;
				$sql = "insert into income (user_id , amount , date , type , incomed_id,plan,level ) values ('$user_id' , '$income' , '$date' , '$income_type[9]','$sale_user_id',$id,6) ";
				query_execute_sqli($sql);
				$m_wallet = $income * $wallet_dividend[0] /100;
				query_execute_sqli("UPDATE wallet SET amount = amount + '$m_wallet' , date = '$date' WHERE id = '$user_id' ");
				insert_wallet_account($user_id , $sale_user_id , $m_wallet , $date , $acount_type[12] , $acount_type_desc[12], 1 , get_user_allwallet($user_id,'amount'),$wallet_type[1],$remarks = "Admin Trade Level Income");
				$m_wallet = $income * $wallet_dividend[1] /100;
				query_execute_sqli("UPDATE wallet SET trade_gaming = trade_gaming + '$m_wallet' , date = '$date' WHERE id = '$user_id' ");
				insert_wallet_account($user_id , $sale_user_id , $m_wallet , $date , $acount_type[12] , $acount_type_desc[12], 1 , get_user_allwallet($user_id,'trade_gaming'),$wallet_type[4],$remarks = "Admin Trade Level Income");
			}
			$sql = "update trade_trasaction set level_mode=1 where id=$id and level_mode=0";
			query_execute_sqli($sql);
		}
	}
	mysqli_free_result($query);
}
?>