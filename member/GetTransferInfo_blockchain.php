<?php
/*
	CoinPayments.net API Example
	Copyright 2014 CoinPayments.net. All rights reserved.	
	License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.txt
*/
session_start();
ini_set('display_errors','on');
require('config.php');
include("function/setting.php");
include("function/functions.php");
include("function/send_mail.php");
include("create_withdrawal/coinpayments.inc.php");
include("function/blockchain_trasaction.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];
extract($_REQUEST);
$time = date("Y-m-d H:i:s");
$investment = $_SESSION['investment'];
$currency_sign = $payment_method == 1 ? 'Bitcoin' : 'ETH';

if($investment > 0){
	if(!isset($_SESSION['session_user_investment']) and $ct == 0){	
		$_SESSION['session_user_investment'] = 1;
		$investment = $_SESSION['investment'];
		$txn_id = $_SESSION['bitcoin_txn_id'];
		$tkey = $_SESSION['bitcoin_tkey'];
		$sql = "INSERT INTO `request_crown_wallet`(`ac_type`,`user_id`, `plan_id`,`investment`,`request_crowd`,
		 `bitcoin_address`,`reqid`, `description`,`date`,`c_by`,`transaction_id`,`tkey`) 
		 VALUES ('$payment_method','$login_id','0','$investment','$val','$address','0','".$currency_sign." DEPOSIT','$systems_date_time','$payment_method','$txn_id','$tkey')"; 
		query_execute_sqli($sql);
	}
	
	if(strtoupper($soft_chk) == "LIVE"){
    	$chk_result = chk_txs_blockchain($address,$val,$systems_date_time);
		if($chk_result[0] == 1){
			$hash = $chk_result[1];
			$tx_time = $chk_result[2];
			$confirmation = $chk_result[3];
			$value = $chk_result[4];
			$script = $chk_result[5];
			$r_addr = $chk_result[6];
			$chk_result = 
				array(
					'error'=>'ok','result'=>
						array(
							'status'=>100,
							'time_completed'=>$tx_time,
							'receivedf'=>$value,
							'hash'=>$hash
						)
				);
		}
		else{
			$chk_result = array('error'=>'ok','result'=>array('status'=>2));
		}
	}
	else{
		$chk_result = array('error'=>'ok','result'=>array('status'=>100));
	}
	 
	$cr = $chk_result['error'];
	if($cr == 'ok'){
	    $txn_id = $_SESSION['bitcoin_txn_id'];
		$sql = "select * from request_crown_wallet where user_id='$login_id'  order by id desc limit 1";
		
		$query = query_execute_sqli($sql);
		if(mysqli_num_rows($query) > 0){
			while($row = mysqli_fetch_array($query)){
				$pid = $row['id'];
				$status = $row['status'];
				$mining_space = $row['request_crowd'];
			}
			if($status == 0)
				$result = check_trasaction($pid,$systems_date_time,$chk_result);
			if($status == 1){
				$result[0] = 2;
				$result[7] = $pid;
				$result[4] = $mining_space ;
			}
			$currency_sign = $payment_method == 1 ? 'Bitcoin' : 'ETH';
			if($result[0] == 2){
				
				//$plan_id = $result[7];
				$msg= "<B style='color:#178102;'>\"Congratulations !! Your payment is confirmed!\"<br>";
				$msg.="<B style='color:#178102;'>\"It`s Credited Fund TO CASH Wallet.\"</B><br>";
				$done=1;
			}
			elseif($status == 0){
				$msg = "Wait For Confirmation !!</B>";
				$done=0;
			}
			
			$obj = array('info'=>$msg,'result'=>$done);
			echo $obj = json_encode($obj);
			die();
		}
	}
	else{
		$obj = array('info'=>"API GOT AN ERROR",'result'=>0);
		echo $obj = json_encode($obj);
	}
}
else{
	$obj = array('info'=>"Try Again Later...",'result'=>0);
	echo $obj = json_encode($obj);
}
include "free_up_memory.php";
?>

<!--
*****CreateTransaction*****
Array ( [error] => ok [result] => Array ( [amount] => 0.53679619 [txn_id] => CPDK1MKULMNSN6AIIUEV9OVZUB [address] => 0x4ac51ca50b57a29ddc5263ed110cef8838ae9c36 [confirms_needed] => 3 [timeout] => 86400 [checkout_url] => https://www.coinpayments.net/index.php?cmd=checkout&id=CPDK1MKULMNSN6AIIUEV9OVZUB&key=c25054ce396f64363b95dfed7b0129f1 [status_url] => https://www.coinpayments.net/index.php?cmd=status&id=CPDK1MKULMNSN6AIIUEV9OVZUB&key=c25054ce396f64363b95dfed7b0129f1 [qrcode_url] => https://www.coinpayments.net/qrgen.php?id=CPDK1MKULMNSN6AIIUEV9OVZUB&key=c25054ce396f64363b95dfed7b0129f1 ) ) 

*****GetTxInfo*****
Array ( [error] => ok [result] => Array ( [time_created] => 1573543873 [time_expires] => 1573630273 [status] => 0 [status_text] => Waiting for buyer funds... [type] => coins [coin] => ETH [amount] => 53679619 [amountf] => 0.53679619 [received] => 0 [receivedf] => 0.00000000 [recv_confirms] => 0 [payment_address] => 0x4ac51ca50b57a29ddc5263ed110cef8838ae9c36 ) ) 
-->