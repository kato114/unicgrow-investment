<?php
include('../security_web_validation.php');
?>
<?php 
include("condition.php");

$token = $_GET['token'];

$string = urldecode($token);

$op_custfirstname = 'Rajesh';
$op_custcity = 'Jaipur';
$op_merchant = 'merchant';
$op_customer_id = 1;
$op_status = 'Succecvxss';
$op_referencenumber = 5465;
$op_currency = '$USD';
$amount = 125;

if($op_status == 'Success')
{
	$date = date('Y-m-d');
	$pay_mode = "Liberty";
	query_execute_sqli("insert into add_funds (user_id , amount , date , mode , payment_mode) values ('$op_customer_id' , '$amount' , '$date' , 1 , '$pay_mode') "); 
	
	$q = query_execute_sqli("select * from wallet where id = '$op_customer_id' ");
	while($r = mysqli_fetch_array($q))
	{
		$wallet_amount = $r['amount'];
	}	
	$total_amount = $wallet_amount+$amount;
	//query_execute_sqli("update wallet set amount = '$total_amount' where id = '$op_customer_id' ");
	
	$log_username = get_user_name($op_customer_id);
	$income_log = $amount;
	$income_type_log = "Add Fund via Liberty Payment Mode";
	include("function/logs_messages.php");
	data_logs($op_customer_id,$data_log[4][0],$data_log[4][1],$log_type[4]);
	print "Success";

}
else
{
	print "<font color=\"#FF0000\" size=\"+2\">Some Error Occured !!<br>Please Contact to Admin !</font>";
}
?>
