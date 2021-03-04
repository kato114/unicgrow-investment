<?php
session_start();
include('../../security_web_validation.php');
include("../function/functions.php");
include("../function/setting.php");
include("../function/send_mail.php");


if(isset($_POST['cancel_allkyc'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	$sql = "SELECT * FROM kyc WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){ 	
		$id_frnt = $row['id_proof_front'];
		$id_back = $row['id_proof_back'];
		$photo = $row['photo'];
		$sign = $row['signature'];
		$id_proof_type = $row['id_proof_type'];
		$id_proof_no = $row['id_proof_no'];
		$add_proof_type = $row['add_proof_type'];
		$add_proof_no = $row['add_proof_no'];
		$add_proof = $row['add_proof'];
	}
	
	$sql_img = $id_frnt.",".$id_back.",".$photo.",".$sign.",".$add_proof;
	
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$sql_img','$remarks', 0 ,'$systems_date')";
	query_execute_sqli($sql);
	
	$sql = "UPDATE kyc SET mode_id = 4, mode_photo = 4, mode_chq = 4, mode = 4, id_proof_type = '', id_proof_no ='',
	add_proof_type = '', add_proof_no = '', add_proof = '', id_proof_front = '', id_proof_back = '', photo = '', 
	signature = '' WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
	
	query_execute_sqli("UPDATE kyc SET proceed = 0 WHERE user_id = '$user_id'");
			
	$_SESSION['IMG_UPLOAD'] = "<B class='text-success'>KYC Rejected Successfully !!</B>";
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$username = get_user_name($user_id);
		$mesgs = "$remarks, Hi $username, Your KYC Rejected by Admin. Thanks https://www.unicgrow.com";
		send_sms(get_user_phone($user_id),$mesgs);
		
		$title = "KYC Rejected ";
		$to = get_user_email($user_id);
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	}
	?> <script>window.location = "index.php?page=all_kyc";</script> <?php
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
	
	$sql = "UPDATE kyc SET mode_id = 1 , mode_photo = 1 ,mode_chq = 1 , mode = 1
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
	?> <script>window.location = "index.php?page=all_kyc";</script> <?php
}
if(isset($_POST['cancel_kyc'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	$sql = "SELECT * FROM kyc WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){ 	
		$id_frnt = $row['id_proof_front'];
		$id_back = $row['id_proof_back'];
		$photo = $row['photo'];
		$sign = $row['signature'];
		$id_proof_type = $row['id_proof_type'];
		$id_proof_no = $row['id_proof_no'];
		$add_proof_type = $row['add_proof_type'];
		$add_proof_no = $row['add_proof_no'];
		$add_proof = $row['add_proof'];
	}
	
	$sql_concat = "$type_kyc = ''";
	switch($type_kyc){
		case 'id_proof' : $field = "mode_id"; $sql_img = $id_frnt.",".$id_back ; 
			$sql_concat = "id_proof_front = '' , id_proof_back = '', id_proof_type = '', id_proof_no = ''";
		break;
		case 'photo' : $field = "mode_photo"; $sql_img = $photo.",".$sign ; 
			$sql_concat = "photo = '' , signature = ''";
		break;
		case 'address_proof' : $field = "mode_chq"; $sql_img = $add_proof; 
			$sql_concat = "add_proof = '', add_proof_type = '', add_proof_no = ''";
		break;
	}
	
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$sql_img','$remarks', 0 ,'$systems_date')";
	query_execute_sqli($sql);
	
	//$sql = "UPDATE kyc SET $field = 4 , $sql_concat WHERE id = '$table_id' AND user_id = '$user_id'";
	$sql = "UPDATE kyc SET $field = 4, $sql_concat WHERE id = '$table_id' AND user_id = '$user_id'";
	query_execute_sqli($sql);
	
	$sql = "UPDATE kyc SET proceed = 
		CASE 
			WHEN proceed = 5 THEN proceed-3 
			WHEN proceed = 6 THEN proceed-4
			ELSE proceed-1 END 
	WHERE user_id = '$user_id'";
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
	?> <script>//window.location = "index.php?page=all_kyc";</script> <?php
}
if(isset($_POST['approve_kyc'])){
	$table_id = $_POST['table_id'];
	$user_id = $_POST['user_id'];
	$type_kyc = $_POST['type_kyc'];
	$remarks = $_POST['remarks'];
	
	$sql = "SELECT * FROM kyc WHERE user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){ 	
		$add_proof = $row['add_proof'];
		$id_frnt = $row['id_proof_front'];
		$id_back = $row['id_proof_back'];
		$photo = $row['photo'];
		$sign = $row['signature'];
		$pan_card = $row['pan_card'];
	}
	
	switch($type_kyc){
		case 'id_proof' : $field = "mode_id"; $sql_img = $id_frnt.",".$id_back ; break;
		case 'photo' : $field = "mode_photo"; $sql_img = $photo.",".$sign ; break;
		case 'address_proof' : $field = "mode_chq"; $sql_img = $add_proof; break;
	}
	
	$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
	VALUES ('$table_id','$user_id','$sql_img','$remarks', 1 ,'$systems_date')";
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
	?> <script>window.location = "index.php?page=all_kyc";</script> <?php
}

if(isset($_POST['user_id'])){
	unset($_SESSION['user_id'],$_SESSION['kyc_type'],$_SESSION['table_id']);
}
if(!isset($_SESSION['user_id'])){
	$_SESSION['user_id'] = $_POST['user_id'];
	$_SESSION['kyc_type'] = $_POST['kyc_type'];
	$_SESSION['table_id'] = $_POST['table_id'];
}

$user_id = $_SESSION['user_id'];
$kyc_type = $_SESSION['kyc_type'];
$table_id = $_SESSION['table_id'];

$field = $kyc_type;
if($kyc_type == 'id_proof_front'){
	$field = "id_proof_front,id_proof_back";
}
if($kyc_type == 'photo'){
	$field = "photo,signature";
}

$sql = "SELECT * FROM kyc WHERE user_id = '$user_id'";
$query = query_execute_sqli($sql);

$kyc_docs1 = $kyc_docs2 = "";
while($row = mysqli_fetch_array($query)){ 	
	$id_frnt = $row['id_proof_front'];
	$id_back = $row['id_proof_back'];
	$photo = $row['photo'];
	$sign = $row['signature'];
	
	$id_proof_no = $row['id_proof_no'];
	$id_proof_type = explode("_",strtoupper($row['id_proof_type']));
	$id_type1 = $id_proof_type[0];
	$id_type2 = $id_proof_type[1];
	
	$add_proof_type = $row['add_proof_type'];
	$add_proof_no = $row['add_proof_no'];
	$add_proof = $row['add_proof'];
	
	$mode_pan = $row['mode_pan'];
	$mode_id = $row['mode_id'];
	$mode_photo = $row['mode_photo'];
	$mode_chq = $row['mode_chq'];
} 

$adrs_img = $photo_img = $sign_img = $id_frnt_img = $id_back_img = "<br /><B class='text-danger'>Not Uploaded</B>";

if($add_proof != ''){
	$adrs_img = "<img src='../images/mlm_kyc/$add_proof' class='img-responsive' />";
}
if($photo != '' and $sign != ''){
	$photo_img = "<img src='../images/mlm_kyc/$photo'' class='img-responsive' />";
	$sign_img = "<img src='../images/mlm_kyc/$sign'' class='img-responsive' />";
}
if($id_frnt != ''){
	$id_frnt_img = "<img src='../images/mlm_kyc/$id_frnt'' class='img-responsive' />";
	$id_back_img = "<img src='../images/mlm_kyc/$id_back'' class='img-responsive' />";
}
/*if($id_frnt != '' and $id_back != ''){
	$id_frnt_img = "<img src='../images/mlm_kyc/$id_frnt'' class='img-responsive' />";
	$id_back_img = "<img src='../images/mlm_kyc/$id_back'' class='img-responsive' />";
}*/



switch($mode_photo){
	case 0 : $photo_btn = "<input type='submit' name='approve_kyc' value='Approve' class='btn btn-info btn-sm' onclick = 'javascript:return confirm(&quot; Are You Sure? You want to Approve KYC !! &quot;);' />
		<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' onclick = 'javascript:return confirm(&quot; Are You Sure? You want to Reject KYC !! &quot;);' />";	
	$remrk_photo="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />";
	break;
	//case 1 : $photo_btn = "<B class='text-info'>Approved</B>";  $remrk_photo = ""; break;
	
	case 1 : $photo_btn = "<B class='text-info'>Approved</B>
		<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' onclick = 'javascript:return confirm(&quot; Are You Sure? You want to Reject KYC !! &quot;);' />";
	$remrk_photo="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />";
	break;
	case 4 : $photo_btn = "<B class='text-danger'>Rejected</B>"; $remrk_photo = ""; break;
}

switch($mode_id){
	case 0 : $idproof_btn = "<input type='submit' name='approve_kyc' value='Approve' class='btn btn-info btn-sm' onclick = 'javascript:return confirm(&quot; Are You Sure? You want to Approve KYC !! &quot;);' />
		<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' onclick = 'javascript:return confirm(&quot; Are You Sure? You want to Reject KYC !! &quot;);' />";
		$remrk_id="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />";	
	break;
	case 1 : $idproof_btn = "<B class='text-info'>Approved</B>
		<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' onclick = 'javascript:return confirm(&quot; Are You Sure? You want to Reject KYC !! &quot;);' />";
		$remrk_id="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />"; 
	break;
	case 4 : $idproof_btn = "<B class='text-danger'>Rejected</B>"; $remrk_id = ""; break;
}

switch($mode_chq){
	case 0 : $img_btn = "<input type='submit' name='approve_kyc' value='Approve' class='btn btn-info btn-sm' onclick = 'javascript:return confirm(&quot; Are You Sure? You want to Approve KYC !! &quot;);' />
		<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' onclick = 'javascript:return confirm(&quot; Are You Sure? You want to Reject KYC !! &quot;);' />";	
		$remrk_adr="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />";
	break;
	case 1 : $img_btn = "<B class='text-info'>Approved</B>
			<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' onclick = 'javascript:return confirm(&quot; Are You Sure? You want to Reject KYC !! &quot;);' />";
		$remrk_adr="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />";
	break;
	case 4 : $img_btn = "<B class='text-danger'>Rejected</B>"; $remrk_adr = ""; break;
}

$all_btn = $remark_all = "";
if($mode_id == 0 and $mode_photo == 0 and $mode_chq == 0){
	$remark_all = "<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea>";
	$all_btn = "<input type='submit' name='approve_allkyc' value='Approve All' class='btn btn-info btn-sm' onclick='javascript:return confirm(&quot; Are You Sure? You want to Approve All KYC Documents !! &quot;);' />
		<input type='submit' name='cancel_allkyc' value='Reject All' class='btn btn-danger btn-sm' onclick='javascript:return confirm(&quot; Are You Sure? You want to Reject All KYC Documents !! &quot;);' />"; 
}

if($mode_pan == 1 and $mode_id == 1 and $mode_photo == 1 and $mode_chq == 1){
	$remark_all = "<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea>";
	$all_btn = "<input type='submit' name='cancel_allkyc' value='Reject All' class='btn btn-danger btn-sm' onclick='javascript:return confirm(&quot; Are You Sure? You want to Reject All KYC Documents !! &quot;);' />"; 
}

?>


<div class="col-sm-12 text-right">
	<button type="button" id="close" onclick="window.close()" class='btn btn-danger btn-sm'>
		<i class="fa fa-reply"></i> Close Window
	</button>
</div>
<div class="col-sm-12">&nbsp;</div>
<!--<div class="col-sm-12">
	<div class="ibox float-e-margins">
		<div class="ibox-title"><h5>Photo</h5></div>
		<div class="ibox-content">
			<div class="row">
				<div class="col-sm-2"><div class="form-group"><B>Name :</B></div></div>
				<div class="col-sm-10"><?=$name?></div>
			</div>
			<form method="post" action="index.php?page=all_kyc">
				<input type="hidden" name="type_kyc" value="photo" />
				<input type="hidden" name="table_id" value="<?=$table_id?>" />
				<input type="hidden" name="user_id" value="<?=$user_id?>" />
				<div class="row">
					<div class="col-sm-12"><?=$photo_img?></div>
					<div class="col-sm-12"><?=$sign_img?></div>
					<div class="col-sm-12">&nbsp;</div>
					<div class="col-sm-12">
						<?=$remrk_photo?>
						<div class="text-center"><?=$photo_btn?></div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>-->
<div class="col-sm-12">
	<div class="ibox float-e-margins">
		<div class="ibox-title"><h5>ID Proof</h5></div>
		<div class="ibox-content">
			<div class="row">
				<div class="col-sm-2"><div class="form-group"><B>ID Proof :</B></div></div>
				<div class="col-sm-10"><?=$id_type1." ".$id_type2?></div>
			</div>
			<div class="row">
				<div class="col-sm-2"><div class="form-group"><B>ID No. :</B></div></div>
				<div class="col-sm-10"><?=$id_proof_no?></div>
			</div>
			<form method="post" action="index.php?page=all_kyc">
				<input type="hidden" name="type_kyc" value="id_proof" />
				<input type="hidden" name="table_id" value="<?=$table_id?>" />
				<input type="hidden" name="user_id" value="<?=$user_id?>" />
				<div class="row">
					<div class="col-sm-12"><?=$id_frnt_img?></div>
					<div class="col-sm-12">&nbsp;</div>
					<div class="col-sm-12"><?=$id_back_img?></div>
					
					<div class="col-sm-12">&nbsp;</div>
					<div class="col-sm-12">
						<?=$remrk_id?>
						<div class="text-center"><?=$idproof_btn?></div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!--<div class="col-sm-12">
	<div class="ibox float-e-margins">
		<div class="ibox-title"><h5>PAN Card & Sign</h5></div>
		<div class="ibox-content">
			<div class="row">
				<div class="col-sm-2"><div class="form-group"><B>PAN No. :</B></div></div>
				<div class="col-sm-10"><?=$pan_no?></div>
			</div>
			<form method="post" action="index.php?page=all_kyc">
				<input type="hidden" name="type_kyc" value="pan_card" />
				<input type="hidden" name="table_id" value="<?=$table_id?>" />
				<input type="hidden" name="user_id" value="<?=$user_id?>" />
				<div class="row">
					<div class="col-sm-12"><?=$pan_img?></div>
					<div class="col-sm-12">&nbsp;</div>
					<div class="col-sm-12"><?=$sign_img?></div>
					<div class="col-sm-12">&nbsp;</div>
					<?=$remrk_pan?>
					<div class="text-center"><?=$pan_btn?></div>
				</div>
			</form>
		</div>
	</div>
</div>-->
<div class="col-sm-12">
	<div class="ibox float-e-margins">
		<div class="ibox-title"><h5>Address Proof</h5></div>
		<div class="ibox-content">
			<div class="row">
				<div class="col-sm-3"><div class="form-group"><B>Address Proof Type :</B></div></div>
				<div class="col-sm-9"><?=$add_proof_type?></div>
			</div>
			<div class="row">
				<div class="col-sm-3"><div class="form-group"><B>Address Proof No. :</B></div></div>
				<div class="col-sm-9"><?=$add_proof_no?></div>
			</div>
			<form method="post" action="index.php?page=all_kyc">
				<input type="hidden" name="type_kyc" value="address_proof" />
				<input type="hidden" name="table_id" value="<?=$table_id?>" />
				<input type="hidden" name="user_id" value="<?=$user_id?>" />
				
				<?=$adrs_img?><br /><br />
				<?=$remrk_adr?>
				<div class="text-center"><?=$img_btn?></div>
			</form>
		</div>
	</div>
</div>


<div class="col-sm-12">&nbsp;</div>
<div class="col-sm-12">
	<form method="post" action="index.php?page=all_kyc">
		<input type="hidden" name="type_kyc" value="all" />
		<input type="hidden" name="table_id" value="<?=$table_id?>" />
		<input type="hidden" name="user_id" value="<?=$user_id?>" />
		
		<?=$remark_all?><br />
		<div class="text-center"><?=$all_btn?></div>
	</form>
</div>

<style>
.ibox{
	border:1px solid #DDD;
}
</style>
