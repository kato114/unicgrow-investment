<?php
include('../security_web_validation.php');
include("function/setting.php");
include("function/total_child_count.php");

$login_id = $_SESSION['mlmproject_user_id'];

$my_plan = my_package($login_id);
$plan_name = $my_plan[0];

$total_child = give_total_children($login_id);
$tot_business = get_network_lr_business($login_id);
$left_bus = $tot_business[0];
$right_bus = $tot_business[1];
//$bin_qual = get_user_binary_qualifier($login_id,$systems_date);

$bin_qual = "<B class='text-danger'>Non Qualified </B>";
if(get_user_binary_qualifier($login_id,$systems_date)){
	$bin_qual = "<B class='text-success'>Qualified </B>";
}


$week_lqulf = "<B class='text-danger'>Non Qualified </B>";
if(week_lottery_exist($login_id,$systems_date)){
	$week_lqulf = "<B class='text-success'>Qualified </B>";
}

//$sql = "SELECT t1.*,shr_amt, trg_amt, ref_amt, bin_amt, ows_amt, leb_amt, lot_amt, lol_amt, trl_amt FROM income 
$sql = " SELECT COALESCE(SUM(amount),0), 'shr_amt' `type` FROM income WHERE type = 1 AND user_id = '$login_id' UNION
SELECT COALESCE(SUM(amount),0), 'trg_amt' `type` FROM income WHERE type = 2 AND user_id = '$login_id' UNION
SELECT COALESCE(SUM(amount),0), 'ref_amt' `type` FROM income WHERE type = 3 AND user_id = '$login_id' UNION
SELECT COALESCE(SUM(amount),0), 'bin_amt' `type` FROM income WHERE type = 4 AND user_id = '$login_id' UNION
SELECT COALESCE(SUM(amount),0), 'ows_amt' `type` FROM income WHERE type = 5 AND user_id = '$login_id' UNION
SELECT COALESCE(SUM(amount),0), 'leb_amt' `type` FROM income WHERE type = 6 AND user_id = '$login_id' UNION
SELECT COALESCE(SUM(amount),0), 'lot_amt' `type` FROM income WHERE type = 7 AND user_id = '$login_id' UNION
SELECT COALESCE(SUM(amount),0), 'lol_amt' `type` FROM income WHERE type = 8 AND user_id = '$login_id' UNION
SELECT COALESCE(SUM(amount),0), 'trl_amt' `type` FROM income WHERE type = 9 AND user_id = '$login_id' UNION
SELECT COALESCE(SUM(amount),0), 'lin5_amt' `type` FROM income WHERE type = 10 AND user_id = '$login_id'";
$query = query_execute_sqli($sql);

$num = mysqli_num_rows($query);
$shr_amt = $trg_amt = 0;
if($num > 0){
	while($row = mysqli_fetch_array($query)){
		$type = $row['type'];
		$amount[$type] = $row[0];
	}
}

?>
<div class="col-lg-4">
	<div class="widget style1 blue-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span>CASH WALLET </span>
				<h2 class="font-bold"><?=round(get_user_allwallet($login_id,'amount'),2)?></h2>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="widget style1 black-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span>SMG CASH WALLET </span>
				<h2 class="font-bold"><?=round(get_user_allwallet($login_id,'trade_gaming'),2)?></h2>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="widget style1 green-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span>TORA GLOBAL SHARE</span><h2 class="font-bold">0</h2>
			</div>
		</div>
	</div>
</div>




<div class="col-lg-3">
	<div class="widget style1 red-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span> SMG Share </span><h2 class="font-bold"><?=round($amount['shr_amt'],2)?></h2>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="widget style1 navy-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span> 5 Linkup Bonus </span>
				<h2 class="font-bold"><?=round(($amount['trl_amt']+$amount['lin5_amt']),2)?></h2>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="widget style1 lazur-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span> Referral Bonus </span><h2 class="font-bold"><?=round($amount['ref_amt'],2)?></h2>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="widget style1 yellow-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span> Binary Bonus </span><h2 class="font-bold"><?=round($amount['bin_amt'],2)?></h2>
			</div>
		</div>
	</div>
</div>
<!--<div class="col-lg-2_5">
	<div class="widget style1 black-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span> Owner Share Bonus </span><h2 class="font-bold"><?=round($amount['ows_amt'],2)?></h2>
			</div>
		</div>
	</div>
</div>-->
<div class="col-lg-4">
	<div class="widget style1 sky-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span> Matching Bonus </span><h2 class="font-bold"><?=round($amount['leb_amt'],2)?></h2>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="widget style1 maroon-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span> Lottery Prize Won </span><h2 class="font-bold"><?=round($amount['lot_amt'],2)?></h2>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="widget style1 white-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span> Winner Sponsor Bonus </span><h2 class="font-bold"><?=round($amount['lol_amt'],2)?></h2>
			</div>
		</div>
	</div>
</div>
<!--<div class="col-lg-2_5">
	<div class="widget style1 lazur-bg">
		<div class="row">
			<div class="col-xs-3"><i class="fa fa-usd fa-5x"></i></div>
			<div class="col-xs-9 text-right">
				<span> Trade Level Income </span><h2 class="font-bold"><?=round($trl_amt,2)?></h2>
			</div>
		</div>
	</div>
</div>-->

<div class="col-lg-12">
	<div class="panel panel-default">
		<div class="panel-body">
			<h4><i class="fa fa-link"></i> Your Referral Link</h4>
			<h3>
				<a class="text-success" href="http://<?=$_SESSION['mlmproject_user_username']?>.unicgrow.com" target="_blank">http://<?=$_SESSION['mlmproject_user_username']?>.unicgrow.com</a>
			</h3>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="panel panel-info">
		<div class="panel-heading"><B>Next Binary Timer</B></div>
		<!--<div class="panel-body lazur-bg">-->
			<table class="table">
				<tr>
					<th>DAYS</th>
					<th>HOURS</th>
					<th>MINS</th>
					<th>SECS</th>
				</tr>
				<tr id="clock_lottery" style="font-size: 30px"></tr>
			</table>
			<!--<div class="col-md-3">DAYS</div>
			<div class="col-md-3">HOURS</div>
			<div class="col-md-3">MINS</div>
			<div class="col-md-3">SECS</div>
				
			<div id="clock_lottery" style="font-size: 32px"></div>
		</div>-->
	</div>
</div>
<div class="col-lg-8">
	<div class="ibox float-e-margins ">
		<div class="ibox-title">
			<h5>Latest Signups</h5>
			<div class="ibox-tools">
				<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
				<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-wrench"></i></a>
				<a class="close-link"><i class="fa fa-times"></i></a>
			</div>
		</div>
		<div class="ibox-content">
			<?php
			$sql = "SELECT * FROM users ORDER BY id_user DESC LIMIT 20";
			$query = query_execute_sqli($sql);
			$num = mysqli_num_rows($query);
			?>
			<div class="simply-scroll simply-scroll-container">
				<div class="simply-scroll-clip">
					<div class="simply-scroll simply-scroll-container">
						<div class="simply-scroll-clip">
							<ul id="scroller" class="simply-scroll-list" style="width: 100%;">
								<?php
								if($num > 0){
									while($row = mysqli_fetch_array($query)){
										$username = ucfirst($row['username']);
										$photo = $row['photo'];
										
										if($photo != ''){ "images/profile_image/$photo"; }
										else{ $photo = "assets/img/1.png"; }
										?>
										<li>
											<table>
												<tr>
													<td rowspan="2" width="60"> 
														<img src="<?=$photo?>" width="55" height="55">
													</td>
													<td>
														<font size="4px" face="Cambria, Hoefler Text, Liberation Serif, Times, Times New Roman, serif"><B><?=$username?></B></font>
													</td>
												</tr>
												<tr><td>Player</td></tr>
											</table>
										</li> <?php
									}
								}
								else{ ?> <li><B class="text-danger">There are no signup user!!</B></li> <?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-4">
	<div class="ibox float-e-margins">
		<div class="ibox-title"><h5><i class="fa fa-usd"></i> Total Earning</h5></div>
		<div class="ibox-content">
			<h2 class="no-margins">
				<button class="btn btn-info btn-circle btn-lg"><i class="fa fa-usd"></i></button> 
				<?=round(get_user_total_bonus($login_id),2)?>
			</h2>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="ibox float-e-margins">
		<div class="ibox-title"><h5><i class="fa fa-trophy"></i> Weekly Qualified</h5></div>
		<div class="ibox-content">
			<h2 class="no-margins">
				<button class="btn btn-success btn-circle btn-lg"><i class="fa fa-trophy fa-5px"></i></button>
				<?=$week_lqulf?>
			</h2>
		</div>
	</div>
</div>
<div class="col-lg-4">
	<div class="ibox float-e-margins">
		<div class="ibox-title"><h5><i class="fa fa-trophy"></i> Binary Qualified</h5></div>
		<div class="ibox-content">
			<h2 class="no-margins">
				<button class="btn btn-success btn-circle btn-lg"><i class="fa fa-trophy fa-5px"></i></button> 
				<?=$bin_qual?>
			</h2>
		</div>
	</div>
</div>

<div class="col-lg-12">
	<div class="ibox-title"><h5><i class="fa fa-money"></i> Left Right Business</h5></div>
</div>
<div class="col-lg-6">
	<div class="widget style1 green-bg">
		<div class="row">
			<div class="col-xs-4"><h3> <B>Left</B></h3></div>
			<div class="col-xs-4 text-center"><h3> <B><?=$total_child[0][3]?> <i class="fa fa-user"></i></B></h3></div>
			<div class="col-xs-4 text-right"><h3><B><i class="fa fa-usd"></i><?=$left_bus?></B></h3></div>
		</div>
	</div>
</div>
<div class="col-lg-6">
	<div class="widget style1 red-bg">
		<div class="row">
			<div class="col-xs-4"><h3> <B>Right</B></h3></div>
			<div class="col-xs-4 text-center">
				<h3><B><?=$total_child[1][3]?> <i class="fa fa-user"></i></B></h3>
			</div>
			<div class="col-xs-4 text-right"><h3><B><i class="fa fa-usd"></i><?=$right_bus?></B></h3></div>
		</div>
	</div>
</div>


<script type="text/javascript" src="assets/js/jquery_004.js"></script>
<link rel="stylesheet" href="assets/css/jquery.css" media="all" type="text/css">
<link rel="stylesheet" href="assets/css/simplyscroll.css" media="all" type="text/css">

<script type="text/javascript">
(function($) {
	$("#scroller").simplyScroll({
		auto: true,
		speed: 1
	});
})(jQuery);
</script>

<?php 
echo $result = date('Y-m-d 00:00:00', strtotime($systems_date_time." Next ".$binary_pay_day));
$cur_time = $systems_date_time;
$swr = "SELECT TIMESTAMPDIFF(SECOND,'$cur_time', '$result') as seconds";
$result = mysqli_fetch_array(query_execute_sqli($swr));
$tot_second = $result[0];
?>
<script>
var clocks = new Array();
clocks['clock_lottery'] = parseInt('<?=$tot_second?>');
</script>