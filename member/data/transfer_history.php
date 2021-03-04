<?php
include('../security_web_validation.php');

$login_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = 30;
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
$sql = "SELECT * FROM account WHERE user_id = '$login_id' AND type IN (42,43) $qur_set_search";	
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(cr) crdt , SUM(dr) dbt ,COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$tot_rec = $ro['num'];
	$tot_cr = $ro['crdt'];
	$tot_dr = $ro['dbt'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0){ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="8">
				<div class="pull-left">
					Total Cr(+) : <span class="btn btn-success btn-sm"><i class="fa fa-usd"></i> <?=$tot_cr?></span>
				</div>
				<div class="pull-right">
					Total Dr(-) : <span class="btn btn-danger btn-sm"><i class="fa fa-usd"></i> <?=$tot_dr?></span>
				</div>
			</th>
		</tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Date</th>
			<th class="text-center">Particulars</th>
			<th class="text-center">Cr(+) / Dr(-)</th>
			<th class="text-center">Wallet Type</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que)){ 	
			$cr = round($row['cr']);
			$dr = round($row['dr']);
			$date = $row['date'];
			$account = $row['account'];
			$remarks = $row['remarks'];
			$wall_type = $row['wall_type'];
			$st_date = $row['date'];

			$wall_balance = round($row['wallet_balance'],2);
			$en_date = date('Y-m-d');
			$wr = $cr > 0 ? $cr : $dr;
			$sign = $cr > 0 ? "+" : "-";
			?>
			<tr align="center">
				<td><?=$sr_no?></td>
				<td><?=$date?></td>
				<td><?=$account?></td>
				<td><B><?=$sign?></B> &#36;<?=$wr?></td>
				<td><?=$wall_type?></td>
			</tr> <?php 
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }
?>