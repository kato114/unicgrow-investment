<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = "";
//$qur_kyc = "AND `url` LIKE '%kyc%'";
//$qur_profile = "AND `url` LIKE '%edit-profile%'";
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['st_date'] = $_SESSION['SESS_strt_date'];
	$_POST['en_date'] = $_SESSION['SESS_end_date'];
	$_POST['search_page'] = $_SESSION['SESS_search_page'];
	$_POST['search_status'] = $_SESSION['SESS_search_status'];
}
else{
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_strt_date'],$_SESSION['SESS_en_date'],$_SESSION['SESS_search_page'],$_SESSION['SESS_search_status']);
}
if(isset($_POST['Search'])){
	
	$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
	$_SESSION['SESS_search_status'] = $search_status = $_POST['search_status'];
	
	if($_POST['st_date'] != '' and $_POST['en_date'] != ''){
		$_SESSION['SESS_strt_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_end_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$qur_set_search = " AND DATE(t1.date_time) BETWEEN '$st_date' AND '$en_date' ";
	}
	
	
	$search_id = get_new_user_id($search_username);
	if($_POST['search_username'] !=''){
		$qur_set_search = " AND t2.username = '$search_username' ";
		if($search_id > 0){
			$qur_set_search = " AND t1.`post_data` LIKE '%user_id=$search_id%'";
		}
	}
	
	if($_POST['search_status'] !='' and $_POST['search_username'] !=''){
		$table_name = "users";
		switch($search_status){
			case 1 : 
				$qur_set_search = " AND (`post_data` LIKE '%cancel_kyc%' OR `post_data` LIKE '%cancel_allkyc%') 
				AND t2.username = '$search_username'";
				if($search_id > 0){
					$qur_set_search = " AND (t1.`post_data` LIKE '%cancel_kyc%' OR t1.`post_data` 
					LIKE '%cancel_allkyc%') AND t1.`post_data` LIKE '%user_id=$search_id%'";
				} 
				$selcted1 = 'selected="selected"';
			break;
			case 2 : 
				$qur_set_search = " AND (`post_data` LIKE '%approve_kyc%' OR `post_data` LIKE '%approve_allkyc%') 
				AND t2.username = '$search_username'";
				if($search_id > 0){
					$qur_set_search = " AND (t1.`post_data` LIKE '%approve_kyc%' OR t1.`post_data` 
					LIKE '%approve_allkyc%') AND t1.`post_data` LIKE '%user_id=$search_id%'";
				} 
				$selcted1 = 'selected="selected"';
			break;
		}
	}
	if($_POST['search_status'] !='' and $_POST['search_username'] ==''){
		switch($search_status){
			case 1 : 
				$qur_set_search = " AND (`post_data` LIKE '%cancel_kyc%' OR `post_data` LIKE '%cancel_allkyc%')"; 
				$selcted1 = 'selected="selected"';
			break;
			case 2 : 
				$qur_set_search = " AND (`post_data` LIKE '%approve_kyc%' OR `post_data` LIKE '%approve_allkyc%')";
				$selcted2 = 'selected="selected"'; 
			break;
		}
	}
}

?>
<table class="table table-bordered">
	<tr>
		<form method="post" action="index.php?page=<?=$val?>">
		<td>
			<select name="search_status" class="form-control">
				<option value="">Search Status</option>
				<option value="1" <?=$selcted1?>>Rejected KYC</option>
				<option value="2" <?=$selcted2?>>Approcev KYC</option>
			</select>
		</td>	
		<td>
			<input type="text" name="search_username" placeholder="Search By Username" value="<?=$_POST['search_username']?>" class="form-control" />
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="st_date" placeholder="Start Date" class="form-control" />
				</div>
			</div>
		</td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="en_date" placeholder="End Date" class="form-control" />
				</div>
			</div>
		</td>
		<th><input type="submit" value="Search" name="Search" class="btn btn-info"></th>
		</form>
	</tr>
</table>

<?php
$sql = "SELECT t1.*,t2.username,t2.email FROM panel_work_history t1 
LEFT JOIN admin t2 ON t1.member_id = t2.id_user
WHERE t1.panel_id = 2 AND t1.url LIKE '%all_kyc%' AND (t1.post_data LIKE '%cancel_allkyc%' OR t1.post_data LIKE '%approve_allkyc%' OR t1.post_data LIKE '%cancel_kyc%' OR t1.post_data LIKE '%approve_kyc%') $qur_set_search";

$SQL = "$sql LIMIT $tstart,$tot_p ";

$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
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
		<thead>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</td>
			<th class="text-center">Admin ID</td>
			<th class="text-center">Date</td>
			<th class="text-center">Status</td>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que)){ 	
			$admin_id = $row['username'];
			$email = $row['email'];
			$date = $row['date_time'];
		 	$url = explode('page=',$row['url']);
			$post_data = $row['post_data'];
			
			parse_str($post_data, $output);
			$user_id = get_user_name($output['user_id']);
			
			
			if(strpos($post_data, "approve_kyc") !== false or strpos($post_data, "approve_allkyc") !== false) {
				$remarks = "<B class='text-success'>KYC Approved</B>";
			}
			elseif(strpos($post_data, "cancel_kyc") !== false or strpos($post_data, "cancel_allkyc") !== false) {
				$remarks = "<B class='text-danger'>KYC Rejected</B>";
			}
			
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$user_id?></td>
				<td><?=$admin_id;?></td>
				<td><?=$date?></td>
				<td><?=$remarks?></td>
			</tr> <?php
			$sr_no++;
		}  ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
?>