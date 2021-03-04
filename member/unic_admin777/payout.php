<?php
include("../config.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/pair_point_income.php");
include("../function/daily_income.php");
include("../function/send_mail.php");

	query_execute_sqli("update income_process set mode = 1 ");
	
	pair_point_income($systems_date);
	$pay_day = date("D", strtotime($systems_date) );
	if($pay_day == 'Sat' or $pay_day == 'Sun')
		print "Investment Income Can not be distributed Today !! ";
	else
		get_daily_income($systems_date);
	get_monthly_only_income($systems_date);
	query_execute_sqli("update income_process set mode = 0 ");

	$from = "alert@delidiamond.com";
	$title = "Cron Job";
	$to = "unicgrow@gmail.com";
	$full_message = "Testing";
	$from = "alert@delidiamond.com";
	$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);	
	$SMTPChat = $SMTPMail->SendMail();	
?>
