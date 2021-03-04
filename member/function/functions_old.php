<?php
//error_reporting(1);


/*include "../config.php";
$chld = get_lft_rht_network_child($user_id=1);*/



/*******************app********************/
function login_check($userame,$pass){
	$sql = "SELECT id_user FROM users WHERE username = '$userame' AND password='$pass'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;	
}
function get_login_details($uname,$pass){
    $sql = "SELECT id_user,username FROM users WHERE username = '$uname' AND password='$pass'";
	$query = query_execute_sqli($sql);
	$row=mysqli_fetch_assoc($query);
	return $row;	
}
function user_details($id,$uname){
    $sql = "SELECT date,f_name,l_name,email,phone_no,username FROM users WHERE username = '$uname' AND id_user=$id";
	$query = query_execute_sqli($sql);
	$row=mysqli_fetch_assoc($query);
	return $row;	
}
function user_baleance($id){
    $sql = "SELECT amount FROM wallet WHERE  id=$id";
	$query = query_execute_sqli($sql);
	$row=mysqli_fetch_assoc($query);
	return $row;	
}
/***************************************/

function user_exist($username){	
	$sql = "SELECT id_user FROM users WHERE username = '$username'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}	

function get_new_user_id($username){
	$sql = "SELECT id_user FROM users WHERE username = '$username'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function btc_address_exist($btcaddress){	
	$sql = "SELECT bit_ac_no FROM users WHERE bit_ac_no = '$btcaddress'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}
function get_full_name($id){
	$sql = "SELECT f_name,l_name FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$row = mysqli_fetch_array($query);
	$result = ucwords($row[0]." ".$row[1]);
	mysqli_free_result($query);
	return $result;	
}
function get_full_name_by_username($username){
	$sql = "SELECT f_name,l_name FROM users WHERE username = '$username'";
	$query = query_execute_sqli($sql);
	$row = mysqli_fetch_array($query);
	$result = ucwords($row[0]." ".$row[1]);
	mysqli_free_result($query);
	return $result;		
}

function get_date($id){
	$sql = "SELECT date FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_pin($user_id){
	$sql = "SELECT user_pin FROM users WHERE id_user = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}


function get_epin_request_trans_password($trans_no){
	$sql = "SELECT * FROM epin_request WHERE transaction_no='$trans_no'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_user_wallet($id){
	$sql = "SELECT amount FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_allwallet($id,$field){
	$sql = "SELECT $field FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}


function show_details($user_id) 
{
	$query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		echo "<center><h1>Step 1</center>";
		echo "<h2>Your ID is ".$row['id_user']."<br>";
		echo "<h2>Your real patent is ".$row['real_parent']."<br>";
		echo "You are added at your virtual parent ".$row['parent_id'];
		echo " at position ".$row['position']."</h2>";
	}	
}

function get_user_name($id){
	$sql = "SELECT username FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_phone($id){
	$sql = "SELECT phone_no FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function real_parent($id){
	$sql = "SELECT real_parent FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_type($id)
{
	$query = query_execute_sqli("SELECT type FROM users WHERE id_user = '$id' ");
	$type = mysqli_fetch_array($query)[0];
	switch($type){
		case 'A' : $status = "Deactive"; break;
		case 'B' : $status = "Light"; break;
		case 'C' : $status = "Activate"; break;
		case 'D' : $status = "Franchisee"; break;
		case 'E' : $status = "Blocked"; break;
	}
	return $status;
}
function get_user_email($id){
	$sql = "SELECT email FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function update_member_wallet($user_id,$wallet_income,$inc_type)
{
	require("setting.php");
	$income = $wallet_income;
	$date = date('Y-m-d');
	query_execute_sqli("UPDATE wallet SET amount = amount + '$income' , date = '$date' WHERE id = '$user_id' ");
	
	if($inc_type == 1) { $income_type_log = "Monthly Salary Bonus"; }
	if($inc_type == 2) { $income_type_log = "Daily Income"; }
	if($inc_type == 3) { $income_type_log = "Club Income on Matching Business"; }
	if($inc_type == 4) { $income_type_log = "Binary Income"; }
	if($inc_type == 5) { $income_type_log = "Rewards Bonus"; }
	
	/*$log_username = get_user_name($user_id);
	$income_log = $wallet_income;
	$date = date('Y-M-d');
	include("logs_messages.php");
	data_logs($user_id,$data_log[4][0],$data_log[4][1],$log_type[4]);*/
}	
function active_check($id){
	$sql = "SELECT CASE
		WHEN type = 'B' THEN 'yes'
		WHEN type = 'D' THEN 'yes'
		ELSE 'no' END types
		FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;	
}
	

function insert_wallet(){
	$date = date('Y-m-d');
	$sql = "insert into wallet (date , amount) values ('$date' , 0) ";
	query_execute_sqli($sql);
}
function get_type_user($id){
	$sql = "SELECT type FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;	
}	

function get_user_pos($id){
	$sql = "SELECT CASE
		WHEN position = '0' THEN 'Left'
		WHEN position = '1' THEN 'Right'
		END pos
		FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;	
}	

function get_message($field){
	$sql = "SELECT $field FROM setting";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;	
}

function get_user_position($id){
	$sql = "SELECT position FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;		
}	

function real_par($id){
	$sql = "SELECT real_parent FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;	
}

function get_paid_free($id){
	$date = date('Y-m-d');
	$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$id' AND date <= '$date' AND end_date >= '$date'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}	

function validate_request_amount($amount) {  
	if(is_numeric($amount))
	{
		if($amount > 0) return 1;
		else return 0;
	}			
	else return 0;     
}



function wallet_balance($id){
	$sql = "SELECT amount FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;	
}	

function get_user_bonus_wallet($id){
	$sql = "SELECT roi FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;	
}	

function get_epin_id($epin){
	$sql = "SELECT id FROM e_pin WHERE epin = '$epin'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;		
}

function franchise_information($id)
{
	$query = query_execute_sqli("SELECT id,franchise_wallet FROM franchise WHERE franchise_id = '$id'");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($rows = mysqli_fetch_array($query))
		{
			$franchise_info[] = $rows['id'];
			$franchise_info[] = $rows['franchise_wallet'];
		}
		return $franchise_info;
		mysqli_free_result($query);
	}
	else { return 0; }	
}

function get_franchise_name($id)
{
	$query_find = query_execute_sqli("SELECT t2.f_name,t2.l_name,t2.username FROM franchise as t1 inner join users as t2 on t1.franchise_id = t2.id_user and t1.id = '$id'");
	$num = mysqli_num_rows($query_find);
	if($num != 0)
	{
		while($rows = mysqli_fetch_array($query_find))
		{
			$franchise_info[] = $rows['f_name'].' '.$rows['l_name'];
			$franchise_info[] = $rows['username'];
		}
		return $franchise_info;
		mysqli_free_result($query_find);
	}
	else { return 0; }	
}
function real_child($id){
	$sql = "SELECT * FROM users WHERE real_parent = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_network_child($id)
{
	//$result = mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent($id)"))[0];
	$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id IN ($id)";
	$quer = query_execute_sqli($sql);
	$result = rtrim(mysqli_fetch_array($quer)[0],',');
	
	$sql = "select * from users where id_user in ($result)";
	$query = query_execute_sqli($sql);
	$num =  mysqli_num_rows($query);
	mysqli_free_result($quer);
	mysqli_free_result($query);
	if($num > 0) { return $num; }
	else{ return 0;	}
}
function get_direct_member($id){
	$sql = "SELECT * FROM users WHERE real_parent = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	if($result > 0) { return $result; }
	else{ return 0;	}
	return $result;
}

function get_team_business($user_id)
{
	$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id IN ($user_id)";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	
	$sqls = "SELECT SUM(income) FROM user_income WHERE user_id IN ($result)";
	$quer = query_execute_sqli($sqls);
	$amount = number_format(mysqli_fetch_array($quer)[0]);
	
	mysqli_free_result($query);
	mysqli_free_result($quer);
	if($amount > 0) { return $amount; }
	else{ return 0;	}	
}
function get_bitcoin_address($url,$api_key,$reqid)
{
	$main_url=$url."api.php?api_key=$api_key&reqid=$reqid";
	$string = file_get_contents($main_url);
	$xml = simplexml_load_string($string);
	$json = json_encode($xml, true);
	$array = json_decode($json,true);
	return $array;
}

function get_user_internal_btc_add($id){
	$sql = "SELECT branch FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_wallet_amount($id){
	$sql = "SELECT amount FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_earn_total($user_id){
	$sql = "SELECT SUM(amount/*+tax+tds_tax*/) FROM income WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	if($result > 0) { return $result; }
	else{ return 0;	}
}

function get_pin_wallet_amount($id){
	$sql = "SELECT pin FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_coin_wallet_amount($id){
	$sql = "SELECT coin FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_levels_wallet($id){
	$sql = "SELECT level_incentive FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_user_roi_wallet($id){
	$sql = "SELECT activationw FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_user_level_wallet($id){
	$sql = "SELECT companyw FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_user_match_wallet($id){
	$sql = "SELECT level_incentive FROM wallet WHERE id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function my_package($user_id){
	$info = array();
	$sql = "SELECT sum(t1.update_fees) update_fees,min(t1.date) act_date 
			FROM reg_fees_structure t1 
			WHERE t1.user_id = '$user_id' ORDER BY t1.id DESC LIMIT 1  ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	
	while($row = mysqli_fetch_array($query)){
		$max_invest = $row['update_fees'];
		$sql = "select * from plan_setting where amount <= $max_invest and max_amount >= $max_invest order by id desc limit 1";
		$query1 = query_execute_sqli($sql);	
		$plan_id = "";
		while($rr = mysqli_fetch_array($query1))
		{
			$plan_id = $rr['id'];
			$plan_name = $rr['plan_name'];
		}
		mysqli_free_result($query1);
		$info[] = $plan_name;
		$info[] = $max_invest;
		$info[] = date('d/M/Y', strtotime($row['act_date']));
		$info[] = $plan_id;
		$info[] = $max_invest;
		$info[] = $max_invest;
	}
	mysqli_free_result($query);
	if($num > 0) { return $info; }
	else{ return 0;	}
}
function get_randomuser(){
	include "setting.php";
	$val = array_rand($user_specific_account,1);
	if($val == 0)$val = 1;
	return $val;
}
function insert_wallet_account($id , $recieve_id , $amount , $date , $type , $account, $mode , $wallet_balance,$wall_type,$remarks = false)
{
	$username = $sql = NULL;
	//include 'setting.php';
	//if($type == 11){
		$username = get_user_name($recieve_id);
	//}
	$date = $date." ".date("H:i:s");
	if($mode == 1){
		$sql = "INSERT INTO account (user_id , cr , date , type , account , wallet_balance , wall_type, remarks)
		VALUES('$id' , '$amount' , '$date' , '$type' , '$account ".$username."' , '$wallet_balance', '$wall_type', 	
		'$remarks')";
	}
	elseif($mode == 2){
		$sql = "INSERT INTO account (user_id , dr , date , type , account , wallet_balance , wall_type, remarks)
		VALUES('$id' , '$amount' , '$date' ,'$type' , '$account ".$username."' , '$wallet_balance' ,'$wall_type','$remarks')";
	}
	query_execute_sqli($sql);
}


function insert_wallet_account_adm($id , $amount , $date , $type , $account, $mode , $wallet_balance ,$wall_type)
{
	if($mode == 1){
		$sql = "INSERT INTO account (user_id , cr , date , type , account , wallet_balance , wall_type)
		VALUES('$id','$amount','$date','$type','$account','$wallet_balance','$wall_type') ";
	}
	elseif($mode == 2){
		$sql = "INSERT INTO account (user_id , dr , date , type , account , wallet_balance , wall_type)
		VALUES('$id','$amount','$date','$type','$account','$wallet_balance','$wall_type') ";
	}
	query_execute_sqli($sql);
}

function kyc_exist($user_id){ //check KYC is already store or not	
	$sql = "SELECT user_id FROM kyc WHERE user_id = '$user_id' AND mode_id = 1 AND mode_chq=1";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}
function get_USD_TO_BITCOIN($currency,$price){
	$result = file_get_contents("https://blockchain.info/tobtc?currency=$currency&value=$price");
	return $result;
}

function admin_btc_address(){
	$sql = "SELECT address FROM admin_btc_address WHERE mode = '0' order by id asc limit 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_member_paid_address($user_id){
	$sql = "SELECT paid_address FROM users WHERE id_user = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_paid_member($user_id){
	$sql = "SELECT t1.user_id,t2.id_user FROM reg_fees_structure t1
			left join users t2 on t1.user_id = t2.id_user
			WHERE t1.user_id = '$user_id' and t1.mode=1 and t2.type='B' and t1.update_fees > 0";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function dirct_member($id){
	$sql = "SELECT * FROM users WHERE real_parent = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_USD_TO_ETH_DOGE_LTC_BTC($currency,$exchange_to){
	/*if($currency == 0){ $name = 'DOGE'; }
	elseif($currency == 1){ $rate = 'ETH'; }
	elseif($currency == 2){ $rate = 'LTC'; }
	elseif($currency == 3){ $rate = 'BTC'; }*/
	//https://min-api.cryptocompare.com/data/price?fsym=DOGE&tsyms=USD;  FOR Dogecoin
	//https://min-api.cryptocompare.com/data/price?fsym=ETH&tsyms=USD ;  FOR Ethereum
	//https://min-api.cryptocompare.com/data/price?fsym=LTC&tsyms=USD ;  FOR LTC
	//https://min-api.cryptocompare.com/data/price?fsym=BTC&tsyms=USD ;  FOR BTC
	//$url = "https://min-api.cryptocompare.com/data/pricemulti?fsyms=DOGE,ETH,LTC,BTC&tsyms=USD" ;  //FOR All
	$url = "https://min-api.cryptocompare.com/data/price?fsym=$currency&tsyms=$exchange_to";
	$result = file_get_contents($url);
	$result = json_decode($result,true);
	return $result;
}
function get_last_access($id){
	$sql = "SELECT last_access FROM users WHERE id_user = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_withdrawal($id,$type=false)
{
	if($type == 0){
		$sql ="SELECT COALESCE(SUM(request_amount),0) FROM withdrawal_crown_wallet WHERE user_id = '$id' 
		AND status = 0 ";
	}
	if($type == 1){
		$sql ="SELECT COALESCE(SUM(request_amount),0) FROM withdrawal_crown_wallet WHERE user_id = '$id' 
		AND status = 1 ";
	}
	if($type == 2){
		$sql ="SELECT COALESCE(SUM(request_amount),0) FROM withdrawal_crown_wallet WHERE user_id = '$id' ";
	}
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_active_investment($user_id,$type)
{
	if($type == 0){ 
		$sql ="SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure WHERE user_id = '$user_id'";
	}
	if($type == 1){ 
		$sql ="SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure WHERE user_id = '$user_id' 
		ORDER BY id DESC LIMIT 1 "; 
	}
	if($type == 2){ 
		$sql ="SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure WHERE user_id = '$user_id'"; 
	}
	/*if($type == 0)
	{
		$sql ="SELECT SUM(amount) FROM daily_interest WHERE member_id = '$id' AND level = 0 AND mode = 1 AND count < max_count";
	}
	if($type == 1)
	{
		$sql ="SELECT SUM(amount) FROM daily_interest WHERE member_id = '$id' AND mode = 1 
		AND level = 0 ORDER BY id DESC LIMIT 1 ";
	}
	if($type == 2)
	{
		$sql ="SELECT SUM(amount) FROM daily_interest WHERE member_id = '$id' AND level = 0";
	}*/
	/*if($type == 0)
	{
		$sql ="SELECT 
			SUM(t1.amt) FROM 
			(
				SELECT SUM(investment) amt FROM request_crown_wallet WHERE user_id = '$user_id' AND status = 1 
				UNION 
				SELECT SUM(amount) amt FROM e_pin WHERE used_id = '$user_id'
			) 
			t1";
	}
	if($type == 1)
	{
		$sql ="SELECT 
			SUM(t1.amt) FROM 
			(
				(SELECT investment amt FROM request_crown_wallet WHERE user_id = '$user_id' AND status = 1 ORDER BY id DESC LIMIT 1)
				UNION 
				(SELECT amount amt FROM e_pin WHERE used_id = '$user_id' ORDER BY id DESC LIMIT 1)
			) 
			t1";
	}
	if($type == 2)
	{
		$sql ="SELECT 
			SUM(t1.amt) FROM 
			(
				SELECT SUM(investment) amt FROM request_crown_wallet WHERE user_id = '$user_id' AND status = 1 
				UNION 
				SELECT SUM(amount) amt FROM e_pin WHERE used_id = '$user_id'
			) 
			t1";
	}*/
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_active_investment_new($id,$type)
{
	if($type == 0)
	{
		$sql ="SELECT COALESCE(SUM(investment),0) FROM request_crown_wallet WHERE user_id = '$id' AND 
		(transaction_hash != '' OR transaction_hash != NULL) AND status = 1 ORDER BY id DESC";
	}
	if($type == 1)
	{
		$sql ="SELECT COALESCE(SUM(investment),0) FROM request_crown_wallet WHERE user_id = '$id' AND 
		(transaction_hash != '' OR transaction_hash != NULL) AND status = 1";
	}
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_all_users_of_system(){
	$sql = "SELECT * FROM users";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function pagging_initation($newp,$pnums,$url)
{ ?>
	<div id="DataTables_Table_3_paginate" class="dataTables_paginate paging_simple_numbers">
		<ul class="pagination">
		<?php
		if($newp > 1){ ?>
			<li class="paginate_button page-item previous">
				<a class="page-link"  href="<?="index.php?page=$url&p=".($newp-1)?>">Previous</a>
			</li> <?php  
		}
		for($i = 1; $i <= $pnums; $i++) 
		{ 
			if($i != $newp)
			{ ?> 
				<li class="paginate_button page-item ">
					<a class="page-link" href="<?="index.php?page=$url&p=$i";?>"><?php print_r("$i")?></a>
				</li>
				<?php 
			}
			else{ ?>
				<li class="paginate_button page-item active">
					<a class="page-link" href="#"><?php print_r("$i")?></a>
				</li> <?php 
			}
		} 
		if($newp < $pnums) 
		{ ?> 
			<li id="DataTables_Table_3_next" class="paginate_button page-item next">
				<a class="page-link" href="<?="index.php?page=$url&p=".($newp+1)?>">Next</a>
			</li> <?php } ?>
		</ul>
	</div> <?php 
}
function refund_exist($user_id,$invst_id){
	$sql = "SELECT * FROM investment_refund WHERE rcw_id = '$invst_id' and user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_user_tot_investment($user_id){
	$sql = "SELECT request_crowd FROM reg_fees_structure where user_id = '$user_id' AND boost_id = 0 
	ORDER BY id DESC LIMIT 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}	

function get_user_tot_withdrawal($user_id){
	$sql = "SELECT COALESCE(SUM(request_crowd),0) FROM withdrawal_crown_wallet WHERE user_id = '$user_id' 
	AND ac_type = 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}	
function get_user_pending_withdrawal($user_id){
	$sql = "SELECT COALESCE(SUM(request_crowd),0) FROM withdrawal_crown_wallet WHERE user_id = '$user_id' 
	AND status=0 ";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_user_direct_income($user_id){
	$sql = "SELECT COALESCE(SUM(amount),0) FROM income WHERE user_id = '$user_id' AND type = 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_roi_income($user_id){
	$result = '';
	$sql = "SELECT SUM(amount) amt,SUM(tax) tax,SUM(tds_tax) tds FROM income WHERE user_id = '$user_id' 
	AND type = 2";
	$query = query_execute_sqli($sql);
	$row = mysqli_fetch_array($query);
	$result = $row['amt']/*+$row['tax']+$row['tds']*/;
	mysqli_free_result($query);
	return $result;
}

function get_user_roi_income_new2($user_id){
	$sql = "SELECT SUM(amount) amt,SUM(tax) tax,SUM(tds_tax) tds FROM income WHERE user_id = '$user_id' 
	AND type = 2";
	$query = query_execute_sqli($sql);
	$row = mysqli_fetch_array($query);
	$result = NULL;
	$result = $row['amt']+$row['tax']+$row['tds'];
	mysqli_free_result($query);
	return $result;
}


function get_user_investment($user_id){
	$sql = "SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_lft_rht_business($user_id,$start_date=false,$end_date=false){
	$search = "";
	if($start_date and $end_date){
		$search = " and date between '$start_date' and '$end_date'";
	}
	
	$sqls = "SELECT id_user FROM users WHERE parent_id = '$user_id' AND position = 0";
	$id_total = mysqli_fetch_array(query_execute_sqli($sqls))[0];
	$result1 = mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent ($id_total)"))[0].",".$id_total;
	
	$sqlk = "SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure t1 
	WHERE t1.user_id IN ($result1) AND t1.request_crowd > 0 $qur_set_search";
	$business[0] = mysqli_fetch_array(query_execute_sqli($sqlk))[0];
	
	
	$sqls = "SELECT id_user FROM users WHERE parent_id = '$user_id' AND position = 1";
	$id_total = mysqli_fetch_array(query_execute_sqli($sqls))[0];
	
	$result2 = mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent ($id_total)"))[0].",".$id_total;
	
	$sqlk = "SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure t1 
	WHERE t1.user_id IN ($result2) AND t1.request_crowd > 0 $qur_set_search";
	$business[1] = mysqli_fetch_array(query_execute_sqli($sqlk))[0];

	return $business;
}
function get_lft_rht_network_child($user_id,$start_date=false,$end_date=false){
	$sql = "select * from users where parent_id='$user_id' order by position asc";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$business[0] = 0;
	$business[1] = 0;
	$child[0] = 0;
	$child[1] = 0;
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$position = $row['position'];
			$child[$position] = $row['id_user'];
		}
		mysqli_free_result($query);
		if($child[0] > 0){
			$sql = "select get_chield_by_parent(".$child[0].") ch";
			$query = query_execute_sqli($sql);
			$row = mysqli_fetch_array($query);
			$dchild = $row[0];
			mysqli_free_result($query);
			if($dchild == ""){
				$lft_child[] = $child[0];
			}
			else{
				$lft_child = explode(",",$dchild);
				$lft_child[] = $child[0];
			}
			//$lft_child = implode(",",$lft_child);
			$business[0] = $lft_child;
		}
		if($child[1] > 0){
			$sql = "select get_chield_by_parent(".$child[1].") ch";
			$query = query_execute_sqli($sql);
			$row = mysqli_fetch_array($query);
			$dchild = $row[0];
			mysqli_free_result($query);
			if($dchild == ""){
				$rht_child[] = $child[1];
			}
			else{
				$rht_child = explode(",",$dchild);
				$rht_child[] = $child[1];
			}
			$business[1] = $rht_child;
		}
	}
	mysqli_free_result($query);
	return $business;
}
function get_business_reward_date(){
	$query = query_execute_sqli("select * from business_reward_date order by id desc limit 1 ");
	while($row = mysqli_fetch_array($query))
	{
		$rwstart_date = $row['start_date'];
		$rwend_date = $row['end_date'];
	}
	return array(0=>$rwstart_date,1=>$rwend_date);
}
function get_business_reward_setting(){
	$sql = "select * from business_reward where mode = 1 order by id asc";
	$query = query_execute_sqli($sql);
	$k = 0;
	while($row = mysqli_fetch_array($query))
	{
		$gbr[$k][0] = $row['left'];
		$gbr[$k][1] = $row['right'];
		$gbr[$k][2] = $row['id'];
		$gbr[$k][3] = $row['title'];
		$k++;
	}
	return $gbr;
}
function pay_new_entry($payment_data){
	$fields='`'.implode('`,`',array_keys($payment_data)).'`';
	$data='\''.implode('\',\'',$payment_data).'\'';
	query_execute_sqli("insert into `paymentinfo` ($fields) values ($data)");
}

function get_ETH_DOGE_LTC_BTC_TO_IN_USD($currency,$exchange_to){
	$url = "https://min-api.cryptocompare.com/data/price?fsym=$currency&tsyms=$exchange_to";
	$result = file_get_contents($url);
	$result = json_decode($result,true);
	return $result;
}
function get_user_active_investment_with_date($user_id)
{
	$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' ORDER BY id ASC LIMIT 1";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	while($row = mysqli_fetch_array($query))
	{
		$info[] = $row['request_crowd'];
		$info[] = $row['date'];
	}
	if($num > 0) { return $info; }
	else{ return 0;	}
}

function get_user_active_investment($user_id){
	$sql = "SELECT request_crowd FROM reg_fees_structure WHERE user_id = '$user_id' AND mode = 1 
	ORDER BY id DESC LIMIT 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	if($result > 0){ return $result; }
	else return 0;
}

function get_user_active_investment_confirm($id){
	$sql = "SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure WHERE user_id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_user_total_withdrawal($user_id){
	$sql = "SELECT COALESCE(SUM(amount),0) FROM paid_unpaid WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_which_type_bonus($user_id , $type){
	$sql = "SELECT COALESCE(SUM(amount/*+tax+tds_tax*/),0) FROM income WHERE user_id = '$user_id' AND type = '$type'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}


function get_amt_receiver_id($date){
	$sql = "SELECT t2.username FROM account t1
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.date = '$date' AND t1.type = 18";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)['username'];
	mysqli_free_result($query);
	return $result;
}

function pan_no_update_or_not($user_id){
	$sql = "SELECT pan_no FROM users WHERE id_user = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_user_pan_card_no($user_id){
	$sql = "SELECT pan_no FROM users WHERE id_user = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_total_bonus($user_id){
	$sql = "SELECT COALESCE(SUM(amount),0) FROM income WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function user_network_left_right_business($user_id,$position)
{
	$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id IN ($user_id)";
	$quer = query_execute_sqli($sql);
	$result = mysqli_fetch_array($quer)[0];
	
	$SQL = "SELECT COALESCE(SUM(t1.request_crowd),0),t1.user_id FROM reg_fees_structure t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.user_id IN ($result) AND t2.position = '$position'";
	
	$query = query_execute_sqli($SQL);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($quer);
	mysqli_free_result($query);
	if($result > 0){ return $result; }
	else{ return 0; }
}

function get_today_network_business($user_id,$date,$field){	
	$sql = "SELECT $field FROM network_users WHERE user_id IN ($user_id)";
	$q1 = query_execute_sqli($sql);
	$result = rtrim(mysqli_fetch_array($q1)[0],',');
	$sqlk = "SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure WHERE user_id IN ($result) 
	AND date = '$date' AND boost_id = 0";
	$q2 = query_execute_sqli($sqlk);
	$amount = mysqli_fetch_array($q2)[0];
	mysqli_free_result($q1);
	mysqli_free_result($q2);
	//mysqli_free_result();
	if($amount > 0){ return $amount; }
	else{ return 0; }
}

function get_today_network_business_new($user_id,$date){	
	$sql = $q1 = NULL;
	$result = array(0,0,0);
	$sql = "SELECT * FROM pair_point WHERE user_id = '$user_id' AND date = '$date'";
	$q1 = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($q1)){
		$result[0] = $row['cf_left'];
		$result[1] = $row['cf_right'];
		$result[2] = $row['cf_left']+$row['cf_right'];
	}
	mysqli_free_result($q1);
	return $result;
}

function get_tot_network_left_right_mem($user_id){	
	$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id IN ($user_id)";
	$q1 = query_execute_sqli($sql);
	$result = rtrim(mysqli_fetch_array($q1)[0],',');
	
	$sql = "SELECT id_user FROM users WHERE id_user IN ($result)";
	$q2 = query_execute_sqli($sql);
	$result = mysqli_num_rows($q2);
	mysqli_free_result($q1);
	mysqli_free_result($q2);
	return $result;
}

function get_network_lr_business($user_id,$field = false){
	$sql = $quer = $q2 = NULL;
	/*$sql = "SELECT $field FROM network_users WHERE user_id = $user_id";
	$quer = query_execute_sqli($sql);
	$result = mysqli_fetch_array($quer)[0];
	$sql = NULL;
	$amount = NULL;
	$q2 = NULL;
	$sql="SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure WHERE user_id IN ($result) 
	AND mode IN(1,189,190) and date <= '2019-01-08'";
	$q2 = query_execute_sqli($sql);
	$amount = mysqli_fetch_array($q2)[0];
	mysqli_free_result($quer);
	mysqli_free_result($q2);
	if($amount > 0){ return $amount; }
	else{ return 0; }*/
	$result = array(0,0);
	$sql = "SELECT COALESCE(SUM(cf_left),0) tot_left ,COALESCE(SUM(cf_right),0) tot_right FROM pair_point 
	WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$row = mysqli_fetch_array($query);
	$result[0] = $row['tot_left'];
	$result[1] = $row['tot_right'];
	mysqli_free_result($query);
	return $result;
}

function get_network_lr_team($user_id,$field,$type){
	$sql = "SELECT COALESCE($field,0) FROM network_users WHERE user_id IN($user_id)";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	if($result == "")return 0;
	switch($type) {
		case 1 : $sql = "SELECT t1.* FROM users t1
				 LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
				 WHERE t1.id_user IN ($result) AND t2.id IS NOT NULL AND t2.mode = 1"; break;
		
		case 2 : $sql = "SELECT t1.* FROM users t1
				 LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
				 WHERE t1.id_user IN ($result) AND t2.id IS NULL"; break;
	}
	$q2 = query_execute_sqli($sql);
	$num = mysqli_num_rows($q2);
	mysqli_free_result($query);
	mysqli_free_result($q2);
	return $num;	
}

function get_network_lr_team_old($user_id,$field,$type=false){
	
	$sql = "SELECT COALESCE($field,0) FROM network_users WHERE user_id IN($user_id)";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	
	/*switch($type) {
		case 1 : $sql = "SELECT * FROM users WHERE id_user IN ($result) AND package > 0"; break;
		case 2 : $sql = "SELECT * FROM users WHERE id_user IN ($result) AND package = 0"; break;
	}*/
	$sql = "SELECT * FROM users WHERE id_user IN ($result)";
	$q2 = query_execute_sqli($sql);
	$num = mysqli_num_rows($q2);
	$num = $num == "" ? 0 : $num;
	mysqli_free_result($query);
	mysqli_free_result($q2);
	return $num;	
}
function get_direct_act_dct_total($user_id,$type){
	switch($type) {
		case 1 : $sql = "SELECT * FROM users WHERE real_parent = $user_id AND package > 0"; break;
		case 2 : $sql = "SELECT * FROM users WHERE real_parent = $user_id AND package = 0"; break;
		
		/*case 1 : $sql = "SELECT t1.* FROM users t1
				 LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
				 WHERE t1.real_parent = '$user_id' AND t2.id IS NOT NULL"; break;
		
		case 2 : $sql = "SELECT t1.* FROM users t1
				 LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
				 WHERE t1.real_parent = '$user_id' AND t2.id IS NULL"; break;*/
	}
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;	
}

function get_tot_direct_business($user_id,$date,$position){
	$sql = "SELECT COALESCE(SUM(t2.request_crowd),0) FROM users t1
	LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
	WHERE t1.real_parent = '$user_id' AND t1.position = '$position' 
	AND t2.mode IN (1,189,190)"; // AND t2.date = '$date'
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_today_direct_business($user_id,$date,$position){
	$sql = "SELECT COALESCE(SUM(t2.request_crowd),0) FROM users t1
	LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
	WHERE t1.real_parent = '$user_id' AND t2.date = '$date' AND t1.position = '$position'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}


function get_user_total_epin($user_id){
	$sql = "SELECT * FROM e_pin WHERE user_id = '$user_id' AND (mode = 1 OR mode = 2)";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_user_kyc_status($user_id)
{
	$sql = "SELECT * FROM kyc WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){ 
		while($row = mysqli_fetch_array($query)){ 	
			$mode = $row['mode'];
			$date = $row['date'];
			$mode_pan = $row['mode_pan'];
			$mode_id = $row['mode_id'];
			$mode_photo = $row['mode_photo'];
			$mode_chq = $row['mode_chq'];
		}
		/*switch($mode){
			case 0 : $status = "<span class='label label-warning'>Pending</B>"; 	break;	
			case 1 : $status = "<span class='label label-success'>Approved ($date)</B>"; 	break;
			case 4 : $status = "<span class='label label-danger'>Rejected ($date)</B>"; 	break;
		}*/
		$status = "<span class='label label-warning'>Pending</B>";
		if($mode_pan == 4 and $mode_id == 4 and $mode_photo == 4 and $mode_chq == 4){
			$status = "<span class='label label-danger'>Rejected ($date)</B>";
		}
		elseif($mode_pan == 1 and $mode_id == 1 and $mode_photo == 1 and $mode_chq == 1){
			$status = "<span class='label label-success'>Approved ($date)</B>"; 
		}
	}
	else{ $status = "<span class='label label-danger'>Incomplete</B>"; }
	return $status;
}

function kyc_approved_or_not($user_id){	
	$sql = "SELECT user_id FROM kyc WHERE user_id = '$user_id' AND mode = 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}


function pagging_initation_last_five($newp,$pnums,$url,$lpnums,$show_tab){
	if($lpnums < $show_tab){
		$show_tab = $lpnums;
	}
	$tot = $newp+($show_tab-1);
	$newpp = $newp;
	if($tot > $lpnums){
		$tot = $lpnums;
		$newp = $lpnums-($show_tab-1);
	}
	?>
	<div id="DataTables_Table_2_paginate" class="dataTables_paginate paging_simple_numbers">
		<ul class="pagination">
		<?php
		if($newpp > 1){ ?> 
			<li class="paginate_button page-item previous">
				<a class="page-link" title="First" href="<?="index.php?page=$url&p=1"?>"> &laquo; </a> 
			</li>
			<?php  
		}
		if($newpp > 1){ ?> 
			<li class="paginate_button page-item previous">
				<a class="page-link" href="<?="index.php?page=$url&p=".($newpp-1);?>">Previous</a>
			</li>
			<?php  
		}
		for($i = $newp; $i <= $tot; $i++){ 
			if($i != $newpp){ ?>
				<li class="paginate_button page-item ">
					<a class="page-link"  href="<?="index.php?page=$url&p=$i";?>"><?php print_r("$i");?></a> 
				</li>
				<?php  
			}
			else{ ?>
				<li class="paginate_button page-item active disabled">
					<a class="page-link" href="#"><?php print_r("$i")?></a>
				</li> <?php 
			}
		} 
		if($newpp < $lpnums){ ?> 
			<li class="paginate_button page-item next">
				<a class="page-link" title="Next" href="<?="index.php?page=$url&p=".($newp+1);?>">Next</a>
			</li>
			<?php  
		} 
		if($newpp < $lpnums){ ?>  
			<li class="paginate_button page-item next">
				<a class="page-link" title="Last" href="<?="index.php?page=$url&p=$lpnums"?>"> &raquo;</a>
			</li> <?php 
		} ?>
	</div><?php 
}


function get_user_pair_point_is_or_not($user_id){
	$show = 0;
	$sql = "select t1.*,t2.l_lps,t2.r_lps from pair_point t1
			left join users t2 on t1.user_id = t2.id_user
			where t1.user_id = '$user_id' order by id desc limit 1";
	$q = query_execute_sqli($sql);
	$n = mysqli_num_rows($q);
	if($n > 0)
	{
		$k = 0;
		while($rrr = mysqli_fetch_array($q))
		{
			$id = $rrr['user_id'];
			$type = get_type_user($id);
			if($type == 'B')
			{
				$lapse_l = $rrr['lapse_l'];
				$lapse_r = $rrr['lapse_r'];
				$ul_lps = $rrr['l_lps'];
				$ur_lps = $rrr['r_lps'];
				if($lapse_l == 0 and $lapse_r == 0 and $ul_lps == 1 and $ur_lps == 1){
					$show = 1;
				}
			}
		}
	}
	return $show;
}



function get_downline_network_new($login_id,$id){
	$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id IN ($login_id)";
	$query = query_execute_sqli($sql);
	$result = explode(",",rtrim(mysqli_fetch_array($query)[0],','));
	mysqli_free_result($query);	
	if(in_array($id,$result))return true;
	else return false;
}


function get_total_month_business($month){
	$sql = "SELECT SUM(amount) FROM income WHERE MONTH(date) = '$month'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_total_month_wal_bal($month){
	$sql = "SELECT COALESCE(SUM(amount),0) FROM wallet WHERE MONTH(date) = '$month'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_total_month_investment($month){
	$sql = "SELECT COALESCE(SUM(request_crowd),0) FROM reg_fees_structure WHERE MONTH(date) = '$month'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_income_by_type($user_id,$type){
	$sql = "SELECT COALESCE(SUM(amount),0) FROM income WHERE user_id = '$user_id' AND type = '$type' ";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_user_total_deposit($user_id){
	$sql = "SELECT COALESCE(SUM(investment),0) FROM request_crown_wallet WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function direct_member_position($real_p,$user_id){
	$sql = "SELECT position FROM users WHERE id_user = '$user_id' and real_parent='$real_p'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

/*function user_booster_is_activate_or_not($user_id){
	$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' AND boost_id > 0 AND mode = 1";
	$num = mysqli_num_rows(query_execute_sqli($sql));
	$time = mysqli_fetch_array(query_execute_sqli($sql))['time'];
	if($num > 0){
		$booster = "<B class='text-success'>Achieved </B>($time)";
	}
	else{
		$booster = "<B class='text-danger'>Pending </B>";
	}
	return $booster;
}*/
function user_booster_is_activate_or_not_for_bonus_roi($user_id,$systems_date_time){
	include('setting.php');
	
	$querw = $query = $quer = $ques = NULL;
	$querw = query_execute_sqli("SELECT time FROM reg_fees_structure WHERE user_id = '$user_id' AND mode = 66");
	$nums = mysqli_num_rows($querw);
	if($nums>0)
	{
		$time = mysqli_fetch_array($querw)[0];
		return "Achieved ($time)";
	}
	
	$query = query_execute_sqli("SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' ");
	$nums = mysqli_num_rows($query );
	while($row = mysqli_fetch_array($query)){
		$date_reg = $row['time'];
		$mode = $row['mode'];
	}
	
	//$date_reg = mysqli_fetch_array($query)[0];
	$last_time = date('Y-m-d H:i:s' , strtotime($date_reg."+ $boost_days days"));
	
	$swr = "SELECT TIMESTAMPDIFF(DAY,'$systems_date_time', '$last_time') day";
	$ques = query_execute_sqli($swr);
	$result = mysqli_fetch_array($ques);
	$day = $result[0];
	if($nums > 0){
		if($day > 0){
			$booster = "Pending ($day Days)";
		}
		else{
			$booster = "Time Out";
			
			if($mode != 99){
				$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' AND boost_id > 0 AND mode = 1";
				$quer = query_execute_sqli($sql);
				$num = mysqli_num_rows($quer);
				$time = mysqli_fetch_array(query_execute_sqli($sql))['time'];
				if($num > 0){
					$booster = "Achieved ($time)";
				}
			}
		}
	}
	else{
		$booster = "None";
	}
	mysqli_free_result($querw);
	mysqli_free_result($query);
	mysqli_free_result($quer);
	mysqli_free_result($ques);
	return $booster;
}
function user_booster_is_activate_or_not($user_id,$systems_date_time){
	include('setting.php');
	$querw = query_execute_sqli("SELECT time FROM reg_fees_structure WHERE user_id = '$user_id' AND mode = 66");
	$nums = mysqli_num_rows($querw );
	if($nums>0)
	{
		$time = mysqli_fetch_array($querw)[0];
		return "<B class='text-success'>Achieved </B>($time)";
	}
	
	$query = query_execute_sqli("SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' ");
	$nums = mysqli_num_rows($query );
	while($row = mysqli_fetch_array($query)){
		$date_reg = $row['time'];
		$mode = $row['mode'];
	}
	
	//$date_reg = mysqli_fetch_array($query)[0];
	$last_time = date('Y-m-d H:i:s' , strtotime($date_reg."+ $boost_days days"));
	
	$swr = "SELECT TIMESTAMPDIFF(DAY,'$systems_date_time', '$last_time') day";
	$result = mysqli_fetch_array(query_execute_sqli($swr));
	$day = $result[0];
	if($nums > 0){
		if($day > 0){
			$booster = "<B class='text-warning'>Pending ($day Days)</B>";
		}
		else{
			$booster = "<B class='text-danger'>Time Out</B>";
			
			if($mode != 99){
				$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' AND boost_id > 0 AND mode = 1";
				$num = mysqli_num_rows(query_execute_sqli($sql));
				$time = mysqli_fetch_array(query_execute_sqli($sql))['time'];
				if($num > 0){
					$booster = "<B class='text-success'>Achieved </B>($time)";
				}
			}
		}
	}
	else{
		$booster = "<B class='text-danger'>None</B>";
	}
	mysqli_free_result($querw);
	mysqli_free_result($query);
	return $booster;
}

function get_user_binary_qualifier($user_id,$date){
	$sql = "SELECT * FROM users WHERE id_user = '$user_id' AND 
	((l_lps >= 1 AND r_lps > 1) OR (r_lps >= 1 AND l_lps > 1)) AND step = 1";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	mysqli_free_result($query);
	
	$bin_qulf = 0;
	if($num > 0 and week_lottery_exist($user_id,$date)){
		$bin_qulf = 1;
	}
	/*if($num > 0 and week_lottery_exist($user_id,$date)){
		$bin_qulf = "<B class='text-success'>Qualified </B>";
	}
	else{
		$bin_qulf = "<B class='text-danger'>Non Qualified </B>";
	}*/
	return $bin_qulf;
}

function get_user_binary_qualifier_date($user_id){
	$sql = "SELECT date FROM income WHERE user_id = '$user_id' AND type = 4 ORDER BY id ASC LIMIT 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result != '' ? $result : false;
}

function get_user_reward($user_id){
	$sql = "SELECT t2.incentive reward FROM income t1
	LEFT JOIN plan_reward t2 ON t1.incomed_id = t2.id
	WHERE t1.user_id = '$user_id' AND t1.type = '5' ORDER BY t1.id DESC LIMIT 1";
	$query = query_execute_sqli($sql);
	$reward = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	if($reward != ''){
		return $reward;
	}
	else{
		return "<B class='text-danger'>None </B>";
	}
}

function get_user_designation($user_id){
	$sqls = "SELECT t2.title reward FROM income t1
	LEFT JOIN plan_reward t2 ON t1.incomed_id = t2.id
	WHERE t1.user_id = '$user_id' AND t1.type = '5' ORDER BY t1.id DESC LIMIT 1";
	$ques = query_execute_sqli($sqls);
	$num1 = mysqli_num_rows($ques);
	$reward = mysqli_fetch_array($ques)[0];
	
	$sql = "SELECT * FROM users WHERE id_user = '$user_id' AND 
	((l_lps >= 1 AND r_lps > 1) OR (r_lps >= 1 AND l_lps > 1))";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	
	if($num > 0 and $num1 == 0){
		$value = "<B class='text-info'>Promotor </B>";
	}
	elseif($num1 > 0){
		$value = "<B class='text-primary'>$reward </B>";
	}
	else{
		$value = "<B class='text-danger'>None </B>";
	}
	mysqli_free_result($ques);
	mysqli_free_result($query);
	return $value;
}

function total_cr_dr($id,$field){
	$sql = "SELECT COALESCE(SUM($field),0) FROM account WHERE user_id = '$id'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function user_flush_business($user_id,$total_pair){
	include('setting.php');
	$sql = "SELECT t1.*,t2.capping FROM reg_fees_structure t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t1.user_id = '$user_id' AND t1.mode IN (1,189,190)
	ORDER BY t1.invest_type DESC LIMIT 1";
	$result = query_execute_sqli($sql);
	$rows = mysqli_fetch_array($result);
	
	$plan_id = $rows['invest_type'];
	$chk_capping = $rows['capping'];
	
	$income = $total_pair*($set_binary_percent[$plan_id-1]/100);
	
	$caping = $set_capping[$plan_id-1];
	if($chk_capping != NULL) $caping = $chk_capping;
		
	if($caping < $income){
		$flush_business = $income - $caping;
	}
	else{
		$flush_business = 0;
	}
	mysqli_free_result($result);
	return $flush_business;
}

function get_user_pair_point($user_id,$date){
	include('setting.php');
	$date = date('Y-m-d', strtotime($date."- 1 DAY"));
	$week_pn = get_pre_nxt_date($date , $binary_froward_day);
	$p_date = $week_pn[0];
	$n_date = $week_pn[1];
	$sql = "SELECT left_point,right_point FROM pair_point WHERE user_id = '$user_id' AND date between '$p_date' and '$n_date'";
	$quer = query_execute_sqli($sql);
	$row = mysqli_fetch_array($quer);
	$left_point = $row[0];
	$right_point = $row[1];
	
	$max_pair = (int) min($left_point,$right_point);
	mysqli_free_result($quer);
	return $max_pair;
}
function user_active_plan($max_invest){
	$sql = "select * from plan_setting where amount <= $max_invest and max_amount >= $max_invest order by id desc limit 1";
	$query1 = query_execute_sqli($sql);	
	$plan_id = "";
	while($rr = mysqli_fetch_array($query1))
	{
		$plan_id = $rr['id'];
		$days = $rr['days'];
		$pv = $rr['amount'];
		$profit = $rr['roi_bonus']; 
		$invest_amount = $investment;
	}
	mysqli_free_result($query1);
	return $plan_id;
}
function user_user_roi_stop_or_not($user_id){
	$sql = "SELECT mode FROM reg_fees_structure WHERE user_id = '$user_id' order by id desc limit 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_today_carry_forward($user_id,$date){
	$sql = $sqlk = $num = $left_point = $right_point = $point = NULL;
	$sql = "SELECT * FROM pair_point where user_id = '$user_id' AND date = '$date'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	
	if($num > 0){
		//$sqlk = "SELECT * FROM pair_point where user_id = '$user_id' ORDER BY id DESC LIMIT 1,1";
		$sqlk = "SELECT * FROM pair_point where user_id = '$user_id' ORDER BY id DESC LIMIT 1";
	}
	else{
		$sqlk = "SELECT * FROM pair_point where user_id = '$user_id' ORDER BY id DESC LIMIT 1";
		//$sqlk = "SELECT * FROM pair_point where user_id = '$user_id' ORDER BY id DESC LIMIT 1,1";
	}

	$que = query_execute_sqli($sqlk);
	while($row = mysqli_fetch_array($que)){
		$left_point = $row['left_point'];
		$right_point = $row['right_point'];
	}
	$point = min($left_point,$right_point);
	$carry_forwd = array(0,0);
	
	if(strtotime($date) >= strtotime(get_user_binary_qualifier_date($user_id))){
		$carry_forwd[0] = $left_point-$point;
		$carry_forwd[1] = $right_point-$point;
	}
	mysqli_free_result($que);
	mysqli_free_result($query);
	return $carry_forwd;
}

function get_user_kyc_status_new($user_id){
	$sql = "SELECT * FROM kyc WHERE user_id = '$user_id' ";
	$que = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($que))
	{
		$mode_pan = $row['mode_pan'];
		$mode_id = $row['mode_id'];
		$mode_photo = $row['mode_photo'];
		$mode_chq = $row['mode_chq'];
	}
	/*$status = "<B class='text-danger'>Cancelled </B>";
	if($mode_pan == 0 and $mode_id  == 0 and $mode_photo == 0 and $mode_chq == 0){
		$status = "<B class='text-warning'>Pending </B>";
	}
	if($mode_pan == 1 and $mode_id  == 1 and $mode_photo == 1 and $mode_chq == 1 ){
		$status = "<B class='text-success'>Approved </B>";
	}*/
	
	$status = "Cancelled";
	if($mode_pan == 0 and $mode_id  == 0 and $mode_photo == 0 and $mode_chq == 0){
		$status = "Pending";
	}
	if($mode_pan == 1 and $mode_id  == 1 and $mode_photo == 1 and $mode_chq == 1 ){
		$status = "Approved";
	}
	mysqli_free_result($que);
	return $status;
}

function get_user_kyc_documents_upload($user_id){
	$sql = "SELECT * FROM kyc WHERE user_id = '$user_id' AND (chq_passbook = '' OR id_proof_front = '' 
	OR id_proof_back = '' OR photo = '' OR signature = '') ";
	$que = query_execute_sqli($sql);
	$num = mysqli_num_rows($que);
	
	if($num > 0){
		$status = "<div class='alert alert-danger alert-dismissable'>
			<B>You have not uploaded your all KYC documents.</B> 
			<button aria-hidden='true' data-dismiss='alert' class='close' type='button'>&times;</button>
		</div>";
	}
	mysqli_free_result($que);
	return $status;
}

function get_user_today_income($user_id,$date){
	$sql = $num = NULL;
	$sql2 = "SELECT * FROM pair_point where user_id = '$user_id' AND date = '$date' ORDER BY id DESC LIMIT 1";
	$query2 = query_execute_sqli($sql2);
	$num = mysqli_num_rows($query2);
	while($row = mysqli_fetch_array($query2)){
		$left_point = $row['left_point'];
		$right_point = $row['right_point'];
		$date = $row['date'];
	}
	$point = min($left_point,$right_point);
	$income = $point*10/100;
	
	$capping = my_package($user_id)[4];
	
	if($income > $capping){
		$income = $capping;
	}
	
	mysqli_free_result($query2);
	return $income;
}

function get_user_roi_date_new($user_id,$sys_date){
	
	$roi_date = array();
	$sql = NULL;
	//$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' AND mode = 1 AND invest_type > 0";
	$sql = "SELECT roi_date FROM reg_fees_structure WHERE user_id='$user_id' AND mode=1 ORDER BY id DESC LIMIT 1";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$roi_date = explode(',',$row['roi_date']);
		$count = $row['count'];
	}
	//print_r($roi_date);
	for($i = 0; $i < 15; $i++){
		if($roi_date[$i] > $sys_date){
			$date = $roi_date[$i]; break;
		}
	}
	$dates = "00-00-0000";
	if($date > 0){
		$dates = date('d M Y', strtotime($date));
	}
	mysqli_free_result($query);
	return $dates;
}


function get_user_account_info($user_id , $type , $mode){
	$sql = "SELECT COALESCE(SUM($mode),0) FROM account WHERE user_id = '$user_id' AND type = '$type'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_user_reg_mode_status($user_id){
	$sql = "SELECT MAX(mode) mode FROM reg_fees_structure WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$mode = $row['mode'];
	}
	
	/*switch($mode){
		case 99 : $status = "<B class='text-info'>Upgrade</B>";  break;
		case 66 : $status = "<B class='text-warning'>Booster</B>";  break;
		case 1 : $status = "<B class='text-primary'>General</B>";  break;
		case 189 : $status = "<B class='text-warning'>Stop ROI (189)</B>";  break;
		case 190 : $status = "<B class='text-warning'>Stop ROI (190)</B>";  break;
		default : $status = "<B class='text-danger'>Deactive</B>";
	}*/
	
	if($mode == 99 or $mode == 98){
		$status = "<B class='text-info'>Upgrade</B>";
	}
	elseif($mode == 66){
		$status = "<B class='text-warning'>Booster</B>";
	}
	elseif($mode == 1){
		$status = "<B class='text-primary'>General</B>";
	}
	elseif($mode == 189){
		$status = "<B class='text-warning'>Stop ROI</B>";
	}
	elseif($mode == 190){
		$status = "<B class='text-warning'>Stop ROI </B>";
	}
	else{
		$status = "<B class='text-danger'>Deactive</B>";
	}
	
	mysqli_free_result($query);
	return $status;
}


function get_user_kyc_approved_date($user_id){
	//$sql = "SELECT * FROM kyc_history WHERE user_id = '$user_id' AND ((kyc_type = 'all' OR kyc_type = 'ALL') AND (remarks = 'approve' OR remarks = 'APPROVED' OR remarks = 'APPROVE' OR remarks = 'ok' OR remarks = 'OK'))";
	$sql = "SELECT date FROM kyc_history WHERE user_id = '$user_id' AND (remarks = 'approve' OR 
	remarks = 'APPROVED' OR remarks = 'APPROVE' OR remarks = 'ok' OR remarks = 'OK') ORDER BY id DESC LIMIT 1";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	$date = "<span class='text-danger'>XXXXXXX</span>";
	if($num > 0){
		$date = date('d/m/Y', strtotime($result));
	}
	return $date;
}

function get_confirm_roi($user_id){
	
	$sql = "SELECT SUM(amount) FROM income WHERE user_id = $user_id AND type = 2";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	
	$amt = 0;
	if($result > 0){
		$amt = $result;
	}
	return $amt;
}

function user_user_total_roi($user_id){
	$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' AND mode in (1,177,189,190)";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$total_days = $row['total_days'];
		$profit = $row['profit'];
		$tot_roi = $profit * $total_days;
	}
	mysqli_free_result($query);
	$sql = "SELECT COALESCE(sum(amount/*+tax+tds_tax*/),0) amount FROM income WHERE user_id = '$user_id' and date < '2019-04-17' AND type = 2";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$amount = $row['amount'];
	}
	mysqli_free_result($query);
	$amount = 0;
	return $tot_roi+$amount;
}

function user_user_total_roi_new($user_id){
	$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$total_days = $row['total_days'];
		$profit = $row['profit'];
		$tot_roi = $profit*$total_days;
	}
	mysqli_free_result($query);
	return $tot_roi;
}

function get_user_cancel_investment_status($user_id){
	$sql = "SELECT mode FROM cancel_investment WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
	$query = query_execute_sqli($sql);
	$mode = mysqli_fetch_array($query)[0];
	$status = "";
	if($mode != ''){
		switch($mode){
			case 0 : $status ="<span class='label label-warning'>Cancellation process is pending</span>"; break;
			case 1 : $status = "<span class='label label-success'>Your Investment is Cancelled !</span>";  break;
			case 2 : $status = "
					<a href='index.php?page=cancel_investment' class='btn btn-danger btn-sm'>
						Click Here <i class='fa fa-arrow-right'></i>
					</a> For Cancel your investment"; 
			break;
		}
	}
	mysqli_free_result($query);
	return $status;
}

function get_user_cancel_investment($user_id){
	$sql = "SELECT mode FROM cancel_investment WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_withdrawal_day(){
	$closing_roi_payout = array();
	include "setting.php";
	
	$sql = "select * from withdrawal_crown_wallet group by date";
	$query = query_execute_sqli($sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0){
		while($ro = mysqli_fetch_array($query))
		{
			$withdrawal_day = date("d",strtotime($ro['date']));
			if(!in_array($withdrawal_day,$closing_roi_payout)){
				$closing_roi_payout[] = $withdrawal_day;
			}
		}
	}
	mysqli_free_result($query);
	return $closing_roi_payout;
}


function get_user_roi_income_count($user_id){
	$sql = "SELECT * FROM income WHERE user_id = '$user_id' AND type = 2";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_user_income_count($user_id){
	$sql = "SELECT * FROM income WHERE user_id = '$user_id' ";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_user_monthly_bonus_pend_month($user_id){	
	$sql = $q1 = NULL;
	$result = array(0,0);
	$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' order by id desc limit 1";
	$q1 = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($q1)){
		$update_fees = $row['update_fees'];
		$total_days = $row['total_days'];
	}
	
	$result[0] = $update_fees*10/100;
	$result[1] = $total_days - get_user_income_count($user_id);
	
	mysqli_free_result($q1);
	return $result;
}

function get_user_tot_roi_for_active_users($user_id){	
	$sql = $q1 = NULL;
	$result = 0;
	$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' AND mode = 1 
	ORDER BY id DESC LIMIT 1";
	$q1 = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($q1)){
		$profit = $row['profit'];
		$total_days = $row['total_days'];
	}
	$result = $profit*$total_days;
	
	mysqli_free_result($q1);
	return $result;
}	

function get_user_reg_active_mode($user_id){	
	/*$sql = "SELECT t1.*,t2.mode can_mode,t2.paid_date FROM reg_fees_structure t1
	LEFT JOIN cancel_investment t2 ON t1.user_id = t2.user_id
	WHERE t1.user_id = '$user_id' ORDER BY t1.id ASC LIMIT 1";*/
	$sql = "SELECT * FROM cancel_investment WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	while($row = mysqli_fetch_array($query)){
		$mode = $row['mode'];
		$remark = $row['remark'];
		$paid_date = date("Y-m-d",strtotime($row['paid_date']));
		
		$update_fees = $row['update_fees'];
	}
	mysqli_free_result($query);
	$my_plan = my_package($user_id);
	$plan_amt = $my_plan[5];
			
	$recvd_roi = get_user_roi_income($user_id);
	$pend_roi = $plan_amt-$recvd_roi;
	
	if($pend_roi > 0){
		switch($mode){
			case 0: $btn_status="<div class='alert alert-success'>Your Cancel Investment Request is Pending !</div>"; 
			break;
			case 1: 
				if($remark == ""){
					$btn_status="<div class='alert alert-warning'>Your Refund Will be issued by Date $paid_date </div>"; 
				}
				else{
					$btn_status="<div class='alert alert-success'>".'Your Refund is Processed "'.$remark.'" '."!!</div>";
				}
			break;
			case 2: $btn_status="<div class='alert alert-danger'>Your Investment Request  is Cancelled By Admin ! </div>"; 
			break;
		}
	}
	else{
	
		$btn_status = "<div class='alert alert-danger'>Your Principle Amount is Returned !</div>";
	}
	return $btn_status;
}
function get_business_data_report($sql){
	$sql = "select count(*) cnt,COALESCE(sum(roi),0) total_rental,COALESCE(sum(bin),0) total_growth,
	COALESCE( ( sum(update_fees)-(sum(roi)+sum(bin)) ),0) total_pending from ($sql) t1";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$total_data[0] = round($row['total_rental'],2);
		$total_data[1] = round($row['total_growth'],2);
		$total_data[2] = round($row['total_pending'],2);
		$total_data[3] = round($row['cnt'],2);
	}
	mysqli_free_result($query);
	return $total_data;
}
function get_random_lottery_no(){
	global $systems_date_time;
	$ticket_no = strtotime($systems_date_time);
	$ticket_no = substr(md5($ticket_no.rand(100000,999999)),0,12);
	do{
		$ticket_no = substr(md5($ticket_no.rand(100000,999999)),0,12);
		$sql = "select * from lottery_ticket where ticket_no='$ticket_no' limit 1";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		mysqli_free_result($query);
	}while($num != 0);
	return $ticket_no;
}
function week_lottery_exist($user_id,$date){
	return 1;
	include "setting.php";
	$date = date('Y-m-d', strtotime("next $lottery_result_day", strtotime($date)));
	$sql ="select * from lottery_ticket where user_id='$user_id' and DATE(`rdate`)='$date'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	mysqli_free_result($query);
	$result = $num > 0 ? 1 : 0;
	return $result;
}

function get_pre_nxt_date($date , $week_day){
	$income_date_dist = date("Y-m-d", strtotime($date."-1 day") );
	$next_date = date('Y-m-d', strtotime("next $week_day", strtotime($income_date_dist)));
	$next_date = date('Y-m-d', strtotime($next_date."0 day"));
	$previous_date = date('Y-m-d', strtotime("previous $week_day", strtotime($next_date)));
	$previous_date = date('Y-m-d', strtotime($previous_date."+1 day"));
	return array($previous_date,$next_date);
}
function get_pool_prize($user_id,$type){
	include "setting.php";
	$result = $query = $num = $st_date = $en_date = NULL;
	global $systems_date;
	$date =  $systems_date;//date('Y-m-d', strtotime($systems_date."- 1 Week"));
	
	if(date('D', strtotime($date)) == 'Sat'){
		$date = date('Y-m-d', strtotime($date."+ 1 Day"));
	}
	
	$p_date = date('Y-m-d', strtotime("previous $lottery_result_day", strtotime($date)));
	$next_date = date('Y-m-d', strtotime("next $lottery_result_day", strtotime($p_date)));
	$n_date = date('Y-m-d', strtotime($next_date."-1 day"));
		
 	$sql ="SELECT COALESCE(SUM(amount),0)
			FROM lottery_ticket WHERE DATE_FORMAT(rdate,'%Y-%m-%d') BETWEEN '$p_date' AND '$n_date'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0]*$fstff_prz_per[$type-1]/100;
	mysqli_free_result($query);
	return $result;
}
function get_pool_prize_old($user_id,$type){
	$result = $query = $num = $st_date = $en_date = NULL;
	if($type == 'current'){
		$sql ="SELECT COALESCE(SUM(amount),0) FROM lottery_ticket WHERE WEEKOFYEAR(date) = WEEKOFYEAR(NOW())";
	}
	elseif($type == 'next'){
		$sql ="SELECT DATE_ADD(rdate, INTERVAL 1 DAY) st_date, DATE_ADD(rdate, INTERVAL 7 DAY) en_date 
		FROM lottery_ticket WHERE WEEKOFYEAR(date) = WEEKOFYEAR(NOW())";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		$row = mysqli_fetch_array($query);
		$st_date = date('Y-m-d', strtotime($row[0]));
		$en_date = date('Y-m-d', strtotime($row[1]));
		
		$sql ="SELECT COALESCE(SUM(amount),0) FROM lottery_ticket WHERE DATE(`date`) BETWEEN '$st_date' AND '$en_date'";
	}
	elseif($type == 'tot_paid'){
		$sql ="SELECT COALESCE(SUM(ramount),0) FROM lottery_ticket WHERE `rank` > 0 ";
	}
	else{
		$sql = "SELECT COALESCE(SUM(ramount),0) FROM lottery_ticket WHERE date >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY AND date < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY";
	}

	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	$result = $num > 0 ? $result : 0;
	return $result;
}

function get_pool_prize_cnt($user_id,$type){
	$result = $query = $num = $st_date = $en_date = NULL;
	if($type == 'current'){
		$sql ="SELECT * FROM lottery_ticket WHERE WEEKOFYEAR(date) = WEEKOFYEAR(NOW())";
	}
	elseif($type == 'next'){
		$sql ="SELECT DATE_ADD(rdate, INTERVAL 1 DAY) st_date, DATE_ADD(rdate, INTERVAL 7 DAY) en_date 
		FROM lottery_ticket WHERE WEEKOFYEAR(date) = WEEKOFYEAR(NOW())";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		$row = mysqli_fetch_array($query);
		$st_date = date('Y-m-d', strtotime($row[0]));
		$en_date = date('Y-m-d', strtotime($row[1]));
		
		$sql ="SELECT * FROM lottery_ticket WHERE DATE(`date`) BETWEEN '$st_date' AND '$en_date'";
	}
	else{
		$sql ="SELECT * FROM lottery_ticket WHERE `rank` > 0 GROUP BY user_id ";
	}

	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}
function get_user_pool_prize_cnt($user_id,$type){
	$result = $query = $num = $st_date = $en_date = NULL;
	global $systems_date;
	if($type == 'current'){
		$sql ="SELECT * FROM lottery_ticket WHERE WEEKOFYEAR(date) = WEEKOFYEAR('$systems_date')";
	}
	elseif($type == 'next'){
		$sql ="SELECT DATE_ADD(rdate, INTERVAL 1 DAY) st_date, DATE_ADD(rdate, INTERVAL 7 DAY) en_date 
		FROM lottery_ticket WHERE WEEKOFYEAR(date) = WEEKOFYEAR('$systems_date')";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		$row = mysqli_fetch_array($query);
		$st_date = date('Y-m-d', strtotime($row[0]));
		$en_date = date('Y-m-d', strtotime($row[1]));
		
		$sql ="SELECT * FROM lottery_ticket WHERE DATE(`date`) BETWEEN '$st_date' AND '$en_date'";
	}
	else{
		$sql ="SELECT * FROM lottery_ticket WHERE `rank` > 0 GROUP BY user_id ";
	}

	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_next_draw_tot_seconds($user_id){
	global $systems_date;
	global $systems_date_time;
	$result = 0;
	$sql ="SELECT rdate FROM lottery_ticket WHERE `rank` = 0 AND WEEKOFYEAR(date) = WEEKOFYEAR('$systems_date') group by rdate";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$result = mysqli_fetch_array($query)[0];
	$cur_time = date('Y-m-d H:i:s');
	$swr = "SELECT TIMESTAMPDIFF(SECOND,'$cur_time', '$result') as seconds";
	$result = mysqli_fetch_array(query_execute_sqli($swr));
	$tot_second = $result[0];

	mysqli_free_result($query);
	return $tot_second;
}

function get_prize_amt_by_rank($rank){	
	$sql = "SELECT COALESCE(SUM(ramount),0) FROM lottery_ticket WHERE `rank` = '$rank'";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_prize_total_amt(){	
	$sql = "SELECT COALESCE(SUM(ramount),0) FROM lottery_ticket WHERE `rank` > 0";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_user_active_player($user_id){	
	$sql = "SELECT t1.* FROM lottery_ticket t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t2.`real_parent` = '$user_id' GROUP BY t1.user_id";
	
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	mysqli_free_result($query);
	$result = $num > 0 ? $num : 0;
	return $result;
}
function get_user_active_users($user_id){	
	$sql = "SELECT t1.* FROM reg_fees_structure t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user
	WHERE t2.`real_parent` = '$user_id' GROUP BY t1.user_id";
	
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	mysqli_free_result($query);
	$result = $num > 0 ? $num : 0;
	return $result;
}
function get_tora_id($userid){
	$sql = "SELECT tora_ref_id FROM users WHERE id_user = $userid";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
function get_member_previous_plan($userid){
	$sql = "SELECT invest_type FROM reg_fees_structure WHERE user_id = $userid order by id desc limit 1";
	$query = query_execute_sqli($sql);
	if(mysqli_num_rows($query) > 0){
		$plan_id = mysqli_fetch_array($query)[0];
		$sql = "select * from plan_setting where id = '$plan_id' order by id desc limit 1";
		$query1 = query_execute_sqli($sql);	
		while($rr = mysqli_fetch_array($query1)){
			$result['id'] 				= $rr['id'];
			$result['amount'] 			= $rr['amount'];
			$result['no_of_lottery'] 	= $rr['no_of_lottery']; 
			$result['tgaming_bonus'] 	= $rr['tgaming_bonus']; 
			$result['pv'] 				= $rr['pv']; 
			$result['share_bonus'] 		= $rr['share_bonus']; 
			$result['share_rf_bonus'] 		= $rr['share_rf_bonus']; 
		}
		mysqli_free_result($query1);
	}
	else{
		$result['id'] 				= 0;
		$result['amount']			= 0;
		$result['no_of_lottery'] 	= 0; 
		$result['tgaming_bonus'] 	= 0; 
		$result['pv'] 				= 0; 
		$result['share_bonus'] 		= 0; 
	}
	mysqli_free_result($query);
	return $result;
}

function get_totl_sub_menu($page){
	/*$sql = "SELECT * FROM menu WHERE parent_menu = (Select parent_menu FROM menu WHERE menu_file = '$page') 
	AND id NOT IN (SELECT id FROM menu WHERE menu_file = '$page') AND mode = 1";*/
	$sql = "SELECT * FROM menu WHERE parent_menu = (SELECT parent_menu FROM menu WHERE menu_file = '$page') 
	AND mode = 1 and parent_menu <> '0' ";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$menu = $row['menu'];
		$menu_file = $row['menu_file'];
		$parent_menu = $row['parent_menu'];
		$class = "btn btn-black btn-xs disabled";
		if($menu_file != $page){
			$class = "btn btn-success btn-xs";
		} ?>
		<a href="index.php?page=<?=$menu_file?>" class="<?=$class?>"><?=$menu?></a> <?php
	}
}
function get_user_total_lottery_ticket($user_id){
	$sql = "SELECT COALESCE(SUM(amount),0) amt FROM lottery_ticket WHERE user_id = $user_id";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_cur_week_lottery($user_id,$date){
	include "setting.php";
	if(date('D', strtotime($date)) == 'Sat'){
		$date = date('Y-m-d', strtotime($date."+ 1 Day"));
	}
	$week_pn = get_pre_nxt_date($date , $lottery_result_day);
	$p_date = $week_pn[0];
	$n_date = $week_pn[1];
	$sql="select * from lottery_ticket where user_id='$user_id' and DATE(`rdate`) BETWEEN '$p_date' AND '$n_date'";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}
?>