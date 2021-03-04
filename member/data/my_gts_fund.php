<?php
include('../security_web_validation.php');

session_start();
require_once("config.php");
include("condition.php");
$login_id = $_SESSION['mlmproject_user_id'];


$query = query_execute_sqli("SELECT * FROM wallet WHERE id = '$login_id' ");
while($row = mysqli_fetch_array($query)){
	$amount = $row['trade_gaming'];
	$owner_share = $row['owner_share'];
}
?>

<div class="col-md-6">
	<div class="alert alert-success">
		<B>USD Wallet Balance : &#36;<?=$amount?></B>
	</div>
</div>
<div class="col-md-6">
	<div class="alert alert-info">
		<B>GTS Share Wallet : <?=$owner_share?></B>
	</div>
</div>