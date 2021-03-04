<?php
include('../../security_web_validation.php');
?>
<?php
// select from setting table

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
	$ten_level_sponsor_percent = $row['ten_level_sponsor_percent'];
	$per_day_multiple_pair = $row['per_day_multiple_pair'];
	$per_day_max_binary_inc_db = $row['per_day_max_binary_inc'];
}


$q = query_execute_sqli("select * from reward_plan ");
while($row = mysqli_fetch_array($q))
{
	for($pi = 1; $pi < 11; $pi++)
  	{
		$reward_lvl_pair_db[$pi] = $row['pair_'.$pi];
		$reward_lvl_inc_db[$pi] = $row['reward_'.$pi];
	}
}		

$pos = 0;

$virtual_parent_condition = 1;
// starting year and month
$start_year = 2010;
$start_month = 01;




// registration types
$type[0] = "A";
$type[1] = "B";

//product id for registration

$product_id[1] = "reg";

//income

/*$income[1] = 20;  //survey income
$income[2] = 30;  //direct member income
*/

// registration fees

$minimum_registration_fees = 0;
$registration_fees = 500;
$minimum_investment = 10;


//income type direct
$income_type[1] = 1;  // direct income type
$income_type[2] = 2;  // daily income type
$income_type[3] = 3;  // binary income
$income_type[4] = 4;  // oil and gold trading income
$income_type[5] = 5;  // reward income


// daily invest details

$q = query_execute_sqli("select * from plan_setting order by id asc ");
$plan_count = mysqli_num_rows($q);
$p=0;
while($row = mysqli_fetch_array($q))
{
	$plan_id[$p] = $row['id'];
	$plan_name[$p] = $row['plan_name'];
	$amount[$p] = $row['amount'];
	$profit[$p] = $row['profit'];
	$days[$p] = $row['days'];
	$p++; 	 	
}

for($pi = 0; $pi < $plan_count; $pi++)
{ 
$daily_income_percent[$pi][0] = $amount[$pi];  // fees of registration or updatation
$daily_income_percent[$pi][1] = $profit[$pi];   // pecrent
$daily_income_percent[$pi][2] = $days[$pi];  // days
$daily_income_percent[$pi][3] = $plan_name[$pi];  // package name 
$daily_income_percent[$pi][4] = $plan_id[$pi];  // package id
}


// oil invest details

$q_oil = query_execute_sqli("select * from plan_setting_2 where plan_type = 1 order by id asc ");
$plan_count_oil = mysqli_num_rows($q_oil);
$p=0;
while($row1 = mysqli_fetch_array($q_oil))
{
	$oil_trading_plan_id[$p] = $row1['id'];
	$oil_trading_plan_name[$p] = $row1['plan_name'];
	$oil_trading_amount[$p] = $row1['amount'];
	$oil_trading_profit[$p] = $row1['profit'];
	$oil_trading_days[$p] = $row1['days'];
	$p++; 	 	
}

for($pi = 0; $pi < $plan_count_oil; $pi++)
{ 
$oil_trading_daily_income_percent[$pi][0] = $oil_trading_amount[$pi];  // fees of registration or updatation
$oil_trading_daily_income_percent[$pi][1] = $oil_trading_profit[$pi];   // pecrent
$oil_trading_daily_income_percent[$pi][2] = $oil_trading_days[$pi];  // days
$oil_trading_daily_income_percent[$pi][3] = $oil_trading_plan_name[$pi];  // package name 
$oil_trading_daily_income_percent[$pi][4] = $oil_trading_plan_id[$pi];  // package id
}


// gold invest details

$q_gold = query_execute_sqli("select * from plan_setting_2 where plan_type = 2 order by id asc ");
$plan_count_gold = mysqli_num_rows($q_gold);
$p=0;
while($row_gold = mysqli_fetch_array($q_gold))
{
	$gold_trading_plan_id[$p] = $row_gold['id'];
	$gold_trading_plan_name[$p] = $row_gold['plan_name'];
	$gold_trading_amount[$p] = $row_gold['amount'];
	$gold_trading_profit[$p] = $row_gold['profit'];
	$gold_trading_days[$p] = $row_gold['days'];
	$p++; 	 	
}

for($pi = 0; $pi < $plan_count_gold; $pi++)
{ 
$gold_trading_daily_income_percent[$pi][0] = $gold_trading_amount[$pi];  // fees of registration or updatation
$gold_trading_daily_income_percent[$pi][1] = $gold_trading_profit[$pi];   // pecrent
$gold_trading_daily_income_percent[$pi][2] = $gold_trading_days[$pi];  // days
$gold_trading_daily_income_percent[$pi][3] = $gold_trading_plan_name[$pi];  // package name 
$gold_trading_daily_income_percent[$pi][4] = $gold_trading_plan_id[$pi];  // package id
}


// registration mode

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

$from = "noreply@canindia.co.in";
$SmtpServer="mail.canindia.co.in";
$SmtpPort="25"; //default
$SmtpUser="noreply@ecocaptalfx.com";
$SmtpPass="9829061228";


//Forex Trading Capping Percentage
$forex_caping_plan[1] = 6000;  // Plan 1
$forex_caping_plan[2] = 18000;  // Plan 2
$forex_caping_plan[3] = 40000;  // Plan 3
$forex_caping_plan[4] = 80000;  // Plan 4

///////// E-Pin Generate Tax
$pin_gen_tax = 10;

///////// Bank Wire Withdrawal Tax
$withdrwal_money_tax = 15;

///////// Bank Wire Withdrawal Tax
$minimum_roi_amount = 200;
$daily_roi_tax = 15;
?>