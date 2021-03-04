<?php
include('../../security_web_validation.php');
?>
<?php
session_start();

include("condition.php");
include("../function/setting.php");


if(isset($_POST['submit']))
{
	$welcome_message = $_REQUEST['welcome_message'];
	$forget_password_message = $_REQUEST['forget_password_message'];
	$payout_generate_message = $_REQUEST['payout_generate_message'];
	$email_welcome_message = $_REQUEST['email_welcome_message'];
	$direct_member_message = $_REQUEST['direct_member_message'];
	$payment_request_message = $_REQUEST['payment_request_message'];
	$payment_transfer_message = $_REQUEST['payment_transfer_message'];
	$epin_generate_message = $_REQUEST['epin_generate_message']; 
	$user_pin_generate_message = $_REQUEST['user_pin_generate_message']; 
	
	query_execute_sqli("update setting set welcome_message  = '$welcome_message' , forget_password_message = '$forget_password_message' , payout_generate_message = '$payout_generate_message' , email_welcome_message = '$email_welcome_message' , direct_member_message = '$direct_member_message' , payment_request_message = '$payment_request_message' , payment_transfer_message = '$payment_transfer_message' , epin_generate_message = '$epin_generate_message' , user_pin_generate_message = '$user_pin_generate_message' ");
	
	data_logs($id,$pos,$data_log[12][0],$data_log[12][1],$log_type[9]);
	
	$p = 1; 
		 	 	 	 	 	 	
}

$query = query_execute_sqli("select * from setting ");
while($row = mysqli_fetch_array($query))
{
	$welcome_message = $row['welcome_message'];
	$forget_password_message = $row['forget_password_message'];
	$payout_generate_message = $row['payout_generate_message'];
	$email_welcome_message = $row['email_welcome_message'];
	$direct_member_message = $row['direct_member_message'];
	$payment_request_message = $row['payment_request_message'];
	$payment_transfer_message = $row['payment_transfer_message'];
	$epin_generate_message = $row['epin_generate_message'];
	$user_pin_generate_message = $row['user_pin_generate_message'];
}

if($p == 1) { echo "<B class='text-success'>Updating completed Successfully</B>"; } ?>
<form name="request" action="index.php?page=general_setting" method="post">
<table class="table table-bordered">
	<thead><tr><th colspan="2">General Setting Form</th></tr></thead>
	<tr>
		<th>Welcome Message</th>
		<td><textarea name="welcome_message" class="form-control"><?=$welcome_message?></textarea></td>
	</tr>
	<tr>
		<th>Forget Password Message</th>
		<td>
			<textarea name="forget_password_message" class="form-control"><?=$forget_password_message?></textarea>
		</td>
	</tr>
	<tr>
		<th>Payout Generate Message</th>
		<td>
			<textarea name="payout_generate_message" class="form-control"><?=$payout_generate_message?></textarea>
		</td>
	</tr>
	<tr>
		<th>Email Welcome Message</th>
		<td><textarea name="email_welcome_message" class="form-control"><?=$email_welcome_message?></textarea></td>
	</tr>
	<tr>
		<th>Direct Member Message</th>
		<td><textarea name="direct_member_message" class="form-control"><?=$direct_member_message?></textarea></td>
	</tr>
	<tr>
		<th>Payout Request Message</th>
		<td>
			<textarea name="payment_request_message" class="form-control"><?=$payment_request_message?></textarea>
		</td>
	</tr>
	<tr>
		<th>Payout Transfer Message</th>
		<td>
			<textarea name="payment_transfer_message" class="form-control"><?=$payment_transfer_message?></textarea>
		</td>
	</tr>
	<tr>
		<th>E-pin Generate Message</th>
		<td><textarea name="epin_generate_message" class="form-control"><?=$epin_generate_message?></textarea></td>
	</tr>
	<tr>
		<th>User pin Generate Message</th>
		<td>
		<textarea name="user_pin_generate_message" class="form-control"><?=$user_pin_generate_message?></textarea>
		</td>
	</tr>
	<tr>
		<td class="text-center" colspan="2">
			<input type="submit" name="submit" value="Update" class="btn btn-info"  />
		</td>
	</tr>
</table>



