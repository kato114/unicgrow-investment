<?php
include('../security_web_validation.php');
session_start();
$user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = "15";



$result= mysqli_fetch_array(query_execute_sqli("SELECT left_network FROM network_users WHERE user_id IN($user_id)"))[0];
$sql = "SELECT t1.*, t2.update_fees from users t1 
LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id 
WHERE t1.id_user in ($result)
ORDER BY t1.id_user ASC";

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User Name</th>
			<th class="text-center">Name</th>
			<th class="text-center">Phone No</th>
			<th class="text-center">Email</th>
			<th class="text-center">Top Up</th>
			<th class="text-center">Date</th>
			<th class="text-center">Status</th>
		</tr> 
		<?php	
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $start+1;
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{
			$date = $row['date'];
			$full_investment = $row['amount'];
			$username = $row['username'];
			$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			$phone_no = $row['phone_no'];
			$email = $row['email'];
			$topup_amt = $row['update_fees'];
			
			$date = date('d/m/Y' , strtotime($date)); 
			
			if($topup_amt > 0)$status = "<span>Paid</span>";
			else $status = "<span>Unpaid</span>";
			?>
			 <tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$username;?></td>
				<td><?=$name;?></td>
				<td><?=$phone_no;?></td>
				<td><?=$email;?></td>
				
				<td><?=round($topup_amt,2);?></td>
				<td><?=$date;?></td>
				<td><?=$status;?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table>
	<?php pagging_initation($newp,$pnums,$val);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }

?>

