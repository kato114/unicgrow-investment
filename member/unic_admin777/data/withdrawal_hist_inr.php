<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
include("../function/setting.php");
$newp = $_GET['p'];
$plimit = "25";

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['search'] = '1';
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['search']))
{
	$_SESSION['SESS_strt_date'] = $st_date = $_POST['st_date'];
	$_SESSION['SESS_end_date'] = $en_date = $_POST['en_date'];
	
	if($st_date !='' and $en_date != '')
	{
		$qur_set_search = " AND date(T1.date) >= '$st_date' AND date(T1.date) <= '$en_date' ";
	}
}

$SQL = "SELECT T1.*,T2.ac_no FROM withdrawal_crown_wallet T1 
LEFT JOIN users T2 ON T1.user_id = T2.id_user
WHERE T1.ac_type = 5 $qur_set_search";
$q = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($q);

if($totalrows > 0)
{ ?>	
	<form method="post" action="index.php?page=withdrawal_hist_inr">	
	<table align="right" hspace=0 cellspacing=0 cellpadding=0 border=0 width=80%>
		<tr>
			<td>
				<input type="text" name="st_date" placeholder="Enter Start Date" class="input-medium flexy_datepicker_input">
			</td>
			<td>
				<input type="text" name="en_date" placeholder="Enter End Date" class="input-medium flexy_datepicker_input">
			</td>
			<td><input name="search" value="Search" class="btn btn-info" type="submit" /></td>
		</tr>
	</table>
	</form><br /><br />
	
	<table align="center" hspace=0 cellspacing=0 cellpadding=0 border=0 width=95%>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">USD Amount</th>
			<th class="text-center">Date Time</td>
			<th class="text-center">Status</td>
		</tr>
		<?php		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		$query = query_execute_sqli("$SQL LIMIT $start,$plimit ");		
		while($row = mysqli_fetch_array($query))
		{
			$user_id = get_user_name($row['user_id']);
			$amount = $row['request_crowd'];
			$amount1 = $row['amount'];
			$hash_code = $row['transaction_hash'];
			$btc_address = $row['ac_no'];
			$mode = $row['status'];
			$date = date('d/m/Y H:i:s', strtotime($row['date']));
			$amt_inr = $amount1*$currency_exch_rate['USD'];
			
			switch($mode)
			{
				case 0 : $status = "<B class='text-success'>Proceed</B>";	break;
				case 1 : $status = "<B class='text-primary'>Processing</B>";	break;
				case 2 : $status = "<B class='text-info'>Confirm</B>";	break;
				case 3 : $status = "<B class='text-danger'>Cancel</B>";	break;
				case 65 : $status = "<B class='text-warning'>Unconfirmed</B>";	break;
			}
			/*if($mode == 0){ $status = "<B style='color:#FF0000;'>Pending</B>"; }
			else{ $status = "<B style='color:#008000;'>Confirmed</B>"; }*/
						
			?>
			<tr align="center">
				<td class="input-small"><small><?=$sr_no?></small></td>
				<td class="input-small"><small><?=$user_id?></small></td>
				<td><small><?=$amount1?> &#36; &nbsp;&nbsp;(<?=$amt_inr?> &#36;)</small></td>
				<td><small><?=$date?></small></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		}
		pagging_admin_panel($newp,$pnums,8,$val); ?>
	</table> <?php
}
else 
{  echo "<B style='color:#FF0000; font-size:16px;'>There are no information to show !!</B>"; }
?>