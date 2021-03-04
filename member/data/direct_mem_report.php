<?php
include('../security_web_validation.php');

include("condition.php");

$login_id = $_SESSION['mlmproject_user_id'];

if($newp == '')
{
	$title = 'Display';
	$message = 'Display Direct Members';
	data_logs($login_id,$title,$message,0);
}

$newp = $_GET['p'];
$plimit = 100;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


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
	
	//$result = mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent ($login_id)"))[0];
	
	/*if($_POST['search_pos'] != ''){
		//$sqls = "SELECT id_user FROM users WHERE parent_id = '$login_id' AND position = $pos";
		$sqls = "SELECT id_user FROM users WHERE real_parent = '$login_id' AND position = $pos";
		/*$quer = query_execute_sqli($sqls);	
		$ro = mysqli_fetch_array($quer);
		$id_total = $ro[0];
		if($id_total > 0)
		$result = mysqli_fetch_array(query_execute_sqli("SELECT get_chield_by_parent ($id_total)"))[0].",".$id_total;
		$query = query_execute_sqli($sqls);
		$id_total = array();
		while($ro = mysqli_fetch_array($query))
		{
			$id_total[] = $ro['id_user'];
		}
		if(!empty($id_total)){
			$result = implode(",",$id_total);
		}
	}*/
	
	if($_POST['search_pos'] != ''){
		if($status_check == 1){
			$qur_set_search = " AND position = '$pos' ";
		}
		else{
			$qur_set_search = " AND position = '$pos' ";
		}
	}
	
	if($_POST['search_username'] != ''){
		$qur_set_search = " AND id_user = '$search_id' ";
	}
	if($_POST['search_date'] != ''){
		$qur_set_search = " AND date = '$search_date' ";
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
			$qur_set_search = " AND package > 0 ";
		}
		else{
			$qur_set_search = " AND package = 0 ";
		}
	}
	if($_POST['status_check'] != '' and $_POST['search_pos'] != ''){
		if($status_check == 1){
			$qur_set_search = " AND package > 0 AND position = '$pos' ";
		}
		else{
			$qur_set_search = " AND package = 0 AND position = '$pos' ";
		}
	}
}
else{
	$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id IN ($login_id)";
	$result = rtrim(mysqli_fetch_array(query_execute_sqli($sql))[0],',');
	$result = trim($result,",");
}
?>


<?php


/*$sql = "SELECT t1.*,t3.date reg_date FROM users t1
LEFT JOIN kyc t2 ON t2.user_id = t1.id_user
LEFT JOIN reg_fees_structure t3 ON t3.user_id = t1.id_user
WHERE t1.real_parent = '$login_id' AND t1.id_user IN ($result) AND t3.boost_id = 0 AND t3.mode = 1 $qur_set_search 
GROUP BY t3.user_id ORDER BY t1.date DESC";*/

$sql = "SELECT * FROM users WHERE real_parent = '$login_id' $qur_set_search ORDER BY id_user ASC";
$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);


/*echo $sqlk = "SELECT COUNT(t1.id_user) num FROM users t1
LEFT JOIN kyc t2 ON t2.user_id = t1.id_user
LEFT JOIN reg_fees_structure t3 ON t3.user_id = t1.id_user
WHERE t1.real_parent = '$login_id' AND t1.id_user IN ($result) $qur_set_search";*/
$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
$query = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($query))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0){ ?>
    <form method="post" action="index.php?page=direct_mem_report">
	<div class="col-md-3">
		<input type="text" name="search_username" placeholder="Search By User ID" class="form-control" />
	</div>

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
	<div class="col-md-1">
		<input type="submit" value="Submit" name="Search" class="btn btn-primary">
	</div>
</form>

<div class="col-md-12">&nbsp;</div>

	<table class="table table-bordered table-hover table-responsive">
		<thead><tr><th colspan="12">Total Members : <?=$tot_rec;?></th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name</th>
			<th class="text-center">Mobile</th>
			<th class="text-center">E-mail</th>
			<th class="text-center">Topup</th>
			<th class="text-center">Package</th>
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
			$username = $row['username'];
			$name= ucwords($row['f_name']." ".$row['l_name']);
			$position = $row['position'];
			$date = date('d/m/Y', strtotime($row['date']));
			
			$email = $row['email'];
			$phone_no = $row['phone_no'];
			$id_user = $row['id_user'];
			//$reg_date = $row['reg_date'];
			$state = $row['state'];
			$city = $row['city'];
			
			$my_plan = my_package($id_user);
			$plan_name = $my_plan[0];
			$plan_amt = $my_plan[1];
			
			$reg_date = get_user_active_investment_with_date($id_user)[1];
			$status = "<span class='badge badge-success'>Active</span>";
			$act_date = date('d/m/Y', strtotime($reg_date));
			
			if($reg_date == ''){ 
				$act_date = "N/A";
				$status = "<span class='badge badge-danger'>Inactive</span>";
				$plan_name = "N/A";
				$plan_amt = "0.00";
			}
			$country = $row['country'];
			$fb_id = $row['fb_id'];
			$whatsapp = $row['whatsapp'];
			$skype_id = $row['skype_id'];
			
			if($position == 0){ $pos = 'Left'; }
			else{ $pos = 'Right'; }
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td><?=$phone_no?></td>
				<td><?=$email?></td>
				<!--<td><a href="<?=$fb_id?>" target="_blank"><?=$fb_id?></a></td>
				<td><?=$whatsapp?></td>
				<td><?=$skype_id?></td>-->
				<td><?=$plan_amt?>&#36;</td>
				<td><?=$plan_name?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php  
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else{ echo "<B class='text-danger'>There are no information to show !!</B>"; }
?>


