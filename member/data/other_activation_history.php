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

$sql = "SELECT * FROM account WHERE user_id = '$id' And type IN (11,20,24,31) $qur_set_search ORDER BY date ASC";
//AND remarks LIKE 'Debit Fund For TOPUP%' 
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(dr) amt , COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tamount = $ro['amt'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="11">Total Investment:- &nbsp; &#36; <?=$tamount;?></th></tr>
		<tr>
			<th class="text-center">Sr. No</th>
			<th class="text-center">Date</th>
			<th class="text-center">Paid From</th>
			<th class="text-center">Paid For</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Package</th>
			<th class="text-center">Mode</th>
			<th class="text-center">Status</th>
			<th class="text-center">Topup Type</th>
			<th class="text-center">Invoice</th>
		</tr>
		</thead>
		<?php 
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		$q = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($r = mysqli_fetch_array($q))
		{
			$id = $r['id'];
			$date = $r['date'];
			$date1 = date('d M Y', strtotime($date));
			$amount = $r['dr'];
			$account = explode(" ",$r['account']);
			$topupto = $account[count($account)-1];
			$remarks = $r['remarks'];
			$type = $r['type'];
			
			$login_username = get_user_name($login_id);
			$com_user_id = get_new_user_id($topupto);
			
			$topup_type = "<B class='text-danger'>Community Topup</B>";
			$plan_name = my_package($com_user_id)[0];
			$form = "<form method='post' action='invoice.php' target='_blank' id='invoice_u'>
							<input type='hidden' name='table_id' value='$id' />
							<input type='hidden' name='user_id' value='$com_user_id' />
							<input type='hidden' name='amount' value='$amount' />
							<input type='hidden' name='date' value='$date' />
							<button style='background:none; border:none;' class='text-info'>
								<i class='fa fa-info-circle'></i>
							</button>
						</form>";
						
			if($topupto == $login_username){
				$topup_type = "<B class='text-info'>Self Topup</B>";
				if($type == 24){
					$topup_type = "<B class='text-primary'>Self Upgrade</B>";
				}
				
				$plan_name = my_package($login_id)[0];
				
				$form = "<form method='post' action='invoice.php' target='_blank' id='invoice_u'>
							<input type='hidden' name='table_id' value='$id' />
							<input type='hidden' name='user_id' value='$login_id' />
							<input type='hidden' name='amount' value='$amount' />
							<input type='hidden' name='date' value='$date' />
							<button style='background:none; border:none;' class='text-info'>
								<i class='fa fa-info-circle'></i>
							</button>
						</form>";
			}
			
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$date1;?></td>
				<td><?=$login_username?></td>
				<td><?=$topupto;?></td>
				<td>&#36; <?=$amount;?></td>
				<td><?=$plan_name;?></td>
				<td>E-Wallet</td>
				<td><b class="text-success">Approved</b></td>
				<td><?=$topup_type;?></td>
				<td><?=$form?></td>
			</tr>
			<?php
			$sr_no++;
		}
		?>
	</table> <?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }
?>	
