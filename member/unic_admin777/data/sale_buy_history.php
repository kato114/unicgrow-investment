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
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_type_by']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['type_by'] = $_SESSION['SESS_type_by'];
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
		$qur_set_search = " WHERE t1.user_id = '$search_id' ";
	}
	if($_POST['type_by'] != ''){
		$_SESSION['SESS_type_by'] = $type_by = $_POST['type_by'];
		switch($type_by){
			case 1 : $qur_set_search = " WHERE t1.type = 1"; $select1 = 'selected="selected"'; break;
			case 2 : $qur_set_search = " WHERE t1.type = 2"; $select2 = 'selected="selected"'; break;
		}
	}
}
?>

<div class="col-md-2">
	<form method="post" action="index.php?page=<?=$val?>">
		<input type="hidden" name="Search" value="1" />
		<select name="type_by" class="form-control" onchange="this.form.submit();" required>
			<option value="">Select Type</option>
			<option value="1" <?=$select1?>>Buy</option>
			<option value="2" <?=$select2?>>Sale</option>
		</select>
	</form>
</div>
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
$sql = "SELECT t1.*,t2.username FROM trade_buy t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
$qur_set_search ORDER BY t1.date DESC";	

$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0){ ?>
	 
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</td>
			<th class="text-center">Volume</td>
			<th class="text-center">Transaction</td>
			<th class="text-center">Mode</td>
			<th class="text-center">Date</td>
			<th class="text-center">Balance</td>
			<th class="text-center">Circulation</td>
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
			$user_id = $row['user_id'];
			$gtb_balance = $row['gtb_balance'];
			$share = $row['share'];
			$username = $row['username'];
			$date = date('d/m/Y H:i:s' , strtotime($row['date']));
			$type = $row['type'] == 1 ? '<span class="label label-success">Buy</span>' : '<span class="label label-danger">Sale</span>';
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$share;?></td>
				<td>------</td>
				<td><?=$type?></td>
				<td><?=$date?></td>
				<td>&#36;<?=$gtb_balance?></td>
				<td>------</td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>