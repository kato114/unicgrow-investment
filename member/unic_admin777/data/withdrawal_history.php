<?php
include('../../security_web_validation.php');

include("../function/functions.php");
include("../function/setting.php");

if(isset($_REQUEST['p'])){
	$_SESSION['page_on'] = $_REQUEST['p'];
}

$newp = $_GET['p'];
$plimit = "100";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_POST['more'])){
	$_SESSION['SESS_date_giv'] = $_POST['date_giv'];
	$_SESSION['SESS_ac_type'] = $_POST['ac_type'];
}
$date_giv = $_SESSION['SESS_date_giv'];
$ac_type = $_SESSION['SESS_ac_type'];

$qur_set_search = " WHERE DATE(date) = '$date_giv'";
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['search_username'] = $_SESSION['SESS_search_username'];
	$_POST['w_status'] = $_SESSION['SESS_w_status'];
	$_POST['date_giv'] = $_SESSION['SESS_date_giv'];
	$_POST['ac_type'] = $_SESSION['SESS_ac_type'];
	$_POST['t_status'] = $_SESSION['SESS_t_status'];
}
else{
	unset($_SESSION['SESS_search_username'],$_SESSION['SESS_w_status'],$_SESSION['SESS_t_status']);
}


if(isset($_POST['Search'])){
	if(!empty($_POST['search_username'])){
		$_SESSION['SESS_search_username'] = $search_username = $_POST['search_username'];
		$search_id = get_new_user_id($search_username);
		$qur_set_search = " AND user_id = '$search_id' ";
	}
	
	if(!empty($_POST['w_status'])){
		$_SESSION['SESS_w_status'] = $w_status = $_POST['w_status'];
		if($w_status == 1){ $w_status = "65,0"; }
		$qur_set_search = "";
		$qur_set_search = " WHERE status IN ($w_status) ";
	}
	
	if(!empty($_POST['t_status'])){
		$_SESSION['SESS_t_status'] = $t_status = $_POST['t_status'];
		$date_giv = $systems_date;
	}
}
if(isset($_POST['confirm'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$utr_no = $_POST['utr_no'];
	$remarks = $_POST['remarks'];
	$update_utr = ",transaction_hash = 'xxxxxx',";
	$sql = "UPDATE withdrawal_crown_wallet SET status = '2' $update_utr sys_comment = '$remarks'
	,action_date='$systems_date_time', accept_date='$systems_date_time' 
	WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
	if(query_affected_rows() > 0){
		?> 
		<script>
			alert("Confirm successfully !"); window.location = "index.php?page=<?=$val?>&p=<?=$_SESSION['page_on']?>";
		</script> <?php
	}
	else{
		echo "<B class='text-danger'>Error : Somthing Goes Wrong !!</B>";
	}
}

if(isset($_POST['approve'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$utr_no = $_POST['utr_no'];
	$remarks = $_POST['remarks'];
	$sql = "UPDATE withdrawal_crown_wallet SET status = 1 ,sys_comment = 'admin approve'
	,accept_date='$systems_date_time' 
	WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
	if(query_affected_rows() > 0){
		?> 
		<script>
			alert("Approved successfully !"); window.location = "index.php?page=<?=$val?>&p=<?=$_SESSION['page_on']?>";
		</script> <?php
	}
	else{
		echo "<B class='text-danger'>Error : Somthing Goes Wrong !!</B>";
	}
}
if(isset($_POST['cancel'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$utr_no = $_POST['utr_no'];
	$remarks = $_POST['remarks'];
	$req_crw = $_POST['req_crw'];
	$update_utr = ($_POST['pay_from'] == 1 or $_POST['pay_from'] == 2) ? ",transaction_hash = '$utr_no'," : ",transaction_no = '$utr_no'," ;
	$sql = "UPDATE withdrawal_crown_wallet SET status = 3 $update_utr sys_comment = 
	'$remarks',action_date='$systems_date_time', accept_date='$systems_date_time'
	WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
	if(query_affected_rows() > 0){
		$sql = "UPDATE wallet SET 
				amount = amount+'$req_crw' 
				WHERE id = '$user_id' ";
		query_execute_sqli($sql);
		insert_wallet_account($user_id , $user_id , $req_crw , $systems_date , $acount_type[41] ,$acount_type_desc[40], $mode=1 , get_wallet_amount($user_id),$wallet_type[1],$remarks = $remarks);
		?> 
		<script>
			alert("Cancel successfully !"); window.location = "index.php?page=<?=$val?>&p=<?=$_SESSION['page_on']?>";
		</script> <?php
	}
	else{
		echo "<B class='text-danger'>Error : Somthing Goes Wrong !!</B>";
	}
}

if(isset($_POST['create_file']))
{
	$file_name = "withdrawal".date('Y-m-d').time();
	$sep = "\t";
	$fp = fopen('mlm_user excel files/'.$file_name.'.xls', "w");
	$insert = ""; 
	$insert_rows = ""; 
	
	$SQL = "SELECT * FROM withdrawal_crown_wallet WHERE DATE(date) = DATE('$date_giv') 
	$qur_set_search";
	
	//$SQL = $_SESSION['SQL_withdraw'];
	$result = query_execute_sqli($SQL);              

	$insert_rows.=" User ID \t Current Withdrawal(INR) \t Date Time \t Beneficiery Name \t A/C No. \t Bank Name \t Branch \t IFSC Code \t UTR No. \t Remarks \t Status" ;
	
	$insert_rows.="\n";
	fwrite($fp, $insert_rows);
	while($row = mysqli_fetch_array($result))
	{
		$insert = "";
		$user_id = $row['user_id'];
		$username = get_user_name($user_id);
		$name = ucwords($row['f_name']." ".$row['l_name']);
		$wallet_type = $row['ac_type'];
		$wal_amt = $row['wal_amt'];
		$tds = $row['tax'];
		$adm_tax = 0;
		$request_crowd = $row['request_crowd'];
		$bit_ac_no = $row['bank_ac'];
		$utr_no = $row['transaction_no'];
		$remarks = $row['sys_comment'];
		$utr_no = $row['transaction_no'];
		$status = $row['status'];
		
		$tot_amt = round($request_crowd+$tds+$adm_tax,2);
		
		$date = date('d/m/Y H:i:s', strtotime($row['date']));
		
		
		switch($status){
			case 0 : $status = "Pending";	break;
			case 1 : $status = "Processing";	break;
			case 2 : $status = "Confirm";	break;
			case 3 : $status = "Cancel";	break;
			case 65 : $status = "Unconfirmed";	break;
		}
		
		$sql = "SELECT * FROM kyc WHERE user_id = '$user_id'";
		$query1 = query_execute_sqli($sql);
		while($rows = mysqli_fetch_array($query1))
		{
			$benf = $rows['name'];
			$ac_no = $rows['bank_ac'];
			$bank = $rows['bank'];
			$bank_code = $rows['ifsc'];
			$branch = $rows['branch'];
		}	
		
		/*$date_kyc = get_user_kyc_approved_date($user_id);
		$tot_cost_withdraw = get_user_withdrawal_tot_cost($user_id,$systems_date);*/
		
		$insert .= $username.$sep;
		$insert .= $tot_amt.$sep;
		$insert .= $date.$sep;
		$insert .= $benf.$sep;
		$insert .= sprintf("A/c - ".$ac_no).$sep;
		$insert .= $bank.$sep;
		$insert .= $branch.$sep;
		$insert .= $bank_code.$sep;
		$insert .= $utr_no.$sep;
		$insert .= $remarks.$sep;
		$insert .= $status.$sep;
		
		$insert = str_replace($sep."$", "", $insert);
		
		$insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $insert);
		$insert .= "\n";
		fwrite($fp, $insert);
	}
	fclose($fp);
	unset($_SESSION['SQL_withdraw']);
	?>
	<p><a href="index.php?page=<?=$val?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a></p>
	<B class='text-success'>Excel File Created Successfully !</B><br />
	<B>Click here for download file =</B> <a href="mlm_user excel files/<?=$file_name?>.xls"><?=$file_name?>.xls</a> <?php
}
else{
	?>
	<!--<div class="row">
		<div class="col-md-12">
			<a href="index.php?page=withdrawal_roi" class="btn btn-black btn-sm"><i class="fa fa-reply"></i>Back</a>
		</div>
	</div>
	<div class="row"><div class="col-md-12">&nbsp;</div></div>-->
	
	
	<div class="col-sm-12">
		<button type="button" id="close" onclick="window.close()" class='btn btn-danger btn-sm'>
			<i class="fa fa-reply"></i> Close Window
		</button>
	</div>
	<div class="col-md-12">&nbsp;</div>
	
	<table class="table table-bordered">
		<tr>
			<td>
				<form method="post" action="index.php?page=<?=$val?>">
					<input type="hidden" name="Search" value="1" />
					<select name="w_status" class="form-control" onchange="this.form.submit();" required>
						<option value="">Select Status</option>
						<option value="1" <?php if($_POST['w_status'] == 1){?> selected="selected" <?php } ?>>
							Pending
						</option>
						<option value="2" <?php if($_POST['w_status'] == 2){?> selected="selected" <?php } ?>>
							Approved
						</option>
						<option value="3" <?php if($_POST['w_status'] == 3){?> selected="selected" <?php } ?>>
							Cancelled
						</option>
					</select>
				</form>
			</td>
			<form method="post" action="index.php?page=<?=$val?>">
			<td><input type="text" name="search_username" placeholder="  By Username" class="form-control" /></td>
			
			<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
			</form>
			<td class="text-right">
				<!--<input type="submit" name="create_file" value="Create Excel File" class="btn btn-warning btn-sm"/>-->
				<form method="post" action="simple_view_withdraw.php" target="_blank"> 
					<input type=submit name="simple_view" value="Simple View" class="btn btn-warning btn-sm" />
				</form>
			</td>
		</tr>
	</table>
	
	
	<?php
	/*$sql = "SELECT T1.*,T2.username,T2.phone_no FROM withdrawal_crown_wallet T1 
	LEFT JOIN users T2 ON T1.user_id = T2.id_user 
	WHERE T1.ac_type = '$ac_type' AND DATE(T1.date) = DATE('$date_giv') $qur_set_search";*/
	$sql = "SELECT * FROM withdrawal_crown_wallet /*ac_type = '$ac_type' AND*/  
	$qur_set_search";
	$_SESSION['SQL_withdraw'] = $sql;
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	$sqlk = "SELECT SUM(amount) amt,SUM(request_crowd) amt1,COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query)){
		$tot_rec = $ro['num'];
		$total_amount = $ro['amt'];
		$total_amount1 = $ro['amt1'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows > 0){ ?>
		<table class="table table-bordered">
			<thead>
			<tr>
			<th colspan="11">Total Payble Amount : <?=round($total_amount,2); ?> &#36;</th>
			<!--<th colspan="6">Total Payble Amount : <?=$total_amount1; ?> &#3647;</th>-->
			</tr>
			<tr>
				<th class="text-center">Sr. No.</th>
				<th class="text-center">User ID</th>
				<th class="text-center">Account Info</th>
				<th class="text-center">Date Time</td>
				<th class="text-center">Pay From</td>
				<th class="text-center">Total Withdrawal</td>
				<th class="text-center">Hash</td>
				<th class="text-center">Remarks</td>
				<th class="text-center">Status</td>
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
				$table_id = $row['id'];
				$user_id = $row['user_id'];
				//$username = $row['username'];
				$username = get_user_name($user_id);
				$amount = 0;
				$amount = $row['request_crowd'];
				$amount1 = $row['amount'];
				$hash_code = $row['transaction_hash'];
				$remarks = $row['user_comment'];
				$utr_no = ($row['ac_type'] == 1 or $row['ac_type'] == 2 )? $hash_code : $row['transaction_no'];
				$tds = $row['tax'];
				$adm_tax = 0;
				$date = date('d/m/Y H:i:s', strtotime($row['date']));
				
				$tot_amt = $amount+$adm_tax;
				$status = $row['status'];
				switch($status){
					case 0 :
					case 65 : 
						$status = "<span class='label label-warning'>Pending</span><br /><br />
						<input type='submit' name='confirm' value='Manual Confirm' class='btn btn-success btn-sm' /></br>
						</br>
						<input type='submit' name='approve' value='Approve' class='btn btn-success btn-sm' /></br>
						</br><input type='submit' name='cancel' value='Cancel' class='btn btn-success btn-sm' />";	
					break;
					case 1 : $status = "<span class='label label-success'>Processing</span>";	break;
					case 2 : $status = "<span class='label label-primary'>Confirm</span>";	break;
					case 3 : $status = "<span class='label label-danger'>Cancel</span>";	break;
				}
				/*if($mode == 0){ $status = "<span style='color:#FF0000;'>Pending</span>"; }
				else{ $status = "<span style='color:#008000;'>Confirmed</span>"; }*/
				$benf = $ac_no = $bank = $bank_code = "";
				$table = "kycm";
				$limit = "limit ".($tds-1)." , 1";
				if($tds == 0){
					$table = "kyc";
					$limit = "";
				}
				$sql = "SELECT * FROM users WHERE id_user = '$user_id' ";
				$query1 = query_execute_sqli($sql);
				while($rows = mysqli_fetch_array($query1))
				{
					$btc_addrs = $rows['btc_ac'];
					$etc_addrs = $rows['etc_ac'];
					$bank_name = $rows['bank_name'];
					$bank_country = $rows['bank_country'];
					$bank_ac = $rows['bank_ac'];
					$swift_code = $rows['swift_code'];
					$ifsc_code = $rows['ifsc_code'];
					$bank_info = $rows['bank_info'];
				}	
				
				$bank_details = "<B>Bank Name : </B>".$bank_name."<br /><B>Bank Country : </B>".$bank_country."<br /><B>Account No. :  </B>".$bank_ac."<br /><B>IBAN : </B>".$swift_code."<br /><B>Swift/ Routing : </B>".$ifsc_code."<br /><B>Additional Info : </B>".$bank_info;
				
				if($utr_no == '' and $row['status'] == 65){
					$utr_no = "<input type='text' name='utr_no' class='form-control' required />";
				}
				
				if($remarks == '' and $row['status'] == 0){
					$remarks = "<textarea name='remarks' class='form-control'></textarea>";
				}	
				$ac_info = $row['ac_type'] == 1 ? $btc_addrs : ($row['ac_type'] == 2 ? $etc_addrs : $bank_details);
				//$date_kyc = get_user_kyc_approved_date($user_id);
				$pay_from = $row['ac_type'] == 1 ? "Bitcoin" : ($row['ac_type'] == 2 ? 'ETH' : 'BANK');
				//$tot_cost_withdraw = get_user_withdrawal_tot_cost($user_id,$systems_date);
				?>
				<form method="post" action="index.php?page=<?=$val?>">
				<input type="hidden" name="table_id" value="<?=$table_id?>" />
				<input type="hidden" name="user_id" value="<?=$user_id?>" />
				<input type="hidden" name="pay_from" value="<?=$row['ac_type']?>" />
				<input type="hidden" name="req_crw" value="<?=$row['amount']?>" />
				<tr class="text-center">
					<td><?=$sr_no?></td>
					<td><?=$username?></td>
					<td><?=$ac_info?></td>
					<td><?=$date?></td>
					<td><?=$pay_from?></td>
					<td><?=round($amount1,2)?> &#36;</td>
					<td><?=$hash_code?></td>
					<td><?=$remarks?></td>
					<td><?=$status?></td>
				</tr>
				</form> <?php
				$sr_no++;
			} ?>
		</table> <?php
		pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
	}
	else{ echo "<B class='text-danger'>There are no information to show!</B>";  }
}



function get_user_withdrawal_tot_cost($user_id,$date){
	
	$sql ="SELECT COALESCE(SUM(request_crowd),0) FROM withdrawal_crown_wallet WHERE user_id = '$user_id' 
	AND DATE(date) < '$date' ";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
?>