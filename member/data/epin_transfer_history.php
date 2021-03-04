<?php
include('../security_web_validation.php');

session_start();
include("condition.php");
$user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = "20";
	
$sql = "SELECT t1.*,t2.epin,t2.amount,t3.username FROM epin_history t1 
LEFT JOIN e_pin t2 ON t1.epin_id = t2.id
LEFT JOIN users t3 ON t1.transfer_to = t3.id_user
WHERE t1.user_id = '$user_id' AND t1.transfer_to != t1.user_id";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num != 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">E-pin</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Transfer To</th>
			<th class="text-center">Date</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($num/$plimit);
		
		if ($newp==''){ $newp='1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{
			$epin = $row['epin'];
			$username = $row['username'];
			$product_id = $row['product_id'];
			$amount = $row['amount'];
			$date = $row['date']; 
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$epin?></td>
				<td><?=$amount?></td>
				<td><?=$username?></td>
				<td><?=$date?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation($newp,$pnums,$val);
}
else{ echo "<B class='text-danger'>There is no Transfer E-pin to show !</B>"; }

?>
		