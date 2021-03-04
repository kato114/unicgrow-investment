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
$company_wallet = $main_wallet = get_user_allwallet($id,'amount');//companyw


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

if(isset($_POST['submit']))
{
	$investment_plan = $_POST['investment_plan'];
	$investment = $_POST['investment_amt'] = $set_amount[$investment_plan-1];
	$investment_pv = $set_plan_pv[$investment_plan-1];
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
	$get_security_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
	if(trim($user_pin) == trim($get_security_pass)){ $pass_num = 1; }
	
	$sql = "select * from reg_fees_structure where user_id='$request_user_id' order by id desc limit 1";
	$numq = mysqli_num_rows($query = query_execute_sqli($sql));
	$numqv = 1;
	if($numq > 0){
		while($row = mysqli_fetch_array($query)){
			$upgrade_last = $row['invest_type'];
		}
		$numq = 1;
		if(in_array($upgrade_last+1,$plan_id)){
			$numq = 0;
		}
		if($upgrade_last >= $investment_plan){
			$numqv = 0;
		}
	}
	mysqli_free_result($query);
	if($numq == 0){
		if($numqv == 1):
		{
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
						$deduct_pv = $investment_pv;
						$pre_pv = $pre_amount = 0;
						$pre_amounta = $pre_pvv = array();
						
						if($_POST['top-from'] == 1){
							if(in_array($investment,$set_amount)){
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
														
														//new registration message
														$phone = get_user_phone($login_id);
														$msg_topup="Buy Package By Given OTP : $rand !! By https://www.unicgrow.com";
														send_sms($phone,$msg_topup);
														//End email message
													
														//new email message
														include("email_letter/activation_otp.php");
														$to = get_user_email($login_id);
														$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $db_msg);	
														//End email message
												
												
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
										else{
											$_POST['submit'] = 'OTP Valid';
										}
										if($_POST['submit'] == 'OTP Valid'){
										
										unset($_SESSION['CONFIRM_TOPUP_OTP']);
										
										$_SESSION['session_user_investmentacw'] = 0;
												
											//$sql = "select * from plan_setting where amount <= '$deduct_amt' order by id desc limit 1";
											$sql = "select * from plan_setting where id = '$investment_plan' order by id desc limit 1";
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
											$end_date = date('Y-m-d', strtotime($systems_date . " + $days days"));
											$sql = "select * from users where id_user='$request_user_id'";
											$sq = query_execute_sqli($sql);
											$nsq = mysqli_num_rows($sq);
											if($nsq > 0){
												while($rt = query_execute_sqli($sq)){
													$pos = $rt['position'];
												}
											}
											mysqli_free_result($sq);
											$sql = "insert into reg_process (user_id,psuser_id,c_wal,d_wall,date) values('$login_id','$request_user_id','$company_wallet','".get_user_allwallet($login_id,'amount')."','$systems_date_time')";
											query_execute_sqli($sql);
											$process_id = get_mysqli_insert_id();
											
											$insert_sql = "INSERT INTO reg_fees_structure (user_id , rcw_id,request_crowd, update_fees , date ,start_date , profit , total_days , invest_type , plan , time,`count`,by_wallet,remarks,position) 
											VALUES ('$request_user_id' , '$rcw_id' , '$pv' , '$investment' , '$systems_date' , '$start_date', '$profit' , '$days' , '$plan_id', '$p_value' , '$systems_date_time','0','1','$remarks',$pos) ";
											if(query_execute_sqli($insert_sql)){
												$insert_id = get_mysqli_insert_id();
												$sql = "update wallet set amount = amount - '$deduct_amt' where id='$login_id'";
												query_execute_sqli($sql);
												$sql = "update reg_process set ps_mode=1 where user_id='$login_id' and psuser_id='$request_user_id'";
												query_execute_sqli($sql);
												$sql = "update reg_fees_structure set mode=99 where id='$pre_id' ";
												query_execute_sqli($sql);
												$sql = "update users set package='$pv' where id_user='$request_user_id'";
												query_execute_sqli($sql);
												$requested_username = get_user_name($request_user_id);
												
												$sqk = "INSERT INTO `ledger`(`user_id`,`by_id`, `particular`, `cr`, `dr`, `balance`, 
												`date_time`) VALUES ('$login_id','$insert_id','Debit TopUp $requested_username','0','$deduct_amt',(SELECT amount FROM wallet where id='$login_id'), '$systems_date_time')";
												query_execute_sqli($sqk);
												$act_desc = $acount_type_desc[11];
												$act_type = $acount_type[11];
												if($login_id != $request_user_id){
													$act_desc = $acount_type_desc[20];
													$act_type = $acount_type[20];
												}
												insert_wallet_account($login_id , $request_user_id , $deduct_amt , $systems_date_time , $act_type ,$act_desc, $mode=2 ,get_user_allwallet($login_id,'amount'),$wallet_type[2],$remarks = "Debit Fund For TOPUP From Deposit Wallet");
												get_direct_income($request_user_id,$systems_date_time,$insert_id,$deduct_pv);//Referral Bonus
												pair_point_calculation($request_user_id,$systems_date,false); // Growth Bonus
												
												
												$sql = "update reg_process set ps_mode=2 where user_id='$login_id' and psuser_id='$request_user_id'";
												query_execute_sqli($sql);
												echo $_SESSION['succ_msg'] =  "<B style='color:#008000;'>$investment $currency_name TOPUP Has Been Completed !</B>";
												
												if(strtoupper($soft_chk) == "LIVE"){
													//Fund Transfer message
													include("email_letter/activation_company_wallet_msg.php");
													$to = get_user_email($request_user_id);
													//include("function/full_message.php");
													$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $db_msg);	
													
													
													$msg_topup = "Your ID is Topup successfully !! By https://www.unicgrow.com";
													$phone = get_user_phone($request_user_id);
													send_sms($phone,$msg_topup);
													
													
													$to_user = get_user_email($login_id);
													$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to_user, $title, $db_msg);
													
													$req_id = get_user_name($request_user_id);
													$phone = get_user_phone($login_id);
													$msg_topup="$req_id is Topup successfully by you !! By https://www.unicgrow.com";
													send_sms($phone,$msg_topup);
													//End email message
													unset($_SESSION['CONFIRM_TOPUP_OTP']);
												}
											}
											else{
												echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>";
											}
										}
									}
									else{ ?>
										<script>
											window.location = "index.php?page=activation_company_wallet";
										</script> <?php
									}
								}
								else{ echo "<B class='text-danger'>Error : In-Sufficient Wallet Fund!!</B>"; }
							}
							else{ echo "<B class='text-danger'>Error : Please Enter Correct Amount Of Plan !!</B>"; }	
						}
						if($_POST['top-from'] == 2){
							
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
		else : echo "<B class='text-danger'>Error : Can't Upgrade By This Package !!</B>";endif;
	}
	else{ echo "<B class='text-danger'>Upgraded All Package Al-ready !!</B>"; }
}
else
{ 
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg'],$_SESSION['CONFIRM_TOPUP_OTP']);
	$sql = "select * from reg_fees_structure where user_id='$login_id' order by id desc limit 1";
	$tpd_num = mysqli_num_rows($query = query_execute_sqli($sql));
	$upgrade_last = "";
	if($tpd_num > 0){
		while($row = mysqli_fetch_array($query)){
			//$upgrade_last = " where id > ".$row['invest_type'];
			$upgrade_last_plan = $row['invest_type'];
		}
	}
	mysqli_free_result($query);
	?>
	<div class="col-md-12">
		<div class="panel panel-success">
			<div class="panel-heading text-left">
				Activation Package
			</div>
		</div>
	</div>
	<!--<div class="col-md-12 plan_show">
		<div class="col-md-4" style="padding-left:0px;padding-right:20px;">
			<div class="panel panel-success">
				<div class="panel-heading text-center">
					<input type="radio" name="top_to" class="top_to" value="0"  checked="checked" /> Buy For Self
				</div>
			</div>
		</div>
		<div class="col-md-4" style="padding-left:10px;padding-right:10px;">
			<div class="panel panel-danger">
				<div class="panel-heading text-center">
					<input type="radio" name="top_to" class="top_to" value="10" /> Buy For Community
				</div>
			</div>
		</div>
	</div>-->
	
	<div class="col-md-3 text-center">
		<div class="alert-default">
		<!--<div class="btn btn-default"><B><?=$plan_name?></B></div><br />-->
			<ul class="list-unstyled">
				<li><img src="images/plan/free.png"  class="img-responsive" /></li>&nbsp;
				<li><span  class="btn btn-info"><i class="fa fa-check">&nbsp;</i></span></li>
			</ul>
		</div>
	</div>
	<?php
	$sql = "SELECT * FROM plan_setting";
	$query = query_execute_sqli($sql);
	$i = 0;
	while($row = mysqli_fetch_array($query))
	{ 
		$i++;
		$plan_id = $row['id'];
		$plan_name = $row['plan_name'];
		$image = $row['image'];
		$amount = $row['amount'];
		$binary_percent = $row['binary_percent'];
		$referral_bonus = $row['referral_bonus'];
		$share_bonus = $row['share_bonus'];
		$tgaming_bonus = $row['tgaming_bonus'];
		$capping = $row['capping'];
		
		$fa_check = "";
		$pack_btn = '<button  name="cash" value="'.$plan_id.'"  class="btn btn-info select_package">Buy Package</button>';
		//$upgrade_last_plan >= 3 custome condition
		if(($upgrade_last_plan != "" and $plan_id <= $upgrade_last_plan) or $upgrade_last_plan >= 4 or $plan_id <= 1 ) {
			$pack_btn = '<span  class="btn btn-info"><i class="fa fa-check">&nbsp;</i></span>';
			$fa_check = " fa-check";
		}
		if($plan_id <= 3)
		{
		?>
		<div class="col-md-3 text-center">
			<div class="alert-default">
			<!--<div class="btn btn-default"><B><?=$plan_name?></B></div><br />-->
				<ul class="list-unstyled">
					<li><img src="images/plan/<?=$plan_id?>.png"  class="img-responsive" /></li>&nbsp;
					<li><?=$pack_btn?></li>
				</ul>
			</div>
		</div>
		<?php
		
		if($i == 3)
		{
		?>
		<div class="col-md-12">
			<div class="panel panel-success">
				<div class="panel-heading text-left">
					Activation Package with prelaunch offer.
				</div>
			</div>
		</div>
		<?php
		}
		}else{
		?>
		<div class="col-md-3 text-center">
			<div class="alert-default">
			<!--<div class="btn btn-default"><B><?=$plan_name?></B></div><br />-->
				<ul class="list-unstyled">
					<li><img src="images/plan/<?=$plan_id?>.png"  class="img-responsive" /></li>&nbsp;
					<li><?=$pack_btn?></li>
				</ul>
			</div>
		</div>
		<?php
		}
	} ?>

	<div class="col-md-12">&nbsp;</div>
	<form name="invest" id="acw" method="post" action="">
	<input type="hidden" name="top-from" class="top_from" value="1" checked="checked" />
	<table class="table table-bordered table-hover" id="secw_id">
		<tr>     
			<th width="30%">Wallet Balance</th>   
			<th><?=$company_wallet?> &#36;</th> 
		</tr>
		<!--<tr>     
			<th width="30%">Top-Up From</th>   
			<th><input type="hid" name="top-from" class="top_from" value="1" checked="checked" /> E-Wallet</th> 
		</tr>-->
		<tr id="community_row"></tr>
		<tr id="genpadi">      
			<th>Package</th>  
			<td> <?php
				$sql = "select * from plan_setting $upgrade_last";
				$query = query_execute_sqli($sql);
				$num = mysqli_num_rows($query);
				if($num > 0){ ?>
					<select class="form-control" name="investment_plan" id="topup_package" required>
						<option value="" >Selected Package</option>
					</select> <?php
				}
				else{ echo "<B class='form-control'>Upgrade Completed</B>"; }
				mysqli_free_result($query); ?>
			</td>    
		</tr>
		<tr id="genepin">       
			<th>Epin</th>    
			<td> 
				<input type="text"  name="epin_chk" value="<?=$_POST['invest_epin'].$_POST['epin_chk']?>" class="form-control" /> 
			</td>      
		</tr>  
		<tr>       
			<th>Remarks</th>  
			<td><textarea type="text" name="remarks" class="form-control"></textarea></td>    
		</tr>     
		<tr>      
			<th>Login Password</th>  
			<td><input type="password" name="user_pin" class="form-control" /></td>    
		</tr>     
		<tr>     
			<td class="text-center" colspan="2">    
				<input type="submit" name="back" value="Back" class="btn btn-info" />    
				<input type="submit" name="submit" value="BUY PACKAGE" class="btn btn-info" />    
			</td>     
		</tr>     
	</table>
	</form> 
	
	<div class="alert-default">
	<br>Note :
	<br>1. Member can upgrade for activation pack only.
	<br>2. Promotional package can purchase one time only.
	<br>
	<br>
	</div>
	<?php
}
?>



