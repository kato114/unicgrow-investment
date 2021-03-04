<?php
include('../security_web_validation.php');
session_start();
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");

$login_id = $_SESSION['mlmproject_user_id'];

if(isset($_SESSION['msgs_sucs_pass']))
{
	echo $_SESSION['msgs_sucs_pass'];
	unset($_SESSION['msgs_sucs_pass']);
}

if(isset($_POST['change_sec_pass'])){
	$user_pin = mt_rand(100000, 999999);
	$sql = "UPDATE users SET user_pin = '$user_pin' WHERE id_user = '$login_id'";
	query_execute_sqli($sql);
	
	$_SESSION['msgs_sucs_pass'] = "<B class='text-success'>Your Transaction Password is <span class='text-danger'>$user_pin</span><br /> Transaction Password Send to Successfully on your E-mail and Phone !! </B>";
	
	$username = get_user_name($login_id);
	$mesgs = "Hi $username, Your Transaction Password is $user_pin. Thanks https://www.unicgrow.com";
	send_sms(get_user_phone($login_id),$mesgs);
	
	$title = "Transaction Password ";
	$to = get_user_email($login_id);
	$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);	
	
	?> <script>window.location = "index.php?page=edit-password";</script> <?PHP
}

if(isset($_POST['change_sec_code']))
{
	$old_sec_code = $_POST['old_sec_code'];
	$new_sec_code = $_POST['new_sec_code'];
	$con_sec_code = $_POST['con_sec_code'];
	
	$q = query_execute_sqli("SELECT * FROM users WHERE id_user = '$login_id' AND user_pin = '$old_sec_code' ");
	$num = mysqli_fetch_array($q);
	if($num > 0)
	{
		if($new_sec_code == $con_sec_code)
		{
			$sql = "UPDATE users SET user_pin = '$new_sec_code' WHERE id_user = '$login_id'";
			query_execute_sqli($sql);
			
			$_SESSION['msgs_sucs_pass'] = "<B class='text-success'>Security Code Updated Successfully</B>";
			?> <script>window.location = "index.php?page=edit-password";</script> <?PHP
		}
		else{ echo "<B class='text-danger'>Please enter correct confirm Security Code</B>"; }
	}
	else{ echo "<B class='text-danger'>Please Enter Correct Old Security Code !</B>"; }
}

if(isset($_POST['change_password']))
{
	$old_password = $_POST['old_password'];
	$new_password = $_POST['new_password'];
	$con_new_password = $_POST['con_new_password'];
	
	$sql = "SELECT * FROM users WHERE id_user = '$login_id' AND password = '$old_password' ";
	$q = query_execute_sqli($sql);
	$num = mysqli_fetch_array($q);
	if($num > 0)
	{
		if($new_password == $con_new_password)
		{
			while($row = mysqli_fetch_array($query))
			{
				$email = $row['email'];
				$phone_no = $row['phone_no'];
			}
			$_SESSION['random_pass'] = $rand_no = rand(10000 , 99999);
			
			$title = "Verification Code";
			$to = $email;
			$full_message = "Your verification OTP code is ".$rand_no." https://www.unicgrow.com";
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);	
			send_sms($phone_no,$full_message);
			?>
			<form action="" method="post">
				<input type="hidden" name="old_password" value="<?=$old_password?>" />
				<input type="hidden" name="new_password" value="<?=$new_password?>" />
				<input type="hidden" name="con_new_password" value="<?=$con_new_password?>" />
				<table class="table table-bordered table-hover">
					<tr><th colspan="3"><h4>OTP Password :</h4> </th></tr>
					<tr>
						<th>Enter OTP Code</th>
						<td><input type="text" name="sec_code" class="form-control" /></td>
						<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
					</tr>
				</table>
			</form>
			<?php
		}
		else 
		{ echo "<B style='color:#FF0000'>Please Enter same Password in both  New and Confirm password Field !</B>"; }
	}
	else { echo "<B style='color:#FF0000'>Please Enter Correct Old Password !</B>"; }	
}

elseif(isset($_POST['submit']))
{
	$old_password = $_POST['old_password'];
	$new_password = $_POST['new_password'];
	$con_new_password = $_POST['con_new_password'];
	
	if($_POST['new_password'] == $_POST['con_new_password'])
	{
		$sql = "UPDATE users SET password = '$new_password' WHERE id_user = '$login_id'";
		$insert_q = query_execute_sqli($sql);
		
		$username = get_user_name($login_id);
		$date = date('Y-m-d');
		$updated_by = $username." Your self";
		include("function/logs_messages.php");
		data_logs($login_id,$data_log[2][0],$data_log[2][1],$log_type[1]);
		
		$_SESSION['msgs_sucs_pass'] =  "<B style='color:#008000;'>Password Updated Successfully</B>";
		?> <script type="text/javascript">window.location = "index.php?page=edit-password";</script> <?php
		
		unset($_SESSION['random_pass']);
	}
	else{ echo "<B class='text-danger'>Please Enter Correct Password in Both field ! </B>"; }
}

else
{
	$sql = "select * from users where id_user = '$login_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{
		$user_pin = $row['user_pin'];
		$password  = $row['password'];
	}
	?>
	
		<div class="col-md-12">
			<div class="pull-right">
			<form action="" method="post">
				<input type="submit" name="change_sec_pass" value="Generate Transaction Password" class="btn btn-primary" onclick="javascript:return confirm(&quot; Are You Sure? You want to Change Your Transaction Password &quot;);" />
			</form>
			</div>
		</div>
		<div class="col-md-12">&nbsp;</div>
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
					<input type="submit" name="submit" value="Update" class="btn btn-info" onclick="javascript:return confirm(&quot; Are You Sure? want to Change Your Password &quot;);" />
				</td>
			</tr>
		</table>
		</form>
	<?php
}
?>
