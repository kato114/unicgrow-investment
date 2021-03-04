<?php
session_start();
ini_set('display_errors','on');
require('config.php');
include("function/setting.php");
include("function/functions.php");
include("function/send_mail.php");
include("function/blockchain_trasaction.php");
include("create_withdrawal/coinpayments.inc.php");

include("vendor/autoload.php");

use IEXBase\TronAPI\Exception\TronException;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Tron;

$provider_url = 'https://api.trongrid.io';//https://api.trongrid.io;

$fullNode = new HttpProvider($provider_url);
$solidityNode = new HttpProvider($provider_url);
$eventServer = new HttpProvider($provider_url);

try {
    $tron = new Tron($fullNode, $solidityNode, $eventServer);
} catch (TronException $e) {
    exit($e->getMessage());
}

$login_id = $id = $_SESSION['mlmproject_user_id'];
extract($_REQUEST);
$time = date("Y-m-d H:i:s");
$investment = $_SESSION['investment'];
$currency_sign = 'Tron';
if($investment > 0){
	if(strtoupper($soft_chk) == "LIVE"){
	    if(!isset($_SESSION['session_user_investment']) and $ct == 0){	
		    $_SESSION['session_user_investment'] = 1;
		    $sql = "INSERT INTO `user_gen_addrs`(`payment_mode`,`user_id`, `address`,`amount`,`investment`, `date`,rate) 
        		 VALUES ('$payment_method','$login_id','$address','$val','$investment','$systems_date_time','$crate')"; 
        	query_execute_sqli($sql);
        	$sql = "INSERT INTO `request_crown_wallet`(`ac_type`,`user_id`, `plan_id`,`investment`,`request_crowd`,
			 `bitcoin_address`,`reqid`, `description`,`date`,`c_by`,`transaction_id`,`tkey`,`bitcoin`) 
			 VALUES ('$payment_method','$login_id','$block_height','$investment','$val','$address','0','".$currency_sign." DEPOSIT','$systems_date_time','$payment_method','$ref_balance','$tkey','$crate')"; 
			query_execute_sqli($sql);
	    }
	    
		$balance = $tron->getBalance($address, true);

		if($balance >= $investment){
			$chk_result = 
				array(
					'error'=>'ok','result'=>
					array(
						'status'=>100,
						'time_completed'=>$systems_date_time,
						'receivedf'=>$balance,
						'hash'=>$address,
						'block_height'=>0,
						'ref_balance'=>0
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
	    $sql = "select * from request_crown_wallet where user_id='$login_id' and bitcoin_address = '$address'  order by id desc limit 1";
		
		$query = query_execute_sqli($sql);
		if(mysqli_num_rows($query) == 0 and $value > 0){
			$_SESSION['session_user_investment'] = 1;
			$investment = $_SESSION['investment'];
			$txn_id = $_SESSION['bitcoin_txn_id'];
			$tkey = $_SESSION['bitcoin_tkey'];
			$sql = "INSERT INTO `request_crown_wallet`(`ac_type`,`user_id`, `plan_id`,`investment`,`request_crowd`,
			 `bitcoin_address`,`reqid`, `description`,`date`,`c_by`,`transaction_id`,`tkey`,`bitcoin`) 
			 VALUES ('$payment_method','$login_id','$block_height','$investment','$val','$address','0','".$currency_sign." DEPOSIT','$systems_date_time','$payment_method','$ref_balance','$tkey','$crate')"; 
			query_execute_sqli($sql);
			$sql = "update user_gen_addrs set mode = 1 where address = '$address'";
			query_execute_sqli($sql);
		}
		mysqli_free_result($sql);
		$sql = "select * from request_crown_wallet where user_id='$login_id' and bitcoin_address = '$address' order by id desc limit 1";
		
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
			$currency_sign = 'Tron';
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
		else{
			$obj = array('info'=>"Wait For Confirmation !!",'result'=>0);
			echo $obj = json_encode($obj);
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
