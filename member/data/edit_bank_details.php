<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");

include("function/send_mail.php");

$user_id = $_SESSION['mlmproject_user_id'];

if(isset($_POST['submit']))
{
	$title = 'Regenerate';
	$message = 'Regenerate Transaction Password';
	$phone = $_POST['phone'];
	data_logs($user_id,$title,$message,0);
	
	$user_pin = mt_rand(100000, 999999);
	query_execute_sqli("update users set password = '$user_pin' where id_user = '$user_id' ");
	
	$title = "Transaction Password generate message";
	include("function/full_message.php");
	$message = "Security Pin - $user_pin, www.canindia.co.in";
	send_sms($phone , $message);	
	
	$title = "Security Password Regenerate";
	$to = get_user_email($user_id);
	$from = "alert@canindia.co.in";
	
	$db_msg = "Security Password is - $user_pin . <br>Security Password Regenerate Successfully !! ";
	include("function/full_message.php");
		
	$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);	
	$SMTPChat = $SMTPMail->SendMail();	
	
	print "Security Password is - $user_pin . <br>Security Password Regenerate Successfully !! Check your registered mobile number for security password. <a href=index.php?page=edit_bank_details>Click Here for Go Back</a>";
}
elseif(isset($_POST['Update']))
{
	$security_pass = $_POST['security_pass'];
	$sql = "select * from users where password = '$security_pass' and id_user = '$user_id' ";
	$query = query_execute_sqli($sql);
 	$num = mysqli_num_rows($query);
	
	if($num > 0)
	{	
		$bank = $_POST['bank'];
		$bank_code =$_POST['bank_code'];
		$beneficiery_name = $_POST['beneficiery_name'];
		$ac_no =$_POST['ac_no'];
		$branch = $_POST['branch'];
		$pan_no = $_POST['pan_no'];
		
		$quwr = query_execute_sqli("select * from users where pan_no = '$pan_no' ");
		$pan_chk = mysqli_num_rows($quwr);
		if($pan_chk > 3)
		{	
		$error_pan_no = "<B style=\"color:#FF0000;font-size:12pt;\">PAN No. already used of 3 times!</B>";	
		}
		else
		{
			$sql_bank = "UPDATE users SET bank = '$bank' , bank_code = '$bank_code' , beneficiery_name = '$beneficiery_name' , ac_no = '$ac_no' , branch = '$branch' , pan_no = '$pan_no' WHERE id_user = '$user_id' ";
			query_execute_sqli($sql_bank);
			
			print "<font color=\"green\" size=+0>Successfully Update</font>";
		}
	}
	else
	{
		print "<font color=\"red\" size=+0>Please Enter Correct Transaction Password</font>";
	}	
}



	$query = query_execute_sqli("select * from users where id_user = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$ac_no = $row['ac_no'];
		$bank = $row['bank'];
		$branch = $row['branch'];
		$bank_code = $row['bank_code'];
		$beneficiery_name = $row['beneficiery_name'];
		$pan_no = $row['pan_no'];
		$phone_no = $row['phone_no'];
	}	

?>
<form name="user_pin_form" action="index.php?page=edit_bank_details" method="post">
	<input type="hidden" name="phone" value="<?=$phone_no?>" />
	<h4 class="page-header">Regenerate Security Password : 
		<small style="padding-left:150px;">
			<code><input type="submit" name="submit" value="Generate" class="btn btn-primary" /></code>
		</small>
	</h4>
</form>
<form method="post">
	<table class="table table-bordered table-hover">		
		<thead><tr><th colspan="3">Edit Bank Details:-</th></tr></thead>
		<tr>
			<td>Security Password</td>
			<td><input type="text" name="security_pass" value="" required /></td>
			<td><?=$error_bank;?></td>
		</tr>
		<thead><tr><th colspan="3">A/C Details:-</th></tr></thead>	
		<tr>
			<td>Bank Name</td>
			<td><input type="text" name="bank" value="<?=$bank;?>" required /></td>
			<td><?=$error_bank;?></td>
		</tr>
		<tr>
			<td>Branch</td>
			<td><input type="text" name="branch" value="<?=$branch;?>" required /></td>
			<td><?=$error_bank;?></td>
		</tr>
		<tr>
			<td>IFSC/MI&#36; Code</td>
			<td><input type="text" name="bank_code" value="<?=$bank_code;?>" required /></td>
			<td><?=$error_bank_code;?></td>
		</tr>
		<tr>
			<td>Beneficiery Name</td>
			<td><input type="text" name="beneficiery_name" value="<?=$beneficiery_name;?>" required /></td>
			<td><?=$error_beneficiery_name;?></td>
		</tr>
		<tr>
			<td>Account No.</td>
			<td><input type="text" name="ac_no" value="<?=$ac_no;?>" required /></td>
			<td><?=$error_ac_no;?></td>
		</tr>
		<tr>
			<td>PAN No.</td>
			<td><input type="text" name="pan_no" value="<?=$pan_no;?>" required /></td>
			<td><?=$error_pan_no;?></td>
		</tr>
		<tr>
			<td colspan="3" class="span1 text-center">
				<input type="submit" name="Update" value="Update" class="btn btn-primary" /> 
			</td>
		</tr>
	</table>
</form>

<?php  ?>