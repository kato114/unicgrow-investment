<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

if(isset($_REQUEST['real_parent'])){
	$_SESSION['real_par'] = $real_parent = $_REQUEST['real_parent'];
}
else{
	$real_parent = $_SESSION['real_par'];
}


$sql = "SELECT * FROM users WHERE real_parent = '$real_parent'";	
$SQL = "$sql LIMIT $tstart,$tot_p ";

$sqlk = "SELECT COUNT(*) num FROM users WHERE real_parent = '$real_parent'";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</td>
			<th class="text-center">Name</td>
			<th class="text-center">Status</td>
		</tr>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{ 	
			$user_id = $row['id_user'];
			$username = $row['username'];
			$type = $row['type'];
			$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
			
			if($type == 'B') { $status = "<span class='label label-success'>Active</span>"; }
			elseif($type == 'C') {  $status = "<span class='label label-warning'>Blocked</span>"; }
			else { $status = "<span class='label label-danger'>Deactive</span>"; }
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>