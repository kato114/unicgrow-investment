<?php
include('../security_web_validation.php');
//die("Please contact to customer care.");
session_start();
//$_SESSION['WTDW_OTP'] = NULL;
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");

include("function/wallet_message.php");
include("function/check_income_condition.php");

$login_id = $_SESSION['mlmproject_user_id'];
$current_amount = get_user_allwallet($login_id,'trade_gaming');
$cash_amount = get_user_allwallet($login_id,'amount');
$commission_amount = $current_amount;
$sql = "SELECT COALESCE(sum(dr),0) cr FROM account WHERE user_id = '$login_id' and type = '$acount_type[38]'";
$que = query_execute_sqli($sql);
$cr = mysqli_fetch_array($que)[0];
mysqli_free_result($que);
$sql = "SELECT COALESCE(sum(cr),0) dr FROM account WHERE user_id = '$login_id' and type = '$acount_type[18]'";
$que = query_execute_sqli($sql);
$dr = mysqli_fetch_array($que)[0];
mysqli_free_result($que);
$current_amount = $bal = $cr - $dr;
?>
<!--<script>
$(document).on('input', '#comm_id', function(){
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
$(document).ready(function() {	
	//$("#comm_id").keyup(function (e) {
	$("#req_amt").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_username = $(this).val();
		if(sponsor_username.length < 2){$("#user-search").html('');return;}
		
		if(sponsor_username.length >= 2){
		
			$("#user-result").html('Lodding...');
			$.post('../check_username.php', {'username_search':sponsor_username},function(data)
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
	$('.top_to').change(function() {
		$('#community_row').html("");
		$("#secw_id").show();
		var top_from = $(this).val();
		if(top_from == 0 || top_from == 1 )	{
			$(".buy-now-self").show();
			$(".buy-now-comm").hide();
			$("#community_row").html("");
		}
		if(top_from == 10){
			$(".buy-now-comm").show();
			$(".buy-now-self").hide();
			$("#community_row").show();
			$('#community_row').html("" );
			input = $('<th width="30%">Member UserId</th><th><input type="text" name="requested_user" class="form-control" id="comm_id" value="" /><span id="user-search"></span></th>');
			$('#community_row').append(input);
		}
	});
});		
</script>-->

<?php
$ccc=1;
if($ccc == 1){   //check_income_condition($login_id) == 1)

	$requested_user = $_POST['requested_user'];
	if(isset($_POST['submit'])){
		if($_POST['submit'] == 'Request'){
			$request_amount = $investment = $_POST['request'];
			unset($_SESSION['investment']);
			$_SESSION['investment'] = $investment;
			
			$pin = $_REQUEST['user_pin'];
			$pass_num = 0;
		
			$sql = "SELECT user_pin FROM users WHERE id_user ='$login_id' AND user_pin = '$pin' ";
			$get_security_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
			if(trim($pin) == trim($get_security_pass)){ $pass_num = 1; } 
			$requested_user = isset($_POST['requested_user']) ? $_POST['requested_user'] : $_SESSION['mlmproject_user_username'];
			$requested_user_id = get_new_user_id($requested_user);
		
			if($requested_user_id < 1){ echo "<B class='text-danger'>Please Enter correct Username !</B>"; }
			else{
				/*$sql = "select * from network_users where FIND_IN_SET($requested_user_id,left_network) 
				or FIND_IN_SET($requested_user_id,right_network)";*/
				$num = 1;//mysqli_num_rows(query_execute_sqli($sql));
				if($num > 0){
					if($pass_num > 0){
						if($request_amount <= $current_amount){
							$inc_chk = validate_request_amount($request_amount); 
							if($inc_chk == 1){ 
								if($request_amount >= $minimum_transfer_amt){
									$_SESSION['ses'] = 1;
									$user_pin = $_POST['user_pin'];
									$request_amount = $_SESSION['request_amount'] = $_POST['request'];
									$_SESSION['requested_user_id'] = $requested_user_id;
									?>
									<form name="money" action="" method="post">
									<input type="hidden" name="request" value="<?=$request_amount;?>"  />
									<input type="hidden" name="requested_user_id" value="<?=$requested_user_id?>" />
										<table class="table table-bordered table-hover">
											<thead><tr><th colspan="2">Your Transaction Information</th></tr></thead>
											<tr>
												<th width="40%">SMG Wallet Balance</th>
												<th><?=round($commission_amount,2)." &#36; ";?></th>
											</tr>
											<tr>
												<th width="40%">Cash Wallet Balance</th>
												<th><?=round($cash_amount,2)." &#36; ";?></th>
											</tr>
											<tr><th>Transfer Amount</th>	<th><?=$request_amount;?> &#36; </th></tr>
											<tr>
											<td colspan="2" class="text-center">
												<input type="submit" name="submit" value="Submit" class="btn btn-success"/>
											</td>   
											</tr>
										</table>
									</form> <?php		
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
					
			if($_SESSION['request_amount'] == $req_amount)
			{
				if($_SESSION['requested_user_id'] == $req_user_id){
					$SQL = "UPDATE wallet SET trade_gaming = trade_gaming-'$req_amount' , date = '$systems_date' 
					WHERE id = '$login_id' ";
					query_execute_sqli($SQL);
					
					$SQLK = "UPDATE wallet SET amount = amount+'$req_amount' , date = '$systems_date' 
					WHERE id = '$req_user_id' ";
					query_execute_sqli($SQLK);
					
					$wal_bal = get_user_allwallet($login_id,'trade_gaming');
					insert_wallet_account($login_id, $req_user_id, $req_amount, $systems_date_time, $acount_type[19],$acount_type_desc[19], 2, $wal_bal,$wallet_type[4],$remarks = "Transfer SMG To Cash Wallet");
					
					$wal_bal = get_user_allwallet($req_user_id,'amount');
					insert_wallet_account($req_user_id, $login_id, $req_amount, $systems_date_time, $acount_type[18],$acount_type_desc[18], 1, $wal_bal,$wallet_type[1],$remarks = "Credit Cash By SMG Wallet");
					
					/*if(strtoupper($soft_chk) == "LIVE")
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
					}*/
					unset($_SESSION['WTDW_OTP']);	
						
					echo "<B class='text-success'>
						You request of transfer amount ".$req_amount." &#36; has been completed successfully !
					</B>";
					$_SESSION['ses'] = 0;
					unset($_SESSION['request_amount']);
				}
				else{
					 echo "<B class='text-danger'>Error : There is some conflicts !!</B>";
				}
			}
			else{ echo "<B class='text-danger'>Transfer Amount Is Not Correct !</B>"; }
			
		}
		else{ echo "<B class='text-danger'>There is some conflicts!</B>"; }		
	}
	else
	{
		$sql = "SELECT * FROM account WHERE user_id = '$login_id' and type = '$acount_type[38]'";
		$que = query_execute_sqli($sql);
		$num = mysqli_num_rows($que);
		mysqli_free_result($que);
		if($num > 0){
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
				
				<!--<div class="plan_show">
					<div class="col-md-3">
						<div class="panel panel-success">
							<div class="panel-heading text-center">
								<input type="radio" name="top_to" class="top_to" value="0"  checked="checked" /> Transfer For Self
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="panel panel-danger">
							<div class="panel-heading text-center">
								<input type="radio" name="top_to" class="top_to" value="10" /> Transfer For Community
							</div>
						</div>
					</div>
				</div>-->
		<form name="money" action="index.php?page=<?=$val?>" method="post">
				<table class="table table-bordered table-hover">
					<tr>
						<th width="40%">SMG Wallet Balance</th>
						<th><?=round($commission_amount,2)." &#36; ";?></th>
					</tr>
					<tr>
						<th width="40%">Cash Wallet Balance</th>
						<th><?=round($cash_amount,2)." &#36; ";?></th>
					</tr>
					<tr>
						<th>Minimum Transfer Amount</th>
						<th><?=$minimum_transfer_amt?> &#36;</th>
					</tr>
					<tr>
						<th>Transferable Amount</th>
						<th><?=$bal?> &#36;</th>
					</tr>
					<tr id="community_row"></tr>
					<tr>
						<th>Amount (&#36;)</th>
						<td><input type="text" name="request" class="form-control" id="req_amt" onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')" /></td>
					</tr>
					
					<tr>
						<th>Remark</th>
						<td><textarea name="remark" class="form-control"></textarea></td>
					</tr>
					<tr>
						<th>Transaction Password</th>
						<td ><input type="password" name="user_pin" class="form-control" /></td>
					</tr>
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
		else{
			echo "<B class='text-danger'>Sorry ,Cash To SMG Wallet Transfer Is Not Done !</B>";
		}
	}   
}
else { echo "<B class='text-danger'>You Are not Authorised To Transfer Money</B>"; }	
?>