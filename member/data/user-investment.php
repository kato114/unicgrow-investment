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
include("function/blockchain_trasaction.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];
$username = $_SESSION['mlmproject_user_name'];
$inv_epin = $_POST['invest_epin'];
$inv_epin = $_POST['invest_epin'];
$main_wallet = get_wallet_amount($id);
?>
<style>
.li-style{}
@media (max-width:767px)
{
.li-style{}
}

</style>

<script>
function select_plan(a) {
    var x = (a.value || a.options[a.selectedIndex].value);
	if(x == '')
	{
		alert("Please Select Plan");
		$( "#share" ).empty();
	}
	else
	{
		$.post( "selcet_plan.php?val="+x, function( data ) {
		  $( "#share" ).html( data );
		});
	}
}

$( document ).ready(function() {
	$("#share_quantity").keyup(function() {
		var list = [<?=implode(",",$profit);?>];
		
		var value = parseFloat($(this).val());
		
		var sq = $("#plan").val();
		
		var percent = parseFloat(value*list[sq-1]/100);
		var tot_amt = parseFloat(value+percent).toFixed(6);
		$("#share_amount").val((tot_amt));
	});
	$(".payment_mode").click(function() {
		var mode = $(this).val();
		if(mode ==1){
			$("#reinvest_wallet_info").show();
			$("#bitcoin_addresss_info").hide();
		}
		if(mode == 2){
			$("#reinvest_wallet_info").show();
			$("#bitcoin_addresss_info").hide();
		}
		if(mode == 3){
			$("#bitcoin_addresss_info").show();
			$("#reinvest_wallet_info").hide();
		}
	});
});
</script>
<script>$(document).ready(function() {	
	$("#search_username").keyup(function (e) {
		$(this).val($(this).val().replace(/\s/g, ''));
		var search_username = $(this).val();
		if(search_username.length < 3){$("#get-result").html('');return;}
		
		if(search_username.length >= 3){
		
			$("#get-result").html('Lodding...');
			$.post('check_username.php', {'search_username':search_username},function(data)
			{
			  $("#get-result").html(data);
			});
		}
	});	
});		
</script>
<?php

if(isset($_POST['submit']))
{
	$plan_select_type = $_POST['plan_select_type'];
	$investment = $set_amount[$plan_select_type-1];
	$request_user_id = $login_id;//get_new_user_id($_REQUEST['request_user']);
	$pin = $_REQUEST['pin'];
	$pass_num = 0;
	$one_usd_value = 0.00084961;	
	if($soft_chk == "LIVE"){
		$one_usd_value = get_USD_TO_BITCOIN("USD",1);	
	}

	$sql = "SELECT password FROM users WHERE id_user ='$login_id' AND password = '$pin' ";
	$get_security_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
	if(trim($pin) == trim($get_security_pass)){ $pass_num = 1; } 
	
	$sql = "select user_id from request_crown_wallet where user_id='$request_user_id' and status=0";
	$topup_num = mysqli_num_rows(query_execute_sqli($sql));
	if($topup_num > 0){
		echo "<B class='text-danger'>Error : Dear ".$_REQUEST['request_user'].", Investment Pending !!</B>";
	}
	else{
		if($request_user_id > 0)
		{
			if($pass_num > 0)
			{
				//$qu = query_execute_sqli(" SELECT `get_chield_by_parent`($login_id) AS `get_chield_by_parent`;");
				//$result = explode(",",mysqli_fetch_array($qu)[0]);
				$result[] = $login_id;
				if(in_array($request_user_id,$result)){
					if($investment > 0){
						if(!isset($_SESSION['bitcoin_addresss'])){
							$address = admin_btc_address();
							$_SESSION['bitcoin_addresss'] = $address;
							//query_execute_sqli("update admin_btc_address set mode=1 where address='$address'");
						}
						if($_SESSION['bitcoin_addresss'] != NULL){
							$address = $_SESSION['bitcoin_addresss'];
							$priceBTC = $investment * $one_usd_value;
							$time = date("Y-m-d H:i:s",strtotime($systems_time.$api_deduct_time));
							if(!isset($_SESSION['priceBTC'])){
								do{
									$priceBTC = $priceBTC + round((rand(1000,9999)/1000),2)/10000;
									$sql = "SELECT * FROM request_crown_wallet WHERE `date` >='$time' AND request_crowd='$priceBTC'";
									$num = mysqli_num_rows(query_execute_sqli($sql));
								}while($num > 0);
								$_SESSION['priceBTC'] = $priceBTC;
							}
							$priceBTC = $_SESSION['priceBTC'];
							unset($_SESSION['investment']);
							unset($_SESSION['request_user_id']);
							$_SESSION['request_user_id'] = $request_user_id;
							$_SESSION['investment'] = $investment;
							$_SESSION['priceBTC'] = $priceBTC;	
							if(!isset($_SESSION['session_user_investment']))	
							$_SESSION['session_user_investment'] = 1;
							?>
							
							<div class="row" style="padding:20px;">
							<form method="post" action="">
								<input type="hidden" name="plan_select_type" value="<?=$plan_select_type;?>" />
								<input type="hidden" name="order_from" value="3" />
								<div id="bitcoin_addresss_info" style="display:block;">
									<div class="col-md-12 color-red">
										<B>Are you sure?</B><br />
										<B>Current Rate in Bitcoin : <?=$one_usd_value?></B>
									</div>
									<div class="col-md-8" style="text-align:left;  line-height: 27px; padding-top:15px; color:#000;">
										This is Address <?=$_SESSION['bitcoin_addresss'];?></B> where you have to 
										transfer <B><?=$priceBTC;?></B> Bitcoin. To purchase &#36; Investment, Put it to
										 this address. This address is using privately by unicgrow.com officials, please 
										 don't use for any other purpose. Confirm address for transfer is 
										 <B><?=$_SESSION['bitcoin_addresss'];?></B> *Placment should exactly 
										 <B><?=$priceBTC;?> Bitcoin </B>for confirm your purchase. 
										 <p class="text-red">
											Note* : This Transaction Complete With-in 10 Minute , Otherwise Transaction 
											Will be Cancelled
										</p><br />
									</div>
									<div class="col-md-4 text-right">
										<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=bitcoin:<?=$_SESSION['bitcoin_addresss'];?>?amount=<?=$priceBTC;?>&message=Crypto Yobit" />
									</div>
									<div class="col-md-12 text-left">
										<p><B><span id="pay_information"></span></B></p>
									</div>
									<div class="col-md-12 text-left">
										<p><B>Timer For Pay : <span id="pay_counddown"></span></B></p>
									</div>
								</div>
								<!--<div id="main_wallet_info" style="display:none; margin-bottom:3%">
									<div class="col-md-2"><B>Enter Pin No. : </B></div>
									<div class="col-md-10 color-red">
										<input type="text" name="pin_type" value="<?=$_REQUEST['pin_type']?>" style="width:80%;" class="form-control"/>
									</div>
									<div class="col-md-12 color-red"><B>Are you sure?</B></div>
								</div>-->
								<br />
								<div class="col-md-12 text-left" id="confirm_btn">
									<input type="submit" name="confirm" value="Confirm" class="btn btn-info" />
								</div>
							</form>
							</div>
							<script>
							$( document ).ready(function(){
								var timerData = [];
								function check_transfer(row,timer_id){
									var seconds = timerData[row].remaining;
									var amount = '<?=$priceBTC?>';
									var baddress = '<?=$_SESSION['bitcoin_addresss']?>';
									var url = "GetTransferInfo.php?val="+amount+"&address="+baddress;
									$.post(url, function( data ) {
										obj = JSON.parse(data);
										if(obj.result > 0){
											 localStorage.setItem('paymentState',obj.info);
											 $("#confirm_btn").show();
										}else{
											document.getElementById("pay_information").innerHTML = obj.info;
										}
									});
									
									if (seconds <= 0 && $("input[name='order_from']:checked").val() == 3 ) {
										
										$("#confirm_btn").hide();
									} else {
										seconds--;
									}
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
								
									var mode = 3;//$(this).val();
									if(mode == 3){
									$("#confirm_btn").hide();
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
											var countdown = 60*40;
											timer(1,countdown,'pay_counddown');
											check_transfer_timer(2,countdown,'myModal');
											localStorage.setItem('popState',countdown);
										}
										else{
											var countdown = localStorage.getItem('popState');
											timer(1,countdown,'pay_counddown');
											check_transfer_timer(2,countdown,'myModal');
										}
									}
									
								
							});
							</script>
						 <?php
						}
						else{
							echo "<B class='text-danger'>Error : Please Wait For Sometime to Mining !!</B>"; 
						}
					}
					else{
						echo "<B class='text-danger'>Error : Please Enter Correct Order Multiple of &#36;$investment_mul or Greater of &#36;$investment_mul !!</B>"; 
					}	
				}
				else{ echo "<B class='text-danger'>Error : Requested Member Have Not In Your Network List!!</B>"; }	
			}
			else{ echo "<B class='text-danger'>Error : Please Enter Correct Security Password!!</B>"; }	
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested Member Name!!</B>"; }
	}	
}

elseif(isset($_POST['confirm']))
{
	if($_SESSION['session_user_investment'] == 1)
	{
		if($_POST['confirm'] == 'Confirm')
		{
			$process = 0;
			$plan_select_type = $_REQUEST['plan_select_type'];
			$hashcode = preg_replace('/[^a-zA-Z0-9_.]/', '', $_REQUEST['hashcode']);
			$request_user_id = $_SESSION['request_user_id'];
			$investment = $_SESSION['investment'];
			$order_from = $_REQUEST['order_from'];
			$btc_addrss = $_SESSION['bitcoin_addresss'];
			//$main_wallet = get_wallet_amount($id);
			$balance = 0;
			$ac_type = 'Bitcoin Money';$c_by = 3;$priceBTC = $_SESSION['priceBTC'];
			
			$make_user_investment_now = 2;
				
			if($make_user_investment_now == 2) { 
				$reqid = $request_user_id;
				if($request_user_id == $login_id)$reqid = 0;
				 $sql = "INSERT INTO `request_crown_wallet`(`ac_type`,`user_id`, `plan_id`,`investment`,`request_crowd`,`bitcoin_address`,`reqid`, `description`,`date`,`c_by`,`transaction_hash`) VALUES ('$ac_type','$request_user_id','$plan_select_type','$investment','$priceBTC','$btc_addrss','$reqid','$description','$systems_date_time','$c_by','$hashcode')"; 
				query_execute_sqli($sql);
				$pid = get_mysqli_insert_id();
				$_SESSION['session_user_investment'] = 0;
				query_execute_sqli("update admin_btc_address set mode=1 where address='$btc_addrss'");
				$num_add = mysqli_num_rows(query_execute_sqli("select * from admin_btc_address"));
				$num_mode = mysqli_num_rows(query_execute_sqli("select * from admin_btc_address where mode=1"));
				
				if($num_mode == $num_add){ query_execute_sqli("update admin_btc_address set mode=0"); }
				$result = check_trasaction($pid,$systems_date_time);
				if($result[0] == 1){
					
					echo $_SESSION['succ_msg'] =  "<B style='color:#008000;'>$investment &#36; TOPUP Has Been Completed !</B>";
					$_SESSION['send_payment'] = 1;
				}
				else{
					echo $_SESSION['succ_msg'] =  "<B class='text-danger'>$investment &#36; TOPUP Has Been Pending ! it will confirm in 10 minute !</B>";
				}	
				$to = get_user_email($id);  //message for mail
				$title = "Investment Message";
				$db_msg = $investment &#36; TOPUP Has Been Completed !;
				include("function/full_message.php");
				$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
				$SMTPChat = $SMTPMail->SendMail();
					
				
			}	
		}
	}else{
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=user-investment\"";
		echo "</script>";
	}
}	
else
{ 
	$_SESSION['bitcoin_addresss'] = $_SESSION['priceBTC'] = "";
	unset($_SESSION['bitcoin_addresss'],$_SESSION['priceBTC'],$_SESSION['session_user_investment'],$_SESSION['succ_msg']);
	$sql = "select * from plan_setting";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{ 
		$plan_id = $row['id'];
		$plan_name = $row['plan_name'];
		$amount = $row['amount'];
		$roi = $row['daily_profit'];
		$ref_per = $row['refferal_percent'];
		$bin_per = $row['binary_percent']; 
		$daily_upto = $row['days'];
		switch ($plan_id) {
			case 1: $btn = "primary"; break;
			case 2: $btn = "warning"; break;
			case 3: $btn = "default"; break;
			case 4: $btn = "danger"; break;
			case 5: $btn = "success"; break;
			case 6: $btn = "info"; break;
			case 7: $btn = "primary"; break;
		}
		?>
		<div class="col-md-3" style="width:20%;float:left; margin-top:1%;">
			<div class="panel panel-default">
				<div class="panel-heading text-center">
					<div class="btn btn-<?=$btn?>"><i class="fa fa-road"></i><B><?=$plan_name?></B></div>
					<!--<img src="assets/images/plan/plan<?=$plan_id?>.png" width="100" />-->
				</div>
				<div class="panel-body">
					<ul class="list-unstyled text-left" style="font-weight:bold;">
						<li>
							<i class="fa fa-check"></i>  
							<span  class="text-danger">&#36;<?=$amount?></span>
						</li>
						<li>
							<i class="fa fa-check"></i> Binary 
							<span  class="text-danger"><?=$bin_per?>%</span>
						</li>
						<li>
							<i class="fa fa-check"></i> ROI 
							<span  class="text-danger"><?=$roi?>%</span>
						</li>
						<li>
							<i class="fa fa-check"></i> Max ROI 
							<span  class="text-danger"><?=$daily_upto?> Week</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<?php
		$k++;
	} ?>
	<script> 
	localStorage.removeItem("popState");
	if(localStorage.getItem('paymentState') != null){
		//alert(localStorage.getItem('paymentState'));
		localStorage.removeItem('paymentState');
	}
	</script>
	<div class="col-md-12">
	<form name="invest" method="post" action="">
		<table class="table table-bordered table-hover">
			<input type="hidden" name="plan_select_type" value="1" />
			<!--<tr>
				<td>
					<input type="radio" name="wal_type"  value="cash_wal" checked="checked" /> Cash Wallet
					<input type="radio" name="wal_type"  value="roi_wal" /> Repurchase Wallet
				</td>
				<td class="epin box"><?=round($cash_wal,2);?></td>
				<td class="cash box"><?=round($roi_wal,2);?></td>
			</tr>-->
			<tr> 
				<th width="40%">Select Plan</th>
				<td> 
					<!--<ul class="inline text-left chart-legend"> -->
					<select name="plan_select_type" class="form-control">
					<?php
					$chk_sqk = "SELECT invest_type FROM reg_fees_structure WHERE user_id='$login_id' order by id desc limit 1";
					$sql = "SELECT *,(
							CASE WHEN EXISTS($chk_sqk)
							  THEN ($chk_sqk)
							  ELSE 0
							END 
							)AS invest_type from plan_setting having id>=invest_type";
					$query = query_execute_sqli($sql);
					$k = 0;
					while($r = mysqli_fetch_array($query))
					{
						$amount = $r['amount'];
						$chk = "";
						if($k==0)$chk = "checked=\"checked\""; ?>
						
						<option value="<?=$r['id']?>"><?=$r['plan_name']?></option>
						
						<!--<li  style="width:auto; font-weight:bold;" >
						<input type="radio" value="<?=$r['id']?>" name="plan_select_type" id="plan" onchange="select_plan(this)" <?=$chk?> required />&nbsp;<?=$r['plan_name']?><!--&nbsp;($<?=$amount/$usd_in_BP?>)&nbsp;&nbsp;--></li>-->
						<?php
						$k++;
					} ?>
					</select>
					<!--</ul>-->
				</td>      
			</tr> 
			 <tr>     
				<th>Member Username &nbsp;&nbsp;&nbsp;&nbsp;<span id="get-result"></span></th>   
				<td>       
					<input type="text" name="request_user" value="<?=get_user_name($login_id)?>" class="form-control" id="search_username" />
				</td>    
			</tr>     
			<tr>     
				<th>Member Username &nbsp;&nbsp;&nbsp;&nbsp;<span id="get-result"></span></th>   
				<td>       
					<input type="text" name="request_user" value="<?=get_user_name($login_id)?>" class="form-control" id="search_username" />
				</td>    
			</tr>  
			<tr>      
				<th>Password</th>  
				<td><input type="password"  name="pin" class="form-control" /></td>    
			</tr>    
			<tr>     
				<td colspan="2" class="text-center">    
					<input type="submit" name="submit" value="TOPUP" class="btn btn-info" />    
				</td>     
			</tr>     
		</table>
	</form>
	</div>
	<?php
}
?>
