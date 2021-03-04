<?php
include('../security_web_validation.php');
?>
<?php
session_start();
ini_set('display_errors','on');
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");

include("function/wallet_message.php");
include("function/check_income_condition.php");

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
			$current_amount = get_user_bonus_wallet($id);
			$request_amount = $_SESSION['request_amount'] = $_REQUEST['request'];
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
					$query = query_execute_sqli("select * from users where id_user = '$id' and password = '$user_pin' ");
					$num = mysqli_num_rows($query);
					if($num > 0)
					{
						if($request_amount <= $current_amount)
						{
							while($rowa = mysqli_fetch_array($query))
							{
								$email = $rowa['email'];
								$phone_no = $rowa['phone_no'];
							}
							$to = $email;
							$title = "Security Pin For Fund Transfer";
							$unique_epin = mt_rand(1000000000, 9999999999);
							$date = date('Y-m-d');
							query_execute_sqli("insert into security_password (user_id , security_password , date , mode) values ('$id' , '$unique_epin' , '$date' , 1) ");
								
							$full_message = "Hello user ".$_SESSION['rsmb_user_name']." , Your Fund Transfer SECURITY PIN is : ".$unique_epin ."www.go-digital.co.in";
							$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
							$SMTPChat = $SMTPMail->SendMail();
							
							send_sms($phone_no,$full_message);
						
						?>
					<form name="money" action="index.php?page=transfer-to-member" method="post">
					<input type="hidden" name="request" value="<?=$request_amount;?>"  />
					<input type="hidden" name="requested_user_id" value="<?=$requested_user_id;?>" />
					<table class="table table-bordered table-hover">
						<thead><tr><th colspan="2">Your Transaction Information</th></tr></thead>
						<tr><td colspan="2">Please check security pin to your email address.</td></tr>
						<tr>
							<th>Current Repurchase Balance</th>
							<th><?=round($current_amount,2)." &#36; ";?></th>
						</tr>
						<tr>
							<th>Transfer Amount :</th>
							<td><?=$request_amount;?> &#36; </td>
						</tr>
						<tr>
							<th>User Id :</th>
							<td><?=$requested_user;?></td>
						</tr>
						<tr>
							<th>User Name :</th>
							<td><?=get_full_name($requested_user_id);?></td>
						</tr>
						<!--<tr>
							<td>Verify Security :</td>
							<td><input type="text" name="security_pass" /></td>
						</tr>-->
						<tr>
						<td colspan="2" class="text-center">
							<input type="submit" name="submit" value="Submit" class="btn btn-success"/>
						</td>   
						</tr>
					</table>
					</form>
							
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
				$current_amount = get_user_bonus_wallet($id);
				$req_amount = $_REQUEST['request'];
				$req_user_id = $_REQUEST['requested_user_id'];
				$security_pass = $_REQUEST['security_pass'];
				
				if($_SESSION['request_amount'] == $req_amount)
				{
					//$qur = query_execute_sqli("select * from security_password where security_password = '$security_pass' and mode = 1 and user_id = '$id' ");
					//$qur = query_execute_sqli("select * from users where id_user = '$id' and password = '$security_pass' ");
					//$pass_num = mysqli_num_rows($qur);
					//if($pass_num > 0)
					//{
						$request_date= date('Y-m-d');
						query_execute_sqli("update wallet set roi = roi+'$req_amount' , date = '$request_date' where id = '$req_user_id' ");
						query_execute_sqli("update wallet set roi = roi-'$req_amount' , date = '$request_date' where id = '$id' ");
						query_execute_sqli("update security_password SET mode = 0 WHERE security_password = '$security_pass' and user_id = '$id' ");
						
						$wal_bal = wallet_balance($id);
						insert_wallet_account($id, $id, $req_amount, $date, $ac_type[8],$ac_type_desc[8], 2, $wal_bal);
						$wal_bal = wallet_balance($req_user_id);
						insert_wallet_account($req_user_id, $req_user_id, $req_amount, $date, $ac_type[9],$ac_type_desc[9], 1, $wal_bal);
						
						$username_log = get_user_name($id);
						$income_log = $request_amount;
						$pay_to_request_username = get_user_name($req_user_id);
						$for = "Transfer to Username ".$pay_to_request_username;
						$date = date('Y-m-d');
						include("function/logs_messages.php");
						data_logs($id,$data_log[8][0],$data_log[8][1],$log_type[4]);
						
						$log_username = get_user_name($req_user_id);
						$income_log = $request_amount;
						$pay_to_request_username = $username_log;
						$income_type_log = "Received amount from Username ".$pay_to_request_username;
						$date = date('Y-m-d');
						include("function/logs_messages.php");
						data_logs($req_user_id,$data_log[4][0],$data_log[4][1],$log_type[4]);
								
								//email
						$pay_request_username = $username_log;
						$pay_to_request_username = $pay_to_request_username;
						$to = get_user_email($req_user_id);  //message foe mail
						$title = "Payment Request Message";
						$db_msg = $transfer_to_member_message;
						include("function/full_message.php");
						$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
						$SMTPChat = $SMTPMail->SendMail();
								
						echo "<B style=\"color:#008000;\">You request of transfer amount ".$req_amount." &#36; has been completed successfully!</B>";
						$_SESSION['ses'] = 0;
						unset($_SESSION['request_amount']);
					//}
					//else { print "Please Enter correct Security Password !!"; }
				}
				else
					echo "<B style=\"color:#FF0000;\">Transfer Amount Is Not Correct !</B>";	
			}
			else { echo "<B style=\"color:#FF0000;\">Sorry Your Today's Transfer Limit is over!</B>"; }	
		}
		else { echo "<B style=\"color:#FF0000;\">There is some conflicts!</B>"; }		
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
			$query = query_execute_sqli("select roi from wallet where id = '$id' ");
			while($row = mysqli_fetch_array($query))
			{
				$curr_amnt = $row[0];
			}	

			$msg = $_REQUEST[mg]; echo $msg; ?> 
			<form name="money" action="index.php?page=transfer-to-member" method="post">
			<table class="table table-bordered table-hover">
				<tr>
					<th>Current Repurchase Balance</th>
					<th><?=round($curr_amnt,2)." &#36; ";?></th>
			  	</tr>
				<tr>
					<th>Request Amount :</th>
					<td><input type="text" name="request" class="form-control" /></td>
				</tr>
				<tr>
					<th>Requested Username :</th>
					<td><input type="text" name="requested_user" class="form-control" /></td>
				</tr>
				<tr>
					<th>Transaction Password :</th>
					<td ><input type="text" name="user_pin" class="form-control" /></td>
				</tr>
				<tr>
					<td colspan="2" class="text-center">
						<input type="submit" name="submit" value="Request" class="btn btn-info" />
					</td>   
				</tr>
			</table>
			</form>
	
	<?php  
		}
		else { echo "<B style='color:#FF0000'>Sorry Your Today's Transfer Limit is over!</B>"; }
	}   
}
else { echo "<B style='color:#FF0000'>You Are not Authorised To Transfer Money</B>"; }	
?>