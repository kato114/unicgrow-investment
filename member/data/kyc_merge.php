<?php
include('../security_web_validation.php');

$login_id = $_SESSION['mlmproject_user_id'];

$sql = "SELECT t1.*,t2.username,t3.amount FROM kyc t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
LEFT JOIN wallet t3 ON t1.user_id = t3.id
WHERE t1.by_user_id = '$login_id'";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0)
{ ?>
	<div class="panel panel-default">
		<div class="panel-body">
			<h3 class="text-primary">My Other Id's</h3>
			<table class="table table-striped table-bordered">
				<thead>
				<tr>
					<th class="text-center">Username</th>
					<th class="text-center">Package</th>
					<th class="text-center">Wallet Balance</th>
					<th class="text-center">Binary Bonus</th>
					<th class="text-center">Withdrawal Pending</th>
				</tr>
				</thead>
				<?php
				$sr = 1;
				while($row = mysqli_fetch_array($query))
				{
					$user_id = $row['user_id'];
					$username = $row['username'];
					$amount = $row['amount'];
					$value = $row['value'];
					$rate = $row['rate'];
					$profit = $row['profit'];
					$date = date('d/m/Y', strtotime($row['date']));
					
					$my_plan = my_package($user_id)[0];
					$bin_bonus = get_user_which_type_bonus($user_id,4);
					$withdraw = get_user_withdrawal($user_id,0);
					?>
					<tr class="text-center">
						<td><?=$username;?></td>
						<td><?=$my_plan;?> </td>
						<td><?=$amount;?></td>
						<td><?=$bin_bonus;?></td>
						<td><?=$withdraw?></td>
					</tr> <?php
				} ?>
			</table>
		</div>
	</div>
<?php
}
?>