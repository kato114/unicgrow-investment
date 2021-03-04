<?php
include('../security_web_validation.php');
//die("Please contact to customer care.");

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
</script>

<?php
if(isset($_POST['buy_ticket'])){ ?>
	<div class="plan_show">
		<div class="col-md-3">
			<div class="panel panel-success">
				<div class="panel-heading text-center">
					<input type="radio" name="top_to" class="top_to" value="0"  checked="checked" /> Buy For Self
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-danger">
				<div class="panel-heading text-center">
					<input type="radio" name="top_to" class="top_to" value="10" /> Buy For Community
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-12">&nbsp;</div>
	<form name="invest" id="acw" method="post" action="">
	<table class="table table-bordered table-hover" id="secw_id">
		<tr>     
			<th width="30%"> E-Wallet Balance</th>   
			<th><?=$company_wallet?> &#36;</th> 
		</tr>
		<tr>     
			<th width="30%">Buy From</th>   
			<th><input type="radio" name="buy-from" class="top_from" value="1" checked="checked" /> E-Wallet</th> 
		</tr>
		<tr id="community_row"></tr>
		<tr>       
			<th>Number Of Lottery Buy</th>  
			<td><input type="text" name="nolb" class="form-control" value="<?=$_POST['nolb']?>"></td>    
		</tr> 
		<tr>       
			<th>Remarks</th>  
			<td><textarea type="text" name="remarks" class="form-control"></textarea></td>    
		</tr>     
		<tr>      
			<th>Password</th>  
			<td><input type="password" name="user_pin" class="form-control" /></td>    
		</tr>     
		<tr>     
			<td class="text-center" colspan="2">    
				<input type="submit" name="back" value="Back" class="btn btn-danger" />    
				<input type="submit" name="submit" value="CONFIRM BUY" class="btn btn-info" />    
			</td>     
		</tr>     
	</table>
	</form> <?php
}
elseif(isset($_POST['submit'])){
	$nolb = $_REQUEST['nolb'];
	$investment = $_REQUEST['investment_amt'] = $nolb*$lottery_amount;
	$remarks = $_REQUEST['remarks'];
	if(isset($_POST['comm_id'])){
		$request_user_id = get_new_user_id($_POST['comm_id']);
	}
	else{
		$request_user_id = get_new_user_id($username);
	}
	$currency_name = "&#36;";
	$pass_num = 0;
	$user_pin = $_REQUEST['user_pin'];
	$sql = "SELECT password FROM users WHERE id_user ='$login_id' ";
	$get_security_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
	if(trim($user_pin) == trim($get_security_pass)){ $pass_num = 1; } 
	{
		if($request_user_id > 0){
			if($pass_num > 0){
				if(isset($_POST['comm_id'])){
					$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users 
					WHERE user_id IN ($login_id)";
					$result = rtrim(mysqli_fetch_array(query_execute_sqli($sql))[0],',');
					$result = explode(",",$result);
				}
				if(!isset($_POST['comm_id'])){
					$result[0] = $request_user_id;
				}
				if(1){//in_array($request_user_id,$result)
					if($investment > 0){
						if($company_wallet >= $investment){
							if(!isset($_SESSION['session_user_investmentacw'])){
								if($request_user_id != $login_id){
									if($_POST['submit'] == 'OTP Valid'){
										if($_SESSION['CONFIRM_BUY_OTP'] != $_POST['valid_otp']){
											 echo '<div class="row form-group" style="padding-left:20px;">
													<label class="text-danger">Invalid OTP ...</label>
												</div>';
											$_POST['submit'] = 'CONFIRM TOPUP';
										}
									}
									
									if($_POST['submit'] == 'CONFIRM BUY'){
										if(!isset($_SESSION['CONFIRM_BUY_OTP'])){
											$_SESSION['CONFIRM_BUY_OTP'] = $rand = rand(1000,9999);
											if(strtoupper($soft_chk) == "LIVE"){
												
												//new registration message
												$phone = get_user_phone($login_id);
												$msg_topup="Confirm Topup By Given OTP : $rand !! By https://www.unicgrow.com";
												send_sms($phone,$msg_topup);
												//End email message
										
											}
										}
										echo $_SESSION['CONFIRM_BUY_OTP'];
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
								
								unset($_SESSION['CONFIRM_BUY_OTP']);
								
								$_SESSION['session_user_investmentacw'] = 0;
									$sql = "update wallet set activationw = activationw - '$investment' where id='$login_id'";
									query_execute_sqli($sql);
									if(mysqli_affected_rows($con) > 0){
										get_weekly_lottery_ticket($request_user_id,$systems_date_time,$nolb,$type=2);
										insert_wallet_account($login_id , $request_user_id , $investment , $systems_date_time , $acount_type[4] ,$acount_type_desc[4], $mode=2 ,get_user_allwallet($login_id,'activationw'),$wallet_type[2],$remarks = "Debit Fund For TOPUP From Deposit Wallet");
										$_SESSION['succ_msg'] =  "<B class='text-success'>Buy Ticket Of $currency_name $investment Successfully !</B>";
											
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
											unset($_SESSION['CONFIRM_BUY_OTP']);
										}
										?> <script>window.location = "index.php?page=buy_ticket";</script> <?php
									}
									else{ echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>"; }
								}
							}
							else{ ?> <script>window.location = "index.php?page=buy_ticket";</script> <?php
							}
						}
						else{ echo "<B class='text-danger'>Error : In-Sufficient Wallet Fund!!</B>"; }
					}
					else{ echo "<B class='text-danger'>Error : Please Enter Correct Number !!</B>"; }	
				}
				else{ 
					echo "<B class='text-danger'>Error : Requested Member Have Not In Your Network List!!</B>"; 
				}	
			}
			else{ echo "<B class='text-danger'>Error : Please Enter Correct Password!!</B>"; }	
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested Member Name!!</B>"; }
	}
	
}
else{ 
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg'],$_SESSION['CONFIRM_BUY_OTP']);
	$sql = "select * from reg_fees_structure where user_id='$login_id'";
	$tpd_num = mysqli_num_rows(query_execute_sqli($sql));
	?>
	<div class="col-lg-8">
		<div class="panel panel-success">
			<div class="panel-heading"><B><i class="fa fa-trophy"></i> Last Week Winners</B></div>
			<div class="panel-body">
				<?php
				$minus_1_week = date('Y-m-d', strtotime($systems_date."- 1 Week"));
				$last_week = get_pre_nxt_date($minus_1_week , $binary_pay_day);
				$f_day_week = $last_week[0];
				$l_day_week = $last_week[1];
				
				$sql ="SELECT t1.* , t2.username FROM lottery_ticket t1 
				LEFT JOIN users t2 ON t1.user_id = t2.id_user
				WHERE DATE(t1.`rdate`) BETWEEN '$f_day_week' AND '$l_day_week' AND t1.rank > 0";
				
				$query = query_execute_sqli($sql);
				$num = mysqli_num_rows($query);
				if($num > 0){
					$color1 = '#'.substr(md5(rand()), 0, 6);
					$color2 = '#'.substr(md5(rand()), 0, 6);
					$color3 = '#'.substr(md5(rand()), 0, 6);
					$color4 = '#'.substr(md5(rand()), 0, 6);
					$color5 = '#'.substr(md5(rand()), 0, 6);
					$color6 = '#'.substr(md5(rand()), 0, 6);
					$color7 = '#'.substr(md5(rand()), 0, 6);
					$color8 = '#'.substr(md5(rand()), 0, 6);
					$color9 = '#'.substr(md5(rand()), 0, 6);
					$i = 1;
					?>
					<marquee width="100%" scrollamount="3">
						<?PHP
						while($row = mysqli_fetch_array($query)){
							$username = $row['username'];
							switch($i){
								case 1 : $color = $color1;	break;
								case 2 : $color = $color2;	break;
								case 3 : $color = $color3;	break;
								case 4 : $color = $color4;	break;
								case 5 : $color = $color5;	break;
								default : $color = $color6;	
							}
							 ?> 
							<div style="float:left; padding:5px 0 0 15px; color:<?=$color?>"> 
								<h3><i class="fa fa-user"></i><?=$username;?></h3>
							</div> <?PHP
							$i++;
						} ?>
					</marquee> <?php
				} 
				else{ echo "<h3 class='text-danger'>There are no winners</h3>"; } ?>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="panel panel-danger">
			<div class="panel-heading"><B><i class="fa fa-clock-o"></i> Next Drawing in</B></div>
			<div class="panel-body">
				<div class="col-md-3">DAYS</div>
				<div class="col-md-3">HOURS</div>
				<div class="col-md-3">MINS</div>
				<div class="col-md-3">SECS</div>
					
				<div id="clock_lottery" style="font-size: 32px"></div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="widget style1 black-bg">
			<div class="row">
				<div class="col-xs-4"><i class="fa fa-money fa-5x"></i></div>
				<div class="col-xs-8 text-right">
					<span> First Prize</span>
					<h2 class="font-bold">&#36;<?=get_pool_prize($login_id,'current')?></h2>
				</div>
				<!--<div class="col-xs-12 text-right">
					<B>Tickets Sold : <?=get_pool_prize_cnt($login_id,'current')?></B>
				</div>-->
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="widget style1 sky-bg">
			<div class="row">
				<div class="col-xs-4"><i class="fa fa-money fa-5x"></i></div>
				<div class="col-xs-8 text-right">
					<span> Second Prize</span>
					<h2 class="font-bold">&#36;<?=get_pool_prize($login_id,'next')?></h2>
				</div>
				<!--<div class="col-xs-12 text-right">
					<B>Tickets Sold : <?=get_pool_prize_cnt($login_id,'next')?></B>
				</div>-->
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="widget style1 maroon-bg">
			<div class="row">
				<div class="col-xs-4"><i class="fa fa-money fa-5x"></i></div>
				<div class="col-xs-8 text-right">
					<span> Third Prize</span>
					<h2 class="font-bold">&#36;<?=get_pool_prize($login_id,'tot_paid')?></h2>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="widget style1 lazur-bg">
			<div class="row">
				<div class="col-xs-4"><i class="fa fa-money fa-5x"></i></div>
				<div class="col-xs-8 text-right">
					<span> Fourth Prize</span>
					<h2 class="font-bold">&#36;<?=get_pool_prize($login_id,'tot_paid')?></h2>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="widget style1 lazur-bg">
			<div class="row">
				<div class="col-xs-4"><i class="fa fa-money fa-5x"></i></div>
				<div class="col-xs-8 text-right">
					<span> Fifth Prize</span>
					<h2 class="font-bold">&#36;<?=get_pool_prize($login_id,'tot_paid')?></h2>
				</div>
			</div>
		</div>
	</div>
	<!--<div class="col-lg-4">
		<div class="widget style1 black-bg">
			<div class="row">
				<div class="col-xs-4"><i class="fa fa-money fa-5x"></i></div>
				<div class="col-xs-8 text-right">
					<span> Current Prize Pool</span>
					<h2 class="font-bold">&#36;<?=get_pool_prize($login_id,'current')?></h2>
				</div>
				<div class="col-xs-12 text-right">
					<B>Tickets Sold : <?=get_pool_prize_cnt($login_id,'current')?></B>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="widget style1 sky-bg">
			<div class="row">
				<div class="col-xs-4"><i class="fa fa-money fa-5x"></i></div>
				<div class="col-xs-8 text-right">
					<span> Next Weeks Pool</span>
					<h2 class="font-bold">&#36;<?=get_pool_prize($login_id,'next')?></h2>
				</div>
				<div class="col-xs-12 text-right">
					<B>Tickets Sold : <?=get_pool_prize_cnt($login_id,'next')?></B>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="widget style1 maroon-bg">
			<div class="row">
				<div class="col-xs-4"><i class="fa fa-money fa-5x"></i></div>
				<div class="col-xs-8 text-right">
					<span> Total Prizes Paid</span>
					<h2 class="font-bold">&#36;<?=get_pool_prize($login_id,'tot_paid')?></h2>
				</div>
				<div class="col-xs-12 text-right">
					<B>Total Winners : <?=get_pool_prize_cnt($login_id,'tot_paid')?></B>
				</div>
			</div>
		</div>
	</div>-->
	
	<div class="col-lg-4">
		<div class="panel panel-default">
			<!--<div class="panel-heading"><B>This Weeks Draw</B></div>-->
			<div class="panel-body">
				<video playsinline="" autoplay="autoplay" loop="" width="100%">
					<source src="assets/video/lottery.mp4" type="video/mp4">
					<source src="assets/video/lottery.oga" type="video/ogg">
					<source src="assets/video/lottery.webm" type="video/webm">
					Your browser does not support the video tag.				
				</video>
				<p>&nbsp;</p>
				<p class="fa-2x"><strong>This Week Draw</strong></p>
				<p style="font-size:14px">
					You have : <B><?=get_user_pool_prize_cnt($login_id,'current')?></B> Tickets
				</p>
				<form method="post" action="">
					<p>
						<input type="text" name="nolb" class="form-control" value="<?=$_POST['nolb']?>" 
						placeholder="Quantity" />
					</p>
					<input type="submit" name="buy_ticket" value="Buy Tickets" class="btn btn-warning btn-block" />
				</form>
				
				<p>&nbsp;</p><p>&nbsp;</p>
				<p class="fa-2x"><strong>Next Week Draw</strong></p>
				<p style="font-size:14px">
					You have : <B><?=get_user_pool_prize_cnt($login_id,'next')?></B> Tickets
				</p>
				<form method="post" action="">
					<input type="hidden" name="type" value="next_week" />
					<p>
						<input type="text" name="nolb" class="form-control" value="<?=$_POST['nolb']?>" 
						placeholder="Quantity" />
					</p>
					<input type="submit" name="buy_ticket" value="Buy Tickets" class="btn btn-warning btn-block" />
				</form>
				
				<p>&nbsp;</p><p>&nbsp;</p>
				<p class="fa-2x"><strong>Ticket Packages</strong></p>
				<p style="font-size:14px">Never miss a draw</p>
				<form method="post" action="">
					<p>
						<select name="productid" class="form-control">
							<option value="20" selected="selected">4 Weeks</option>
							<option value="21">14 Weeks</option>
							<option value="22">26 Weeks</option>
							<option value="23">52 Weeks</option>
						</select>
					</p>
					<input type="submit" name="buy_ticket" value="Buy Tickets" class="btn btn-warning btn-block" />
				</form>
			</div>
		</div>
	</div>
	<div class="col-lg-8">
		<div class="alert alert-warning">
			<h2 class="m-1">
				Last Weeks Results - 
				<B class="text-warning">Prize Pool: &#36;<?=get_pool_prize($login_id,'last_week')?></B>
			</h2>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php
				$sql = "SELECT t2.username FROM lottery_ticket t1 
				LEFT JOIN users t2 ON t1.user_id = t2.id_user
				WHERE t1.user_id = '$login_id' AND t1.rank = 1";
				$query = query_execute_sqli($sql);
				?>
				<div class="col-md-12">
					<div class="col-md-2"><i class="fa fa-trophy info-box-icon yellow-bg"></i></div>
					<div class="col-md-10 fa-2x"><B>FIRST PRIZE <br />&#36;<?=get_prize_amt_by_rank(1)?></B></div>
				</div>
				<div class="col-md-12">&nbsp;</div>
				<?php
				while($row = mysqli_fetch_array($query)){
					$username = $row['username']; 
					?>
					<div class="col-md-3">
						<div class="winner-box">
							<img style="margin-top: -2px;" src="assets/img/<?=rand(1,6)?>.png" height="20"> 
							<?=$username?>             			
						</div>
					</div> <?php
				} ?>
				
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php
				$sql = "SELECT t2.username FROM lottery_ticket t1 
				LEFT JOIN users t2 ON t1.user_id = t2.id_user
				WHERE t1.user_id = '$login_id' AND t1.rank = 2";
				$query = query_execute_sqli($sql);
				?>
				<div class="col-md-12">
					<div class="col-md-2"><i class="fa fa-trophy info-box-icon blue-bg"></i></div>
					<div class="col-md-10 fa-2x"><B>SECOND PRIZE <br />&#36;<?=get_prize_amt_by_rank(2)?></B></div>
				</div>
				<div class="col-md-12">&nbsp;</div>
				<?php
				while($row = mysqli_fetch_array($query)){
					$username = $row['username']; 
					?>
					<div class="col-md-3">
						<div class="winner-box">
							<img src="assets/img/<?=rand(1,6)?>.png" height="20"> 
							<?=$username?>             			
						</div>
					</div> <?php
				} ?>
				
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php
				$sql = "SELECT t2.username FROM lottery_ticket t1 
				LEFT JOIN users t2 ON t1.user_id = t2.id_user
				WHERE t1.user_id = '$login_id' AND t1.rank = 3";
				$query = query_execute_sqli($sql);
				?>
				<div class="col-md-12">
					<div class="col-md-2"><i class="fa fa-trophy info-box-icon navy-bg"></i></div>
					<div class="col-md-10 fa-2x"><B>THIRD PRIZE <br />&#36;<?=get_prize_amt_by_rank(3)?></B></div>
				</div>
				<div class="col-md-12">&nbsp;</div>
				<?php
				while($row = mysqli_fetch_array($query)){
					$username = $row['username']; 
					?>
					<div class="col-md-3">
						<div class="winner-box">
							<img src="assets/img/<?=rand(1,6)?>.png" height="20"> 
							<?=$username?>             			
						</div>
					</div> <?php
				} ?>
				
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php
				$sql = "SELECT t2.username FROM lottery_ticket t1 
				LEFT JOIN users t2 ON t1.user_id = t2.id_user
				WHERE t1.user_id = '$login_id' AND t1.rank = 4";
				$query = query_execute_sqli($sql);
				?>
				<div class="col-md-12">
					<div class="col-md-2"><i class="fa fa-trophy info-box-icon maroon-bg"></i></div>
					<div class="col-md-10 fa-2x"><B>FOURTH PRIZE <br />&#36;<?=get_prize_amt_by_rank(4)?></B></div>
				</div>
				<div class="col-md-12">&nbsp;</div>
				<?php
				while($row = mysqli_fetch_array($query)){
					$username = $row['username']; 
					?>
					<div class="col-md-3">
						<div class="winner-box">
							<img src="assets/img/<?=rand(1,6)?>.png" height="20"> 
							<?=$username?>             			
						</div>
					</div> <?php
				} ?>
				
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-body">
				<?php
				$sql = "SELECT t2.username FROM lottery_ticket t1 
				LEFT JOIN users t2 ON t1.user_id = t2.id_user
				WHERE t1.user_id = '$login_id' AND t1.rank = 5";
				$query = query_execute_sqli($sql);
				?>
				<div class="col-md-12">
					<div class="col-md-2"><i class="fa fa-trophy info-box-icon black-bg"></i></div>
					<div class="col-md-10 fa-2x"><B>FIFTH PRIZE <br />&#36;<?=get_prize_amt_by_rank(5)?></B></div>
				</div>
				<div class="col-md-12">&nbsp;</div>
				<?php
				while($row = mysqli_fetch_array($query)){
					$username = $row['username']; 
					?>
					<div class="col-md-3">
						<div class="winner-box">
							<img src="assets/img/<?=rand(1,6)?>.png" height="20"> 
							<?=$username?>             			
						</div>
					</div> <?php
				} ?>
				
			</div>
		</div>
	</div>
	<?php
}
?>

<script>
var clocks = new Array();
clocks['clock_lottery'] = parseInt('<?=get_next_draw_tot_seconds($login_id)?>');
</script>