<link rel="stylesheet" href="assets/investment/vendor.min.css">
<link rel="stylesheet" href="assets/investment/app.min.css">
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/orange.css">
<?php
include('../security_web_validation.php');
//die("Please contact to customer care.");
?>
<?php
ini_set("display_errors","off");
include("condition.php");
include("function/setting.php");
include("function/direct_income.php");
include("function/send_mail.php");
include("function/pair_point_calc.php");
include("function/all_child.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];
$username = $_SESSION['mlmproject_user_username'];
$inv_epin = $_POST['invest_epin'];
$company_wallet = $main_wallet = get_user_allwallet($id,'activationw');//companyw
$debit_per = 0;//custom wallet deduction
?>
<style>
.li-style{}
@media (max-width:767px)
{
.li-style{}
}

</style>
<script>
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
	var list = [<?php for($i = 0; $i < count($plan_name); $i++){
			echo "'",$plan_name[$i],"',";
		} ?>];
	$("#comm_id").keyup(function (e) {
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
	
	

	$("#secw_id").show();
	$("#community_row").hide();
	$(".buy-now-comm").hide();
	<?php
	if(isset($_POST['epin_submit'])){ ?>
		$("#genpadi").hide();
		$("#genepin").show(); <?php
	}
	else{ ?>
		$("#genpadi").show();
		$("#genepin").hide(); <?php
	} ?>
	$("#genpadi").show();
	$("#genepin").hide();
	$('.top_from').change(function() {
		var top_from = $(this).val();
		if(top_from == 1)	{
			$("#genpadi").show();
			$("#genepin").hide();
		}
		if(top_from == 2){
			$("#genepin").show();
			$("#genpadi").hide();
		}
	});
	$('.select_package').click(function() {
		var pack = $(this).val();
		$('#topup_package').html("");
		var opt =  $('<option value="" >Selected Package</option><option value="'+pack+'" >'+list[pack-1]+'</option>');
		$('#topup_package').append(opt);
		$('#topup_package').val(pack).change();
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
			input = $('<th width="30%">Member UserId</th><th><input type="text" name="comm_id" class="form-control" id="comm_id" value="" /><span id="user-search"></span></th>');
			$('#community_row').append(input);
		}
	});
});
function plan_display(id,ug=false){
		$('#upgrade').remove();
		var nn = list[id-1];
		$("#investment_plan").val(id);
		$("#investment_select").html(nn);
		$(".plan_show").hide();
		$("#secw_id").show();
		if(ug){
			input = $('<input name="upgrade" type="hidden" id="upgrade" value="1">');
			$('#acw').append(input);
		}
}

</script>
<?php
$max_invest = 0;
$sql = "select sum(update_fees) update_fees from reg_fees_structure where user_id='$login_id' and mode = 1 having update_fees > 0";
$tpd_num = mysqli_num_rows($query = query_execute_sqli($sql));
if($tpd_num > 0){
	while($row = mysqli_fetch_array($query)){
		//$upgrade_last = " where id > ".$row['invest_type'];
		$max_invest = $row['update_fees'];
	}
}
mysqli_free_result($query);
$set_max_amount = max($set_max_amount);
$set_min_amount = min($set_amount);
if(isset($_POST['submit'])){
	$investment_plan = $_POST['investment_plan'];
	$investment = $_POST['investment_amt'] = $_POST['amount'];
	$remarks = $_POST['remarks'];
	$user_pin = $_POST['user_pin'];
	
	if(isset($_POST['comm_id'])){
		$request_user_id = get_new_user_id($_POST['comm_id']);
	}
	else{
		$request_user_id = get_new_user_id($username);
	}
	//echo $request_user_id;
	$pass_num = 0;
	
	$sql = "SELECT password FROM users WHERE id_user ='$login_id' AND password = '$user_pin' ";
	$query = query_execute_sqli($sql);
	$get_security_pass = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	if(trim($user_pin) == trim($get_security_pass)){ $pass_num = 1; }
	$sql = "select * from plan_setting where id = $investment_plan and amount <= $investment 
			and max_amount >= $investment";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	mysqli_free_result($query);
	$pass_num = 1;
	if($num > 0){
		if($request_user_id > 0){
			if($pass_num > 0){
				if(isset($_POST['comm_id'])){
					$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users 
					WHERE user_id IN ($login_id)";
					$result = rtrim(mysqli_fetch_array($query = query_execute_sqli($sql))[0],',');
					$result = explode(",",$result);
					mysqli_free_result($query);
				}
				if(!isset($_POST['comm_id'])){
					$result[0] = $request_user_id;
				}
				$rcw_id = 1;
				$_POST['curr_type'] = 2;
				if(1){//in_array($request_user_id,$result)
					$currency_name = "&#36;";
					$set_amount1 = min($set_amount);
					$deduct_amt = $investment;
					
					if($_POST['top-from'] == 1){
						if($company_wallet >= $deduct_amt){
							if(!isset($_SESSION['session_user_investmentacw'])){
								if($request_user_id != $login_id){
									if($_POST['submit'] == 'OTP Valid'){
										if($_SESSION['CONFIRM_TOPUP_OTP'] != $_POST['valid_otp']){
											 echo '<div class="row form-group" style="padding-left:20px;">
													<label class="text-danger">Invalid OTP ...</label>
												</div>';
											$_POST['submit'] = 'BUY PACKAGE';
										}
									}
									
									if($_POST['submit'] == 'BUY PACKAGE'){
										if(!isset($_SESSION['CONFIRM_TOPUP_OTP'])){
											$_SESSION['CONFIRM_TOPUP_OTP'] = $rand = rand(1000,9999);
											
											if(strtoupper($soft_chk) == "LIVE"){
                                    			include "email_letter/activation_otp.php";
                                    			$to = get_user_phone($login_id);
                                    			 // Always set content-type when sending HTML email
                                                $headers = "MIME-Version: 1.0" . "\r\n";
                                                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                                // More headers
                                                $headers .= "From: <$from_email>" . "\r\n";
                                    
                                                mail($to,$title,$db_msg,$headers);
                                    		}	
										}
										//echo $_SESSION['CONFIRM_TOPUP_OTP'];
										?>
<div class="row form-group" style="padding:20px;">
  <form method="post" action="" enctype="multipart/form-data">
    <?php
												$arr_post_key = array_keys($_POST);
												for($i = 0; $i < count($_POST); $i++){
													$kky = $arr_post_key[$i];
												?>
    <input type="hidden" value="<?=$_POST[$kky]?>" name="<?=$kky?>" />
    <?php
												}
												?>
    <div class="form-group">
      <label class="text-success">OTP Sent On Your Registered email...</label>
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
								else{
									$_POST['submit'] = 'OTP Valid';
								}
								if($_POST['submit'] == 'OTP Valid'){
								
								unset($_SESSION['CONFIRM_TOPUP_OTP']);
								
								$_SESSION['session_user_investmentacw'] = 0;
										
									//$sql = "select * from plan_setting where amount <= '$deduct_amt' order by id desc limit 1";
									$sql = "select * from plan_setting where amount <= $investment and max_amount >= $investment and id = $investment_plan order by id desc limit 1";
									$query1 = query_execute_sqli($sql);	
									$plan_id = "";
									while($rr = mysqli_fetch_array($query1))
									{
										$plan_id = $rr['id'];
										$days = $rr['days'];
										$pv = $rr['amount'];
										$profit = $rr['roi_bonus']; 
										$invest_amount = $investment;
									}
									mysqli_free_result($query1);
									$plan_select_type = $plan_id;
									$p_value = 64+$plan_id;
									$p_value = chr($p_value);
									$time = $systems_time;
									$start_date = date('Y-m-d', strtotime($systems_date . ' + 1 days'));
									$end_date = get_date_without_sat_sun($systems_date,$days);
									
									$sql = "insert into reg_process (user_id,psuser_id,c_wal,d_wall,date) values('$login_id','$request_user_id','$company_wallet','".get_user_allwallet($login_id,'amount')."','$systems_date_time')";
									query_execute_sqli($sql);
									$process_id = get_mysqli_insert_id();
									//$pre_plan_detail = get_member_previous_plan($request_user_id);
									$insert_sql = "INSERT INTO reg_fees_structure (user_id , rcw_id,request_crowd, update_fees , date ,start_date,end_date , profit , total_days , invest_type , plan , time,`count`,by_wallet,remarks) 
									VALUES ('$request_user_id' , '$rcw_id' , '$investment' , '$investment' , '$systems_date' , '$start_date','$end_date', '$profit' , '$days' , '$plan_id', '$p_value' , '$systems_date_time','0','1','$remarks') ";
									//die($investment_plan);
									if(query_execute_sqli($insert_sql)){
										$insert_id = get_mysqli_insert_id();
										if($plan_id == 5 or $plan_id == 6){
											$sql = "update reg_fees_structure set mode = 2 where id = '$insert_id'";
											query_execute_sqli($sql);
										}
										$sql = "update wallet set activationw = activationw - '$deduct_amt' where id='$login_id'";
										query_execute_sqli($sql);
										$sql = "update reg_process set ps_mode=1 where user_id='$login_id' and psuser_id='$request_user_id'";
										query_execute_sqli($sql);
										/*$sql = "update reg_fees_structure set mode=99 where id='$pre_id' ";
										query_execute_sqli($sql);*/
										$sql = "update users set package='$plan_id' where id_user='$request_user_id'";
										query_execute_sqli($sql);
										
										$requested_username = get_user_name($request_user_id);
										
										$sqk = "INSERT INTO `ledger`(`user_id`,`by_id`, `particular`, `cr`, `dr`, `balance`, 
										`date_time`) VALUES ('$login_id','$insert_id','Debit TopUp $requested_username','0','$deduct_amt',(SELECT amount FROM wallet where id='$login_id'), '$systems_date_time')";
										query_execute_sqli($sqk);
										$act_desc = $acount_type_desc[5];
										$act_type = $acount_type[5];
										if($login_id != $request_user_id){
											$act_desc = $acount_type_desc[20];
											$act_type = $acount_type[20];
										}
										insert_wallet_account($login_id , $request_user_id , $deduct_amt , $systems_date_time , $act_type ,$act_desc, $mode=2 ,get_user_allwallet($login_id,'amount'),$wallet_type[2],$remarks = "Debit Fund For TOPUP From Deposit Wallet");
										
										//pair_point_calculation($request_user_id,$systems_date,false); // Growth Bonus
										
										get_level_income($request_user_id,$systems_date_time,$insert_id);//blank
										$sql = "update reg_process set ps_mode=2 where user_id='$login_id' and psuser_id='$request_user_id'";
										query_execute_sqli($sql);
										if(strtoupper($soft_chk) == "LIVE"){
                                			include "email_letter/activation_company_wallet_msg.php";
                                			$to = get_user_email($request_user_id);
                                			 // Always set content-type when sending HTML email
                                            $headers = "MIME-Version: 1.0" . "\r\n";
                                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                            // More headers
                                            $headers .= "From: <$from_email>" . "\r\n";
                                
                                            mail($to,$title,$db_msg,$headers);
                                		}
										echo $_SESSION['succ_msg'] =  "<B style='color:#008000;'>$investment $currency_name TOPUP Has Been Completed !</B>";
										
										
									}
									else{
										echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>";
									}
								}
							}
							else{ ?>
<script>
									window.location = "index.php?page=activation_company_wallet";
								</script>
<?php
							}
						}
						else{ echo "<B class='text-danger'>Error : In-Sufficient Wallet Fund!!</B>"; }
						
					}
					
				}
				else{ 
					echo "<B class='text-danger'>Error : Requested Member Have Not In Your Network List!!</B>"; 
				}	
			}
			else{ echo "<B class='text-danger'>Error : Please Enter Correct Login Password!!</B>"; }	
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested Member Name!!</B>"; }
		
	}
	else{ echo "<B class='text-danger'>Error : Please Enter Correct Amount According To Package!!</B>";}
}
else{ 
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg'],$_SESSION['CONFIRM_TOPUP_OTP']);
	if($max_invest  < $set_max_amount){
	?>
<div class="col-md-12">
  <div class="panel panel-success">
    <div class="panel-heading text-left"> Activation Package </div>
  </div>
</div>


<!-- Section Content Starts -->
<div class="pricing-container">
	<!-- Pricing Tables Starts -->
	<ul class="pricing-list bounce-invert">
		<li class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<ul class="pricing-wrapper">
				<!-- Buy Pricing Table #1 Starts -->
				<li data-type="buy" class="is-visible">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-warning">Plan 1</div>
					<br />
					<header class="pricing-header"> 
						<h2>1% <span>Daily for For </span></h2>
						<div class="price">
							<span class="value"> 15 Days</span>
						</div>
						<hr />
						<h2>Currency<span>BTC/ETH</span></h2>
						<hr />
						<h2>Total profit<span>15%</span></h2>
						<hr />
						<h2>Accruals<span>5 days per week</span></h2>
						<hr />
						<h2>Refund of a deposit <span>YES</span> </h2>
						<hr />
						
						<form method="POST" action="" id="package2">
						<input type="hidden" name="top-from" class="top_from" value="1" />
						<input type="hidden" name="investment_plan" class="top_from" value="1" />
							  <div class="col-md-6">
								<label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
								<input type="text" placeholder="$50-  $300" class="input-control" name="amount" required="required" id="amount2">
							  </div>
								<input type="submit" class="btn_primary btn packvrfy" name="submit" value="Invest" />
							  <p id="amount_error" style="color:#fff;"></p>
					  </form>
					</header>
				</li>
				<!-- Buy Pricing Table #1 Ends -->
			</ul>
		</li>
		<li class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<ul class="pricing-wrapper">
				<!-- Buy Pricing Table #1 Starts -->
				<li data-type="buy" class="is-visible">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-warning">Plan 2</div>
					<br />
					<header class="pricing-header">
						<h2>1.3% <span>Daily for For </span></h2>
						<div class="price">
							<span class="value"> 30 Days</span>
						</div>
						<hr />
						<h2>Currency<span>BTC/ETH</span></h2>
						<hr />
						<h2>Total profit<span>39%</span></h2>
						<hr />
						<h2>Accruals<span>5 days per week</span></h2>
						<hr />
						<h2>Refund of a deposit <span>YES</span> </h2>
						<hr />
						<form method="POST" action="" id="package3">
						<input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
						<input type="hidden" name="investment_plan" class="top_from" value="2" />
						<div class="col-md-6">
								<label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
								<input type="text" placeholder="$300-  $800" class="input-control" name="amount" required="required" id="amount3">
						</div>
						<input type="submit" class="btn_primary btn" name="submit" value="Invest" />
						<p id="amount_error" style="color:#fff;"></p>
					  </form>
					</header>
				</li>
				<!-- Buy Pricing Table #1 Ends -->
			</ul>
		</li>
	</ul>
	<ul class="pricing-list bounce-invert">
		<li class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<ul class="pricing-wrapper">
				<!-- Buy Pricing Table #1 Starts -->
				<li data-type="buy" class="is-visible">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-warning">Plan 3</div>
					<br />
					<header class="pricing-header">
						<h2>1.5% <span>Daily for For </span></h2>
						<div class="price">
							<span class="value"> 50 Days</span>
						</div>
						<hr />
						<h2>Currency<span>BTC/ETH</span></h2>
						<hr />
						<h2>Total profit<span>75%</span></h2>
						<hr />
						<h2>Accruals<span>5 days per week</span></h2>
						<hr />
						<h2>Refund of a deposit <span>YES</span> </h2>
						<hr />
						<form method="POST" action="" id="package4">
						<input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
						<input type="hidden" name="investment_plan" class="top_from" value="3" />
							  <div class="col-md-6">
								<label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
								<input type="text" placeholder="$1000-  $5000" class="input-control" name="amount" required="required" id="amount4">
							  </div>
								<input type="submit" class="btn_primary btn" name="submit" value="Invest" />
							<p id="amount_error" style="color:#fff;"></p>
					  </form>	
					</header>
				</li>
				<!-- Buy Pricing Table #1 Ends -->
			</ul>
		</li>
		<li class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<ul class="pricing-wrapper">
				<!-- Buy Pricing Table #1 Starts -->
				<li data-type="buy" class="is-visible">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-warning">Plan 4</div><br />
					<header class="pricing-header">
						<h2>1.8% <span>Daily for For </span></h2>
						<div class="price">
							<span class="value"> 90 Days</span>
						</div>
						<hr />
						<h2>Currency<span>BTC/ETH</span></h2>
						<hr />
						<h2>Total profit<span>162%</span></h2>
						<hr />
						<h2>Accruals<span>5 days per week</span></h2>
						<hr />
						<h2>Refund of a deposit <span>YES</span> </h2>
						<hr />
						<form method="POST" action="" id="package5">
						<input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
						<input type="hidden" name="investment_plan" class="top_from" value="4" />
							 <div class="col-md-6">
								<label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
								<input type="text" placeholder="$5000-  $50000" class="input-control" name="amount" required="required" id="amount5">
							  </div>
								<input type="submit" class="btn_primary btn" name="submit" value="Invest" onclick="buypack()" />
							<p id="amount_error" style="color:#fff;"></p>
					  </form>	
					</header>
				</li>
				<!-- Buy Pricing Table #1 Ends -->
			</ul>
		</li>
	</ul>
	<ul class="pricing-list bounce-invert">
		<li class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<ul class="pricing-wrapper">
				<!-- Buy Pricing Table #1 Starts -->
				<li data-type="buy" class="is-visible">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-warning">SUPERIOR PLAN 1</div><br />
					<header class="pricing-header">
						<h2>5X <span>Time Profit </span></h2>
						<div class="price">
							<span class="value"> 100 Days</span>
						</div>
						<hr />
						<h2>Currency<span>BTC/ETH</span></h2>
						<hr />
						<h2>Total profit<span>500% at the time of maturity</span></h2>
						<hr />
						<h2>Accruals<span>5 days per week</span></h2>
						<hr />
						<h2>Refund of a deposit <span>YES</span> </h2>
						<hr />
						<form method="POST" action="" id="package6">
						<input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
						<input type="hidden" name="investment_plan" class="top_from" value="5" />
							<div class="col-md-6">
								<label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
								<input type="text" placeholder="$1000-  $25000" class="input-control" name="amount" required="required" id="amount6">
							</div>
								<input type="submit" class="btn_primary btn" name="submit" value="Invest" />
						  <p id="amount_error" style="color:#fff;"></p>
					  </form>	
					</header>
				</li>
				<!-- Buy Pricing Table #1 Ends -->
			</ul>
		</li>
		<li class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<ul class="pricing-wrapper">
				<!-- Buy Pricing Table #1 Starts -->
				<li data-type="buy" class="is-visible">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 btn btn-warning">SUPERIOR PLAN 2</div><br />
					<header class="pricing-header">
						<h2>8X <span>Time profit</span></h2>
						<div class="price">
							<span class="value"> 150 Days</span>
						</div>
						<hr />
						<h2>Currency<span>BTC/ETH</span></h2>
						<hr />
						<h2>Total profit<span>800% at the time of maturity</span></h2>
						<hr />
						<h2>Accruals<span>5 days per week</span></h2>
						<hr />
						<h2>Refund of a deposit <span>YES</span> </h2>
						<hr />
						<form method="POST" action="" id="package7">
						<input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
						<input type="hidden" name="investment_plan" class="top_from" value="6" />
							<div class="col-md-6">
								<label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
								<input type="text" placeholder="$2000-  $50000" class="input-control" name="amount" required="required" id="amount7">
							  </div>
								<input type="submit" class="btn_primary btn" name="submit" value="Invest" />
						  <p id="amount_error" style="color:#fff;"></p>
						</form>	
					</header>
				</li>
				<!-- Buy Pricing Table #1 Ends -->
			</ul>
		</li>
	</ul>
</div>

<!--<div class="padding-y-4">
  <div class="row">
    <div class="col-md-6">
      <div class="plate-package bg-cover-top mb-30 bg-comb">
          <div class="plate-package__top plate-package__top_alt">
            <div class="margin-x-2">PLAN 1</div>
          </div>
          <div class="plate-package__table">
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Term of the work</div>
                  <div class="rightcontent">
                    <div class="plandays font-bold">15 days</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Daily Return</div>
                  <div class="rightcontent">
                    <div class="font-bold">1%</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Total profit</div>
                  <div class="rightcontent">
                    <div class="font-bold">15%</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Currency</div>
                  <div class="rightcontent">
                    <div class="font-bold">BTC/ETH</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Accruals</div>
                  <div class="rightcontent">
                    <div class="font-bold">5 days per week</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Refund of a deposit</div>
                  <div class="rightcontent">
                    <div class="font-bold">Yes</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
		   <div class="col-md-12 mb-30">
		 	 <form method="POST" action="" id="package2">
			<input type="hidden" name="top-from" class="top_from" value="1" />
			<input type="hidden" name="investment_plan" class="top_from" value="1" />
				  <div class="col-md-6 mb-30">
					<label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
					<input type="text" placeholder="$50-  $300" class="input-control" name="amount" required="required" id="amount2">
				  </div>
				  <div class="col-md-6">
					<input type="submit" class="btn_primary btn packvrfy" name="submit" value="Invest" />
				  </div>
				  <p id="amount_error" style="color:#fff;"></p>
		  </form>
		  </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="plate-package bg-cover-top mb-30 bg-comb">
          <div class="plate-package__top plate-package__top_alt ">
            <div class="margin-x-2">PLAN 2</div>
          </div>
          <div class="plate-package__table">
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Term of the work</div>
                  <div class="rightcontent">
                    <div class="plandays font-bold">30 days</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Daily Return</div>
                  <div class="rightcontent">
                    <div class="font-bold">1.3%</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Total profit</div>
                  <div class="rightcontent">
                    <div class="font-bold">39%</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Currency</div>
                  <div class="rightcontent">
                    <div class="font-bold">BTC/ETH</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Accruals</div>
                  <div class="rightcontent">
                    <div class="font-bold">5 days per week</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Refund of a deposit</div>
                  <div class="rightcontent">
                    <div class="font-bold">Yes</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
		  <div class="col-md-12 mb-30">
	          <form method="POST" action="" id="package3">
            <input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
            <input type="hidden" name="investment_plan" class="top_from" value="2" />
				 <div class="col-md-6 mb-30">
                    <label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
                    <input type="text" placeholder="$300-  $800" class="input-control" name="amount" required="required" id="amount3">
                  </div>
                 <div class="col-md-6">
                    <input type="submit" class="btn_primary btn" name="submit" value="Invest" />
                  </div>
              	<p id="amount_error" style="color:#fff;"></p>
          </form>
		  </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="plate-package bg-cover-top mb-30 bg-comb">
          <div class="plate-package__top plate-package__top_alt">
            <div class="margin-x-2">PLAN 3</div>
          </div>
          <div class="plate-package__table">
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Term of the work</div>
                  <div class="rightcontent">
                    <div class="plandays font-bold">50 days</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Daily Return</div>
                  <div class="rightcontent">
                    <div class="font-bold">1.5%</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Total profit</div>
                  <div class="rightcontent">
                    <div class="font-bold">75%</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Currency</div>
                  <div class="rightcontent">
                    <div class="font-bold">BTC/ETH</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Accruals</div>
                  <div class="rightcontent">
                    <div class="font-bold">5 days per week</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Refund of a deposit</div>
                  <div class="rightcontent">
                    <div class="font-bold">Yes</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
		  <div class="col-md-12 mb-30">
          	<form method="POST" action="" id="package4">
            <input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
            <input type="hidden" name="investment_plan" class="top_from" value="3" />
                  <div class="col-md-6 mb-30">
                    <label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
                    <input type="text" placeholder="$1000-  $5000" class="input-control" name="amount" required="required" id="amount4">
                  </div>
                  <div class="col-md-6">
                    <input type="submit" class="btn_primary btn" name="submit" value="Invest" />
                  </div>
              	<p id="amount_error" style="color:#fff;"></p>
          </form>
		  </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="plate-package bg-cover-top mb-30 bg-comb">
          <div class="plate-package__top plate-package__top_alt">
            <div class="margin-x-2">PLAN 4</div>
          </div>
          <div class="plate-package__table">
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Term of the work</div>
                  <div class="rightcontent">
                    <div class="plandays font-bold">90 days</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Daily Return</div>
                  <div class="rightcontent">
                    <div class="font-bold">1.8%</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Total profit</div>
                  <div class="rightcontent">
                    <div class="font-bold">162%</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Currency</div>
                  <div class="rightcontent">
                    <div class="font-bold">BTC/ETH</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Accruals</div>
                  <div class="rightcontent">
                    <div class="font-bold">5 days per week</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Refund of a deposit</div>
                  <div class="rightcontent">
                    <div class="font-bold">Yes</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
		  <div class="col-md-12 mb-30">
			  <form method="POST" action="" id="package5">
				<input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
				<input type="hidden" name="investment_plan" class="top_from" value="4" />
					 <div class="col-md-6 mb-30">
						<label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
						<input type="text" placeholder="$5000-  $50000" class="input-control" name="amount" required="required" id="amount5">
					  </div>
					 <div class="col-md-6">
						<input type="submit" class="btn_primary btn" name="submit" value="Invest" onclick="buypack()" />
					</div>
					<p id="amount_error" style="color:#fff;"></p>
			  </form>
		  </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="plate-package bg-cover-top mb-30 bg-comb">
          <div class="plate-package__top plate-package__top_alt">
            <div class="margin-x-2">SUPERIOR PLAN 1</div>
          </div>
          <div class="plate-package__table">
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Term of the work</div>
                  <div class="rightcontent">
                    <div class="plandays font-bold">100 days</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Time Profit</div>
                  <div class="rightcontent">
                    <div class="font-bold">5X</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Total profit</div>
                  <div class="rightcontent">
                    <div class="font-bold">500% at the time of maturity</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Currency</div>
                  <div class="rightcontent">
                    <div class="font-bold">BTC/ETH</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Accruals</div>
                  <div class="rightcontent">
                    <div class="font-bold">5 days per week</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Refund of a deposit</div>
                  <div class="rightcontent">
                    <div class="font-bold">Yes</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
		  <div class="col-md-12 mb-30">
			  <form method="POST" action="" id="package6">
				<input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
				<input type="hidden" name="investment_plan" class="top_from" value="5" />
					<div class="col-md-6 mb-30">
						<label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
						<input type="text" placeholder="$1000-  $25000" class="input-control" name="amount" required="required" id="amount6">
					</div>
					<div class="col-md-6">
						<input type="submit" class="btn_primary btn" name="submit" value="Invest" />
					  </div>
				  <p id="amount_error" style="color:#fff;"></p>
			  </form>
		  </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="plate-package bg-cover-top mb-30 bg-comb">
          <div class="plate-package__top plate-package__top_alt">
            <div class="margin-x-2">SUPERIOR PLAN 2</div>
          </div>
          <div class="plate-package__table">
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Term of the work</div>
                  <div class="rightcontent">
                    <div class="plandays font-bold">150 days</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Time profit</div>
                  <div class="rightcontent">
                    <div class="font-bold">8X</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Total profit</div>
                  <div class="rightcontent">
                    <div class="font-bold">800% at the time of maturity</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="plate-package__row grid">
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Currency</div>
                  <div class="rightcontent">
                    <div class="font-bold">BTC/ETH</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Accruals</div>
                  <div class="rightcontent">
                    <div class="font-bold">5 days per week</div>
                  </div>
                </div>
              </div>
              <div class="plate-package__cell grid-column-12 grid-column-12--md">
                <div class="work_plan">
                  <div class="term_work">Refund of a deposit</div>
                  <div class="rightcontent">
                    <div class="font-bold">Yes</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
		  <div class="col-md-12 mb-30">
          	<form method="POST" action="" id="package7">
            <input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
            <input type="hidden" name="investment_plan" class="top_from" value="6" />
            	<div class="col-md-6 mb-30">
                    <label class="input-label hidden--xl inline-block--xxl">Enter amount</label>
                    <input type="text" placeholder="$2000-  $50000" class="input-control" name="amount" required="required" id="amount7">
                  </div>
                <div class="col-md-6">
                    <input type="submit" class="btn_primary btn" name="submit" value="Invest" />
                  </div>
              <p id="amount_error" style="color:#fff;"></p>
          	</form>
          </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <h2 style="background:#333333; color:#FFFFFF; padding:5px;">REFERRAL PROGRAM: <span style="color:#ffae11;">5% - 3% - 2%</span></h2>
    </div>
  </div>
</div>-->
<!--<form name="invest" id="acw" method="post" action="">
	<input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
	<table class="table table-bordered table-hover" id="secw_id">
		<tr>       
			<th>Minimum-Amount</th>    
			<td> 
				<?=min($set_amount)?> &#36;
			</td>      
		</tr>
		<tr>       
			<th>Maximum-Amount</th>    
			<td> 
				<?=$set_max_amount?> &#36;
			</td>      
		</tr>
		<tr>     
			<th width="30%">Wallet Balance</th>   
			<th><?=$company_wallet?> &#36;</th> 
		</tr>
		<?php
		$sql = "SELECT * FROM plan_setting";
		$query = query_execute_sqli($sql);
		$sel_opt = "selected";
		?>
		<tr> 
			<th width="40%">Select Package</th>
			<td>
				<select name="investment_plan" class="form-control">
					<option value="">Select Package</option>
				<?php
				while($row = mysqli_fetch_array($query)){ 
					$plan_id = $row['id'];
					$plan_name = $row['plan_name'];
					$amount = $row['amount'];
					$max_amount = $row['max_amount']; ?>
					<option value="<?=$plan_id?>" <?=$_POST['plan_select_type'] == $plan_id ? $sel_opt : "";?>><?=$plan_name?> (&#36;<?=$amount."-&#36;".$max_amount?>)</option>
					<?php 
				}?>
				</select>
			</td>
		</tr>
		<tr>       
			<th>Package-Amount (USD)</th>    
			<td> 
				<input type="text"  name="amount" value="<?=$_POST['amount']?>" placeholder="Enter your Topup Amount" class="form-control" /> 
			</td>      
		</tr>  
	  
		<tr>     
			<td class="text-center" colspan="2">    
				<input type="submit" name="submit" value="BUY PACKAGE" class="btn btn-info" />    
			</td>     
		</tr>     
	</table>
	</form> -->
<?php
	}
	else{
		echo "<B class='text-danger'>Upgraded Top-Up Limit Is &#36;$set_max_amount Over !!</B>";
	}
}
?>
<script>
$(document).ready(function() {
	var list1 =[50,300,1000,5000,1000,2000];
	var list2 =[300,800,5000,50000,25000,50000];
    $('form input[name=amount]').keyup(function(e) { // attach the listener to your button
		e.preventDefault();
		
		var form = $(this.form);
		form.find("[name=submit]").hide();
		var pack = form.find("[name=investment_plan]").val();
		var amount = form.find("[name=amount]").val();
		if(amount >= list1[pack-1] && amount <= list2[pack-1] ){
			form.find("#amount_error").html("");
			form.find("[name=submit]").show();
		}
		else{ 
			form.find("#amount_error").html("Choose Investment Amount According To Package");
	   	}
	});
});
</script>
