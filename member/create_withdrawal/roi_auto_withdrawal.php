<?php
ini_set('display_errors','on');
/*$db_host = "localhost";
$db_username = "mahendra";
$db_password = "mahendra123";
$db = "topride_business_test2";
$con=mysqli_connect($db_host,$db_username,$db_password);
mysqli_select_db($db,$con);*/
//$systems_date_time = "2020-11-21 05:05:30"; 

require('../config.php');
require('../function/functions.php');
require('../function/setting.php');
unset($amount);
$daily_roi_tax = 0;
$minimum_roi_auto_withdrawal= 1;
$pay_day = date("d", strtotime($systems_date));
/*if($pay_day == $closing_binary_payout[0] or $pay_day == $closing_binary_payout[1] or $pay_day == $closing_binary_payout[2])*/
if($pay_day == $closing_roi_payout[0] or $pay_day == $closing_roi_payout[1] or $pay_day == $closing_roi_payout[2])
{
	$sql = "select * from withdrawal_crown_wallet where DATE(date)='$systems_date' order by id desc limit 1";
	$quer = query_execute_sqli($sql); 
	$nuq = mysqli_num_rows($quer);
	mysqli_free_result($quer);
	if($nuq == 0){
		$sql = "select t1.* from wallet t1 
				left join kyc t2 on t1.id = t2.user_id
				where t1.amount > '$minimum_roi_auto_withdrawal' 
				and t2.mode_pan=1 and t2.mode_id=1 and t2.mode_photo=1 and t2.mode_chq=1 and t2.user_id is not null
				group by t1.id";
		/*$sql = "select t1.* from wallet t1 
				left join kyc t2 on t1.id = t2.user_id
				where t1.amount > '$minimum_roi_auto_withdrawal' 
				and t2.ifsc <> '' and t2.bank_ac <> '' and t2.user_id is not null
				group by t1.id";*/
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);	
		if($num > 0){ 
			while($rt = mysqli_fetch_array($query)){
				$id = $rt['id'];
				$net_amount = $amount = $rt['amount'];
				$sql = "insert into withdrawal_crown_wallet(user_id , request_crowd , ac_type , date , description,tax, cur_bitcoin_value)
values('$id','$amount','1','$systems_date_time','Auto-Withdrawal','$tax_amount1','$tax_amount2')";
				if(get_type_user($id) == "B"){
					if(query_execute_sqli($sql)){
						$w_id = get_mysqli_insert_id();
						$sql = "update wallet set amount = amount - '$amount' where id='$id'";
						query_execute_sqli($sql);
						$wal_amt = get_user_allwallet($id,'amount');
						insert_wallet_account($id , $id , $amount , $systems_date_time , $acount_type[15] , $acount_type_desc[15], 2 , $wal_amt , $wallet_type[1]);
					}
				}
			}
		}
		mysqli_free_result($query);
	}
	else{
		print "<h1>Withdrawal Al-Ready Distributed Today !! <h1>";
	}
	//sqli_free_result($query);
}
else{
	print "<h1>Withdrawal Closing Date Of Every Month is $closing_roi_payout[0]st Or $closing_roi_payout[1]th Or $closing_roi_payout[2]th ";
}
$vars = array_keys(get_defined_vars());
foreach($vars as $var) {
	unset(${"$var"});
	${"$var"} = NULL;
}
unset($vars,$i);
free_object_memory();
unset($vr);

		