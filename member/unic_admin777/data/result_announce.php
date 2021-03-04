<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");
include("../function/direct_income.php");
include("../function/setting.php");

$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_ticket_no'],$_SESSION['SESS_username']);
}
else{
	$_POST['Search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['ticket_no'] = $_SESSION['SESS_ticket_no'];
	$_POST['username'] = $_SESSION['SESS_username'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " WHERE DATE_FORMAT(date,'%Y-%m-%d') BETWEEN '$st_date' AND '$en_date' ";
	}
}
if(isset($_POST['asubmit'])){
	$adate = $_POST['adate'];
	$rdate = date('Y-m-d' , strtotime($adate));
	if($rdate == $systems_date){
		$tamount = $_POST['tamount'];
		$tmember = $_POST['tmember'];
	?>
	<script>
		var addSerialNumber = function () {
			var i = 0
			$('table tr').each(function(index) {
				$(this).find('td:nth-child(1)').html(index-2+1);
				var row_index = $(this).index();
				if(row_index > 4){//control row
					var chl = $('#prizeTable >tbody >tr').length;;
					if(chl > 0){
						$('#prizeTable tr:last').remove();
						addSerialNumber();
					}
				}
			}); 
		};
		$(document).ready(function() { 
			$("#add_prize").click(function (e) {
				
				//alert(row_index);
				$('#prizeTable > tbody:last-child').append('<tr><td></td><td><input type="text" name="member[]" Placeholder="Member" class="text-center pmember"  /></td><td><input type="text" name="percent[]" Placeholder="percent" class="text-center ppercent" oninput="this.value=isNaN(this.value) ? 0 : this.value;this.value=(this.value < 0) ? 0 : this.value;" /></td></tr>');
				addSerialNumber();
			});
			$("#remove_prize").click(function (e) {
				var chl = $('#prizeTable >tbody >tr').length;;
				if(chl > 0){
					$('#prizeTable tr:last').remove();
					addSerialNumber();
				}
			});
		});
		function prizeValidate(){
			var total1 = total2 = 0;
			$('.pmember').each(function (index, element) {
				total1 = total1 + parseFloat($(element).val());
			});
			$('.ppercent').each(function (index, element) {
				total2 = total2 + parseFloat($(element).val());
			});
			if(total1 > <?=$tmember?>){
				$("#error").html("Error : Prize Total Member Is Gretter Than Sold Lottery Member!!");
				return false;
			}
			if(total2 > 100){
				$("#error").html("Error : Prize Total Percent Must Be Less Or Equal To 100 !!");
				return false;
			}
			return true;
		}
		
	</script>
	<span id="error" class="text-danger"></span>
	<form action="index.php?page=<?=$val?>" method="post" onsubmit="return prizeValidate()">
		<input type="text" name="adate" value="<?=$adate?>" />
		<input type="hidden" name="tamount" value="<?=$tamount?>" />
		<input type="hidden" name="tmember" value="<?=$tmember?>" />
		<div class="table-responsive">
			<table class="table table-bordered table-hover" id="prizeTable">
				<thead>
				<tr>
					<th>Sold Ticket : <?=$tmember?></th>
					<th>Amount : &#36;<?=$tamount?></th>
					<th colspan="1" class="text-right">
					<input type="button" name="add_prize" id="add_prize" value="Add Prize" class="btn btn-info btn-xs" />&nbsp;&nbsp;
					<input type="button" name="remove_prize" id="remove_prize" value="Remove Prize" class="btn btn-danger btn-xs" />&nbsp;&nbsp;
					<input type="submit" name="announce_prize" id="a_prize" class="btn btn-success btn-xs" value="Announce Prize" /></th>
				</tr>
				<tr>
					<th>Prize No.</th>
					<th>Member/Ticket No.</th>
					<th>Percent</th>
				</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</form>
	<?php
	}
	else{
		echo "<B class='text-danger'>Error : Result Announce On Date '$rdate' !!</B>";
	}
}
elseif(isset($_POST['announce_prize'])){
	$adate = $_POST['adate'];
	$tamount = $_POST['tamount'];
	$tmember = $_POST['tmember'];
	$prize_member_manual = false;
	$prize_ticket_no = array();
	//$ptm = array_sum($_POST['member']);//prize total member
	$ptp = array_sum($_POST['percent']);//prize total percent
	for($i = 0; $i < count($_POST['member']); $i++){
		if($_POST['member'][$i] != '0' and $_POST['member'][$i] != "" and strlen($_POST['member'][$i])> 10){
			if(!is_int($_POST['member'][$i])){
				
				$prize_member = explode(",",$_POST['member'][$i]);
				$prize_member_manual = true;
				
				for($n = 0; $n < count($prize_member); $n++){
					$ticket_no = $prize_member[$n];
					if(strlen($ticket_no) > 10){
						$sql = "select * from lottery_ticket where ticket_no='$ticket_no' and mode=0 
								and DATE_FORMAT(rdate,'%Y-%m-%d') = '$adate'";
						$query = query_execute_sqli($sql);
						while($row = mysqli_fetch_array($query)){
							$prize_ticket_no[] = $row['id'];
						}
						mysqli_free_result($query);
						$ptm = $ptm + 1;
					}
					else{
						$ptm = $ptm + $ticket_no;
					}
				}
				//$ptm = $ptm + count(explode(",",$_POST['member'][$i]));
			}
		}
		else{
			$ptm = $ptm + $_POST['member'][$i];
		}
	}
	//print $ptm;
	$process = 1;
	if($ptm > $tmember){
		$error1 = "<B class='text-danger'>Error : Prize Total Member Is Gretter Than Sold Lottery Member!!</B><br>";
		$process = 0;
	}
	if($ptp > 100){
		$error2 = "<B class='text-danger'>Error : Prize Total Percent Must Be Less Or Equal To 100 !!</B><br>";
		$process = 0;
	}
	if($prize_member_manual){
		if(count($prize_ticket_no) != count(array_unique($prize_ticket_no))){
			$process = 0;
			$error3 = "<B class='text-danger'>Error : Duplicate Or Invalid Ticket Found !!</B>";
		}
		
	}
	if($process == 1){
		$result = set_lottery_prize($adate,$tamount,$tmember,$_POST['member'],$_POST['percent'],$systems_date_time);
		if($result){
			echo "<B class='text-success'>Lottery Ticket Result Successfully Announced !!</B>";
			$sql = "select * from lottery_ticket where DATE_FORMAT(rdate,'%Y-%m-%d') = '$adate' and mode=1 and rank >0";
			$quer = query_execute_sqli($sql);
			?>
			<table class="table table-bordered table-hover">
			<thead>
			<tr>
				<th class="text-center">Sr. no.</th>
				<th class="text-center">Date</th>
				<th class="text-center">Ticket No.</th>
				<th class="text-center">Prize</th>
				<th class="text-center">Rank</th>
			</tr>
			</thead>
			<?php
			$s_no = 1;
			while($ro = mysqli_fetch_array($quer))
			{
				$tmember = $row['tmember']; 
				$rank = round($row['rank'],2); 
				$date = date('d/m/Y' , strtotime($row['date']));
				$ticket_no = $row['ticket_no'];
				$ramount = round($row['ramount'],2); 
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$date?></td>
					<td><?=$ticket_no?></td>
					<td>&#36;<?=$ramount?></td>
					<td><?=$rank?></td>
				</tr> <?php
				$sr_no++;
			}
			?>
			</table> <?php
		}
		else
			echo "<B class='text-danger'>Somthing Goes Wrong !!</B>";
	}
	else{
		echo $error1,$error2,$error3;
	}
}
else{
	
	$sql = "select t1.*,COUNT(/*DISTINCT*/(t2.user_id)) tmember,sum(t2.amount) amount,t2.mode,t2.lottery_no  from (
	SELECT DATE_FORMAT(`rdate`,'%Y-%m-%d') 'date' FROM `lottery_ticket` $qur_set_search GROUP by DATE_FORMAT(`rdate`,'%Y-%m-%d')
	) t1
	INNER JOIN `lottery_ticket` t2 on t1.date = DATE_FORMAT(t2.`rdate`,'%Y-%m-%d') 
	GROUP BY DATE_FORMAT(`rdate`,'%Y-%m-%d')
	ORDER BY t1.date DESC";
	
	/*$sql = "select COUNT(t2.user_id) tmember,sum(t2.amount) amount,t2.mode,DATE_FORMAT(t2.`rdate`,'%Y-%m-%d') 'date'  from `lottery_ticket` t2 
	WHERE DATE_FORMAT(`rdate`,'%Y-%m-%d') in(SELECT DATE_FORMAT(`rdate`,'%Y-%m-%d') 'date' FROM `lottery_ticket` $qur_set_search GROUP by DATE_FORMAT(`rdate`,'%Y-%m-%d'))
	GROUP BY DATE_FORMAT(`rdate`,'%Y-%m-%d')
	ORDER BY DATE_FORMAT(`rdate`,'%Y-%m-%d') DESC";*/
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	//die();
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT SUM(amount) amt , SUM(tmember) num,COUNT(tmember) cnt FROM ($sql) t1";
	$quer = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($quer))
	{
		$tamount = $ro['amt'];
		$ttmember = $ro['num'];
		$tot_rec = $ro['cnt'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	mysqli_free_result($quer);
	mysqli_free_result($query);
	if($totalrows != 0){ ?>
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
			<div class="col-md-2">
				<input type="submit" value="Search" name="Search" class="btn btn-warning btn-sm">
			</div>
		</form>
		<table class="table table-bordered table-hover">
			<thead>
			<tr>
				<th colspan="3">Total Sold Ticket : <?=$ttmember;?></th>
				<th colspan="3">Total Amount : &#36;<?=$tamount;?></th>
			</tr>
			<tr>
				<th class="text-center">Sr. no.</th>
				<th class="text-center">Lottery No.</th>
				<th class="text-center">Date</th>
				<th class="text-center">Sold Ticket</th>
				<th class="text-center">Amount</th>
				<th class="text-center">Result</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil ($totalrows/$plimit);
			
			if ($newp==''){ $newp='1'; }
		
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;
			$que = query_execute_sqli("$sql LIMIT $start,$plimit");
			while($row = mysqli_fetch_array($que))
			{
				$tmember = $row['tmember']; 
				$amount = round($row['amount'],2); 
				$date = date('d/m/Y' , strtotime($row['date']));
				$rdate = date('d/m/Y' , strtotime($row['rdate']));
				$ramount = round($row['ramount'],2); 
				$lottery_no = $row['lottery_no'];
				if($row['mode'] == 0){
					$form = "<form action='index.php?page=$val' method='post'>
								<input type='hidden' name='adate' value='".$row['date']."' />
								<input type='hidden' name='rdate' value='".$rdate."' />
								<input type='hidden' name='tamount' value='".$amount."' />
								<input type='hidden' name='tmember' value='".$tmember."' />
								<input type='submit' name='asubmit' value='Announce' class='btn btn-info btn-xs' />
							</form>";
				}
				else{
					$form = "<form action='index.php?page=lottery_comp' method='post' target='_blank'>
						<input type='hidden' name='rdate' value='".$row['date']."' />
						<input type='submit' value='Complete' name='complete' class='btn btn-success btn-xs' />
					</form>";
				}
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$lottery_no?></td>
					<td><?=$date?></td>
					<td><?=$tmember?></td>
					<td>&#36;<?=$amount?></td>
					<td><?=$form?></td>
				</tr> <?php
				$sr_no++;
			}
			?>
		</table> <?php 
		mysqli_free_result($que);
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}		
	else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
}
?>
