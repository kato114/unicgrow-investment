<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");

$user_id = $_SESSION['mlmproject_user_id'];
$ac_type_detail = implode(",",array("'E-Wallet'"));
$sql = "SELECT * FROM account WHERE user_id = '$user_id' and wall_type in($ac_type_detail)";
$query = query_execute_sqli($sql);
$query2 = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Total Credit : &#36;<?=total_cr_dr($user_id,'cr');?></th>	
			<th class="text-center">Total Debit : &#36;<?=total_cr_dr($user_id,'dr');?></th>
		</tr>
		</thead>
		<tr>
			<td>
				<table class="table table-bordered">
					<thead>
					<tr>
						<th class="text-center">Date</th>
						<th class="text-center">Credit</th>
						<th class="text-center">Account</th>
						<th class="text-center">Balance</th>
					</tr>
					</thead>
					<?php
					while($row = mysqli_fetch_array($query))
					{
						$cr = $row['cr'];
						$date = $row['date'];
						if($cr > 0)
						{ ?>
							<tr>
								<td class="text-center"><?=$date; ?></td>
								<td class="text-center">&#36; <?=$cr; ?></td>
								<td class="text-center"><?=$row['account']?></td>
								<td class="text-center">&#36; <?=$row['wallet_balance']?></td>
							</tr> <?php 
						}
					}  ?>
				</table>
			</td>
			<td>
				<table class="table table-bordered">
					<thead>
					<tr>
						<th class="text-center">Date</th>
						<th class="text-center">Debit</th>
						<th class="text-center">Account</th>
						<th class="text-center">Balance</th>
					</tr>
					</thead>
					<?php
					while($rows = mysqli_fetch_array($query2))
					{
						$dr = $rows['dr'];
						$date1 = $rows['date'];
						if($dr > 0)
						{
							
						?>
							<tr>
								<td class="text-center"><?=$date; ?></td>
								<td class="text-center">&#36; <?=$dr;?></td>
								<td class="text-center"><?=$rows['account']?></td>
								<td class="text-center">&#36; <?=$rows['wallet_balance']?></td>
							</tr> <?php 
						}
					} ?>
				</table>
			</td>
		</tr>
	</table> <?php
} 
else{  echo "<B class='text-danger'>There are no information to show !!</B>"; }
?>