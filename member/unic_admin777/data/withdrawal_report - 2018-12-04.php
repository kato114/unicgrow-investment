<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_st_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_st_date'];
	$_POST['en_date'] = $_SESSION['SESS_en_date'];
}

if(isset($_POST['search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != '')
	{
		$_SESSION['SESS_st_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_en_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	$search_id = get_new_user_id($search_username);
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " AND T1.date BETWEEN '$st_date%' AND '$en_date%' ";
	}
	if($search_username !=''){
		$qur_set_search = " AND T1.user_id = '$search_id' ";
	}
	if($search_username !='' and $st_date !='' and $en_date != '')
	{		
		$qur_set_search = " AND T1.user_id = '$search_id' AND T1.date BETWEEN '$st_date%' AND '$en_date%' ";
	}
}
?>
<form method="post" action="index.php?page=withdrawal_report">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="  By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Enter Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="Enter End Date" class="form-control" />
				</div>
			</div>
		</td>
		<th><input type="submit" value="Submit" name="search" class="btn btn-info"></th>
	</tr>
</table>
</form>	

<?php
$sql = "SELECT T1.*,T2.beneficiery_name,T2.bank_name,T2.bank_ac,T2.ifsc_code FROM withdrawal_crown_wallet T1 
LEFT JOIN users T2 ON T1.user_id = T2.id_user 
WHERE T1.ac_type = 1 $qur_set_search";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(request_crowd) amt,COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$total_amount = $ro['amt'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>
	<table class="table table-bordered">
		<thead><tr><th colspan="9">Total Payble Amount : <?=$total_amount; ?> &#36;</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Withdrawal Amount</th>
			<th class="text-center">TDS</th>
			<th class="text-center">Admin Tax</th>
			<th class="text-center">Net Payble Amount</th>
			<th class="text-center">Bank Info</th>
			<th class="text-center">Date Time</td>
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
		while($row = mysqli_fetch_array($query))
		{
			$user_id = get_user_name($row['user_id']);
			$amount = $row['request_crowd'];

			$hash_code = $row['transaction_hash'];
			$mode = $row['status'];
			$tds = $row['tax'];
			$adm_tax = $row['cur_bitcoin_value'];
			$date = date('d/m/Y H:i:s', strtotime($row['date']));
			
			$ac_info = "<B>Beneficiery Name :</B> ".$row['beneficiery_name']."<br><B>Bank :</B> ".$row['bank_name']."<br><B>Bank Ac :</B> ".$row['bank_ac']."<br><B>IFSC :</B> ".$row['ifsc_code'];
			
			$tot_amt = $amount+$tds+$adm_tax;
			
			switch($mode)
			{
				case 0 : $status = "<span class='label label-info'>Proceed</span>";	break;
				case 1 : $status = "<span class='label label-success'>Processing</span>";	break;
				case 2 : $status = "<span class='label label-primary'>Confirm</span>";	break;
				case 3 : $status = "<span class='label label-danger'>Cancel</span>";	break;
				case 65 : $status = "<span class='label label-warning'>Unconfirmed</span>";	break;
			}
			/*if($mode == 0){ $status = "<span style='color:#FF0000;'>Pending</span>"; }
			else{ $status = "<span style='color:#008000;'>Confirmed</span>"; }*/
						
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$user_id?></td>
				<td><?=$tot_amt?>&#36;</td>
				<td><?=$tds?>&#36;</td>
				<td><?=$adm_tax?>&#36;</td>
				<td><?=$amount?>&#36;</td>
				<td class="text-left"><?=$ac_info?> </td>
				<td><?=$date?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>