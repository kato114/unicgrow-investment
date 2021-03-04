<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>UNICGROW | Admin Login</title>
<link rel="shortcut icon" href="images/logo.png" />
<link href="assets/css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">

<link href="assets/css/animate.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="black-bg">
<div class="middle-box text-center loginscreen  animated fadeInDown">
	<div>
		<div><h1 class="logo-name"><img src="images/logo1.png" /></h1></div>
		<h3>Welcome to Admin</h3>
		<!--<p>Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
			Continually expanded and constantly improved Inspinia Admin Them (IN+)
		</p>-->
		<p>Login in to Admin Panel.</p>
		<?php
		if($_REQUEST['err'] != ''){ ?> 
			<B class="text-danger">Please Enter Correct Username Or Password !!</B> <?php	
		} ?>
		<form action="login_check.php" method="post">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Username" required="" name="username">
			</div>
			<div class="form-group">
				<input type="password" class="form-control" placeholder="Password" required="" name="password">
			</div>
			<input name="submit" type="submit" value="Login" class="btn btn-primary block full-width m-bs" />

			<!--<a href="forget_password.php"><small>Forgot password?</small></a>-->
		</form>
		<p class="m-t"> 
			<small>Copyright &copy; <?=date('Y')?> UNICGROW</small> 
		</p>
	</div>
</div>

<!-- Mainly scripts -->
<script src="assets/js/jquery-2.1.1.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
