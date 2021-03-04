<?php 
ini_set("display_errors","on");
session_start();
include("config.php");
require("function/functions.php");

if($_SESSION['mlmproject_user_login'] != 1){
	include("login.php");
	die;
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
<title>UNICGROW - Dashboard</title>
<link rel="apple-touch-icon" href="assets/images/logo.png">
<link rel="shortcut icon" type="image/x-icon" href="assets/images/logo.png">
<!--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700"
rel="stylesheet">-->
<link href="assets/css/line-awesome.min.css" rel="stylesheet">
<link href="assets/css/css.css" rel="stylesheet">
<link href="assets/css/line-awesome.css" rel="stylesheet">
<!-- BEGIN VENDOR CSS-->
<link rel="stylesheet" type="text/css" href="assets/css/vendors.css">
<!-- END VENDOR CSS-->
<!-- BEGIN MODERN CSS-->
<link rel="stylesheet" type="text/css" href="assets/css/app.css">
<!-- END MODERN CSS-->

<link rel="stylesheet" type="text/css" href="assets/css/jquery-jvectormap-2.0.3.css">
<!--<link rel="stylesheet" type="text/css" href="assets/css/charts/morris.css">-->

<!-- BEGIN Page Level CSS-->
<link rel="stylesheet" type="text/css" href="assets/css/vertical-menu.css">
<link rel="stylesheet" type="text/css" href="assets/css/palette-gradient.css">
<link rel="stylesheet" type="text/css" href="assets/css/animate.min.css">
<link rel="stylesheet" type="text/css" href="assets/css/cryptocoins.css">
<!-- END Page Level CSS-->
<!-- BEGIN Custom CSS-->
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<!-- END Custom CSS-->
<style type="text/css">/* Chart.js */
@-webkit-keyframes chartjs-render-animation{from{opacity:0.99}
to{opacity:1}}
@keyframes chartjs-render-animation{from{opacity:0.99}
to{opacity:1}}
.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}</style>
<script src="assets/js/jquery_1.js" type="text/javascript"></script>
<script data-require-id="echarts/chart/line" src="assets/vendors/js/charts/echarts/chart/line.js" async="">
<script async="" src="assets/js/line.js" data-require-id="echarts/chart/line"></script>
<script async="" src="assets/js/scatter.js" data-require-id="echarts/chart/scatter"></script>
<script async="" src="assets/js/k.js" data-require-id="echarts/chart/k"></script>
</head>
<body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar"
data-open="click" data-menu="vertical-menu" data-col="2-columns">
<!-- fixed-top-->
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5f5681eaf0e7167d000e2eda/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
<?php 
include "top.php"; 
include "left.php";
include "middle.php";
//include "assets/template_settings.php";
include "footer.php"; 
?>
<!-- BEGIN VENDOR JS-->
<script src="assets/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!--<script src="assets/js/chart.js" type="text/javascript"></script>-->
<!-- BEGIN PAGE VENDOR JS-->
<!-- <script src="assets/js/chart.min.js" type="text/javascript"></script>
<script src="assets/js/echarts.js" type="text/javascript"></script>-->

<!-- END PAGE VENDOR JS-->
<!-- BEGIN MODERN JS-->
<script src="assets/js/app-menu.min.js" type="text/javascript"></script>
<script src="assets/js/app-menu.js" type="text/javascript"></script>
<script src="assets/js/app.js" type="text/javascript"></script>
<!--<script src="assets/js/app.min.js" type="text/javascript"></script>-->
<script src="assets/js/customizer.js" type="text/javascript"></script>
<script src="assets/js/components-modal.min.js" type="text/javascript"></script>

<!--<script type="text/javascript">
$(window).on("load",function(){var o=$("#line-chart");new Chart(o,{type:"line",options:{responsive:!0,maintainAspectRatio:!1,legend:{position:"bottom"},hover:{mode:"label"},scales:{xAxes:[{display:!0,gridLines:{color:"#f3f3f3",drawTicks:!1},scaleLabel:{display:!0,labelString:"Month"}}],yAxes:[{display:!0,gridLines:{color:"#f3f3f3",drawTicks:!1},scaleLabel:{display:!0,labelString:"Value"}}]},title:{display:!0,text:"Monthly Progress"}},data:{labels:["January","February","March","April","May","June","July"],datasets:[{label:"Total Income",data:[<?=get_total_month_business(1)?>,<?=get_total_month_business(1)?>,<?=get_total_month_business(3)?>,<?=get_total_month_business(4)?>,<?=get_total_month_business(5)?>,<?=get_total_month_business(6)?>,<?=get_total_month_business(7)?>],fill:!1,borderDash:[5,5],borderColor:"#9C27B0",pointBorderColor:"#9C27B0",pointBackgroundColor:"#FFF",pointBorderWidth:2,pointHoverBorderWidth:2,pointRadius:4},{label:"Total Wallet",data:[<?=get_total_month_wal_bal(1)?>,<?=get_total_month_wal_bal(2)?>,<?=get_total_month_wal_bal(3)?>,<?=get_total_month_wal_bal(4)?>,<?=get_total_month_wal_bal(5)?>,<?=get_total_month_wal_bal(6)?>,<?=get_total_month_wal_bal(7)?>],fill:!1,borderDash:[5,5],borderColor:"#00A5A8",pointBorderColor:"#00A5A8",pointBackgroundColor:"#FFF",pointBorderWidth:2,pointHoverBorderWidth:2,pointRadius:4},{label:"Total Investment",data:[<?=get_total_month_investment(1)?>,<?=get_total_month_investment(2)?>,<?=get_total_month_investment(3)?>,<?=get_total_month_investment(4)?>,<?=get_total_month_investment(5)?>,<?=get_total_month_investment(6)?>,<?=get_total_month_investment(7)?>],lineTension:0,fill:!1,borderColor:"#FF7D4D",pointBorderColor:"#FF7D4D",pointBackgroundColor:"#FFF",pointBorderWidth:2,pointHoverBorderWidth:2,pointRadius:4}]}})});	
</script>-->
<!-- END MODERN JS-->
<?php
if($val != 'activation_company_wallet'){
	$sql = "SELECT * FROM request_crown_wallet WHERE user_id = '".$_SESSION['mlmproject_user_id']."' AND status = 0 and ac_type=1 and DATE_ADD(date, INTERVAL 180 DAY) >= '$systems_date_time'   ORDER BY id ASC LIMIT 1";
		$qwtt = query_execute_sqli($sql);
		$num_inf = mysqli_num_rows($qwtt);
		if($num_inf > 0)
		{ 
			while($roe = mysqli_fetch_array($qwtt)){
				$table_id = $roe['id'];
			}?>
		<script>
		 $(document ).ready(function() {
			//setInterval(function(){ check_btc_payment }, 10000);
			var myVar = setInterval(check_btc_payment, 10000);
			function check_btc_payment(){
				$.ajax({
					url: "data/verify.php",
					type: "POST",
					cache: false,
					data: "&pid=<?=$table_id?>",
					success : function(html){
					   // alert(html);
						
						obj = JSON.parse(html);
						if(obj.result > 0){
							alert(obj.info);
							clearInterval(myVar);
							window.location = "index.php?page=deposit_history";
						}
						
					}
				});
			}
		});
		</script>
	<?php
		}
	$sql = "SELECT * FROM request_crown_wallet WHERE user_id = '".$_SESSION['mlmproject_user_id']."' AND status = 0 and ac_type=3 and DATE_ADD(date, INTERVAL 180 DAY) >= '$systems_date_time'   ORDER BY id ASC LIMIT 1";
		$qwtt = query_execute_sqli($sql);
		$num_inf = mysqli_num_rows($qwtt);
		if($num_inf > 0)
		{ 
			while($roe = mysqli_fetch_array($qwtt)){
				$table_id = $roe['id'];
			}?>
		<script>
		 $(document ).ready(function() {
			//setInterval(function(){ check_btc_payment }, 10000);
			var myVar = setInterval(check_eth_payment, 10000);
			function check_eth_payment(){
				$.ajax({
					url: "data/verify.php",
					type: "POST",
					cache: false,
					data: "&pid=<?=$table_id?>",
					success : function(html){
						
						obj = JSON.parse(html);
						if(obj.result > 0){
							alert(obj.info);
							clearInterval(myVar);
							window.location = "index.php?page=deposit_history";
						}
						
					}
				});
			}
		});
		</script>
	<?php
		}
}
?>
<!-- BEGIN PAGE LEVEL JS-->
<script src="assets/js/dashboard-crypto.js" type="text/javascript"></script>

<!-- END PAGE LEVEL JS-->
</body>
</html>