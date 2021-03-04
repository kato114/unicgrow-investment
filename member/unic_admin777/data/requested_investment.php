<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");
include("../function/wallet_message.php");

if(isset($_POST['submit']))
{

	$req_id = $_REQUEST['id'];
	$u_id = $_REQUEST['u_id'];
	$req_amount = $_REQUEST['req_amount'];
	$query = query_execute_sqli("select * from wallet where id = '$u_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$total_amount = $row['amount'];
	}
	
		
		$accept_date= date('Y-m-d');
		query_execute_sqli("update add_funds set app_date = '$accept_date' , mode = 1 where id = '$req_id' ");
		
		$username_log = get_user_name($u_id);
		$amount = $req_amount;
		$date = $accept_date;
		include("../function/logs_messages.php");
		data_logs($u_id,$data_log[14][0],$data_log[14][1],$log_type[6]);
		
		$from_user = $u_id;
		$to_user = $u_id;
		$phone_to = get_user_phone($u_id);
		include("../function/sms_message.php");
		send_sms($url_sms,$request_yourself,$phone_to);  //send sms

		$bal_amount = $total_amount+$req_amount;		
		query_execute_sqli("update wallet set amount = '$bal_amount' , date = '$accept_date' where id = '$u_id' ");
		
		$log_username = get_user_name($u_id);
		$income_log = $req_amount;
		$date = $accept_date;
		$income_type_log = "Requested Fund from ADMIN";
		include("../function/logs_messages.php");
		data_logs($u_id,$data_log[4][0],$data_log[4][1],$log_type[4]);
		
		$pay_request_username = get_user_name($u_id);
		$request_amount = $req_amount;
		$to = get_user_email($u_id);
		$title = "Payment Transfer Message";
		$db_msg = $payment_transfer_message;
		include("../function/full_message.php");
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
		$SMTPChat = $SMTPMail->SendMail();
		
		print "Request Accepted!";
}
else
{
	$mg = $_REQUEST[mg]; echo $mg;
	$query = query_execute_sqli("select * from add_funds where mode = 0 and amount > 0 ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		print " 
					
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=700>
			
			<tr><td class=\"message tip\"><strong>User Name</strong></td><td class=\"message tip\"><strong>Request Amount</strong></td><td class=\"message tip\"><strong>Payment Mode</strong></td></td><td class=\"message tip\"><strong>Date</strong></td><td class=\"message tip\"><strong>Action</strong></td><td class=\"message tip\"><strong>Information</strong></td></tr>";
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$u_id = $row['user_id'];
			$username = get_user_name($u_id);
			$request_amount = $row['amount'];
			$request_date = $row['date'];
			$payment_mode = $row['payment_mode'];
			print "<tr><td class=\"input-medium\">$username</td><td class=\"input-medium\"><small>$request_amount RC</small></td><td class=\"input-medium\"><small>$payment_mode</small></td><td class=\"input-medium\"><small>$request_date</small></td>
				
				<td class=\"input-medium\"><form name=\"inact\" action=\"index.php?page=requested_add_funds\" method=\"post\">
					<textarea name=\"information\" class=\"input-medium\" > </textarea></td><td class=\"input-medium\">
					<input type=\"hidden\" name=\"id\" value=\"$id\" />
					<input type=\"hidden\" name=\"u_id\" value=\"$u_id\" />
					<input type=\"hidden\" name=\"req_amount\" value=\"$request_amount\" />
					<input type=\"submit\" name=\"submit\" value=\"Accept\" class=\"button\" />					
				</td></form></tr>";
		}
		print "</table>";	
	}
	else{ print "There is no request !"; }
 }  ?>
 
 