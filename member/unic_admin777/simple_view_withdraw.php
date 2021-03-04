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
$search_id = $_SESSION['net_mem_id'];

$sql = $_SESSION['SQL_withdraw'];

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

if($totalrows != 0){ ?>
	<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
		<thead>
		<tr><th colspan="14" class="text-left">Withdrawal History</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Account Info</th>
			<th class="text-center">Date Time</td>
			<th class="text-center">Pay From</td>
			<th class="text-center">Total Withdrawal</td>
			<th class="text-center">Hash</td>
			<th class="text-center">Status</td>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		while($row = mysqli_fetch_array($query)){
			$table_id = $row['id'];
			$user_id = $row['user_id'];
			$username = get_user_name($user_id);
			$amount = 0;
			$amount = $row['request_crowd'];
			$amount = $row['amount'];
			$hash_code = $row['transaction_hash'];
			$remarks = $row['user_comment'];
			$utr_no = $row['ac_type'] == 1 ? $hash_code : $row['transaction_no'];
			$tds = $row['tax'];
			$adm_tax = $row['cur_bitcoin_value'];
			$date = date('d/m/Y H:i:s', strtotime($row['date']));
			
			$tot_amt = $amount+$adm_tax;
			$status = $row['status'];
			switch($status){
				case 65 : $status = "<span class='label label-warning'>Pending</span>";	break;
				case 1 : $status = "<span class='label label-success'>Processing</span>";	break;
				case 2 : $status = "<span class='label label-primary'>Confirm</span>";	break;
				case 3 : $status = "<span class='label label-danger'>Cancel</span>";	break;
				case 65 : $status = "<span class='label label-warning'>Unconfirmed</span>";	break;
			}

			$benf = $ac_no = $bank = $bank_code = "";
			$table = "kycm";
			$limit = "limit ".($tds-1)." , 1";
			if($tds == 0){
				$table = "kyc";
				$limit = "";
			}
			$sql = "SELECT * FROM users WHERE id_user = '$user_id' ";
			$query1 = query_execute_sqli($sql);
			while($rows = mysqli_fetch_array($query1))
			{
				$btc_addrs = $rows['btc_ac'];
				$etc_addrs = $rows['etc_ac'];
				$bank_addrs = $rows['bank_ac'];
			}	
			
			$ac_info = $row['ac_type'] == 1 ? $btc_addrs : ($row['ac_type'] == 2 ? $etc_addrs : $bank_addrs);
			//$date_kyc = get_user_kyc_approved_date($user_id);
			$pay_from = $row['ac_type'] == 1 ? "Bitcoin" : ($row['ac_type'] == 2 ? 'ETH' : 'BANK');
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$ac_info?></td>
				<td><?=$date?></td>
				<td><?=$pay_from?></td>
				<td><?=round($amount,4)?> &#36;</td>
				<td><?=$hash_code?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table>
	<?PHP
}
?>
</body>
</html>