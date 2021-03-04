<?php
ini_set('display_errors','on');
session_start();

include("../config.php");
$admin_loginID = $_SESSION['intrade_admin_id'];
activity_on_panel($admin_loginID , $_REQUEST, $panel_id = 2);

include("function/pagging_function.php");
include("function/menu_privilegs_function.php");
require("../web_security.php");
if(!empty($_POST))validate_all_post_from_input($_POST);
if(!empty($_GET))validate_all_post_from_input($_GET);
if($_SESSION['intrade_admin_login'] != 1)
{
	include("login.php");
	die;
}

$members_menuId = get_members_menuId($admin_loginID);
for($i = 0; $i < count($members_menuId); $i++){
	$members_menuIds[$i] = $members_menuId[$i][0];
	$members_main_menuIds[$i] = $members_menuId[$i][1];
}

$page_id = get_page_menuId($_REQUEST['page']);
/*$sql = "select t1.* from admin_menu t1 
LEFT JOIN privileges t2 ON t1.id = t2.menu_id
where t1.id = '$page_id' AND  t2.menu_id IS NULL";
$nuq = mysqli_num_rows(query_execute_sqli($sql));
if(((!in_array($page_id,$members_menuIds) and $_REQUEST['page'] !="") or ($page_id == '')) and count($_GET) > 0 and $nuq > 0){*/



$sql = "select * from admin_menu where menu_file='".$_REQUEST['page']."'";
$nuq = mysqli_num_rows(query_execute_sqli($sql));
//if(((!in_array($page_id,$members_menuIds) and $_REQUEST['page'] !="") or ($page_id == '')) and count($_GET) > 0){
if(!in_array($page_id,$members_menuIds) and $_REQUEST['page'] !="" and $nuq > 0){
	include("data/index.html");
	die();
}

$date = date('Y-m-d');
$access_page = $_REQUEST['page'];
$username_log = $_SESSION['intrade_admin_name'];
include("../function/logs_messages.php");
data_logs($admin_loginID,$data_log[26][0],$data_log[26][1],$log_type[26]);
?>
<!--<script type="text/javascript">
if(window.console.firebug)  { 
     document.body.innerHTML = "PLEASE DO NOT USE FIREBUG" 
};
</script>-->
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>UNICGROW - Admin Panel</title>
<link rel="shortcut icon" href="images/logo.png" />

<link href="assets/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">

<!-- Morris -->
<link href="assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

<!-- Gritter -->
<link href="assets/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

<link href="assets/css/animate.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<link href="assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<script src="assets/js/jquery-2.1.1.js"></script>
<script src="assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>

<link href="assets/css/font-awesome.css" rel="stylesheet">    
<link rel="stylesheet" href="assets/css/dashboard.css" type="text/css" media="screen" /> 
</head>
<body>
<div id="wrapper">
	<?php include "left.php"; ?>
	<div id="page-wrapper" class="gray-bg dashbard-1">
		<?php 
		include "top.php";
		include "middle.php";
		include "footer.php"; 
		?>
	</div>
</div>
<script src="assets/js/date.js"></script>
<!-- Mainly scripts -->

<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Flot -->
<script src="assets/js/plugins/flot/jquery.flot.js"></script>
<script src="assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="assets/js/plugins/flot/jquery.flot.spline.js"></script>
<script src="assets/js/plugins/flot/jquery.flot.resize.js"></script>
<script src="assets/js/plugins/flot/jquery.flot.pie.js"></script>

<!-- Peity -->
<script src="assets/js/plugins/peity/jquery.peity.min.js"></script>
<script src="assets/js/demo/peity-demo.js"></script>

<!-- Custom and plugin javascript -->
<script src="assets/js/inspinia.js"></script>
<script src="assets/js/plugins/pace/pace.min.js"></script>
<?php include "chart_data.php"; ?>
<!-- jQuery UI -->
<script src="assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- GITTER -->
<script src="assets/js/plugins/gritter/jquery.gritter.min.js"></script>

<!-- EayPIE -->
<script src="assets/js/plugins/easypiechart/jquery.easypiechart.js"></script>

<!-- Sparkline -->
<script src="assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- Sparkline demo data  -->
<script src="assets/js/demo/sparkline-demo.js"></script>

<!-- ChartJS-->
<script src="assets/js/plugins/chartJs/Chart.min.js"></script>
	
<script>
	$(document).ready(function() {
		setTimeout(function() {
			$.gritter.add({
				title: 'You have <?=inbox_message($id);?> new messages',
				text: 'Go to <a href="index.php?page=inbox" class="text-warning">Mailbox</a> to see who wrote to you.',
				time: 2000
			});
		}, 2000);


		$('.chart').easyPieChart({
			barColor: '#f8ac59',
//                scaleColor: false,
			scaleLength: 5,
			lineWidth: 4,
			size: 80
		});

		$('.chart2').easyPieChart({
			barColor: '#1c84c6',
			scaleLength: 5,
			lineWidth: 4,
			size: 80
		});

		var data1 = [
			[0,4],[1,8],[2,5],[3,10],[4,4],[5,16],[6,5],[7,11],[8,6],[9,11],[10,30],[11,10],[12,13],[13,4],[14,3],[15,3],[16,6]
		];
		var data2 = [
			[0,1],[1,0],[2,2],[3,0],[4,1],[5,3],[6,1],[7,5],[8,2],[9,3],[10,2],[11,1],[12,0],[13,2],[14,8],[15,0],[16,0]
		];
		$("#flot-dashboard-chart").length && $.plot($("#flot-dashboard-chart"), [
			data1, data2
		],
				{
					series: {
						lines: {
							show: false,
							fill: true
						},
						splines: {
							show: true,
							tension: 0.4,
							lineWidth: 1,
							fill: 0.4
						},
						points: {
							radius: 0,
							show: true
						},
						shadowSize: 2
					},
					grid: {
						hoverable: true,
						clickable: true,
						tickColor: "#d5d5d5",
						borderWidth: 1,
						color: '#d5d5d5'
					},
					colors: ["#1ab394", "#464f88"],
					xaxis:{
					},
					yaxis: {
						ticks: 4
					},
					tooltip: false
				}
		);

		var doughnutData = [
			{
				value: 300,
				color: "#a3e1d4",
				highlight: "#1ab394",
				label: "App"
			},
			{
				value: 50,
				color: "#dedede",
				highlight: "#1ab394",
				label: "Software"
			},
			{
				value: 100,
				color: "#b5b8cf",
				highlight: "#1ab394",
				label: "Laptop"
			}
		];

		var doughnutOptions = {
			segmentShowStroke: true,
			segmentStrokeColor: "#fff",
			segmentStrokeWidth: 2,
			percentageInnerCutout: 45, // This is 0 for Pie charts
			animationSteps: 100,
			animationEasing: "easeOutBounce",
			animateRotate: true,
			animateScale: false,
		};

		var ctx = document.getElementById("doughnutChart").getContext("2d");
		var DoughnutChart = new Chart(ctx).Doughnut(doughnutData, doughnutOptions);

		var polarData = [
			{
				value: 300,
				color: "#a3e1d4",
				highlight: "#1ab394",
				label: "App"
			},
			{
				value: 140,
				color: "#dedede",
				highlight: "#1ab394",
				label: "Software"
			},
			{
				value: 200,
				color: "#b5b8cf",
				highlight: "#1ab394",
				label: "Laptop"
			}
		];

		var polarOptions = {
			scaleShowLabelBackdrop: true,
			scaleBackdropColor: "rgba(255,255,255,0.75)",
			scaleBeginAtZero: true,
			scaleBackdropPaddingY: 1,
			scaleBackdropPaddingX: 1,
			scaleShowLine: true,
			segmentShowStroke: true,
			segmentStrokeColor: "#fff",
			segmentStrokeWidth: 2,
			animationSteps: 100,
			animationEasing: "easeOutBounce",
			animateRotate: true,
			animateScale: false,
		};
		var ctx = document.getElementById("polarChart").getContext("2d");
		var Polarchart = new Chart(ctx).PolarArea(polarData, polarOptions);

	});
	
</script>
</body>
</html>
<?php
include "../free_up_memory.php";
?>