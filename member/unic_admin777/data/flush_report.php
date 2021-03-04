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
<form method="post" action="index.php?page=binary_matching_status">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
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
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>	

<?php

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
}
if(isset($_POST['Search']))
{
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
	}
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	
	$search_id = get_new_user_id($search_username);
	
	if($st_date !='' and $en_date != ''){
		$qur_set_search = " AND date BETWEEN '$st_date' AND '$en_date' ";
	}
	if($search_username !=''){
		$qur_set_search = " AND user_id = '$search_id' ";
	}
}


$date =  date("Y-m-d",strtotime($systems_date." -1 DAY"));

$sql = "SELECT * FROM pair_point WHERE date <= '$date' AND flush_business > 0 $qur_set_search ORDER BY date DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(id) num FROM pair_point WHERE date <= '$date' AND flush_business > 0 $qur_set_search ORDER BY date DESC";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">Username</th>
			<th class="text-center">Left Point</th>
			<th class="text-center">Right Point</th>
			<th class="text-center">Total Business</th>
			<th class="text-center">Flush Business</th>
			<th class="text-center">Remain Business</th>
			<th class="text-center">Left Carry Forward</th>
			<th class="text-center">Right Carry Forward</th>
			<th class="text-center">Date</th>
		</tr>
		</thead>
		<?php		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");		
		while($r = mysqli_fetch_array($query))
		{
			$date = date('d/m/Y', strtotime($r['date']));
			$user_id = get_user_name($r['user_id']);
			$left_point = $r['left_point'];
			$right_point = $r['right_point'];
			$total_business = $r['total_business'];
			$flush_business = $r['flush_business'];
			$remain_business = $r['remain_business'];
			$cf_left = $r['cf_left'];
			$cf_right = $r['cf_right'];
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$user_id?></td>
				<td>&#36;<?=$left_point?></td>
				<td>&#36;<?=$right_point?></td>
				<td>&#36;<?=$total_business?></td>
				<td>&#36;<?=$flush_business?></td>
				<td>&#36;<?=$remain_business?></td>
				<td>&#36;<?=$cf_left?></td>
				<td>&#36;<?=$cf_right?></td>
				<td><?=$date?></td>
				
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else { echo "<B class='text-danger'>There are no information to show !!</B>"; }	
?>