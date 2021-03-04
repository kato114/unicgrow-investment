<?php
include('../security_web_validation.php');
?>
<?php
session_start();
//include("function/account_maintain.php");
include("function/setting.php");

$user_id = $_SESSION['mlmproject_user_id'];
	
$sqli = "select * from withdrawal_crown_wallet where user_id = '$user_id' AND ac_type = 5 order by id DESC";
$query = query_execute_sqli($sqli);
$num = mysqli_num_rows($query);
if($num > 0)
{
?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th class="text-center">Sr No.</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Request Date</th>
			<th class="text-center">Verify Date</th>
			<td class="text-center">Payment Mode</td>
			<th class="text-center">Status</th>
		</tr>
	</thead>
	<?php
	$sr_no = 1;
	while($row = mysqli_fetch_array($query))
	{
		$id = $row['id'];
		$amount = $row['amount'];
		$status = $row['status'];
		$action_date = $row['accept_date'];
		$req_date = date('d/m/Y H:i:s' , strtotime($row['date']));
		$wallet_type = $row['ac_type'];
		$payment_mode = $pm_name[$wallet_type-1];
		
		if($action_date == '0000-00-00 00:00:00'){ $paid_date = '................'; }
		else{ $paid_date = date('d/m/Y H:i:s' , strtotime($row['accept_date'])); }
		
		switch($status)
		{
			case 0 : $paid = "<B class='text-warning'>Proceed</B>";	break;
			case 1 : $paid = "<B class='text-danger'>Processing</B>";	break;
			case 2 : $paid = "<B class='text-info'>Confirm</B>";	break;
			case 3 : $paid = "<B class='text-warning'>Cancel</B>";	break;
			case 65 : $paid = "<B class='text-warning'>Unconfirmed</B>";	break;
		}
		
		?>
		<tr class="text-center">
			<td><?=$sr_no?></td>
			<td>$ <?=$amount?></td>
			<td><?=$req_date?></td>
			<td><?=$paid_date?></td>
			<td><?=$payment_mode?></td>
			<td><?=$paid?></td>
		</tr> <?php
		$sr_no++;
	} ?>
	</table> <?php
}	
else{ echo "<B style='color:#FF0000'>There are no information to show !!</b>";}
?>

