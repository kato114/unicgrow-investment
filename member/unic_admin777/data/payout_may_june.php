<?php
include('../../security_web_validation.php');
?>
<?php
ini_set("display_errors",'on');
include("../../config.php");
include("../../function/functions.php");
include("../../function/pair_point_income.php");
include("../../function/daily_income.php");

//$date = $systems_date;
for($i = 1; $i <= 61; $i++)
{
	$mm = 05;
	$dd = $i;
	if($i >= 32)
	{	$mm = 06;
		$dd = $i-31;
	}
	$date = "2015-$mm-$dd";
	payout_may_june($date);
	
}

function payout_may_june($date)
{	
	$systems_date = $date;
	$day = date('d',strtotime($date));
	$s_time = date();
	query_execute_sqli("insert into payout_entry (date ,pay_start_time ,pay_end_time ,mode ,pay_type)
										values('$date' , CURTIME(), CURTIME(), '1' , '1')");
	query_execute_sqli("update income_process set mode = 1 ");
	
	pair_point_income($systems_date);
	query_execute_sqli("update income_process set mode = 0 ");
	
	query_execute_sqli("insert into payout_entry (date ,pay_start_time ,pay_end_time ,mode ,pay_type)
										values('$date' , CURTIME(), CURTIME(), '2' , '1')");
	if($day == '05')
	{
		query_execute_sqli("insert into payout_entry (date ,pay_start_time ,pay_end_time ,mode ,pay_type)
											values('$date' , CURTIME(), CURTIME(), '1' , '2')");
		query_execute_sqli("update income_process set mode = 1 ");
		get_daily_income($systems_date);
		
		query_execute_sqli("update income_process set mode = 0 ");
		
		query_execute_sqli("insert into payout_entry (date ,pay_start_time ,pay_end_time ,mode ,pay_type)
										values('$date' , CURTIME(), CURTIME(), '2' , '2')");
	}
	else
	{
		print "<p></p><font size=5 color=\"#FF0000\">Investment Income Can't Distribute Today !! </font>";
	}
}
		
?>	