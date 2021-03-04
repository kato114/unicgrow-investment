<?php
include('../../security_web_validation.php');
?>
<div class="col-sm-12 text-right">
	<button type="button" id="close" onclick="window.close()" class='btn btn-danger btn-sm'>
		<i class="fa fa-reply"></i> Close Window
	</button>
</div>
<div class="col-sm-12">&nbsp;</div>
<?php


$newp = $_GET['p'];
$plimit = 25;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

$user_id = $_POST['user_id'];

$sql = "SELECT * FROM users WHERE id_user = '$user_id'";
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

if($totalrows > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">Contact</th>
			<th class="text-center">E-mail</th>
			<th class="text-center">Reg. Date</th>
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
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$date = $row['date'];
			$phone_no = $row['phone_no'];
			$email = $row['email'];

			$date1 = "0000-00-00";
			if($date > 0)
			$date1 = date('d/m/Y', strtotime($date));
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td><?=$phone_no?></td>
				<td><?=$email?></td>
				<td><?=$date1?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	//pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val); 
}
else{ echo "<B class='text-danger'>There are no information !!</B>";
}
?>


