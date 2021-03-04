<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$newp = $_GET['p'];
$plimit = 100;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$minus_1_day = date('Y-m-d', strtotime($systems_date."- 1 Day"));
$cur_week = get_pre_nxt_date($minus_1_day , $lottery_result_day);
$f_day_week = $cur_week[0];
$l_day_week = $cur_week[1];


$qur_set_search = $left_join = '';
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_text'] = $_SESSION['SESS_search_text'];
	$_POST['search_opt'] = $_SESSION['SESS_search_opt'];
	$_POST['date_search'] = $_SESSION['SESS_date_search'];
	$_POST['st_date'] = $_SESSION['SESS_st_date'];
	$_POST['en_date'] = $_SESSION['SESS_en_date'];
	$_POST['mem_status'] = $_SESSION['SESS_mem_status'];
	$_POST['mem_package'] = $_SESSION['SESS_mem_package'];
	$_POST['lot_qual'] = $_SESSION['SESS_lot_qual'];
	$_POST['today_mem'] = $_SESSION['SESS_today_mem'];
}
else{
	unset($_SESSION['SESS_search_text'],$_SESSION['SESS_search_opt'],$_SESSION['SESS_date_search'],$_SESSION['SESS_en_date'],$_SESSION['SESS_st_date'],$_SESSION['SESS_mem_status'],$_SESSION['SESS_mem_package'],$_SESSION['SESS_lot_qual'],$_SESSION['SESS_today_mem']);
}

if(isset($_POST['Search'])){
	$where = " WHERE ";
	$qur_set_search = array();
	if($_POST['mem_status'] != ''){
		$_SESSION['SESS_mem_status'] = $mem_status = $_POST['mem_status'];
		switch($mem_status) {
			case 1 : $qur_set_search[] = " t1.step = 0 AND t1.type = 'B'"; 
					$selmem1='selected="selected"'; 
			break;
			case 2 : $qur_set_search[] = " t1.step = 1 AND t1.type = 'B'"; 
					$selmem2='selected="selected"'; 
			break;
			case 3 : $qur_set_search[] = " t1.type = 'D'"; $selmem3='selected="selected"'; break;
			case 4 : $qur_set_search[] = " t1.step = 1 AND t1.type = 'B' AND t2.date BETWEEN '$f_day_week' AND '$l_day_week'"; 
					$selmem4='selected="selected"'; 
			break;
		}
	}
	
	// Search from project_summary page
	if($_POST['lot_qual'] != ''){
		$_SESSION['SESS_lot_qual'] = $lot_qual = $_POST['lot_qual'];
		switch($lot_qual) {
			case 1 : 
				$qur_set_search[] = " t4.user_id IS NOT NULL"; 
				$left_join = "LEFT JOIN lottery_ticket t4 ON t1.id_user = t4.user_id";
			break;
			case 2 : 
				$qur_set_search[] = " t4.user_id IS NULL AND t2.user_id IS NOT NULL"; 
				$left_join = "";
			break;
		}
	}
	
	if($_POST['today_mem'] != ''){
		$_SESSION['SESS_today_mem'] = $today_mem = $_POST['today_mem'];
		switch($today_mem) {
			case 1 : $qur_set_search[] = " t1.type = 'B' AND t1.date = '$systems_date'"; break;
			case 2 : $qur_set_search[] = " t1.step = 1 AND t1.type = 'B' AND t1.date = '$systems_date'"; break;
		}
	}
	
	
	
	if($_POST['mem_package'] != ''){
		$_SESSION['SESS_mem_package'] = $mem_package = $_POST['mem_package'];
		
		if($mem_package == 14){
			$qur_set_search[] = " t2.mode IN (189,190)"; 
		}
		else{
			$qur_set_search[] = " t2.invest_type = $mem_package"; 
		}
		switch($mem_package) {
			case 1 : $selpack1='selected="selected"'; break;
			case 2 : $selpack2='selected="selected"'; break;
			case 3 : $selpack3='selected="selected"'; break;
			case 4 : $selpack4='selected="selected"'; break;
		}
	}
	
	
	if($_POST['search_opt'] !='' and $_POST['search_text'] !=''){
		
		$_SESSION['SESS_search_text'] = $search_opt = $_POST['search_opt'];
		$_SESSION['SESS_search_opt'] = $search_text = $_POST['search_text'];
		
		$search_id = get_new_user_id($search_text);
		
		switch($search_opt) {
			case 1 : $qur_set_search[] = " CONCAT(t1.f_name, ' ', t1.l_name) = '$search_text'"; 
				$selct1='selected="selected"'; 
			break;
			case 2 : $qur_set_search[] = " t1.id_user = '$search_id'"; $selct2='selected="selected"'; break;
			case 3 : $qur_set_search[] = " t1.phone_no = '$search_text'"; $selct3='selected="selected"'; break;
		}
		
	}
	if($_POST['st_date'] != '' and $_POST['en_date'] != '' and $_POST['date_search'] != ''){
		$_SESSION['SESS_st_date'] = $st_date = date('Y-m-d', strtotime($_POST['st_date']));
		$_SESSION['SESS_en_date'] = $en_date = date('Y-m-d', strtotime($_POST['en_date']));
		$_SESSION['SESS_date_search'] = $date_search = $_POST['date_search'];
		
		switch($date_search) {
			case 1 : $qur_set_search[] = " DATE(t1.date) >= '$st_date' AND DATE(t1.date) <= '$en_date' ";
				$selst1 = 'selected="selected"'; 
			break;
			case 2 : $qur_set_search[] = " DATE(t2.date) >= '$st_date' AND DATE(t2.date) <= '$en_date' ";
				$selst2 = 'selected="selected"'; 
			break;
		}
	}
	if(count($qur_set_search) > 0){
		$qur_set_search = $where.implode(" AND ",$qur_set_search);
	}
	else{
		$qur_set_search = '';	
	}
}
?>

<form method="post" action="index.php?page=<?=$val?>">	
	<input type="hidden" name="mem_status" value="<?=$_POST['mem_status']?>" />
	<div class="col-md-2">
		<select name="date_search" class="form-control">
			<option value="">Select Option</option>
			<option value="1" <?=$selst1?>>Joining Date</option>
			<option value="2" <?=$selst2?>>Activation Date</option>
		</select>
	</div>

	<div class="col-md-2">
		<div class="form-group" id="data_1">
			<div class="input-group date">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" name="st_date" placeholder="From Date" class="form-control" />
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group" id="data_1">
			<div class="input-group date">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				<input type="text" name="en_date" placeholder="To Date" class="form-control" />
			</div>
		</div>
	</div>
	<div class="col-md-2">
		<select name="search_opt" class="form-control">
			<option value="">Search Option</option>
			<option value="1" <?=$selct1?>>User Name</option>
			<option value="2" <?=$selct2?>>User ID</option>
			<option value="3" <?=$selct3?>>Mobile</option>
		</select>
	</div>
	<div class="col-md-3">
		<input type="text" name="search_text" placeholder="Search Text" class="form-control" value="<?=$_POST['search_text']?>" />
	</div>
	<div class="col-md-1">
		<input type="submit" value="Search" name="Search" class="btn btn-info btn-sm">
	</div>
</form>

<div class="col-md-12">&nbsp;</div>

<?php 
if(isset($_POST['create_file'])){
	$file_name = "User_information".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = $_SESSION['SQL_user_info'];
	$result = query_execute_sqli($SQL);              

	$insert_rows.=" User ID \t Username \t E-mail \t Phone \t Sponsor User ID \t Sponsor Username \t Joining Date \t Activate Date \t Status \t Package Name \t Package Amount \t USD Wallet \t KYC Status  " ;
	$insert_rows.="\n";
	
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$id_user = $row['id_user'];
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
		
		$amount = $row['amount'];
		$usd_amt = $row['activationw'];
		$torashare = $row['torashare'];
		
		
		$date1 = $act_date1 = "N/A";
		if($date > 0)
		$date1 = date('d M Y', strtotime($date));
		
		if($act_date > 0)
		$act_date1 = date('d M Y', strtotime($act_date));
		
		/*if($type == 'B') { $status = "<span class='label label-success'>Active</span>"; }
		elseif($type == 'C') {  $status = "<span class='label label-warning'>Blocked</span>"; }
		else { $status = "<span class='label label-danger'>Deactive</span>"; }*/
		
		$top_up = get_paid_member($id);
		if($top_up == 0) { $status = "Inactive"; }
		else { $status = "Active"; }
		if($row['type']== 'D'){ $status = "Block"; }
	
		$wallet = get_user_wallet($id);
		//$investment = round(get_user_investment($id),4);
		$withdrawal = get_user_withdrawal($id);
		$investment = "";
		$package = '***';
		$my_plan = my_package($id_user);
		if(!empty($my_plan)){
			$investment = $my_plan[0];
			$package = '('.$my_plan[1].')';
		}
		
		$sposor_id = get_user_name($parent_id);
		$sposor_name = get_full_name($parent_id);
		
		$kyc_sts = get_user_kyc_status_new($id_user);
		switch($kyc_sts){
			case 'Cancelled' : 	$kyc_status = "Cancelled "; break;
			case 'Pending' : 	$kyc_status = "Pending"; break;
			case 'Approved' : 	$kyc_status = "Approved"; break;
		}
		
		$user_reg_status = get_user_reg_mode_status($id);
		$lottery_ticket = get_user_total_lottery_ticket($id_user);
		
		
		$insert .= $username.$sep;
		$insert .= $name.$sep;
		$insert .= $email.$sep;
		$insert .= $phone_no.$sep;
		$insert .= $sposor_id.$sep;
		$insert .= $sposor_name.$sep;
		$insert .= $date1.$sep;
		$insert .= $act_date1.$sep;
		$insert .= $status.$sep;
		$insert .= $investment.$sep;
		$insert .= $package.$sep;
		$insert .= $amount.$sep;
		$insert .= $usd_amt.$sep;
		$insert .= $kyc_status.$sep;

		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	unset($_SESSION['SQL_user_info']);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click <i class="fa fa-hand-o-right"></i>  here for download file =</B> 
	<a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else{
	$sql = "SELECT t1.*,t2.date act_date, t3.amount,t3.activationw FROM users t1 
	LEFT JOIN reg_fees_structure t2 ON t1.id_user = t2.user_id
	LEFT JOIN wallet t3 ON t1.id_user = t3.id
	$left_join
	$qur_set_search GROUP BY t1.id_user ORDER BY t1.id_user ASC, t2.id ASC";
	
	$_SESSION['SQL_user_info'] = $sql;
	
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query)){
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	} ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="5">Total Member : <?=$tot_rec?></th>
			<th colspan="2">
				<form method="post" action="index.php?page=<?=$val?>">
					<input type="hidden" name="Search" value="Search" />
					<select name="mem_package" class="form-control" onchange="this.form.submit();">
						<option value="">Select package</option>
						<?php
						for($i = 0; $i < count($plan_name); $i++){ ?>
							<option value="<?=$plan_id[$i]?>" <?=$_POST['mem_package']== $plan_id[$i] ? "selected" : ''; ?>>
								<?=$plan_name[$i]?>
							</option> <?php
						} ?>
						
					</select>
				</form>
			</th>
			<th colspan="1">
				<form method="post" action="index.php?page=<?=$val?>">
					<input type="hidden" name="Search" value="Search" />
					<select name="mem_status" class="form-control" onchange="this.form.submit();">
						<option value="">Select Status</option>
						<option value="1" <?=$selmem1?>>Registered Member</option>
						<option value="2" <?=$selmem2?>>Active Member</option>
						<option value="3" <?=$selmem3?>>Block Member</option>
						<option value="4" <?=$selmem4?>>Active This Week</option>
					</select>
				</form>
			</th>
			<th colspan="1" class="text-right">
				<form method="post" action="index.php?page=<?=$val?>">
				<input type="submit" name="create_file" value="Create Excel File" class="btn btn-danger btn-sm"/>
				</form>
				<!--<a href="index.php?page=user_email_excel_file" class="btn btn-danger btn-sm" title="Create Excel File">
					Create Excel File
				</a>-->
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
			<th class="text-center" width="20%">Wallet</th>
			<!--<th class="text-center">Kyc Status</th>
			<th class="text-center">Lottery</th>-->
			<th class="text-center">Login/Profile</th>
			
		</tr>
		</thead>
		<?php
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$cnt = $plimit*($newp-1);
		$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query)){
			$cnt++;
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
			$step = $row['step'];
			
			$amount = $row['amount'];
			$usd_amt = $row['activationw'];
			
			
			$date1 = $act_date1 = "N/A";
			if($date > 0)
			$date1 = date('d M Y', strtotime($date));
			
			if($act_date > 0)
			$act_date1 = date('d M Y', strtotime($act_date));
			
			/*if($step == 1 and $type == 'B') { $status = "<span class='label label-success'>Active</span>"; }
			elseif($step == 0 and $type == 'B') {  $status = "<span class='label label-warning'>Inactive</span>"; }
			elseif($type == 'D'){ $status = "<span class='label label-danger'>Block</span>"; }*/
			
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
			
			$sposor_id = get_user_name($parent_id);
			$sposor_name = get_full_name($parent_id);
			
			$kyc_sts = get_user_kyc_status_new($id);
			switch($kyc_sts){
				case 'Cancelled' : 	$kyc_status = "<B class='text-danger'>Cancelled </B>"; break;
				case 'Pending' : 	$kyc_status = "<B class='text-warning'>Pending </B>"; break;
				case 'Approved' : 	$kyc_status = "<B class='text-success'>Approved </B>"; break;
			}
			
			$user_reg_status = get_user_reg_mode_status($id);
			$lottery_ticket = get_user_total_lottery_ticket($id);
			?>
			<tr class="text-center">
				<td><?=$cnt?></td>
				<td class="text-left"><B>User ID -</B> <?=$username?><br /><B>Username - </B><?=$name?><br />
				 <B>Pass -</B> <?=$password?>
				</td>
				<td class="text-left"><B>E-mail -</B> <?=$email?><br /><B>Phone -</B> <?=$phone_no?></td>
				<td class="text-left"><B>User ID -</B> <?=$sposor_id?> <br /> <B>Name -</B> <?=$sposor_name?></td>
				<td class="text-left"><B>Join Date -</B>   <?=$date1?><br /><B>Act. Date -</B> <?=$act_date1?></td>
				<td><?=$status?><!--<br /><br /><?=$user_reg_status?>--></td>
				<td><?=$investment?><br /><?=$package;?></td>
			<td>Bonus Wallet : <?=round($amount,2)?> <br />
				Deposit Wallet : $ <?=round($usd_amt,2)?>
					<!--<b>Deposit Wallet :</b> <?=$amount?><br>
					<b>SMG Wallet :</b> <?=$smg_wal?><br>
					<b>SMG Share :</b> <?=$smg_share?><br>
					<b>Tora Global :</b> <?= $torashare?>-->
				</td>
				<!--<td><?=$kyc_status?></td>
				<td><?=$lottery_ticket?></td>-->
				<td>
					<!--<form action="#" method="post">
						<input type="hidden" name="user_name" value="<?=$username; ?>" />
						<input type="submit" name="submit" value="Summary" class="btn btn-danger btn-xs" />
					</form>
					<br />-->
					<form action="../login_check.php" target="_new" method="post">
						<input type="hidden" name="username" value="<?=$username; ?>" />
						<input type="hidden" name="password" value="<?=$password; ?>" />
						<input type="hidden" name="aul" value="1" />
						<input type="hidden" name="admin_login" value="admin" />
						<input type="submit" name="submit" value="Login" class="btn btn-success btn-xs" />
					</form>
					<!--<br />
					<form action="../login_check.php" target="_new" method="post">
						<input type="hidden" name="username" value="<?=$username; ?>" />
						<input type="hidden" name="password" value="<?=$password; ?>" />
						<input type="hidden" name="aul" value="1" />
						<input type="submit" name="submit" value="Web Login" class="btn btn-primary btn-xs" />
					</form>-->
					<br />
					<form action="index.php?page=user_profile" method="post">
						<input type="hidden" name="user_name" value="<?=$username; ?>" />
						<input type="submit" name="submit" value="Profile" class="btn btn-black btn-xs" />
					</form>
				</td>
			</tr> <?php		
		} 
		?><!--<p id="demo"></p>-->
	</table> <?php
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val); 
}

function user_booster_active($user_id){
	$querw = query_execute_sqli("SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' and boost_id > 0 ");
	$nums = mysqli_num_rows($querw );
	$date_reg = mysqli_fetch_array($querw)[0];
	$booster = "";
	if($nums > 0){
		$booster = "<B class='text-success'>Booster</B>";
	}
	return $nums;
}
?>
<script>
var aa = Math.random().toString(36).substr(2, 10);
document.getElementById("demo").innerHTML = aa; 
</script>


