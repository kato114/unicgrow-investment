<?php
include('../security_web_validation.php');

include("condition.php");
include("function/setting.php");

$login_id = $user_id = $_SESSION['mlmproject_user_id'];


$newp = $_GET['p'];
$plimit = 30;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_ticket_no']);
}
else{
	$_POST['Search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['ticket_no'] = $_SESSION['SESS_ticket_no'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " WHERE DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$st_date' AND '$en_date' ";
	}
	if($_POST['ticket_no'] != ''){
		$_SESSION['SESS_ticket_no'] = $ticket_no = $_POST['ticket_no'];
		$qur_set_search .= " WHERE ticket_no = '$ticket_no'";
		/*$ticket_no = $_POST['ticket_no'];
		$sql = "SELECT * FROM lottery_ticket WHERE user_id = '$user_id' and ticket_no = '$ticket_no' and mode=1 and ramount > 0";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		mysqli_free_result($query);
		if($num > 0){
			$qur_set_search .= " and ticket_no = '$ticket_no'";
			$_SESSION['SESS_ticket_no'] = $ticket_no;
		}
		else{
			echo "<B class='text-danger'>In-Correct Ticket No !!</B>";
		}*/
	}
}


$sql = "SELECT t1.*, COUNT(t2.user_id) tot_prtcp FROM (SELECT *,DATE_FORMAT(rdate,'%Y-%m-%d') 'ddate' FROM lottery_ticket GROUP BY DATE_FORMAT(rdate,'%Y-%m-%d')) t1 
LEFT JOIN lottery_ticket t2 ON DATE(t1.ddate) = DATE_FORMAT(t2.rdate,'%Y-%m-%d')
$qur_set_search GROUP BY t1.ddate Order by t1.id desc";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(tot_prtcp*3) amt , COUNT(id) num FROM ($sql) t1";
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
	<form method="post" action="index.php?page=<?=$val?>">
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
			<!--<input type="text" name="ticket_no" placeholder="Ticket No." class="form-control" />-->
		</div>
		<div class="col-md-3 text-right">
			<input type="submit" value="Search" name="Search" class="btn btn-warning btn-sm">
		</div>
	</form>	

	<table class="table table-bordered table-hover">
		<thead>
		<tr><th colspan="7">Total Buy Ticket : &#36; <?=round($amount,2);?></th></tr>
		<tr>
			<th class="text-center">Sr. no.</th>
			<th class="text-center">Date</th>
			<th class="text-center">Total Participants</th>
			<th class="text-center">Winner List</th>
			<th class="text-center">Lottery Status</th>
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
			$ticket_no = $row['ticket_no']; 
			$tot_prtcp = $row['tot_prtcp']; 
			$date = $row['date'];
			$rdate = $row['rdate'];
			$ramount = round($row['ramount'],2); 
			$mode = $row['mode'];
			
			$date1 = date('d/m/Y' , strtotime($rdate));
			
			//$status = $mode == 0 ? "Ongoing" : ($systems_date > $rdate ? "Completed" : "Result Announced");
			$status = $mode == 0 ? "<B class='text-danger'>Ongoing</B>" : "<B class='text-success'>Result Announced</B>";
			$dates = date('Y-m-d' , strtotime($rdate));
			if(date('D', strtotime($dates)) == 'Sat'){
				$dates = date('Y-m-d', strtotime($dates."+ 1 Day"));
			}
			
			$week_pn = get_pre_nxt_date($dates , $lottery_result_day);
			$p_date = $week_pn[0];
			$n_date = $week_pn[1];
			
			
			$style = "";
			if($systems_date >= $p_date and $systems_date <= $n_date){
				$style = 'style="font-weight:bold; color:#2604B6"';
			}
			
			$win_btn = 'Pending';
			if( $mode == 1){
				$win_btn = '<form method="post" action="index.php?page=winner_list" target="_blank">
						<input type="hidden" name="date" value="'.date("Y-m-d", strtotime($rdate)).'" />
						<input type="submit" name="submit" value="Winner List" class="btn btn-success btn-sm" />
					</form>';
			}
			?>
			<tr class="text-center" <?=$style?>>
				<td><?=$sr_no;?></td>
				<td><?=$date1?></td>
				<td><?=$tot_prtcp?></td>
				<td><?=$win_btn;?></td>
				<td><?=$status?></td>
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