<?php
include('../security_web_validation.php');
die("Please contact to customer care.");
?>
<?php
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");
$login_id = $id = $_SESSION['mlmproject_user_id'];

if($_SESSION['mlmproject_user_id'] == 273 or $_SESSION['mlmproject_user_id'] == 2010 or $_SESSION['mlmproject_user_id'] == 2035 or $_SESSION['mlmproject_user_id'] == 21  or $_SESSION['mlmproject_user_id'] == 25 or $_SESSION['mlmproject_user_id'] == 217 or $_SESSION['mlmproject_user_id'] == 27400000 or $_SESSION['mlmproject_user_id'] == 275 or $_SESSION['mlmproject_user_id'] == 304 or $_SESSION['mlmproject_user_id'] == 305 or $_SESSION['mlmproject_user_id'] == 5110000000 or $_SESSION['mlmproject_user_id'] == 2059 or $_SESSION['mlmproject_user_id'] == 7691 or $_SESSION['mlmproject_user_id'] == 21 or $_SESSION['mlmproject_user_id'] == 25 or $_SESSION['mlmproject_user_id'] == 26 or $_SESSION['mlmproject_user_id'] == 217 or $_SESSION['mlmproject_user_id'] == 272 or $_SESSION['mlmproject_user_id'] == 273 or $_SESSION['mlmproject_user_id'] == 274 or $_SESSION['mlmproject_user_id'] == 275 or $_SESSION['mlmproject_user_id'] == 304 or $_SESSION['mlmproject_user_id'] == 305 or $_SESSION['mlmproject_user_id'] == 511){
	die("Contact to Administrator!");
}

$main_wallet = get_user_allwallet($id,'amount');
$company_wallet = get_user_allwallet($id,'activationw');

if(isset($_POST['submit']))
{	
	$request_user_id = $login_id;
	if($_POST['submit'] == 'OTP Valid'){
		if($_SESSION['WTDW_OTP'] == $_POST['valid_otp']){
			 $pass_num = 1;
			 $investment = $_POST['trans_amt'];
		}
		else{
			echo '<div class="form-group" style="padding-left:20px;">
					<label class="text-danger">Invalid OTP ...</label>
				</div>';
			$_POST['submit'] = 'Transfer';
		}
	}
	if($_POST['submit'] == 'Transfer'){
		$investment = $_POST['trans_amt'];
		unset($_SESSION['investment']);
		$_SESSION['investment'] = $investment;
		
		$pin = $_REQUEST['pin'];
		$pass_num = 0;
	
		$sql = "SELECT password FROM users WHERE id_user ='$login_id' AND password = '$pin' ";
		$get_security_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
		if(trim($pin) == trim($get_security_pass)){ $pass_num = 1; } 
	}
	{
		if($request_user_id > 0)
		{
			if($pass_num > 0)
			{
				if($_POST['submit'] == 'OTP Valid'){
					switch($_POST['wal_type']){
						case 'dep_wal' : $main_wallet = $main_wallet;
										  $to_field='activationw';$from_field='amount';
										  $cr_act = $acount_type[17];$cr_actd = $acount_type_desc[17];
										  $dr_act = $acount_type[29];$dr_actd = $acount_type_desc[29];
										  
										  $wt1 = 1;$wt2 = 2;break;
						case 'cash_wal' :  $main_wallet = $company_wallet;
										  $to_field='amount';$from_field='activationw';
										  $cr_act = $acount_type[18];$cr_actd = $acount_type_desc[18];
										  $dr_act = $acount_type[23];$dr_actd = $acount_type_desc[23];
										  
										  $wt1 = 2;$wt2 = 1;
										  break;
					}
					if($investment > 0 and $main_wallet >= $investment){
						if(!isset($_SESSION['session_user_wtdw'])){
							$net_amount = $investment;
							$_SESSION['session_user_wtdw'] = 1;
							
							if($_POST['wal_type'] == 'dep_wal'){
								$wal_type = 'Commission Wallet ';
							}
							if($_POST['wal_type'] == 'cash_wal'){
								$wal_type = 'E-Wallet';
							}
							$si = $net_amount;
							query_execute_sqli("update wallet set $from_field = $from_field - '$si' , date = '$systems_date' where id = '$login_id' ");
							insert_wallet_account($login_id , $login_id , $si , $systems_date_time , $dr_act ,$dr_actd, $mode=2 , get_user_allwallet($login_id,$from_field),$wallet_type[$wt1],$remarks = "Amount Transfer From ".$wal_type);
							query_execute_sqli("update wallet set $to_field = $to_field + '$si' , date = '$systems_date' where id = '$login_id' ");
							insert_wallet_account($login_id , $login_id , $si , $systems_date_time , $cr_act ,$cr_actd, $mode=1 , get_user_allwallet($login_id,$to_field),$wallet_type[$wt2],$remarks = "Amount Transfer From ".$wal_type);
							unset($_SESSION['WTDW_OTP']);
							
							echo "<script type=\"text/javascript\">";
							echo "alert('Fund Transfer To Wallet Successfully Completed !! ');window.location = \"index.php?page=wallet_transfer_dw\"";
							echo "</script>";
						}
						else{ }
					}
					else{ echo "<B class='text-danger'>Error : Please Enter Correct Transfer Amount !!</B>"; }
				}
				if($_POST['submit'] == 'Transfer'){
				    unset($_SESSION['WTDW_OTP']);
					if(!isset($_SESSION['WTDW_OTP'])){
						$_SESSION['WTDW_OTP'] = $rand = rand(1000,9999);
						if(strtoupper($soft_chk) == "LIVE"){
							//new registration message
							include("email_letter/wallet_trans_com_to_ewallet.php");
							$to = get_user_email($login_id);
							$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $db_msg);	
							$phone = get_user_phone($login_id);
							$message_login = "Your Transfer to Wallet OTP is ".$rand." https://www.unicgrow.com";
							send_sms($phone,$message_login);
							//End email message
						}
					}
					 //$_SESSION['WTDW_OTP'];
				?>
					<div class="form-group" style="padding:20px;">
						<form method="post" action="" enctype="multipart/form-data">
							<input type="hidden" name="trans_amt" value="<?=$_SESSION['investment']?>" />
							<input type="hidden" name="wal_type" value="<?=$_POST['wal_type']?>" />
							<div class="form-group">
								<label class="text-success">OTP Sent On Your Registered Mobile...</label>
							</div>
							<div class="form-group">
								<div class="pull-left"><label>Enter OTP</label></div>
								<div class="pull-left">&nbsp;</div>
								<div class="pull-left">&nbsp;</div>
								<div class="pull-left">&nbsp;</div>
								<div class="pull-left"><input type="text" name="valid_otp" value="<?=$_POST['valid_otp']?>" class="form-control" /></div>
								<div class="pull-left">&nbsp;</div>
								<div class="pull-left">&nbsp;</div>
								<div class="pull-left">&nbsp;</div>
							</div>
							<div class="form-group" id="confirm_btn">
								<input type="submit" name="submit" value="OTP Valid" class="btn btn-info" />
							</div>
						</form>
					</div>
				<?php
				}
		
			}
			else{ echo "<B class='text-danger'>Error : Please Enter Correct Security Password!!</B>"; }	
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested Member Name!!</B>"; }
	}	
}
else
{ 
	unset($_SESSION['session_user_wtdw'],$_SESSION['succ_msg'],$_SESSION['investment'],$_SESSION['WTDW_OTP']);
	?>
	<form name="invest" method="post" action="index.php?page=wallet_transfer_dw">
		<table class="table table-bordered table-hover">
			<tr> 
				<!--<th>MAIN WALLET</th>
				<th>&#36;<?=$main_wallet?></th>-->
				<th>E-WALLET</th>
				<th>&#36;<?=$company_wallet?></th>       
			</tr>
			<tr> 
				<th>COMMISSION WALLET</th>
				<th>&#36;<?=$main_wallet?></th>
			</tr>
			<tr>
				<th> From Wallet</th>
				<td>
					<!--<input type="radio" name="wal_type"  value="cash_wal" checked="checked" /> MAIN WALLET-->
					<input type="radio" name="wal_type"  value="dep_wal" checked="checked" /> COMMISSION WALLET
				</td>
			</tr>
			
			<tr> 
				<th>Transfer Amount</th>
				<td> 
				<input type="text" name="trans_amt" class="form-control"  value="<?=$main_wallet?>" required />
				</td>      
			</tr> 
			  
			<tr>      
				<th>Password</th>  
				<td><input type="password"  name="pin" class="form-control" autocomplete="off" required /></td>    
			</tr>    
			<tr>     
				<td colspan="2" class="text-center">    
					<input type="submit" name="submit" value="Transfer" class="btn btn-info" />    
				</td>     
			</tr>     
		</table>
	</form>
	<?php
}
?>
