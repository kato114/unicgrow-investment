<?php
include('../security_web_validation.php');

session_start();
require_once("config.php");
include("condition.php");
$login_id = $_SESSION['mlmproject_user_id'];

$title = 'Display';
$message = 'Display Wallet Information';
data_logs($login_id,$title,$message,0);

$query = query_execute_sqli("SELECT amount,activationw FROM wallet WHERE id = '$login_id' ");
$row = mysqli_fetch_array($query);
$amount = round($row[0],2);
$e_wallet = round($row[1],2);

?>

<div class="col-md-12">
	<div class="alert alert-info" style="height:50px;">
		<div class="col-md-6"><B>Bonus-wallet Balance : &#36;<?=$amount?></B></div>
		<div class="col-md-6"><B>E-wallet Balance : &#36;<?=$e_wallet?></B></div>
	</div>
</div>