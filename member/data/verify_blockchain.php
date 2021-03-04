<?php
ini_set("display_erros","off");
session_start();
include('../security_web_validation.php');
include('../config.php');
include('../condition.php');
include('../function/setting.php');
include("../function/functions.php");
include("../function/send_mail.php");
include("../create_withdrawal/coinpayments.inc.php");
include("../function/blockchain_trasaction.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];
$pid = $_REQUEST['pid'];
$ppid = $_REQUEST['ppid'];
$sql = "select * from request_crown_wallet where user_id='$login_id' and id = '$ppid'  order by id desc limit 1";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query)){
	$address = $row['bitcoin_address'];
	$val = $row['request_crowd'];
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
	$sql = "select * from request_crown_wallet where user_id='$login_id' and id = '$ppid'  order by id desc limit 1";
	$query = query_execute_sqli($sql);
	if(mysqli_num_rows($query) > 0){
		while($row = mysqli_fetch_array($query)){
			$pid = $row['id'];
			$status = $row['status'];
			$mining_space = $row['request_crowd'];
		}
		if($status == 0)
			$result = check_trasaction($ppid,$systems_date_time,$chk_result);
		if($status == 1){
			$result[0] = 2;
			$result[7] = $pid;
			$result[4] = $mining_space ;
		}
		$currency_sign = $payment_method == 1 ? 'Bitcoin' : 'ETH';
		if($result[0] == 2){
			$msg= "Congratulations !! Your payment is confirmed ";
			$msg.="It`s Credited Fund TO Cash Wallet";
			$done=1;
		}
		elseif($status == 0){
			$msg = "Wait For Confirmation !!";
			$done=0;
		}
		
		$obj = array('info'=>$msg,'result'=>$done);
		echo $obj = json_encode($obj);
		die();
	}
	else{
	    $obj = array('info'=>'Record Not Found !','result'=>$done);
		echo $obj = json_encode($obj);
		die();
	}
}
else{
	$obj = array('info'=>"API GOT AN ERROR",'result'=>0);
	echo $obj = json_encode($obj);
}
?>