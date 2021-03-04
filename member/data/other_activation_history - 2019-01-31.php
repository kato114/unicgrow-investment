<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];
unset($_SESSION['s_date']);
unset($_SESSION['e_date']);

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
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND date BETWEEN '$st_date' AND '$en_date' ";
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
/*$sql = "SELECT t1.`user_id`,t1.`by_id`,t1.`dr`,t1.`particular`,t2.id,t2.user_id topup_id,t2.update_fees
,t3.username,t2.date,t2.by_wallet,t2.profit 
FROM `ledger` t1
left join reg_fees_structure t2 on t1.by_id = t2.id
left join users t3 on t2.user_id = t3.id_user
WHERE t1.user_id='$id' and t1.particular like 'Debit Fund For TOPUP FROM%' and t2.user_id is not null ";*/

$sql1 = "SELECT * FROM account WHERE user_id = '$id' AND type = 20";
$query1 = query_execute_sqli($sql1);
echo $num = mysqli_num_rows($query1);

$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$id' AND mode  = 1 AND update_fees > 0 $qur_set_search";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(update_fees) amt , COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tamount = $ro['amt'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0 or $num > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="11">Total Investment:- &nbsp; &#36; <?=$tamount;?></th></tr>
		<tr>
			<th class="text-center">Sr. No</th>
			<th class="text-center">Date</th>
			<th class="text-center">Paid From</th>
			<th class="text-center">Paid For</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Mode</th>
			<!--<th class="text-center">Monthly Return</th>
			<th class="text-center">Received ROI</th>
			<th class="text-center">Remaining ROI</th>
			<th class="text-center">ROI Start Date</th>
			<th class="text-center">ROI End Date</th>-->
			<th class="text-center">Status</th>
			<th class="text-center">Invoice</th>
		</tr>
		</thead>
		<?php 
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$i = 1;
		$q = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($r = mysqli_fetch_array($q))
		{
			$id = $r['id'];
			$date = date('d M Y', strtotime($r['date']));
			$type = $r['by_wallet'];
			$reg_fees = $r['dr'];
			$update_fees = $r['update_fees'];
			$rcw_id = $r['rcw_id'];
			$profit = $r['profit'];
			$user_id = $r['user_id'];
			$total_days = $r['total_days'];
			$st_date = $r['date'];
			$remarks = $r['remarks'];
			$start_date = date('d/m/Y', strtotime($st_date."+ 1 Month"));
			$en_date = date('Y-m-d', strtotime($st_date."+".$total_days." Month"));
			$end_date = date('d/m/Y', strtotime($en_date));
			
			$amount = $update_fees;
			$topupto = get_user_name($user_id);
			
			$recivd_days = $r['count'];
			$remain_days = $total_days - $r['count'];
						
			//if($type == 0){ $status = "Activation Wallet"; }
			//else{ $status = "ATS Wallet"; }
			?>
			
			<tr class="text-center">
				<td><?=$i;?></td>
				<td><?=$date;?></td>
				<td><?=$_SESSION['mlmproject_user_username']?></td>
				<td><?=$topupto;?></td>
				<td>&#36; <?=$amount;?></td>
				<td>E-Wallet</td>
				<td><b class="text-success">Approved</b></td>
				<!--<td>&#36; <?=$profit;?></td>
				<td><?=$recivd_days;?> Month</td>
				<td><?=$remain_days;?> Month</td>
				<td><?=$start_date;?></td>
				<td><?=$end_date;?></td>-->
				<td>
					<form method="post" action="invoice.php" target="_blank" id="invoice_u">
						<input type="hidden" name="table_id" value="<?=$id?>" />
						<input type="hidden" name="user_id" value="<?=$user_id?>" />
						<input type="hidden" name="amount" value="<?=$amount?>" />
						<input type="hidden" name="date" value="<?=$date?>" />
						<button style="background:none; border:none;" class="text-info">
							<i class="fa fa-info-circle"></i>
						</button>
					</form>
				</td>
				<!--<td class="span1 text-center"><?=$total_days;?></td>
				<td class="span1 text-center">
					<form method="post" action="data/img.php" target="_blank">
						<input type="hidden" name="topup_id" value="<?=$reg_fees_id; ?>">
						<input type="submit" name="certificate" value="Certificate" class="btn btn-primary">
					</form>
					<form method="post" action="index.php?page=calender" target="_blank">
						<input type="hidden" name="s_date" value="<?=$s_date?>">
						<input type="hidden" name="e_date" value="<?=$e_date?>">
						<input type="hidden" name="amount" value="<?=$amount?>">
						<input type="submit" name="calender" value="Calender" class="btn btn-primary">
					</form>
				</td>-->
			</tr>
			<?php
			$i++;
		}
		?>
	</table> <?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }
?>	
