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
		  $("#user-search").html('&nbsp;( '+ data +' )');
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
	
	$('input[name=top_to]:checked').val();

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
			$('#community_row').html("");
			input = $('<th width="30%">Member UserId</th><th><input type="text" name="comm_id" class="form-control" id="comm_id" value="" /><span id="user-search"></span></th>');
			$('#community_row').append(input);
		}
	});
});
</script>

<?php
if(isset($_POST['buy_ticket'])){ 
	if($_POST['tickt_type'] == 1){
		$radio_btn = '<input type="hidden" name="top_to" class="top_to" value="0" /> Buy For Self';
		$class = 'success';
		$th_input = '';
		if(isset($_POST['advance_lottery']))
		$advance_lottery = "<tr>     
			<th> Ticket Packages</th>   
			<th>".$_POST['advance_lottery']." Weeks<input type=\"hidden\" name=\"advance_lottery\" value=".$_POST['advance_lottery']." /></th> 
		</tr>";
	}
	else{
		$radio_btn = '<input type="hidden" name="top_to" class="top_to" value="10" /> Buy For Community';
		$class = 'danger';
		$th_input = '<tr><th width="30%">Member UserId <span id="user-search"></span></th><th><input type="text" name="comm_id" class="form-control" id="comm_id" value="" /></th></tr>';
	}
	?>
	<div class="plan_show">
		<div class="col-md-3">
			<div class="panel panel-<?=$class?>">
				<div class="panel-heading text-center">
					<?=$radio_btn?>
				</div>
			</div>
		</div>
		<!--<div class="col-md-3">
			<div class="panel panel-success">
				<div class="panel-heading text-center">
					<input type="radio" name="top_to" class="top_to" value="0" checked="checked" /> Buy For Self
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="panel panel-danger">
				<div class="panel-heading text-center">
					<input type="radio" name="top_to" class="top_to" value="10" /> Buy For Community
				</div>
			</div>
		</div>-->
	</div>
	
	<div class="col-md-12">&nbsp;</div>
	<form name="invest" id="acw" method="post" action="">
	<table class="table table-bordered table-hover" id="secw_id">
		<tr>     
			<th width="30%"> Deposit Balance</th>   
			<th><?=$company_wallet?> &#36;</th> 
		</tr>
		<?=$advance_lottery?>
		<tr>     
			<th width="30%">Buy From</th>   
			<th><input type="radio" name="buy-from" class="top_from" value="1" checked="checked" /> Deposit Wallet</th> 
		</tr>
		<!--<tr id="community_row"></tr>-->
		<?=$th_input?>
		<tr>       
			<th>Number Of Lottery Buy</th>  
			<td><input type="text" name="nolb" class="form-control"  onkeyup="this.value=this.value.replace(/[^\d{0,4}]/,'')" value="<?=$_POST['nolb']?>"></td>    
		</tr> 
		<!--<tr>       
			<th>Remarks</th>  
			<td><textarea type="text" name="remarks" class="form-control"></textarea></td>    
		</tr>-->     
		<tr>      
			<th>Transaction Password</th>  
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
	$investment = $nolb*$lottery_amount;
	$advance_lottery = 0;
	if(isset($_POST['advance_lottery'])){
		$investment =  $nolb*$lottery_amount*$_POST['advance_lottery'];
		$advance_lottery = $_POST['advance_lottery'];
	}
	$remarks = $_REQUEST['remarks'];
	$request_for = true;
	if(isset($_POST['comm_id'])){
		$request_user_id = get_new_user_id($_POST['comm_id']);
		$request_for = $request_user_id == $login_id ? false : true;
	}
	else{
		$request_user_id = get_new_user_id($username);
	}
	$currency_name = "&#36;";
	$pass_num = 0;
	$user_pin = $_REQUEST['user_pin'];
	$sql = "SELECT user_pin FROM users WHERE id_user ='$login_id' ";
	$get_security_pass = mysqli_fetch_array($query=query_execute_sqli($sql))[0];
	mysqli_free_result($query);	
	if(trim($user_pin) == trim($get_security_pass)){ $pass_num = 1; } 
	{		
		$create_ticket = true;
		$current_week_ticket = true;
		if($request_user_id > 0 and $request_for){
			if(isset($_POST['comm_id'])){
				$last_week = get_pre_nxt_date($systems_date , $lottery_result_day);
				$sql = "select * from account where user_id='$login_id' and type= '".$acount_type[4]."' and account like '%Buy Ticket ".$_POST['comm_id']."%' and DATE_FORMAT(date,'%Y-%m-%d') between '".$last_week[0]."' and '".$last_week[1]."'";
				$query = query_execute_sqli($sql);
				$dnum = mysqli_num_rows($query);
				$create_ticket = $dnum == 0 ? true : false;
				if($nolb > 1){
					$current_week_ticket = false;
				}
				mysqli_free_result($query);	
			}
			if($pass_num > 0){
				if(isset($_POST['comm_id'])){
					$sql = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users 
					WHERE user_id IN ($login_id)";
					$result = rtrim(mysqli_fetch_array($query=query_execute_sqli($sql))[0],',');
					$result = explode(",",$result);
					mysqli_free_result($query);	
				}
				if(!isset($_POST['comm_id'])){
					$result[0] = $request_user_id;
				}
				if($create_ticket){//in_array($request_user_id,$result)
					if($investment > 0){
						if($company_wallet >= $investment){
							if($current_week_ticket){
								if(!isset($_SESSION['session_user_investmentacw']))
								{
									/*if($request_user_id != $login_id)
									{
										if($_POST['submit'] == 'OTP Valid'){
											if($_SESSION['CONFIRM_BUY_OTP'] != $_POST['valid_otp']){
												 echo '<div class="row form-group" style="padding-left:20px;">
														<label class="text-danger">Invalid OTP ...</label>
													</div>';
												$_POST['submit'] = 'CONFIRM TOPUP';
											}
										}
										
										if($_POST['submit'] == 'CONFIRM BUY'){
											if(!isset($_SESSION['CONFIRM_BUY_OTP']))
											{
												$_SESSION['CONFIRM_BUY_OTP'] = $rand = rand(1000,9999);
												if(strtoupper($soft_chk) == "LIVE")
												{
													
													//new registration message
													$phone = get_user_phone($login_id);
													$msg_topup="Confirm Topup By Given OTP : $rand !! By https://www.unicgrow.com";
													send_sms($phone,$msg_topup);
													//End email message
													
													//buy Ticket otp message
														include("email_letter/buy_ticket_otp.php");
														$to = get_user_email($login_id);
														//include("function/full_message.php");
														$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $db_msg);	
											
												}
											}
											echo $_SESSION['CONFIRM_BUY_OTP'];*/
										?>
											<!--<div class="row form-group" style="padding:20px;">
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
											</div>-->
										<?php
										//}
									/*}
									else{
										$_POST['submit'] = 'OTP Valid';
									}*/
									/*if($_POST['submit'] == 'OTP Valid')
									{
									
									unset($_SESSION['CONFIRM_BUY_OTP']);*/
									
									$_SESSION['session_user_investmentacw'] = 0;
										$sql = "update wallet set amount = amount - '$investment' where id='$login_id'";
										query_execute_sqli($sql);
										if(mysqli_affected_rows($con) > 0){
											if($advance_lottery == 0){
												$sql = "select rdate from lottery_ticket where rdate like '%$systems_date%' and mode=1 limit 1";
												$que = query_execute_sqli($sql);
												$num = mysqli_num_rows($que);
												mysqli_free_result($que);
												if($num > 0){
													$p_date = date("Y-m-d",strtotime($systems_date_time." +1 DAY"));
													get_weekly_lottery_ticket($request_user_id,$p_date,$nolb,$type=2,$systems_date_time);
												}
												else{
													get_weekly_lottery_ticket($request_user_id,$systems_date_time,$nolb,$type=2,$systems_date_time);
												}
												insert_wallet_account($login_id , $request_user_id , $investment , $systems_date_time , $acount_type[4] ,$acount_type_desc[4], $mode=2 ,get_user_allwallet($login_id,'amount'),$wallet_type[2],$remarks = "Debit Fund For Buy Ticket");
											}
											else{
												//$p_date = date("Y-m-d H:i:s",strtotime($systems_date_time." -1 DAY"));
												//$date_time = date("Y-m-d 23:59:00",strtotime($p_date." NEXT $lottery_result_day"));
												$sql = "select rdate from lottery_ticket where user_id='$request_user_id' order by rdate desc limit 1";
												$que = query_execute_sqli($sql);
												$num = mysqli_num_rows($que);
												if($num > 0){
													while($ro = mysqli_fetch_array($que)){
														$last_date = date("Y-m-d H:i:s",strtotime($ro['rdate']."-1 WEEK"));
													}
												}
												mysqli_free_result($que);
												$last_date = $num > 0 ? $last_date : $systems_date_time;
												for($al = 1; $al <= $advance_lottery; $al++){
													$date_time = date("Y-m-d H:i:s",strtotime($last_date."+".($al-1+$num)." Week"));
													get_weekly_lottery_ticket($request_user_id,$date_time,$nolb,$type=2,$systems_date_time);
												}
												insert_wallet_account($login_id , $request_user_id , $investment , $systems_date_time , $acount_type[4] ,$acount_type_desc[4], $mode=2 ,get_user_allwallet($login_id,'amount'),$wallet_type[2],$remarks = "Debit Fund For Ticket Packages");
											}
											linkup_level_income($request_user_id,$systems_date_time,$investment);
											$_SESSION['succ_msg'] =  "<B class='text-success'>Buy Ticket Of $currency_name $investment Successfully !</B>";
												
											if(strtoupper($soft_chk) == "LIVE"){
												//Fund Transfer message
												include("email_letter/buy_ticket_msg.php");
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
												//unset($_SESSION['CONFIRM_BUY_OTP']);
											}
											?> <script>
												alert("Buy Ticket Successfully !");
												window.location = "index.php?page=buy_ticket";</script> <?php
										}
										else{ echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>"; }
									//}
								}
								else{ ?> <script>window.location = "index.php?page=buy_ticket";</script> <?php
								}
							}
							else{
								echo "<B class='text-danger'>For Downline 1 Ticket Can Buy For 1 Week !!</B>";
							}
						}
						else{ echo "<B class='text-danger'>Error : In-Sufficient Wallet Fund!!</B>"; }
					}
					else{ echo "<B class='text-danger'>Error : Please Enter Correct Number !!</B>"; }	
				}
				else{ 
					echo "<B class='text-danger'>Error : Buy Ticket For Requested Member Have Already Done For Current Week !!</B>"; 
				}	
			}
			else{ echo "<B class='text-danger'>Error : Please Enter Correct Transaction Password!!</B>"; }	
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested Member Name!!</B>"; }
	}
	
}
else{ 
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg']);//,$_SESSION['CONFIRM_BUY_OTP']
	$sql = "select * from reg_fees_structure where user_id='$login_id'";
	$tpd_num = mysqli_num_rows($query=query_execute_sqli($sql));
	mysqli_free_result($query);	
	/*$bg = "red1";
	$fa_icon = "times";
	if(kyc_exist($login_id) > 0){
		$bg = "green";
		$fa_icon = "check";
	}
	
	$lbg = "red1";
	$lfa_icon = "times";
	if(week_lottery_exist($login_id,$systems_date)){
		$lbg = "green";
		$lfa_icon = "check";
	}*/
	
	$first_prize = round(get_pool_prize($login_id,1),4);
	$secnd_prize = round(get_pool_prize($login_id,2),4);
	$third_prize = round(get_pool_prize($login_id,3),4);
	$forth_prize = round(get_pool_prize($login_id,4),4);
	$fifth_prize = round(get_pool_prize($login_id,5),4);
	?>
	<!--<div class="col-lg-6">
		<div class="widget style1 <?=$bg?>-bg">
			<div class="row">
				<div class="col-xs-8"><h1> <B>KYC</B></h1></div>
				<div class="col-xs-4 text-right"><i class="fa fa-<?=$fa_icon?>-circle fa-5x"></i></div>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="widget style1 <?=$lbg?>-bg">
			<div class="row">
				<div class="col-xs-8"><h1> <B>Lottery</B></h1></div>
				<div class="col-xs-4 text-right"><i class="fa fa-<?=$lfa_icon?>-circle fa-5x"></i></div>
			</div>
		</div>
	</div>
	<div class="col-lg-12">&nbsp;</div>-->
	
	<div class="col-lg-8">
		<div class="panel panel-success">
			<div class="panel-heading"><B><i class="fa fa-trophy"></i> Last Week Winners</B></div>
			<div class="panel-body">
				<div class="col_md_2_5"><div class="pull-left color_lot1"></div>&nbsp;1<sup>st</sup> Prize </div>
				<div class="col_md_2_5"><div class="pull-left color_lot2"></div>&nbsp;2<sup>nd</sup> Prize</div>
				<div class="col_md_2_5"><div class="pull-left color_lot3"></div>&nbsp;3<sup>rd</sup> Prize</div>
				<div class="col_md_2_5"><div class="pull-left color_lot4"></div>&nbsp;4<sup>th</sup> Prize</div>
				<div class="col_md_2_5"><div class="pull-left color_lot5"></div>&nbsp;5<sup>th</sup> Prize</div>
				<div class="col_md_12">&nbsp;</div>
				<?php
				$minus_1_week = date('Y-m-d', strtotime($systems_date."- 1 Week"));
				if(date('D', strtotime($systems_date)) == 'Sat'){
					$minus_1_week = date('Y-m-d', strtotime($systems_date));
				}
				//$minus_1_week = date('Y-m-d', strtotime($systems_date."- 1 Week"));
				$last_week = get_pre_nxt_date($minus_1_week , $lottery_result_day);
				$f_day_week = $last_week[0];
				$l_day_week = $last_week[1];
				
				$sql ="SELECT t1.* , t2.username FROM lottery_ticket t1 
				LEFT JOIN users t2 ON t1.user_id = t2.id_user
				WHERE DATE(t1.`rdate`) BETWEEN '$f_day_week' AND '$l_day_week' AND t1.rank > 0 ORDER BY t1.rank ASC";
				
				$query = query_execute_sqli($sql);
				$num = mysqli_num_rows($query);
				if($num > 0){
					/*$color1 = '#'.substr(md5(rand()), 0, 6);
					$color2 = '#'.substr(md5(rand()), 0, 6);
					$color3 = '#'.substr(md5(rand()), 0, 6);
					$color4 = '#'.substr(md5(rand()), 0, 6);
					$color5 = '#'.substr(md5(rand()), 0, 6);*/
					$color1 = '#259F51';
					$color2 = '#412EB4';
					$color3 = '#0A456A';
					$color4 = '#553041';
					$color5 = '#B38AAD';

					$i = 1;
					?>
					<marquee width="100%" scrollamount="3">
						<?PHP
						while($row = mysqli_fetch_array($query)){
							$username = $row['username'];
							$rank = $row['rank'];
							/*switch($i){
								case 1 : $color = $color1;	break;
								case 2 : $color = $color2;	break;
								case 3 : $color = $color3;	break;
								case 4 : $color = $color4;	break;
								case 5 : $color = $color5;	break;
								default : $color = $color6;	
							}*/
							switch($rank){
								case 1 : $color = $color1;	break;
								case 2 : $color = $color2;	break;
								case 3 : $color = $color3;	break;
								case 4 : $color = $color4;	break;
								case 5 : $color = $color5;	break;
							}
							?> 
							<span style="color:<?=$color?>"> 
								<span style="font-size:16px"><i class="fa fa-user"></i> <B><?=$username;?></B></span>
							</span> <?PHP
							$i++;
						} ?>
					</marquee> <?php
				} 
				else{ echo "<b class='text-danger'>There are no winners</b>"; }
				mysqli_free_result($query);	 ?>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="panel panel-danger">
			<div class="panel-heading"><B><i class="fa fa-clock-o"></i> Next Drawing in</B></div>
			<table class="table">
				<tr>
					<th>DAYS</th>
					<th>HOURS</th>
					<th>MINS</th>
					<th>SECS</th>
				</tr>
				<tr id="clock_lottery" style="font-size: 30px"></tr>
			</table>
			<!--<div class="panel-body">
				<div class="col-md-3">DAYS</div>
				<div class="col-md-3">HOURS</div>
				<div class="col-md-3">MINS</div>
				<div class="col-md-3">SECS</div>
					
				<div id="clock_lottery" style="font-size: 32px"></div>
			</div>-->
		</div>
	</div>
	<div class="col-lg-4">
		<div class="widget style1 green1-bg">
			<div class="row">
				<div class="col-xs-4"><img src="assets/img/01.png" /><!--<i class="fa fa-money fa-5x"></i>--></div>
				<div class="col-xs-8 text-right">
					<span> First Prize</span>
					<h2 class="font-bold">&#36;<?=$first_prize?></h2>
				</div>
				<div class="col-xs-12 text-right">
					[ WSB(L1) : &#36;<?=$first_prize*10/100?> | WSB(L2) : &#36;<?=$first_prize*5/100?> ]
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="widget style1 blue1-bg">
			<div class="row">
				<div class="col-xs-4"><img src="assets/img/02.png" /><!--<i class="fa fa-money fa-5x"></i>--></div>
				<div class="col-xs-8 text-right">
					<span> Second Prize</span>
					<h2 class="font-bold">&#36;<?=$secnd_prize?></h2>
				</div>
				<div class="col-xs-12 text-right">
					[ WSB(L1) : &#36;<?=$secnd_prize*10/100?> | WSB(L2) : &#36;<?=$secnd_prize*5/100?> ]
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="widget style1 maroon1-bg">
			<div class="row">
				<div class="col-xs-4"><img src="assets/img/03.png" /><!--<i class="fa fa-money fa-5x"></i>--></div>
				<div class="col-xs-8 text-right">
					<span> Third Prize</span>
					<h2 class="font-bold">&#36;<?=$third_prize?></h2>
				</div>
				<div class="col-xs-12 text-right">
					[ WSB(L1) : &#36;<?=$third_prize*10/100?> | WSB(L2) : &#36;<?=$third_prize*5/100?> ]
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-md-offset-2">
		<div class="widget style1 brown1-bg">
			<div class="row">
				<div class="col-xs-4"><img src="assets/img/04.png" /><!--<i class="fa fa-money fa-5x"></i>--></div>
				<div class="col-xs-8 text-right">
					<span> Fourth Prize</span>
					<h2 class="font-bold">&#36;<?=$forth_prize?></h2>
				</div>
				<div class="col-xs-12 text-right">
					[ WSB(L1) : &#36;<?=$forth_prize*10/100?> | WSB(L2) : &#36;<?=$forth_prize*5/100?> ]
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="widget style1 velvel1-bg">
			<div class="row">
				<div class="col-xs-4"><img src="assets/img/05.png" /><!--<i class="fa fa-money fa-5x"></i>--></div>
				<div class="col-xs-8 text-right">
					<span> Fifth Prize</span>
					<h2 class="font-bold">&#36;<?=$fifth_prize?></h2>
				</div>
				<div class="col-xs-12 text-right">
					[ WSB(L1) : &#36;<?=$fifth_prize*10/100?> | WSB(L2) : &#36;<?=$fifth_prize*5/100?> ]
				</div>
			</div>
		</div>
	</div>
	<!--<div class="col-lg-12">
		<div class="alert alert-info">
			<h3><B>Info Regarding Tora Weekly Lottery</B></h3>
		</div>
	</div>-->
	<div class="col-lg-6">
		<form method="post" action="">
			<input type="hidden" name="tickt_type" value="1" />
			<button name="buy_ticket" type="submit" class="buy_tkt btn-block">
				<div class="widget style1 navy-bg">
					<div class="row vertical-align mt-n1">
						<div class="col-xs-2"><i class="fa fa-ticket fa-4x"></i></div>
						<div class="col-xs-10"><h1 class="font-bold">Buy Ticket </h1></div>
					</div>
				</div>
			</button>
		</form>
	</div>
	<div class="col-lg-6">
		<form method="post" action="">
			<input type="hidden" name="tickt_type" value="2" />
			<button name="buy_ticket" type="submit" class="buy_tkt btn-block">
				<div class="widget style1 skyblue-bg">
					<div class="row vertical-align">
						<div class="col-xs-2"><i class="fa fa-ticket fa-4x"></i></div>
						<div class="col-xs-10"><h1 class="font-bold">Buy Ticket For Downline</h1></div>
					</div>
				</div>
			</button>
		</form>
	</div>
	<div class="col-lg-12">&nbsp;</div>
	<div class="col-lg-12">
	<p class="fa-2x"><strong>Ticket Packages</strong></p>
		<p style="font-size:14px">Never miss a draw</p>
		<form method="post" action="">
			<p>
				<input type="hidden" name="tickt_type" value="1" />
				<select name="advance_lottery" class="form-control">
					<?php
					for($i = 0; $i < count($advance_lottery_ticket); $i++){ ?>
					<option value="<?=$advance_lottery_ticket[$i]?>"><?=$advance_lottery_ticket[$i]?> Weeks</option>
					<?php
					} ?>
				</select>
			</p>
			<input type="submit" name="buy_ticket" value="Buy Tickets" class="btn btn-warning btn-block" />
		</form>
	</div>
	<div class="col-lg-12">&nbsp;</div>
	<?php
}






$week_pn = get_pre_nxt_date($systems_date , $lottery_result_day);
$p_date = $week_pn[0];
$n_date = $week_pn[1];

if(date('D', strtotime($systems_date)) == 'Sat'){
	$n_date = date('Y-m-d', strtotime($systems_date."+ 1 Week"));
}

$n_date = date('Y-m-d 00:00:00', strtotime($n_date));
$swr = "SELECT TIMESTAMPDIFF(SECOND,'$systems_date_time', '$n_date') as seconds";
$result = mysqli_fetch_array($query = query_execute_sqli($swr));
$tot_second = $result[0];
mysqli_free_result($query);	
?>

<script>
var clocks = new Array();
clocks['clock_lottery'] = parseInt('<?=$tot_second?>');
</script>

<style>
.buy_tkt{
	border: 0;
	background-color: transparent;
}
.col_md_2_5{
	width:20%; float:left; font-weight:bold;
}

.color_lot1 {
	width: 18px;
	height: 18px;
	border-radius: 15px;
	background: #259F51;
}
.color_lot2 {
	width: 18px;
	height: 18px;
	border-radius: 15px;
	background: #412EB4;
}
.color_lot3 {
	width: 18px;
	height: 18px;
	border-radius: 15px;
	background: #0A456A;
}
.color_lot4 {
	width: 18px;
	height: 18px;
	border-radius: 15px;
	background: #553041;
}
.color_lot5 {
	width: 18px;
	height: 18px;
	border-radius: 15px;
	background: #B38AAD;
}
</style>