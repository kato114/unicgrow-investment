<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 30;
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
if(isset($_POST['Search'])){
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " WHERE t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	
	if($_POST['search_username'] != ''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " WHERE t2.user_id = '$search_id' OR t3.user_id = '$search_id'";
	}
}
?>

<form method="post" action="index.php?page=<?=$val?>">
<div class="col-md-3">
	<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
</div>
<div class="col-md-3">
	<div class="form-group" id="data_1">
		<div class="input-group date">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
		</div>
	</div>
</div>
<div class="col-md-3">
	<div class="form-group" id="data_1">
		<div class="input-group date">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="en_date" placeholder="End Date" class="form-control" />
		</div>
	</div>
</div>
<div class="col-md-3 text-right">
	<input type="submit" value="Search" name="Search" class="btn btn-info">
</div>
</form>
<div class="col-md-12">&nbsp;</div>
<?php
$sql = "SELECT t1.*,t4.username buy_u_id, t5.username sale_u_id FROM trade_trasaction t1 
LEFT JOIN trade_buy t2 ON t1.buy_id = t2.id
LEFT JOIN trade_buy t3 ON t1.sale_id = t3.id
LEFT JOIN users t4 ON t2.user_id = t4.id_user
LEFT JOIN users t5 ON t3.user_id = t5.id_user
$qur_set_search ORDER BY t1.id DESC";	

$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COALESCE(SUM(buy_unit_amount),0) tot_vol, COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$tot_vol = $ro['tot_vol'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0){ ?>
	 
	<table class="table table-bordered">
		<thead>
		<tr><th colspan="7">Total Volume : <?=$tot_vol?></th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Buyer</th>
			<th class="text-center">Seller</th>
			<th class="text-center">Buy Volume</td>
			<th class="text-center">Sale Volume</td>
			<th class="text-center">Rate</td>
			<th class="text-center">Date</td>
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
			$buy_u_id = $row['buy_u_id'];
			$sale_u_id = $row['sale_u_id'];
			$buy_unit_amount = $row['buy_unit_amount'];
			$sale_unit_amount = $row['sale_unit_amount'];
			$tx_unit = $row['tx_unit'];
			$date = date('d/m/Y H:i:s' , strtotime($row['date']));
			
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$buy_u_id;?></td>
				<td><?=$sale_u_id?></td>
				<td><?=$buy_unit_amount?></td>
				<td><?=$sale_unit_amount?></td>
				<td><?=$tx_unit?></td>
				<td><?=$date?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>