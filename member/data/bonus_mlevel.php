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
		$qur_set_search = " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
}

$sql = "SELECT t1.*,t3.username 'from' FROM income t1
		left join reg_fees_structure t2 on t1.incomed_id = t2.id
		left join users t3 on t2.user_id = t3.id_user
		WHERE t1.user_id = '$user_id' AND t1.type = '$income_type[2]' $qur_set_search ORDER BY t1.date DESC";
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
<form method="post" action="index.php?page=<?=$val?>">
	<div class="col-md-3">
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
	<table class="table table-bordered table-hover table-responsive">
		<thead>
		<tr><th colspan="6">Total Level Bonus : &#36;&nbsp;<?=round($amount,2)?></th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Release Date</th> 
			<th class="text-center">Amount</th>
			<th class="text-center">Level</th>
			<th class="text-center">From User</th>
			<th class="text-center">Status</th>
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
			$from = $row['from'];
			$level = $row['level'];
			$tot_amt = round($amount,2);
			$mode = $row['mode'];
			switch($mode){
				case 0 : $status = '<span class="text-danger">Pending</span>';break;
				case 1 : $status = '<span class="text-success">Success</span>';break;
			}
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$date?></td>
				<td>&#36; <?=$amount?></td>
				<td><?=$level?></td>
				<td><?=$from?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		}
		?>
	</table> <?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}		
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>
