<?php
session_start();
//include("function/account_maintain.php");
include("../function/setting.php");
include("../function/functions.php");

	
$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['search_status'] = $_SESSION['SESS_search_status'];
	$_POST['search_utr'] = $_SESSION['SESS_search_utr'];
}
else{
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_search_username'],$_SESSION['SESS_search_status'],$_SESSION['SESS_search_utr']);
}
if(isset($_POST['Search'])){

	if($_POST['search_username'] !=''){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " WHERE t1.user_id = '$search_id'";
	}
	
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		
		$qur_set_search = " WHERE DATE(t1.date) BETWEEN '$st_date' AND '$en_date' ";
	}
	
	if($_POST['search_status'] !=''){
		$_SESSION['SESS_search_status'] = $search_status = $_POST['search_status'];
		switch($search_status){
			case 1 : $qur_set_search = " WHERE t1.`paid` = 1 "; $sel1 = 'selected="selected"'; break;
			case 2 : $qur_set_search = " WHERE t1.`paid` = 0 "; $sel2 = 'selected="selected"'; break;
			case 3 : $qur_set_search = " WHERE t1.`paid` = 3 "; $sel3 = 'selected="selected"'; break;
		}
	}
	if($_POST['search_utr'] !=''){
		$_SESSION['SESS_search_utr'] = $search_utr = $_POST['search_utr'];
		$qur_set_search = " WHERE t1.transaction_no = '$search_utr'";
	}
}
?>

<table class="table table-bordered">
	<tr>
		<td>
			<form method="post" action="index.php?page=<?=$val?>">
				<input type="hidden" name="Search" value="Search" />
				<select name="search_status" class="form-control" onchange="this.form.submit();">
					<option value="">Search Status</option>
					<option value="1" <?=$sel1?>>Confirm</option>
					<option value="2" <?=$sel2?>>Pending</option>
					<option value="3" <?=$sel3?>>Cancel</option>
				</select>
			</form>
		</td>
		<form method="post" action="index.php?page=<?=$val?>">
		<td>
			<input type="text" name="search_utr" placeholder="Search By UTR No." class="form-control" />
		</td>
		<td>
			<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
		</td>
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
		</form>	
	</tr>
</table>


<?php	
$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM fund_request t1 
INNER JOIN users t2 ON t1.user_id = t2.id_user
$qur_set_search 
ORDER BY t1.id DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

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
			<th class="text-center">Sr No.</th>
			<th class="text-center">Username</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Payment Mode</th>
			<th class="text-center">Request Date</th>
			<th class="text-center">Verify Date</th>
			<th class="text-center">UTR No.</th>
			<th class="text-center">Remarks</th>
			<th class="text-center">Status</th>
		</tr>
	</thead>
	<?php
	$pnums = ceil ($num/$plimit);
		
	if($newp == ''){ $newp = '1'; }

	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	$sr_no = $starting_no;

	$que = query_execute_sqli("$sql LIMIT $start,$plimit");
	while($row = mysqli_fetch_array($que))
	{
		$id = $row['id'];
		$amount = $row['amount'];
		$tax = $row['tax'];
		$status = $row['paid'];
		$action_date = $row['app_date'];
		$username = $row['username'];
		$req_date = date('d/m/Y' , strtotime($row['date']));
		$payment_mode = $row['payment_mode'];
		$admin_remarks = $row['admin_remarks'];
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$utr_no = $row['transaction_no'];
		$payment_mode = $add_fund_mode_value[$payment_mode-1];
		
		if($action_date == '0000-00-00'){ $paid_date = '................'; }
		else{ $paid_date = date('d/m/Y' , strtotime($row['app_date'])); }
		
		switch($status)
		{
			case 0 : $paid = "<B class='text-warning'>Proceed</B>"; $remarks = "";	break;
			case 1 : $paid = "<B class='text-info'>Confirm</B>"; $remarks = "";	break;
			case 3 : $paid = "<B class='text-danger'>Cancel</B>"; $remarks = $admin_remarks;	break;
		}
		
		$tax_amt = 	$amount*$tax/100;
		$net_amt = $amount-$tax_amt;
		?>
		<tr class="text-center">
			<td><?=$sr_no?></td>
			<td><?=$name?></td>
			<td><?=$username?></td>
			<td>&#36; <?=$amount?></td>
			<td>Bank</td>
			<td><?=$req_date?></td>
			<td><?=$paid_date?></td>
			<td><?=$utr_no?></td>
			<td><?=$remarks?></td>
			<td><?=$paid?></td>
		</tr> <?php
		$sr_no++;
	} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}	
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>

