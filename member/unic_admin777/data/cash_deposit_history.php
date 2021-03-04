<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 100;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_search_utrno']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['search_utrno'] = $_SESSION['SESS_search_utrno'];
}
if(isset($_POST['Search']))
{
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	$_SESSION['SESS_search_utrno'] = $search_utrno = $_POST['search_utrno'];
	
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		
		$qur_set_search = " WHERE DATE(t1.date) BETWEEN '$st_date' AND '$en_date' ";
	}
	

	$search_id = get_new_user_id($search_username);
	
	if($search_username !=''){
		$qur_set_search = " WHERE t1.user_id = '$search_id' ";
	}
	
	if(!empty($search_utrno)){
		$qur_set_search = " WHERE t1.utr_no = '$search_utrno' ";
	}
}
?>

<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td>
			<input type="text" name="search_utrno" placeholder="Search By UTR No." class="form-control" />
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
		<td>
			<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
		</td>
		<th><input type="submit" value="Search" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>

<?php
$sql = "SELECT t1.*,t2.username FROM cash_deposit t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user $qur_set_search";	

$SQL = "$sql LIMIT $tstart,$tot_p ";

$sqlk = "SELECT SUM(amount) amt , COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$amount = $ro['amt'];
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead><tr><th colspan="10">Total Cash Deposit : <?=$amount; ?> &#36;</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Date & Time</td>
			<th class="text-center">User ID</td>
			<th class="text-center">Name</td>
			<th class="text-center">Mobile No.</td>
			<th class="text-center">Amount</td>
			<th class="text-center">UTR No.</td>
			<th class="text-center">Received By</td>
			<th class="text-center">Status</td>
			<th class="text-center">Approved By</td>
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
			$user_id = $row['user_id'];
			$username = $row['username'];
			$name = $row['name'];
			$date = $row['date'];
			$mobile = $row['mobile'];
			$amount = $row['amount'];
			$utr_no = $row['utr_no'];
			$rec_by = $row['received_by'];
			$mode = $row['mode'];
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$date?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$mobile?></td>
				<td>&#36; <?=$amount?></td>
				<td><?=$utr_no?></td>
				<td><?=$rec_by?></td>
				<td>-------</td>
				<td>-------</td>
			</tr> <?php
			$sr_no++;
		}  ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>