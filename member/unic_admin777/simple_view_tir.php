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
$sql = $_SESSION['SQL_roi_new'];

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

if($totalrows != 0)
{ ?>
	<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
		<thead>
		<tr><th colspan="13" class="text-left">Rental Bonus</th></tr>
		<tr>
			<th class="text-center">Sr.No.</th>
			<th class="text-center">User ID</td>
			<th class="text-center">Name</td>
			<th class="text-center">Mobile No.</td>
			<th class="text-center">Investment</th>
			<th class="text-center">Booster</th>
			<th class="text-center">Total ROI</td>
			<th class="text-center">Total Received</td>
			<th class="text-center">Total Pending</td>
			<th class="text-center">10% Of ROI</td>
			<th class="text-center">Month</td>
			<th class="text-center">Block date</td>
			<th class="text-center">Remark</td>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$user_id = $row['id_user'];
			$date = date('d/m/Y' , strtotime($row['date']));
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$phone_no = $row['phone_no'];
			
			$tot_invst = $row['tot_invst'];
			$total_days = $row['total_days'];
			$mode = $row['reg_mode'];
			$boost_id = $row['boost_id'];
			$profit = $row['profit'];
			$update_fees = $row['update_fees'];
			$block_date = $row['block_date'];
			$remarks = $row['remarks'];

			//if($mode == 1){
				$tot_roi = $profit*$total_days;
			//}
			
			//$booster = "<B class='text-danger'>Pending </B>";
			//if($mode == 66){ $booster = "<B class='text-success'>Achieved </B>"; }
			
			$booster = user_booster_is_activate_or_not($user_id,$systems_date_time);
			
			$recvd_roi = get_user_roi_income($user_id);
			$pend_roi = $tot_roi-$recvd_roi;

			$roi_per_10 = $update_fees*10/100;
			$month = round($pend_roi/$roi_per_10);
			//if($pend_roi < 0) continue;
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$phone_no?></td>
				<td>&#36;<?=$update_fees?></td>
				<td><?=$booster?></td>
				<td>&#36;<?=$tot_roi?></td>
				<td>&#36;<?=$recvd_roi?></td>
				<td>&#36;<?=$pend_roi?></td>
				<td>&#36;<?=$roi_per_10?></td>
				<td><?=$month?></td>
				<td><?=$block_date?></td>
				<td><?=$remarks?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table>
	<?PHP
}
?>
</body>
</html>