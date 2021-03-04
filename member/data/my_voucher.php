<?php
include('../security_web_validation.php');

session_start();
//include("function/account_maintain.php");
include("function/setting.php");
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
if(isset($_POST['Search'])){
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND DATE(purchasedate) BETWEEN '$st_date' AND '$en_date' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<div class="col-md-5">
	<div class="form-group" id="data_1">
		<div class="input-group date">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
		</div>
	</div>
</div>
<div class="col-md-5">	
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
$sql = "SELECT * FROM voucher_redeem WHERE userid = '$login_id' $qur_set_search ORDER BY id DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$num = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sqli) t1";
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
			$price = $row['price'];
			$purchasedate = $row['date'];
			$date = date('d/m/Y' , strtotime($row['date']));
			
			$type = $sr_no == 1 ? "<B class='text-primary'>Upgrade Package</B>" : "<B class='text-success'>Buy Package</B>";
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$code?></td>
				<td><?=$purchasedate?></td>
				<td>&#36;<?=$price?></td>
			</tr> <?php
			$sr_no++;
		} ?>
		</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}	
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>

