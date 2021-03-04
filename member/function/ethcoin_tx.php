<?php
error_reporting(0);
include "../config.php";
include('../function/setting.php');
include("../function/functions.php");
include("../create_withdrawal/coinpayments.inc.php");

$cps = new CoinPaymentsAPI();
$cps->Setup($Private_Key, $Public_Key);

$sql = $sql = "select t1.*,t2.id_user,t2.ac_no,t2.phone_no from `request_crown_wallet` t1 
	inner join `users` t2 on t1.`user_id` = t2.`id_user`		
	where t1.`status` in(1) and t1.ac_type in(3) and t2.`type`='B' and t1.transaction_hash IS NULL";
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
		$transaction_id = $row['transaction_id'];
	    $time = $row['date'];
	    $req = array(
			'txid' => "$transaction_id",
		);
		$result = $cps->GetTxInfo($req);
        //print_r($result);
        if($result['error'] == "ok"){
            $results = $result['result'];
            $hash = $results['send_tx'];
            if($hash != ""){
    			$sql = "UPDATE request_crown_wallet set `transaction_hash` = '$hash' where `id`='$r_id'";
    			query_execute_sqli($sql);
            }
        }
	}
}
//print_r(chk_txs_ethcoin($g_addr='0xc03b2e868f868ab840df1c354d06c062a313ce95',$amount=0.0037,$time=date('Y-m-d H:i:s',1539169007)));//
//copy from ngtc coin software

function chk_txs_ethcoin($g_addr,$amount,$time){
	$hit = 0;
	$deduct_time = "- 2 Hour" ;// Hour
	$api_deduct_time = "-12 Hour";
	$satohi_value = 1000000000000000000; // Not Changable
	$mining_hour = 48; // Not Changable
	
	$time = date("Y-m-d H:i:s",strtotime($time.$deduct_time));
	$time = strtotime($time);
	$amount = (double)$amount * $satohi_value;
	$date = date("Y-m-d H:i:s",($time));
	$address = $g_addr;
	$url = "http://api.etherscan.io/api?module=account&action=txlist&address=$g_addr&offset=100&sort=desc"; 
	//$url = "../test3.php"; // TEST URL
	$string = file_get_contents($url,true);
	$array = json_decode($string,true);
	$result[0] = 0;
	if(is_array($array)){
		$url = "https://api.blockcypher.com/v1/eth/main"; // Total Block Height For Calculate HIT Confirmation
		$total_block_height = file_get_contents($url);
		$total_block_height = json_decode($total_block_height,true)['height'];
		$n_tx = count($array['result']);
		if($n_tx > 0){
			$i = 0;
			$confirmation = 0;
			foreach($array['result'] as $key=>$value){
				if($value['to'] != $g_addr)continue;
				$addr_out[] = $value['to'];
				$value_out[] = (double)$value['value'];
				$amount1[]=$amount;
				
				if(in_array($g_addr,$addr_out)){
				    $mk = array_search($g_addr,$addr_out);
					if(($value['timeStamp']) <= $time and isset($value['blockNumber'])){
						//echo $value['timeStamp'] ,"----", $time;
						$confirmation = ($total_block_height - $value['blockNumber']) + 1;
					}
					if($confirmation >= $hit){
						$sk = array_search($g_addr,$addr_out);
						$result[0] = 1;
						$result[1] = $value['hash'];
						$result[2] = $value['timeStamp'];
						$result[3] = $confirmation;
						$result[4] = $value_out[$sk]/$satohi_value;
						$result[5] = $value['blockNumber'];
						$result[6] = $value['timeStamp'];
						return $result;
					}
				}
				$i++;
			}
		}
	}
	return $result;
}
//http://api.etherscan.io/api?module=account&action=tokentx&address=0x4e83362442b8d1bec281594cea3050c8eb01311c&startblock=0&endblock=999999999&sort=asc&apikey=YourApiKeyToken

//http://api.etherscan.io/api?module=account&action=txlist&address=0xa28ecEf75390a6fe308EB48C0972b8AaAF607E41&startblock=0&endblock=99999999&page=1&offset=10&sort=asc&apikey=YourApiKeyToken

//https://api.etherscan.io/api?module=account&action=txlistinternal&address=0x2c1ba59d6f58433fb1eaee7d20b26ed83bda51a3&startblock=0&endblock=2702578&page=1&offset=10&sort=asc&apikey=YourApiKeyToken


?>