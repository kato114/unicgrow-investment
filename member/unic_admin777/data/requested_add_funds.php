<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");
include("../function/wallet_message.php");

if(isset($_POST['submit']) and ($_SESSION['dccan_admin_login'] == 1))
{
	if($_POST['submit'] == 'Accept')
	{

		$req_id = $_REQUEST['id'];
		$u_id = $_REQUEST['u_id'];
		$information = $_REQUEST['information'];
		$req_amount = $_REQUEST['req_amount'];
		$query = query_execute_sqli("select * from wallet where id = '$u_id' ");
		while($row = mysqli_fetch_array($query))
		{
			$total_amount = $row['amount'];
		}
	
		
		$accept_date= date('Y-m-d');
		query_execute_sqli("update add_funds set app_date = '$accept_date' , information = '$information' , mode = 1 where id = '$req_id' ");
		
		$bal_amount = $total_amount+$req_amount;		
		query_execute_sqli("update wallet set amount = '$bal_amount' , date = '$accept_date' where id = '$u_id' ");
		
		
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
		
		print "Request Accepted Successfully!";
	}
	elseif($_POST['submit'] == 'Cancel')
	{
		$req_id = $_REQUEST['id'];
		$accept_date= date('Y-m-d');
		$information = $_REQUEST['information'];
		query_execute_sqli("update add_funds set app_date = '$accept_date' , information = '$information' , mode = 2 where id = '$req_id' ");
		print "Request Cancelled Successfully !";
	}
	else { }	
}
else
{
	
	$newp = $_GET['p'];
	$plimit = "5";
	
	$mg = $_REQUEST[mg]; echo $mg;
	$query = query_execute_sqli("select * from add_funds where mode = 0 and amount > 0 ");
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0)
	{
		print " 
					
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=940>
			
			<tr>
			<td class=\"message tip\" align=\"center\"><strong>User Name</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Request Amount</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Payment Mode</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Date</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Information</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Action</strong></td>
			</tr>";
		
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	
		$query = query_execute_sqli("select * from add_funds where mode = 0 and amount > 0 LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$u_id = $row['user_id'];
			$username = get_user_name($u_id);
			$request_amount = $row['amount'];
			$request_date = $row['date'];
			$payment_mode = $row['payment_mode'];
			print "<tr><td class=\"input-medium\" align=\"center\"><a href=\"index.php?page=requested_add_funds_info&inf=$id\">$username</a></td><td class=\"input-medium\" align=\"center\"><small> $request_amount RC</small></td><td class=\"input-medium\" align=\"center\"><small>$payment_mode</small></td><td class=\"input-medium\" align=\"center\"><small>$request_date</small></td>
				
				<td  class=\"input-medium\"><center><form name=\"inact\" action=\"index.php?page=requested_add_funds\" method=\"post\">
					<textarea name=\"information\" class=\"input-medium\" style=\"height:30px; width:150px\" > </textarea></td><td class=\"input-medium\">
					<input type=\"hidden\" name=\"id\" value=\"$id\" />
					<input type=\"hidden\" name=\"u_id\" value=\"$u_id\" />
					<input type=\"hidden\" name=\"req_amount\" value=\"$request_amount\" />
					<input type=\"submit\" name=\"submit\" value=\"Cancel\" />	</center>
					<input type=\"submit\" name=\"submit\" value=\"Accept\" />	</center>					
				</td></form></tr>";
		}
		print "<tr><td colspan=6 >&nbsp;</td></tr><td colspan=6 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=requested_add_funds&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=requested_add_funds&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=requested_add_funds&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print "</table>";	
	}
	else{ print "There is no request !"; }
 }  ?>
 
 