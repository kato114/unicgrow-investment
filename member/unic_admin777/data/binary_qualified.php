<?php
include('../../security_web_validation.php');
session_start();
include("condition.php");
include("../function/functions.php");


$newp = $_GET['p'];
$plimit = 25;
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

/*$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM pair_point t1
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.id IN(SELECT MAX(id) FROM pair_point GROUP BY user_id)
AND t1.lapse_l = 0 AND t1.lapse_r = 0 AND t2.r_lps = 1 AND t2.l_lps = 1
$qur_set_search ORDER BY t1.date DESC";*/

?>
<div class="col-md-4 col-md-offset-8">
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	
</div>

<?php

$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM pair_point t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.lapse_l = 0 AND t1.lapse_r = 0 AND t2.r_lps = 1 AND t2.l_lps = 1 
$qur_set_search ORDER BY t1.date DESC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM ($sql) t1 ";
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
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Left Point</th>
			<th class="text-center">Right Point</th>
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
		while($row = mysqli_fetch_array($query))
		{
			$user_id = $row['user_id'];
			$username = $row['username'];
			$l_point = $row['left_point'];
			$r_point = $row['right_point'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$date = date('d/m/Y', strtotime($row['date']));
			
			?>
			<tr align="center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td><?=$l_point?></td>
				<td><?=$r_point?></td>
				<td><?=$date?></th>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to Show!!</b>";}

?>
