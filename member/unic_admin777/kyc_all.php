<?php
include("../config.php");

$user_id = $_REQUEST['user_id'];
$kyc_type = $_REQUEST['kyc_type'];
$table_id = $_REQUEST['table_id'];


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
	$chq_pas_b = $row['chq_passbook'];
	$id_frnt = $row['id_proof_front'];
	$id_back = $row['id_proof_back'];
	$photo = $row['photo'];
	$sign = $row['signature'];
	$pan_card = $row['pan_card'];
	$pan_no = $row['pan_no'];
	$bank_ac = $row['bank_ac'];
	$bank = $row['bank'];
	$branch = $row['branch'];
	$ifsc = $row['ifsc'];
	$name = $row['name'];
	$id_proof_no = $row['id_proof_no'];
	$id_proof_type = explode("_",strtoupper($row['id_proof_type']));
	$id_type1 = $id_proof_type[0];
	$id_type2 = $id_proof_type[1];
	
	$mode_pan = $row['mode_pan'];
	$mode_id = $row['mode_id'];
	$mode_photo = $row['mode_photo'];
	$mode_chq = $row['mode_chq'];
} 

$pan_img = $bankpass_img = $photo_img = $sign_img = $id_frnt_img = $id_back_img = "<br /><B class='text-danger'>Not Uploaded</B>";

if($pan_card != ''){
	$pan_img = "<img src='../images/mlm_kyc/$pan_card' width='50%' />";
}
if($pan_card != ''){
	$bankpass_img = "<img src='../images/mlm_kyc/$chq_pas_b' width='50%' />";
}
if($photo != '' and $sign != ''){
	$photo_img = "<img src='../images/mlm_kyc/$photo' width='50%' />";
	$sign_img = "<img src='../images/mlm_kyc/$sign' width='50%' />";
}
if($id_frnt != '' and $id_back != ''){
	$id_frnt_img = "<img src='../images/mlm_kyc/$id_frnt' width='50%' />";
	$id_back_img = "<img src='../images/mlm_kyc/$id_back' width='50%' />";
}



switch($mode_photo){
	case 0 : $photo_btn = "<input type='submit' name='approve_kyc' value='Approve' class='btn btn-info btn-sm' />
		<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' />";	
	$remrk_photo="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />";
	break;
	case 1 : $photo_btn = "<B class='text-info'>Approved</B>";  $remrk_photo = ""; break;
	case 4 : $photo_btn = "<B class='text-danger'>Rejected</B>"; $remrk_photo = ""; break;
}
switch($mode_id){
	case 0 : $idproof_btn = "<input type='submit' name='approve_kyc' value='Approve' class='btn btn-info btn-sm' />
		<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' />";
		$remrk_id="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />";	
	break;
	case 1 : $idproof_btn = "<B class='text-info'>Approved</B>"; $remrk_id = ""; break;
	case 4 : $idproof_btn = "<B class='text-danger'>Rejected</B>"; $remrk_id = ""; break;
}
switch($mode_pan){
	case 0 : $pan_btn = "<input type='submit' name='approve_kyc' value='Approve' class='btn btn-info btn-sm' />
		<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' />";	
		$remrk_pan="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />";
	break;
	case 1 : $pan_btn = "<B class='text-info'>Approved</B>"; $remrk_pan = "";	break;
	case 4 : $pan_btn = "<B class='text-danger'>Rejected</B>"; $remrk_pan = ""; break;
}
switch($mode_chq){
	case 0 : $chq_btn = "<input type='submit' name='approve_kyc' value='Approve' class='btn btn-info btn-sm' />
		<input type='submit' name='cancel_kyc' value='Reject' class='btn btn-danger btn-sm' />";	
		$remrk_chq="<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea><br />";
	break;
	case 1 : $chq_btn = "<B class='text-info'>Approved</B>"; $remrk_chq = "";	break;
	case 4 : $chq_btn = "<B class='text-danger'>Rejected</B>"; $remrk_chq = ""; break;
}

$all_btn = $remark_all = "";
if($mode_pan == 0 and $mode_id == 0 and $mode_photo == 0 and $mode_chq == 0){
	$remark_all = "<label>Remarks:</label><textarea name='remarks' class='form-control' required></textarea>";
	$all_btn = "<input type='submit' name='approve_allkyc' value='Approve All' class='btn btn-info btn-sm' />
		<input type='submit' name='cancel_allkyc' value='Reject All' class='btn btn-danger btn-sm' />"; 
}
?>

<div class="row"> 
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title"><h5>Photo</h5></div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-2"><div class="form-group"><B>Name :</B></div></div>
					<div class="col-sm-10"><?=$name?></div>
				</div>
				<form method="post" action="index.php?page=kyc">
					<input type="hidden" name="type_kyc" value="photo" />
					<input type="hidden" name="table_id" value="<?=$table_id?>" />
					<input type="hidden" name="user_id" value="<?=$user_id?>" />
					<div class="row">
						<div class="col-sm-12"><?=$photo_img?></div>
						<div class="col-sm-12">&nbsp;</div>
						<div class="col-sm-12">
							<?=$remrk_photo?>
							<div class="text-center"><?=$photo_btn?></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
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
				<form method="post" action="index.php?page=kyc">
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
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title"><h5>PAN Card & Sign</h5></div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-2"><div class="form-group"><B>PAN No. :</B></div></div>
					<div class="col-sm-10"><?=$pan_no?></div>
				</div>
				<form method="post" action="index.php?page=kyc">
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
	</div>
	<div class="col-sm-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title"><h5>Bank Passbook</h5></div>
			<div class="ibox-content">
				<div class="row">
					<div class="col-sm-2"><div class="form-group"><B>Name :</B></div></div>
					<div class="col-sm-10"><?=$name?></div>
				</div>
				<div class="row">
					<div class="col-sm-2"><div class="form-group"><B>Bank Name :</B></div></div>
					<div class="col-sm-10"><?=$bank?></div>
				</div>
				<div class="row">
					<div class="col-sm-2"><div class="form-group"><B>A/C No. :</B></div></div>
					<div class="col-sm-10"><?=$bank_ac?></div>
				</div>
				<div class="row">
					<div class="col-sm-2"><div class="form-group"><B>Branch :</B></div></div>
					<div class="col-sm-10"><?=$branch?></div>
				</div>
				<div class="row">
					<div class="col-sm-2"><div class="form-group"><B>IFSC Code :</B></div></div>
					<div class="col-sm-10"><?=$ifsc?></div>
				</div>
				<form method="post" action="index.php?page=kyc">
					<input type="hidden" name="type_kyc" value="chq_passbook" />
					<input type="hidden" name="table_id" value="<?=$table_id?>" />
					<input type="hidden" name="user_id" value="<?=$user_id?>" />
					
					<?=$bankpass_img?><br /><br />
					<?=$remrk_chq?>
					<div class="text-center"><?=$chq_btn?></div>
				</form>
			</div>
		</div>
	</div>
	
	
	<div class="col-sm-12">&nbsp;</div>
	<div class="col-sm-12">
		<form method="post" action="index.php?page=kyc">
			<input type="hidden" name="type_kyc" value="all" />
			<input type="hidden" name="table_id" value="<?=$table_id?>" />
			<input type="hidden" name="user_id" value="<?=$user_id?>" />
			
			<?=$remark_all?><br />
			<div class="text-center"><?=$all_btn?></div>
		</form>
	</div>
</div>

<style>
.ibox{
	border:1px solid #DDD;
}
</style>
