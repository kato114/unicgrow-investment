<?php
ini_set("display_errors","off");
include("../../../config.php");
include("../../../function/setting.php");
include("../../../function/functions.php");
include("../../../function/daily_income.php");
include("../../../function/send_mail.php");
$pay_day = date("D", strtotime($systems_date));
if($pay_day == 'Sat' or $pay_day == 'Sun')
{
	print "<font size=5 color=\"#FF0000\">
	Investment Income Can't Distributed Today !!
	</font>";
}
else
{
	query_execute_sqli("update income_process set mode = 1 ");
	get_daily_income($systems_date_time);
	query_execute_sqli("update income_process set mode = 0 ");
}	
/*$s_date = date("Y-m-d",strtotime('2020-11-21'));
$e_date = date("Y-m-d",strtotime('2020-12-01'));
while($s_date  < $e_date){
	$systems_date_time = $s_date." ".date("H:i:s");	
	
	query_execute_sqli("update income_process set mode = 1 ");
	get_daily_income($systems_date_time);
	query_execute_sqli("update income_process set mode = 0 ");
	
	/*$s_date = date("Y-m-d",strtotime($s_date." +1 day"));
}*/

?>