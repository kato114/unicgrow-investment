<?php
include('../security_web_validation.php');

session_start();
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");

include("function/wallet_message.php");
include("function/check_income_condition.php");

$login_id = $_SESSION['mlmproject_user_id'];
$current_amount = get_user_allwallet($login_id,'amount');
$commission_amount = get_user_allwallet($login_id,'amount');
?>
<script>
$(document).ready(function() {	
	//$("#search_username").mouseout(function (e) {
	$("#search_username").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_username = $(this).val();
		if(sponsor_username.length < 2){$("#user-search").html('');return;}
		
		if(sponsor_username.length >= 2){
		
			$("#user-result").html('Lodding...');
			$.post('check_username.php', {'search_username':sponsor_username},function(data)
			{
			  $("#user-search").html(data);
			});
		}
	});	
	
	$("#req_amt").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var req_amt = $(this).val();
		
		$("#request_amt_result").html('Lodding...');
		$.post('selcet_plan.php', {'request_amt':req_amt},function(data)
		{
		  $("#request_amt_result").html(data);
		});
	});	
});		
</script>

<?php
$ccc=1;
if($ccc == 1)   //check_income_condition($login_id) == 1)
{
	$requested_user = $_POST['requested_user'];
	if(isset($_POST['submit']))
	{
		if($_POST['submit'] == 'OTP Valid')
		{
			if($_SESSION['WTDW_OTP'] == $_POST['valid_otp']){
				 $pass_num = 1;
				 $request_amount = $_POST['request'];
				 
				 $_POST['submit'] = 'OTP Valid True';
				 
			}
			else{
				echo '<div class="row form-group" style="padding-left:20px;">
						<label class="text-danger">Invalid OTP ...</label>
					</div>';
				$_POST['submit'] = 'Request';
			}
		}
		if($_POST['submit'] == 'Request' or $_POST['submit'] == 'OTP Valid True'){
			$request_amount = $investment = $_POST['request'];
			unset($_SESSION['investment']);
			$_SESSION['investment'] = $investment;
			
			$pin = $_REQUEST['user_pin'];
			$pass_num = 0;
		
			$sql = "SELECT password FROM users WHERE id_user ='$login_id' AND password = '$pin' ";
			$get_security_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
			if(trim($pin) == trim($get_security_pass)){ $pass_num = 1; } 
			$requested_user = $_POST['requested_user'];
			$requested_user_id = get_new_user_id($requested_user);
		
			if($requested_user_id == 0 or $requested_user_id == $login_id)
			{ echo "<B class='text-danger'>Please Enter correct Username !</B>"; }
			else{
				$sql = "select * from network_users where FIND_IN_SET($requested_user_id,left_network) 
				or FIND_IN_SET($requested_user_id,right_network)";
				$num = 1 ; // mysqli_num_rows(query_execute_sqli($sql));
				if($num > 0){
					if($pass_num > 0){
						if($request_amount <= $commission_amount){
							$inc_chk = validate_request_amount($request_amount); 
							if($inc_chk == 1){ 
								if($request_amount >= $minimum_transfer_amt){
									
									if($_POST['submit']  == 'OTP Valid True')//=='Request'
									{
										$_SESSION['ses'] = 1;
										$user_pin = $_POST['user_pin'];
										$request_amount = $_SESSION['request_amount'] = $_POST['request'];
										?>
										<form name="money" action="" method="post">
										<input type="hidden" name="request" value="<?=$request_amount;?>"  />
										<input type="hidden" name="requested_user_id" value="<?=$requested_user_id?>" />
											<table class="table table-bordered table-hover">
												<thead><tr><th colspan="2">Your Transaction Information</th></tr></thead>
												<tr>
													<th width="40%">E-Wallet Balance</th>
													<th><?=round($commission_amount,2)." &#36; ";?></th>
												</tr>
												<tr><th>Transfer Amount</th>	<th><?=$request_amount;?> &#36; </th></tr>
												<tr><th>Receiver Id</th>		<th><?=$requested_user;?></th></tr>
												<tr><th>Receiver Name</th>		<th><?=get_full_name($requested_user_id);?></th></tr>
												<tr>
												<td colspan="2" class="text-center">
													<input type="submit" name="submit" value="Submit" class="btn btn-success"/>
												</td>   
												</tr>
											</table>
										</form> <?php		
									}
									if($_POST['submit'] == 'Request'){
										if(!isset($_SESSION['WTDW_OTP'])){
											$_SESSION['WTDW_OTP'] = $rand = rand(1000,9999);
											if(strtoupper($soft_chk) == "LIVE"){
												
												//new registration message
												$phone = get_user_phone($request_user_id);
												$message_login = "Your Transfer to Member E-wallet OTP is ".$rand_no." https://www.unicgrow.com";
												send_sms($phone,$message_login);
												
												include("email_letter/deposti_fund_otp_msg.php");
												$to = get_user_email($request_user_id);
												include("function/full_message.php");
												$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $full_message);	
												//End email message
											}
										}
										//echo $_SESSION['WTDW_OTP'];
									?>
										<div class="row form-group" style="padding:20px;">
											<form method="post" action="" enctype="multipart/form-data">
												<input type="hidden" name="request" value="<?=$_POST['request']?>" />
												<input type="hidden" name="requested_user" value="<?=$_POST['requested_user']?>" />
												<input type="hidden" name="user_pin" value="<?=$_POST['user_pin']?>" />
												<div class="form-group">
													<label class="text-success">OTP Sent On Your Registered Mobile...</label>
												</div>
												<div class="form-group">
													<label>Enter OTP</label>
													<input type="text" name="valid_otp" value="<?=$_POST['valid_otp']?>">
												</div>
												<div class="form-group" id="confirm_btn">
													<input type="submit" name="submit" value="OTP Valid" class="btn btn-info" />
												</div>
											</form>
										</div>
									<?php
									}
								}
								else { 
									echo "<B class='text-danger'>
									Transfer minimum $minimum_transfer_amt &#36; amount !!</B>"; 
								}	
							}
							else{ 
								echo "<B class='text-danger'>Request of transfer amount can not be completed.<br> Please Enter An Integer Amount for Transfer !!</B>";
							}	
						}
						else{
							echo "<B class='text-danger'>You request balance is not available in your wallet</B>";
						}
					}
					else { echo "<B class='text-danger'>Please enter correct Password !</B>"; }
				}
				else{ echo "<B class='text-danger'>Requested Member Not In Network !</B>"; }
			}
		}
		elseif($_POST['submit'] == 'Submit' and $_SESSION['ses'] == 1)
		{
			$date = date('Y-m-d');
			$req_amount = $_POST['request'];
			$req_user_id = $_POST['requested_user_id'];
			$security_pass = $_POST['security_pass'];
			
			$sql = "SELECT * FROM transfer_count WHERE user_id = '$login_id' and date = '$systems_date'";
			$quer = query_execute_sqli($sql);
			$num = mysqli_num_rows($quer);
			$transfer_count = 0;
			
			if($num >  0)
			{
				while($row = mysqli_fetch_array($quer))
				$transfer_count = $row['tr_count'];
			}		
			if($num == 0 or $transfer_count < $max_transfer_count)
			{
				if($num >  0)
				{
					$nxt_trns = $transfer_count+1;
					$sql = "UPDATE transfer_count SET tr_count='$nxt_trns' WHERE user_id = '$login_id' 
					AND date = '$systems_date'";
				}
				else 
				{ 
					$sql = "INSERT INTO transfer_count (user_id , tr_count , date) 
					VALUES ('$login_id',1,'$systems_date')";
				}
				query_execute_sqli($sql);
				
				if($_SESSION['request_amount'] == $req_amount)
				{
					$SQL = "UPDATE wallet SET activationw = activationw+'$req_amount' , date = '$systems_date' 
					WHERE id = '$req_user_id' ";
					query_execute_sqli($SQL);
					
					$SQLK = "UPDATE wallet SET activationw = activationw-'$req_amount' , date = '$systems_date' 
					WHERE id = '$login_id' ";
					query_execute_sqli($SQLK);
					
					$wal_bal = get_user_allwallet($login_id,'activationw');
					insert_wallet_account($login_id, $req_user_id, $req_amount, $systems_date_time, $acount_type[9],$acount_type_desc[9], 2, $wal_bal,$wallet_type[2]);
					
					$wal_bal = get_user_allwallet($req_user_id,'activationw');
					insert_wallet_account($req_user_id, $login_id, $req_amount, $systems_date_time, $acount_type[10],$acount_type_desc[10], 1, $wal_bal,$wallet_type[2]);
					
					if(strtoupper($soft_chk) == "LIVE")
					{
						include("email_letter/fund_transfer_to_mem.php");
						$to = get_user_email($login_id);
						$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $db_msg);
						$login_username = get_user_name($login_id);
						$phone = get_user_phone($req_user_id);
						$message = "Dear Member, you have received transfer amount from $login_username. 
						Thanks, https://www.unicgrow.com";
						send_sms($phone,$message);
						
						$req_username = get_user_name($req_user_id);
						$phone = get_user_phone($login_id);
						$message = "Dear Member, You have transferred amount to $req_amount to $req_username. 
						Thanks, https://www.unicgrow.com";
						send_sms($phone,$message);
					}
							
					echo "<B class='text-success'>
						You request of transfer amount ".$req_amount." &#36; has been completed successfully !
					</B>";
					$_SESSION['ses'] = 0;
					unset($_SESSION['request_amount']);
				}
				else{ echo "<B class='text-danger'>Transfer Amount Is Not Correct !</B>"; }
			}
			else{ echo "<B class='text-danger'>Sorry Your Today's Transfer Limit is over!</B>"; }
		}
		else{ echo "<B class='text-danger'>There is some conflicts!</B>"; }		
	}
	else
	{
		$sql = "SELECT * FROM transfer_count WHERE user_id = '$login_id' and date = '$systems_date'";
		$que = query_execute_sqli($sql);
		$num = mysqli_num_rows($que);
		$transfer_count = 0;
		if($num >  0)
		{
			while($row = mysqli_fetch_array($que))
			$transfer_count = $row['tr_count'];
		}		
		if($num == 0 or $transfer_count < $max_transfer_count)
		{ ?> 
			<form name="money" action="" method="post">
			<table class="table table-bordered table-hover">
				<tr>
					<th width="40%">E-Wallet Balance</th>
					<th><?=round($commission_amount,2)." &#36; ";?></th>
				</tr>
				<tr>
					<th>Minimum Transfer Amount</th>
					<th><?=$minimum_transfer_amt?> &#36;</th>
				</tr>
				<tr>
					<th>To User ID <span id="user-search" style="float:right;"></span></th>
					<td><input type="text" name="requested_user" class="form-control" id="search_username" /></td>
				</tr>
				<tr>
					<th>Amount (&#36;)</th>
					<td><input type="text" name="request" class="form-control" id="req_amt" onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')" /></td>
				</tr>
				
				<tr>
					<th>Remark</th>
					<td><textarea name="remark" class="form-control"></textarea></td>
				</tr>
				<!--<tr>
					<th>Password</th>
					<td ><input type="password" name="user_pin" class="form-control" /></td>
				</tr>-->
				<tr>
					<td colspan="2" class="text-center">
						<input type="submit" name="submit" value="Request" class="btn btn-info" />
					</td>   
				</tr>
			</table>
			</form> <?php 
		}
		else{ echo "<B class='text-danger'>Sorry Your Today's Transfer Limit is over !</B>"; } 
	}   
}
else { echo "<B class='text-danger'>You Are not Authorised To Transfer Money</B>"; }	
?>