<?php
include('../security_web_validation.php');
include("condition.php");
session_start();

$login_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = 30;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_POST['user_id'])){
	unset($_SESSION['user_id']);
}
if(!isset($_SESSION['user_id'])){
	$_SESSION['user_id'] = $_POST['user_id'];
}
$user_id = $_SESSION['user_id'];

?>
<div class="col-md-12">
	<a class="btn btn-danger" href="index.php?page=trading_all"><i class="fa fa-reply"></i> Back</a>
</div>

<div class="col-md-12">&nbsp;</div>
<?php
$sql = "SELECT * FROM trade_buy WHERE user_id = '$login_id' AND bywallet = 1 AND hmode IN (0,1) AND mode = 1 ORDER BY date DESC";

$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer)){
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


if($totalrows > 0){ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Date</th>
			<th class="text-center">Share</th>
			<th class="text-center">Release Date</th>
		</tr> 
		</thead>
		<?php	
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $start+1;
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query)){
			$id_user = $row['id_user'];
			$total_amount = $row['total_amount'];
			$share = $row['share'];
			$unit_amount = $row['unit_amount'];
			$date = $row['date'];
			$udate = $row['udate'];
			
			$date = date('d/m/Y' , strtotime($row['date']));
			$udate = date('d/m/Y' , strtotime($row['udate']));
			?>
			 <tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$date?></td>
				<td><?=$share?></td>
				<td><?=$udate?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>

