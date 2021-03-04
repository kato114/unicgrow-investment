<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/setting.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$search_id = get_new_user_id($search_username);
	
	if($_POST['search_username'] !=''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
}
?>
<div class="row">
	<div class="col-md-4 col-md-offset-8">
	<form method="post" action="index.php?page=add_fund_report">
	<table class="table table-bordered">
		<tr>
			<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
			<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
		</tr>
	</table>
	</form>	
	</div>
</div>
<?php
/*$sql = "SELECT  t1.*,t2.username FROM (SELECT user_id , account msg , cr amt , date , '.....' mobile_no FROM account WHERE type = '$acount_type[13]' AND date < (SELECT date FROM add_funds ORDER BY id ASC LIMIT 1)
UNION 
SELECT user_id , message msg , amount amt , date , mobile_no FROM add_funds ORDER BY date DESC) t1
LEFT JOIN users t2 ON t1.user_id = t2.id_user 
ORDER BY t1.date DESC";*/

$sql = "SELECT t1.*,t2.username,t2.phone_no FROM account t1
LEFT JOIN users t2 ON t1.user_id = t2.id_user 
WHERE t1.type = '$acount_type[13]' $qur_set_search
ORDER BY t1.date DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COALESCE(SUM(t1.cr),0) amt,COUNT(t1.id) num FROM ($sql) t1 ";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$tot_amt = $ro['amt'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr><th colspan="7">Total Add Fund : <?=$tot_amt; ?> &#36;</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Username</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Mobile No.</th>
			<th class="text-center">Wallet Type</th>
			<th class="text-center">Date</th>
			<th class="text-center">Remarks</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		$QUERY = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($r = mysqli_fetch_array($QUERY))
		{
			$message = $r['msg'];
			$username = $r['username'];
			$mobile_no = $r['phone_no'];
			$amount = $r['cr'];
			$wall_type = $r['wall_type'];
			$remarks = $r['remarks'];
			$date = date('d/m/Y', strtotime($r['date']));
			
			?>	
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$amount?> &#36;</td>
				<td><?=$mobile_no?></td>
				<td><?=$wall_type?></td>
				<td><?=$date?></td>
				<td><?=$remarks?></td>
			</tr> <?PHP
			$sr_no++;
		} ?>
	</table> <?PHP  
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }
	