<?php
include('../security_web_validation.php');
//die("Please contact to customer care.");

ini_set("display_errors","off");
include("condition.php");
include("function/setting.php");

$login_id = $user_id = $_SESSION['mlmproject_user_id'];


$newp = $_GET['p'];
$plimit = 10000;
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
		$qur_set_search = " AND DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$st_date' AND '$en_date' ";
	}
	if($_POST['ticket_no'] != ''){
		$ticket_no = $_POST['ticket_no'];
		$sql = "SELECT * FROM lottery_ticket WHERE user_id = '$user_id' and ticket_no = '$ticket_no' and mode=0";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		mysqli_free_result($query);
		if($num > 0){
			$qur_set_search .= " and ticket_no = '$ticket_no'";
			$_SESSION['SESS_ticket_no'] = $ticket_no;
		}
		else{
			echo "<B class='text-danger'>In-Correct Ticket No !!</B>";
		}
	}
}


$sql = "SELECT COUNT(id) tot_lot, user_id, SUM(amount) tot_amt, SUM(ramount) w_amt, rdate, mode FROM lottery_ticket 
WHERE user_id = '$user_id' group by DATE(rdate) ";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(amount) amt , COUNT(id) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer))
{
	$amount = $ro['amt'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}
mysqli_free_result($quer);
mysqli_free_result($query);
if($totalrows != 0){ ?>
	
	<!--<form method="post" action="index.php?page=<?=$val?>">
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
		<div class="col-md-3">
			<input type="text" name="ticket_no" placeholder="Ticket No." class="form-control" />
		</div>
		<div class="col-md-3 text-right">
			<input type="submit" value="Search" name="Search" class="btn btn-warning btn-sm">
		</div>
	</form>-->	

	<table class="table table-striped table-hover">
		<thead>
		<!--<tr><th colspan="7">Total Buy Ticket : &#36; <?=round($amount,2);?></th></tr>-->
		<tr>
			<th class="text-center">Result Date</th>
			<th class="text-center">Total Ticket</th>
			<th class="text-center">Status</th>
			<th class="text-center">Winning Amount</th>
			<th class="text-center">Total Tickets Paid</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		
		if ($newp==''){ $newp='1'; }
	
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que)){
			
			$tot_lot = $row['tot_lot']; 
			$tot_amt = $row['tot_amt']; 
			$rdate = date('d/m/Y' , strtotime($row['rdate']));
			$ramount = round($row['ramount'],5); 
			$mode = $row['mode'];
			$rank = $row['rank'];
			$w_amt = $row['w_amt'];
			//$ramount = $mode == 0 ? "Wait" : ($ramount > 0 ? "&#36; $ramount" : "Lose");
			
			$date1 = date('M d Y' , strtotime($row['date']));
			$date2 = date('M d Y' , strtotime($row['rdate']));
			
			$status = "<B class='text-danger'>Result Pending</B>";
			if($mode == 1){
				$status = "<B class='text-success'>Result Declared</B>";
			}
			
			?>
			<tr class="text-center">
				<td><?=$date2?></td>
				<td>
					<form action="index.php?page=ticket_details" method="post" target="_blank">
						<input type="hidden" value="<?=$row['rdate']?>" name="rdate" />
						<input type="submit" name="more" value="<?=$tot_lot?>" class="btn btn-primary btn-xs" title="Click For More" />
					</form>
				</td>
				<td><?=$status?></td>
				<td><?=round($w_amt,2)?></td>
				<td><?=$tot_amt?></td>
			</tr> <?php
			$sr_no++;
		}
		?>
	</table> <?php 
	mysqli_free_result($que);
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}		
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>
