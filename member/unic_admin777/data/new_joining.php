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
		$qur_set_search = " AND id_user = '$search_id' ";
	}
}
?>
<div class="col-md-4 col-md-offset-8">
<form method="post" action="index.php?page=new_joining">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	
</div>

<?php
$sql = "SELECT * FROM users WHERE date = '$systems_date' $qur_set_search";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(id_user) num FROM users WHERE date = '$systems_date' $qur_set_search";
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
			<th class="text-center">User Name</th>
			<th class="text-center">Name</th>
			<th class="text-center">Date</th>
			<th class="text-center">Status</th>
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
			$id = $row['id_user'];
			$username = get_user_name($id);
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$type = $row['type'];
			$date = date('d/m/Y', strtotime($row['date']));
			
			if(get_paid_member($id) > 0) { $status = "<span class='label label-success'>Active User</span>"; }
			else { $status = "<span class='label label-danger'>Registered User</span>"; }
			
			?>
			<tr align="center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td><?=$date?></td>
				<td><?=$status?></th>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are No Joining to Show!!</b>";}

?>
