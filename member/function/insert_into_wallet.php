<?php
function insert_into_wallet($user_id,$wallet_income,$inc_type)
{
	require_once("functions.php");
	$query = query_execute_sqli("select * from wallet where id = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$amount = $row['amount'];
	}
	$total_income = $amount+$wallet_income;	
	$date = date('Y-m-d');
	query_execute_sqli("update wallet set amount = '$total_income' , date = '$date'  where id = '$user_id' ");
	if($inc_type == 1) { $wallet_income_type = "Survey"; }
	if($inc_type == 2) { $wallet_income_type = "Direct member"; }
	if($inc_type == 3) { $wallet_income_type = "Binary"; }
	$username = get_user_name($user_id);
	$position = get_user_position($user_id);
	$amount = $wallet_income;
	$date = date('Y-M-d');
	include("setting.php");
	$full_msg = $wallet_log[1][1];
	$wallet_log[1][0];
	data_logs($user_id,$position,$wallet_log[1][0],$full_msg,$log_type[4]);
}	