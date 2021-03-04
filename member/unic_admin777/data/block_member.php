<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
require("../function/functions.php");
require("../function/setting.php");

$newp = $_GET['p'];
$plimit = 25;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$qur_set_search = ' where id_user > 0';
if(count($_GET) == 1){
	unset($_SESSION['SESS_USERNAME'],$_SESSION['SESS_member_roi']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
	$_POST['member_roi'] = $_SESSION['SESS_member_roi'];
}
if(isset($_POST['Search'])){
	if($_POST['search_username'] !=''){
		$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
		$u_id = get_new_user_id($search_username);
		$qur_set_search .= " and id_user = '$u_id' ";
	}
	if($_POST['member_roi'] !=''){
		$_SESSION['SESS_member_roi'] = $_POST['member_roi'];
		if($_POST['member_roi'] == 0)
			$qur_set_search .= " and type = 'B' ";
		else
			$qur_set_search .= " and type = 'D' ";
	}
	
}
elseif(isset($_POST['block']) and $_POST['block'] == 'Confirm'){
	$id_user = $_REQUEST['id'];
	$user_name = $_REQUEST['user_name'];	
	query_execute_sqli("update users set type = 'D' where id_user = '$id_user' ");
	insert_wallet_account($id_user , $id_user , 1 , $systems_date_time , $acount_type[28] ,$acount_type_desc[28], $mode=2 ,0,0,$remarks = $_POST['remark']);
	$msgs =  "User ".$user_name." Blocked Successfully !!";
	?> <script>alert("<?=$msgs?>");window.location="index.php?page=<?=$val?>";</script> <?php
}
elseif(isset($_POST['unblock'])){
	$id_user = $_REQUEST['id'];
	$user_name = $_REQUEST['user_name'];	
	query_execute_sqli("update users set type = 'B' where id_user = '$id_user' ");
	insert_wallet_account($id_user , $id_user , 1 , $systems_date_time , $acount_type[28] ,"Member Un-Blocked", $mode=1 ,0,0,$remarks = "Unblock Member");
	$msgs =  "User ".$user_name." Activated Successfully !!";
	?> <script>alert("<?=$msgs?>");window.location="index.php?page=<?=$val?>";</script> <?php
}
if(isset($_POST['block']) and $_POST['block'] == "Block"){
	$username = $_POST['user_name'];
	$query = query_execute_sqli("select * from users where username = '$username' ");
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$id_user = $row['id_user'];
			$user_type = $row['type'];
		}
		if($user_type != 'D'){
			$w_q = query_execute_sqli("select * from wallet where id = '$id_user' ");
			while($rr = mysqli_fetch_array($w_q)){
				$wallet_amount = $rr['amount'];
				$ewallet_amount = $rr['activationw'];
			}
			$investment = '';
			$inv_amt = 0;
			$inv_prf = 0;
			$sql = "select sum(update_fees) update_fees from reg_fees_structure where user_id = '$id_user' and mode in(1,2) order by id desc limit 1 ";
			$w_q = query_execute_sqli($sql);
			while($rr = mysqli_fetch_array($w_q)){
				$inv_amt = $rr['update_fees'];
				$inv_prf = $rr['profit'];
				$investment .= "PV = ".$rr['request_crowd']."Fess = ".$rr['update_fees']."Profit = ".$rr['profit']." &#3647; on ".$rr['date']."<br>";
				
			}
			if(isset($_POST['block']) and $_POST['block'] == 'Block'){
			?>
			<form action="" method="post">
			<table class="table table-bordered">
				<tr><th>Member UserId</th><th><?=$username?></th></tr>
				<tr><th>Comission Wallet : <?=$wallet_amount?></th><th>E-Wallet : <?=$ewallet_amount?></th></tr>
				<tr><th>Investment Amount : <?=$inv_amt?></th></tr>
				<tr><th>Remarks : </th><th><input type="text" name="remark" value="<?=$_POST['remark']?>" class="form-control" required /></th></tr>
				<tr>
					<td colspan="2" align="center">
							<input type="hidden" name="id" value="<?=$id_user?>" />
							<input type="hidden" name="user_name" value="<?=$username?>" />
							<input type="submit" name="back" value="Back" class="btn btn-info" />
							<input type="submit" name="block" value="Confirm" class="btn btn-danger" onClick="javascript: return confirm('Please confirm To Block');" />
						
					</td>
				</tr>
			</table>
			</form> <?php
			}
		}
		else{ echo "<B class='text-danger'>User " .$username." already Block !!</B>";  }
	}
	else{ echo "<B class='text-danger'>Please enter correct usernsme !!</B>";  }
}
else{
	$sel = "selected=selected"; ?>

	<form method="post" action="index.php?page=<?=$val?>">
	<table class="table table-bordered">
		<tr><thead><th colspan="3">Block Member Information</th></thead></tr>
		<tr>
			<td><input type="text" name="search_username" value="<?=$_POST['search_username']?>" placeholder="Search By Username" class="form-control" /></td>
			<td>
				<select name="member_roi" class="form-control">
					<option value="">Select Member</option>
					<option value="0" <?=($_POST['member_roi'] == 0 && $_POST['member_roi'] != "" && isset($_POST['member_roi'])) ? $sel : "";?>>Member For Block</option>
					<option value="1"  <?=($_POST['member_roi'] == 1 && isset($_POST['member_roi']))  ? $sel : "";?>>Member For Un-Block</option>
				</select>
			</td>
			<td><input type="submit" value="Submit" name="Search" class="btn btn-info"></td>
		</tr>
	</table>
	</form>	
	<?php
	$sql = "SELECT * FROM users $qur_set_search";
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
	
	if($totalrows > 0){ ?>
		<table class="table table-bordered table-hover">
			<thead>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">User Name</th>
				<th class="text-center">Contact</th>
				<th class="text-center">E-mail ID</th>
				<th class="text-center">Reg. Date</th>
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
				$username = $row['username'];
				$name = ucwords($row['f_name']." ".$row['l_name']);
				$date = $row['date'];
				$phone_no = $row['phone_no'];
				$email = $row['email'];
				$btn_v = $row['type'] == "D" ? "Unblock" : "Block";
				$btn_n = $row['type'] == "D" ? "unblock" : "block";
				$btn_c = $row['type'] == "D" ? "danger" : "success";
				if($date > 0)
				$date1 = date('d M Y', strtotime($date));
				?>
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username?></td>
					<td><?=$name?></td>
					<td><?=$phone_no?></td>
					<td><?=$email?></td>
					<td><?=$date1?></td>
					<td>
						<form name="inact" action="" method="post">
							<input type="hidden" name="id" value="<?=$id?>" />
							<input type="hidden" name="user_name" value="<?=$username?>" />
							<input type="submit" name="<?=$btn_n?>" value="<?=$btn_v ?>" onclick="javascript:return confirm(&quot; Are you sure to <?=$btn_v?> (<?=$username?>) this member? !! &quot;);" class="btn btn-<?=$btn_c?> btn-xs" />
						</form>
					</td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
} ?>


