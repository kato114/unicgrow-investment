<?php
session_start();
if(isset($_REQUEST['page']))
{
	$page = $_REQUEST['page'];
}
else
{
	$page = '';
}
?>
<header class="header1">
	<div class="container">
		<div class="row">
			<!-- User Sign In/Sign Up Starts -->
			<div class="col-md-12 col-lg-12">
				<ul class="unstyled user1">
				 	<i class="fa fa-clock-o text-theme-colored1"></i><li class="m-0 timezone" id="theTime"> </li>  
					<li class="sign-up" id="google_translate_element">
						<script type="text/javascript">
					function googleTranslateElementInit() {
					  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
					}
					</script>
						<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
					</li>
				</ul>
			</div>
			<!-- User Sign In/Sign Up Ends -->
		</div>
	</div>
</header>

<header class="header">
	<div class="container">
		<div class="row">
			<!-- Logo Starts -->
			<div class="main-logo col-xs-12 col-md-3 col-md-2 col-lg-2 hidden-xs">
				<a href="index.php">
					<img id="logo" class="img-responsive" src="images/logo.png" alt="logo">
				</a>
			</div>
			<!-- Logo Ends -->
			<!-- Statistics Starts -->
			<div class="col-md-7 col-lg-7">
				<ul class="unstyled bitcoin-stats text-left">
					<li>
						<h6>Mail Us Today</h6><span><a href="mailto:support@unicgorw.com" style="color:#fd961a;">support@unicgrow.com</a></span></li>
					<li>
						<h6>Call us for more details</h6><span><a href="tel:+(012) 345 6789" style="color:#fd961a;"> +(45) 924 59 796</a></span></li>
					<li>
						<h6>Company Location</h6><span style="color:#fd961a;">Lundsgade 15, 1680 Copenhagen Denmark</span></li>
					<!--<li>
						<h6>2,231,775</h6><span>active traders</span></li>
					<li>
						<div class="btcwdgt-price" data-bw-theme="light" data-bw-cur="usd"></div>
						<span>Live Bitcoin price</span>
					</li>-->
				</ul>
			</div>
			<!-- Statistics Ends -->
			<!-- User Sign In/Sign Up Starts -->
			<div class="col-md-3 col-lg-3">
				<ul class="unstyled user">
					<li class="sign-in"> <a href="member/login.php" target="_blank"  class="btn btn-info"><i class="fa fa-user"></i> Login</a></li>
					<li class="sign-up"><a href="member/register.php" target="_blank"  class="btn btn-primary"><i class="fa fa-user-plus"></i> Register</a></li>
				</ul>
			</div>
			<!-- User Sign In/Sign Up Ends -->
		</div>
	</div>
	
	<!-- Navigation Menu Starts -->
	<nav class="site-navigation navigation" id="site-navigation">
		<div class="container">
			<div class="site-nav-inner">
				<!-- Logo For ONLY Mobile display Starts -->
				<a class="logo-mobile" href="index.php">
					<img id="logo-mobile" class="img-responsive" src="images/logo.png" alt="">
				</a>
				<!-- Logo For ONLY Mobile display Ends -->
				<!-- Toggle Icon for Mobile Starts -->
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- Toggle Icon for Mobile Ends -->
				<div class="collapse navbar-collapse navbar-responsive-collapse">
					<!-- Main Menu Starts -->
					<ul class="nav navbar-nav">
						<li class="<?php if($page == ''){echo 'active';}?>"><a href="index.php" class="<?php if($page == ''){echo 'active';}?>">Home</a></li>
						<li class="<?php if($page == 'about'){echo 'active';}?>"><a href="index.php?page=about">About Us</a></li>
						<li class="<?php if($page == 'invest'){echo 'active';}?>"><a href="index.php?page=invest">Investment</a></li>
						<li class="<?php if($page == 'contact'){echo 'active';}?>"><a href="index.php?page=contact">Support</a></li>
						<!--<li><a href="member/login.php" target="_blank">Login</a></li>
						<li><a href="member/register.php" target="_blank">Register</a></li>-->
					</ul>
					<!-- Main Menu Ends -->
				</div>
			</div>
		</div>
	</nav>
	<!-- Navigation Menu Ends -->
</header>
<script>
   var clockID;
var yourTimeZoneFrom = -4.00 //time zone value where you are at

var d = new Date();  
//get the timezone offset from local time in minutes
var tzDifference = yourTimeZoneFrom * 60 + d.getTimezoneOffset();
//convert the offset to milliseconds, add to targetTime, and make a new Date
var offset = tzDifference * 60 * 1000;

function UpdateClock() {
    var tDate = new Date(new Date().getTime()+offset);
    var in_hours = tDate.getHours()
    var in_minutes=tDate.getMinutes();
    var in_seconds= tDate.getSeconds();

    if(in_minutes < 10)
        in_minutes = '0'+in_minutes;
    if(in_seconds<10)   
        in_seconds = '0'+in_seconds;
    if(in_hours<10) 
        in_hours = '0'+in_hours;

   document.getElementById('theTime').innerHTML = "" 
                   + in_hours + ":" 
                   + in_minutes + ":" 
                   + in_seconds;

}
function StartClock() {
   clockID = setInterval(UpdateClock, 500);
}

function KillClock() {
  clearTimeout(clockID);
}
window.onload=function() {
  StartClock();
}
  </script>