<?php
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");


$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = $left_join = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_status'],$_SESSION['SESS_mode_by']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['status'] = $_SESSION['SESS_status'];
	$_POST['mode_by'] = $_SESSION['SESS_mode_by'];
}
if(isset($_POST['Search'])){
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	
	if($_POST['search_username'] != ''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
	if($_POST['mode_by'] != ''){
		$_SESSION['SESS_mode_by'] = $mode_by = $_POST['mode_by'];
		$left_join = "LEFT JOIN payment_method t3 ON t1.ac_type = t3.id";
		switch($mode_by){
			case 1 : $qur_set_search = " AND t1.ac_type = 1"; $select_m1 = 'selected="selected"'; break;
			case 2 : $qur_set_search = " AND t1.ac_type = 3"; $select_m2 = 'selected="selected"'; break;
			case 3 : $qur_set_search = " AND t1.ac_type > 3"; $select_m3 = 'selected="selected"'; break;
		}
	}
	if($_POST['status'] != ''){
		$_SESSION['SESS_status'] = $status = $_POST['status'];
		switch($status){
			case 1 : $qur_set_search = " AND t1.status = 1"; $select1 = 'selected="selected"'; break;
			case 2 : $qur_set_search = " AND t1.status = 0"; $select2 = 'selected="selected"'; break;
			case 3 : $qur_set_search = " AND t1.status = 3"; $select3 = 'selected="selected"'; break;
		}
	}
}
?>

<div class="col-md-2">
	<form method="post" action="index.php?page=<?=$val?>">
		<input type="hidden" name="Search" value="1" />
		<select name="mode_by" class="form-control" onchange="this.form.submit();" required>
			<option value="">Select Mode</option>
			<option value="1" <?=$select_m1?>>BTC</option>
			<option value="2" <?=$select_m2?>>ETH</option>
			<option value="3" <?=$select_m3?>>Bank</option>
		</select>
	</form>
</div>
<div class="col-md-2">
	<form method="post" action="index.php?page=<?=$val?>">
		<input type="hidden" name="Search" value="1" />
		<select name="status" class="form-control" onchange="this.form.submit();" required>
			<option value="">Select Status</option>
			<option value="1" <?=$select1?>>Confirm</option>
			<option value="2" <?=$select2?>>Pending</option>
			<option value="3" <?=$select3?>>Cancelled</option>
		</select>
	</form>
</div>
<form method="post" action="index.php?page=<?=$val?>">
<div class="col-md-2">
	<input type="text" name="search_username" placeholder="By User ID" class="form-control" />
</div>
<div class="col-md-2">
	<div class="form-group" id="data_1">
		<div class="input-group date">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
		</div>
	</div>
</div>
<div class="col-md-2">
	<div class="form-group" id="data_1">
		<div class="input-group date">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="en_date" placeholder="End Date" class="form-control" />
		</div>
	</div>
</div>
<div class="col-md-2 text-right">
	<input type="submit" value="Search" name="Search" class="btn btn-info">
</div>
</form>
<div class="col-md-12">&nbsp;</div>
<?php
$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM request_crown_wallet t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
$left_join
WHERE t1.investment > 0 $qur_set_search";
$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0) {  ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">Amount</th>
			<!--<th class="text-center">BTC Amount</th>
			<th class="text-center">Payment Mode</th>-->
			<th class="text-center">Date</th>
			<!--<th class="text-center">Hash Code</th>-->
			<th class="text-center">Status</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$u_id = $row['user_id'];
			$request_amount = $row['investment'];
			$request_crowd = $row['request_crowd'];
			$payment_mode = $row['ac_type'] == 1 ? "BTC" : "Other";
			$information = $row['information'];
			$mode = $row['status'];
			$hash_code = $row['transaction_hash'];
			$date = date('d/m/Y H:i:s' , strtotime($row['date']));
			
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$username = $row['username'];
			
			
			switch($mode){
				case 0 : $status = "<span class='label label-warning'>Pending</span>";	break;
				case 1 : $status = "<span class='label label-success'>Confirmed</span>";	break;
				case 3 : $status = "<span class='label label-danger'>Cancelled</span>";	break;
			}
			
			
			$plan_type = $row['plan_id'];
			$inv_plan_type = $plan_name[$plan_type-1];
			
			if($hash_code != NULL){ $hashcode = $hash_code; }
			else{ $hashcode = "None"; }
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td>&#36;<?=$request_amount?></td>
				<!--<td><?=$request_crowd?></td>
				<td><?=$payment_mode?></td>-->
				<td><?=$date?></td>
				<!--<td><?=$hashcode?></td>-->
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?PHP 
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There is no Investment to show!</B>";  }
?>