<?php
/*
	CoinPayments.net API Example
	Copyright 2014 CoinPayments.net. All rights reserved.	
	License: GPLv2 - http://www.gnu.org/licenses/gpl-2.0.txt
*/
session_start();
ini_set('display_errors','on');
require('config.php');
include("function/setting.php");
include("function/functions.php");
include("function/send_mail.php");
include("create_withdrawal/coinpayments.inc.php");
include("function/blockchain_trasaction.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];
extract($_REQUEST);
$time = date("Y-m-d H:i:s");
$investment = $_SESSION['investment'];
$currency_sign = $payment_method == 1 ? 'Bitcoin' : 'ETH';
$cps = new CoinPaymentsAPI();
$cps->Setup($Private_Key, $Public_Key);
$req = array(
			'amount' => 1,
			'currency1' => "USD",
			'currency2' => "BTC",
			'buyer_email' => 'unicgrow@gmail.com',
		);
//print_r($req);
$result = $cps->CreateTransaction($req);
print_r($result);