<?php
include('../security_web_validation.php');

session_start();
include("condition.php");
include("function/setting.php");
$user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = 2;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$sql = "SELECT * FROM income WHERE user_id = '$user_id' AND type = '$income_type[5]'";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{
	while($row1 = mysqli_fetch_array($query))
	{ $tatal_amt = $tatal_amt+$row1['amount']; } 
	?>
	<table class="table table-bordered table-hover">
		<thead>
			<tr><th colspan="3">Diamond Bonus : &#36; <?=round($tatal_amt,2);?></th></tr>
		</thead>
		<tr>
			<th class="text-center">Sr. no.</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Date</th> 
		</tr>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		
		if ($newp==''){ $newp='1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{
			$date = date('d/m/Y' , strtotime($row['date']));
			$amount = round($row['amount'],5); ?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td>&#36; <?=$amount?></td>
				<td><?=$date?></td>
			</tr> <?php
			$sr_no++;
		}
		?>
	</table<?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}		
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>
