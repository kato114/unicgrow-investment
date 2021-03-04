<?php
include('../../security_web_validation.php');
//die("Please contact to customer care.");

include("condition.php");
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
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_ticket_no'],$_SESSION['SESS_username'],$_SESSION['SESS_chk_lottery']);
}
else{
	$_POST['Search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['ticket_no'] = $_SESSION['SESS_ticket_no'];
	$_POST['username'] = $_SESSION['SESS_username'];
	$_POST['chk_lottery'] = $_SESSION['SESS_chk_lottery'];
}
if(isset($_POST['Search'])){
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " WHERE DATE_FORMAT(t1.date,'%Y-%m-%d') BETWEEN '$st_date' AND '$en_date' ";
	}
	if($_POST['ticket_no'] != ''){
		$ticket_no = $_POST['ticket_no'];
		$sql = "SELECT * FROM lottery_ticket WHERE ticket_no = '$ticket_no' and mode=0";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		mysqli_free_result($query);
		if($num > 0){
			$qur_set_search .= " WHERE t1.ticket_no = '$ticket_no'";
			$_SESSION['SESS_ticket_no'] = $ticket_no;
		}
		else{
			echo "<B class='text-danger'>In-Correct Ticket No !!</B>";
		}
	}
	if($_POST['username'] !=''){
		$_SESSION['SESS_username'] = $search_userid = $_POST['username'];
		$search_id = get_new_user_id($search_userid);
		$qur_set_search = " WHERE t1.user_id = '$search_id'";
	}
	if($_POST['chk_lottery'] !=''){
		$_SESSION['SESS_chk_lottery'] = $chk_lottery = $_POST['chk_lottery'];
		$qur_set_search = " WHERE t1.lottery_no = '$chk_lottery'";
	}
}
?>
<!--<form method="post" action="index.php?page=<?=$val?>">
	<div class="col-md-2">
		<select name="chk_lottery" class="form-control">
			<option value="">Select Lottery</option>
			<?php
			$sql = "SELECT * FROM lottery_ticket GROUP BY lottery_no";
			$query = query_execute_sqli($sql);
			while($row = mysqli_fetch_array($query)){
				$lottery_no = $row['lottery_no']; ?>
				<option value="<?=$lottery_no?>"><?=$lottery_no?></option> <?php
			}
			?>
		</select>
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
	<div class="col-md-2">
		<input type="text" name="ticket_no" placeholder="Ticket No." class="form-control" />
	</div>
	<div class="col-md-3">
		<input type="text" name="username" placeholder="Username" class="form-control" />
	</div>
	<div class="col-md-1 text-right">
		<input type="submit" value="Search" name="Search" class="btn btn-warning btn-sm">
	</div>
</form>	
<div class="col-md-12">&nbsp;</div>-->
<?php

$minus_1_week = date('Y-m-d', strtotime($systems_date."- 1 Day"));
$last_week = get_pre_nxt_date($minus_1_week , $lottery_result_day);
$f_day_week = $last_week[0];
$l_day_week = $last_week[1];

$sql = "SELECT t1.*, COUNT(t2.id) tot_lot, COUNT(t3.id) winners1, COUNT(t4.id) winners2, COUNT(t5.id) winners3, COUNT(t6.id) winners4, COUNT(t7.id) winners5, COALESCE(SUM(t3.ramount),0) amt1, COALESCE(SUM(t4.ramount),0) amt2, COALESCE(SUM(t5.ramount),0) amt3, COALESCE(SUM(t6.ramount),0) amt4, COALESCE(SUM(t7.ramount),0) amt5 
FROM lottery_ticket t1 
LEFT JOIN lottery_ticket t2 ON t1.id = t2.id
LEFT JOIN lottery_ticket t3 ON t1.id = t3.id AND t3.rank = 1
LEFT JOIN lottery_ticket t4 ON t1.id = t4.id AND t4.rank = 2
LEFT JOIN lottery_ticket t5 ON t1.id = t5.id AND t5.rank = 3
LEFT JOIN lottery_ticket t6 ON t1.id = t6.id AND t6.rank = 4
LEFT JOIN lottery_ticket t7 ON t1.id = t7.id AND t7.rank = 5
WHERE t1.mode = 1 $qur_set_search GROUP BY t1.lottery_no";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(amount) amt , COUNT(id) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer)){
	$amount = $ro['amt'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}
mysqli_free_result($quer);
mysqli_free_result($query);
if($totalrows > 0){ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Lottery No.</th>
			<th class="text-center">Week Date</th>
			<th class="text-center">Result Date</th>
			<th class="text-center">Lottery</th>
			<th class="text-center">Winner 1</th>
			<th class="text-center">Winner 2</th>
			<th class="text-center">Winner 3</th>
			<th class="text-center">Winner 4</th>
			<th class="text-center">Winner 5</th>
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
			$lottery_no = $row['lottery_no'];
			$date = $row['date'];
			$rdate1 = $row['rdate'];
			$rank = $row['rank'];
			$tot_lot = $row['tot_lot'];
			$rdate = date('d/m/Y' , strtotime($row['rdate']));
			
			$winners1 = $row['winners1'];
			$winners2 = $row['winners2'];
			$winners3 = $row['winners3'];
			$winners4 = $row['winners4'];
			$winners5 = $row['winners5'];
			
			
			$minus_1_week = date('Y-m-d', strtotime($row['rdate']."- 1 day"));
			$last_week = get_pre_nxt_date($minus_1_week , $lottery_result_day);
			$f_day_week = $last_week[0];
			$l_day_week = $last_week[1];
			
			
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$lottery_no?></td>
				<td><?=date('d/m/Y' , strtotime($f_day_week))?> To <?=date('d/m/Y' , strtotime($l_day_week))?></td>
				<td><?=$rdate?></td>
				<td><?=$tot_lot?></td>
				<td>
					<form action="index.php?page=result_historyw" method="post" target="_blank">
						<input type="hidden" name="rank" value="1" />
						<input type="hidden" name="rdate" value="<?=$rdate1?>" />
						Winners : 
						<input type="submit" name="winners1" value="<?=$winners1?>" class="btn btn-danger btn-xs" />
						<br /> Amount : &#36;<?=round($row['amt1'],2)?>
					</form>
				</td>
				<td>
					<form action="index.php?page=result_historyw" method="post" target="_blank">
						<input type="hidden" name="rank" value="2" />
						<input type="hidden" name="rdate" value="<?=$rdate1?>" />
						Winners : 
						<input type="submit" name="winners2" value="<?=$winners2?>" class="btn btn-danger btn-xs" />
						<br /> Amount : &#36;<?=round($row['amt2'],2)?>
					</form>
				</td>
				<td>
					<form action="index.php?page=result_historyw" method="post" target="_blank">
						<input type="hidden" name="rank" value="3" />
						<input type="hidden" name="rdate" value="<?=$rdate1?>" />
						Winners : 
						<input type="submit" name="winners3" value="<?=$winners3?>" class="btn btn-danger btn-xs" />
						<br /> Amount : &#36;<?=round($row['amt3'],2)?>
					</form>
				</td>
				<td>
					<form action="index.php?page=result_historyw" method="post" target="_blank">
						<input type="hidden" name="rank" value="4" />
						<input type="hidden" name="rdate" value="<?=$rdate1?>" />
						Winners : 
						<input type="submit" name="winners4" value="<?=$winners4?>" class="btn btn-danger btn-xs" />
						<br /> Amount : &#36;<?=round($row['amt4'],2)?>
					</form>
				</td>
				<td>
					<form action="index.php?page=result_historyw" method="post" target="_blank">
						<input type="hidden" name="rank" value="5" />
						<input type="hidden" name="rdate" value="<?=$rdate1?>" />
						Winners : 
						<input type="submit" name="winners5" value="<?=$winners5?>" class="btn btn-danger btn-xs" />
						<br /> Amount : &#36;<?=round($row['amt5'],2)?>
					</form>
				</td>
			</tr>
			<?php
			$sr_no++;
		}
		?>
	</table> <?php 
	mysqli_free_result($que);
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}		
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>
