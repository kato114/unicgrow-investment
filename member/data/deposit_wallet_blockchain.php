<?php
include('../security_web_validation.php');
?>
<?php
ini_set("display_errors","on");
include("condition.php");
include("function/setting.php");
include("function/direct_income.php");
include("function/pair_point_calc.php");
include("function/send_mail.php");
include("create_withdrawal/coinpayments.inc.php");
include("function/blockchain_trasaction.php");
include("blockchain/setup.php");
require_once __DIR__ . './../blockchain/vendor/autoload.php';
$login_id = $id = $_SESSION['mlmproject_user_id'];
$username = $_SESSION['mlmproject_user_name'];
$inv_epin = $_POST['invest_epin'];
$inv_epin = $_POST['invest_epin'];
$credit_per = 0;//custom wallet deduction
$main_wallet = get_wallet_amount($id);
$cps = new CoinPaymentsAPI();
$cps->Setup($Private_Key, $Public_Key);
if(strtoupper($soft_chk) == "LIVE"){
	$result = $cps->GetRates();//get_USD_TO_BITCOIN("INR",1);	
	if($result['error'] != "ok"){
		die($result['error']);
	}
	else{
		$one_usd_value = $result['result']['USD']['rate_btc'];
		$one_eth_value = $result['result']['ETH']['rate_btc'];
	}
}
$Blockchain = new \Blockchain\Blockchain($api_code);
$Blockchain->setServiceUrl($setServiceUrl);
if(is_null($wallet_guid) || is_null($wallet_pass)) {
    echo "Please enter a wallet GUID and password in the source file.<br/>";
    exit;
}
$process = 0;
if(isset($_POST['submit']))
{	$request_user_id = $login_id;
	/*if($_POST['submit'] == 'OTP Valid'){
		if($_SESSION['ADD_FUND_OTP'] == $_POST['valid_otp'])
		{
			 $pass_num = 1;
			 $payment_method = $_REQUEST['payment_method'];
			 $investment = $_REQUEST['deposit'];
		}
		else{
			echo '<div class="row form-group" style="padding-left:20px;">
					<label class="text-danger">Invalid OTP ...</label>
				</div>';
			$_POST['submit'] = 'ADD FUND';
		}
	}*/
	if($_POST['submit'] == 'ADD FUND'){
		$plan_select_type = $_POST['plan_select_type'];
		$investment = $_POST['deposit'] + $_POST['deposit']*$credit_per/100;
		unset($_SESSION['investment']);
		$_SESSION['investment'] = $investment;
		
		$pin = $_REQUEST['pin'];
		$pass_num = 0;
		$_SESSION['payment_method'] = $payment_method = $_REQUEST['payment_method'];
		
		$sql = "select * from reg_fees_structure where user_id = $login_id order by id desc limit 1";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		$last_investment = 0;
		if($num > 0){
			while($row = mysqli_fetch_array($query)){
				$last_investment = $row['update_fees'];
			}
		}
		mysqli_free_result($query);
		
	
		$sql = "SELECT password FROM users WHERE id_user ='$login_id' AND password = '$pin' ";
		$get_security_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
		if(trim($pin) == trim($get_security_pass)){$pass_num = 1;  } 
		
		$sql = "select user_id from request_crown_wallet where user_id='$request_user_id' and status=0 and DATE_ADD(date, INTERVAL 30 MINUTE) >= '$systems_date_time'";
		$topup_num = mysqli_num_rows(query_execute_sqli($sql));
		if($topup_num > 0){
			$topup_err = "<B class='text-danger'>Error : Dear ".$username.", Deposit Request Pending !!</B>";
			$_POST['submit'] = "Fail";
		}
	$pass_num = 1;
	if($request_user_id > 0)
	{
		if($pass_num > 0)
		{
			$sql = "select * from plan_setting where id = $plan_select_type and amount <= $investment 
					and max_amount >= $investment";
			$query = query_execute_sqli($sql);
			$num = mysqli_num_rows($query);
			mysqli_free_result($query);
			if($topup_num == 0){
			
			/*if($_POST['submit'] == 'OTP Valid')
			{*/
				if($investment >= $last_investment or true){
					if($_REQUEST['payment_method'] != ""){
						if($_REQUEST['payment_method'] == 1 or $_REQUEST['payment_method'] == 3){
							if(!isset($_SESSION['bitcoin_addresss'])){
								if($_REQUEST['payment_method'] == 1){
									if(strtoupper($soft_chk) == "LIVE"){
										$one_usd_value =  number_format($Blockchain->Rates->toBTC(1, 'USD'),8);
										$getNewAddress = $Blockchain->Wallet->getNewAddress("Unic Grow Address");
										var_dump($getNewAddress);
										$_SESSION['bitcoin_addresss'] = $address = $getNewAddress->address;	
										$priceBTC = $one_usd_value*$investment;									
									}
									else{
										
										$_SESSION['bitcoin_addresss'] = $address = md5(rand(1000,9999));
										$usd_bitcoin_val = $one_usd_value = 0.00587;
										$priceBTC = $investment * $one_usd_value;
									}
								}
							}
							/*if(strtoupper($soft_chk) == "LIVE"){
								$result = $cps->GetRates();//get_USD_TO_BITCOIN("INR",1);	
								if($result['error'] != "ok"){
									die($result['error']);
								}
								else{
									$one_usd_value = $result['result']['USD']['rate_btc'];
									$one_eth_value = $result['result']['ETH']['rate_btc'];
								}
							}*/
								
							
							$currency_sign = $payment_method == 1 ? 'Bitcoin' : 'ETH';
							if($_SESSION['bitcoin_addresss'] != NULL){
								$address = $_SESSION['bitcoin_addresss'];
								//$priceBTC = $investment * $one_usd_value;
								$time = date("Y-m-d H:i:s",strtotime($systems_time.$api_deduct_time));
								if(!isset($_SESSION['priceBTC'])){
									$_SESSION['priceBTC'] = $priceBTC;
								}
								$priceBTC = $_SESSION['priceBTC'];
								$one_usd_value = $payment_method == 1 ?  $one_usd_value : $priceBTC/$investment;	
								$one_usd_value = round($one_usd_value,8);
								unset($_SESSION['request_user_id']);
								$_SESSION['priceBTC'] = $priceBTC;
								$process = 1;	
								?>
								<div class="row" style="padding:20px;">
								<form method="post" action="">
									<input type="hidden" name="plan_select_type" value="<?=$plan_select_type;?>" />
									<input type="hidden" name="order_from" value="3" />
									<div id="bitcoin_addresss_info" style="display:block;">
										<div class="col-md-12 color-red">
											<B>Are you sure?</B><br />
											<B>Deposit Amount <?=$currency_sign?> : <?=$priceBTC;?></B>
										</div>
										<div class="col-md-8" style="text-align:left;  line-height: 27px; padding-top:15px; color:#000;">
											This is Address <?=$_SESSION['bitcoin_addresss'];?></B> where you have to 
											transfer <B><?=$priceBTC;?></B> <?=$currency_sign?>. To purchase &#36; <?=$investment?> Investment, Put it to
											 this address. This address is using privately by "UNICGROW" officials, please 
											 don't use for any other purpose. Confirm address for transfer is 
											 <B><?=$_SESSION['bitcoin_addresss'];?></B> *Placment should exactly 
											 <B><?=$priceBTC;?> <?=$currency_sign?> </B>for confirm your purchase. 
											 <p class="text-red">
												Note* : This Transaction Window With-in 30 Minute , upon payment otherwise this will be cancelled automatically
											</p><br />
										</div>
										<div class="col-md-4 text-right">
											<!--<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=<?=$currency_sign?>:<?=$_SESSION['bitcoin_addresss'];?>?amount=<?=$priceBTC;?>&message=UnicGrow" />
											<img src="https://www.coinpayments.net/qrgen.php?id=<?=$_SESSION['bitcoin_txn_id']?>&key=<?=$_SESSION['bitcoin_tkey']?>" height="150" width="150" />-->
											<img src="https://www.bitcoinqrcodemaker.com/api/?style=bitcoin&amp;address=<?=$_SESSION['bitcoin_addresss'];?>" height="150" width="150" border="0" />
										</div>
										<div class="col-md-12 text-left">
											<p><B><span id="pay_information"></span></B></p>
										</div>
										<!--<div class="col-md-12 text-left">
											<p><B>Timer For Pay : <span id="pay_counddown"></span></B></p>
										</div>-->
									</div>
								</form>
								</div>
								<script>
								$( document ).ready(function(){
									var timerData = [];
									if(localStorage.getItem('popState') == null){
										var ct = 0; 
									}
									function check_transfer(row,timer_id){
										var seconds = timerData[row].remaining;
										var payment_method = '<?=$payment_method?>';
										var payment_plan = '<?=$plan_select_type?>';
										var amount = '<?=$priceBTC?>';
										var baddress = '<?=$_SESSION['bitcoin_addresss']?>';
										var crate = '<?=$one_usd_value?>';
										var url = "GetTransferInfo.php?val="+amount+"&address="+baddress+"&payment_method="+payment_method+"&ct="+ct+"&crate="+crate;
										if(seconds%10 == 0 || seconds < 5){
											$.post(url, function( data ) {
												obj = JSON.parse(data);
												if(obj.result > 0){
													localStorage.setItem('paymentState',obj.info);
													document.getElementById("pay_information").innerHTML = obj.info;
													setTimeout(function() {
													   window.location='index.php?page=deposit_wallet';	
													}, 3000);
												}else{
													document.getElementById("pay_information").innerHTML = obj.info;
												}
											});
										}
										seconds--;
										ct++;
										timerData[row].remaining = seconds;
									}
									
									
									function secondPassed(row,timer_id) {
										var seconds = timerData[row].remaining;
										var days        = Math.floor(seconds/24/60/60);
										var hoursLeft   = Math.floor((seconds) - (days*86400));
										var hours       = Math.floor(hoursLeft/3600);
										var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
										var minutes     = Math.floor(minutesLeft/60);
										var remainingSeconds = seconds % 60;
										if (remainingSeconds < 10) {
											remainingSeconds = "0" + remainingSeconds;
										}
										document.getElementById(timer_id).innerHTML = minutes + " Minutes " + remainingSeconds + " Seconds " ;
										if (seconds <= 0) {
											clearInterval(timerData[row].timerId);
											document.getElementById(timer_id).innerHTML = "Time Out";
											//$(".modal_close").show();
										} else {
											seconds--;
											localStorage.setItem('popState',seconds);
											//$(".modal_close").hide();
										}
										timerData[row].remaining = seconds;
									}
									
									function check_transfer_timer(row, min,timer_id) {
									   timerData[row] = {
											remaining: min,
											timerId: setInterval(function () { check_transfer(row,timer_id); }, 1000)
										};
									}
									function timer(row, min,timer_id) {
									   timerData[row] = {
											remaining: min,
											timerId: setInterval(function () { secondPassed(row,timer_id); }, 1000)
										};
									}
									
									if(localStorage.getItem('popState') == null){
										var countdown = 60*30;
										timer(1,countdown,'pay_counddown');
										check_transfer_timer(2,countdown,'myModal');
										localStorage.setItem('popState',countdown);
									}
									else{
										var countdown = localStorage.getItem('popState');
										timer(1,countdown,'pay_counddown');
										check_transfer_timer(2,countdown,'myModal');
									}
									
								});
								</script>
							 <?php
							}
							else{
								echo "<B class='text-danger'>Error : Please Wait For Sometime to Deposit !!</B>"; 
							}
						}
						elseif($_REQUEST['payment_method'] != 1 ){
							$sql = "select * from payment_method where id='$payment_method'";
							$qu = query_execute_sqli($sql);
							
							while($row = mysqli_fetch_array($qu))
							{
								$address = $row['address'];
								$currencyn = $row['currency'];
								$_SESSION['curr_addresss'] = $address;
								$_SESSION['curr_name'] = $currencyn;
							}
							$_SESSION['payment_method'] = $payment_method;
							?>
							<div class="row form-group" style="padding:20px;">
								<form method="post" action="" enctype="multipart/form-data">
									<input type="hidden" name="payment_method" value="<?=$payment_method?>" />
									<input type="hidden" name="deposit" value="<?=$_SESSION['investment']?>" />
									<div class="form-group">
										<label>Please Depost Amount To Given Address <?=$address?> For <?=$currencyn?>
										Payment Method.</label>
									</div>
									<div class="form-group">
										<label class="form-group" style="vertical-align:top;">Remark</label><span class="text-danger"  style="vertical-align:top;">*</span>
										<textarea name="remark" style="width:354px;" placeholder="Your Valuable Suggestion..." required></textarea>
									</div>
									<div class="form-group">
										<label>Upload Reciept</label>
										<input type="file" name="payment_receipt">
									</div>
									<div class="form-group" id="confirm_btn">
										<input type="submit" name="confirm" value="Confirm" class="btn btn-info" />
									</div>
								</form>
							</div>
							<?php
						}
					}
					else{
						echo "<B class='text-danger'>Error : Please Select At-Least One Payment Method !!</B>"; 
					}
				}
				else{
					echo "<B class='text-danger'>Error : Please Enter Gretter Than Equal Last Cash Amount !!</B>"; 
				}
			//}
			/*if($_POST['submit'] == 'ADD FUND')
			{
				if(!isset($_SESSION['ADD_FUND_OTP']))
				{
					$_SESSION['ADD_FUND_OTP'] = $rand = rand(1000,9999);
					if(strtoupper($soft_chk) == "LIVE")
					{
						//new registration message
						include("email_letter/deposti_fund_otp_msg.php");
						$to = get_user_email($request_user_id);
						include("function/full_message.php");
						$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $full_message);	
						//End email message
				
					}
				}
				echo $_SESSION['ADD_FUND_OTP'];*/
			?>
				<!--<div class="row form-group" style="padding:20px;">
					<form method="post" action="" enctype="multipart/form-data">
						<input type="hidden" name="payment_method" value="<?=$payment_method?>" />
						<input type="hidden" name="deposit" value="<?=$_SESSION['investment']?>" />
						<div class="form-group">
							<label class="text-success">One Time Password is sent to your email...</label>
						</div>
						<div class="form-group">
							<label>Enter OTP</label>
							<input type="text" name="valid_otp" value="<?=$_POST['valid_otp']?>">
						</div>
						<div class="form-group" id="confirm_btn">
							<input type="submit" name="submit" value="OTP Valid" class="btn btn-info" />
						</div>
					</form>
				</div>-->
			<?php
			//}
			}
			else{
				echo "<B class='text-danger'>$topup_err</B>";
			}
	
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Login Password!!</B>"; }	
	}
	else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested Member Name!!</B>"; }
	}	
}
elseif(isset($_REQUEST['confirm'])){
	if($_SESSION['payment_method'] == $_REQUEST['payment_method']){
		if($_SESSION['investment'] == $_REQUEST['deposit']){
			if(!isset($_SESSION['session_user_investment'])){
				$allowedfiletypes = array("jpg","png");
				$uploadfolder = $payment_receipt_img_full_path;
				$unique_time = time();
				$unique_name =	"GW".$unique_time.$login_id;
				$uploadfilename = $_FILES['payment_receipt']['name'];
				if(!empty($_FILES['payment_receipt']['name'])){
					$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
					
					if (!in_array($fileext,$allowedfiletypes)){ ?>	
						<script> alert('File Extension Error !');
						window.location ="index.php?page=deposit_wallet"; </script>  <?php
					}	
					else{
						$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
						$unique_name = $unique_name.".".$fileext;
						$time = $systems_date_time;
				
						if(copy($_FILES['payment_receipt']['tmp_name'], $fulluploadfilename)){ 
							$investment = $_SESSION['investment'];
							$payment_method = $_REQUEST['payment_method'];
							$mremark = $_REQUEST['remark'];
							$curr_addresss = $_SESSION['curr_addresss'];
							$curr_name = $_SESSION['curr_name'];
							$_SESSION['session_user_investment'] = 1;
							$sql = "INSERT INTO request_crown_wallet(`ac_type`,`user_id`,`plan_id`,`investment`,`request_crowd`,
							 `bitcoin_address`,`reqid`, `description`,`date`,`c_by`,`transaction_hash`,`mremark`) 
							 VALUES ('$payment_method','$login_id','0','$investment','$investment','$curr_addresss','0',' $curr_name DEPOSIT',
							 '$systems_date_time','$payment_method','$unique_name','$mremark')"; 
							query_execute_sqli($sql);
							unset($_SESSION['curr_addresss'],$_SESSION['curr_name']);
							$msg= "<B style='color:#178102;'>Congratulations !! Your payment request is send to consideration for confirmation!!<br>";
							$msg.="<B style='color:#178102;'>It's Credited Fund TO Activation Wallet Soonly.</B><br>";
							print $msg;
						}	
						else{ 
							?> <script>window.location = "index.php?page=deposit_wallet&pay_err=2";</script> <?php 
						}
					}
				}
				else{ ?> <script>window.location = "index.php?page=deposit_wallet&pay_err=3";</script> <?php }
			}
			else{ ?> <script>window.location = "index.php?page=deposit_wallet";</script> <?php }
		}
		else{ echo "<B class='text-danger'>Error Code 101: Deposit Denied, Because Somthing Goes Wrong!!</B>"; }
	}
	else{ echo "<B class='text-danger'>Error Code 102: Deposit Denied, Because Somthing Goes Wrong!!</B>"; }
}	
if($process == 0){ ?>
	<script> 
	localStorage.removeItem("popState");
	if(localStorage.getItem('paymentState') != null){
		//alert(localStorage.getItem('paymentState'));
		localStorage.removeItem('paymentState');
	}
	</script>
	<div class="col-md-12">
	<?php
	$pay_err = $_REQUEST['pay_err'];
	if($pay_err == 1){ echo "<B class='text-danger'>Error: Invalid file extension!</B>"; }
	elseif($pay_err == 2){ echo "<B class='text-danger'>Error: Payment Slip not saved !</B>"; }
	elseif($pay_err == 3){ echo "<B class='text-danger'>Error: Payment Slip Not Found !</B>"; }
	$_SESSION['bitcoin_addresss'] = $_SESSION['priceBTC'] = "";
	unset($_SESSION['bitcoin_addresss'],$_SESSION['priceBTC'],$_SESSION['session_user_investment'],$_SESSION['succ_msg'],$_SESSION['investment'],$_SESSION['payment_method']);//$_SESSION['ADD_FUND_OTP'],
	$sel_opt = "selected";
	?>
	<form name="invest" method="post" action="index.php?page=deposit_wallet">
	   
	        <div class="col-md-4">Enter your Deposit USD Amount ($)</div>
	        <div class="col-md-8"><input type="text" name="deposit" id="deposit_val" value="<?=$_POST['deposit']?>" class="form-control" pattern="^\d*(\.\d{0,0})?$" placeholder="0.00"  oninvalid="setCustomValidity('Please Enter Numbers With 2 Decimal Digits ')"  required /></div>
	        <div class="col-md-12">&nbsp;</div>
	        <script>
					$(document).ready(function(){
						$('.slectOne').on('change', function() {
							var pay_mode = $(this).val();
							//alert($("#deposit_val").val());
							var deposit_val = parseFloat($("#deposit_val").val());
							cry_rate(pay_mode,deposit_val)
						});
						$('#deposit_val').on('keyup', function() {
							var deposit_val = parseFloat($(this).val());
							var pay_mode = parseInt($('#payment_method').find(":selected").val());//$("#payment_method").val();
							cry_rate(pay_mode,deposit_val)
						});
						
						function cry_rate(pay_mode,deposit_val){
					    //	alert(pay_mode+" "+deposit_val)
							$("#dep_amt").html("");
							
							var pay_cny = pay_mode == 1 ? 'BTC' : 'ETH';
							var app = '<th>Deposit '+pay_cny+': </th>';
							if(pay_mode == 1)	{
								var rate = parseFloat(<?=round($one_usd_value,8)?>)*deposit_val;
							}
							if(pay_mode == 3){
								var rate = parseFloat(<?=round($one_usd_value/$one_eth_value,8)?>)*deposit_val;
							}
							//if(rate != 'undefined' || rate != NaN)
							$("#dep_amt").append(app+'<td>'+rate.toFixed(4)+'</td>');
						}
					});
				</script>
	        <div class="col-md-4">Select Payment Method</div>
	        <div class="col-md-8">	
	            <select class="form-control slectOne" name="payment_method" id="payment_method" required>
					<option value="">Select Method</option>
					<?php
					$i = 1;
					$qu = query_execute_sqli("select * from payment_method where id in(1)");
					while($row = mysqli_fetch_array($qu))
					{
						$paym_id = $row['id'];
						$currency_p = $row['currency'];
						$sel = "";
						if($i == 1)$sel = "checked=\"checked\"";
						
						?>
					    <option value="<?=$paym_id?>" <?=$_POST['payment_method'] == $paym_id ? $sel_opt : "";?>><?=$currency_p?></option>
						<?php
						$i++;
					}
					?>
				</select>
			</div>
			<div class="col-md-12"><span id="dep_amt"></span></div>
			<div class="col-md-12">&nbsp;</div>
			<div class="col-md-12 text-center"><input type="submit" name="submit" value="ADD FUND" class="btn btn-info" /> </div>
			<div class="col-md-12">&nbsp;</div>
	</form>
	</div>
	<?php
}
?>
