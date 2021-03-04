<?php
include('../../security_web_validation.php');
include("../function/functions.php");
include("../function/setting.php");
include("../function/send_mail.php");


$newp = $_GET['p'];
$plimit = "20";
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


if(isset($_SESSION['IMG_UPLOAD'])){
	echo $_SESSION['IMG_UPLOAD'];
	unset( $_SESSION['IMG_UPLOAD']);
}


$qur_set_search = '';
if(count($_GET) == 1){
	unset($_SESSION['SESS_search_date'],$_SESSION['SESS_USERNAME']);
}
else{
	$_POST['Search'] = '1';
	$_POST['search_date'] = $_SESSION['SESS_search_date'];
	$_POST['search_username'] = $_SESSION['SESS_USERNAME'];
}
if(isset($_POST['Search']))
{
	if($_POST['search_date'] != '')
	$_SESSION['SESS_search_date'] = $search_date = date('Y-m-d', strtotime($_POST['search_date']));
	$_SESSION['SESS_USERNAME'] = $search_username = $_POST['search_username'];
	
	$search_id = get_new_user_id($search_username);
	
	if($search_username !=''){
		$qur_set_search = " AND t1.user_id = '$search_id' ";
	}
	if($search_date != ''){
		$qur_set_search = "AND t1.date = '$search_date' ";
	}
}

?>
<form method="post" action="index.php?page=kyc">
<table class="table table-bordered">
	<tr>
		<td><input type="text" name="search_username" placeholder="Search By Username" class="form-control" /></td>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group date">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					<input type="text" name="search_date" placeholder="Search By Date" class="form-control" />
				</div>
			</div>
		</td>
		<th><input type="submit" value="Submit" name="Search" class="btn btn-info"></th>
	</tr>
</table>
</form>	

<?php
if(isset($_POST['cancel_allkyc']))
{
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$type_kyc','$remarks', 0 ,'$systems_date')";
	query_execute_sqli($sql);
	
	$sql = "UPDATE kyc SET mode_id = 4 , mode_photo = 4 , mode_pan = 4 , mode_chq = 4 , mode = 4 , 
	chq_passbook = '' , pan_card = '' , id_proof_front = '' , id_proof_back = '' , photo = '' , signature = ''
	WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
			
	$_SESSION['IMG_UPLOAD'] = "<B class='text-success'>KYC Cancel Successfully !!</B>";
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$username = get_user_name($user_id);
		$mesgs = "Hi $username, Your KYC Cancelled by Admin. Thanks https://www.unicgrow.com";
		send_sms(get_user_phone($user_id),$mesgs);
		
		$title = "KYC Cancelled ";
		$to = get_user_email($user_id);
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	}
	?> <script>window.location = "index.php?page=kyc";</script> <?php
}
if(isset($_POST['approve_allkyc']))
{
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$type_kyc','$remarks', 1 ,'$systems_date')";
	query_execute_sqli($sql);
	
	$sql = "UPDATE kyc SET mode_id = 1 , mode_photo = 1 , mode_pan = 1 , mode_chq = 1 , mode = 1
	WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
			
	$_SESSION['IMG_UPLOAD'] = "<B class='text-success'>KYC Cancel Successfully !!</B>";
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$username = get_user_name($user_id);
		$mesgs = "Hi $username, Your KYC Approved by Admin. Thanks https://www.unicgrow.com";
		send_sms(get_user_phone($user_id),$mesgs);
		
		$title = "KYC Approved ";
		$to = get_user_email($user_id);
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	}
	?> <script>window.location = "index.php?page=kyc";</script> <?php
}
if(isset($_POST['cancel_kyc']))
{
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	switch($type_kyc){
		case 'id_proof' : $field = "mode_id"; break;
		case 'photo' : $field = "mode_photo"; break;
		case 'pan_card' : $field = "mode_pan"; break;
		case 'chq_passbook' : $field = "mode_chq"; break;
	}
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$type_kyc','$remarks', 0 ,'$systems_date')";
	query_execute_sqli($sql);
	
	$sql = "UPDATE kyc SET $field = 4 , $type_kyc = '' , mode = 4 WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
			
	$_SESSION['IMG_UPLOAD'] = "<B class='text-success'>KYC Cancel Successfully !!</B>";
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$username = get_user_name($user_id);
		$mesgs = "Hi $username, Your KYC Cancelled by Admin. Thanks https://www.unicgrow.com";
		send_sms(get_user_phone($user_id),$mesgs);
		
		$title = "KYC Cancelled ";
		$to = get_user_email($user_id);
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	}
	?> <script>window.location = "index.php?page=kyc";</script> <?php
}
if(isset($_POST['approve_kyc']))
{
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	switch($type_kyc){
		case 'id_proof' : $field = "mode_id"; break;
		case 'photo' : $field = "mode_photo"; break;
		case 'pan_card' : $field = "mode_pan"; break;
		case 'chq_passbook' : $field = "mode_chq"; break;
	}
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$type_kyc','$remarks', 1 ,'$systems_date')";
	query_execute_sqli($sql);
	
	query_execute_sqli("UPDATE kyc SET $field = 1 , mode = 1 WHERE id = '$table_id' AND user_id = '$user_id'");		
	$_SESSION['IMG_UPLOAD'] = "<B class='text-success'>KYC Approve Successfully !!</B>";
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$username = get_user_name($user_id);
		$mesgs = "Hi $username, Your KYC Approved by Admin. Thanks https://www.unicgrow.com";
		send_sms(get_user_phone($user_id),$mesgs);
		
		$title = "KYC Approved ";
		$to = get_user_email($user_id);
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	}
	?> <script>window.location = "index.php?page=kyc";</script> <?php
}


$sql = "SELECT t1.*,t2.username,t2.phone_no FROM kyc t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.chq_passbook <> '' AND t1.pan_card <> '' AND t1.photo <> '' AND t1.id_proof_front <> '' 
AND t1.id_proof_back <> '' AND t1.signature <> '' $qur_set_search ORDER BY t1.date DESC";

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
			<th class="text-center">User ID</th>
			<th class="text-center">Name/Mobile</th>
			<th class="text-center">Sign & Photo</th>
			<th class="text-center">Id Proof</th>
			<th class="text-center">PAN Card</th>
			<th class="text-center">Bank Passbook</th>
			<th class="text-center" width="30%">Bank Details</th>
			<th class="text-center">Date</th>
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
		while($row = mysqli_fetch_array($que))
		{ 	
			$id = $row['id'];
			$member_id = $row['user_id'];
			$date = date('d/m/Y' , strtotime($row['date']));
			$id_proof = $row['id_proof'];
			$pan_card = $row['pan_card'];
			$pan_no = $row['pan_no'];
			$username = $row['username'];
			$mode = $row['mode'];
			$aadhar = $row['aadhar_no'];
			$name = ucwords($row['name']);
			$addr_proof = $row['address_proof'];
			$phone_no = $row['phone_no'];
			$id_proof_type = explode("_",strtoupper($row['id_proof_type']));
			
			$chq_pas_b = $row['chq_passbook'];
			$id_frnt = $row['id_proof_front'];
			$id_back = $row['id_proof_back'];
			$photo = $row['photo'];
			$sign = $row['signature'];
			
			$id_type1 = $id_proof_type[0];
			$id_type2 = $id_proof_type[1];
			
			$bank_info = "<B>PAN No. :</B> ".$row['pan_no']."<br> <B>ID Proof :</B> ".$id_type1." ".$id_type2. "<br> <B>A/C No. :</B> ".$row['bank_ac']."<br><B>Bank Name :</B> ".$row['bank']." <br><B>Branch :</B> ".$row['branch'].'<br /><B>IFSC Code :</B> '.$row['ifsc'];
			
			$mode_pan = $row['mode_pan'];
			$mode_id = $row['mode_id'];
			$mode_photo = $row['mode_photo'];
			$mode_chq = $row['mode_chq'];
			
			
			$pan_status = $idproof_status = $photo_status = $chq_status = "<B class='text-warning'>Not Uploaded</B>";
			$pan_img = $id_frnt_img = $id_back_img  = $photo_img = $sign_img = $chq_pas_img = 
			"<B class='text-warning'>Not Uploaded</B>";
			
			if($pan_card != ''){
				$pan_img = "<img id='$rand' src='../images/mlm_kyc/$pan_card' width='30' />";
				switch($mode_pan){
					case 0 : $pan_status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal2' data-id='$member_id' data-kyc_type='pan_card' data-table_id='$id'>Pending</button>";	
					break;
					case 1 : $pan_status = "<B class='text-info'>Approved</B>";	break;
					case 4 : $pan_status = "<B class='text-danger'>Cancelled</B>";	break;
				}
			}
			else{
				if($mode_pan == 4){ $pan_status = "<B class='text-danger'>Cancelled</B>"; }
			}
			
			if($id_frnt != '' and $id_back != ''){
				$id_frnt_img = "<img id='$rand' src='../images/mlm_kyc/$id_frnt' width='30' />";
				$id_back_img  = "<img id='$rand' src='../images/mlm_kyc/$id_back' width='30' />";
				switch($mode_id){
					case 0 : $idproof_status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal2' data-id='$member_id' data-kyc_type='id_proof' data-table_id='$id'>Pending</button>";	
					break;
					case 1 : $idproof_status = "<B class='text-info'>Approved</B>";	break;
					case 4 : $idproof_status = "<B class='text-danger'>Cancelled</B>";	break;
				}
			}
			else{
				if($mode_id == 4){ $idproof_status = "<B class='text-danger'>Cancelled</B>"; }
			}
			
			if($photo != '' and $sign != ''){
				$photo_img = "<img id='$rand' src='../images/mlm_kyc/$photo' width='30' />";
				$sign_img = "<img id='$rand' src='../images/mlm_kyc/$sign' width='30' />";
				switch($mode_photo){
					case 0 : $photo_status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal2' data-id='$member_id' data-kyc_type='photo' data-table_id='$id'>Pending</button>";	
					break;
					case 1 : $photo_status = "<B class='text-info'>Approved</B>";	break;
					case 4 : $photo_status = "<B class='text-danger'>Cancelled</B>";	break;
				}
			}
			else{
				if($mode_photo == 4){ $photo_status = "<B class='text-danger'>Cancelled</B>"; }
			}
			
			if($chq_pas_b != ''){
				$chq_pas_img = "<img id='$rand' src='../images/mlm_kyc/$chq_pas_b' width='30' />";
				switch($mode_chq){
					case 0 : $chq_status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal2' data-id='$member_id' data-kyc_type='chq_passbook' data-table_id='$id'>Pending</button>";	
					break;
					case 1 : $chq_status = "<B class='text-info'>Approved</B>";	break;
					case 4 : $chq_status = "<B class='text-danger'>Cancelled</B>";	break;
				}
			}
			else{
				if($mode_chq == 4){ $chq_status = "<B class='text-danger'>Cancelled</B>"; }
			}
			
			
			//$status = "<B class='text-warning'>Pending</B>";
			$status = "<button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal3' data-id='$member_id' data-btn_type='viewall' data-table_id='$id'>View All</button>";
			if($mode_pan == 4 and $mode_id == 4 and $mode_photo == 4 and $mode_chq == 4){
				$status = "<B class='text-danger'>Cancelled</B>";
			}
			elseif($mode_pan == 1 and $mode_id == 1 and $mode_photo == 1 and $mode_chq == 1){
				$status = "<B class='text-info'>Approved</B>";
			}
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td class="text-left">
					<B>Name : </B><?=$name?><br /><B>Mobile : </B><?=$phone_no?>
				</td>
				<td>
					<?=$photo_img?>
					<?=$sign_img?>
					<?=$photo_status?>
				</td>
				<td>
					<?=$id_frnt_img?>
					<?=$id_back_img?>
					<?=$idproof_status?>
				</td>
				<td>
					<?=$pan_img?>
					<?=$pan_status?>
				</td>
				
				
				<td>
					<?=$chq_pas_img?>
					<?=$chq_status?>
				</td>
				<td class="text-left"><?=$bank_info?></td>
				<td><?=$date?></td>
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

//include("modal.php");
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
				<small class="font-bold">Approve OR Cancel KYC</small>
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
				<small class="font-bold">Approve OR Cancel KYC</small>
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
