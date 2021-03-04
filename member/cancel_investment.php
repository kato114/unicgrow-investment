<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");

$login_id = $_SESSION['mlmproject_user_id'];

$my_plan = my_package($login_id);
$plan_name = $my_plan[0]." (&#36;".$my_plan[5].")";
$user_plan = user_user_roi_stop_or_not($login_id);

if($user_plan == 189){
	$plan_name = "Basic Package (&#36;".$my_plan[5].")";
}
elseif($user_plan == 190){
	$plan_name = "Blocked (&#36;".$my_plan[5].")";
}


$total_roi = user_user_total_roi_new($login_id);

$tot_roi = $recvd_roi = 0;
$pend_roi = $total_roi;
if($total_roi > 0){
	$tot_roi = $total_roi;
	$recvd_roi = get_user_roi_income($login_id);
	$pend_roi = $tot_roi-$recvd_roi;
}

$user_cancel_invst = get_user_cancel_investment($login_id);

/*if(isset($_SESSION['MSG_CSN_INV'])){
	echo $_SESSION['MSG_CSN_INV'];
	unset($_SESSION['MSG_CSN_INV']);	
}*/

$kyc_sts = get_user_kyc_status_new($login_id);
switch($kyc_sts){
	case 'Cancelled' : 	$kyc_status = "<B class='text-danger'>Cancelled </B>"; break;
	case 'Pending' : 	$kyc_status = "<B class='text-warning'>Pending </B>"; break;
	case 'Approved' : 	$kyc_status = "<B class='text-success'>Approved </B>"; break;
}

if(isset($_POST['approve'])){
	if($user_cancel_invst == '' or $user_cancel_invst == 2){	
		if($_SESSION['OTP_CAN_INV'] == $_POST['otp_code']){
			$sql = "INSERT INTO `cancel_investment`(`user_id`, `tot_roi`, `received_roi`, `pending_roi`, 
			`req_date`, `paid_date`) 
			VALUES ('$login_id', '$tot_roi', '$recvd_roi', '$pend_roi', NOW(), DATE_ADD(NOW(), INTERVAL 1 MONTH))";
			query_execute_sqli($sql);
			
			query_execute_sqli("UPDATE reg_fees_structure SET mode = 177 WHERE user_id = '$login_id'");
			
			unset($_SESSION['OTP_CAN_INV']);
		
			$_SESSION['MSG_CSN_INV'] = "<div class='alert alert-success alert-dismissable'>
				<button aria-hidden='true' data-dismiss='alert' class='close' type='button'>&times;</button>
				<B>Investment Cancelled Successfully.</B>
			</div>";
			?> <script>window.location="index.php?page=my_request";</script> <?php
		}
		else{ $_POST['submit'] = 1;
			echo "<div class='alert alert-danger alert-dismissable'>
				<button aria-hidden='true' data-dismiss='alert' class='close' type='button'>&times;</button>
				<B>Please Enter Correct OTP Code</B>
			</div>"; 
		}
	}
}

if(isset($_POST['submit'])){
	if($user_cancel_invst == '' or $user_cancel_invst == 2){	
		$sql = "SELECT * FROM kyc WHERE mode_pan = 1 AND mode_id = 1 AND mode_photo = 1 AND mode_chq = 1 
		AND user_id = '$login_id'";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);	
		if($num > 0){ 
			if(!isset($_SESSION['OTP_CAN_INV'])){
				$_SESSION['OTP_CAN_INV'] = $OTP_CAN_INV = rand(10000 , 99999);
				if(strtoupper($soft_chk) == "LIVE"){
					$phone_no_sms = get_user_phone($login_id);
					$message = "Your Cancel Investment OTP is ".$OTP_CAN_INV." https://www.unicgrow.com";
					send_sms($phone_no_sms,$message);
				
					$title = "Verification Code";
					$to = $email;
					$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $message);	
				}
			}
			echo $_SESSION['OTP_CAN_INV'];
			if($_POST['submit'] != 1){ ?>
				<div class='alert alert-success alert-dismissable'>
					<button aria-hidden='true' data-dismiss='alert' class='close' type='button'>&times;</button>
					<B>OTP Password has been sent to your Phone &amp; E-mail Id. </B>
				</div> <?php
			} ?>
			<form action="" method="post">
				<table class="table table-bordered table-hover">
					<thead><tr><th colspan="3">OTP Information</th></tr></thead>
					<tr>
						<th width="25%">OTP Code</th>
						<td>
							<input type="text" name="otp_code" value="<?=$_POST['otp_code']?>" class="form-control" maxlength="5" onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')" required />
						</td>
						<td><input type="submit" name="approve" value="Submit" class="btn btn-primary" /></td>
					</tr>
				</table>
			</form> <?php
		}
		else{ 
			echo "<div class='alert alert-danger alert-dismissable'>
				<button aria-hidden='true' data-dismiss='alert' class='close' type='button'>&times;</button>
				<B>KYC is not complete !</B>
			</div>"; 
		}
	}
}
else{
	if($user_cancel_invst == '' or $user_cancel_invst == 2){ 
		$payment_date = date('d/m/Y', strtotime(date('Y-m-d')."+ 2 MONTH"));
		?>
		<form action="" method="post">
		<table class="table table-bordered table-hover">
			<thead><tr><th colspan="2">Investment Details</th></tr></thead>
			<tr><th width="30%">Investment Amount</th><td><?=$plan_name?></td></tr>
			<tr><th>Received Amount</th>	<td>&#36;<?=$recvd_roi?></td></tr>
			<tr><th>Pending Amount</th>		<td>&#36;<?=$pend_roi?></td></tr>
			<tr><th>Payment Date</th>		<td><?=$payment_date?></td></tr>
			<tr><th>KYC Status</th>			<td><?=$kyc_status?></td></tr>
			<tr><th colspan=2>**Terms and Condition Applied</td></tr>
			<tr>	
				<td colspan="2" class="text-center">
					<input type="submit" name="submit" value="Submit" class="btn btn-primary" />
				</td>
			</tr>
		</table>
		</form> <?php
	}
	else{ echo "<B class='text-danger'>Cancell Investment already Done By You!</B>"; }
}
?>

