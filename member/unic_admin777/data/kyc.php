<?php
include('../../security_web_validation.php');
include("../function/functions.php");
include("../function/setting.php");
include("../function/send_mail.php");


$newp = $_GET['p'];
$plimit = 100;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;

unset($_SESSION['user_id'],$_SESSION['kyc_type'],$_SESSION['table_id']);

if(isset($_SESSION['IMG_UPLOAD'])){
	echo $_SESSION['IMG_UPLOAD'];
	unset( $_SESSION['IMG_UPLOAD']);
}


$qur_set_search = " WHERE t1.add_proof <> '' AND t1.id_proof_front <> ''";
if(count($_GET) > 1){
	$_POST['Search'] = '1';
	$_POST['s_date'] = $_SESSION['SESS_s_date'];
	$_POST['e_date'] = $_SESSION['SESS_e_date'];
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
	$_POST['kyc_status'] = $_SESSION['SESS_kyc_status'];
}
else{
	unset($_SESSION['SESS_s_date'],$_SESSION['SESS_e_date'],$_SESSION['SESS_USERNAME'],$_SESSION['SESS_kyc_status']);
}


if(isset($_POST['Search'])){
	if($_POST['s_date'] != '' and $_POST['e_date'] != ''){
		$_SESSION['SESS_s_date'] = $s_date = date('Y-m-d', strtotime($_POST['s_date']));
		$_SESSION['SESS_e_date'] = $e_date = date('Y-m-d', strtotime($_POST['e_date']));
		$qur_set_search = "WHERE DATE(t1.date) BETWEEN '$s_date' AND '$e_date'";
	}
	
	$_SESSION['SESS_kyc_status'] = $kyc_status = $_POST['kyc_status'];
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	$search_id = get_new_user_id($search_username);
	
	if($_POST['search_username'] != ''){
		$qur_set_search = " WHERE t1.user_id = '$search_id' ";
	}
	if($_POST['search_username'] != '' and $_POST['kyc_status'] != ''){
		$qur_set_search = " WHERE t1.user_id = '$search_id' ";
		switch($kyc_status) {
			case 1 : 
				/*$qur_set_search=" WHERE ((t1.mode_pan= 0 OR t1.mode_id= 0 OR t1.mode_photo= 0 OR t1.mode_chq = 0) 
				OR (t1.mode_pan = 4 OR t1.mode_id = 4 OR t1.mode_photo = 4 OR t1.mode_chq = 4))*/ 
				$qur_set_search=" WHERE (t1.mode_pan = 0 OR t1.mode_id = 0 OR t1.mode_photo = 0 OR t1.mode_chq = 0)
				AND (t1.mode_pan != 4 AND t1.mode_id != 4 AND t1.mode_photo != 4 AND t1.mode_chq != 4)
				AND t1.user_id = '$search_id'"; 
				$kyc_select1 = 'selected="selected"'; 
			break;
			case 2 : 
				$qur_set_search=" WHERE (t1.mode_pan = 1 AND t1.mode_id = 1 AND t1.mode_photo = 1 AND t1.mode_chq=1)
				AND t1.user_id = '$search_id'"; 
				$kyc_select2 = 'selected="selected"'; 
			break;
			case 3 : 
				$qur_set_search=" WHERE (t1.mode_pan=4 OR t1.mode_id = 4 OR t1.mode_photo = 4 OR t1.mode_chq = 4)
				AND t1.user_id = '$search_id'"; 
				$kyc_select3 = 'selected="selected"'; 
			break;
		}
	}

	if($_POST['kyc_status'] != '' and $_POST['search_username'] == ''){
		switch($kyc_status) {
			case 1 : 
				/*$qur_set_search=" WHERE ((t1.mode_pan= 0 OR t1.mode_id= 0 OR t1.mode_photo= 0 OR t1.mode_chq = 0) 
				OR (t1.mode_pan = 4 OR t1.mode_id = 4 OR t1.mode_photo = 4 OR t1.mode_chq = 4))"; */
				$qur_set_search=" WHERE (t1.mode_pan = 0 OR t1.mode_id = 0 OR t1.mode_photo = 0 OR t1.mode_chq = 0)
				AND (t1.mode_pan != 4 AND t1.mode_id != 4 AND t1.mode_photo != 4 AND t1.mode_chq != 4)";
				$kyc_select1 = 'selected="selected"'; 
			break;
			case 2 : 
				$qur_set_search=" WHERE (t1.mode_pan = 1 AND t1.mode_id = 1 AND t1.mode_photo=1 AND t1.mode_chq=1)"; 
				$kyc_select2 = 'selected="selected"'; 
			break;
			case 3 : 
				$qur_set_search = " WHERE t1.mode_pan=4 OR t1.mode_id = 4 OR t1.mode_photo = 4 OR t1.mode_chq = 4"; 
				$kyc_select3 = 'selected="selected"'; 
			break;
		}
	}
	
	if($_POST['s_date'] != '' and $_POST['e_date'] != '' and $_POST['kyc_status'] != ''){
		switch($kyc_status) {
			case 1 : 
				/*$qur_set_search=" WHERE ((t1.mode_pan = 0 OR t1.mode_id = 0 OR t1.mode_photo= 0 OR t1.mode_chq= 0) 
				OR (t1.mode_pan = 4 OR t1.mode_id = 4 OR t1.mode_photo = 4 OR t1.mode_chq = 4)) 
				AND DATE(t1.date) >= '$s_date' AND DATE(t1.date) <=  '$e_date'";*/ 
				$qur_set_search=" WHERE (t1.mode_pan = 0 OR t1.mode_id = 0 OR t1.mode_photo = 0 OR t1.mode_chq = 0)
				AND (t1.mode_pan != 4 AND t1.mode_id != 4 AND t1.mode_photo != 4 AND t1.mode_chq != 4)
				AND DATE(t1.date) >= '$s_date' AND DATE(t1.date) <=  '$e_date'"; 
				$kyc_select1 = 'selected="selected"'; 
			break;
			case 2 : 
				$qur_set_search=" WHERE (t1.mode_pan = 1 AND t1.mode_id = 1 AND t1.mode_photo = 1 AND t1.mode_chq=1)
				AND DATE(t1.date) >= '$s_date' AND DATE(t1.date) <=  '$e_date'"; 
				$kyc_select2 = 'selected="selected"'; 
			break;
			case 3 : 
				$qur_set_search=" WHERE (t1.mode_pan=4 OR t1.mode_id = 4 OR t1.mode_photo = 4 OR t1.mode_chq = 4)
				AND DATE(t1.date) >= '$s_date' AND DATE(t1.date) <=  '$e_date'"; 
				$kyc_select3 = 'selected="selected"'; 
			break;
		}
	}
}

?>
<form method="post" action="index.php?page=kyc">
<table class="table table-bordered">
	<tr>
		<td>
			<!--<form method="post" action="index.php?page=<?=$val?>">
				<input type="hidden" name="Search" value="Search" />-->
				<!--<select name="kyc_status" class="form-control" onchange="this.form.submit();">-->
				<select name="kyc_status" class="form-control">
					<option value="">Select Status</option>
					<!--<option value="1" <?=$kyc_select1?>>Pending (Rejected)</option>-->
					<option value="1" <?=$kyc_select1?>>Pending</option>
					<option value="2" <?=$kyc_select2?>>Approved</option>
					<option value="3" <?=$kyc_select3?>>Rejected</option>
				</select>
			<!--</form>-->
		</td>
		
		<td>
			<div class="col-md-6">
				<div class="form-group" id="data_1">
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="s_date" placeholder="Start Date" class="form-control" />
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group" id="data_1">
					<div class="input-group date">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" name="e_date" placeholder="End Date" class="form-control" />
					</div>
				</div>
			</div>
		</td>
		<td><input type="text" name="search_username" placeholder="Search By Username" value="<?=$_POST['search_username']?>" class="form-control" /></td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	

<?php
if(isset($_POST['cancel_allkyc'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$type_kyc','$remarks', 0 ,'$systems_date')";
	query_execute_sqli($sql);
	
	$sql = "UPDATE kyc SET mode_id = 4 , mode_photo = 4 , mode_pan = 4 , mode_chq = 4 , mode = 4 , 
	add_proof = '' , id_proof_front = '' , id_proof_back = '' , photo = '' , signature = ''
	WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
			
	$_SESSION['IMG_UPLOAD'] = "<B class='text-success'>KYC Rejected Successfully !!</B>";
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$username = get_user_name($user_id);
		$mesgs = "$remarks, Hi $username, Your KYC Rejected by Admin. Thanks https://www.unicgrow.com";
		send_sms(get_user_phone($user_id),$mesgs);
		
		$title = "KYC Rejected ";
		$to = get_user_email($user_id);
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	}
	?> <script>window.location = "index.php?page=kyc";</script> <?php
}
if(isset($_POST['approve_allkyc'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$type_kyc','$remarks', 1 ,'$systems_date')";
	query_execute_sqli($sql);
	
	$sql = "UPDATE kyc SET mode_id = 1 , mode_photo = 1 , mode_chq = 1 , mode = 1
	WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
			
	$_SESSION['IMG_UPLOAD'] = "<B class='text-success'>KYC Rejected Successfully !!</B>";
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$username = get_user_name($user_id);
		$mesgs = "$remarks, Hi $username, Your KYC Approved by Admin. Thanks https://www.unicgrow.com";
		send_sms(get_user_phone($user_id),$mesgs);
		
		$title = "KYC Approved ";
		$to = get_user_email($user_id);
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	}
	?> <script>window.location = "index.php?page=kyc";</script> <?php
}
if(isset($_POST['cancel_kyc'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	switch($type_kyc){
		case 'id_proof' : $field = "mode_id"; break;
		case 'photo' : $field = "mode_photo"; break;
		case 'addrs_proof' : $field = "mode_chq"; break;
	}
	/*$sql_concat = "$type_kyc = ''";
	if($type_kyc == 'id_proof'){
		$sql_concat = "id_proof_front = '' , id_proof_back = ''";
	}
	if($type_kyc == 'photo'){
		$sql_concat = "photo = '' , signature = ''";
	}*/
	
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$type_kyc','$remarks', 0 ,'$systems_date')";
	query_execute_sqli($sql);
	
	//$sql = "UPDATE kyc SET $field = 4 , $sql_concat WHERE id = '$table_id' AND user_id = '$user_id'";
	$sql = "UPDATE kyc SET $field = 4 WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
			
	$_SESSION['IMG_UPLOAD'] = "<B class='text-success'>KYC Rejected Successfully !!</B>";
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$username = get_user_name($user_id);
		$mesgs = "$remarks, Hi $username, Your KYC Rejected by Admin. Thanks https://www.unicgrow.com";
		send_sms(get_user_phone($user_id),$mesgs);
		
		$title = "KYC Rejected ";
		$to = get_user_email($user_id);
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	}
	?> <script>window.location = "index.php?page=kyc";</script> <?php
}
if(isset($_POST['approve_kyc'])){
$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	switch($type_kyc){
		case 'id_proof' : $field = "mode_id"; break;
		case 'photo' : $field = "mode_photo"; break;
		case 'addrs_proof' : $field = "mode_chq"; break;
	}
	
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$type_kyc','$remarks', 1 ,'$systems_date')";
	query_execute_sqli($sql);
	
	query_execute_sqli("UPDATE kyc SET $field = 1 , mode = 1 WHERE id = '$table_id' AND user_id = '$user_id'");		
	
	$_SESSION['IMG_UPLOAD'] = "<B class='text-success'>KYC Approve Successfully !!</B>";
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$username = get_user_name($user_id);
		$mesgs = "$remarks, Hi $username, Your KYC Approved by Admin. Thanks https://www.unicgrow.com";
		send_sms(get_user_phone($user_id),$mesgs);
		
		$title = "KYC Approved ";
		$to = get_user_email($user_id);
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	}
	?> <script>window.location = "index.php?page=kyc";</script> <?php
}


$sql = "SELECT t1.*,t2.username,t2.phone_no,t2.f_name,t2.l_name FROM kyc t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
$qur_set_search ORDER BY t1.date DESC";

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
		<tr><th colspan="11">Total KYC : <?=$tot_rec?></th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">Name/Mobile</th>
			<th class="text-center">Id Proof</th>
			<th class="text-center">Address Proof</th>
			<!--<th class="text-center">Sign & Photo</th>-->
			<th class="text-center">Date</th>
			<th class="text-center">Approve Date</th>
			<!--<th class="text-center">Approve By</th>-->
			<th class="text-center">Status</th>
		</tr>
		</thead>
		<?php
		$pnums = ceil($totalrows/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		$sr_no = $starting_no;
		$rand = 1;
		$rand1 = 21;

		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que)){ 	
			$id = $row['id'];
			$member_id = $row['user_id'];
			$date = date('d/m/Y' , strtotime($row['date']));
			$id_proof = $row['id_proof'];
			$pan_card = $row['pan_card'];
			$pan_no = $row['pan_no'];
			$username = $row['username'];
			$mode = $row['mode'];
			$aadhar = $row['aadhar_no'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$addr_proof = $row['address_proof'];
			$phone_no = $row['phone_no'];
			
			
			$id_frnt = $row['id_proof_front'];
			$id_back = $row['id_proof_back'];
			$id_proof_no = $row['id_proof_no'];
			
			$add_proof_type = $row['add_proof_type'];
			$add_proof_no = $row['add_proof_no'];
			$add_proof = $row['add_proof'];
			
			$photo = $row['photo'];
			$sign = $row['signature'];
			
			$id_proof_type = explode("_",strtoupper($row['id_proof_type']));
			$id_type1 = $id_proof_type[0];
			$id_type2 = $id_proof_type[1];
			
			$mode_pan = $row['mode_pan'];
			$mode_id = $row['mode_id'];
			$mode_photo = $row['mode_photo'];
			$mode_chq = $row['mode_chq'];
			
			
			$pan_status = $idproof_status = $photo_status = $adr_status = "<B class='text-warning'>Not Uploaded</B>";
			/*$pan_img = $id_frnt_img = $id_back_img  = $photo_img = $sign_img = $add_img = 
			"<B class='text-warning'>Not Uploaded</B>";*/
			
			if($pan_card != ''){
				switch($mode_pan){
					case 0 : $pan_status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal2' data-id='$member_id' data-kyc_type='pan_card' data-table_id='$id'>Pending</button>";	
						$pan_img = "";
					break;
					case 1 : 
						$pan_status = "<B class='text-info'>Approved</B>";	
						$pan_img = "<img class='imgss' src='../images/mlm_kyc/$pan_card' width='30' />";
					break;
					case 4 : $pan_status = "<B class='text-danger'>Rejected</B>"; $pan_img = "";	break;
				}
			}
			else{
				if($mode_pan == 4){ $pan_status = "<B class='text-danger'>Rejected</B>"; }
			}
			
			if($id_frnt != ''){
				switch($mode_id){
					case 0 : $idproof_status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal2' data-id='$member_id' data-kyc_type='id_proof' data-table_id='$id'>Pending</button>";	
						$id_frnt_img = $id_back_img = "";
					break;
					case 1 : 
						$idproof_status = "<B class='text-info'>Approved</B>";	
						$id_frnt_img = "<img class='imgss' src='../images/mlm_kyc/$id_frnt' width='30' />";
						$id_back_img  = "<img class='imgss' src='../images/mlm_kyc/$id_back' width='30' />";
					break;
					case 4 : $idproof_status = "<B class='text-danger'>Rejected</B>";	
						$id_frnt_img = $id_back_img = "";
					break;
				}
			}
			else{
				if($mode_id == 4){ $idproof_status = "<B class='text-danger'>Rejected</B>"; }
			}
			
			if($photo != '' and $sign != ''){
				switch($mode_photo){
					case 0 : $photo_status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal2' data-id='$member_id' data-kyc_type='photo' data-table_id='$id'>Pending</button>";	
						$photo_img = $sign_img = "";
					break;
				
					case 1 : 
						$photo_status = "<B class='text-info'>Approved</B>";	
						$photo_img = "<img class='imgss' src='../images/mlm_kyc/$photo' width='30' />";
						$sign_img = "<img class='imgss' src='../images/mlm_kyc/$sign' width='30' />";
					break;
					
					case 4 : $photo_status = "<B class='text-danger'>Rejected</B>"; 
						$photo_img = $sign_img = "";	
					break;
				}
			}
			else{
				if($mode_photo == 4){ $photo_status = "<B class='text-danger'>Rejected</B>"; }
			}
			
			if($add_proof != ''){
				switch($mode_chq){
					case 0 : $adr_status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal2' data-id='$member_id' data-kyc_type='addrs_proof' data-table_id='$id'>Pending</button>";	
					 	$add_img = "";
					break;
					
					case 1 : 
						$adr_status = "<B class='text-info'>Approved</B>";	
						$add_img = "<img class='imgss' src='../images/mlm_kyc/$add_proof' width='30' />";
					break;
					case 4 : $adr_status = "<B class='text-danger'>Rejected</B>"; $add_img = "";	break;
				}
			}
			else{
				if($mode_chq == 4){ $adr_status = "<B class='text-danger'>Rejected</B>"; }
			}
			
			
			//$status = "<B class='text-warning'>Pending</B>";
			//$status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal3' data-id='$member_id' data-btn_type='viewall' data-table_id='$id'>View All</button>";
			$status = "<form method='post' action='index.php?page=all_kyc' target='_blank'>
				<input type='hidden' name='type_kyc' value='all' />
				<input type='hidden' name='table_id' value='$id' />
				<input type='hidden' name='user_id' value='$member_id' />
				<input type='submit' name='view_all' value='View All' class='btn btn-success btn-xs' />
			</form>";
			if($mode_pan == 4 and $mode_id == 4 and $mode_photo == 4 and $mode_chq == 4){
				$status = "<B class='text-danger'>Rejected</B>";
			}
			/*elseif($mode_pan == 1 and $mode_id == 1 and $mode_photo == 1 and $mode_chq == 1){
				$status = "<B class='text-info'>Approved</B>";
			}*/
			
			$kyc_date = get_user_kyc_approved_date($member_id);
			
			//$admin_id = get_kyc_approve_byadmin($member_id);
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td class="text-left"><B>Name : </B><?=$name?><br /><B>Mobile : </B><?=$phone_no?></td>
				
				<td>
					<?=$id_frnt_img?>
					<?=$id_back_img?>
					<?=$idproof_status?>
				</td>
				<td>
					<?=$add_img?>
					<?=$adr_status?>
				</td>
				<!--<td>
					<?=$photo_img?>
					<?=$sign_img?>
					<?=$photo_status?>
				</td>-->
				<td><?=$date?></td>
				<td><?=$kyc_date?></td>
				<!--<td><?=$admin_id?></td>-->
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
			$rand++;
			$rand1++;
		} ?>
	</table> <?PHP
	pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val);
}
else{ echo "<B class='text-danger'>There are no information to show!</B>";  }

include("modal.php");
?>
<script>
$(document).ready(function(){
	$('#myModal2').on('show.bs.modal', function (e) {
		var id = $(e.relatedTarget).data('id');
		var kyc_type = $(e.relatedTarget).data('kyc_type');
		var table_id = $(e.relatedTarget).data('table_id');
        $.ajax({
            type : 'post',
            url : 'kyc_status.php',
            data :  {'user_id': id, 'kyc_type': kyc_type, 'table_id': table_id},
            success : function(data){
				$('.show_kyc').html(data);
			}
		});
	});
	
	$('#myModal3').on('show.bs.modal', function (e) {
		var id = $(e.relatedTarget).data('id');
		var table_id = $(e.relatedTarget).data('table_id');
		var viewall = $(e.relatedTarget).data('viewall');
        $.ajax({
            type : 'post',
            url : 'kyc_all.php',
            data :  {'user_id': id, 'table_id': table_id, 'viewall': viewall},
            success : function(data){
				$('.show_allkyc').html(data);
			}
		});
	});
});
</script>
<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">KYC Status</h4>
				<small class="font-bold">Approve OR Rejected KYC</small>
			</div>
			<div class="modal-body">
				<div class="show_kyc"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-primary">Save changes</button>-->
			</div>
		</div>
	</div>
</div>

<div class="modal inmodal" id="myModal3" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content animated flipInY">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">KYC Status</h4>
				<small class="font-bold">Approve OR Rejected KYC</small>
			</div>
			<div class="modal-body">
				<div class="show_allkyc"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
				<!--<button type="button" class="btn btn-primary">Save changes</button>-->
			</div>
		</div>
	</div>
</div>

<?php
/*function get_kyc_approve_byadmin($user_id){
	$sql = "SELECT t2.username FROM panel_work_history t1 
	LEFT JOIN admin t2 ON t1.member_id = t2.id_user
	LEFT JOIN kyc_history t3 ON t1.member_id = t3.user_id
	WHERE t1.panel_id = 2 AND t1.post_data LIKE '%$user_id%' AND (t3.remarks = 'approve' OR t3.remarks= 'APPROVED' 
	OR t3.remarks = 'APPROVE' OR t3.remarks = 'ok' OR t3.remarks = 'OK')";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	
	$username = "<span class='text-danger'>XXXXXXX</span>";
	if($result != ''){
		$username = $result;
	}
	return $username;
}*/


?>