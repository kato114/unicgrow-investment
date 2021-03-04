<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_POS'],$_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
	$_POST['search_pos'] = $_SESSION['SESS_POS'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_POS'] = $search_pos = $_POST['search_pos'];
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$search_id = get_new_user_id($search_username);
	if($search_pos !=''){
		$qur_set_search = " AND t2.position = '$search_pos' ";
	}
	if($_POST['search_username'] !=''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<select name="search_pos" class="form-control">
				<option value="">Select Position</option>
				<option value="0" <?php if($_POST['search_pos'] != ''){?> selected="selected" <?php } ?>>
					Left
				</option>
				<option value="1" <?php if($_POST['search_pos'] == 1){?> selected="selected" <?php } ?>>
					Right
				</option>
			</select>
		</td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	

<?php
//$SQL = "SELECT * FROM reg_fees_structure WHERE level = 0 ORDER BY date DESC";
$sql = "SELECT t1.*,t2.position,t2.username FROM reg_fees_structure t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.level = 0 $qur_set_search ORDER BY date DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);



$sqlk = "SELECT COALESCE(SUM(t1.update_fees),0) amt,COUNT(t1.id) num FROM ($sql) t1 ";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$tot_invest = $ro['amt'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr><th colspan="11">Total Investment : <?=$tot_invest; ?> &#36;</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Username</th>
			<th class="text-center">Date</th>
			<th class="text-center" width="30%">E-mail/Phone No.</th>
			<th class="text-center">Investment</th>
			<th class="text-center">Profit (%)</th>
			<th class="text-center">Total month</th>
			<th class="text-center">Top-Up By</th>
			<th class="text-center">Position</th>
			<th class="text-center">Remarks</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$user_query_lmt = query_execute_sqli("$sql LIMIT $start,$plimit");
		$total_investment = 0;
		while($r = mysqli_fetch_array($user_query_lmt))
		{
			$reg_fees_id = $r['id'];
			$date = $r['date'];
			$user_ids = $r['user_id'];
			$rcw_id = $r['rcw_id'];
			$remarks = $r['remarks'];
			$position = $r['position'];
			$profit = $r['profit'];
			$total_days = $r['total_days'];
			$reg_fees = $r['reg_fees'];
			$update_fees = $r['update_fees'];
			
			$qu1 = query_execute_sqli("select * from users where id_user = '$user_ids' ");
			while($rrr = mysqli_fetch_array($qu1))
			{
				$usernames = $rrr['username'];
				$email = $rrr['email'];
				$phone_no = $rrr['phone_no'];					
			}
			
			if($update_fees == 0)
				$amount = $reg_fees;
			else
				$amount = $update_fees;
			if($rcw_id == 0)
				$topupby = "Admin";
			elseif($rcw_id == 1){
				$sql = "select user_id from ledger where by_id='$reg_fees_id' and particular like 
				'Debit Fund For TOPUP FROM%' ";
				$lqu = query_execute_sqli($sql);
				$lnum = mysqli_num_rows($lqu);
				if($lnum > 0){
					while($lrow = mysqli_fetch_array($lqu)){
						$topupby = get_user_name($lrow['user_id']);
					}
				}
				else $topupby = "Self";
			}
			
			if($position == 0) { $pos = 'Left'; }
			else { $pos = 'Right'; }
			 ?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$usernames?></td>
				<td><?=$date?></td>
				<td class="text-left">
					<i class="fa fa-envelope"> E-mail</i> - <?=$email?><br />
					<i class="fa fa-phone"> Phone</i> - <?=$phone_no?>
				</td>
				<td><?=$amount?> &#36;</td>
				<td><?=$profit?></td>
				<td><?=$total_days?></td>
				<td><?=$topupby?></td>
				<td><?=$pos?></td>
				<td><?=$remarks?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val); 
}
else{ echo "<B class='text-danger'>No Investment Found !</B>";  }	

?>
