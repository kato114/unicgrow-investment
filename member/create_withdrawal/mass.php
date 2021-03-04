<?php
/*
	CoinPayments.net API Example
	Copyright 2014 CoinPayments.net. All rights reserved.	
	License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.txt
*/
ini_set('display_errors','on');
require('coinpayments.inc.php');
require('../config.php');
require('../function/functions.php');
$sql="	SELECT t1.id,t1.api_description,t1.request_crowd,t2.btc_ac ac_no1,t1.ac_type 
		FROM withdrawal_crown_wallet t1 
		LEFT JOIN users t2 ON t1.user_id=t2.id_user 
		WHERE t1.status in(65,1) and t1.ac_type in(1,2)";
$query=query_execute_sqli($sql);
if(mysqli_num_rows($query) > 0){
	//$cur_bitcoin_value=chk_get_bitcoin($xquise_starling_root,'USD',1);
	$cps = new CoinPaymentsAPI();
	$cps->Setup($Private_WKey, $Public_WKey);
	$auto_confirm = FALSE;
	$ipn_url = '';
	$result = $cps->GetRates();//get_USD_TO_BITCOIN("INR",1);	
	
	if($result['error'] != "ok"){
		echo $result['error'];
	}
	else{
		$one_usd_value = $result['result']['USD']['rate_btc'];
		$one_eth_value = $result['result']['ETH']['rate_btc'];
	}
	$k = 1;
	$req = array();
	while($row = mysqli_fetch_array($query)){
		$address=$row['ac_type'] == 1 ? $row['ac_no1'] : $row['ac_no2'];
		$row['ac_type'] == 2 ? $one_usd_value = (1/$one_eth_value)*$one_usd_value : "";
		$with_curr = $row['ac_type'] == 1 ? "BTC" : "ETH";
		if($address != ""){
			$wstatus=$row['status'];
			$id=$row['id'];
			$value_btc = $row['request_crowd']*$one_usd_value;
			$withdrawal_id =$row['api_description'];
			
			if($withdrawal_id != ""){ // get withdrawal information
				$result = $cps->GetWithdrawalInfo($withdrawal_id);
				if ($result['error'] == 'ok') {
					if($result['result']['send_txid'] != "" and $result['result']['status'] == 2){
						$time_created = $result['result']['time_created'];
						$date = date("Y-m-d H:i:s",($time_created));
						$send_txid = $result['result']['send_txid'];
						query_execute_sqli("UPDATE withdrawal_crown_wallet set transaction_hash='$send_txid',status='2', action_date='".$date."' WHERE id='$id'");
					}
				} 
			}
			elseif($wstatus == 1 and $withdrawal_id == ""){ // SET withdrawal Variable
				$wd = 'wd'.$k;
				$req["wd[".$wd."][amount]"] = $value_btc;
				$req["wd[".$wd."][address]"] = $address;
				$req["wd[".$wd."][currency]"] = $with_curr;
				$req["wd[".$wd."][auto_confirm]"] = $auto_confirm ? 1:0;
				$req["wd[".$wd."][ipn_url]"] = $ipn_url;
				$chk_id_wd[$wd] = $id;
				
				$k++;
			}
		}
		
	}
	
	
	if(!empty($req)){ // SET withdrawal Request
		$result = $cps->CreateMassWithdrawal($req);	
		if ($result['error'] == 'ok'){
			foreach($result as $key1 => $value1){
				foreach($value1 as $key2 => $value2){
					if($result['result'][$key2]['error'] == "ok"){
						if($result['result'][$key2]['id'] != ""){
							$id = $chk_id_wd[$key2];
							$sql = "UPDATE withdrawal_crown_wallet set cur_bitcoin_value='$one_usd_value', api_description='".$result['result'][$key2]['id']."',status='1',request_crowd='$value_btc' WHERE id='$id'";
							query_execute_sqli($sql);
						}
					}
					else{
						print 'Error: on id '.$chk_id_wd[$key2]." and ".$result['result'][$key2]['error']."<br>";
					}
				}
			}
		}
		else{
			print 'Error: '.$result['error']."\n";
		}
	}
	
		
		
}
/*$result = array('error' => 'ok',
	'result' => array(
					'wd1' => array(
							'error' => 'That amount is larger than your balance!'
							),
					'wd2' => array(
							'error' => 'ok',
							'id' => '5d2b60b70c2ee37b954c197f6907f7743286693163c388815dd18bb49188aa48',
							'status' => 1,
							'amount' => 0.01000000,
							),
					'wd3' => array(
							'error' => 'That amount is larger than your balance!'
							),
					'wd4' => array(
							'error' => 'That amount is larger than your balance!',
							'id' => '5d2b60b70c2ee37b954c197f6907f7743286693163c388815dd18bb49188aa48',
							'status' => 1,
							'amount' => 0.01000000,
							)
				)
);*/
	