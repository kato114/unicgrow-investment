<?php
include('../security_web_validation.php');
session_start();
include("condition.php");
$login_id = $_SESSION['mlmproject_user_id'];

$title = 'Display';
$message = 'Display Wallet Information';
data_logs($login_id,$title,$message,0);

$query = query_execute_sqli("SELECT amount FROM wallet WHERE id = '$login_id' ");
$activationw = round(mysqli_fetch_array($query)[0],2);
?>

<div class="col-md-12">
	<div class="alert alert-info">
		<strong>Commission Wallet : &#36;<?=$amount?></strong>
	</div>
</div>

