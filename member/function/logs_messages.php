<?php

//log data


$data_log[1][0] = "edit profile";  // edit title
$data_log[1][1] = "Profile updated of user ".$username." on ".$date." by ".$updated_by;  // edit data
$data_log[2][0] = "Edit Password";  // edit title
$data_log[2][1] = "Password updated of user ".$username." on ".$date." by ".$updated_by;  // edit data
 
$data_log[3][0] = "New user registration";  //  Wallet title
$data_log[3][1] = "New user ".$username." registered with Amount ".$reg_amount." on ".$date." By ".$real_parent_username_log;  // other data
$data_log[5][0] = "New wallet inserted";  //  edit title
$data_log[5][1] = "wallet inserted of new user ".$username;  // registration data
$data_log[4][0] = "Update wallet";  // network title
$data_log[4][1] = "Update wallet of ".$log_username." by receiving amount ".$income_log." USD on ".$date." As ".$income_type_log;  //  Wallet data
$data_log[6][0] = "User Activate";  // network title
$data_log[6][1] = "User ".$username_log." Successfully Activate by using E-pin : ".$epin_log." on ".$date; //  Wallet data

$data_log[7][0] = "E-pin Transfer";  // E-pin Transfer
$data_log[7][1] = "User ".$username_log." E-pin ".$epin_name." Transfer  To ".$transfer_log_name." on ".$date; 
$data_log[8][0] = "Update wallet";  // network title
$data_log[8][1] = "Update wallet of ".$username_log." by reducing amount ".$income_log." USD by ".$for." on ".$date; //  Wallet data

$data_log[9][0] = "E-pin generate";  // network title
$data_log[9][1] = "User ".$username_log." Generate E-pin Amount Of : ".$epin_log." on ".$date;  //  Wallet data
$data_log[10][0] = "E-pin Used";  // network title
$data_log[10][1] = "User ".$real_parent_username_log." Used E-pin : ".$reg_pin_used." for New User ".$username." on ".$date;  //  Wallet data


$data_log[11][0] = "New Investment";  // network title
$data_log[11][1] = "User ".$username_log." make new Investment of amount USD ".$invest_amount." as ".$invest_plan." for ".$days." Days , on ".$date;  //  Wallet data
$data_log[12][0] = "Edit Wallet Amount";  // network title
$data_log[12][1] = "Wallet has edit of user ".$username_log." by amount USD ".$edit_amount." by The ADMIN on ".$date;  

$data_log[13][0] = "Add Funds Request";  // network title
$data_log[13][1] = "User ".$username_log." Request for Add Amount USD ".$amount." By ".$add_by." to Admin on ".$date;  

$data_log[14][0] = "Accept Add Funds Request";  // network title
$data_log[14][1] = "Request by user ".$username_log." for Add Funds of amount USD ".$amount." has accepted by The ADMIN on ".$date;  //  Wallet data


//  Wallet data
$data_log[15][0] = "Update Network setting";  // network title
$data_log[15][1] = "update network setting by admin on ".$date;  //  Wallet data


$data_log[16][0] = "Edit Wallet Amount";  // network title
$data_log[16][1] = "Wallet Amount of USD ".$db_amount." has edited by amount USD ".$amount." of User ".$log_username." by The Royal Trader Group Admin on ".$date;  //  Wallet data

$data_log[17][0] = $title_block;  // network title
$data_log[17][1] = "User ".$log_username." has been ".$blocked." by The Admin on ".$date."<br> His Information is : <br> Main Wallet Amount : ".$wallet_amount."USD <br> E-Wallet Amount : ".$ewallet_amount."USD <br> His Investment : ".$investment."<br> All amount has Changed. Now his Balance and investments are 0.";  //  Wallet data

$data_log[18][0] = "Make Shopping";  // network title
$data_log[18][1] = "User ".$pay_request_username." have order for ".$product_name." of amount ".$product_cost." USD on ".$date." Your Wallet amount  is Reduced by amount ".$product_cost." RC, Your Left Balance is RC".$left_amount;  //  Wallet data

$data_log[19][0] = "Withdrawal Balance Request";  // network title
$data_log[19][1] = "User ".$pay_request_username." have request for Withdrawal his Balance of amount ".$request_amount." USD by ".$pay_mode." on ".$request_date;  //  Wallet data

$data_log[20][0] = "Accept Withdrawal Balance Request";  // network title
$data_log[20][1] = "Request by user ".$username_log." for Withdrawal his Balance of amount ".$req_amount." USD has accepted by The ADMIN on ".$accept_date;   //  Wallet data

$data_log[21][0] = "Cancel Withdrawal Balance Request";  // network title
$data_log[21][1] = "Request by user ".$username_log." for Withdrawal his Balance of amount ".$req_amount." USD has been Cancled by The ADMIN on ".$accept_date;   //  Wallet data

$data_log[22][0] = "New Investment For $other_user By $login_id";  // network title
$data_log[22][1] = "User ".$username_log." make new Investment of amount USD ".$invest_amount." as ".$invest_plan." for ".$days." Days , on ".$date." For ".$other_user;  //  Wallet data

$data_log[23][0] = "Update wallet For Binary Recovery";  // network title
$data_log[23][1] = "Update wallet of ".$log_username." by receiving amount ".$income_log." USD on ".$date." As ".$income_type_log;  //  Wallet data

$data_log[24][0] = "Invalid Withdrawal Transaction";  // network title
$data_log[24][1] = "Invalid  Withdrawal Transaction By ".$pay_request_username." of Real Amount ".$real_amount." USD and Wrong Amount ".$request_amount." on ".$date." and Current Balance is - ".$current_amount;  //  Wallet data

$data_log[25][0] = "Add Kyc Docs";  // edit title
$data_log[25][1] = "Add Kyc Docs of user ".$username." on ".$date." by ".$updated_by;  // edit data

$data_log[26][0] = "Request Epin To Company";  // network title
$data_log[26][1] = "User ".$username_log." Request for Epin to Admin on ".$date;

$data_log[26][0] = "Admin Access Page";  // network title
$data_log[26][1] = "Admin ".$username_log." Request for Page $access_page on ".$date;
//log data

$log_type[1] = 1;  // profile log
$log_type[2] = 2;  // password log
$log_type[3] = 3;  // registration log
$log_type[4] = 4;  //  Update wallet add
$log_type[5] = 5;  //  investment Logs 
$log_type[6] = 6;  //  Update wallet reduce
$log_type[7] = 7;  //  User E-pin Transfer
$log_type[8] = 8;  //  shopping
$log_type[9] = 9;  //  Generate Epin
$log_type[10] = 10;  // block member
$log_type[11] = 11;  //  Board Voucher Generated
$log_type[12] = 12;  //  Transfer Board Voucher
$log_type[13] = 13;  //  receive board voucher
$log_type[14] = 14;  //  update setting
$log_type[15] = 15;  //  Board Break
$log_type[16] = 16;  //  wallet amount edit
$log_type[17] = 17;  //  block member
$log_type[18] = 18;  //  Other User Top Up
$log_type[19] = 19;  //  Generate E-pin 
$log_type[20] = 20;  //  Binary Recovery
$log_type[21] = 21;  //  Invalid Withdrawal Transaction
$log_type[22] = 22;  //  Add Kyc Docs
$log_type[23] = 23;  //  Request Epin

$log_type[26] = 26;  //  Admin Accesss Page

// sms message 

$message1 = "Hi $username, You have Invested $invest_amount USD on $date. Thanks From unicgrow.com";
