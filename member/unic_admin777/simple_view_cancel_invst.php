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
$sql = $_SESSION['sql_cancel_invst'];

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

if($totalrows != 0)
{ ?>
	<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th class="text-center">Sr.No.</th>
			<th class="text-center">User ID</td>
			<th class="text-center">Name</td>
			<th class="text-center">Total Investment</td>
			<th class="text-center">Rental Bonus</th>
			<th class="text-center">Growth Bonus</th>
			<th class="text-center">Total Received</th>
			<th class="text-center">Pending Balance</th>
			<th class="text-center">Beneficiery Name</th>
			<th class="text-center">A/C No.</th>
			<th class="text-center">Bank Name</th>
			<th class="text-center">IFSC Code</th>
			<th class="text-center">Status</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$user_id = $row['user_id'];
			$name = $row['name'];
			$tot_roi = $row['tot_roi'];
			$recvd_roi = $row['received_roi'];
			$pend_roi = $row['pending_roi'];
			$req_date = $row['req_date'];
			$mode = $row['mode'];
			$req_date = date('d/m/Y' , strtotime($row['req_date']));
			$paid_date = date('d/m/Y' , strtotime($row['paid_date']));
			
			$roi_bonus = round($row['roi'],2);
			$binary_bonus = round($row['bin'],2);
			
			
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			
					
			switch($mode){
				case 0 : $status = "<B class='text-warning'>Pending</B>";	break;//$status = "***";	break;
				case 1 : $status = "<B class='text-success'>Approved</B>";	break;
				case 2 : $status = "<B class='text-danger'>Cancelled</B>";	break;
			}
			
			$my_plan = my_package($user_id);
			$plan_amt = $my_plan[5];
			$act_date = get_user_active_investment_with_date($user_id)[1];
			$pending_bonus = $plan_amt - ($roi_bonus+$binary_bonus);
			
			$benf = $row['name'];
			$ac_no = $row['bank_ac'];
			$bank = $row['bank'];
			$bank_code = $row['ifsc'];
			
			$roi_bonus_tot += $roi_bonus;
			$binary_bonus_tot += $binary_bonus;
			$pending_bonus_tot += $pending_bonus;
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td>&#36;<?=$plan_amt?></td>
				<td><?=$roi_bonus?></td>
				<td><?=$binary_bonus?></td>
				<td><?=$binary_bonus+$roi_bonus?></td>
				<td><?=$pending_bonus?></td>
				
				<td><?=$benf?></td>
				<td><?=$ac_no?></td>
				<td><?=$bank?></td>
				<td><?=$bank_code?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		}  ?>
	</table>
	<?PHP
}
?>
</body>
</html>