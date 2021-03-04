<?php
include("../config.php");

$user_id = $_REQUEST['user_id'];
$kyc_type = $_REQUEST['kyc_type'];
$table_id = $_REQUEST['table_id'];


/*$field = $kyc_type;
if($kyc_type == 'id_proof'){
	$field = "id_proof_front,id_proof_back";
}
if($kyc_type == 'photo'){
	$field = "photo,signature";
}*/

$sql = "SELECT t1.*, t2.f_name, t2.l_name FROM kyc t1 
LEFT JOIN users t2 ON t1.user_id = t2.id_user
WHERE t1.user_id = '$user_id' AND t1.id = '$table_id'";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query)){ 	
	$id_proof_front = $row['id_proof_front'];
	$id_proof_back = $row['id_proof_back'];
	$photo = $row['photo'];
	$signature = $row['signature'];
	
	$add_proof_type = $row['add_proof_type'];
	$add_proof_no = $row['add_proof_no'];
	$add_proof = $row['add_proof'];
	
	$name = ucwords($row['f_name']." ".$row['l_name']);
	$id_proof_no = $row['id_proof_no'];
	$id_proof_type = explode("_",strtoupper($row['id_proof_type']));
	$id_type1 = $id_proof_type[0];
	$id_type2 = $id_proof_type[1];
} 

$img = "<B class='text-danger'>Not Uploaded</B>";

switch($kyc_type){
	case 'id_proof' : 
		$title = "";	
		$img = "<h4>ID Proof Front</h4><img src='../images/mlm_kyc/$id_proof_front' class='img-responsive' /><br />
		<br /><h4>ID Proof Back</h4><img src='../images/mlm_kyc/$id_proof_back' class='img-responsive' />";	
		$content = "
		<div class='row'>
			<div class='col-sm-2'><div class='form-group'><B>ID Proof :</B></div></div>
			<div class='col-sm-10'>$id_type1 $id_type2</div>
		</div>
		<div class='row'>
			<div class='col-sm-2'><div class='form-group'><B>ID No. :</B></div></div>
			<div class='col-sm-10'>$id_proof_no</div>
		</div>";
	break;
	case 'photo' : 
		$title = "";	
		$img = "<h4>Photo</h4> <img src='../images/mlm_kyc/$photo' class='img-responsive' /> <br /><br />
		<h4>Signature</h4> <img src='../images/mlm_kyc/$signature' class='img-responsive' />";	
		$content = "<div class='row'><div class='col-sm-2'><div class='form-group'><B>Name :</B></div></div>
		<div class='col-sm-10'>$name</div></div>";
	break;
	case 'pan_card' : 
		$title = "PAN Card";	
		$img = "<h4></h4><img src='../images/mlm_kyc/$pan_card' class='img-responsive' />";	
		$content = "<div class='row'> <div class='col-sm-2'><div class='form-group'><B>PAN No. :</B></div></div>
		<div class='col-sm-10'>$pan_no</div></div>";
	break;
	case 'addrs_proof' : 
		$title = "Address Proof";	
		$img = "<h4></h4><img src='../images/mlm_kyc/$add_proof' class='img-responsive' />";	
		$content = "
		<div class='row'>
			<div class='col-sm-2'><div class='form-group'><B>Address Proof :</B></div></div>
			<div class='col-sm-10'>$add_proof_type</div>
		</div>
		<div class='row'>
			<div class='col-sm-2'><div class='form-group'><B>Address Proof No. :</B></div></div>
			<div class='col-sm-10'>$add_proof_no</div>
		</div>";
	break;
}
?>
<?=$content?>
<div class="row"> 
	<div class="col-sm-12">&nbsp;</div>
	<div class="col-sm-12"><label><B><?=$title?></B></label> <?=$img?></div>
	<div class="col-sm-12">&nbsp;</div>
	<form method="post" action="index.php?page=kyc">
		<input type="hidden" name="type_kyc" value="<?=$kyc_type?>" />
		<input type="hidden" name="table_id" value="<?=$table_id?>" />
		<input type="hidden" name="user_id" value="<?=$user_id?>" />
		<!--<div class="col-sm-12">
			<label>Remarks :</label>
			<textarea name="remarks" class="form-control"></textarea><br />
			<div class="text-center">
				<input type="submit" name="approve_kyc" value="Approve" class="btn btn-info btn-sm" />
				<input type="submit" name="cancel_kyc" value="Cancel" class="btn btn-danger btn-sm" />
			</div>
		</div>-->
	</form>
</div>
