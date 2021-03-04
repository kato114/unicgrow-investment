<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");
?>
<!--<h1 align="left">Request Status</h1>-->
<?php
$id = $_SESSION['mlmproject_user_id'];

$title = 'Display';
$message = 'Display Withdrawal Request';
data_logs($id,$title,$message,0);

$query = query_execute_sqli("select * from paid_unpaid where user_id = '$id' ");
$num = mysqli_num_rows($query);
if($num != 0)
{ ?>
	<table class="table table-bordered table-hover">				
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Request Amount</th>
			<th class="text-center">Request Date</th>
			<th class="text-center">Paid Date</th>
			<th class="text-center">Request Status</th>
		</tr>
		</thead>
<?php
		$i = 1;
		while($row = mysqli_fetch_array($query))
		{
			$request_amount = $row['amount'];
			$request_date = $row['request_date'];
			$paid = $row['paid'];
			if($paid == '0')
			{
				$paid_status = "Pending";
				$paid_date = "-";
			}
			elseif($paid == '1')
			{
				$paid_status = "Paid";
				$paid_date = $row['paid_date'];
			}
			elseif($paid == '2')
			{
				$paid_status = "Canceled";
				$paid_date = $row['paid_date'];
			}
		?>	
			<tr>
				<td class="text-center">$i</td>
				<td class="text-center"><small>$request_amount</small></td>
				<td class="text-center"><small>$request_date</small></td>
				<td class="text-center"><small>$paid_date</small></td>
				<td class="text-center"><small>$paid_status</small></td>
			</tr>
		<?php
			$i++;
		}
		print "</table>";	
	}
	else{ echo "<B style=\"color:#FF0000; font-size:12pt;\">There are no fund for approved !</B>"; }
?>
