<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

?>
<div class="col-sm-12 text-right">
	<button type="button" id="close" onclick="window.close()" class='btn btn-danger btn-sm'>
		<i class="fa fa-reply"></i> Close Window
	</button>
</div>
<div class="col-sm-12">&nbsp;</div>
<?php
if(isset($_POST['user_id'])){
	unset($_SESSION['user_id'],$_SESSION['username_post'],$_SESSION['type_post']);
}
if(!isset($_SESSION['user_id'])){
	$_SESSION['user_id'] = $_POST['user_id'];
	$_SESSION['type_post'] = $_POST['type'];
	$_SESSION['username_post'] = $_POST['username'];
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username_post'];
$type = $_SESSION['type_post'];

$sql = "SELECT * FROM income WHERE user_id = '$user_id' AND type = '$type'";

$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(amount) amt, SUM(tax) tax, SUM(tds_tax) tds_tax, COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$amount = round($ro['amt']+$ro['tax']+$ro['tds_tax'],2);
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


$bonus_type = "Growth";
if($type == 2){ $bonus_type = "ROI"; }

if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="6">
				Total <?=$bonus_type?> Bonus of <B class="text-danger"><?=$username?></B> 
				<B class='text-primary'><i class="fa fa-arrow-right"></i></B> <?=$amount; ?> 
			</th>
		</tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Date</th>
			<th class="text-center">Amount</td>
			<th class="text-center">Status</td>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{ 	
			$id = $row['id'];
			$date = date('d/m/Y' , strtotime($row['date']));
			$amount = $row['amount'];
			$tot_amt = round($amount,4);
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$date?></td>
				<td><?=$tot_amt?> $</td>
				<td><span class="text-success">Success</span></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>No info found!</B>";  }
?>
