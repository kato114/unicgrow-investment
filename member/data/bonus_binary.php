<?php
include('../security_web_validation.php');

session_start();
include("condition.php");
include("function/setting.php");
$user_id = $_SESSION['mlmproject_user_id'];
$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['Search'])){
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND date BETWEEN '$st_date' AND '$en_date' ";
	}
}

?>
<form method="post" action="index.php?page=<?=$val?>">
	<div class="col-md-3 col-md-offset-5">
		<input type="date" name="st_date" placeholder="Start Date" class="form-control" id="date" />
	</div>
	<div class="col-md-3">
		<input type="date" name="en_date" placeholder="End Date" class="form-control" id="date" />
	</div>
	<div class="col-md-1 text-right">
		<input type="submit" value="Search" name="Search" class="btn btn-info" />
	</div>

</form>	
<div class="col-md-12">&nbsp;</div>

<?php

$sql = "SELECT * FROM income WHERE user_id = '$user_id' AND type = '$income_type[3]' 
$qur_set_search ORDER BY date DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(amount) amt , COUNT(id) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer)){
	$amount = $ro['amt'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0){ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr><th colspan="5">Total Binary Bonus : <?=round($amount,2)?></th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Release Date</th>
			<th class="text-center">Matching Business</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Status</th>
			<!--<th class="text-center">Admin Tax</th>
			<th class="text-center">TDS</th>
			<th class="text-center">Net Amount</th>-->
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		
		if ($newp==''){ $newp='1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que)){
			$date = date('d/m/Y' , strtotime($row['date']));
			$amount = round($row['amount'],5); 
			$pair_point = get_user_pair_point($user_id,$row['date']);
			$tot_amt = round($amount,2);
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$date?></td>
				<td><?=$pair_point?></td>
				<td>&#36; <?=$amount?></td>
				<td><span class="text-success">Success</span></td>
			</tr> <?php
			$sr_no++;
		}
		?>
	</table><?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}		
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>
