<?php
include('../security_web_validation.php');

session_start();
include("condition.php");

$user_id = $_SESSION['mlmproject_user_id'];
	
$sql = "select * from epin_history as t1 inner join e_pin as t2 on t1.epin_id = t2.id where t1.generate_id = '$user_id' group by t1.epin_id";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num != 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">Sr. no.</th>
			<th class="text-center">E-pin</th>
			<th class="text-center">Date</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Type</th>
			<th class="text-center">Action</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		$query = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($query))
		{
			$epin = $row['epin'];
			$date = $row['date'];
			$product_id = $row['product_id'];
			$amount = $row['amount'];
			$epin_type = $row['epin_type']; 
			
			if($epin_type == 0)
			{
				$epin_type_status = "<font color=\"blue\"><strong>Registration E-pin</strong></font>";
				$pin_status = "<form method=\"post\" action='index.php?page=epin_history'>
								<input type='hidden' name='epin' value='$epin'>
								<input type='submit' name='epin_submit' value='More' class='btn btn-info'>
								</form>";
			}	
			else
			{
				$epin_type_status = "<font color=\"#f01\"><strong>Top Up E-pin</strong></font>";
				$pin_status = "<form method=\"post\" action='index.php?page=epin_history'>
								<input type='hidden' name='epin' value='$epin'>
								<input type='submit' name='epin_submit' value='More' class='btn btn-success'>
								</form>";
			}
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$epin;?></td>
				<td><?=$date;?></td>
				<td><?=$amount;?></td>
				<td><?=$epin_type_status;?></td>
				<td><?=$pin_status;?></td>
			</tr> <?php	
			$sr_no++;	
		} ?> 
	</table> <?PHP
	pagging_initation($newp,$pnums,$val);
}
else{ echo "<B class='text-danger'>There is no E-pin to show !!</b>"; }

?>
		