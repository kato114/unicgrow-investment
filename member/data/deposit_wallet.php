<?php
include('../security_web_validation.php');
?>
<?php
ini_set("display_errors","off");
include("condition.php");
include("function/setting.php");
include("function/direct_income.php");
include("function/pair_point_calc.php");
include("function/send_mail.php");
include("create_withdrawal/coinpayments.inc.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];
$username = $_SESSION['mlmproject_user_name'];
$inv_epin = $_POST['invest_epin'];
$inv_epin = $_POST['invest_epin'];
$credit_per = 0;//custom wallet deduction
$main_wallet = get_wallet_amount($id);
$cps = new CoinPaymentsAPI();
$cps->Setup($Private_Key, $Public_Key);

$process = 0;
if(isset($_POST['submit']))
{	$request_user_id = $login_id;
	
	if($_POST['submit'] == 'ADD FUND'){
		$plan_select_type = $_POST['plan_select_type'];
		$investment = $_POST['deposit'];
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
		if(trim($pin) == trim($get_security_pass)){ $pass_num = 1; } 
		
		$sql = "select user_id from request_crown_wallet where user_id='$request_user_id' and status=0 and ac_type = $payment_method";
		$topup_num = mysqli_num_rows(query_execute_sqli($sql));
		if($topup_num > 0){
			$err = "<B class='text-danger'>Error : Dear ".$username.", Deposit Request Pending !!</B>";
			$_POST['submit'] = "Fail";
		}
	
	if($request_user_id > 0)
	{
		if($topup_num == 0)
		{
			if($investment >= $last_investment or true){
				if($_REQUEST['payment_method'] != ""){
					if($_REQUEST['payment_method'] == 9){
						if(!isset($_SESSION['bitcoin_addresss'])){
							if(true){
								if(strtoupper($soft_chk) == "LIVE"){
									$currency = 'TRX';
									$result = $cps->GetCallbackAddress($currency);

									if($result['error'] != "ok"){
										die($result['error']);
									}
									else{
										$address = $result['result']['address'];
									}
									$_SESSION['bitcoin_addresss'] = $address;	
								}
								else{
									$_SESSION['bitcoin_addresss'] = $address = md5(rand(1000,9999));
									$investment = 100;
								}
							}
						}
						
						$currency_sign = 'TRX' ;
						if($_SESSION['bitcoin_addresss'] != NULL){
							$address = $_SESSION['bitcoin_addresss'];
							$time = date("Y-m-d H:i:s",strtotime($systems_time.$api_deduct_time));
							
							unset($_SESSION['request_user_id']);
							$process = 1;
							$qr_path = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=".$address;
							//print $qr_path;
							?>
							<div class="row" style="padding:20px;">
							<form method="post" action="">
								<input type="hidden" name="plan_select_type" value="<?=$plan_select_type;?>" />
								<input type="hidden" name="order_from" value="3" />
								<div id="bitcoin_addresss_info" style="display:block;">
									<div class="col-md-12 color-red">
										<B>Are you sure?</B><br />
										<B>Current Rate in <?=$currency_sign?> : <?=$investment?></B>
									</div>
									<div class="col-md-8" style="text-align:left;  line-height: 27px; padding-top:15px; color:#000;">
										This is Address <?=$_SESSION['bitcoin_addresss'];?></B> where you have to 
										transfer <B><?=$investment;?></B> <?=$currency_sign?>. To purchase Investment, Put it to this address. This address is using privately by "Unic Grow" officials, please don't use for any other purpose. Confirm address for transfer is 
										 <B><?=$_SESSION['bitcoin_addresss'];?></B> *Placment should exactly 
										 <B><?=$investment;?> <?=$currency_sign?> </B>for confirm your purchase. 
										 <p class="text-red">
												Note* : This Transaction Window With-in 30 Minute , upon payment otherwise this will be cancelled automatically
											</p><br />
										
									</div>
									<div class="col-md-4 text-right">
										<img src="<?=$qr_path?>" width='150' />
										<br>
										<B><?=$investment;?></B> <?=$currency_sign?>
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
									var amount = '<?=$investment?>';
									var baddress = '<?=$_SESSION['bitcoin_addresss']?>';
									var crate = '<?=$investment?>';
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
									var countdown = 60*60;
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
				}
				else{
					echo "<B class='text-danger'>Error : Please Select At-Least One Payment Method !!</B>"; 
				}
			}
			else{
				echo "<B class='text-danger'>Error : Please Enter Gretter Than Equal Last Cash Amount !!</B>"; 
			}
	
		}
		else{ echo $err; }	
	}
	else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested Member Name!!</B>"; }
	}	
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
	$_SESSION['bitcoin_addresss'] = $_SESSION['priceBTC'] = "";
	$_SESSION['session_user_investment'] = 0;
	unset($_SESSION['bitcoin_addresss'],$_SESSION['priceBTC'],$_SESSION['session_user_investment'],$_SESSION['succ_msg'],$_SESSION['investment'],$_SESSION['payment_method']);//$_SESSION['ADD_FUND_OTP'],
	$sel_opt = "selected";
	?>
	<form name="invest" method="post" action="index.php?page=deposit_wallet">
		<table class="table table-bordered table-hover">
			<tr> 
				<th width="40%">Enter your Deposit TRON Amount</th>
				<td> 
					<input type="text" name="deposit" id="deposit_val" value="<?=$_POST['deposit']?>" class="form-control" pattern="^\d*(\.\d{0,0})?$" placeholder="0.00" required />
				</td>      
			</tr> 
			<tr>     
				<th>Select Payment Method</th>   
				<td>  
				<script>
					$(document).ready(function(){
						$('.slectOne').on('change', function() {
						   $('.slectOne').not(this).prop('checked', false);
						   $('#result').html($(this).data( "id" ));
						   if($(this).is(":checked")){
							$('#result').html($(this).data( "id" ));
						   }	
						   else
							$('#result').html('Empty...!');
						});
					});
					$("button").click(function() {
						var btn_value = $(this).val();
						$('#deposit_val').val(btn_value);
					});
					
				</script>
					<div class="form-group">
						<select class="form-control" name="payment_method" required>
						<option value="">Select Method</option>
						<?php
						$i = 1;
						$qu = query_execute_sqli("select * from payment_method where id in(9)");
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
						<!--<option value="4" <?=$_POST['payment_method'] == 4 ? $sel_opt : "";?>>LTCT Test</option>-->
						</select>
					</div>
				</td>    
			</tr> 
	   
			<tr>     
				<td colspan="2" class="text-center">    
					<input type="submit" name="submit" value="ADD FUND" class="btn btn-info" />    
				</td>     
			</tr>     
		</table>
	</form>
	</div>
	<?php
}
?>
