<?php
include('../security_web_validation.php');

$login_id = $_SESSION['mlmproject_user_id'];

$sql = "SELECT * FROM reg_fees_structure WHERE user_id = '$login_id'";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
while($row = mysqli_fetch_array($query)){
	$date = date('d.m.Y', strtotime($row['date']));
}

$act_status = "Your subscription is not active";
$act_icon = "times-circle";
$act_class = "text-danger";
$act_d = 'M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z';
if($num > 0){
	$act_status = "Your subscription is active on <B>$date</B>";
	$act_icon = "check-circle";
	$act_class = "text-success";
	$act_d = 'M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z';
}


$sql = "SELECT * FROM lottery_ticket WHERE `user_id` = '$login_id'";
$query = query_execute_sqli($sql);
$num1 = mysqli_num_rows($query);

$cur_status = 'You do not have any tickets. <a href="index.php?page=buy_ticket">Buy Tickets Now</a>';
$cur_icon = "times-circle";
$cur_class = "text-danger";
$cur_d = 'M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z';
if($num1 > 0){
	$cur_status = 'You have '.$num1.' tickets. Buy more <a href="index.php?page=buy_ticket">Buy Tickets</a>';
	$cur_icon = "check-circle";
	$cur_class = "text-success";
	$cur_d = 'M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z';
}


$sql = "SELECT * FROM users WHERE real_parent = '$login_id'";
$query = query_execute_sqli($sql);
$nums = mysqli_num_rows($query);

$plyr_status = "You are NOT qualified";
$plyr_icon = "times-circle";
$plyr_class = "text-danger";
$plyr_d = 'M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z';
if($nums >= 2 and get_user_binary_qualifier($login_id,$systems_date)){
	$plyr_status = "You are qualified";
	$plyr_icon = "check-circle";
	$plyr_class = "text-success";
	$plyr_d = 'M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z';	
}
?>
<div class="col-md-12"><h2><B>Commission Qualification Requirement</B></h2></div>
<div class="col-md-12"><hr /></div>
<!--<div class="col-md-4">
	<div class="row">
		<div class="col-md-2">
			<span class="<?=$act_class?>"><svg class="svg-inline--fa fa-<?=$act_icon?> fa-w-20" aria-hidden="true" data-prefix="fa" data-icon="<?=$act_icon?>" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="<?=$act_d?>"></path></svg></span>
		</div>
		<div class="col-md-10">
			<h4>Monthly Affiliate License</h4>
			<p>You must maintain an active subscription to qualify for weekly payouts.</p>
			<?=$act_status?>
		</div>
	</div>
</div>-->
<div class="col-md-6">
	<div class="row">
		<div class="col-md-2">
			<span class="<?=$cur_class?>"><svg class="svg-inline--fa fa-<?=$cur_icon?> fa-w-16" aria-hidden="true" data-prefix="fa" data-icon="<?=$cur_icon?>" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="<?=$cur_d?>"></path></svg></span>
		</div>
		<div class="col-md-10">
			<h4>Current Lottery</h4>
			<p>You must have at least 1 ticket for the current lottery.</p>
			<?=$cur_status?>
		</div>
	</div>
</div>
<div class="col-md-6">		
	<div class="row">
		<div class="col-md-2">
			<span class="<?=$plyr_class?>"><svg class="svg-inline--fa fa-<?=$plyr_icon?> fa-w-16" aria-hidden="true" data-prefix="fa" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="<?=$plyr_d?>"></path></svg></span>
		</div>
		<div class="col-md-10">
			<h4>Player Requirement</h4>
			<p>You must have <B>2</B> Paid Members,1 on each leg to be paid out bonuses.</p>
			<p>
				<?=$plyr_status?>. You have <B><?=get_user_active_users($login_id)?></B> 
				Paid Members and <B><?=$nums?></B> Active Affiliates
			</p> 
			<p>You need  <B><?=2-get_user_active_users($login_id)?></B> Paid Members</p>
		</div>
	</div>
</div>
<!--<div class="row">
	<div class="col-md-2">
		<span class="text-danger"><svg class="svg-inline--fa fa-times-circle fa-w-16" aria-hidden="true" data-prefix="fa" data-icon="times-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"></path></svg></span>
	</div>
	<div class="col-md-10">
		<h4>PV Requirement</h4>
		<p>You must have 150 PV left and 150 PV right.</p>
		You are not qualified. You have <B>0</B> PV left and <B>0</B> PV right.<br> <br>
		You need <B>150</B> PV left and <B>150</B> PV right.
	</div>
</div>
<div class="row"><hr /></div>-->
</div>
