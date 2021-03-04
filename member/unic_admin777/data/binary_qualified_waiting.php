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

?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="search_date" placeholder="Search By Date" class="form-control" />
				</div>
			</div>
		</td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	

<?php
$qur_set_search = " AND t1.date = '$systems_date' ";
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_date'],$_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_date'] = $_SESSION['SESS_search_date'];
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
}
if(isset($_POST['Search']))
{
	if($_POST['search_date'] != '')
	$_SESSION['SESS_search_date'] = $search_date = date('Y-m-d', strtotime($_POST['search_date']));
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$search_id = get_new_user_id($search_username);
	
	if($_POST['search_username'] !=''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
	if($search_date != ''){
		$qur_set_search = " AND t1.date = '$search_date' ";
	}
}

$sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM (SELECT t1.* FROM pair_point t1) t1
INNER JOIN reg_fees_structure t3 ON t1.user_id = t3.user_id 
INNER JOIN users t2 ON t1.user_id = t2.id_user 
WHERE ((t2.r_lps = 0 AND t2.l_lps = 0) OR (t2.r_lps = 0 OR t2.l_lps = 0)) 
AND t3.user_id IS NOT NULL AND (t1.lapse_l > 0 OR t1.lapse_r > 0) $qur_set_search 
GROUP BY t3.user_id ORDER BY t1.date DESC";

/*echo $sql = "SELECT t1.*,t2.username,t2.f_name,t2.l_name FROM (SELECT t1.* FROM pair_point t1 ) t1
INNER JOIN reg_fees_structure t3 ON t1.user_id = t3.user_id 
INNER JOIN users t2 ON t1.user_id = t2.id_user 
WHERE ((t2.r_lps = 0 OR t2.l_lps = 0) OR (t2.r_lps = 1 OR t2.l_lps = 1))
AND t3.user_id IS NOT NULL AND ((t1.lapse_l > 0 OR t1.lapse_r > 0) OR (t1.lapse_l = 0 OR t1.lapse_r = 0)) 
$qur_set_search 
GROUP BY t3.user_id ORDER BY t1.date DESC";*/

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
			<th class="text-center">Left Pending</th>
			<th class="text-center">Right Pending</th>
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
			$l_point = $row['lapse_l'];
			$r_point = $row['lapse_r'];
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
