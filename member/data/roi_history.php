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
$sql = "select COALESCE(sum(amount),0) from income where type='".$income_type[2]."' and mode=0 and user_id='$user_id'";
$query = query_execute_sqli($sql);
$tot_pending_roi = mysqli_fetch_array($query)[0];
$sqli = "SELECT * FROM withdrawal_crown_wallet WHERE user_id = '$user_id' AND ac_type = 2 $qur_set_search 
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

if($num > 0)
{
?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th class="text-center">Pending Withdrawal</th>
			<th class="text-center" colspan="6">&#36; <?=$tot_pending_roi?></th>
		</tr>
		<tr>
			<th class="text-center">Sr No.</th>
			<th class="text-center">Date</th>
			<th class="text-center">Withdrawal Amount</th>
			<th class="text-center">TDS</th>
			<th class="text-center">Admin Tax</th>
			<th class="text-center">Net Payble</th>
			<!--<th class="text-center">Verify Date</th>
			<th class="text-center">Payment Mode</th>-->
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
		$amount = $row['request_crowd']+$row['tax']+$row['cur_bitcoin_value'];
		$tax = $row['tax'];
		$status = $row['status'];
		$action_date = $row['accept_date'];
		$req_date = date('d/m/Y H:i:s' , strtotime($row['date']));
		$wallet_type = $row['ac_type'];
		$payment_mode = $pm_name[$wallet_type-1];
		
		if($action_date == '0000-00-00 00:00:00'){ $paid_date = '................'; }
		else{ $paid_date = date('d/m/Y H:i:s' , strtotime($row['accept_date'])); }
		
		switch($status)
		{
			case 0 : $paid = "<B class='text-warning'>Proceed</B>";	break;
			case 1 : $paid = "<B class='text-danger'>Processing</B>";	break;
			case 2 : $paid = "<B class='text-info'>Confirm</B>";	break;
			case 3 : $paid = "<B class='text-warning'>Cancel</B>";	break;
			case 65 : $paid = "<B class='text-warning'>Unconfirmed</B>";	break;
		}
		
		$tax_amt = 	$row['tax']+$row['cur_bitcoin_value'];
		$net_amt = $amount-$tax_amt;
		?>
		<tr class="text-center">
			<td><?=$sr_no?></td>
			<td><?=$req_date?></td>
			<td>&#36; <?=$amount?></td>
			<td>&#36; <?=$row['tax']?></td>
			<td>&#36; <?=$row['cur_bitcoin_value']?></td>
			<td>&#36; <?=$net_amt?></td>
			<!--<td>&#36; <?=$tax_amt?> &nbsp;&nbsp;(<?=$tax?> %)</td>
			<td><?=$paid_date?></td>
			<td>Bank</td>-->
			<td><?=$paid?></td>
		</tr> <?php
		$sr_no++;
	} ?>
	</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}	
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>

