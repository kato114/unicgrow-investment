<?php
include('../security_web_validation.php');
?>
<?php
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");

include("function/wallet_message.php");
include("function/check_income_condition.php");
?>
<h1 align="left">Internal Transfer</h1>
<?php

$id = $_SESSION['mlmproject_user_id'];

$ccc=1;
if($ccc == 1)    //check_income_condition($id) == 1)
{
	if(isset($_POST['submit']))
	{
		if($_POST['submit'] =='Request')
		{
			$_SESSION['ses'] = 1;
			$user_pin = $_REQUEST['user_pin'];
			$current_amount = $_REQUEST['curr_amnt'];
			$request_amount = $_REQUEST['request'];
			$requested_user = $_REQUEST['requested_user'];
			$requested_user_id = get_new_user_id($requested_user);
			if($requested_user_id == 0)
			{
				print "Please Enter correct Username !";
			}
			else
			{
				$inc_chk = validate_request_amount($request_amount); 
				if($inc_chk == 1)
				{	
					$query = query_execute_sqli("select * from users where id_user = '$id' and user_pin = '$user_pin' ");
					$num = mysqli_num_rows($query);
					if($num > 0)
					{
						if($request_amount <= $current_amount)
						{
							$to = get_user_email($id);
							$title = "Security Pin For Fund Transfer";
							$unique_epin = mt_rand(1000000000, 9999999999);
							$date = date('Y-m-d');
							query_execute_sqli("insert into security_password (user_id , security_password , date , mode) values ('$id' , '$unique_epin' , '$date' , 1) ");
								
							$full_message = "Hello user ".$_SESSION['mlmproject_user_name']." , Your Fund Transfer SECURITY PIN is : ".$unique_epin;
							$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
							$SMTPChat = $SMTPMail->SendMail();
						
						?>
							
							<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=500>
							<form name="money" action="index.php?page=transfer-to-member" method="post">
							<input type="hidden" name="curr_amnt" value="<?php echo $current_amount; ?>"  />
							<input type="hidden" name="request" value="<?php echo $request_amount; ?>"  />
							<input type="hidden" name="requested_user_id" value="<?php echo $requested_user_id; ?>"  />
							  <tr>
								<td colspan="2" class="td_title"><strong>Your Transaction Information</strong></td>   
							  </tr>
							  
							  <tr>
								<td colspan="2">&nbsp;</td>   
							  </tr>
							  <tr>
								<td colspan="2">Please check security pin to your email address.</td>   
							  </tr>
							   <tr>
								<td colspan="2">&nbsp;</td>   
							  </tr>
							  <tr>
								<td class="td_title" align="left"><h3>Current Balance</h3></td><th align="left"> <?php echo $current_amount." &#36; ";  ?></th>
							  </tr>
							  <tr>
								<td colspan="2">&nbsp;</td>   
							  </tr>
							  <tr>
							   <td class="td_title"><h3>Transfer Amount :</h3></td>
								<th  style="margin-top:-10px;" align="left"><?php print $request_amount; ?>&#36; </th>
							  </tr>
							  <tr>
								<td colspan="2">&nbsp;</td>   
							  </tr>
							  <tr>
								<td class="td_title"><h3> User Id :</h3></td>
								<th align="left"><?php print $requested_user; ?></th>
							  </tr>
							  <tr>
								<td colspan="2">&nbsp;</td>   
							  </tr>
							  <tr>
								<td class="td_title"><h3> User Name :</h3></td>
								<th align="left" style="padding-bottom:5px;"><?php print get_full_name($requested_user_id); ?></th>
							  </tr>
							  <tr>
								<td colspan="2">&nbsp;</td>   
							  </tr>
							  <tr>
								<td class="td_title"><h3>Verify Security :</h3></p></td>
								<td style="padding-bottom:23px;"><input type="text" name="security_pass" style="width:120px;"  class="input-medium" /></td>
							  </tr>
							  <tr>
								<td colspan="2">&nbsp;</td>   
							  </tr>
							  <tr>
								<td colspan="2"><p align="center"><input type="submit" name="submit" value="Submit" class="normal-button"/></p></td>   
							  </tr>
							  </form>
							</table>
							
				<?php		}	
						else { print "You request balance is not available in your wallet"; }
					}
					else { print "Please enter correct user pin !"; }
				}
				else { print "<font color=\"#FF0000\" size=\"+1\">Request of transfer amount can not be completed.<br> Please Enter An Integer Amount for Transfer !!</font>"; }				
			}	
		}
		elseif($_POST['submit'] == 'Submit' and $_SESSION['ses'] == 1)
		{
			$date = date('Y-m-d');
			$trns = query_execute_sqli("select * from transfer_count where user_id = '$id' and date = '$date' ");
			$tr_cnt = mysqli_num_rows($trns);
			$transfer_count = 0;
			if($tr_cnt >  0)
			{
				while($trns_row = mysqli_fetch_array($trns))
					$transfer_count = $trns_row['tr_count'];
			}		
			if($tr_cnt == 0 or $transfer_count < $max_transfer_count)
			{
				if($tr_cnt >  0)
				{
					$nxt_trns = $transfer_count+1;
					query_execute_sqli("update transfer_count set tr_count = '$nxt_trns' where user_id = '$id' and date = '$date' ");
				}
				else 
				{ 
					query_execute_sqli("insert into transfer_count (user_id , tr_count , date) values ('$id' , 1 , '$date') ");
				}
				$current_amount = $_REQUEST['curr_amnt'];
				$request_amount = $_REQUEST['request'];
				$requested_user_id = $_REQUEST['requested_user_id'];
				$security_pass = $_REQUEST['security_pass'];
				$qur = query_execute_sqli("select * from security_password where security_password = '$security_pass' and mode = 1 and user_id = '$id' ");
				$pass_num = mysqli_num_rows($qur);
				if($pass_num > 0)
				{
					$left_amount = $current_amount-$request_amount;
					$query = query_execute_sqli("select * from wallet where id = '$requested_user_id' ");
					while($row = mysqli_fetch_array($query))
					{
						$wallet_amount = $row['amount'];
						$total_amount = $wallet_amount+$request_amount;
					}	
					$request_date= date('Y-m-d');
					query_execute_sqli("update wallet set amount = '$total_amount' , date = '$request_date' where id = '$requested_user_id' ");
					query_execute_sqli("update wallet set amount = '$left_amount' , date = '$request_date' where id = '$id' ");
					query_execute_sqli("update security_password SET mode = 0 WHERE security_password = '$security_pass' and user_id = '$id' ");
						
					$username_log = get_user_name($id);
					$income_log = $request_amount;
					$pay_to_request_username = get_user_name($requested_user_id);
					$for = "Transfer to Username ".$pay_to_request_username;
					$date = date('Y-m-d');
					include("function/logs_messages.php");
					data_logs($id,$data_log[8][0],$data_log[8][1],$log_type[4]);
					
					$log_username = get_user_name($requested_user_id);
					$income_log = $request_amount;
					$pay_to_request_username = get_user_name($id);
					$income_type_log = "Received amount from Username ".$pay_to_request_username;
					$date = date('Y-m-d');
					include("function/logs_messages.php");
					data_logs($requested_user_id,$data_log[4][0],$data_log[4][1],$log_type[4]);
							
							//email
					$pay_request_username = get_user_name($id);
					$pay_to_request_username = get_user_name($requested_user_id);
					$to = get_user_email($requested_user_id);  //message foe mail
					$title = "Payment Request Message";
					$db_msg = $transfer_to_member_message;
					include("function/full_message.php");
					$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
					$SMTPChat = $SMTPMail->SendMail();
							
					print "You request of transfer amount ".$request_amount." &#36; has been completed successfully!";
					$_SESSION['ses'] = 0;
				}
				else { print "Please Enter correct Security Password !!"; }
			}
			else { print "Sorry Your Today's Transfer Limit is over!"; }	
		}
		else { print "There is some conflicts!"; }		
	}
	else
	{
		$date = date('Y-m-d');
		$trns = query_execute_sqli("select * from transfer_count where user_id = '$id' and date = '$date' ");
		$tr_cnt = mysqli_num_rows($trns);
		$transfer_count = 0;
		if($tr_cnt >  0)
		{
			while($trns_row = mysqli_fetch_array($trns))
				$transfer_count = $trns_row['tr_count'];
		}		
		if($tr_cnt == 0 or $transfer_count < $max_transfer_count)
		{
			$query = query_execute_sqli("select amount from wallet where id = '$id' ");
			while($row = mysqli_fetch_array($query))
			{
				$curr_amnt = $row[0];
			}	
			
			
			$msg = $_REQUEST[mg]; echo $msg; ?> 
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=500>
			<form name="money" action="index.php?page=transfer-to-member" method="post">
			<input type="hidden" name="curr_amnt" value="<?php echo $curr_amnt; ?>"  />
		<!--	  <tr>
				<td colspan="2" class="td_title"><strong>Your Wallet Information</strong></td>   
			  </tr>
		-->	  
		<p></p><tr>
				<td  class="td_title"><h3 style="width:250px;">Current Balance</h3></td><td style="width:250px;">  <?php echo $curr_amnt." &#36; ";  ?></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>   
			  </tr>
			  <tr>
			   <td class="td_title"><h3 style="width:250px;">Request Amount :</h3></td>
				<td style="width:250px;"><p style="padding-bottom:10px"><input type="text" name="request" class="input-small" /> &#36; </p></td>
			  </tr>
			  <tr>
				<td class="td_title"><h3>Requested Username :</h3></td>
				<td ><p style="padding-bottom:10px; padding-left:9px;"><input type="text" name="requested_user" style="width:120px;"  class="input-medium" /></p></td>
			  </tr>
			  <tr>
				<td class="td_title"><h3>Transaction Password :</h3></td>
				<td ><p style="padding-bottom:10px; padding-left:9px;"><input type="text" name="user_pin" style="width:120px;"  class="input-medium" /></p></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>   
			  </tr>
			  <tr>
				<td colspan="2"><p align="center"><input type="submit" name="submit" value="Request" class="button_submit" /></p></td>   
			  </tr>
			  </form>
			</table>
	
	<?php  
		}
		else { print "<font style=\"color:#FF0000\"><strong>Sorry Your Today's Transfer Limit is over!</strong></font>"; }
	}   
}
else
{
	print "<font style=\"color:#FF0000\"><strong>You Are not Authorised To Transfer Money</strong></font>";
}	
