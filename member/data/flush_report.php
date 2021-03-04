<?php
include('../security_web_validation.php');

$id = $new_user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$date =  date("Y-m-d",strtotime($systems_date." -1 DAY"));

$sql = "SELECT * FROM pair_point WHERE date < '$date' AND user_id='$id' ORDER BY date DESC";

$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>	
	
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th width="5%" class="text-center">Sr. No.</th>
			<!--<th class="text-center">Username</th>-->
			<th class="text-center">Left Point</th>
			<th class="text-center">Right Point</th>
			<th class="text-center">Total Business</th>
			<th class="text-center">Flush Business</th>
			<th class="text-center">Remain Business</th>
			<th class="text-center">Left Carry Forward</th>
			<th class="text-center">Right Carry Forward</th>
			<th class="text-center">Date</th>
		</tr>
		</thead>
		<?php		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");		
		while($r = mysqli_fetch_array($query))
		{
			$date = date('d/m/Y', strtotime($r['date']));
			//$user_id = get_user_name($r['user_id']);
			$left_point = $r['left_point'];
			$right_point = $r['right_point'];
			$total_business = $r['total_business'];
			$flush_business = $r['flush_business'];
			$remain_business = $r['remain_business'];
			$cf_left = $r['cf_left'];
			$cf_right = $r['cf_right'];
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<!--<td><?=$user_id?></td>-->
				<td>&#36;<?=$left_point?></td>
				<td>&#36;<?=$right_point?></td>
				<td>&#36;<?=$total_business?></td>
				<td>&#36;<?=$flush_business?></td>
				<td>&#36;<?=$remain_business?></td>
				<td>&#36;<?=$cf_left?></td>
				<td>&#36;<?=$cf_right?></td>
				<td><?=$date?></td>
				
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab); 
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>