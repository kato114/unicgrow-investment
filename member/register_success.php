<?php
ini_set("display_errors",'on');
session_start();
require_once("config.php");


$sql = "Select * from users order by id_user DESC limit 1";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query))
{
	$username = $row['username'];
	$password = $row['password'];
	$full_name = $row['f_name']." ".$row['l_name'];
	$spon_name = sponsor_name($row['real_parent']);
}

function sponsor_name($id)
{
	$quer = query_execute_sqli("SELECT username FROM users WHERE id_user = '$id' ");
	while($ro = mysqli_fetch_array($quer))
	{
		$sponsor  = $ro['username'];
		return $sponsor ;
	}	
}
?>
<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="UNICGROW">
<meta name="keywords" content="UNICGROW">
<meta name="author" content="UNICGROW">
<title>UNICGROW - Register Panel</title>
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

<?php //include "top1.php"; ?>

<div class="app-content content">
	<div class="content-wrapper">
		<div class="content-header row"></div>
		<div class="content-body">
			<section class="flexbox-container">
				<div class="col-12 d-flex align-items-center justify-content-center">
					<div class="col-md-5 col-10 box-shadow-2 p-0">
						<div class="card border-grey border-lighten-3 m-0">
							<div class="card-header border-0 pb-0">
								<div class="card-title text-center">
									<img src="assets/images/logo.png" alt="branding logo">
								</div>
								<h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
									<span>Registration Success Panel</span>
								</h6>
								<?PHP 
								if($_REQUEST['err']== 1)
								{ echo "<B class='text-success'>UserName or Password is Incorrect !!</B>"; }
								echo $error_code; ?>
							</div>
							
							<div class="card-content">
								<div class="card-body">
									<div class="alert alert-success">
										Congratulation Your Registration is Successfully Completed !!			
									</div>
										<div class="alert alert-info">
										Login Details send your register E-mail  !!			
									</div>
									<!--<div class="alert alert-danger">
										Your Username &nbsp; :  &nbsp;<?=$username;?><br />
										Your Password &nbsp;&nbsp; :  &nbsp; ......<?php //$password;?><br />
										Your Sponsor &nbsp; &nbsp;&nbsp; &nbsp;:  &nbsp;<?=$spon_name;?>		
									</div>-->	
									
								</div>
							</div>
							<div class="card-footer border-0">
								<div class="row">
									<div class="col-12 col-sm-5 col-md-5">
										<a href="register.php" class="btn btn-warning">
											<i class="ft-user"></i> For New Register
										</a>
									</div>
									<div class="col-12 col-sm-3 col-md-3">
										<a href="login_check.php?username=<?=$username;?>&password=<?=$password;?>" class="btn btn-danger">
											<i class="ft-unlock"></i> Login
										</a>
									</div>
									<div class="col-12 col-sm-4 col-md-4 text-right">
										<a href="http://unicgrow.com" class="btn btn-primary">
											<i class="ft-home"></i> Home
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