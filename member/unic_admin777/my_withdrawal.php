<?php
include("../config.php");

$user_id = $_REQUEST['id'];
$withdraw_amt = $_REQUEST['business'];


$sql = "SELECT * FROM account WHERE user_id = '$user_id' AND dr > 0 AND type = 15";
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="4">
				Total Withdrawal 
				<i class="fa fa-arrow-right"></i> <B class="text-danger"><?=$withdraw_amt?>&#36;</B>
			</th>
		</tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Date</th>
			<th class="text-center">Status</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		$que = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($que))
		{ 	
			$user_id = $row['id_user'];
			$amount = $row['dr'];
			$date = date('d/m/Y', strtotime($row['date']));
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$amount;?> &#36;</td>
				<td><?=$date?></td>
				<td>&nbsp;</td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
}
else{ echo "<B class='text-danger'>No info found!</B>";  }
?>
