<?php
include('../../security_web_validation.php');

include("../function/functions.php");

$newp = $_GET['p'];
$plimit = 50;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;



$qur_set_search = $qur_status_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_POS'],$_SESSION['SESS_search_username'],$_SESSION['SESS_status_check'],$_SESSION['SESS_search_pack']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_pos'] = $_SESSION['SESS_POS'];
	$_POST['search_userid'] = $_SESSION['SESS_search_username'];
	$_POST['status_check'] = $_SESSION['SESS_status_check'];
	$_POST['search_pack'] = $_SESSION['SESS_search_pack'];
}
?>
<form method="post" action="index.php?page=<?=$val?>">
<table class="table table-bordered">
	<tr>
		<td>
			<input type="text" name="search_userid" placeholder="Enter User ID" class="form-control" value="<?=$_POST['search_userid']?>" required />
		</td>
		<td>
			<select name="status_check" class="form-control" required>
				<option value="">Select Status</option>
				<option value="1" <?php if($_POST['status_check'] == 1){?> selected="selected" <?php } ?>>
					Activate
				</option>
				<option value="2" <?php if($_POST['status_check'] == 2){?> selected="selected" <?php } ?>>
					Inactive
				</option>
				<option value="3" <?php if($_POST['status_check'] == 3){?> selected="selected" <?php } ?>>
					Blocked
				</option>
			</select>
		</td>
		<td>
			<select name="search_pos" class="form-control" required>
				<option value="">Select Position</option>
				<option value="0" <?php if($_POST['search_pos'] != ''){?> selected="selected" <?php } ?>>
					Left
				</option>
				<option value="1" <?php if($_POST['search_pos'] == 1){?> selected="selected" <?php } ?>>
					Right
				</option>
			</select>
		</td>
		<td>
			<select name="search_pack" class="form-control" >
				<option value="">Select Plan Type</option>
				<option value="0" <?php if($_POST['search_pack'] != ''){?> selected="selected" <?php } ?>>
					Normal
				</option>
				<option value="1" <?php if($_POST['search_pack'] == 1){?> selected="selected" <?php } ?>>
					Basic
				</option>
			</select>
		</td>
		<td><input type="submit" value="Search" name="Search" class="btn btn-info"></td>
	</tr>
</table>
</form>
<?php
if(isset($_POST['Search'])){
	
	if($_POST['search_pos'] != '' and $_POST['search_userid'] != ''){
		$_SESSION['SESS_search_username'] = $search_userid = $_POST['search_userid'];
		$_SESSION['SESS_POS'] = $pos = $_POST['search_pos'];
		
		$search_id = get_new_user_id($search_userid);
		
		switch($pos){
			case 0: $field = 'left_network'; break;
			case 1:  $field = 'right_network'; break;
		}
		
		$sqlk = "SELECT $field FROM network_users WHERE user_id = '$search_id'";
		$result = mysqli_fetch_array(query_execute_sqli($sqlk))[0];
	}
	if($_POST['status_check'] != ''){
		$_SESSION['SESS_status_check'] = $status_check = $_POST['status_check'];
		/*if($status_check == 1){
			$qur_set_search = " AND package > 0 ";
		}
		else{
			$qur_set_search = " AND package = 0 ";
		}*/
		switch($status_check){
			case 1 : $qur_set_search .= " AND t1.package > 0 AND t1.type = 'B'"; break;
			case 2 : $qur_set_search .= " AND t1.package = 0 AND t1.type = 'B'"; break;
			case 3 : $qur_set_search .= " AND t1.type = 'D' "; break;
		}
	}
	if($_POST['search_pack'] != ''){
		$_SESSION['SESS_search_pack'] = $search_pack = $_POST['search_pack'];
		if($search_pack == 0){
			$qur_set_search .= " AND t2.mode = 1 ";
		}
		else{
			$qur_set_search .= " AND t2.mode in(189,190) ";
		}
	}
	
	$sql = "SELECT t1.*,t2.mode rg_mode FROM users t1 
			left join reg_fees_structure t2 on t1.id_user = t2.user_id
			WHERE t1.id_user IN ($result) $qur_set_search group by t1.id_user ORDER BY t1.id_user ASC";
	
	$_SESSION['SQL_network_member'] = $sql;
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT count(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
		
	if($totalrows != 0)
	{ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
				<th colspan="9">Total Members : <?=$tot_rec;?></th>
				<th>
					<form method="post" action="simple_view_netwrk_mem.php" target="_blank"> 
						<input type=submit name="simple_view" value="Simple View" class="btn btn-warning btn-sm" />
					</form>
				</th>
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User Info</th>
				<th class="text-center">Contact</th>
				<th class="text-center">Sponsor Info</th>
				<th class="text-center">Join/Act Date</th>
				<th class="text-center">Status</th>
				<th class="text-center">Package</th>
				<th class="text-center">Kyc Status</th>
				<th class="text-center">PAN no.</th>
				<th class="text-center">Bank Details</th>
			</tr>
			</thead>
			<?php
			$pnums = ceil($totalrows/$plimit);
			if($newp == ''){ $newp = '1'; }
				
			$start = ($newp-1) * $plimit;
			$starting_no = $start + 1;
			
			$sr_no = $starting_no;
			
			$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
			while($row = mysqli_fetch_array($query))
			{
				$id = $row['id_user'];
				$password = $row['password'];
				$username = $row['username'];
				$email = $row['email'];
				$phone_no = $row['phone_no'];
				
				$alert_email = $row['alert_email'];
				$parent_id = $row['real_parent'];
				$name = $row['f_name']." ".$row['l_name'];
				$type = $row['type'];
				$user_pin = $row['user_pin'];
				$date = $row['date'];
				$act_date = $row['act_date'];
				$pt_mode = $row['rg_mode'];//plan type
				$pt_mode = $pt_mode == 1 ? "Noraml" : "Basic"; 
				$date1 = $act_date1 = "N/A";
				if($date > 0)
				$date1 = date('d M Y', strtotime($date));
				
				if($act_date > 0)
				$act_date1 = date('d M Y', strtotime($act_date));
				
				/*if($type == 'B') { $status = "<span class='label label-success'>Active</span>"; }
				elseif($type == 'C') {  $status = "<span class='label label-warning'>Blocked</span>"; }
				else { $status = "<span class='label label-danger'>Deactive</span>"; }*/
				
				$top_up = get_paid_member($id);
				if($top_up == 0) { $status = "<span class='label label-danger'>Inactive</span>"; }
				else { $status = "<span class='label label-info'>Active</span>"; }
				if($row['type']== 'D'){ $status = "<span class='label label-danger'>Block</span>"; }
			
				$wallet = get_user_wallet($id);
				//$investment = round(get_user_investment($id),4);
				$withdrawal = get_user_withdrawal($id);
				$investment = "";
				$package = '***';
				$my_plan = my_package($id);
				if(!empty($my_plan)){
					$investment = $my_plan[0];
					$package = '('.$my_plan[1].')';
				}
				//$class = '';
				//if($investment == 0){ $class = 'text-danger';}
				
				$benf = $ac_no = $bank = $bank_code = "";
				$sql = "SELECT * FROM kyc WHERE user_id = '$id'";
				$query1 = query_execute_sqli($sql);
				while($rows = mysqli_fetch_array($query1))
				{
					$benf = $rows['name'];
					$ac_no = $rows['bank_ac'];
					$bank = $rows['bank'];
					$bank_code = $rows['ifsc'];
					$pan_no = $rows['pan_no'];
				}
				
				$sposor_id = get_user_name($parent_id);
				$sposor_name = get_full_name($parent_id);
				
				$booster = "";
				/*if(user_booster_active($id) > 0){
					$booster = "<B class='text-success'>Booster</B>";
				}*/
				
				$kyc_sts = get_user_kyc_status_new($id);
				switch($kyc_sts){
					case 'Cancelled' : 	$kyc_status = "<B class='text-danger'>Cancelled </B>"; break;
					case 'Pending' : 	$kyc_status = "<B class='text-warning'>Pending </B>"; break;
					case 'Approved' : 	$kyc_status = "<B class='text-success'>Approved </B>"; break;
				}
				
				$user_reg_status = get_user_reg_mode_status($id);
				
				
				$reg_date = get_user_active_investment_with_date($id)[1];
				 
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><b>User ID -</b> <?=$username?><br /><b>Username - </b><?=$name?></td>
					<td><B>E-mail -</B> <?=$email?><br /><b>Phone -</b> <?=$phone_no?></td>
					<td><b>User ID -</b> <?=$sposor_id?> <br /> <B>Name -</B> <?=$sposor_name?></td>
					<td><B>Join Date -</B>   <?=$date1?><br /><b>Act. Date -</b> <?=$act_date1?></td>
					<td><?=$status?><br /><br /><?=$user_reg_status?></td>
					
					<td><?=$investment?><br /><?=$package;?><br /><?=$booster?><?=$pt_mode?></td>
					<td><?=$kyc_status?></td>
					<td><?=$pan_no?></td>
					<td>
						<b>Beneficiery Name :</b> <?=$benf?><br>
						<b>Account No. :</b> <?=$ac_no?><br>
						<b>Bank Name :</b> <?=$bank?><br>
						<b>IFSC Code :</b> <?= $bank_code?>
					</td>
				</tr> <?php	
				$sr_no++;	
			} 
			?>
		</table> <?PHP
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}
else{ 
}
?>

							