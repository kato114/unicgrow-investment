<?php

//log data


$data_log[1][0] = "Stop Roi";  // edit title
$data_log[1][1] = "Stop Roi of user ".$stop_roi_username." on ".$date." by ".$_SESSION['topride_admin_name'];  // edit data
$data_log[2][0] = "Topup For Activation";  // edit title
$data_log[2][1] = "Topup For Activation of User ".$topup_username." make new Investment of amount USD ".$invest_amount." as ".$invest_plan." for ".$days." Month , on ".$date;  //  Wallet data  // edit data
 
$data_log[3][0] = "Power Topup";  //  Wallet title
$data_log[3][1] = "Power Topup of User ".$username_log." make new Investment of amount USD ".$invest_amount." as ".$invest_plan." for ".$days." Days , on ".$date;  //  Wallet data


$data_log[4][0] = "Sponser Change";  // network title
$data_log[4][1] = "User ".$log_username." sponser changed from ".$pre_spon_name_log." to ".$next_spon_name_log." by ".$_SESSION['topride_admin_name']." on ".$date;

//log data

$log_type[1] = 1;  // Stop Roi
$log_type[2] = 2;  // Topup For Activation
$log_type[3] = 3;  // Power Topup
$log_type[4] = 4;  //  Sponser Change


