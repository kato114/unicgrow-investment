<?php
include('../security_web_validation.php');
include("condition.php");
session_start();
$login_id = $user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = 100;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$sqlk = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id =($user_id)";
$result = rtrim(mysqli_fetch_array(query_execute_sqli($sqlk))[0],',');

$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_POS'],$_SESSION['SESS_USERNAME'],$_SESSION['SESS_kyc_check'],$_SESSION['SESS_status_check'],$_SESSION['SESS_search_date']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_pos'] = $_SESSION['SESS_POS'];
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
	$_POST['kyc_check'] = $_SESSION['SESS_kyc_check'];
	$_POST['status_check'] = $_SESSION['SESS_status_check'];
	$_POST['search_date'] = $_SESSION['SESS_search_date'];
}

if(isset($_POST['Search'])){
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$_SESSION['SESS_POS'] = $pos = $_POST['search_pos'];
	$_SESSION['SESS_kyc_check'] = $kyc_check = $_POST['kyc_check'];
	$_SESSION['SESS_status_check'] = $status_check = $_POST['status_check'];
	$_SESSION['SESS_search_date'] = $search_date = $_POST['search_date'];
	
	$search_id = get_new_user_id($search_username);
	
	if($_POST['search_pos'] != ''){
		$sqls = "SELECT id_user FROM users WHERE parent_id = '$login_id' AND position = $pos";
		$quer = query_execute_sqli($sqls);	
		$ro = mysqli_fetch_array($quer);
		$id_total = $ro[0];
		if($id_total > 0){
			$sqlk = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id =($id_total)";
			$result = rtrim("$id_total,".mysqli_fetch_array(query_execute_sqli($sqlk))[0],',');
		}
	}
	
	if($_POST['search_username'] != ''){
		$qur_set_search = " AND t1.id_user = '$search_id' ";
	}
	if($_POST['search_date'] != ''){
		$qur_set_search = " AND t1.date = '$search_date' ";
	}
	if($_POST['kyc_check'] != ''){
		if($kyc_check == 1){
			$qur_set_search = " AND t2.user_id IS NOT NULL ";
		}
		else{
			$qur_set_search = " AND t2.user_id IS NULL ";
		}
	}
	if($_POST['status_check'] != ''){
		if($status_check == 1){
			$qur_set_search = " AND t2.id IS NOT NULL AND t2.mode = 1 ";
		}
		else{
			$qur_set_search = " AND t2.id IS NULL ";
		}
	}
}

?>



<form method="post" action="index.php?page=network_members">
	<div class="col-md-3">
		<input type="text" name="search_username" placeholder="Search By User ID" class="form-control" />
	</div>
	<!--<div class="col-md-2">
		<div class="form-group" id="data_1">
			<div class="input-group date">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" name="search_date" placeholder="Search By Date" class="form-control" />
			</div>
		</div>
	</div>-->
	<div class="col-md-3">
		<select name="status_check" class="form-control">
			<option value="">Select Status</option>
			<option value="1" <?php if($_POST['status_check'] == 1){?> selected="selected" <?php } ?>>
				Activate
			</option>
			<option value="2" <?php if($_POST['status_check'] == 2){?> selected="selected" <?php } ?>>
				Deactivate
			</option>
		</select>
	</div>
	<!--<div class="col-md-2">
		<select name="kyc_check" class="form-control">
			<option value="">Select KYC</option>
			<option value="1" <?php if($_POST['kyc_check'] == 1){?> selected="selected" <?php } ?>>
				KYC
			</option>
			<option value="2" <?php if($_POST['kyc_check'] == 2){?> selected="selected" <?php } ?>>
				Non KYC
			</option>
		</select>
	</div>-->
	<div class="col-md-3">
		<select name="search_pos" class="form-control">
			<option value="">Select Position</option>
			<option value="0" <?php if($_POST['search_pos'] != ''){?> selected="selected" <?php } ?>>
				Left
			</option>
			<option value="1" <?php if($_POST['search_pos'] == 1){?> selected="selected" <?php } ?>>
				Right
			</option>
		</select>
	</div>
	<div class="col-md-3">
		<input type="submit" value="Submit" name="Search" class="btn btn-primary">
	</div>
</form>
<div class="col-md-12">&nbsp;</div>
<?php
//count(explode(",",$result));

$sql = "SELECT t1.*,t2.date reg_date FROM users t1 
LEFT JOIN reg_fees_structure t2 ON t2.user_id = t1.id_user
WHERE t1.id_user in ($result) $qur_set_search 
GROUP BY t1.id_user,t2.user_id ORDER BY t1.id_user ASC";

$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}


if($totalrows > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="12">Total Members : <?=$tot_rec;?></th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">Join Date</th>
			<th class="text-center">Activation Date</th>
			<th class="text-center">Package Name</th>
			<th class="text-center">MRP</th>
			<th class="text-center">State</th>
			<th class="text-center">City</th>
			<th class="text-center">Side</th>
			<th class="text-center">Status</th>
		</tr> 
		</thead>
		<?php	
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $start+1;
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{
			$id_user = $row['id_user'];
			$date = $row['date'];
			$full_investment = $row['amount'];
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$phone_no = $row['phone_no'];
			$email = $row['email'];
			$topup_amt = $row['update_fees'];
			$reg_date = $row['reg_date'];
			$state = $row['state'];
			$city = $row['city'];
			$position = $row['position'];
			
			$date = date('d M Y' , strtotime($date));
			 
			$status = "<span class='label label-primary'>Active</span>";
			$act_date = date('d/m/Y', strtotime($row['reg_date']));
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
				<td><?=$name;?></td>
				<td><?=$date;?></td>
				<td><?=$act_date;?></td>
				<td><?=$plan_name?></td>
				<td><?=$plan_amt;?></td>
				<td><?=$state?></td>
				<td><?=$city;?></td>
				<td><?=$pos;?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }

?>

