<?php
//die("Please contact to customer care.");
ini_set("display_errors",'on');
session_start();
require_once("config.php");
require("web_security.php");
if(!empty($_POST))validate_all_post_from_input($_POST);
if(!empty($_GET))validate_all_post_from_input($_GET);
if(isset($_REQUEST['placeref'])){
	$_REQUEST['ref'] = $_REQUEST['placeref'];
}
$referral_u = $_REQUEST['ref'];

/*if(isset($_REQUEST['reg_pos_user']))
{
	$reg_pos_user = $_SESSION['reg_pos_user'] = $_REQUEST['reg_pos_user'];
}

$reg_pos_user = $_SESSION['reg_pos_user'];*/
$free_sponser = array(1,2);

include("function/setting.php");
include("function/functions.php");
require_once "function/formvalidator.php";
include("function/virtual_parent.php");
include("function/best_position.php");
include("function/send_mail.php");
include("function/country_list.php");
include("function/pair_point_calc.php");
require_once("validation/validation.php"); 
include("data/api.php");
$qu = query_execute_sqli("select * from income_process where id = 1 ");
while($r = mysqli_fetch_array($qu))
{
	$process_mode = $r['mode'];
}
$sql = "select * from users order by id_user asc limit 1";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query)){
	//$ref_user = $row['refrral_link'];
	$ref_user = $row['username'];
}
mysqli_free_result($query);
$ref_cnt = 0;
if($process_mode == 12)
{ ?> <B style="color:#FF0000">Sorry Site is In Under Process !!!<br />Please Try Again Leter !</B> <?php }
else
{
	if(isset($_REQUEST['ref'])){ 
		$ref = $_REQUEST['ref'];
		$position = trim(isset($_REQUEST['position']) ? $_REQUEST['position'] : (isset($_REQUEST['bp']) ? $_REQUEST['bp'] : 0));
		
		$chk_ref_user = query_execute_sqli("SELECT * FROM users WHERE refrral_link = '$ref'");
		$ref_cnt = mysqli_num_rows($chk_ref_user);
		
		if($ref_cnt > 0){ 
			while($row = mysqli_fetch_array($chk_ref_user)){
				$ref_user = $row['refrral_link'];
				$ref = $row['username'];
				$qu_str = "?".$_SERVER["QUERY_STRING"];
			}

			$ref_user = $ref; 
			$readonly = "readonly=''";
		}
		else{ $error_sponsor = "<B class='warning'>Incorrect Sponser Id !</B>"; }	
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
<script>$(document).ready(function() {	
	$("#sponsor_username").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_username = $(this).val();
		if(sponsor_username.length < 3){$("#user-result").html('');return;}
		
		if(sponsor_username.length >= 3){
		
			$("#user-result").html('Lodding...');
			$.post('check_username.php', {'sponsor_username':sponsor_username},function(data)
			{
			  $("#user-result").html(data);
			});
		}
	});	
});		
</script>     
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
					<div class="col-md-6 col-10 box-shadow-2 p-0">
						<div class="card border-grey border-lighten-3 m-0">
							<div class="card-header border-0 pb-0">
								<div class="card-title text-center">
									<img src="assets/images/logo.png" alt="branding logo img-responsive" />
								</div>
								<h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
									<span>Please Sign Up</span>
								</h6>
							</div>
							<div class="card-content">
								<div class="card-body">
<?php 
$process = 0;
if(isset($_POST['submit'])){
	$real_parent = $_REQUEST['real_perent_id'];
	$username = $_REQUEST['username'];
	$position = $_REQUEST['position'];
	$f_name = $_REQUEST['f_name'];
	$l_name = $_REQUEST['l_name']; 
	$email = $_REQUEST['email'];
	//$phone = preg_replace('/[^0-9]/', '', $_REQUEST['phone']);
	$phone = $_REQUEST['phone'];
	$username = preg_replace('/\s+/', '', $_REQUEST['username']);
	$password = preg_replace('/\s+/', '', $_REQUEST['password']); //mt_rand(100000, 999999);
	$date = $systems_date; //date('Y-m-d');
	$isd_code = $_REQUEST['isd_code'];
	$country = $_REQUEST['country'];
	$month =$_REQUEST['month'];
	$year = $_REQUEST['year'];
	$tr_pass = $_REQUEST['tr_pass'];
	$re_tr_pass = $_REQUEST['re_tr_pass'];
	$dob = $year.'-'.$month.'-'.$day;
	
	/*do{
		$username = 'CN'.abs(rand(10000000,99999999));
	}
	while(mysqli_num_rows(query_execute_sqli("SELECT * FROM users where username='$username'"))!=0);*/
	//$phone = preg_replace('/[^0-9]/', '', $phone); // Removes special chars.
	
	//$phone = $isd_code.$phone;
	/*$full_name = explode(" ", $name);
	$f_name = $full_name[0];
	$l_name = $full_name[1]." ".$full_name[2];*/
	
	$id_query = query_execute_sqli("SELECT * FROM users WHERE username = '$real_parent' ");
	$num = mysqli_num_rows($id_query);
	if($num == 0){ $error_sponsor = "<B class='warning'>Please enter correct Sponsor Id !</B>"; }
	else{
		while($row = mysqli_fetch_array($id_query)){
			$real_p = $real_parent_id = $_SESSION['rbhgrocery_real_parent_id'] = $row['id_user'];
			$power_leg = $row['power_leg'];
			$power_position = $row['position'];
		}
		mysqli_free_result($id_query);
		$paid_join = false;
		if(in_array($real_p,$free_sponser)){
			$paid_join = true;
		}
		else{
			$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$real_p' and mode = 1 and update_fees > 0 ";
			$query = query_execute_sqli($sql);
			$num = mysqli_num_rows($query);
			mysqli_free_result($query);
			if($num > 0)$paid_join = true;
		}
		$paid_join = true;
		if($paid_join){
			$position = $power_leg == NULL ? ($power_position == 0 ? 1 : 0) : $power_leg;
			$qu = query_execute_sqli("select * from users where phone_no = '$phone' ");
			$phone_chk = mysqli_num_rows($qu);
			
			if($phone_chk > 0){ $error_phone = "<B class='warning'>Phone No. already used !</B>"; }
			else{	
				$sql = "select * from users where username = '$username' ";
				$query_uc = query_execute_sqli($sql);
				$uc_num = mysqli_num_rows($query_uc);
				
				if($uc_num > 0){ $error_username = "<B class='warning'>Username Already Exists.</B>"; }
				else{
					$qur = query_execute_sqli("select * from users where email = '$email' ");
					$email_chk = mysqli_num_rows($qur);
					
					if($email_chk > 0){ $error_email = "<B class='warning'>E-mail already used !</B>"; }
					else{
						if(/*!validatePhone($_POST['phone']) and !filter_var($email, FILTER_VALIDATE_EMAIL)*/0){ 
							if(!validatePhone($_POST['phone'])):  
							$error_phone = "<B class='warning'>Invalid Phone No:</B>";
							endif;
							
							if(!filter_var($email, FILTER_VALIDATE_EMAIL)): 
							$error_email = "<B class='warning'>Invalid E-mail:</B>";  
							endif;
						}
						else{
							$type = "B";
							//$user_pin = mt_rand(100000, 999999);
							$time = $systems_time;//date("Y-m-d H:i:s");
							
							/*//$password = mt_rand(100000, 999999);
							do
							{
								//$username = "ALEX".mt_rand(100000, 999999);
								$chk = user_exist($username);
							
							}while($chk != 0); */
							$captcha = $_POST["captcha"];
							if(isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]){
								$process = 2;
								if(isset($_POST['email_otp']) and $_POST['email_otp'] == $_SESSION['rand_code']){
									$process = 1;
								}
								elseif(isset($_POST['email_otp'])){
									$error_email = "<B class='warning'>Please Enter Correct OTP</B>";
									$process = 2;
								}
								if($process == 2){
									if(!isset($_SESSION['rand_code'])){
										$_SESSION['rand_code'] = $rand_no = rand(10000 , 99999);
										if(strtoupper($soft_chk) == "LIVE"){
											include "email_letter/email_verification.php";
											$to = $email;
											//$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $db_msg);	
											
                                            // Always set content-type when sending HTML email
                                            $headers = "MIME-Version: 1.0" . "\r\n";
                                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                            // More headers
                                            $headers .= "From: <$from_email>" . "\r\n";

                                            mail($to,$title,$db_msg,$headers);
										}
									}
									 $_SESSION['rand_code'];
									?>
								
									<div class="alert alert-success text-bold-700">OTP Send Your Register E-mail id !</div>
									<?php
									if($error_email != ''){ 
										echo $error_email;
									} ?>
									<form action="register.php" method="post" enctype="multipart/form-data">
										<div class="row">
											<div class="col-md-9">
												<fieldset class="form-group position-relative has-icon-left">
													<input type="text" name="email_otp" class="form-control"  placeholder="Enter OTP here" required />
													<div class="form-control-position">
														<i class="la la-key"></i>
													</div>
												</fieldset>
											</div>
											<div class="col-md-3">
												<button type="submit" name="submit" class="btn btn-info">
													Verify
												</button>
											</div>
										</div> <?php
										$post_input = $_POST;
										$post_key = array_keys($_POST);
										unset($post_input['email_otp']);
										for($p = 0; $p <= count($post_input); $p++){
											if( $post_key[$p] == 'email_otp' or $post_key[$p] == '' ){ continue; } ?>
											<input type="hidden" name="<?=$key=$post_key[$p]?>" value="<?=$post_input[$key]?>">
											<?php
										} ?>
									</form> <?php
								}
								if($process == 1){
									$place_prc = false;
									if(!isset($_POST['place']) and !isset($_POST['place_pos'])){
										$virtual_par = geting_best_position($real_p,$position);
										$users_parent_id = $virtual_par[0];
										$user_pos = $position;//$virtual_par[1];
									}
									elseif(isset($_POST['place']) and isset($_POST['place_pos'])){
										$user_pos = $_REQUEST['place_pos'] == 'left' ? 0 : 1;
										$users_parent_id = get_new_user_id($_POST['place']);
										$place_prc = true;
									}
									
									query_execute_sqli("INSERT INTO users (username,real_parent) VALUES ('$username' , '$real_p')");
									
									$query = query_execute_sqli("SELECT id_user FROM users WHERE username = '$username' ");
									while($row = mysqli_fetch_array($query)){
										$insert_id = $row[0];
									}
									$user_id = $insert_id;
									
									do{
										$reqid = abs(rand(1000000000,9999999999)).abs(rand(1000000000,9999999999));
									}
									while(mysqli_num_rows(query_execute_sqli("SELECT id FROM users where bank='$reqid'"))!=0);	
									do{
										$refrral_l = generateRandomString(16);
										$sql = "SELECT id_user FROM users WHERE refrral_link = '$refrral_l' ";
										$query = query_execute_sqli($sql);
										$num = mysqli_num_rows($query);
										mysqli_free_result($query);
									}
									while($num > 0);
									
									set_left_right_network($insert_id,$user_pos,$users_parent_id);
									$sql = "UPDATE users SET f_name='$f_name',l_name='$l_name',
									parent_id = '$users_parent_id' , position = '$user_pos' , activate_date = '$date' ,
									email = '$email' , phone_no = '$phone' , password = '$password' , user_pin = 
									'$tr_pass' , date = '$date' , type = '$type' , ac_no = '$bitcoin',country='$country',refrral_link='$refrral_l'  
									WHERE id_user = '$insert_id' ";
									query_execute_sqli($sql);
									if($place_prc){
										$sql = "UPDATE users SET matching_qualification = $users_parent_id  
												WHERE id_user = '$insert_id' ";
										query_execute_sqli($sql);
									}
									insert_wallet();  // inserting in wallet
									set_user_level($insert_id);
									//activate_income($insert_id,$systems_date);
									
									$ip_Add = $_SERVER['REMOTE_ADDR'];
									
									query_execute_sqli("insert into login_logs (user_id , ip , date) values ('$insert_id' , '$ip_Add' , '$date_time') ");	
									
									
									
									if(strtoupper($soft_chk) == "LIVE"){
    									    $virtual_parent_username = get_user_name($users_parent_id);
    										$real_parent_username = get_user_name($real_p);
											include("email_letter/welcome.php");
											$to = $email;
											//$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $db_msg);	
											
                                            // Always set content-type when sending HTML email
                                            $headers = "MIME-Version: 1.0" . "\r\n";
                                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                                            // More headers
                                            $headers .= "From: <$from_email>" . "\r\n";

                                            mail($to,$title,$db_msg,$headers);
										}
								
									//$_SESSION['rand_code'] = "";
									//unset($_SESSION['rand_code']);		
									$_SESSION['register_success'] = "User Registration Successfully Completed !";
									include "free_up_memory.php";
									?>
									<form id="loginForm" action="register_success.php" method="post" > 
										<input type="hidden" name="username" value="<?=$username?>" />
										<input type="hidden" name="password" value="<?=$password?>" />
									</form>
									<script>
										document.getElementById("loginForm").submit();
										$(document).ready(function() {	
											document.forms['loginForm'].submit();	
										});
									</script> <?php
								}
							}
							else{ $error_code = "<B class='warning'>Please Enter correct Code !!</B><br>"; }	
						}
					}		
				}
			}	
		}
		else{
			$error_sponsor = "<B class='warning'>Sponsor Id Is Not Paid Yet !</B>"; 
		}
	}	
}
if($process == 0){
$all_country = all_country_detail();
$_SESSION['rand_code'] = "";
unset($_SESSION['rand_code']);
?>


	<form class="form-horizontal" action="register.php<?=$qu_str?>" method="post">
		<?php 
		if($_REQUEST['reg_pos_user']){
			if($_REQUEST['reg_pos_user'] == 0){
				$pos_chk_l = "checked='checked'";
			}
			else{ $pos_chk_r = "checked='checked'"; }
		}
		else{
			$pos_chk_l = "checked='checked'";
		}
		if(isset($_REQUEST['ref']))
		{ 
			$referral_username = get_full_name_by_username($referral_u);
			if($referral_username == '0')
			print "<B style='text-danger'>Invalid Referral !</B>";
		} ?>
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12">
				<?php
				if($ref_cnt > 0)
				{ ?>
				<fieldset class="form-group position-relative has-icon-left">
					<input type="hidden" name="real_perent_id" value="<?=$ref_user?>"/>
					<lable class="form-control">Sponser Id : <span id="user-result"><?=$ref_user?></span></lable>
					<div class="form-control-position">
						<i class="ft-users"></i>
					</div>
					
				</fieldset>
				<?php 
				}
				else{
				?>
				<input type="hidden" name="real_perent_id" value="<?=$ref_user?>"/>
				<?php
				} ?>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="text" name="username" value="<?=$_REQUEST['username']; ?>"  class="form-control"  placeholder="Enter Your Username" required />
					<div class="form-control-position">
						<i class="ft-user"></i>
					</div>
					<?=$error_username; ?>
				</fieldset>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="text" name="f_name" value="<?=$_REQUEST['f_name']; ?>"  class="form-control"  placeholder="Enter Your First Name" required />
					<div class="form-control-position">
						<i class="ft-user"></i>
					</div>
				</fieldset>
			</div>
			<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="text" name="l_name" value="<?=$_REQUEST['l_name']; ?>"  class="form-control"  placeholder="Enter Your Last Name" required />
					<div class="form-control-position">
						<i class="ft-user"></i>
					</div>
				</fieldset>
			</div>
		</div>
		<div class="row">
			<!--<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="text" name="phone" value="<?=$_REQUEST['phone']?>" class="form-control"  placeholder="Your Phone" required />
					<div class="form-control-position">
						<i class="ft-phone"></i>
					</div>
					<?=$error_username; ?>
				</fieldset>
			</div>-->
			<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<!--<input type="text" name="phone" class="form-control" required  pattern="[0-9]{10}" title="10 Digit Phone No. Required" value="<?=$_REQUEST['phone']?>" placeholder="Enter Your Phone No." onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')" />
					-->
					<input id="phone" name="phone" required type="tel" value="<?=$_REQUEST['phone']?>" class="form-control" onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')">
					<!--<div class="form-control-position">
						<i class="ft-phone"></i>
					</div>-->
					<?=$error_phone; ?>
				</fieldset>
			</div>
			<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="example@gmail.com" name="email" value="<?=$_POST['email']?>" class="form-control" required placeholder="Your E-mail Here"  />
					<div class="form-control-position">
						<i class="ft-mail"></i>
					</div>
					<?=$error_email; ?>
				</fieldset>
			</div>
		</div>
		
		<div class="row">
			<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="password" name="password" value="<?=$_POST['password']?>" class="form-control" placeholder="Enter Password" required />
					<div class="form-control-position">
						<i class="ft-lock"></i>
					</div>
				</fieldset>
			</div>
			<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="password" name="re_password" class="form-control" placeholder="Enter Confirm Password" required />
					<div class="form-control-position">
						<i class="ft-lock"></i>
					</div>
				</fieldset>
			</div>
		</div>
		
		<link rel="stylesheet" href="assets/css/intlTelInput.css">
		 <script src="assets/js/intlTelInput.js"></script>
		  <script>
			var input = document.querySelector("#phone");
			window.intlTelInput(input, {
			  // allowDropdown: false,
			  // autoHideDialCode: false,
			  // autoPlaceholder: "off",
			  // dropdownContainer: document.body,
			  // excludeCountries: ["us"],
			  // formatOnDisplay: false,
			  // geoIpLookup: function(callback) {
			  //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
			  //     var countryCode = (resp && resp.country) ? resp.country : "";
			  //     callback(countryCode);
			  //   });
			  // },
			  // hiddenInput: "full_number",
			  // initialCountry: "auto",
			  // localizedCountries: { 'de': 'Deutschland' },
			  // nationalMode: false,
			  // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
			  // placeholderNumberType: "MOBILE",
			  // preferredCountries: ['cn', 'jp'],
			  // separateDialCode: true,
			  utilsScript: "assets/js/utils.js",
			});
		  </script>
		<!--<div class="row">
			<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="password" name="tr_pass" class="form-control" value="<?=$_REQUEST['tr_pass']?>"onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')" maxlength="6" pattern="[0-9]{6,}" placeholder="Enter Transaction Password" required />
					<div class="form-control-position">
						<i class="la la-key"></i>
					</div>
				</fieldset>
			</div>
			<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="password" name="re_tr_pass" class="form-control" value="<?=$re_tr_pass; ?>" onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')" maxlength="6" pattern="[0-9]{6,}" placeholder="Confirm Transaction Password" required />
					<div class="form-control-position">
						<i class="la la-key"></i>
					</div>
				</fieldset>
			</div>
		</div>-->
		<div class="row">
			<div class="col-12 col-sm-6 col-md-6">
				<fieldset class="form-group position-relative has-icon-left">
					<input type="password" name="captcha" class="form-control" placeholder="Enter the code shown" required />
					<div class="form-control-position">
						<i class="ft-lock"></i>
					</div>
					<?=$error_code?>
				</fieldset>
			</div>
			<div class="col-12 col-sm-2 col-md-2"><img src="captcha.php" /></div>
		</div>
		<div class="row mb-1">
			<div class="col-12 col-sm-12 col-md-12">
				<fieldset>
					<input type="checkbox" required />
					<label for="remember-me"> I agree with Terms and conditions</label>
				</fieldset>
			</div>
			<!--<div class="col-8 col-sm-9 col-md-9">
				<p class="font-small-3">
					By clicking Register, you agree to the 
					<a href="#dialog-approve-terms_condition" data-toggle="modal" data-target="#dialog-approve-terms_condition">
						Terms &amp; Conditions
					</a>
				</p>
			</div>-->
		</div>
		<div class="row">
			<div class="col-12 col-sm-6 col-md-6">
				<button type="submit" class="btn btn-info btn-lg btn-block" name="submit">
					<i class="ft-user"></i> Register
				</button>
			</div>
			<div class="col-12 col-sm-6 col-md-6">
				<a href="login.php" class="btn btn-danger btn-lg btn-block">
					<i class="ft-unlock"></i> Login
				</a>
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

  
<?php
} 
include "footer.php"; ?>
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
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
include "free_up_memory.php";
include 'data/box_terms_condition.php'; 
?>