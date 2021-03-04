<?php
include('../security_web_validation.php');
session_start();
include("condition.php");
include("function/setting.php");

$user_id = $_SESSION['mlmproject_user_id'];


$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_POS'],$_SESSION['SESS_USERNAME'],$_SESSION['SESS_kyc_check']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_pos'] = $_SESSION['SESS_POS'];
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
	$_POST['kyc_check'] = $_SESSION['SESS_kyc_check'];
}

if(isset($_POST['Search'])){
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$_SESSION['SESS_POS'] = $pos = $_POST['search_pos'];
	$_SESSION['SESS_kyc_check'] = $kyc_check = $_POST['kyc_check'];
	
	$search_id = get_new_user_id($search_username);
	$result=mysqli_fetch_array(query_execute_sqli("SELECT left_network FROM network_users WHERE user_id IN($user_id)"))[0];
	
	if($_POST['search_pos'] != ''){
		$sqls = "SELECT id_user FROM users WHERE parent_id = '$user_id' AND position = $pos";
		$quer = query_execute_sqli($sqls);	
		$ro = mysqli_fetch_array($quer);
		$id_total = $ro[0];

		$sql = "SELECT left_network FROM network_users WHERE user_id IN($id)";
		$result = mysqli_fetch_array(query_execute_sqli($sql))[0].",".$id_total;
	}
	
	if($search_username != ''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
	if($kyc_check != ''){
		if($kyc_check == 1){
			$qur_set_search = " AND t3.user_id IS NOT NULL ";
		}
		else{
			$qur_set_search = " AND t3.user_id IS NULL ";
		}
	}
}
else{
	$result=mysqli_fetch_array(query_execute_sqli("SELECT left_network FROM network_users WHERE user_id IN($user_id)"))[0];
}
?>


<form method="post" action="index.php?page=network_business">
	<div class="col-lg-4">
		<input type="text" name="search_username" placeholder="Search By Username" class="form-control" />
	</div>
	<div class="col-lg-3">
		<select name="kyc_check" class="form-control">
			<option value="">Select KYC</option>
			<option value="1" <?php if($_POST['kyc_check'] == 1){?> selected="selected" <?php } ?>>
				KYC
			</option>
			<option value="2" <?php if($_POST['kyc_check'] == 2){?> selected="selected" <?php } ?>>
				Non KYC
			</option>
		</select>
	</div>
	<div class="col-lg-3">
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
	<div class="col-lg-1">
		<input type="submit" value="Submit" name="Search" class="btn btn-primary">
	</div>
</form>

<div class="col-lg-12">&nbsp;</div>
<?php

$sql = "SELECT t1.*,t2.position,t2.username FROM reg_fees_structure t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
LEFT JOIN kyc t3 ON t3.user_id = t1.user_id
WHERE t1.user_id IN ($result) AND t1.update_fees > 0 $qur_set_search group by t1.user_id";

$SQL = "$sql LIMIT $tstart,$tot_p ";
$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT SUM(t1.update_fees) amt,COUNT(t1.id) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer))
{
	$tot_rec = $ro['num'];
	$amt = $ro['amt'];
	$lpnums = ceil ($tot_rec/$plimit);
}


if($totalrows != 0)
{
	?>
	<table class="table table-bordered table-hover">
		<thead>
			<tr><th colspan="4">Total Network Business : &#36; <?=round($amt,2);?></th></tr>
		</thead>
		<tr>
			<th class="text-center">Sr. no.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Topup Amount</th>
			<th class="text-center">Date</th>
			<!--<th class="text-center">Position</th>-->  
		</tr>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{
			$date = date('d/m/Y' , strtotime($row['date']));
			$amount = round($row['update_fees'],5);
			$user_id = $row['username'];
			$position = $row['position'];
			
			if($position == 0) { $pos = 'Left'; }
			else { $pos = 'Right'; }
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$user_id?></td>
				<td>&#36; <?=$amount?></td>
				<td><?=$date?></td>
				<!--<td><?=$pos?></td>-->
			</tr> <?php
			$sr_no++;
		}
		?>
	</table> <?php 
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}		
else{ echo "<B class='text-danger'>There are no information to show !!</B>";  }
?>
