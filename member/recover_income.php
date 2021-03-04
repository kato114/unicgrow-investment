<?php
ini_set("display_errors",'on');
session_start();
require_once("config.php");
require_once("function/setting.php");
require_once("function/functions.php");
$s_date = date("Y-m-d",strtotime('2020-08-30'));
$e_date = date("Y-m-d",strtotime('2020-11-21'));
while($s_date  < $e_date){
	$systems_date = $s_date;
	$systems_date_time = $s_date." ".date("H:i:s");	
	$pay_day = date("d", strtotime($systems_date));
	if($pay_day == $closing_roi_payout[0] or $pay_day == $closing_roi_payout[1] or $pay_day == $closing_roi_payout[2]){
		/*$s_Date = date("Y-m-01",strtotime($systems_date." - 1 month"));
		$e_Date = date("Y-m-t",strtotime($s_Date));*/
		$sql = "select sum(amount),user_id from income where date < '$systems_date' and mode=0 and (type='".$income_type[2]."') group by user_id";
		$query = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($query))
		{
			$net_amount = $amount = $row[0];
			$user_id = $row[1];
			
			$tax_amount1 = $amount * $setting_withdrawal_tax/100;
			$amount = $amount - $tax_amount1;
			$tax_amount2 = $amount * $setting_admin_tax/100;
			$amount = $amount - $tax_amount2;
			$tax_amount = $tax_amount1 + $tax_amount2;
			$sql = "insert into withdrawal_crown_wallet(user_id , request_crowd , ac_type , date , description,tax, 	cur_bitcoin_value)
					values('$user_id','$amount','2','$systems_date_time','Auto-Withdrawal','$tax_amount1','$tax_amount2')";
			print $sql."<br><br>";
			/*if(query_execute_sqli($sql)){
				$sql = "update income set mode = 1 where user_id = '$user_id' and date < '$systems_date' and mode=0 and (type='".$income_type[2]."')";
				query_execute_sqli($sql);
				$wal_amt = get_wallet_amount($user_id)+$net_amount;	
				insert_wallet_account($user_id , $user_id , $net_amount , $systems_date_time , $acount_type[4] , $acount_type_desc[4], 1 ,  $wal_amt , $wallet_type[1],$remarks = "ROI INCOME");
				insert_wallet_account($user_id , $user_id , $amount , $systems_date_time , $acount_type[15], 'Roi Withdrawal BY Member', $mode=2 , $wal_amt-$amount ,$wallet_type[1],$remarks = "ROI INCOME");
				$wal_amt = $wal_amt-$amount;
				insert_wallet_account($user_id , $user_id , $tax_amount , $systems_date_time , $acount_type[16],'Roi Withdrawal Tax Pay BY Member', 2 ,  $wal_amt- $tax_amount , $wallet_type[1],$remarks = "ROI INCOME");
			}*/	
		}
		mysqli_free_result($query);
	}
$s_date = date("Y-m-d",strtotime($s_date." +1 day"));
}


