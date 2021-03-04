<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;
?>

<form method="post" action="index.php?page=ledger">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="date" placeholder="Search By Date" class="form-control" />
				</div>
			</div>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	
<?php

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_search_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['date'] = $_SESSION['SESS_search_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['date'] != '')
	$_SESSION['SESS_search_date'] = $date = date('Y-m-d', strtotime($_POST['date']));
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];	
	
	$search_id = get_new_user_id($search_username);
	
	if($date !=''){
		$qur_set_search = " WHERE t1.date LIKE '$date%' ";
	}
	if($search_username !=''){
		$qur_set_search = " WHERE t1.user_id = '$search_id' ";
	}
}

$sql = "SELECT t1.*, t2.username FROM account t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
$qur_set_search ORDER BY t1.id DESC";	
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM account t1 $qur_set_search";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="5">
				<div class="pull-left">
					Total Credit : <span class="label label-danger"><?=total_cr_dr('cr')?> &#36;</span>
				</div>
				<div class="pull-right">
					Total Debit : <span class="label label-danger"><?=total_cr_dr('dr');?> &#36;</span>
				</div>
			</th>
		</tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Username</th>
			<th class="text-center">Credit</th>
			<th class="text-center">Debit</th>
			<th class="text-center">Date</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{ 	
			$cr = $row['cr'];
			$dr = $row['dr'];
			$date = $row['date'];
			$username = $row['username'];
			?>
			<tr align="center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$cr?> &#36;</td>
				<td><?=$dr?> &#36;</td>
				<td><?=$date?></td>
			</tr> <?php 
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }	


function total_cr_dr($field)
{
	$sql = "SELECT SUM($field) FROM account";
	$query = query_execute_sqli($sql);
	$row = mysqli_fetch_array($query);
	$amt = $row[0];
	return $amt;
}

?>