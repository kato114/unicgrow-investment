<?php
include('../security_web_validation.php');

session_start();
//include("function/account_maintain.php");
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
		$qur_set_search = " AND DATE(date) BETWEEN '$st_date' AND '$en_date' ";
	}
}


$remark = "";
$sql = "SELECT remark FROM cancel_investment WHERE user_id = '$user_id' and mode=1 and re_mode=1";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0){
	while($row = mysqli_fetch_array($query)){
		$remark = $row['remark'];
	}
	echo "<div class='alert alert-danger'>".'Note : Your Refund issued by "'.$remark.'" '."!!</div>";
}
mysqli_free_result($query);
if($remark == ""){ ?>
	
	<?php
	$sqli = "SELECT * FROM withdrawal_crown_wallet WHERE user_id = '$user_id' $qur_set_search 
	ORDER BY id DESC";
	$SQL = "$sqli LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$num = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}

	if($num > 0){ ?>
	<form method="post" action="index.php?page=<?=$val?>">
		<div class="col-md-3 ">
			<input type="date" name="st_date" placeholder="Start Date" class="form-control" id="date" />
		</div>
		<div class="col-md-3">
			<input type="date" name="en_date" placeholder="End Date" class="form-control" id="date" />
		</div>
		<div class="col-md-1 text-right">
			<input type="submit" value="Search" name="Search" class="btn btn-info" />
		</div>
	</form>	
	<div class="col-md-12">&nbsp;</div>
		<table class="table table-bordered table-hover table-responsive">
			<thead>
				<tr>
					<th class="text-center">Sr. No.</th>
					<th class="text-center">Date</th>
					<th class="text-center">Withdrawal Amount</th>
					<!--<th class="text-center">Withdrawal Amount(BTC)</th>
					<th class="text-center">TDS</th>-->
					<th class="text-center">Admin Tax (<?=$withdrwal_money_tax?> %)</th>
					<th class="text-center">Net Payble</th>
					<!--<th class="text-center">Verify Date</th>
					<th class="text-center">Payment Mode</th><th class="text-center">Remarks</th>-->
					<th class="text-center">Hash</th>
					
					<th class="text-center">Status</th>
				</tr>
			</thead>
			<?php
			$pnums = ceil ($num/$plimit);
				
			if($newp == ''){ $newp = '1'; }
		
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			$sr_no = $starting_no;
			$sql = "$sqli LIMIT $start,$plimit";
			$que = query_execute_sqli($sql);
			while($row = mysqli_fetch_array($que))
			{
				$id = $row['id'];
				$amount = $row['request_crowd']+$row['tax'];
				$tax = $row['tax'];
				$usd_amt = $row['amount'];
				$tds = 0;
				$status = $row['status'];
				$action_date = $row['accept_date'];
				$req_date = date('d/m/Y H:i:s' , strtotime($row['date']));
				$wallet_type = $row['ac_type'];
				$payment_mode = $pm_name[$wallet_type-1];
				$tax_amt = $usd_amt*$tax/100;
				$net_amt = $usd_amt-$tax_amt;
				$remarks = $row['sys_comment'];
				$utr_no = $row['transaction_hash'];
				
				if($action_date == '0000-00-00 00:00:00'){ $paid_date = '................'; }
				else{ $paid_date = date('d/m/Y H:i:s' , strtotime($row['accept_date'])); }
				
				switch($status){
					case 0 : $paid = "<span class='label label-warning'>Proceed</span>";	break;
					case 1 : $paid = "<span class='label label-info'>Processing</span>";	break;
					case 2 : $paid = "<span class='label label-primary'>Approved</span>";	break;
					case 3 : $paid = "<span class='label label-danger'>Cancel</span>";	break;
					case 65 : $paid = "<span class='label label-success'>Unpaid</span>";	break;
				}
				/*$usd_amt = $wallet_type == 1 ? $usd_amt." (".$row['request_crowd']." BTC)" : ($wallet_type == 2 ? $usd_amt." (".$row['request_crowd']." ETH)" : $usd_amt);*/
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$req_date?></td>
					<td>&#36;<?=$usd_amt?></td>
					<!--<td>&#36; <?=$amount?></td>
					<td>&#36; <?=$tax?></td>-->
					<td>&#36; <?=$tax_amt?> </td>
					<td>&#36; <?=$net_amt?></td>
					<!--<td>&#36; <?=$tax_amt?> &nbsp;&nbsp;(<?=$tax?> %)</td>
					<td><?=$paid_date?></td>
					<td>Bank</td><td><?=$remarks?></td>-->
					<td><?=$utr_no?></td>
					
					<td><?=$paid?></td>
				</tr> <?php
				$sr_no++;
			} ?>
			</table> <?php
		pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
	}	
	else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
}
?>

