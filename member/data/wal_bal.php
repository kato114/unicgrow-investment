<?php
include('../security_web_validation.php');
include("function/setting.php");

include("condition.php");
$login_id = $_SESSION['mlmproject_user_id'];


$query = query_execute_sqli("SELECT * FROM wallet WHERE id = '$login_id' ");
while($row = mysqli_fetch_array($query)){
	$amount = $row['amount'];
	$rtoken = $row['rtoken'];
}
?>

<div class="col-md-12">
	<div class="alert alert-success">
		<B>Wallet Balance : <?=round($amount,2)?>  </B><br />
		
		<div class="pull-right"><a href="index.php?page=request-fund-transfer" class="btn btn-danger btn-xs">Withdrawal This Balance</a></div>
	</div>
</div>