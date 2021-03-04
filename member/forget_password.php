<?php 
ini_set("display_errors",'on');
session_start();
require("config.php");
include("function/setting.php");
include("function/send_mail.php");

if(isset($_POST['submit']))
{
	$email = $_REQUEST['email'];
 	$sql = "select * from users where email = '$email' ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$username = $row['username'];
			$password = $row['password'];
			$to = $row['email'];
		}
		
		if(strtoupper($soft_chk) == "LIVE"){
			include "email_letter/forget_pss.php";
			$to = $email;
			 // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            // More headers
            $headers .= "From: <$from_email>" . "\r\n";

            mail($to,$title,$db_msg,$headers);
		}
		
		$_SESSION['msgs_error']="<B class='text-danger'>Please Check Your Email, We have sent your password !</B>";
		?> <script>window.location="forget_password.php";</script> <?php
	}
	else {
		$_SESSION['msgs_error'] = "<B class='text-danger'>Please Enter Correct E-mail !</B>"; ?>
		<script>window.location="forget_password.php";</script> <?php
	}
} ?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="UNICGROW">
<meta name="keywords" content="UNICGROW">
<meta name="author" content="UNICGROW">
<title>UNICGROW - Forget Password</title>
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
<!-- END Page Level CSS-->
<!-- BEGIN Custom CSS-->
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<!-- END Custom CSS-->
</head>
<body class="vertical-layout vertical-menu 1-column  bg-cyan bg-lighten-2 menu-expanded fixed-navbar"
data-open="click" data-menu="vertical-menu" data-col="1-column">
<?php // include "top1.php"; ?>
<div class="app-content content">
	<div class="content-wrapper">
		<div class="content-header row"></div>
		<div class="content-body">
			<section class="flexbox-container">
				<div class="col-12 d-flex align-items-center justify-content-center">
					<div class="col-md-4 col-10 box-shadow-2 p-0">
						<div class="card border-grey border-lighten-3 m-0">
							<div class="card-header border-0 pb-0">
								<div class="card-title text-center">
									<img src="assets/images/logo.png" alt="branding logo">
								</div>
								<h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
									<span>Forgot Your Password</span>
								</h6>
								<?PHP
								if(isset($_SESSION['msgs_error']) && $_SESSION['msgs_error'] != '')
								{ echo $_SESSION['msgs_error']; }
								?>
							</div>
							
							<div class="card-content">
								<div class="card-body">
									<form class="form-horizontal" action="forget_password.php" method="post">
										<fieldset class="form-group position-relative has-icon-left">
											<input type="text" class="form-control" placeholder="Enter Your E-mail" tabindex="1" name="email" />
											<div class="form-control-position"><i class="ft-user"></i></div>
											<div class="help-block font-small-3"></div>
										</fieldset>
										<button type="submit" class="btn btn-danger btn-block btn-lg" name="submit">
											<i class="ft-unlock"></i> Submit
										</button>
										
									</form>
								</div>
							</div>
							
							<div class="card-footer border-0">
								<p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1">
									<span> UNICGROW</span>
								</p>
								<div class="row">
									<div class="col-12 col-sm-6 col-md-6">
										<a href="register.php" class="btn btn-success btn-lg btn-block">
											<i class="ft-user"></i> Register
										</a>
									</div>
									<div class="col-12 col-sm-6 col-md-6">
										<a href="login.php" class="btn btn-warning btn-lg btn-block">
											<i class="ft-unlock"></i> Login
										</a>
									</div>
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
<!-- END MODERN JS-->
<!-- BEGIN PAGE LEVEL JS-->
<script src="assets/js/form-login-register.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL JS-->
</body>
</html>