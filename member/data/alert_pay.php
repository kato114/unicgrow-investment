<?php
include('../security_web_validation.php');
?>
<?php 
include("condition.php");

$token = $_GET['token'];
?>
<h1 align="left">Alert Pay</h1>
<?php
$string = urlencode($token);
$alert_pay_id = $_SESSION['dccan_alert_pay_customer_id'];
if($alert_pay_id != 0)
{
	$date = date('Y-m-d');
	query_execute_sqli("insert into temp_payment (user_id , date , status , alert_token) values ('$alert_pay_id' , '$date' , 1 , '$string') ");
	unset($_SESSION['dccan_alert_pay_customer_id']);
	print "<font color=\"#313164\" size=\"+2\">Your Request has been Send To Admin !<br><br>Please Save this TOKEN for further use!!</font>";
}	


/*print $string;
$ap_custfirstname = 'Rajesh';
$ap_custcity = 'Jaipur';
$ap_merchant = 'merchant';
$ap_customer_id = 1;
$ap_status = 'Sfuccess';
$ap_referencenumber = 5465;
$ap_currency = '$USD';
$amount = 125;

if($ap_status == 'Success')
{
	$date = date('Y-m-d');
	$pay_mode = "Alert Pay";
	query_execute_sqli("insert into add_funds (user_id , amount , date , mode , payment_mode) values ('$ap_customer_id' , '$amount' , '$date' , 1 , '$pay_mode') "); 
	
	$q = query_execute_sqli("select * from wallet where id = '$ap_customer_id' ");
	while($r = mysqli_fetch_array($q))
	{
		$wallet_amount = $r['amount'];
	}	
	$total_amount = $wallet_amount+$amount;
	query_execute_sqli("update wallet set amount = '$total_amount' where id = '$ap_customer_id' ");
	
	$log_username = get_user_name($ap_customer_id);
	$income_log = $amount;
	$income_type_log = "Add Fund via Alert Pay";
	include("function/logs_messages.php");
	data_logs($ap_customer_id,$data_log[4][0],$data_log[4][1],$log_type[4]);
	//print "Success";

}
else
{
	//print "<font color=\"#FF0000\" size=\"+2\">Some Error Occured !!<br>Please Contact to Admin !</font>";
}*/
?>
