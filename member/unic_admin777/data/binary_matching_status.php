<?php
include('../../security_web_validation.php');

session_start();
include("../function/functions.php");
include("../function/setting.php");


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
		$qur_set_search = " WHERE date BETWEEN '$st_date' AND '$en_date' ";
	}
	if($search_username !=''){
		$qur_set_search = " WHERE user_id = '$search_id' ";
	}
}


$sql = "SELECT * FROM pair_point $qur_set_search ORDER BY date DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(id) num FROM pair_point $qur_set_search ORDER BY date DESC";
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
			<th class="text-center" rowspan="2">Sr no.</th>
			<th class="text-center" rowspan="2">User ID</th>
			<th class="text-center" rowspan="2">Date</th>
			<th class="text-center" colspan="2">Investment</th>
			<th class="text-center" colspan="2">Carry Forward</th>
			<th class="text-center">Pair Point</th>
		</tr>
		<tr>	
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
			
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
			<th class="text-center">Pair</th>
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
			$left_point1 = $left_point = $row['left_point'];
			$right_point1 = $right_point = $row['right_point'];
			$date = date('d/m/Y', strtotime($row['date']));
			$user_name = get_user_name($user_id);
			
			$max_pair = (int)(min($left_point,$right_point)/$per_day_multiple_pair)*$per_day_multiple_pair;  
			// Original Pair Point
			
			
			// Pair Point after deduction 10%
			$max_pair_per = $max_pair*10/100;
			$active_investmnt = get_user_active_investment($user_id);
			
			
			if($active_investmnt < $max_pair_per){ $pair_point = $active_investmnt; }
			else{ $pair_point = $max_pair_per; }
			// Pair Point after deduction 10%
			
			
			$left_carry = $right_carry= 0;
			
			$right_pair = (int)($right_point/$per_day_multiple_pair);
			$left_pair = (int)($left_point/$per_day_multiple_pair);
			if($right_point == 0){ $left_carry = $left_point; }
			elseif($right_point < $left_point){ 
				$left_carry = $left_point-($per_day_multiple_pair*$right_pair);
				$right_carry = $right_point-($per_day_multiple_pair*$right_pair);
			}
			
			if($left_point == 0){ $right_carry = $right_point; }
			elseif($left_point < $right_point){ 
				$right_carry = $right_point-($per_day_multiple_pair*$left_pair);
				$left_carry = $left_point-($per_day_multiple_pair*$left_pair);
			}
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$user_name;?></td>
				<td><?=$date;?></td>
				<td><?=$left_point1;?></td>
				<td><?=$right_point1;?></td>
				<td><?=$left_carry;?></td>
				<td><?=$right_carry;?></td>
				<td><?=$max_pair;?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else { echo "<B class='text-danger'>You have no child !</B>"; }	
?>

