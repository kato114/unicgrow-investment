<?php
/*error_reporting(0);
include "../config.php";
include('../function/setting.php');
include("../function/functions.php");
include("../function/pair_point_calc.php");
//include("function/pair_point_income.php");
include("../function/direct_income.php");
print_r(check_trasaction(2,$systems_date_time));

print_r(chk_txs('3LG2Khq6bPTQYkfNaBH8DFsNtTdGcpcmyo',0.00228782,'2019-11-12 16:40:52'));*/
function check_trasaction($pid,$systems_date_time,$chk_result=false){
	include "setting.php";
	$msql = $sql = "select t1.*,t2.id_user,t2.ac_no,t2.phone_no from `request_crown_wallet` t1 
	inner join `users` t2 on t1.`user_id` = t2.`id_user`		
	where t1.`status` in(0) and t1.ac_type in(1,3) and t2.`type`='B' and t1.id='$pid'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$date_array = explode(" ",$systems_date_time);
	$systems_date = $date_array[0];
	$systems_time = $systems_date_time;
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$r_id = $row['id'];
			$user_id = $row['user_id'];
			$s_addr = $row['ac_no'];
			$g_addr = $row['bitcoin_address'];
			$phone_no = $row['phone_no'];
			$full_name= $row['f_name']." ".$row['l_name'];
			$amount = $request_crowd = $row['request_crowd'];
			$investment = $row['investment'];
			$hash = $row['transaction_hash'];
			$time = $row['date'];
			$status = $row['status'];
			$currency_sign = $row['c_by'] == 1 ? 'Bitcoin' : 'ETH';
			if($hash == ""){
				if($chk_result['error'] == 'ok'){
					$chk_result = $chk_result['result'];
					$time = date("Y-m-d H:i:s",($chk_result['time_completed']));
					if($chk_result['status'] == 100 or $chk_result['status'] == 1){
						$time = $systems_time;
						$hash = $chk_result['hash'];
						$sql = "UPDATE request_crown_wallet set `status` = '1', `action_date` = '$systems_time',trasaction_hash='$hash' 
							where `id`='$r_id' and `status`=0";
						
						query_execute_sqli($sql);
						if(strtoupper($soft_chk) == "LIVE"){
							$investment = $chk_result['receivedf']/($row['request_crowd']/$investment);
						}
						query_execute_sqli("update wallet set activationw = activationw+'$investment' where id='$user_id'");
						insert_wallet_account($user_id , $user_id , $investment , $systems_date_time , $acount_type[6] , $acount_type_desc[6], 1 , get_user_allwallet($user_id,'activationw'),$wallet_type[2],$remarks = "$currency_sign Trasaction");
						if(strtoupper($soft_chk) == "LIVE"){
							$udetail = userid_details($user_id);
							$to = $udetail['email'];  //message for mail
							$phone = $udetail['phone_no'];
							$title = "Credit Fund Message";
							$db_msg = "$investment USD Deposition Has Been Completed !";
							send_sms($phone,$db_msg);
							include("email_letter/deposti_fund_msg.php");
							$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
							$SMTPChat = $SMTPMail->SendMail();
						}
						$chk_result[0] = 2;
						$chk_result[10] = $msql;
						return $chk_result;
					}
					else{
						$chk_result[0] = 0;
						$chk_result[10] = $msql;
						return $chk_result;
					}
				}
			}
		}
	}
	return $chk_result;
}
function chk_txs($g_addr,$amounts,$time){
	//include "setting.php";
	$hit = 0;
    $deduct_time = "- 2 Hour" ;// Hour
    $api_deduct_time = "-12 Hour";
    $satohi_value = 100000000; // Not Changable
    $mining_hour = 48; // Not Changable
	$amount = "";
	$time = date("Y-m-d H:i:s",strtotime($time.$deduct_time));
	$time = strtotime($time);
	$amount = $amounts * $satohi_value;
	$date = date("Y-m-d H:i:s",($time));
	$address = $g_addr;
	//https://www.blockchain.com/eth/address/0x38a8fa0a38c4a32d8e9bdde578edf4c6f477b85e
	$url = "https://blockchain.info/address/$address?format=json"; // LIVE URL
	//$url = "test.php"; // TEST URL
	$string = file_get_contents($url,true);
	$array = json_decode($string,true);
	$result[0] = 0;
	if(is_array($array) and $g_addr == $array['address']){
		$url = "https://blockchain.info/q/getblockcount"; // Total Block Height For Calculate HIT Confirmation
		$total_block_height = file_get_contents($url);
		$hash = $array['hash160'];
		$address = $array['address'];
		$total_received = $array['total_received'];
		$total_sent = $array['total_sent'];
		$final_balance = $array['final_balance'];
		$n_tx = $array['n_tx'];
		
		if($n_tx > 0){
			$i = 0;
			$confirmation = 0;
			foreach($array['txs'] as $key=>$value){
				$addr_in = $addr_out = array();
				$inputs = $array['txs'][$i]['inputs'];
				$outs = $array['txs'][$i]['out'];
				foreach($inputs as $key1 => $value1){
					$addr_in[] = $value1['prev_out']['addr'];
					$value_in[] = $value1['prev_out']['value'];
				}
				foreach($outs as $key1 => $value1){
					$addr_out[] = $value1['addr'];
					$value_out[] = $value1['value'];
				}
				$amount1[]=$amount;
				if(in_array($g_addr,$addr_out) and array_intersect($amount1,$value_out)){
				
					if($value['time'] <= $time and isset($value['block_height'])){
						$confirmation = ($total_block_height - $value['block_height']) + 1;
					}
					if($confirmation >= $hit){
						
						$result[0] = 1;
						$result[1] = $value['hash'];
						$result[2] = $value['time'];
						$result[3] = $confirmation;
						$result[4] = $amount;
						$result[5] = $value['tx_index'];
						$result[6] = $value['time'];
						return $result;
					}
				}
				$i++;
			}
		}
	}
	return $result;
}
function chk_txs_blockchain($g_addr,$amounts,$time){
	include("blockchain/setup.php");
	require_once __DIR__ . './../blockchain/vendor/autoload.php';
	$result[0] = 0;
	$Blockchain = new \Blockchain\Blockchain($api_code);
	$Blockchain->setServiceUrl($setServiceUrl);
	$Blockchain->Wallet->credentials($wallet_guid, $wallet_pass);
	if(is_null($wallet_guid) || is_null($wallet_pass)) {
		echo "Please enter a wallet GUID and password in the source file.<br/>";
		exit;
	}
	$hit = 0;
    $deduct_time = "- 2 Hour" ;// Hour
    $api_deduct_time = "-12 Hour";
    $satohi_value = 100000000; // Not Changable
    $mining_hour = 48; // Not Changable
	$amount = "";
	$time = date("Y-m-d H:i:s",strtotime($time.$deduct_time));
	$time = strtotime($time);
	$amount = $amounts;
	$date = date("Y-m-d H:i:s",($time));
	$address = $g_addr;
	$getAddresses = $Blockchain->Explorer->getAddress($address);//$url = "test.php"; // TEST URL
	$total_received = $getAddresses->total_received;
	$total_sent = $getAddresses->total_sent;
	$final_balance = $getAddresses->final_balance;
	$n_tx = $getAddresses->n_tx;
	$transactions = $getAddresses->transactions;
	$total_block_height = $Blockchain->Explorer->getLatestBlock()->height;
	foreach($transactions as $transaction){
		$hash = $transaction->hash;
		$block_height = $transaction->block_height;
		$tx_time = $transaction->time;
		//print date("Y-m-d H:is",$tx_time);
		$outputs = $transaction->outputs;
		foreach($outputs as $outputs){
			$value = $outputs->value;
			$r_addr = $outputs->address;
			$script = $outputs->script;
			//print $tx_time."-".$time."<br>";
			if($tx_time <= $time and $value == $amount and $r_addr == $g_addr){
				$confirmation = ($total_block_height - $block_height) + 1;
				if($confirmation >= 2){
					$result[0] = 1;
					$result[1] = $hash;
					$result[2] = $tx_time;
					$result[3] = $confirmation;
					$result[4] = $value;
					$result[5] = $script;
					$result[6] = $r_addr;
					return $result;
				}
			}
		}
	}
	return $result;
}

function hast_exist($hash){
	if(mysqli_num_rows(query_execute_sqli("select * from request_crown_wallet where transaction_hash='$hash'")) > 0)
		return 1;
	else
		return 0;
	
}
function get_userby_userId($user_id,$table){
	$result = query_execute_sqli("SELECT id_user FROM $table WHERE user_id = '$user_id' ");
	while($row = mysqli_fetch_array($result))
	{
		$id_user = $row[0];
	}
	return $id_user;
}
?>