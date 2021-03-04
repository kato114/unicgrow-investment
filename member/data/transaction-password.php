<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");

include("function/send_mail.php");
?>
<!--<h1 align="left">Transaction Password</h1>-->
<?php
$user_id = $_SESSION['mlmproject_user_id'];
if(isset($_POST['submit']))
{
	$title = 'Regenerate';
	$message = 'Regenerate Transaction Password';
	data_logs($user_id,$title,$message,0);
	
	$user_pin = mt_rand(100000, 999999);
	query_execute_sqli("update users set user_pin = '$user_pin' where id_user = '$user_id' ");
	
	$title = "Transaction Password generate message";
	$to = get_user_email($user_id);
	$username = $_SESSION['mlmproject_user_name'];
	$db_msg = $user_pin_generate_message;
	include("function/full_message.php");
	$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
	$SMTPChat = $SMTPMail->SendMail();
	
	print "Transaction Password Regenerate Successfully !!";
}
else
{ ?>
	<table width="650" border="0" align="center" height="200">
		<form name="user_pin_form" action="index.php?page=transaction-password" method="post" >
			<tr><td style="font-size:18px; color:#CC0000;"><strong>Regenerate Security Password</strong></td>
			<td><input type="submit" name="submit" value="Generate" class="normal-button"/></td></tr>
		</form>
	</table>

<?php } ?>