<?php
error_reporting(0);
include "../config.php";
include('../function/setting.php');
include("../function/functions.php");

$sql = $sql = "select t1.*,t2.id_user,t2.ac_no,t2.phone_no from `request_crown_wallet` t1 
	inner join `users` t2 on t1.`user_id` = t2.`id_user`		
	where t1.`status` in(1) and t1.ac_type in(1) and t2.`type`='B' and t1.transaction_hash IS NULL";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
$date_array = explode(" ",$systems_date_time);
$systems_date = $date_array[0];
$systems_time = $systems_date_time;
if($num > 0){
	while($row = mysqli_fetch_array($query)){
		$r_id = $row['id'];
		$amounts = $row['request_crowd'];
		$g_addr = $row['bitcoin_address'];
		$time = $row['date'];
	
		$result = chk_txs($g_addr,$amounts,$time);
		if($result[0] == 1){
			$hash = $result[1];
			$sql = "UPDATE request_crown_wallet set `transaction_hash` = '$hash' where `id`='$r_id'";
			query_execute_sqli($sql);
		}
	}
}

function chk_txs($g_addr,$amounts,$time){
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
				if(in_array($g_addr,$addr_out)){
				    $mk = array_search($g_addr,$addr_out);
					if($value_out[$mk] >= $amount){
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
				}
				$i++;
			}
		}
	}
	return $result;
}

?>