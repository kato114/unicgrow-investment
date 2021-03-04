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
$sql = $_SESSION['SQL_roi_withdraw'];

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

if($totalrows != 0)
{ ?>
	<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
		<thead>
		<tr><th colspan="14" class="text-left">ROI Withdrawal</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">KYC Name</th>
			<th class="text-center">Bank</th>
			<th class="text-center">A/C</th>
			<th class="text-center">IFSC Code</th>
			<th class="text-center">Branch</th>
			<th class="text-center">ROI Amount</th>
			<th class="text-center">TDS</th>
			<th class="text-center">Admin Tax</th>
			<th class="text-center">Date</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		while($row = mysqli_fetch_array($query))
		{
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$tot_roi = $row['tot_roi'];
			$tax = $row['tax'];
			$tds_tax = $row['tds_tax'];
			
			$date = date('d/m/Y', strtotime($row['date']));
			
			$benf_name = $row['name'];
			$ac_no = $row['bank_ac'];
			$bank = $row['bank'];
			$bank_code = $row['ifsc'];
			$branch = $row['branch'];

			

			$ac_info = "<B>Bank :</B> ".$bank."<br><B>Bank Ac :</B> ".$ac_no."<br><B>IFSC :</B> ".$bank_code."<br><B>Branch :</B> ".$branch;	
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td><?=$benf_name?></td>
				<td><?=$bank?></td>
				<td><?=$ac_no?></td>
				<td><?=$bank_code?></td>
				<td><?=$branch?></td>
				<td>&#36;<?=$tot_roi?></td>
				<td>&#36;<?=$tax?></td>
				<td>&#36;<?=$tds_tax?></td>
				<td><?=$date?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table>
	<?PHP
}
?>
</body>
</html>