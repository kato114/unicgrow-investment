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
$sql = $_SESSION['sql_business_data'];

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

if($totalrows != 0)
{ ?>
	<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">Activation Date</td>
			<th class="text-center">Package</th>
			<th class="text-center">Rental Bonus Received</th>
			<th class="text-center">Growth Bonus Received</th>
			<th class="text-center">Pending Balance</th>
			<th class="text-center">Remarks</th>
			<th class="text-center">Status</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$user_id = $row['user_id'];
			$username = $row['username'];
			$roi_bonus = round($row['roi'],2);
			$binary_bonus = round($row['bin'],2);
			$can_mode = $row['can_mode'];
			$remark = $row['remark'];
			
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$investment = "";
			$package = '***';
			$my_plan = my_package($user_id);
			if(!empty($my_plan)){
				$investment = $my_plan[0];
				$package = $my_plan[5];
			}
			
			$top_up = get_paid_member($user_id);
			if($top_up == 0) { $status = "<span class='label label-danger'>Inactive</span>"; }
			else { $status = "<span class='label label-info'>Active</span>"; }
			if($row['user_type']== 'D'){ $status = "<span class='label label-danger'>Block</span>"; }
			
			$tot_roi = $my_plan[5];//get_user_tot_roi_for_active_users($user_id);
			$act_date = get_user_active_investment_with_date($user_id)[1];
			$pending_bonus = $package - ($roi_bonus+$binary_bonus);
			$pend_roi = $my_plan[5]-($roi_bonus+$binary_bonus);
			$btn_status = "<B class='text-success'>Active</B>";
			switch(/*get_user_cancel_investment($user_id)*/$can_mode){
				case 0 : $btn_status = "<B class='text-warning'>Cancel Pending</B>";	break;
				case 1 : $btn_status = "<B class='text-primary'>Cancel Approved</B>";	break;
				case 51 : $btn_status = "<B class='text-primary'>Cancel Unblocked</B>";	break;
				case 2 : $btn_status = "<B class='text-danger'>Request Cancelled</B>";	break;
				default : $btn_status = "<B class='text-success'>Active</B>";
			}
			if($row['re_mode']== 1){ $btn_status = ""; }

			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$act_date?></td>
				<td><?=$package?></td>
				<td><?=$roi_bonus?></td>
				<td><?=$binary_bonus?></td>
				<td><?=$pending_bonus?></td>
				<td><?=$remark?></td>
				<td><?=$btn_status?>
				</td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table>
	<?PHP
}
?>
</body>
</html>