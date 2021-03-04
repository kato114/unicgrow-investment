<?php
include('../../security_web_validation.php');

session_start();
include("../function/functions.php");
include("../function/setting.php");


$newp = $_GET['p'];
$plimit = 100;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

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
		$qur_set_search = " WHERE t1.date BETWEEN '$st_date' AND '$en_date' ";
	}
	if($search_username !=''){
		$qur_set_search = " WHERE t1.user_id = '$search_id' ";
	}


	$sql = "SELECT t1.*,SUM(t1.cf_left) tot_left,SUM(t1.cf_right) tot_right,t2.username,t2.f_name, 
	t2.l_name,t2.phone_no,t2.state,t2.city 
	FROM pair_point t1 
	LEFT JOIN users t2 ON t1.user_id = t2.id_user $qur_set_search GROUP BY t1.user_id 
	HAVING (tot_left + tot_right) > 1000000 ORDER BY t1.date DESC";
	//WHERE (SUM(t1.cf_left) + SUM(t1.cf_right)) > 1000000
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
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
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">Name</th>
				<th class="text-center">Mobile No.</th>
				<th class="text-center">State</th>
				<th class="text-center">City</th>
				<th class="text-center">Left Business</th>
				<th class="text-center">Right Business</th>
				<th class="text-center">Matching</th>
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
				$username = $row['username'];
				$name = ucwords($row['f_name']." ".$row['l_name']);
				$phone_no = $row['phone_no'];
				$state = $row['state'];
				$city = $row['city'];
				$tot_left = $row['tot_left'];
				$tot_right = $row['tot_right'];
				$username = $row['username'];
	
				$date = date('d/m/Y', strtotime($row['date']));
	
				
				$max_pair = (int)(min($tot_left,$tot_right)/$per_day_multiple_pair)*$per_day_multiple_pair;  
				//Original Pair Point
				
				?>
				<tr class="text-center">
					<td><?=$sr_no;?></td>
					<td><?=$username;?></td>
					<td><?=$name;?></td>
					<td><?=$phone_no;?></td>
					<td><?=$state;?></td>
					<td><?=$city;?></td>
					<td><?=$tot_left;?></td>
					<td><?=$tot_right;?></td>
					<td><?=$max_pair;?></td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else { echo "<B class='text-danger'>There are no information to show !</B>"; }	
}
else{
?>
	<form method="post" action="index.php?page=<?=$val?>">
	<table class="table table-bordered">
		<tr>
			<!--<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>-->
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
	</form>	<?php
} ?>

