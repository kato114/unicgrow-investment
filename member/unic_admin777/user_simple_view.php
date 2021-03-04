<?php
ini_set('display_errors','on');
include('../../security_web_validation.php');

session_start();
include("../config.php");
include("../function/setting.php");
include("../function/functions.php");
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Deli Diamond - Admin Panel</title>
<link rel="shortcut icon" href="images/logo.png" />
<style>
.text-center{
	text-align:center;
}
</style>

</head>
<body>
<?php
$SQL = "SELECT t1.*,
t2.profit month_roi,t2.date act_date,t2.start_date,t2.total_days, DATE_ADD(t2.start_date,INTERVAL t2.total_days MONTH) end_date,t2.count,(t2.total_days - t2.count) month_remain,
t3.plan_name,
t4.bank_ac bank_acc,t4.name,t4.pan_no pan_num,t4.bank bank_nam,t4.ifsc ifsc_num
FROM users t1
LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
LEFT JOIN plan_setting t3 ON t2.request_crowd = t3.pv
LEFT JOIN kyc t4 ON t1.id_user = t4.user_id
GROUP BY t1.id_user";


$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
?>
<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
	<tr>
		<th class="text-center">Sr. No.</th>
		<th class="text-center">Username</th>
		<th class="text-center">Name</th>
		<th class="text-center">Joining Date</th>
		<th class="text-center">Package</th>
		<th class="text-center">Activation Date</th>
		<th class="text-center">Beneficiary Name</th>
		<th class="text-center">Bank Name</th>
		<th class="text-center">A/C No.</th>
		<th class="text-center">IFSC Code</th>
		<th class="text-center">PAN No.</th>
		<th class="text-center">Monthly ROI</th>
		<th class="text-center">ROI Start Date</th>
		<th class="text-center">ROI End Date</th>
		<th class="text-center">ROI Received Time</th>
		<th class="text-center">ROI Remaining Time</th>
	</tr>
	<?php
	$sr_no = 1;
	while($row = mysqli_fetch_array($query))
	{
		$id = $row['id'];
		$u_id = $row['user_id'];
		$username = $row['username'];
		$name = $row['name'];
		$join_date = $row['date'];
		
		$act_date = $row['act_date'];
		$package = $row['plan_name'];
		$start_date = $row['start_date'];
		$total_days = $row['total_days'];
		$end_date = $row['end_date'];
		$count = $row['count'];
		$month_remain = $row['month_remain'];
		
		$benf_name = $row['name'];
		$bank = $row['bank_nam'];
		$ac_no = $row['bank_acc'];
		$ifsc_code = $row['ifsc_num'];
		$pan_no = $row['pan_num'];
		$month_roi = $row['month_roi'];
		
		?>
		<tr class="text-center">
			<td><?=$sr_no?></td>
			<td><?=$username?></td>
			<td><?=$name?></td>
			<td><?=$join_date?></td>
			<td><?=$package?></td>
			<td><?=$act_date?></td>
			
			<td><?=$benf_name?></td>
			<td><?=$bank?></td>
			<td><?=$ac_no?></td>
			<td><?=$ifsc_code?></td>
			<td><?=$pan_no?></td>
			<td><?=$month_roi?></td>
			<td><?=$start_date?></td>
			<td><?=$end_date?></td>
			<td><?=$count?></td>
			<td><?=$month_remain?></td>
		</tr> <?php
		$sr_no++;
	} ?>
	
</table>
</body>
</html>

<?php
function daysSince($date, $date2){
	$sql = "SELECT DATEDIFF('$date','$date2') AS days;";
	$result = query_execute_sqli($q);
	$row = mysqli_fetch_array($result);
	return ($row[0]);
}
?>