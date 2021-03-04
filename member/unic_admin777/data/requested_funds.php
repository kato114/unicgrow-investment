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
	$pay_to = $_REQUEST['pay_to'];
	$req_amount = $_REQUEST['req_amount'];
	$query = query_execute_sqli("select * from wallet where id = '$u_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$total_amount = $row['amount'];
	}
	if($req_amount > $total_amount)
	{
		header("location:requested_funds.php?mg=Requested amount $req_amount is not available in the wallet of User ID : <strong>$u_id</strong> !<br> Tour current Balance is $total_amount.");
	}
	else
	{
		
		$accept_date= date('Y-m-d');
		if($pay_to == 0)
		{
			query_execute_sqli("update paid_unpaid set paid_date = '$accept_date' , paid = 1 where id = '$req_id' ");
			$pos = get_user_position($req_id);
			data_logs($req_id,$pos,$data_log[6][0],$data_log[6][1],$log_type[5]);
			
			$position = get_user_position($u_id);
			$requested_user = 0;
			$payee_user = get_user_name($u_id);
			$wallet_message[0] = request_approval_message(0,$payee_user,$req_amount,$requested_user);
			data_logs($u_id,$position,$data_log[5][1],$wallet_message,$log_type[5]);
			
			$from_user = $payee_user;
			$to_user = $requested_user;
			$phone_to = get_user_phone($u_id);
			include("../function/sms_message.php");
			send_sms($url_sms,$request_yourself,$phone_to);  //send sms
		}
		else 
		{
			query_execute_sqli("update paid_unpaid set paid_date = '$accept_date' , paid = 1 where id = '$req_id' ");
			$pos = get_user_position($req_id);
			data_logs($req_id,$pos,$data_log[6][0],$data_log[6][1],$log_type[5]);
			update_member_wallet($pay_to,$req_amount,$data_log,$log_type);
			
			$position = get_user_position($id);
			$requested_user = get_user_name($pay_to);
			$payee_user = get_user_name($u_id);
			$wallet_message[0] = request_approval_message(1,$payee_user,$req_amount,$requested_user);
			data_logs($u_id,$position,$data_log[5][5],$wallet_message,$log_type[5]);
			
			$phone_to = get_user_phone($pay_to);
			include("../function/sms_message.php");
			send_sms($url_sms,$requested_to,$phone_to);  //send sms
			
			$from_user = $payee_user;
			$to_user = $requested_user;
			$phone_to = get_user_phone($u_id);
			include("../function/sms_message.php");
			send_sms($url_sms,$requested_from,$phone_to);  //send sms
		}
		$bal_amount = $total_amount-$req_amount;		
		query_execute_sqli("update wallet set amount = '$bal_amount' , date = '$accept_date' where id = '$u_id' ");
			$pos = get_user_position($u_id);
			$wall_msg = "Request from ".$payee_user." of amount ".$req_amount." &#36; has accepted";
			data_logs($u_id,$pos,$data_log[5][1],$wall_msg,$log_type[4]);

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
}
else
{
	$mg = $_REQUEST[mg]; echo $mg;
	$query = query_execute_sqli("select * from paid_unpaid where paid = 0 and amount > 0 ");
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
			$pay_to = $row['pay_to'];
			$request_amount = $row['amount'];
			$request_date = $row['request_date'];
			$payment_mode = $row['payment_mode'];
			print "<tr><td class=\"input-medium\">$username</td><td class=\"input-medium\"><small>$request_amount RC</small></td><td class=\"input-medium\"><small>$payment_mode</small></td><td class=\"input-medium\"><small>$request_date</small></td>
				
				<td ><form name=\"inact\" action=\"index.php?page=requested_funds\" method=\"post\">
					<textarea name=\"information\" class=\"input-medium\" > </textarea></td><td>
					<input type=\"hidden\" name=\"id\" value=\"$id\" />
					<input type=\"hidden\" name=\"u_id\" value=\"$u_id\" />
					<input type=\"hidden\" name=\"pay_to\" value=\"$pay_to\" />
					<input type=\"hidden\" name=\"req_amount\" value=\"$request_amount\" />
					<input type=\"submit\" name=\"submit\" value=\"Accept\" class=\"button\" />					
				</td></form></tr>";
		}
		print "</table>";	
	}
	else{ print "There is no request !"; }
 }  ?>
 
 