<?php
function check_trasaction($pid,$systems_date_time,$chk_result=false){
	include "setting.php";
	$msql = $sql = "select t1.*,t2.id_user,t2.phone_no from `request_crown_wallet` t1 
	inner join `users` t2 on t1.`user_id` = t2.`id_user`		
	where t1.`status` in(0) and t1.ac_type in(9) and t2.`type`='B' and t1.id='$pid'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$date_array = explode(" ",$systems_date_time);
	$systems_date = $date_array[0];
	$systems_time = $systems_date_time;
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$r_id = $row['id'];
			$user_id = $row['user_id'];
			$g_addr = $row['bitcoin_address'];
			$phone_no = $row['phone_no'];
			$full_name= $row['f_name']." ".$row['l_name'];
			$amount = $request_crowd = $row['request_crowd'];
			$investment = $row['investment'];
			$hash = $row['transaction_hash'];
			$time = $row['date'];
			$status = $row['status'];
			$crate = $row['bitcoin'];
			$currency_sign = $row['c_by'] == 1 ? 'Bitcoin' : 'ETH';
			
			if($hash == ""){
				if($chk_result['error'] == 'ok'){
					$chk_result = $chk_result['result'];
					$time = date("Y-m-d H:i:s",strtotime($chk_result['time_completed']));
					if($chk_result['status'] == 100 or $chk_result['status'] == 1){
						$time = $systems_time;
						$sql = "UPDATE request_crown_wallet set `status` = '1', `action_date` = '$systems_time',
								transaction_hash='".$chk_result['hash']."',sent_bitcoin = '".$chk_result['receivedf']."' 
								where `id`='$r_id' and `status`=0";
						
						query_execute_sqli($sql);
						query_execute_sqli("update wallet set activationw = activationw+'$investment' where id='$user_id'");
						insert_wallet_account($user_id , $user_id , $investment , $systems_date_time , $acount_type[6] , $acount_type_desc[6], 1 , get_user_allwallet($user_id,'activationw'),$wallet_type[2],$remarks = "$currency_sign Trasaction");
						if(strtoupper($soft_chk) == "LIVE12"){
							$udetail = userid_details($user_id);
							$to = $udetail['email'];  //message for mail
							$phone = $udetail['phone_no'];
							$title = "Credit Fund Message";
							$db_msg = "$investment TRX Deposition Has Been Completed !";
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

//chk_txs('3CieWDAgFCpDgGnTsivYcAu8SWje9QB3Nb','2020-10-10 18:26:50',$pm=1);
//chk_txs('0x7a250d5630b4cf539739df2c5dacb4c659f2488d','2020-10-17 21:05:50',$pm=3);
function chk_txs($g_addr,$time,$pm){
//	print phpinfo();
	$deduct_time = "0";
    $time = date("Y-m-d H:i:s",strtotime($time.$deduct_time));
	$time = strtotime($time);
	$date = date("Y-m-d H:i:s",($time));
	$address = $g_addr;

	$currency = 'trx';
	$satohi_value = pow(10,6);

	$url = "https://api.blockcypher.com/v1/$currency/main/addrs/$address"; // LIVE URL
	$arrContextOptions=array(
      "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    ); 
//	print $string = file_get_contents($url,false, stream_context_create($arrContextOptions));
    print $string = url_get_contents ($url);
    var_dump($string);
    exit;
	$array = json_decode($string,true);
	$result[0] = 0;
	if(is_array($array) and $g_addr == $array['address']){
		$url = "https://api.blockcypher.com/v1/$currency/main"; // Total Block Height For Calculate HIT Confirmation
		$string = file_get_contents($url,true);
		$total_block_height = json_decode($string,true)['height'];
		$address = $array['address'];
		$total_received = $array['total_received'];
		$total_sent = $array['total_sent'];
		$final_balance = $array['final_balance'];
		$n_tx = $array['n_tx'];
		if($n_tx > 0){
			$confirmation = 0;
			//print_r($array['txrefs']);
			foreach($array['txrefs'] as $txrefs){
				$tx_hash = $txrefs['tx_hash'];
				$block_height = $txrefs['block_height'];
				$value = $txrefs['value'];
				$ref_balance = $txrefs['ref_balance'];
				$confirmations = $txrefs['confirmations'];
				$confirmed = date("Y-m-d H:i:s",strtotime($txrefs['confirmed']));
				
				if(strtotime($confirmed) <= $time and isset($txrefs['block_height'])){
					$confirmation = ($total_block_height - $block_height) + 1;
				}
				if($confirmation >= 0){
					
					$result[0] = 1;
					$result[1] = $tx_hash;
					$result[2] = $confirmed;
					$result[3] = $confirmations;
					$result[4] = number_format($value/$satohi_value,8);
					$result[5] = $block_height;
					$result[6] = $ref_balance;
					return $result;
				}
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
function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
?>