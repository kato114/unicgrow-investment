<div class="col-sm-12 text-right">
	<button type="button" id="close" onclick="window.close()" class='btn btn-danger btn-sm'>
		<i class="fa fa-reply"></i> Close Window
	</button>
</div>
<div class="col-sm-12">&nbsp;</div>
<?php
include('../security_web_validation.php');

include("function/setting.php");
$user_id = $_SESSION['mlmproject_user_id'];
	
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
		$qur_set_search = " AND t1.date BETWEEN '$st_date' AND '$en_date' ";
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
$sql = "SELECT t1.*, t2.username FROM lottery_ticket t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.`rank` > 0 $qur_set_search ORDER BY t1.rdate DESC";

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
				<th class="text-center">Sr. No.</th>
				<th class="text-center">Username</th>
				<th class="text-center">Ticket No.</th>
				<th class="text-center">Date</th>
				<th class="text-center">Amount</th>
				<th class="text-center">Result Date</th>
				<th class="text-center">Rank</th>
			</tr>
		</thead>
		<?php
		$pnums = ceil ($num/$plimit);
		if($newp == ''){ $newp = '1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que)){
			$ticket_no = $row['ticket_no'];
			$ramount = $row['ramount'];
			$ramount = $row['ramount'];
			$date = date('d/m/Y', strtotime($row['date']));
			$rdate = date('d/m/Y', strtotime($row['rdate']));
			$username = $row['username'];
			$rank = $row['rank'];
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$ticket_no?></td>
				<td><?=$date?></td>
				<td>&#36;<?=$ramount?></td>
				<td><?=$rdate?></td>
				<td><?=$rank?></td>
			</tr> <?php
			$sr_no++;
		} ?>
		</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}	
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }

?>

