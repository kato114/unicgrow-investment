<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");

$user_id = $_SESSION['mlmproject_user_id'];
$newp = $_GET['p'];
$plimit = "15";

$SQL = "SELECT * FROM income WHERE user_id = '$user_id' AND type = '$income_type[5]' ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{
	while($row1 = mysqli_fetch_array($query))
	{ $tatal_amt = $tatal_amt+$row1['amount']; } 
	?>
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Total Rewards Bonus</th>
				<th>&#36; <?=round($tatal_amt,2);?></th>
			</tr>
		</thead>
		<tr>
			<th class="text-center">Date</th> 
			<th class="text-center">Amount</th>
		</tr>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		
		if ($newp==''){ $newp='1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$que = query_execute_sqli("$SQL LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{
			$date = $row['date'];
			$amount = round($row['amount'],5); ?>
			<tr>
				<td class="text-center"><?=$date?></td>
				<td class="text-center">&#36; <?=$amount?></td>
			</tr> <?php
		}
		?>
	</table> 
	<?php pagging_initation($newp,$pnums,$val);
}		
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>
