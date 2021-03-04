<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$search_id = get_new_user_id($search_username);
	
	if($_POST['search_username'] !=''){
		$qur_set_search = " AND t1.by_id = '$search_id' ";
	}
}
?>
<div class="row">
	<div class="col-md-4 col-md-offset-8">
	<form method="post" action="index.php?page=<?=$val?>">
	<table class="table table-bordered">
		<tr>
			<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
			<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
		</tr>
	</table>
	</form>	
	</div>
</div>
<?php

$sql = "SELECT t1.*,t2.update_fees,t3.username topto,t4.username topby FROM ledger t1
LEFT JOIN reg_fees_structure t2 ON t1.by_id = t2.id
LEFT JOIN users t3 ON t2.user_id = t3.id_user
LEFT JOIN users t4 ON t1.user_id = t4.id_user
WHERE particular LIKE 'Debit Fund For TOPUP FROM Company Wallet' $qur_set_search";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COALESCE(SUM(t1.dr),0) amt,COUNT(t1.id) num FROM ($sql) t1 ";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$tot_amt = $ro['amt'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>	
	<table class="table table-bordered">
		<thead><tr><th colspan="5">Total Amount : <?=$tot_amt; ?> &#36;</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Top-Up By</th>
			<th class="text-center">Top-Up To</th>
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
			$date = $r['date_time'];
			$type = $r['by_wallet'];
			$reg_fees = $r['dr'];
			$update_fees = $r['update_fees'];
			$topupto = $r['topto'];
			$topupby = $r['topby'];
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td>&#36;<?=$update_fees?></td>
				<td><?=$topupby?></td>
				<td><?=$topupto?></td>
				<td><?=$date?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>