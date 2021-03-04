<?php
// select from setting table
include 'account_maintain.php';
$soft_chk = "LIVE";//LIVE
$query = query_execute_sqli("select * from setting ");
while($row = mysqli_fetch_array($query))
{
	//messages
	
	$welcome_message = $row['welcome_message'];
	$forget_password_message = $row['forget_password_message'];
	$payout_generate_message = $row['payout_generate_message'];
	$email_welcome_message = $row['email_welcome_message'];
	$direct_member_message = $row['direct_member_message'];
	$payment_request_message = $row['payment_request_message'];
	$payment_transfer_message = $row['payment_transfer_message'];
	$user_pin_generate_message = $row['user_pin_generate_message'];
	$epin_generate_message = $row['epin_generate_message'];
	$member_to_member_message = $row['member_to_member_message']; 
	$transfer_to_member_message = $row['transfer_to_member_message']; 
	$direct_income_percent = $row['direct_income_percent'];
	$pair_point_percent = $row['binary_income_percent']; 
	$make_shopping_email = $row['make_shopping_email'];  
	$minimun_invests_amount = $row['minimun_invest'];
	$max_transfer_count = $row['transfer_count'];
	$level = $row['parent_limit'];
	$minimum_withdrawal = $row['minimum_withdrawal'];
	$minimum_withdrawal1 = $row['direct_spon_per'];
	$direct_spon_per_month = $row['direct_spon_per_month'];
	$per_day_multiple_pair = 1;$row['per_day_multiple_pair'];
	$token_rate = $row['per_day_max_binary_inc'];
	$fee_of_registration = $row['registration_fees'];
}
mysqli_free_result($query);
$minimum_withdrawal_usd = 0.05;
$pos = 0;

$virtual_parent_condition = 1;
// starting year and month
$start_year = 2010;
$start_month = 01;

// registration types
$type[0] = "A";
$type[1] = "B";

//income type direct
$income_type[1] = 1;  // Daily ROI
$income_type[2] = 2;  // Level Income
$income_type[3] = 3;  // Principal Return


$income_type_desc[1] = 'Daily ROI';  
$income_type_desc[2] = 'Level Income';
$income_type_desc[3] = 'Principal Return';

$sql = "select * from plan_setting order by id asc ";
$q = query_execute_sqli($sql);
$plan_count = mysqli_num_rows($q);
$p=0;
while($row = mysqli_fetch_array($q))
{
	$plan_id[$p] = $row['id'];
	$plan_name[$p] = $row['plan_name'];
	$set_amount[$p] = $row['amount'];
	$set_max_amount[$p] = $row['max_amount'];
	$set_roi_bonus[$p] = $row['roi_bonus'];
	$set_binary_percent[$p] = $row['binary_percent'];
	$set_days[$p] = $row['days'];
	$set_capping[$p] = $row['capping'];
	$set_plan_pv[$p] = $row['amount'];
	$p++; 	 	
}
mysqli_free_result($q);

for($pi = 0; $pi < $plan_count; $pi++)
{ 
$daily_income_percent[$pi][0] = $set_amount[$pi];  // fees of registration or updatation
$daily_income_percent[$pi][1] = $set_max_amount[$pi];   // set_daily_profit
$daily_income_percent[$pi][2] = $set_binary_percent[$pi];  // set_binary_percent
$daily_income_percent[$pi][3] = $plan_name[$pi];  // package name 
$daily_income_percent[$pi][4] = $plan_id[$pi];  // package id
$daily_income_percent[$pi][5] = $set_refferal_percent[$pi];  // set_refferal_percent
$daily_income_percent[$pi][6] = $set_capping[$pi];  // set_capping
//$daily_income_percent[$pi][7] = $set_booster[$pi];  // set_booster
//$daily_income_percent[$pi][8] = $set_dbooster[$pi];  // set_dbooster
$daily_income_percent[$pi][9] = $set_daily_upto[$pi];  // set_daily_upto
}
// registration mode
$level_income_setting = array();
$q = query_execute_sqli("select * from level_income order by id asc ");
$level_count = mysqli_num_rows($q);
$p=0;
while($row = mysqli_fetch_array($q))
{
	$level_income_setting[$p] = $row['percent'];
	$p++; 	 	
}
mysqli_free_result($q);



$add_fund_mode[0] = "neft";
$add_fund_mode[1] = "rtgs";
$add_fund_mode[2] = "imps";
$add_fund_mode[3] = "cash";
$add_fund_mode[4] = "cheque";
$add_fund_mode[5] = "demanddraft";
$add_fund_mode[6] = "phonepay";
$add_fund_mode[7] = "paytm";

$add_fund_mode_value[0] = "NEFT";
$add_fund_mode_value[1] = "RTGS";
$add_fund_mode_value[2] = "IMPS";
$add_fund_mode_value[3] = "Cash in Hand";
$add_fund_mode_value[4] = "Cheque";
$add_fund_mode_value[5] = "Demand draft";
$add_fund_mode_value[6] = "Phone Pay";
$add_fund_mode_value[7] = "Paytm";


$fund_transfer_mode[0] = "wallet";
$fund_transfer_mode[1] = "ge_currency";
$fund_transfer_mode[2] = "liberty";
$fund_transfer_mode[3] = "Perfect_money";
$fund_transfer_mode[4] = "alert_pay";
$fund_transfer_mode[5] = "uae_exchange";
$fund_transfer_mode[6] = "western_union";
$fund_transfer_mode[7] = "credit_card";
$fund_transfer_mode[8] = "bank_wire";


// registration mode value

$fund_transfer_mode_value[0] = "E-Wallet";
$fund_transfer_mode_value[1] = "Ge Currency";
$fund_transfer_mode_value[2] = "Liberty Reserve";
$fund_transfer_mode_value[3] = "Perfect Money"; 
$fund_transfer_mode_value[4] = "Alert Pay";
$fund_transfer_mode_value[5] = "UAE Exchange";
$fund_transfer_mode_value[6] = "Western Union";
$fund_transfer_mode_value[7] = "Credit Card";
$fund_transfer_mode_value[8] = "Bank Wire";

//mail setting
$from_email = "noreply@unicgrow.com";
$SmtpServer="unicgrow.com";
$SmtpPort="465"; //default
$SmtpUser="support@unicgrow.com";
$SmtpPass="Unic@123"; // Password of this email



///////// Bank Wire Withdrawal Tax
$withdrwal_money_tax = 10;
$matching_percent = 20;
$tds = 0;

///////// Bank Wire Withdrawal Tax

$withdrawal_request_day = array("USD"=>array(06,16,26));
$binary_pay_day = "Saturday";
$binary_froward_day = "Friday";
$min_matching_business = 500;


//////

$minimum_transfer_amt = 5;
$setting_min_withdrawal = 100;
$setting_transfer_tds = $setting_withdrawal_tax = 5;
$setting_admin_tax = 5;
$withdrawal_crowd_tax = 0;
$transfer_wallet_tax = 1;

// BLOCKCHIAN.INFO SETTING START
$hit = 0;
$deduct_time = "- 2 Hour" ;// Hour
$api_deduct_time = "-12 Hour";
$satohi_value = 100000000; // Not Changable
$mining_hour = 48; // Not Changable
// BLOCKCHIAN.INFO SETTING END

//$admin_tax_roi = 15;
//$admin_tax_binary = 20;


$bank_name_for_fund = array('AXIS_BANK','HDFC_BANK','KOTAK_MAHINDRA_BANK','IDFC_BANK','RBL_BANK','INDUSIND_BANK','SBI_BANK','PNB_BANK','BOB_BANK','BOI_BANK','CORPORATION_BANK');

$nominee_relation = array('father','mother','son','daughter','brother','sister','wife','aunty','uncle');
//$com_wallet_crdr = "2,4,15,21,22,29";
//$e_wallet_crdr = "6,9,10,11,13,17,19,20,24";

$com_wallet_crdr = array('2','4','15','21','22','29','90');
$e_wallet_crdr = array('6','9','10','11','13','17','19','20','24','50','60','70');
//$e_wallet_crdr = array('17','11','50','60','70');
?>