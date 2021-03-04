<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = "";
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['w_status'] = $_SESSION['SESS_w_status'];
	$_POST['date_giv'] = $_SESSION['SESS_date_giv'];
	$_POST['ac_type'] = $_SESSION['SESS_ac_type'];
	$_POST['t_status'] = $_SESSION['SESS_t_status'];
}
else{
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_w_status'],$_SESSION['SESS_t_status']);
}


if(isset($_POST['Search'])){
	if(!empty($_POST['search_username'])){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " AND user_id = '$search_id' ";
	}
	
	if(!empty($_POST['w_status'])){
		$_SESSION['SESS_w_status'] = $w_status = $_POST['w_status'];
		if($w_status == 1){ $w_status = "65,0"; }
		$qur_set_search = "";
		$qur_set_search = " WHERE status IN ($w_status) ";
	}
	
	if(!empty($_POST['t_status'])){
		$_SESSION['SESS_t_status'] = $t_status = $_POST['t_status'];
		$date_giv = $systems_date;
	}
}
?>
<table class="table table-bordered">
	<tr>
		<td>
			<form method="post" action="index.php?page=<?=$val?>">
				<input type="hidden" name="Search" value="1" />
				<select name="w_status" class="form-control" onchange="this.form.submit();" required>
					<option value="">Select Status</option>
					<option value="1" <?php if($_POST['w_status'] == 1){?> selected="selected" <?php } ?>>
						Pending
					</option>
					<option value="2" <?php if($_POST['w_status'] == 2){?> selected="selected" <?php } ?>>
						Approved
					</option>
					<option value="3" <?php if($_POST['w_status'] == 3){?> selected="selected" <?php } ?>>
						Cancelled
					</option>
				</select>
			</form>
		</td>
		<form method="post" action="index.php?page=<?=$val?>">
		<td><input type="text" name="search_username" placeholder="  By Username" class="form-control" /></td>
		
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
		</form>
		<!--<td class="text-right">
			<input type="submit" name="create_file" value="Create Excel File" class="btn btn-warning btn-sm"/>
			<form method="post" action="simple_view_withdraw.php" target="_blank"> 
				<input type=submit name="simple_view" value="Simple View" class="btn btn-warning btn-sm" />
			</form>
		</td>-->
	</tr>
</table>


<?php
/*$sql = "SELECT T1.*,T2.username,T2.phone_no FROM withdrawal_crown_wallet T1 
LEFT JOIN users T2 ON T1.user_id = T2.id_user 
WHERE T1.ac_type = '$ac_type' AND DATE(T1.date) = DATE('$date_giv') $qur_set_search";*/
$sql = "SELECT * FROM withdrawal_crown_wallet $qur_set_search";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(amount) amt,SUM(request_crowd) amt1,COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query)){
	$tot_rec = $ro['num'];
	$total_amount = $ro['amt'];
	$total_amount1 = $ro['amt1'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0){ ?>
	<table class="table table-bordered">
		<thead>
		<tr><th colspan="9">Total Debit Amount : <?=$total_amount; ?> &#36;</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Date Time</td>
			<th class="text-center">Pay From</td>
			<th class="text-center">DR Amount</td>
			<th class="text-center">Status</td>
		</tr>
		</thead>
		<?php		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");		
		while($row = mysqli_fetch_array($query)){
			$table_id = $row['id'];
			$user_id = $row['user_id'];
			//$username = $row['username'];
			$username = get_user_name($user_id);
			$amount = 0;
			$amount = $row['request_crowd'];
			$amount1 = $row['amount'];
			$hash_code = $row['transaction_hash'];
			$remarks = $row['user_comment'];
			$tds = $row['tax'];
			$adm_tax = $row['cur_bitcoin_value'];
			$date = date('d/m/Y H:i:s', strtotime($row['date']));
			
			$tot_amt = $amount+$adm_tax;
			$status = $row['status'];
			switch($status){
				case 65 : $status = "<span class='label label-warning'>Pending</span>";	break;
				case 1 : $status = "<span class='label label-success'>Processing</span>";	break;
				case 2 : $status = "<span class='label label-primary'>Confirm</span>";	break;
				case 3 : $status = "<span class='label label-danger'>Cancel</span>";	break;
				case 65 : $status = "<span class='label label-warning'>Unconfirmed</span>";	break;
			}

			$pay_from = $row['ac_type'] == 1 ? "Bitcoin" : "USD";
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$date?></td>
				<td><?=$pay_from?></td>
				<td>&#36;<?=round($amount1,2)?> </td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>