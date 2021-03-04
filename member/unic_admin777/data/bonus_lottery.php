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
		$qur_set_search = " AND DATE(t1.date) BETWEEN '$st_date' AND '$en_date' ";
	}
	
	if($_POST['search_username'] != ''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
}
?>

<div class="col-md-2">
	<form action="index.php?page=excel_bonus" method="post">
		<input type="hidden" name="bonus_name" value="<?=$sub_menu?>" />
		<input type="hidden" name="inc_type" value="7" />
		<input type="hidden" name="url" value="<?=$val?>" />
		<input type="submit" name="Excel" value="Download Excel" class="btn btn-warning" />
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
$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM income t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.type = 7 $qur_set_search ORDER BY t1.date DESC";	

$SQL = "$sql LIMIT $tstart,$tot_p ";

$sqlk = "SELECT SUM(amount) amt, COUNT(t1.id) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$amt = round($ro['amt'],2);
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

$_SESSION['search_result'] = $SQL;
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows > 0){ ?>
	 
	<table class="table table-bordered">
		<thead><tr><th colspan="6">Total <?=$sub_menu?> : &#36;<?=$amt; ?> </th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</td>
			<th class="text-center">Name</td>
			<th class="text-center">Amount</td>
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
			$member_id = $row['user_id'];
			$date = date('d/m/Y' , strtotime($row['date']));
			$amount = $row['amount'];
			$mode = $row['mode'];
			$username = $row['username'];
			$level = $row['level'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td>&#36;<?=$amount?></td>
				<td><?=$date?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>