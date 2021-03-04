<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");
include("function/database.php");
include("function/send_mail.php");

$id = $_SESSION['mlmproject_user_id'];
include("function/wallet_message.php");
$work_bal = get_user_allwallet($id,'amount');
$TDS_NAM = $MSGS_PAN = '';
$sale_rate = $token_rate - round($token_rate * $sale_rate_percent / 100,2);
if(true)//pan_no_update_or_not($id) == ''
{
	$admin_tsx = $withdrwal_money_tax;
	$withdrwal_money_tax = $withdrwal_money_tax;
	$TDS_NAM = " ($admin_tsx%)";
	
}
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Request')
	{
		$user_pin = $_REQUEST['user_pin'];
		$request_amount = $_REQUEST['request'];
		$from_wallet = $_REQUEST['from'];
		$user_pin = $_REQUEST['user_pin'];
		$current_amount= $work_bal;
		$cur_code = "";
		$inc_chk = 1;
		//validate_request_amount($request_amount); 
		if($inc_chk == 1)
		{
			if($request_amount >= $minimum_withdrawal)
			{
				$pay_mode = $franch_id = $_REQUEST['pay_mode'];
			
				if($request_amount <= $current_amount)
				{ 
					if(/*!in_array($pay_mode,$pm_id)*/false)
					{
						print "Comming Soon !";
					}
					else
					{
						$sql = "select * from users where id_user='$id' and password='$user_pin'";
						$user_pin_num = mysqli_num_rows(query_execute_sqli($sql));
						if($user_pin_num > 0){
							$_SESSION['from_wallet'] = $from_wallet;
							$_SESSION['user_comment'] = $_REQUEST['comment'];
							$request_amount_ori = $request_amount;
							
							$payment_mode = $pay_mode == 1 ? "BTC" : ($pay_mode == 2 ? "ETH" : "BANK");				
							$res_token = round($request_amount/2,2);
							$res_usd  = round($res_token*$sale_rate,2);
							$usd_tax = round($res_usd*$withdrwal_money_tax/100,2);
							$usd_withdrawal = $res_usd - $usd_tax;
							
							$query = query_execute_sqli($sql);
							while($row = mysqli_fetch_array($query)){
								$btc_addrs = $row['btc_ac'];
								$eth_addrs = $row['etc_ac'];
								$bank_ac = $row['bank_ac'];
							}
							
							$_POST['pay_mode'] == 1 ? ($ac_addrs = $btc_addrs != "" ? $btc_addrs : "") : "";
							$_POST['pay_mode'] == 2 ? ($ac_addrs = $eth_addrs != "" ? $eth_addrs : "") : "";
							$_POST['pay_mode'] == 3 ? ($ac_addrs = $bank_ac != "" ? $bank_ac : "") : "";
								
							
							if($ac_addrs != ""){					
							?>
							<form name="money" action="index.php?page=request-fund-transfer" method="post">
							
							<input type="hidden" name="request" value="<?=$request_amount_ori; ?>"  />
							<input type="hidden" name="totl_req_amount" value="<?=$request_amount; ?>"  />	
							<input type="hidden" name="pay_mode" value="<?=$pay_mode; ?>"  />
							<?php //print $MSGS_PAN?>
							<table class="table table-bordered table-hover">
								<tr>
									<th width="40%">Your Current Bonus</th>
									<td><?=round($current_amount,2)?></td>
								</tr>
								<tr><th>Your Request Amount :</th><td><?=$request_amount?></td></tr>
								<tr><th>Admin Tax <?=$TDS_NAM; ?></th><td><?=$withdrwal_money_tax?> %</td></tr>
								<tr><th>Payment Mode :</th><td><?=$payment_mode; ?></td></tr>
								<tr>
									<td colspan="2" class="text-center">
										<input type="submit" name="submit" value="Submit" class="btn btn-info"  />
									</td>   
								</tr>
							</table>
							</form>
							 <?php
							 }
							 else{
							 	echo "<B class='text-danger'>Please Update BTC Address For Withdrawal!</B>";
							 }
						}
						else{
							 echo "<B class='text-danger'>Please Enter Correct Login Password!</B>"; 
						}
					}	
				}	
				else { echo "<B class='text-danger'>You request balance is not available in your wallet!</B>"; }	
			}
			else{ echo "<B class='text-danger'>Please Withdrawal At least $minimum_withdrawal !</B>"; }	
		}
		else { echo "<B class='text-danger'>Request of transfer amount can not be completed. Please Enter An Integer Amount for Transfer !!</B>"; }	

	}
	elseif($_POST['submit'] == 'Submit')
	{
		$date = $systems_date;
		$request_amount = $_REQUEST['request'];
		$pay_mode = $franch_id = $_POST['pay_mode'];
		$security_pass = $_REQUEST['security_pass'];
		//$totl_req_amount = $request_amount-($request_amount*$withdrwal_money_tax)/100;
		
		//$res_token = round($request_amount/2,2);
		$res_usd  = round($request_amount,2);
		$usd_tax = round($res_usd*$withdrwal_money_tax/100,2);
		$totl_req_amount = $res_usd - $usd_tax;
		
		$from_wallet = $_SESSION['from_wallet'];
		$user_comment = $_SESSION['user_comment'];
		if($from_wallet == 1){
			$current_amount= $work_bal;
			$field_name = 'amount';
			$from_wallet1 = "Main Wallet";
			$with_status = 65;
		}
		//$totl_req_amount = $_REQUEST['totl_req_amount'];
		$pass_num = 1; 

		if($pass_num > 0 )
		{
			if(!isset($_SESSION['withdrwal_inc'])){
				$_SESSION['withdrwal_inc'] = 1;
				$request_date= date('Y-m-d');
				$one_usd_value = 1;
				/*if($pay_mode != 3){
					$one_usd_value = 0.00084961;	
					if(strtoupper($soft_chk) == "LIVE"){
						$result = $cps->GetRates();//get_USD_TO_BITCOIN("INR",1);	
						if($result['error'] != "ok"){
							echo $result['error'];
						}
						else{
							$one_usd_value = $result['result']['USD']['rate_btc'];
							$one_eth_value = $result['result']['ETH']['rate_btc'];
						}
						$pay_mode == 2 ? $one_usd_value = (1/$one_eth_value)*$one_usd_value : "";
					}
				}*/
				
				$totl_req_amount = $totl_req_amount*$one_usd_value;
				$decs_mode = $pay_mode == 1 ? "BTC" : ($pay_mode == 2 ? "ETH" : "BANK");
				if(query_execute_sqli("INSERT INTO `withdrawal_crown_wallet`(`ac_type`,`user_id`, `amount`, `request_crowd`, `description`,`date`,`tax`,`cur_bitcoin_value`,status,user_comment,rate) 
							VALUES ('$pay_mode','$id','$res_usd','$totl_req_amount','$decs_mode  Withdrawal','$systems_date_time','$withdrwal_money_tax','0','$with_status','$user_comment','$sale_rate')"))
				{
					$r_id = get_mysqli_insert_id();
					
					query_execute_sqli("UPDATE wallet SET $field_name = $field_name-$request_amount WHERE id = '$id' ");
					/*query_execute_sqli("UPDATE wallet SET rtoken = rtoken+$res_token WHERE id = '$id' ");
					insert_wallet_account($id , $id , $request_amount , $systems_date , $acount_type[15] ,$acount_type_desc[15], $mode=2 , get_user_allwallet($id,$field_name),$wallet_type[1],$remarks = $user_comment);
					insert_wallet_account($id , $id , $res_token , $systems_date , $acount_type[7] ,$acount_type_desc[7], $mode=1 , get_user_allwallet($id,'rtoken'),$wallet_type[3],$remarks = 'Reserved Token');*/
	
					$wal_bal = wallet_balance($id);
					if(strtoupper($soft_chk) == "LIVE"){
						$pay_request_username = get_user_name($id);
						$franch_username = get_user_name($franch_id);
						include("function/logs_messages.php");
						data_logs($u_id,$data_log[22][0],$data_log[22][1],$log_type[18]);
												
						//Withdrawal success email message
						include("email_letter/withdrawal_success_btc.php");
						$to = get_user_email($id);
						include("function/full_message.php");
						$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,
						$title, $full_message);	
						//End email message;
						
						echo "You request for Withdrawal  ".$request_amount."  has been completed successfully!";
					}
				}
				else{
					$_SESSION['withdrwal_inc'] = 1;
					?> <script>window.location = "index.php?page=request-fund-transfer&success=1";</script> <?PHP
				}
			}
			else{ ?> <script>window.location = "index.php?page=request-fund-transfer&success=1";</script> <?PHP }
		}
		else 
		{ ?> <script>window.location = "index.php?page=request-fund-transfer";</script> <?PHP }	
	}	
}
else
{
	if($_SESSION['mlmproject_user_type'] == 'B'){
		unset($_SESSION['withdrwal_inc']);
		$pay_day = date("d", strtotime($systems_date));
		if($pay_day == $withdrawal_request_day['USD'][0] or $pay_day == $withdrawal_request_day['USD'][1] or $pay_day == $withdrawal_request_day['USD'][2] or true)
		{
		$sql = "SELECT * FROM reg_fees_structure WHERE user_id='$id' AND 
		DATE_ADD(`date`, INTERVAL $matching_pay_day DAY) <= '$systems_date' AND mode=1";
		$match_with_chk = mysqli_num_rows(query_execute_sqli($sql));
		
		
		?> 
		<script>
			$(document).ready(function() {
				$('#pay_mode').change(function() {
					var pay_mode = $(this).val();
					$("#addr").html("");
					var pay_cny = pay_mode == 1 ? 'BTC' : 'ETH';
					var app = '<th>'+pay_cny+' Address : </th>';
					if(pay_mode == 1)	{
						$("#addr").append(app+'<td><?=get_cryto_addrs($id,'btc_ac')?></td>'); 
					}
					if(pay_mode == 2){
						$("#addr").append(app+'<td><?=get_cryto_addrs($id,'etc_ac')?></td>'); 
					}
				});
			});
		</script>
		<form name="money" action="index.php?page=request-fund-transfer" method="post">
		<input type="hidden" name="from" value="1" />
		 <div class="col-md-12"><b>Minimum Withdrawal Amount : &nbsp;&#36;&nbsp;<?=round($minimum_withdrawal,5);?></b></div>
    	    <div class="col-md-12">&nbsp;</div>
    	    <div class="col-md-4"><b>Payment Mode :</b></div>
    	     <div class="col-md-8">
    	         <select name="pay_mode" class="form-control"  id="pay_mode" required>
						<option value="">Select Payment Type</option>
						<option value="1">BTC</option>
						<option value="2">ETH</option>
				</select>
			</div>
				
    	    <div class="col-md-12">&nbsp;</div>
    	    <div class="col-md-12"><span id="addr"></span></div>
    	    
    	    <div class="col-md-4"><b>Your Request Amount :</b></div>
    	     <div class="col-md-8"><input type="text" name="request" class="form-control" /></div>
    	    <div class="col-md-12">&nbsp;</div>
    	    
    	    <div class="col-md-4"><b>Remark :</b></div>
    	     <div class="col-md-8"><textarea name="comment" placeholder="Your Remarks..." class="form-control"></textarea></div>
    	    <div class="col-md-12">&nbsp;</div>
    	    
    	    <div class="col-md-4"><b>Password :</b></div>
    	     <div class="col-md-8"><input type="password" name="user_pin" class="form-control" /></div>
    	    <div class="col-md-12">&nbsp;</div>
    	    
    	     <div class="col-md-12 text-center"><input type="submit" name="submit" value="Request" class="btn btn-info" /></div>
    	    <div class="col-md-12">&nbsp;</div>
		</form>
	
	<?php 
		}
		else
		{ echo "<B class='text-danger'>Withdrawal Is Working On ".$withdrawal_request_day['USD'][0]."/".$withdrawal_request_day['USD'][1]."/".$withdrawal_request_day['USD'][2]." Date Of Month !</B>"; }
	}
	else{
		$qu = query_execute_sqli("select * from account where type = 28 and user_id = ".$_SESSION['mlmproject_user_id']."  and dr > 0 order by date desc limit 1 ");
		while($r = mysqli_fetch_array($qu)){
			$blockremarks = $r['remarks'];
		}
		mysqli_free_result($qu);
		?>
		<B class='text-danger'>Sorry Membership is Blocked !!!<br />Please Contact to Admin <br />& Reason Given By Admin : <?=$blockremarks;?> !!</B>
		<?php
		include "free_up_memory.php"; 
	}
}  
function get_cryto_addrs($id,$currency){
    $sql = "select * from users where id_user = '$id'";
    $qu = query_execute_sqli($sql);
    while($r = mysqli_fetch_array($qu)){
		 $addrs = $r[$currency];
		 return $addrs != "" ? $addrs : "<B class='text-danger'>Address Not Available !!</B>"; 
	}
	mysqli_free_result($qu);
}

?>

