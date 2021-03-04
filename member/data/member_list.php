<?php
include('../security_web_validation.php');
include("condition.php");
session_start();

$newp = $_GET['p'];
$plimit = 30;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_POST['user_id'])){
	unset($_SESSION['user_id'],$_SESSION['url_from']);
}
if(!isset($_SESSION['user_id'])){
	$_SESSION['user_id'] = $_POST['user_id'];
	$_SESSION['url_from'] = $_POST['url_from'];
}
$user_id = $_SESSION['user_id'];
$url_from = $_SESSION['url_from'];


$field = 'left_network';
$title = "Left";
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_mem']);
}
else{
	$_POST['search_mem'] = $_SESSION['SESS_search_mem'];
}

/*if(isset($_POST['search_mem'])){
	if($_POST['search_mem'] == 'Left Member'){
		unset($_SESSION['SESS_search_mem']);
		$_SESSION['SESS_search_mem'] = $_POST['search_mem'];
		$field = 'left_network';
	}
	if($_POST['search_mem'] == 'Right Member'){
		unset($_SESSION['SESS_search_mem']);
		$_SESSION['SESS_search_mem'] = $_POST['search_mem'];
		$field = 'right_network';
	}
}*/

if(isset($_POST['search_mem'])){
	if($_POST['search_mem'] != ''){
		$_SESSION['SESS_search_mem'] = $search_mem = $_POST['search_mem'];
		switch($search_mem){
			case 'Left Member' : $field = 'left_network'; $title = "Left";	break;
			case 'Right Member' : $field = 'right_network'; $title = "Right";	break;
		}
	}
}
?>
<div class="col-md-6">
	<a class="btn btn-danger" href="index.php?page=<?=$url_from?>"><i class="fa fa-reply"></i> Back</a>
</div>
<div class="col-md-6 text-right">
	<form action="index.php?page=member_list" method="post">
		<input type="submit" name="search_mem" value="Left Member" class="btn btn-primary btn-sm" />
		<input type="submit" name="search_mem" value="Right Member" class="btn btn-danger btn-sm" />
	</form>
</div>
<div class="col-md-12">&nbsp;</div>
<?php

$sqlk = "SELECT $field FROM network_users WHERE user_id ='$user_id'";
$result = rtrim(mysqli_fetch_array(query_execute_sqli($sqlk))[0],',');

$sql = "SELECT * FROM users WHERE id_user in ($result) $qur_set_search ORDER BY date DESC";

$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer)){
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


if($totalrows > 0){ ?>
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="12">Total <?=$title?> Members : <?=$tot_rec;?></th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<!--<th class="text-center">User Name</th>-->
			<th class="text-center">Join Date</th>
			<th class="text-center">Activation Date</th>
			<th class="text-center">Package Name</th>
		</tr> 
		</thead>
		<?php	
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $start+1;
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query)){
			$id_user = $row['id_user'];
			$date = $row['date'];
			$full_investment = $row['amount'];
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$phone_no = $row['phone_no'];
			$email = $row['email'];
			$topup_amt = $row['update_fees'];
			$reg_date = $row['reg_date'];
			$position = $row['position'];
			
			$date = date('d M Y' , strtotime($date));
			$reg_date = get_user_active_investment_with_date($id_user)[1];
			 
			$status = "<span class='label label-primary'>Active</span>";
			$act_date = date('d/m/Y', strtotime($reg_date));
			$my_plan = my_package($id_user);
			$plan_name = $my_plan[0];
			$plan_amt = $my_plan[1];
			
			
			if($reg_date == ''){ 
				$act_date = "N/A";
				$status = "<span class='label label-danger'>Inactive</span>";
				$plan_name = "N/A";
				$plan_amt = "0.00";
			}
			
			if($position == 0){ $pos = 'Left'; }
			else{ $pos = 'Right'; }
			?>
			 <tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$username;?></td>
				<!--<td><?=$name;?></td>-->
				<td><?=$date;?></td>
				<td><?=$act_date;?></td>
				<td><?=$plan_name?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>

