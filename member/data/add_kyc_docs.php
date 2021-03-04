<?php
include('../security_web_validation.php');
include("function/send_mail.php");
include("function/setting.php");

$allowedfiletypes = array("jpeg","jpg","png","gif");
$uploadfolder = $kyc_docs_path;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;

$user_id = $_SESSION['mlmproject_user_id'];
$err_msg = '';
if(isset($_SESSION['msg_kyc']))
{
	echo $_SESSION['msg_kyc'];
	unset( $_SESSION['msg_kyc']);
}


if(isset($_POST['proceed'])){
	query_execute_sqli("UPDATE kyc SET proceed = 5 WHERE user_id = '$user_id'");
	?> <script>window.location = "index.php?page=add_kyc_docs";</script> <?php
}


if(isset($_POST['update_pic'])){
	
	$photo = $_POST['photo'];
	
	$unique_name =	"CN".time();
	$uploadfilename = $_FILES['photo_sign']['name'];

	$query = query_execute_sqli("SELECT * FROM kyc WHERE user_id = '$user_id'");
	$num = mysqli_num_rows($query);
		
	$cnt = count($uploadfilename);
	for($i = 0; $i < $cnt; $i++){
		if(!empty($_FILES['photo_sign']['name'][$i])){
			$fileext = strtolower(substr($uploadfilename[$i],strrpos($uploadfilename[$i],".")+1));
			if (!in_array($fileext,$allowedfiletypes)){
				$inval = 1; break;
			}
		}
	}
	if($inval == 1){ echo "<B class='text-danger'>Invalid Extension!!</B>"; }
	else{
		for($i = 0; $i < $cnt; $i++){	
			if(!empty($_FILES['photo_sign']['name'])){
				
				list($width, $height, $type, $attr) = getimagesize($_FILES['photo_sign']['tmp_name'][$i]);
		
				if($_FILES['photo_sign']['size'][$i] > 5242880){ //1 MB. if($width>200 || $height>200){//Limit in px
					$err_msg = "<B class='text-danger'>Images size should be less than 1 MB.</B>";
				} 
				else{
					$fileext = strtolower(substr($uploadfilename[$i],strrpos($uploadfilename[$i],".")+1));
					if (!in_array($fileext,$allowedfiletypes)){
						echo "<B class='text-danger'>Invalid Extension</B>";
					}
					else{
						$unique_time = time();
						$unique_name =	"CN".$unique_time.$user_id.$i;
						
						$fulluploadfilename = '';
						$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
						$unique_name = $unique_name.".".$fileext;
						
						if (copy($_FILES['photo_sign']['tmp_name'][$i], $fulluploadfilename)){ 	
							switch($i){
								case '0' : $field = 'photo'; break;
								case '1' : $field = 'signature'; break;
							}
							$img .= $field."='".$unique_name."',";
						}
					}
				}
			}
			else{ echo "<B class='text-danger'>Please Select Image !!</B>"; }
		}
		$img  = rtrim($img,',');
		if($num > 0){
			$sql = "UPDATE kyc SET $img , mode_photo = 0, date = NOW() WHERE user_id = '$user_id'";
		}
		else{
			$sql = "INSERT INTO kyc SET user_id = '$user_id' , $img , date = NOW()";
		}
		if(query_execute_sqli($sql)){
			query_execute_sqli("UPDATE kyc SET proceed = proceed+1 WHERE user_id = '$user_id'");
			kyc_is_complete_or_not($user_id);
			$_SESSION['msg_kyc']="<B class='text-success'>Profile Picture Updated Successfully !!</B>";
			?> <script>window.location = "index.php?page=add_kyc_docs";</script> <?php
		}
	}
}
if(isset($_POST['updt_idproof'])){
	
	$id_prf_type = $_POST['type_of_idproof'];
	$id_proof_no = $_POST['id_proof_no'];
	$id_proof_img = $_POST['id_proof_img'];
	$uploadfilename = $_FILES['id_proof_img']['name'];

	$query = query_execute_sqli("SELECT * FROM kyc WHERE user_id = '$user_id'");
	$num = mysqli_num_rows($query);
		
	$cnt = count($uploadfilename);
	for($i = 0; $i < $cnt; $i++){
		if(!empty($_FILES['id_proof_img']['name'][$i])){
			$fileext = strtolower(substr($uploadfilename[$i],strrpos($uploadfilename[$i],".")+1));
			if (!in_array($fileext,$allowedfiletypes)){
				$inval = 1; break;
			}
		}
	}
	if($inval == 1){ echo "<B class='text-danger'>Invalid Extension!!</B>"; }
	else{
		for($i = 0; $i < $cnt; $i++){	
			if(!empty($_FILES['id_proof_img']['name'])){
				
				list($width, $height, $type, $attr) = getimagesize($_FILES['id_proof_img']['tmp_name'][$i]);
		
				if($_FILES['id_proof_img']['size'][$i] > 5242880){ //1 MB.if($width>200 || $height>200){// Limitin px
					$err_msg = "<B class='text-danger'>Images size should be less than 1 MB.</B>";
				} 
				else{
					$fileext = strtolower(substr($uploadfilename[$i],strrpos($uploadfilename[$i],".")+1));
					if (!in_array($fileext,$allowedfiletypes)){
						echo "<B class='text-danger'>Invalid Extension</B>";
					}
					else{
						$unique_time = time();
						$unique_name =	"CN".$unique_time.$user_id.$i;
						
						$fulluploadfilename = '';
						$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
						$unique_name = $unique_name.".".$fileext;
						
						if (copy($_FILES['id_proof_img']['tmp_name'][$i], $fulluploadfilename)){ 	
							switch($i){
								case '0' : $field = 'id_proof_front'; break;
								case '1' : $field = 'id_proof_back'; break;
							}
							$img .= $field."='".$unique_name."',";
						}
					}
				}
			}
			else{ echo "<B class='text-danger'>Please Select Image !!</B>"; }
		}
		$img  = rtrim($img,',');
		if($num > 0){
			$sql = "UPDATE kyc SET id_proof_type = '$id_prf_type', id_proof_no = '$id_proof_no', $img, mode_id =0, 
			date = NOW() WHERE user_id = '$user_id'";
		}
		else{
			$sql = "INSERT INTO kyc SET user_id = '$user_id', id_proof_type = '$id_prf_type', 
			id_proof_no = '$id_proof_no' , $img , date = NOW()";
		}

		if(query_execute_sqli($sql)){
			query_execute_sqli("UPDATE kyc SET proceed = proceed+1 WHERE user_id = '$user_id'");
			kyc_is_complete_or_not($user_id);
			$_SESSION['msg_kyc'] = "<B class='text-success'>ID Proof Updated Successfully !!</B>";
			?> <script>window.location = "index.php?page=add_kyc_docs";</script> <?php
		}
	}
}
if(isset($_POST['update_pan'])){
	
	$pan_no = $_POST['pan_no'];
	$dob = date('Y-m-d', strtotime($_POST['dob']));

	$unique_name =	"CN".time();
	$uploadfilename = $_FILES['pan_card']['name'];
	
	$query = query_execute_sqli("SELECT * FROM kyc WHERE user_id = '$user_id'");
	$num = mysqli_num_rows($query);
	
	if(!empty($_FILES['pan_card']['name'])){
		list($width, $height, $type, $attr) = getimagesize($_FILES['pan_card']['tmp_name']);
		
		if($_FILES['pan_card']['size'] > 5242880) { //1 MB. if($width > 200 || $height > 200){ // Limit in px
			$err_msg = "<B class='text-danger'>Images size should be less than 1 MB.</B>";
		} 
		else{
			$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
			
			if (!in_array($fileext,$allowedfiletypes)){
				echo "<B class='text-danger'>Invalid Extension</B>";
			}
			else{
				$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
				if (copy($_FILES['pan_card']['tmp_name'], $fulluploadfilename)){ 
					$unique_name = $unique_name.".".$fileext;
					if($num > 0){
						$sql = "UPDATE kyc SET pan_no = '$pan_no', pan_card = '$unique_name', dob = '$dob' ,
						mode_pan = 0, date = NOW() WHERE user_id = '$user_id'";
					}
					else{
						$sql = "INSERT INTO kyc SET user_id = '$user_id', pan_no = '$pan_no', 
						pan_card = '$unique_name', dob = '$dob', date = NOW()";
					}
					query_execute_sqli($sql);
					
					query_execute_sqli("UPDATE kyc SET proceed = proceed+1 WHERE user_id = '$user_id'");
					kyc_is_complete_or_not($user_id);
					$_SESSION['msg_kyc']="<B class='text-success'>PAN Card Updated Successfully !!</B>";
					?> <script>window.location = "index.php?page=add_kyc_docs";</script> <?php
				}
			}
		}
	}
}

if(isset($_POST['update_addr'])){
	
	$add_proof_type = $_POST['add_proof_type'];
	$add_proof_no = $_POST['add_proof_no'];
	$add_proof = $_POST['add_proof'];

	$unique_name =	"CN".time();
	$uploadfilename = $_FILES['adr_img']['name'];
	
	$query = query_execute_sqli("SELECT * FROM kyc WHERE user_id = '$user_id'");
	$num = mysqli_num_rows($query);

	if(!empty($_FILES['adr_img']['name'])){
		list($width, $height, $type, $attr) = getimagesize($_FILES['adr_img']['tmp_name']);
		
		if($_FILES['adr_img']['size'] > 5242880) { //1 MB. if($width > 200 || $height > 200){ // Limit in px
			$err_msg = "<B class='text-danger'>Images size should be less than 1 MB.</B>";
		} 
		else{
			$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
			
			if (!in_array($fileext,$allowedfiletypes)){
				echo "<B class='text-danger'>Invalid Extension</B>";
			}
			else{
				$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
				if (copy($_FILES['adr_img']['tmp_name'], $fulluploadfilename)){ 
					$unique_name = $unique_name.".".$fileext;
					if($num > 0){
						$sql = "UPDATE kyc SET add_proof_type = '$add_proof_type', add_proof_no = '$add_proof_no', 
						add_proof = '$unique_name' , mode_chq = 0, date = NOW() WHERE user_id = '$user_id'";
					}
					else{
						$sql = "INSERT INTO kyc SET user_id = '$user_id', add_proof_type = '$add_proof_type', 
						add_proof_no = '$add_proof_no', add_proof = '$unique_name', date = NOW()";
					}
					query_execute_sqli($sql);
					
					query_execute_sqli("UPDATE kyc SET proceed = proceed+1 WHERE user_id = '$user_id'");
					kyc_is_complete_or_not($user_id);
					$_SESSION['msg_kyc']="<B class='text-success'>Address Proof Updated Successfully !!</B>";
					?> <script>window.location = "index.php?page=add_kyc_docs";</script> <?php
				}
			}
		}
	}
}

if(isset($_POST['reject_approve'])){
	$table_id = $_REQUEST['table_id'];
	$remarks = $_REQUEST['remarks'];
	if($_SESSION['random_OTP'] == $_REQUEST['otp_pass']){
		
		$sql = "UPDATE kyc SET mode_id = 4 , mode_photo = 4 , mode_chq = 4 , mode = 4 , 
		add_proof = '' , id_proof_front = '' , id_proof_back = '' , photo = '' , signature = '', id_proof_type = '' ,
		id_proof_no = '' , date = '' , mode = 0 , proceed = 0
		WHERE id = '$table_id' AND user_id = '$user_id'";
		query_execute_sqli($sql);
	
		$sql = "INSERT INTO `kyc_history`(`ref_id`, `user_id`, `kyc_type` , `remarks`, `mode` , `date`) 
		VALUES ('$table_id','$user_id','Reject All KYC by User Self','$remarks', 0 ,'$systems_date')";
		query_execute_sqli($sql);
		unset($_SESSION['random_OTP']);
		?> 
		<script>
			alert("KYC Rejected successfully !"); window.location = "index.php?page=add_kyc_docs";
		</script> <?php
	}
	else{ ?> 
		<script>
			alert("Please Enter Correct OTP Password !"); 
			window.location = "index.php?page=add_kyc_docs";
		</script> <?php
	} 
}

if(isset($_POST['reject_all'])){

	$_SESSION['random_OTP'] = $rand_no = rand(10000 , 99999);
	$table_id = $_REQUEST['table_id'];
	
	if(strtoupper($soft_chk) == 'LIVE'){
		$title = "OTP Code KYC Cancel";
		$to = get_user_email($user_id);
		$full_message = "Your OTP code is for KYC Reject".$rand_no." https://www.unicgrow.com";
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);	
		//$SMTPChat = $SMTPMail->SendMail();
	
		$phone = get_user_phone($user_id);
		$message_login = "Your OTP code is for KYC Reject ".$rand_no." https://www.unicgrow.com";
		send_sms($phone,$message_login);
	} ?>
	<form action="" method="post">
		<input type="hidden" name="table_id" value="<?=$table_id?>" />
		<table class="table table-bordered table-hover">
			<thead><tr><th colspan="3">OTP Password </th></tr></thead>
			<tr>
				<th>Enter OTP Code</th>
				<td>
					<input type="text" name="otp_pass" class="form-control" maxlength="5" onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')" required />
				</td>
			</tr>
			<tr>
				<th>Remarks</th>
				<td><textarea name="remarks" class="form-control" required></textarea></td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="reject_approve" value="Reject KYC" class="btn btn-info" />
				</td>
			</tr>
		</table>
	</form> <?php
}
else{
	$photo_chk = $id_proof_chk = $dob = "";;
	$photo = $id_proof_front = $id_proof_back = $pancard_img = $adr_img = $sign = "<img src='images/noimage.png'>";
	$pan_card_img = $add_proof_img = "<img src='images/noimage.png'>";
	
	$btn_idproof = '<input type="submit" name="updt_idproof" value="Update" class="btn btn-info" />';
	$btn_pic = '<input type="submit" name="update_pic" value="Update" class="btn btn-info" />';
	$btn_pan = '<input type="submit" name="update_pan" value="Update" class="btn btn-info" />';
	$btn_add = '<input type="submit" name="update_addr" value="Update" class="btn btn-primary" />';
	
	$file_pan = '<input type="file" name="pan_card" required />';
	$file_idfrnt = '<input type="file" name="id_proof_img[]" required />';
	$file_idback = '<input type="file" name="id_proof_img[]" />';
	$file_photo = '<input type="file" name="photo_sign[]" required />';
	$file_sign = '<input type="file" name="photo_sign[]" required />';
	$file_add = '<input type="file" name="adr_img" required />';
	
			
	$sql = "select * from kyc where user_id = '$user_id'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	while($row = mysqli_fetch_array($query)){
	
		$btn_idproof = $btn_pic = $btn_pan = $btn_add = '';
		$photo= $id_proof_front= $id_proof_back= $pancard_img= $adr_img = $sign = "<img src='images/noimage.png'>";
		
		$file_pan = $file_idfrnt = $file_idback = $file_photo = $file_sign = $file_add = '';
		
		$table_id = $row['id'];
		$name = $row['name'];
		$fat_name = $row['father_name'];
		$mobile = $row['mobile_no'];
		$gender = $row['gender'];
		$maried = $row['marital_status'];
		$mode = $row['mode'];
		
		$date_ob = $row['dob'];
	
		$add_proof_type = $row['add_proof_type'];
		$add_proof_no = $row['add_proof_no'];
		$add_proof = $row['add_proof'];
		
		
		$p_address = $row['permanent_address'];
		$address_type = $row['address_type'];		
	
		$pan_card = $row['pan_card'];
		$pan_no = $row['pan_no'];
		
		$id_proof_type = $row['id_proof_type'];
		$id_proof_no = $row['id_proof_no'];
		$id_front = $row['id_proof_front'];
		$id_back = $row['id_proof_back'];
		$user_pic = $row['photo'];
		$sign = $row['signature'];
		
		$mode_pan = $row['mode_pan'];
		$mode_id = $row['mode_id'];
		$mode_photo = $row['mode_photo'];
		$mode_chq = $row['mode_chq'];
		$proceed = $row['proceed'];
		
		$btn_proceed = "";
		if($proceed == 3){
			$btn_proceed = "<div class='row'>
				<div class='col-sm-8 text-right'>
					<h3>Continue to Admin approval press proceed button <i class='fa fa-hand-o-right'></i></h3>
				</div>
				<div class='col-sm-4 text-left'>
					<form method='post' action=''>
						<input type='submit' name='proceed' value='Proceed' class='btn btn-info' />
					</form>
				</div>
			</div>";
		}
		
		if($date_ob > 0)$dob = date('m/d/Y', strtotime($row['dob']));
	
		if($pan_card == '' and $mode_pan == 0 or $mode_pan == 4){
			$btn_pan = '<input type="submit" name="update_pan" value="Update" class="btn btn-info" />';
			$file_pan = '<input type="file" name="pan_card" required />';
		}
		if($id_front == '' or $id_back == '' and $mode_id == 0 or $mode_id == 4){
			$btn_idproof = '<input type="submit" name="updt_idproof" value="Update" class="btn btn-info" />';
			$file_idfrnt = '<input type="file" name="id_proof_img[]" required />';
			$file_idback = '<input type="file" name="id_proof_img[]" />';
		}
		if($user_pic == '' or $sign == '' and $mode_photo == 0 or $mode_photo == 4){
			$btn_pic = '<input type="submit" name="update_pic" value="Update" class="btn btn-info" />';
			$file_photo = '<input type="file" name="photo_sign[]" required />';
			$file_sign = '<input type="file" name="photo_sign[]" required />';
		}
		if($add_proof == '' and $mode_chq == 0 or $mode_chq == 4){
			$btn_add = '<input type="submit" name="update_addr" value="Update" class="btn btn-primary" />';
			$file_add = '<input type="file" name="adr_img" required />';
		}
		
		
		
		if($id_front != '' and $mode_id != 4){
			$id_proof_front = "<img src='images/mlm_kyc/$id_front' width='100' class='imgss' />";
			$id_proof_chk = "<img src='images/checked.png' width='30' />";
		}
		if($id_back != '' and $mode_id != 4){
			$id_proof_back = "<img src='images/mlm_kyc/$id_back' width='100' class='imgss' />";
			$id_proof_chk = "<img src='images/checked.png' width='30' />";
		}
	
		if($user_pic != '' and $sign != '' and $mode_photo != 4){
			$photo = "<img src='images/mlm_kyc/$user_pic' width='100' class='imgss' />";
			$sign = "<img src='images/mlm_kyc/$sign' width='100' class='imgss' />";
			$photo_chk = "<img src='images/checked.png' width='30' />";
		}
		if($user_pic != '' and $sign == '' and $mode_photo != 4){
			$photo = "<img src='images/mlm_kyc/$user_pic' width='100' class='imgss' />";
			$sign = "<img src='images/noimage.png' width='100' class='imgss'>";
		}
		
		if($pan_card != '' and $mode_pan != 4){
			$pan_card_img = "<img src='images/mlm_kyc/$pan_card' width='100' class='imgss' />";
			$pan_card_chk = "<img src='images/checked.png' width='30' />";
		}
		
		if($add_proof != '' and $mode_chq != 4){
			$add_proof_img = "<img src='images/mlm_kyc/$add_proof' width='100' class='imgss' />";
			$add_prf_chk = "<img src='images/checked.png' width='30' />";
		}
		
		switch($id_proof_type){
			case 'votter_id' : 		$select_v = 'selected="selected"'; break;
			case 'smart_card' : 	$select_s = 'selected="selected"'; break;
			case 'passport' : 		$select_ps = 'selected="selected"'; break;
			case 'pan_card' : 		$select_pn = 'selected="selected"'; break;
			case 'driv_licence' : 	$select_d = 'selected="selected"'; break;
			case 'aadhar' : 		$select_a = 'selected="selected"'; break;
		}
	} 
	
	if($num > 0){
		/*switch($mode){
			case 0: $message = "Dear Customer, Your KYC Verification is not verified"; $alert_cls = "warning"; break;
			case 1: $message = "KYC Approved by Admin"; $alert_cls = "info"; break;
			case 4: $message = "KYC Cancelled. Please Enter KYC again!!"; $alert_cls = "danger"; break;
			case 150: $message = "KYC is not completed!!"; $alert_cls = "danger"; break;
		}*/
		
		$message = "Your KYC documents is not uploaded !!"; $alert_cls = "danger";
		if($id_front != '' and $id_back != '' and $add_proof != ''){
			$message = "Your KYC is pending for Admin approval !!"; $alert_cls = "warning";
		}
		
		if($mode_id == 4 and $mode_chq == 4){
			$message = "Your KYC is Rejected. Please upload documents again !!"; $alert_cls = "danger"; 
		}
		elseif($mode_id == 1 and $mode_chq == 1){
			$message = "KYC Approved by Admin"; $alert_cls = "info";
			
		}
		elseif($mode_id == 0 and $mode_chq == 0){
			$message = "Your All KYC documents is not uploaded !!"; $alert_cls = "danger";
			
			if($id_front != '' and $id_back != '' and $add_proof != ''){
				$message = "Your KYC is pending for Admin approval !!"; $alert_cls = "warning";
			}
		}
		
		$reject_form = "";
		/*if(($id_front != '' and $id_back != '') or $add_proof != '' or ($user_pic != '' and $sign != '') or $pan_card != ''){
			$reject_form = "<form action='' method='post'>
				<input type='hidden' name='table_id' value='$table_id' />
				<B>I want to reject My All KYC</B> <input type='submit' name='reject_all' value='Reject All' class='btn btn-danger btn-sm' />
			</form> ";
		}*/
		if($mode_pan != 1 and $mode_id != 1 and $mode_photo != 1 and $mode_chq != 1){
			$reject_form = "<form action='' method='post'>
				<input type='hidden' name='table_id' value='$table_id' />
				<B>I want to reject My All KYC</B> <input type='submit' name='reject_all' value='Reject All' class='btn btn-danger btn-sm' />
			</form> ";
		}
	}
	else{ $message = "Your KYC documents is not uploaded !!"; $alert_cls = "danger"; }
	
	echo $err_msg;
	?>
	<div class='alert alert-<?=$alert_cls?>'><B>KYC Status:</B> <?=$message?></div>
	
	<script type="text/javascript">
	$(document).ready(function(){
		$("div.clickable").on('click', function () {
			alert('Click');
		});
	});

	$(document).ready(function(){
	
		//$("#update_box").on("click touchstart",function(){
		$("#update_box").on('click', function () {
			$("#kyc_box").show();
			$("#update_box").hide();
		});
		
		//$("#kyc_cancel").on("click touchstart",function(){
		$("#kyc_cancel").on('click',function () {
			$("#kyc_box").hide();
			$("#update_box").show();
			$(".update_card").show();
		});
	
		//$("#prfl_img").on("click touchstart",function(){
		$("#prfl_img").on('click',function () {
			$("#photo_box").show();
			$("#idproof_box").hide();
			$("#pan_card_box").hide();
			$("#bank_acc_box").hide();
			document.getElementById("title").innerHTML = "Upload My Photo";
		});
		
		//$("#id_proof").on("click touchstart",function(){
		$("#id_proof").on('click',function () {
			$("#idproof_box").show();
			$("#photo_box").hide();
			$("#pan_card_box").hide();
			$("#bank_acc_box").hide();
			document.getElementById("title").innerHTML = "Upload Country ID Proof";
		});
		
		//$("#pan_card").on("click touchstart",function(){
		$("#pan_card").on('click',function () {
			$("#pan_card_box").show();
			$("#photo_box").hide();
			$("#idproof_box").hide();
			$("#bank_acc_box").hide();
			document.getElementById("title").innerHTML = "Upload My Pan Card";
		});
		
		//$("#bank_acc").on("click touchstart",function(){
		$("#bank_acc").on('click',function () {
			$("#bank_acc_box").show();
			$("#photo_box").hide();
			$("#idproof_box").hide();
			$("#pan_card_box").hide();
			document.getElementById("title").innerHTML = "Upload Country Address Proof";
		});
	});
	</script>
	<div class="col-sm-12 text-center">
		<h2>Account Verification</h2>
		<p>
			To comply with regulations will run KYC after you have contributed.<br>
			All contributors will need to complete KYC
		</p>
		<h3 class="text-danger">Note : Images size should be less than 1 MB.</h3>
	</div>
	<div class="col-sm-12">&nbsp;</div>
	<div class="col-sm-12">
		<div id="update_box" style="display:block;">
			<?=$btn_proceed?>
			<div class="col-sm-12">&nbsp;</div>
			<div class="col-sm-4 col-md-offset-2">
				<div class="checked_info">
					<div class="update_card text-center" id="id_proof">
						<div class="panel panel-default">
							<div class="text-right check_aero"><?=$id_proof_chk?> </div>
							<div class="panel-body"><img src="images/aadhar-card.png"> <h3>Country ID Proof</h3></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="checked_info">
					<div class="update_card text-center" id="bank_acc">
						<div class="panel panel-default">
							<div class="text-right check_aero"><?=$add_prf_chk?> </div>
							<div class="panel-body">
								<img src="images/electrick_bill.jpg"> <h3>Country Address Proof</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--<div class="col-sm-4">
				<div class="checked_info">
					<div class="update_card text-center" id="prfl_img">
						<div class="panel panel-default">
							<div class="text-right check_aero"><?=$photo_chk?></div>
							<div class="panel-body"><img src="images/user_icon.png"> <h3>Update Your Image</h3></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="checked_info">
					<div class="update_card text-center" id="pan_card">
						<div class="panel panel-default">
							<div class="text-right check_aero"><?=$pan_card_chk?> </div>
							<div class="panel-body"><img src="images/pan-card.png"> <h3>PAN Card</h3></div>
						</div>
					</div>
				</div>
			</div>-->
		</div>
		
		
		
		<div id="kyc_box" style="display:none;">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 id="title"></h3>
					<div class="pull-right" style="margin-top:-3.3%;">
						<button id="kyc_cancel" class="btn btn-danger btn-sm">
							<i class="fa fa-reply"></i> Back
						</button>
					</div>
				</div>
				<div class="panel-body text-center">
					<div id="idproof_box">
						<form method="post" action="" enctype="multipart/form-data">
							<div class="col-sm-3 col-sm-offset-3 text-left">
								<label>Type of ID<span class="text-danger">*</span></label>
								<select name="type_of_idproof" class="form-control" required>
									<option value="">Select One</option>
									<option value="votter_id" <?=$select_v?>>Votter ID Card</option>
									<option value="smart_card" <?=$select_s?>>Smart Card</option>
									<option value="passport" <?=$select_ps?>>Passport</option>
									<option value="pan_card" <?=$select_pn?>>Pan Card</option>
									<option value="driv_licence" <?=$select_d?>>Driving Licence</option>
									<option value="aadhar" <?=$select_a?>>Aadhar Card</option>
								</select>
							</div>
							<div class="col-sm-3 text-left">
								<label>ID Number<span class="text-danger">*</span></label>
								<input type="text" name="id_proof_no" value="<?=$id_proof_no?>" class="form-control" placeholder="635563336333"  required />
							</div>
							<div class="col-sm-12">&nbsp;</div>
							<div class="col-sm-12">&nbsp;</div>
							<div class="col-sm-3 col-sm-offset-3 text-left"><h3>Upload Document</h3></div>
							<div class="col-sm-6">&nbsp;</div>
							<div class="col-sm-12">&nbsp;</div>
							
							<div class="col-sm-3 col-sm-offset-3">
								<h4>Front Identity Card</h4>
								<div class="imgarea"><?=$id_proof_front?></div><br />
								<?=$file_idfrnt?>
							</div>
							<div class="col-sm-3">
								<h4>Back Identity Card</h4>
								<div class="imgarea"><?=$id_proof_back?></div><br />
								<?=$file_idback?>
							</div>
							<div class="col-sm-12">&nbsp;</div>
							<!--<div class="col-sm-3 col-sm-offset-3 text-left">
								<input type="file" name="id_proof_img[]" />
							</div>
							<div class="col-sm-3 text-left"><input type="file" name="id_proof_img[]" /></div>-->
							<div class="col-sm-12">&nbsp;</div>
							<div class="col-sm-12"><?=$btn_idproof?></div>
						</form>
					</div>
					
					<div id="bank_acc_box">
						<form method="post" action="" enctype="multipart/form-data">
							<div class="col-sm-3 col-sm-offset-3 text-left">
								<label>Type of ID<span class="text-danger">*</span></label>
								<input type="text" name="add_proof_type" value="<?=$add_proof_type?>" class="form-control" placeholder="Driving Licence"  required />
							</div>
							<div class="col-sm-3 text-left">
								<label>ID Number<span class="text-danger">*</span></label>
								<input type="text" name="add_proof_no" value="<?=$add_proof_no?>" class="form-control" placeholder="US-635/005/633/36333"  required />
							</div>
							<div class="col-sm-12">&nbsp;</div>
							
							<div class="col-sm-4 col-sm-offset-4">
								<h4>Update your Address Proof</h4>
								<div class="imgarea"><?=$add_proof_img?></div>
							</div>
							<div class="col-sm-3 col-sm-offset-1">&nbsp;</div>
							<div class="col-sm-12">&nbsp;</div>
							
							<div class="col-sm-12">&nbsp;</div>
							<div class="col-sm-8 col-sm-offset-4"><?=$file_add?></div>	
							<div class="col-sm-12">&nbsp;</div>
							<div class="col-sm-8 col-sm-offset-4 text-left"><?=$btn_add?></div>
						</form>
					</div>
					
					<!--<div id="photo_box">
						<form method="post" action="" enctype="multipart/form-data">
							<div class="col-sm-3 col-sm-offset-3">
								<h4>Your Photo</h4>
								<div class="imgarea"><?=$photo?></div><br />
								<?=$file_photo?>
							</div>
							<div class="col-sm-3">
								<h4>Your Signature</h4>
								<div class="imgarea"><?=$sign?></div><br />
								<?=$file_sign?>
							</div>
							<div class="col-sm-3">&nbsp;</div>
							
							<div class="col-sm-12">&nbsp;</div>
							
							<div class="col-sm-3 col-sm-offset-3 text-left">
								<input type="file" name="photo_sign[]" />
							</div>
							<div class="col-sm-4 text-left"><input type="file" name="photo_sign[]" /></div>
							<div class="col-sm-2">&nbsp;</div>
							
							<div class="col-sm-12">&nbsp;</div>
							<div class="col-sm-12"><?=$btn_pic?></div>
							<div class="col-sm-12">&nbsp;</div>
							<div class="col-sm-12">
								<h3>Guidelines for KYC Photo Upload</h3>
								<div>Image size should 200px width &amp; 200px height for better view.</div>
								<div>Image size should 1 MB for better view.</div>
								<div>Uploaded image should be clearly visible.</div>
								<div>Blur Image Can't be Accepted</div>
								<div>Background of every image must be Black/white</div>
							</div>
							
						</form>
					</div>-->
					
					<!--<div id="pan_card_box">
						<form method="post" action="" enctype="multipart/form-data">
							<div class="col-sm-3 col-sm-offset-3 text-left">
								<label>Pan Card No.<span class="text-danger">*</span></label>
								<input type="text" name="pan_no" value="<?=$pan_no?>" class="form-control" required />
							</div>
							<div class="col-sm-3 text-left">
								<label>Select D.O.B<span class="text-danger">*</span></label>
								<div class="form-group" id="data_1">
									<div class="input-group date">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										<input type="text" name="dob" value="<?=$dob?>" placeholder="Your DOB" class="form-control" required />
									</div>
								</div>
							</div>
							<div class="col-sm-12">&nbsp;</div>
							
							<div class="col-sm-4 col-sm-offset-4">
								<h4>Update your Pan Card</h4>
								<div class="imgarea"><?=$pan_card_img?></div>
							</div>
							<div class="col-sm-3 col-sm-offset-1">&nbsp;</div>
							<div class="col-sm-12">&nbsp;</div>
							
							<div class="col-sm-12">&nbsp;</div>
							<div class="col-sm-8 col-sm-offset-4"><?=$file_pan?></div>	
							<div class="col-sm-12">&nbsp;</div>
							<div class="col-sm-8 col-sm-offset-4 text-left"><?=$btn_pan?></div>
						</form>
					</div>-->
				</div>
			</div>
		</div>
		<div class="col-sm-12 text-right"><?=$reject_form?></div>
		<div class="col-sm-12 text-right">&nbsp;</div>
	</div>


	<?php
	include("modal.php");
	
	$newp = $_GET['p'];
	$plimit = 25;
	$show_tab = 5;
	if($newp == ''){ $newp='1'; }
	$tstart = ($newp-1) * $plimit;
	$tot_p = $plimit * $show_tab;
	
	$sql = "SELECT * FROM kyc_history WHERE user_id = '$user_id' AND mode = 0";
	$SQL = "$sql LIMIT $tstart,$tot_p ";
	
	$query = query_execute_sqli($SQL);
	$totalrows = mysqli_num_rows($query);
	
	
	/*echo $sqlk = "SELECT COUNT(t1.id_user) num FROM users t1
	LEFT JOIN kyc t2 ON t2.user_id = t1.id_user
	LEFT JOIN reg_fees_structure t3 ON t3.user_id = t1.id_user
	WHERE t1.real_parent = '$login_id' AND t1.id_user IN ($result) $qur_set_search";*/
	$sqlk = "SELECT COUNT(*) num FROM ($sql) t1";
	$query = query_execute_sqli($sqlk);
	while($ro = mysqli_fetch_array($query))
	{
		$tot_rec = $ro['num'];
		$lpnums = ceil ($tot_rec/$plimit);
	}
	
	if($totalrows > 0)
	{ ?>
		<table class="table table-bordered table-hover">
			<thead>
			<tr><th colspan="4"><h3>KYC History</h3></th></tr>
			<tr>
				<!--<th class="text-center">Sr. No</th>-->
				<th class="text-center">Date</th>
				<th class="text-center">Status</th>
				<th class="text-center">Remarks</th>
				<th class="text-center" width="30%">Image</th>
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
				$kyc_type = explode(',',$row['kyc_type']);
				$remarks = $row['remarks'];
				$date = $row['date'];
				
				$kyc1 = $kyc_type[0];
				$kyc2 = $kyc_type[1];
				
				$img = "<img src='images/mlm_kyc/$kyc1' width='50%' class='imgss' />";
				if(!empty($kyc2)){
					$img = "<img src='images/mlm_kyc/$kyc1' width='50%' class='imgss' />
					<img src='images/mlm_kyc/$kyc2' width='50%' class='imgss' />";
				}
				?>
				<tr class="text-center">
					<!--<td><?=$sr_no?></td>-->
					<td><?=$date;?></td>
					<td>Rejected</td>
					<td><?=$remarks?></td>
					<td><?=$img?></td>
				</tr> <?php
				$sr_no++;
			} ?>
		</table> <?php  
		pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
	}
}



function kyc_is_complete_or_not($user_id){
	$mode = 150;
	$sql = "SELECT * FROM kyc WHERE add_proof <> '' AND pan_card <> '' AND photo <> '' AND id_proof_front <> '' 
	AND id_proof_back <> '' AND signature <> '' AND user_id = '$user_id'";
	$num = mysqli_num_rows(query_execute_sqli($sql));
	if($num > 0){
		$mode = 0;
	}
	$sql = "UPDATE kyc SET mode = '$mode' WHERE user_id = '$user_id'";
	query_execute_sqli($sql);
	return $mode;
}

?>
<style>
.check_aero {
	right: 10px;
	margin:5px 5px 0 0;
	position:absolute;
}
.checked_info {
	position: relative;
	right: 0;
	z-index: 99;
	top: 0;
}

.imgarea {
	text-align: center;
	padding: 10px 0;
	height: 150px;
	display: table-cell;
	clear: both;
	width: 250px;
	vertical-align: middle;
	border: 1px solid #e8e8e8;
	margin-bottom: 10px;
}
.update_card{
	cursor:pointer;
}
</style>