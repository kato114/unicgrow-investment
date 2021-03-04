<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
	

	$search_id = get_new_user_id($search_username);
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " AND DATE(t1.date) BETWEEN '$st_date' AND '$en_date' ";
	}
	if($search_username !=''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
}
?>
<!--<div class="col-lg-12 text-right">
	<a href="index.php?page=crdr_mainwal_reports" class="btn btn-success btn-xs disabled">Deposit Wallet Statement</a>
	<a href="index.php?page=crdr_smg_statement" class="btn btn-success btn-xs">SMG Wallet Statement</a>
	<a href="index.php?page=crdr_tora_statement" class="btn btn-success btn-xs">Tora Share Statement</a>
</div>
<div class="col-lg-12">&nbsp;</div>-->
<table class="table table-bordered">
	<tr>
		<form method="post" action="index.php?page=<?=$val?>">
		<td>
			<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
		</td>
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
		<th><input type="submit" value="Search" name="Search" class="btn btn-info"></th>
		</form>
	</tr>
</table>

<?php
$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM account t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE (t1.wall_type = 'E-Wallet' or t1.wall_type = 'Main Wallet') $qur_set_search ORDER BY t1.date DESC";	
//t1.type IN(13,19) AND

$SQL = "$sql LIMIT $tstart,$tot_p ";

$sqlk = "SELECT SUM(cr) crdt , SUM(dr) dbt ,COUNT(id) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_cr = $ro['crdt'];
	$tot_dr = $ro['dbt'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<!--<tr>
			<th colspan="7">
				<div class="pull-left">
					Total Credit : <span class="btn btn-success btn-sm">&#36;<?=$tot_cr?></span>
				</div>
				<div class="pull-right">
					Total Debit : <span class="btn btn-danger btn-sm">&#36;<?=$tot_dr?></span>
				</div>
			</th>
		</tr>-->
		<tr>
			<th class="text-center" width="10%">Sr. No.</th>
			<th class="text-center">Date</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">Particulars</th>
			<th class="text-center">Cr(+)/Dr(-)</th>
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
			$date = date('d/m/Y' , strtotime($row['date']));
			$cr = $row['cr'];
			$dr = $row['dr'];
			$account = $row['account'];
			$username = $row['username'];
			$wal_bal = $row['wallet_balance'];
			$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			
			$wr = $cr > 0 ? $cr : $dr;
			$sign = $cr > 0 ? "+" : "-";
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$date?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$account?></td>
				<td><?=$sign?>&#36;<?=$wr?></td>
				<td>&#36;<?=$wal_bal?></td>
			</tr> <?php
			$sr_no++;
		}  ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>