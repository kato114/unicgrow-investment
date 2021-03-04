<?php
include('../security_web_validation.php');

session_start();
include("../function/setting.php");
include("../function/functions.php");
	
$newp = $_GET['p'];
$plimit = 30;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_search_username']);
}
else{
	$_POST['Search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
}
if(isset($_POST['Search'])){
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " WHERE DATE(purchasedate) BETWEEN '$st_date' AND '$en_date' ";
	}
	if($_POST['search_username'] !=''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " WHERE t1.userid = '$search_id' ";
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
<div class="col-md-4">	
	<div class="form-group" id="data_1">
		<div class="input-group date">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="en_date" placeholder="End Date" class="form-control" />
		</div>
	</div>
</div>
<div class="col-md-2 text-right"><input type="submit" value="Search" name="Search" class="btn btn-info"></div>
</form>	

<div class="col-md-12">&nbsp;</div>
<?php	
$sql = "SELECT t1.*, t2.username FROM voucher_redeem t1 
LEFT JOIN users t2 ON t1.userid = t2.id_user
$qur_set_search ORDER BY id DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$num = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($num > 0){ ?>
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="text-center">Sr No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">Voucher Code</th>
				<th class="text-center">Used Date/time</th>
				<th class="text-center">Value</th>
			</tr>
		</thead>
		<?php
		$pnums = ceil ($num/$plimit);
		if($newp == ''){ $newp = '1'; }
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$sqli = "$sql LIMIT $start,$plimit";
		$que = query_execute_sqli($sqli);
		while($row = mysqli_fetch_array($que)){
			$user_id = $row['userid'];
			$code = $row['code'];
			$username = $row['username'];
			$price = $row['price'];
			$purchasedate = $row['date'];
			$date = date('d/m/Y' , strtotime($row['date']));
			
			$type = $sr_no == 1 ? "<B class='text-primary'>Upgrade Package</B>" : "<B class='text-success'>Buy Package</B>";
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$code?></td>
				<td><?=$purchasedate?></td>
				<td>&#36;<?=$price?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}	
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>

