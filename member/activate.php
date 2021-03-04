<?php
ini_set("display_errors",'on');
session_start();

/*if(isset($_REQUEST['reg_pos_user']))
{
	$reg_pos_user = $_SESSION['reg_pos_user'] = $_REQUEST['reg_pos_user'];
}

$reg_pos_user = $_SESSION['reg_pos_user'];*/

require_once("config.php");
include("function/setting.php");
include("function/functions.php");
require_once "function/formvalidator.php";
include("function/virtual_parent.php");
include("function/best_position.php");
include("function/send_mail.php");
include("function/country_list.php");
require_once("validation/validation.php"); 
require('create_withdrawal/coinpayments.inc.php');
$qu = query_execute_sqli("select * from income_process where id = 1 ");
while($r = mysqli_fetch_array($qu))
{
	$process_mode = $r['mode'];
}

if($process_mode == 12)
{ ?> <B style="color:#FF0000">Sorry Site is In Under Process !!!<br />Please Try Again Leter !</B> <?php }
else
{	?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="UNICGROW">
<meta name="keywords" content="UNICGROW">
<meta name="author" content="UNICGROW">
<title>UNICGROW - Register </title>
<link rel="apple-touch-icon" href="assets/images/logo.png">
<link rel="shortcut icon" type="image/x-icon" href="assets/images/logo.png">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
rel="stylesheet">
<link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
<!-- BEGIN VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="assets/css/vendors.min.css">
<link rel="stylesheet" type="text/css" href="assets/vendors/css/forms/icheck/icheck.css">
<link rel="stylesheet" type="text/css" href="assets/css/custom.css">
<!-- END VENDOR CSS-->
<!-- BEGIN MODERN CSS-->
<link rel="stylesheet" type="text/css" href="assets/css/app.min.css">
<!-- END MODERN CSS-->
<!-- BEGIN Page Level CSS-->
<link rel="stylesheet" type="text/css" href="assets/css/vertical-menu.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/palette-gradient.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/login-register.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/animate.min.css">
<!-- END Page Level CSS-->
<!-- BEGIN Custom CSS-->
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<!-- END Custom CSS-->
<script type="text/javascript" src="assets/js/jquery_1.js"></script>
</head>
<body class="vertical-layout vertical-menu 1-column  bg-cyan bg-lighten-2 menu-expanded fixed-navbar"
data-open="click" data-menu="vertical-menu" data-col="1-column">

<?php 
if(isset($_POST['submit']))
{
	$act_key = mysqli_real_escape_string($con,$_REQUEST['act_key']);
	$sql = "select * from temp_user where `key`='$act_key' and type='A'";	
	$query = query_execute_sqli($sql);
	if(mysqli_num_rows($query) > 0){
		while($row = mysqli_fetch_array($query)){
			$id_user = $row['id_user'];
			$real_p = $row['real_parent'];
			$f_name =$row['f_name']; 
			$l_name =$row['l_name']; 
			$email =$row['email'];
			$position =$row['position'];
			$username = $row['username'];
			$password =$row['password'];
			$date = $systems_date; //date('Y-m-d');
			$user_pin = $row['user_pin'];
			$bitcoin = $row['ac_no'];
			$act_mode = $row['mode'];
			$type = "B";
			$date = $systems_date; 
			$time = $systems_time;
		}
		$virtual_par = geting_best_position($real_p,$position);
		$users_parent_id = $virtual_par[0];
		$user_pos = $position;//$virtual_par[1];
		$sql = "update temp_user set type='B' where id_user='$id_user'";
		query_execute_sqli($sql);
		query_execute_sqli("INSERT INTO users (username,real_parent) VALUES ('$username' , '$real_p')");					
		$query = query_execute_sqli("SELECT id_user FROM users WHERE username = '$username' ");
		while($row = mysqli_fetch_array($query))
		{
			$insert_id = $row[0];
		}
		$user_id = $insert_id;
		
		$sql = "UPDATE users SET username = '$username' ,f_name='$f_name',l_name='$l_name',
		parent_id = '$users_parent_id' , position = '$user_pos' , activate_date = '$systems_date' ,
		email = '$email' , password = '$password' , user_pin = 
		'$user_pin' , date = '$systems_date' , type = '$type' , ac_no = '$bitcoin'  
		WHERE id_user = '$insert_id' ";
		query_execute_sqli($sql);
		insert_wallet();  // inserting in wallet
		//activate_income($insert_id,$systems_date);
		$ip_Add = $_SERVER['REMOTE_ADDR'];
		if($act_mode == 1){
			query_execute_sqli("Update wallet set amount=amount+'$app_reg_amount' where id='$insert_id'");
			query_execute_sqli("Update wallet set amount=amount+'$app_reg_amount' where id='$real_p'");
		}
		
		if(strtoupper($soft_chk) == "LIVE")
		{
			$from = "noreply@unicgrow.com";
			
			$message_login = "Your Login Username is ".$username." and Password is ".$password."  By https://www.unicgrow.com";
			send_sms($phone,$message_login);
			
			$virtual_parent_username = get_user_name($users_parent_id);
			$real_parent_username = get_user_name($real_p);
			
			//new registration message
			include("email_letter/welcome.php");
			$to = $email;
			include("function/full_message.php");
			$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,
			$title, $full_message);	
			//End email message
		}
				
		$_SESSION['register_success'] = "User Registration Successfully Completed !";
		?>
		<form id="loginForm" action="register_success.php" method="post"  class="form-signin" > 
			<input type="hidden" name="username" value="<?=$username?>" />
			<input type="hidden" name="password" value="<?=$password?>" />
		</form>
		<script>
			$(document).ready(function() {	
				document.forms['loginForm'].submit();	
			  });
		</script>
		<?php
	}
	else{
		$error_act_key = "<B style='color:#FF0000;'>Invalid Activation Key !!</B>"; 
	}
}
if($_REQUEST['act_key']){
	$sql = "select * from temp_user where `key`='".$_REQUEST['act_key']."' and type='A'";	
	$query = query_execute_sqli($sql);
	if(mysqli_num_rows($query) == 0){
		$error_act_key = "<B style='color:#FF0000;'>Invalid Activation Key !!</B>"; 
	}
}
?>

<?php include "top1.php"; ?>

<div class="app-content content">
	<div class="content-wrapper">
		<div class="content-header row"></div>
		<div class="content-body">
			<section class="flexbox-container">
				<div class="col-12 d-flex align-items-center justify-content-center">
					<div class="col-md-6 col-10 box-shadow-2 p-0">
						<div class="card border-grey border-lighten-3 m-0">
							<div class="card-header border-0 pb-0">
								<div class="card-title text-center">
									<img src="assets/images/logo-dark.png" alt="branding logo">
								</div>
								<h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
									<span>Activation Panel</span>
								</h6>
							</div>
							<div class="card-content">
								<div class="card-body">
									<div class="alert alert-success">
										<span>Activation OTP Is Mail To Your Registered Email !!</span><br>
										<span>Please Verify OTP For Complete Your Registration Process !!</span>
									</div>
									<form class="form-horizontal" action="activate.php" method="post">
									<div class="row">
										<div class="col-3 col-sm-3 col-md-3">
											<label><i class="ft-mail"></i>Activation Key</label>
										</div>
										<div class="col-9 col-sm-9 col-md-9">
											<fieldset class="form-group position-relative has-icon-left">
												<input type="text" name="act_key" value="<?=$_REQUEST['act_key']; ?>"  class="form-control"  placeholder="Activation Key" required />
												<?=$error_act_key; ?>
											</fieldset>
										</div>
										
									</div>
									<div class="row">
											<div class="col-12 col-sm-12 col-md-12">
												<button type="submit" class="btn btn-info btn-lg btn-block" name="submit">
													<i class="ft-unlock"></i> Activate
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>

  
<?php include "footer.php"; ?>
<!-- BEGIN VENDOR JS-->
<script src="assets/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN PAGE VENDOR JS-->
<script src="assets/js/jqBootstrapValidation.js" type="text/javascript"></script>
<script src="assets/js/icheck.min.js" type="text/javascript"></script>
<!-- END PAGE VENDOR JS-->
<!-- BEGIN MODERN JS-->
<script src="assets/js/app-menu.min.js" type="text/javascript"></script>
<script src="assets/js/app.min.js" type="text/javascript"></script>
<script src="assets/js/customizer.js" type="text/javascript"></script>
<script src="assets/js/components-modal.min.js" type="text/javascript"></script>
<!-- END MODERN JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="assets/js/form-login-register.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL JS-->
</body>
</html>
<?php
mysqli_close($con);
}
include 'data/box_terms_condition.php'; 
?>