<?php
include('../security_web_validation.php');

session_start();
include("condition.php");
include('function/setting.php');

$login_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = "20";
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
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = "AND DATE_FORMAT(t1.date,'%Y-%m-%d') BETWEEN '$st_date' AND '$en_date'";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="End Date" class="form-control" />
				</div>
			</div>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	


<?php
$sql = "SELECT t1.* FROM account t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.user_id = '$login_id' AND t1.type IN (".join(', ', $com_wallet_crdr).")  
$qur_set_search ORDER BY t1.date DESC";	
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(cr) crdt , SUM(dr) dbt ,COUNT(id) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$tot_cr = $ro['crdt'];
	$tot_dr = $ro['dbt'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="6" class="text-danger">
				Wallet Balance : &#36;<?=$tot_cr-$tot_dr//get_user_allwallet($login_id,'amount')?>
			</th>
		</tr>
		<tr>
			<th colspan="6">
				<div class="pull-left">
					Total Credit : <span class="btn btn-success btn-sm">&#36;<?=$tot_cr?></span>
				</div>
				<div class="pull-right">
					Total Debit : <span class="btn btn-danger btn-sm">&#36;<?=$tot_dr?></span>
				</div>
			
			</th>
		</tr>
		<tr>
			<th class="text-center" width="10%">Sr. No.</th>
			<th class="text-center">Date</th>
			<th class="text-center">Particulars</th>
			<th class="text-center">Credit</th>
			<th class="text-center">Debit</th>
			<th class="text-center">Balance</th>
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
			$cr = $row['cr'];
			$dr = $row['dr'];
			$date = $row['date'];
			$type = $row['type'];
			$wall_balance = $row['wallet_balance'];
			$account = $row['account'];
			?>
			<tr align="center">
				<td><?=$sr_no?></td>
				<td><?=$date?></td>
				<td><?=$account?></td>
				<td><?=$cr?> &#36;</td>
				<td><?=$dr?> &#36;</td>
				<td><?=$wall_balance?> &#36;</td>
			</tr> <?php 
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }

?>
		

