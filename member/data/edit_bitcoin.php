<?php
session_start();
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");

$login_id = $_SESSION['mlmproject_user_id'];


if(isset($_POST['submit_otp'])){
	$bitcoin =$_REQUEST['bitcoin'];
	$eth_ac = $_REQUEST['eth_ac'];
	$bank_ac = $_REQUEST['bank_ac'];
	$user_pin = $_REQUEST['otp'];
	
	$pass_num = 0;
	$sql = "SELECT password FROM users WHERE id_user ='$login_id' AND password = '$user_pin' ";
	$sec_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
	if(trim($user_pin) == trim($sec_pass)){ $pass_num = 1; } 
	
	//if($_SESSION['otp']== $otp){
	if($pass_num > 0){
		if(btc_address_exist($bitcoin) > 1){
			$error_bit_code = "<B class='color-red'>Bitcoin Address Already Exists !!</B>";
		}
		else{
			$url = "https://blockchain.info/address/$bitcoin?format=json";  // live RUN
			$string = file_get_contents($url);
			$array = array();
			//$array = json_decode($string,true);
			if(is_array($array)){
				$nominee =$_REQUEST['nominee'];
				$n_relation =$_REQUEST['n_relation'];
				
				$dob = date('Y-m-d', strtotime($dob));
					
				$sql = "UPDATE users SET btc_ac = '$bitcoin', etc_ac = '$eth_ac', bank_ac = '$bank_ac'
				WHERE id_user = '$login_id'";
				query_execute_sqli($sql);
				if(strtoupper($soft_chk) == "LIVE"){
        			include "email_letter/ac_details.php";
        			$to = get_user_email($login_id);
        			 // Always set content-type when sending HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    // More headers
                    $headers .= "From: <$from_email>" . "\r\n";
        
                    mail($to,$title,$db_msg,$headers);
        		}
				echo "<B class='text-success'>Account details Successfully Updated</B>";
				unset($_SESSION['otp']);
			}
			else{ $error_bit_code = "<B class='text-danger'>Please Enter correct Bitcoin Address !!</B>"; }
		}
	}	
	else{ echo "<B class='text-danger'>Please Enter Correct Login Password !!</B>"; }
}
if(isset($_POST['chng_btc'])){
	$bitcoin =$_REQUEST['bitcoin'];
	$eth_ac =$_REQUEST['eth_ac'];
	$bank_ac = $_REQUEST['bank_ac'];
	
	/*if(!isset($_SESSION['otp'])){
		$_SESSION['otp'] = $otp = rand(1111,9999);
		if(strtoupper($soft_chk) == "LIVE"){
			include("email_letter/btc_otp");
			$to = get_user_email($login_id);
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser,$SmtpPass,$from_email,$to,$title,$db_msg);	
		}
	}
	echo $_SESSION['otp'];*/
	?>
	<!--<div class="alert alert-danger"><B>OTP Send on your email Please check your email id ! </B></div>-->
	<form action="" method="post">
		<input type="hidden" name="bitcoin" value="<?=$bitcoin;?>" />
		<input type="hidden" name="eth_ac" value="<?=$eth_ac;?>" />
		<input type="hidden" name="bank_ac" value="<?=$bank_ac;?>" />
		<table class="table table-bordered table-hover">
			<thead><tr><th colspan="3"><h4>Login Password :</h4> </th></tr></thead>
			<tr>
				<th>Enter Login Password</th>
				<td>
					<input type="text" name="otp" class="form-control" />
				</td>
				<td><input type="submit" name="submit_otp" value="Confirm" class="btn btn-primary btn-sm" ></td>
			</tr>
		</table>	
	</form>	
	<?php
	
}
else{
	$query = query_execute_sqli("select * from users where id_user = '$login_id'");
	while($row = mysqli_fetch_array($query)){
		$bitcoin = $row['btc_ac'];
		$eth_ac = $row['etc_ac'];
		$bank_ac = $row['bank_ac'];
	} ?>
	<?=$error_bit_code;?>
	<form action="" method="post">
	 <div class="col-md-4">BTC Address</div>
	        <div class="col-md-8"><input type="text" name="bitcoin" value="<?=$bitcoin;?>" class="form-control" /></div>
	    <div class="col-md-12">&nbsp;</div>
	    <div class="col-md-4">ETH Address</div>
	     <div class="col-md-8"><input type="text" name="eth_ac" value="<?=$eth_ac;?>" class="form-control" /></div>
	    <div class="col-md-12">&nbsp;</div>
	    <div class="col-md-12 text-center"><input type="submit" name="chng_btc" value="Save" class="btn btn-primary" /></div>
	    <div class="col-md-12">&nbsp;</div>
    </form>	
	<?php
}
?>	
