<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");
include("../function/daily_income.php");

	
	$pay_day = date("D", strtotime($systems_date) );
	if($pay_day == 'Sat' or $pay_day == 'Sun')
	{
		print "Investment Income Can not be distributed Today !! ";
	}
	else
	{	
		query_execute_sqli("update income_process set mode = 1 ");
		get_daily_income($systems_date);
		//get_monthly_only_income($systems_date);
		query_execute_sqli("update income_process set mode = 0 ");
	}
	
?>