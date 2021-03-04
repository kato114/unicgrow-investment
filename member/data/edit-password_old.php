<?php
include('../security_web_validation.php');

session_start();
include("condition.php");
include("function/setting.php");

$id = $_SESSION['mlmproject_user_id'];

//$password = get_user_password($id);
if(isset($_SESSION['succ_msg'])){ ?>
	<div class="col-md-12"><?=$_SESSION['succ_msg']?></div> <?php
	unset($_SESSION['succ_msg']);
}
if(isset($_REQUEST['change_password'])){
	$old_password = $_REQUEST['old_password'];
	$new_password = $_REQUEST['new_password'];
	$con_new_password = $_REQUEST['con_new_password'];
	$sql = "select * from users where id_user = '$id' and password = '$old_password' ";
	$q = query_execute_sqli($sql);
	$num = mysqli_fetch_array($q);
	if($num > 0){
		if($new_password == $con_new_password){
			$sql = "UPDATE users SET password = '$new_password' WHERE id_user = '$id'";
			$insert_q = query_execute_sqli($sql);
			
			$username = get_user_name($id);
			$date = date('Y-m-d');
			$updated_by = $username." Your self";
			include("function/logs_messages.php");
			data_logs($id,$data_log[2][0],$data_log[2][1],$log_type[1]);
			
			include("email_letter/edit_password.php");
			$to = get_user_email($id);
			//$full_message = 'Your security code is '.$user_pin." www.bitfinbull.com";
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $db_msg);
			//$SMTPChat = $SMTPMail->SendMail();
	
	
			$_SESSION['succ_msg'] = "<B class='text-success'>Login Password Updated Successfully </B>";
			?> <script>window.location="index.php?page=edit-password";</script> <?php
		}
		else 
		{ echo "<B class='text-danger'>Please Enter same Password in both  New and Confirm password Field !</B>"; }
	}
	else{ echo "<B class='text-danger'>Please Enter Correct Old Password !</B>"; }
}
elseif(isset($_REQUEST['change_sec_code'])){
	$old_sec_code = $_REQUEST['old_sec_code'];
	$new_sec_code = $_REQUEST['new_sec_code'];
	$con_sec_code = $_REQUEST['con_sec_code'];
	
	$q = query_execute_sqli("select * from users where id_user = '$id' and user_pin = '$old_sec_code' ");
	$num = mysqli_fetch_array($q);
	if($num > 0){
		if($new_sec_code == $con_sec_code){
			$sql = "UPDATE users SET user_pin = '$new_sec_code' WHERE id_user = '$id'";
			$insert_q = query_execute_sqli($sql);
			
			$_SESSION['succ_msg'] = "<B class='text-success'>Transaction Password Updated Successfully</B>";
			?> <script>window.location="index.php?page=edit-password";</script> <?php
		}
		else{ echo "<B class='text-danger'>Please enter correct confirm Security Code</B>"; }
	}
	else{ echo "<B class='text-danger'>Please Enter Correct Old Security Code !</B>"; }
}

elseif(isset($_REQUEST['transaction_pass'])){
	$sql = "select * from users where id_user = '$id' ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	while($row = mysqli_fetch_array($query)){
		$user_pin = $row['user_pin'];
		$username  = $row['username '];
		$to = $row['email'];
	}
	$_SESSION['fog_pass']="Please Check Your Email !!<br> we have sent your Transcation Password on your E-mail !!";
	
	?> <script>window.location="index.php?page=edit-password";</script> <?php
}


$sql = "select * from users where id_user = '$id'";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query))
{
	$user_pin = $row['user_pin'];
	$password  = $row['password'];
}
?>
<div class="col-md-6">
	<form name="change_pass" action="" method="post">
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="2"><h4>Login Password</h4></th></tr></thead>
		<tr>
			<th>Old Password</th>
			<td><input type="password" name="old_password" value="<?=$password;?>" class="form-control" /></td >
		</tr>
		<tr>
			<th>New Password</th>
			<td><input type="password" name="new_password" class="form-control" required /></td>
		</tr>
		<tr>
			<th>Confirm Password</th>
			<td><input type="password" name="con_new_password" class="form-control" required /></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="change_password" value="Update" class="btn btn-info"/>
			</td>
		</tr>
	</table>
	</form>
</div>
<div class="col-md-6">
	<form name="change_sec_code" action="" method="post">
	<table class="table table-bordered table-hover">
		<thead><tr><th colspan="2"><h4>Transaction Password</h4></th></tr></thead>
		<tr>
			<th>Current Transaction Password</th>
			<td><input type="password" name="old_sec_code" class="form-control" value="<?=$user_pin;?>" /></td>
		</tr>
		<tr>
			<th>New Transaction Password</th>
			<td><input type="password" name="new_sec_code" class="form-control"	required /></td >
		</tr>
		<tr>
			<th>Confirm Transaction Password</th>
			<td><input type="password" name="con_sec_code" class="form-control" required /></td>
		</tr>
		<tr>
			<!--<td>
				<a href="#dialog-forgot_sec_code" data-toggle="modal" class="btn btn-info">
					Forgot Transaction Password
				</a>
			</td>-->
			<td class="text-center" colspan="2">
				<input type="submit" name="change_sec_code" value="Update" class="btn btn-info"/>
			</td>
		</tr>
	</table>
	
	</form>
</div>
