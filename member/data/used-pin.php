<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");

$user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$sql = "SELECT * FROM e_pin WHERE user_id = '$user_id' AND mode = 0 ";
$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(id) num FROM e_pin WHERE user_id = '$user_id' AND mode = 0";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">Sr. no.</th>
			<th class="text-center">E-pin</th>
			<th class="text-center">Date</th>
			<th class="text-center">Type</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Used Id</th>
			<th class="text-center">Used Name</th>
			<th class="text-center">Used Date</th>
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
			$used_id = get_user_name($row['used_id']);
			$used_date = $row['used_date'];
			$amount = $row['amount'];
			$epin_type = $row['epin_type']; 
			$date = date('d-m-Y' , strtotime($date));
			$used_date = date('d-m-Y' , strtotime($used_date));
			$used_name = get_full_name($row['used_id']);
			
			if($epin_type == 0)
				$epin_type_status = "<span class='label label-warning'>Registration E-pin</span>";
			else
				$epin_type_status = "<span class='label label-info'>Top Up E-pin</span>";
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$epin;?></td>
				<td><?=$date;?></td>
				<td><?=$epin_type_status;?></td>
				<td><?=$amount;?></td>
				<td><?=$used_id;?></td>
				<td><?=$used_name;?></td>
				<td><?=$used_date;?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab); 
}
else{ echo "<B class='text-danger'>There are no information to show !!</b>"; } 
?>

