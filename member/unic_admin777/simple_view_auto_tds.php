<?php
ini_set('display_errors','on');
session_start();
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../config.php");
include("../function/setting.php");
include("../function/functions.php");
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>UNICGROW - Admin Panel</title>
<link rel="shortcut icon" href="images/logo.png" />
<style>
.text-center{
	text-align:center;
}
.text-left{
	text-align:left;
}
</style>

</head>
<body>
<?php
$sql = $_SESSION['SQL_auto_tds'];

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

if($totalrows != 0)
{ ?>
	<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
		<thead>
		<tr><th colspan="14" class="text-left">Auto TDS Report</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Withdrawal Amount</th>
			<th class="text-center">ROI Received</th>
			<th class="text-center">Rest Amount</th>
			<th class="text-center">TDS</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		while($row = mysqli_fetch_array($query))
		{
			$user_id = $row['user_id'];
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$amount = $row['request_crowd'];
			$pan_no = $row['pan_no'];
			
			$tot_amt = $amount*100/(100-($withdrwal_money_tax+$admin_tax));
			
			$receive_roi = get_confirm_roi($user_id);
			$rest_amt = $amount - $receive_roi;
			
			$tds = 0;
			if($rest_amt > 0){
				$tds = $rest_amt*5/100;
			}
			$adm_tax = $rest_amt*$admin_tax/100;
			?>	
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td>&#36;<?=round($amount,2)?></td>
				<td>&#36;<?=round($receive_roi,2)?></td>
				<td>&#36;<?=round($rest_amt,2)?></td>
				<td>&#36;<?=round($tds,2)?></td>
				<!--<td>&#36;<?=round($net_amt,2)?></td>-->
			</tr> <?php
			$sr_no++;
			
		} ?>
	</table>
	<?PHP
}
?>
</body>
</html>