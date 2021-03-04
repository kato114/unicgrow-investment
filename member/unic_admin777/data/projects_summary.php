<?php
//include('../../security_web_validation.php');

include("condition.php");
include("../function/setting.php");


$all_member = get_tot_act_dct_member($date = false);
$tot_users = $all_member[0];
$tot_act_users = $all_member[1];
$tot_inct_users = $tot_users-$tot_act_users;
$tot_block_users = $all_member[2];
$tot_lot_qual_users = $all_member[3];

$tot_invest = get_total_investment_users($date = false);

$today_member = get_tot_act_dct_member($systems_date);
$today_users = $today_member[0];
$today_act_users = $today_member[1];
$today_inct_users = $today_users-$today_act_users;
$today_block_users = $today_member[2];
$today_tot_users = $today_member[4];

$today_invest = get_total_investment_users($systems_date);

$last_month = get_month_act_dct_member('last');
$last_month_users = $last_month[0];
$last_month_act_users = $last_month[1];
$last_month_inct_users = $last_month_users-$last_month_act_users;
$last_month_block_users = $last_month[2];

$this_month = get_month_act_dct_member('current');
$this_month_users = $this_month[0];
$this_month_act_users = $this_month[1];
$this_month_inct_users = $this_month_users-$this_month_act_users;
$this_month_block_users = $this_month[2];

$last_month_invst = get_month_users_investment('last');
$cur_month_invst = get_month_users_investment('current');


$tot_withdraw = get_total_withdrawal(1);
$total_withdraw = $tot_withdraw[1];
$num_withdraw = $tot_withdraw[0];

$last_withdraw = get_total_withdrawal(1,$systems_date)[1];

$tot_roi = get_total_withdrawal(2);
$total_roi_withdraw = $tot_roi[1];
$num_roi_withdraw = $tot_roi[0];

$last_roi_withdraw = get_total_withdrawal(2,$systems_date)[1];

$bonus_ref = round(get_user_income_total(2),4);
$bonus_own = round(get_user_income_total(5),4);
$bonus_bin = round(get_user_income_total(3),4);
$bonus_lev = round(get_user_income_total(1),4);
$bonus_w_spon = round(get_user_income_total(7),4);
$bonus_5linkup = round(get_user_income_total(8),4);
$bonus_trade = round(get_user_income_total(9),4);

$withdraw_pend = round(get_total_withdraw_amt(65, false),4);
$withdraw_conf = round(get_total_withdraw_amt(2, false),4);
$withdraw_today = round(get_total_withdraw_amt(false,$systems_date),4);

$cash_wal = get_total_all_wallet('amount');
?>

<div class="widget-content">
	<div class="shortcuts"> 
		<a href="index.php?page=user_email" class="shortcut">
			<i class="shortcut-icon icon-user"></i>
			<span class="shortcut-label">User Data</span>
		 </a>
		<a href="index.php?page=tree_mem" class="shortcut">
			<i class="shortcut-icon icon-signal"></i>
			<span class="shortcut-label">Geneology</span> 
		</a>
		<a href="index.php?page=wallet_info" class="shortcut">
			<i class="shortcut-icon icon-list"></i> 
			<span class="shortcut-label">Deposit Wallet</span> 
		</a>
		<a href="index.php?page=network_setting" class="shortcut"> 
			<i class="shortcut-icon icon-tasks"></i>
			<span class="shortcut-label">Packages</span>
		</a>
		<!--<a href="index.php?page=network_setting" class="shortcut"> 
			<i class="shortcut-icon icon-gear"></i>
			<span class="shortcut-label">Setting</span>
		</a>-->
		<a href="index.php?page=approve_investment" class="shortcut">
			<i class="shortcut-icon icon-indent-left "></i>
			<span class="shortcut-label">Payment Verification</span>
		</a>
		<a href="index.php?page=withdrawal_report" class="shortcut">
			<i class="shortcut-icon icon-glass"></i>
			<span class="shortcut-label">Withdrawal Request</span> 
		</a>
		<a href="index.php?page=support_ticket" class="shortcut">
			<i class="shortcut-icon icon-random"></i> 
			<span class="shortcut-label">Support</span> 
		</a>
		<a href="index.php?page=admin_privileges" class="shortcut"> 
			<i class="shortcut-icon icon-inbox"></i>
			<span class="shortcut-label">Sub Account</span> 
		</a>
	</div>
</div>


<!--<div class="col-md-12"><h3>Transaction :</h3></div>
<div class="col-lg-3">
	<div class="widget style1 yellow-bg">
		<div class="row">
			<div class="col-xs-4"><i class="fa fa-usd fa-4x"></i></div>
			<div class="col-xs-8 text-right">
				<span> Cash <br />Wallet</span>
				<h3 class="font-bold"><?=$cash_wal?></h3>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="widget style1 navy-bg">
		<div class="row">
			<div class="col-xs-4"><i class="fa fa-trophy fa-4x"></i></div>
			<div class="col-xs-8 text-right">
				<span>SMG Trading Wallet</span>
				<h3 class="font-bold"><?=get_total_all_wallet('trade_gaming')?></h3>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="widget style1 bg-success">
		<div class="row">
			<div class="col-xs-4"><i class="fa fa-trophy fa-4x"></i></div>
			<div class="col-xs-8 text-right">
				<span>SMG <br />Share</span>
				<h3 class="font-bold"><?=get_total_all_wallet('owner_share')?></h3>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="widget style1 blue-bg">
		<div class="row">
			<div class="col-xs-4"><i class="fa fa-trophy fa-4x"></i></div>
			<div class="col-xs-8 text-right">
				<span>Tora <br />Global</span>
				<h3 class="font-bold"><?=get_total_all_wallet('share_holder')?></h3>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">&nbsp;</div>-->

<table class="table table-bordered">
	<thead><tr><th colspan="3">Business Summary</th></tr></thead>
	<tr>
		<th>Registered Member</th>
		<th class="text-center"><i class="fa fa-user"></i> <?=$tot_users?></th>
		<th class="text-center"><a href="index.php?page=user_email" class="btn btn-info btn-sm">List</a></th>
	</tr>
	<tr>
		<th>Active Member</th>
		<th class="text-center"><i class="fa fa-user"></i> <?=$tot_act_users?></th>
		<th class="text-center">
			<form action="index.php?page=user_email" method="post">
				<input type="hidden" name="mem_status" value="2" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>
	<!--<tr>
		<th>Lottery Qualifier</th>
		<th class="text-center"><i class="fa fa-user"></i> <?=$tot_lot_qual_users?></th>
		<th class="text-center">
			<form action="index.php?page=user_email" method="post">
				<input type="hidden" name="lot_qual" value="1" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>-->
	<tr>
		<th>Blocked Member</th>
		<th class="text-center"><i class="fa fa-user"></i> <?=$tot_block_users?></th>
		<th class="text-center">
			<form action="index.php?page=user_email" method="post">
				<input type="hidden" name="mem_status" value="3" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>
	<!--<tr>
		<th>Active Without Lottery</th>
		<th class="text-center"><i class="fa fa-user"></i>  <?=get_total_act_without_lottery()?></th>
		<th class="text-center">
			<form action="index.php?page=user_email" method="post">
				<input type="hidden" name="lot_qual" value="2" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>-->
	<tr>
		<th>Today Joined Member</th>
		<th class="text-center"><i class="fa fa-user"></i> <?=$today_tot_users?></th>
		<th class="text-center">
			<form action="index.php?page=user_email" method="post">
				<input type="hidden" name="today_mem" value="1" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>
	<tr>
		<th>Today Total Activations</th>
		<th class="text-center"><i class="fa fa-user"></i> <?=$today_act_users?></th>
		<th class="text-center">
			<form action="index.php?page=user_email" method="post">
				<input type="hidden" name="today_mem" value="2" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>
	<tr>
		<th>Today Total Deposits</th>
		<th class="text-center">&#36;<?=get_total_deposit($systems_date)?></th>
		<th class="text-center">
			<form action="index.php?page=approve_investment" method="post">
				<input type="hidden" name="today_depo" value="1" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>
	<tr>
		<th>Today Total Withdrawal</th>
		<th class="text-center">&#36;<?=$withdraw_today?></th>
		<th class="text-center">
			<form action="index.php?page=withdrawal_history" method="post">
				<input type="hidden" name="t_status" value="1" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>
	<tr>
		<th>All Pending Withdrawal</th>
		<th class="text-center">&#36;<?=$withdraw_pend?></th>
		<th class="text-center">
			<form action="index.php?page=withdrawal_history" method="post">
				<input type="hidden" name="w_status" value="1" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>
	<tr>
		<th>All Confirmed Withdrawal</th>
		<th class="text-center">&#36;<?=$withdraw_conf?></th>
		<th class="text-center">
			<form action="index.php?page=withdrawal_history" method="post">
				<input type="hidden" name="w_status" value="2" />
				<input type="submit" value="List" name="Search" class="btn btn-info btn-sm">
			</form>
		</th>
	</tr>
	<!--<tr>
		<th>Lottery Data</th>
		<th class="text-center"><?=get_total_lottery_ticket()?></th>
		<th class="text-center"><a href="index.php?page=ticket_history" class="btn btn-info btn-sm">List</a></th>
	</tr>
	<tr>
		<th>Total Lottery Era(Weeks)</th>
		<th class="text-center"><?=get_total_lottery_ticket_week()?></th>
		<th class="text-center"><a href="index.php?page=result_history" class="btn btn-info btn-sm">List</a></th>
	</tr>-->
	<tr>
		<th>Total Credit To Company</th>
		<th class="text-center">&#36;<?=get_total_cr_dr_to_company()?></th>
		<th class="text-center"><a href="index.php?page=cr_com_wall" class="btn btn-info btn-sm">List</a></th>
	</tr>
	<tr>
		<th>Total Debit To Company</th>
		<th class="text-center">&#36;<?=get_total_cr_dr_to_company(1)?></th>
		<th class="text-center"><a href="index.php?page=dr_com_wallet" class="btn btn-info btn-sm">List</a></th>
	</tr>
	<!--<tr>
		<th>Liability of Company</th>
		<th class="text-center">&#36;<?=$cash_wal?></th>
		<th class="text-center"><a href="index.php?page=wallet_info" class="btn btn-info btn-sm">List</a></th>
	</tr>-->
</table>

<table class="table table-bordered">
	<thead><tr><th colspan="3">Bonuses</th></tr></thead>
	<tr>
		<th>Daily ROI Income</th>
		<th class="text-center"><?=$bonus_lev?></th>
		<th class="text-center"><a href="index.php?page=bonus_roi" class="btn btn-info btn-sm">List</a></th>
	</tr>
	<!--<tr>
		<th>Owner Share Bonus</th>
		<th class="text-center">&#36;<?=$bonus_own?></th>
		<th class="text-center"><a href="index.php?page=bonus_owner" class="btn btn-info btn-sm">List</a></th>
	</tr>
	<tr>
		<th>Binary Bonus</th>
		<th class="text-center"><?=$bonus_bin?></th>
		<th class="text-center"><a href="index.php?page=bonus_binary" class="btn btn-info btn-sm">List</a></th>
	</tr>-->
	<tr>
		<th>Level Income</th>
		<th class="text-center"><?=$bonus_ref?></th>
		<th class="text-center"><a href="index.php?page=bonus_level" class="btn btn-info btn-sm">List</a></th>
	</tr>
	<!--<tr>
		<th>Principal Return</th>
		<th class="text-center">&#36;<?=$bonus_bin?></th>
		<th class="text-center"><a href="index.php?page=bonus_lottery" class="btn btn-info btn-sm">List</a></th>
	</tr>
	<tr>
		<th>5 Linkup Bonus</th>
		<th class="text-center">&#36;<?=$bonus_5linkup?></th>
		<th class="text-center"><a href="index.php?page=bonus_lottery_level" class="btn btn-info btn-sm">List</a></th>
	</tr>
	<tr>
		<th>5 Linkup Trading Bonus</th>
		<th class="text-center">&#36;<?=$bonus_trade?></th>
		<th class="text-center"><a href="index.php?page=bonus_tradel" class="btn btn-info btn-sm">List</a></th>
	</tr>-->
</table>
<!--<div class="col-md-6">
	<table class="table table-bordered">
		<thead><tr><th colspan="2" class="text-center">Business Summary</th></tr></thead>
		<tr>
			<th width="50%">Total Associates</th>
			<th><i class="fa fa-user"></i><?=$tot_users;?></th>
		</tr>
		<tr>
			<th>Active Associates</th>
			<th><i class="fa fa-user"></i><?=$tot_act_users;?></th>
		</tr>
		<tr>
			<th>Registered Associates</th>
			<th><i class="fa fa-user"></i><?=$tot_inct_users;?></th>
		</tr>
		<tr>
			<th>Block Associates</th>
			<th><i class="fa fa-user"></i><?=$tot_block_users;?></th>
		</tr>
		<tr>
			<th>Total Investment</th>
			<th><i class="fa fa-inr"></i><?=$tot_invest;?></th>
		</tr>
		<tr>
			<th>Total Deposit</th>
			<th><i class="fa fa-inr"></i><?=get_total_wallet_amnt("amount")?></th>
		</tr>
		<tr>
			<th>Total Commission Wallet</th>
			<th><i class="fa fa-inr"></i><?=get_total_wallet_amnt("amount")?></th>
		</tr>
	</table>
</div>
<div class="col-md-6">
	<table class="table table-bordered">
		<thead><tr><th colspan="2" class="text-center">Today's Business Summary</th></tr></thead>
		<tr>
			<th width="50%">Total Associates</th>
			<th><i class="fa fa-user"></i><?=$today_users;?></th>
		</tr>
		<tr>
			<th>Active Associates</th>
			<th><i class="fa fa-user"></i><?=$today_act_users;?></th>
		</tr>
		<tr>
			<th>Registered Associates</th>
			<th><i class="fa fa-user"></i><?=$today_inct_users;?></th>
		</tr>
		<tr>
			<th>Block Associates</th>
			<th><i class="fa fa-user"></i><?=$today_block_users;?></th>
		</tr>
		<tr>
			<th>Total Investment</th>
			<th><i class="fa fa-inr"></i><?=$today_invest;?></th>
		</tr>
		<tr>
			<th>Total Deposit</th>
			<th><i class="fa fa-inr"></i><?=get_total_wallet_amnt("amount",$systems_date)?></th>
		</tr>
		<tr>
			<th>Total Commission Wallet</th>
			<th><i class="fa fa-inr"></i><?=get_total_wallet_amnt("amount",$systems_date)?></th>
		</tr>
	</table>
</div>


<div class="col-md-6">
	<table class="table table-bordered">
		<thead><tr><th colspan="2" class="text-center">Last Month Business Summary</th></tr></thead>
		<tr>
			<th width="50%">Total Associates</th>
			<th><i class="fa fa-user"></i><?=$last_month_users;?></th>
		</tr>
		<tr>
			<th>Active Associates</th>
			<th><i class="fa fa-user"></i><?=$last_month_act_users;?></th>
		</tr>
		<tr>
			<th>Registered Associates</th>
			<th><i class="fa fa-user"></i><?=$last_month_inct_users;?></th>
		</tr>
		<tr>
			<th>Block Associates</th>
			<th><i class="fa fa-user"></i><?=$last_month_block_users;?></th>
		</tr>
		<tr>
			<th>Total Investment</th>
			<th><i class="fa fa-inr"></i><?=$last_month_invst;?></th>
		</tr>
		<tr>
			<th>Total Deposit</th>
			<th><i class="fa fa-inr"></i><?=get_month_wallet_amnt("amount",'last')?></th>
		</tr>
		<tr>
			<th>Total Commission Wallet</th>
			<th><i class="fa fa-inr"></i><?=get_month_wallet_amnt("amount",'last')?></th>
		</tr>
	</table>
</div>
<div class="col-md-6">
	<table class="table table-bordered">
		<thead><tr><th colspan="2" class="text-center">This Month Summary</th></tr></thead>
		<tr>
			<th width="50%">Total Associates</th>
			<th><i class="fa fa-user"></i><?=$this_month_users;?></th>
		</tr>
		<tr>
			<th>Active Associates</th>
			<th><i class="fa fa-user"></i><?=$this_month_act_users;?></th>
		</tr>
		<tr>
			<th>Registered Associates</th>
			<th><i class="fa fa-user"></i><?=$this_month_inct_users;?></th>
		</tr>
		<tr>
			<th>Block Associates</th>
			<th><i class="fa fa-user"></i><?=$this_month_block_users;?></th>
		</tr>
		<tr>
			<th>Total Investment</th>
			<th><i class="fa fa-inr"></i><?=$cur_month_invst;?></th>
		</tr>
		<tr>
			<th>Total Deposit</th>
			<th><i class="fa fa-inr"></i><?=get_month_wallet_amnt("amount",'current')?></th>
		</tr>
		<tr>
			<th>Total Commission Wallet</th>
			<th><i class="fa fa-inr"></i><?=get_month_wallet_amnt("amount",'current')?></th>
		</tr>
	</table>
</div>

<div class="col-md-6">
	<table class="table table-bordered">
		<thead><tr><th colspan="2" class="text-center">Withdrawal Details</th></tr></thead>
		<tr>
			<th>Total Withdrawal Amount</th>
			<th><i class="fa fa-inr"></i><?=$total_withdraw?></th>
		</tr>
		<tr>
			<th>Number of Withdrawal</th>
			<th> <?=$num_withdraw?></th>
		</tr>
		<tr>
			<th>Last Withdrawal</th>
			<th><i class="fa fa-inr"></i><?=$last_withdraw?></th>
		</tr>
	</table>
</div>
<div class="col-md-6">
	<table class="table table-bordered">
		<thead><tr><th colspan="2" class="text-center">ROI Withdrawal Details</th></tr></thead>
		<tr>
			<th>Total ROI Withdrawal</th>
			<th><i class="fa fa-inr"></i><?=$total_roi_withdraw?></th>
		</tr>
		<tr>
			<th>Number of ROI</th>
			<th> <?=$num_roi_withdraw?></th>
		</tr>
		<tr>
			<th>Last ROI Withdrawal</th>
			<th><i class="fa fa-inr"></i><?=$last_roi_withdraw?></th>
		</tr>
	</table>
</div>

<div class="col-md-6">
	<table class="table table-bordered">
		<thead><tr><th colspan="2" class="text-center">Deposit Details</th></tr></thead>
		<tr>
			<th width="50%">Total Deposit</th>
			<th><i class="fa fa-inr"></i><?=get_total_wallet_amnt("activationw")?></th>
		</tr>
		<tr>
			<th>Total Available Deposit</th>
			<th><i class="fa fa-inr"></i><?=get_total_wallet_amnt("amount")?></th>
		</tr>
		<tr>
			<th>Total Used Deposit</th>
			<th><i class="fa fa-inr"></i><?=get_availabe_ewallet_amnt()?></th>
		</tr>
	</table>
</div>
<div class="col-md-6">
	<table class="table table-bordered">
		<thead><tr><th colspan="2" class="text-center">Commission Details</th></tr></thead>
		<tr><th width="50%">Today's Binary</th><th><i class="fa fa-inr"></i><?=$today_bin?></th></tr>
		<tr><th>Total Binary</th><th><i class="fa fa-inr"></i><?=$tot_bin?></th></tr>
		<tr><th>Today's ROI</th><th><i class="fa fa-inr"></i><?=$today_roi?></th></tr>
		<tr><th>Total ROI</th><th><i class="fa fa-inr"></i><?=$tot_roi?></th></tr>
	</table>
</div>-->




<?php
// total 
function get_tot_act_dct_member($date = false){
	
	$quer_where = $quer_and = $sql1 = $qu1 = $sql2 = $qu2 = $sql3 = $qu3 = $sql4 = $qu4 = NULL;
	if($date != false){
		$quer_where = " WHERE date = '$date'";
		$quer_and = " AND date = '$date'";  
	}
	
	$result = array(0,0,0,0);
	//Total Users
	$sql1 = "SELECT * FROM users $quer_where";
	$qu1 = query_execute_sqli($sql1);
	$result[0] = mysqli_num_rows($qu1);
	
	//Total Active Users
	$sql2 = "SELECT * FROM users WHERE step = 1 AND type = 'B' $quer_and";
	$qu2 = query_execute_sqli($sql2);
	$result[1] = mysqli_num_rows($qu2);
	
	//Total Block Users
	$sql3 = "SELECT * FROM users WHERE type = 'D' $quer_and";
	$qu3 = query_execute_sqli($sql3);
	$result[2] = mysqli_num_rows($qu3);
	
	//Total Lottery Qualifier Users
	$sql4 = "SELECT * FROM lottery_ticket $quer_where GROUP BY user_id";
	$qu4 = query_execute_sqli($sql4);
	$result[3] = mysqli_num_rows($qu4);
	
	//Total Lottery Qualifier Users
	$sql5 = "SELECT * FROM users WHERE step = 0 AND type = 'B' $quer_and";
	$qu5 = query_execute_sqli($sql5);
	$result[4] = mysqli_num_rows($qu5);
	
	mysqli_free_result($qu1);
	mysqli_free_result($qu2);
	mysqli_free_result($qu3);
	mysqli_free_result($qu4);
	return $result;	
}

function get_total_investment_users($date = false){
	$quer_search = $sql = $amt = NULL;
	if($date != false){
		$quer_search = " WHERE date = '$date'";  
	}
	
	$sql = "SELECT SUM(request_crowd) amt FROM reg_fees_structure $quer_search";
	$query = query_execute_sqli($sql);
	$amt = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	if($amt > 0){ return $amt; }
	else{ return 0; } 
}

function get_total_deposit($date = false){
	$quer_search = $sql = $amt = NULL;
	if($date != false){
		$quer_search = " WHERE DATE(date) = '$date'";  
	}
	
	$sql = "SELECT SUM(investment) amt FROM request_crown_wallet $quer_search";
	$query = query_execute_sqli($sql);
	$amt = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	if($amt > 0){ return $amt; }
	else{ return 0; } 
}

function get_total_withdraw_amt($status = false, $date = false){
	$quer_search = $sql = $amt = NULL;
	if($date != false){
		$sql = "SELECT COALESCE(SUM(amount),0) amt FROM withdrawal_crown_wallet WHERE DATE(date) = '$date'";  
	}
	else{
		$quer_search = "2";
		switch($status){
			case 65 : $quer_search = "65,0";	break;
		}
		$sql = "SELECT COALESCE(SUM(amount),0) amt FROM withdrawal_crown_wallet WHERE status IN($quer_search)";
	}
	$query = query_execute_sqli($sql);
	$amt = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $amt;
}

function get_total_lottery_ticket(){
	$query = $sql = $result = NULL;
	$sql = "SELECT COUNT(*) num FROM lottery_ticket";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}

function get_total_lottery_ticket_week(){
	$query = $sql = $result = NULL;
	$sql = "SELECT COUNT(*) num FROM lottery_ticket WHERE mode = 1 GROUP BY lottery_no, rdate";
	$query = query_execute_sqli($sql);
	//$result = mysqli_fetch_array($query)[0];
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_total_act_without_lottery(){
	$query = $sql = $result = NULL;
	$sql = "SELECT * FROM users WHERE id_user NOT IN( SELECT user_id FROM lottery_ticket GROUP BY user_id) AND
	step = 1";
	$query = query_execute_sqli($sql);
	$result = mysqli_num_rows($query);
	mysqli_free_result($query);
	return $result;
}

function get_total_all_wallet($field){
	$sql = "SELECT COALESCE(SUM($field),0) amt FROM wallet";
	$query = query_execute_sqli($sql);
	$result = round(mysqli_fetch_array($query)[0],2);
	mysqli_free_result($query);
	return $result;
}

function get_total_cr_dr_to_company($type = false){
	$quer_search = $sql = $amt = NULL;
	if($type != false){
		//$sql = "SELECT COALESCE(SUM(ramount),0) amt FROM lottery_ticket WHERE `rank` > 0";
		$sql = "SELECT COALESCE(SUM(amount),0) amt FROM withdrawal_crown_wallet";
	}
	else{
		$sql = "SELECT COALESCE(SUM(investment),0) amt FROM request_crown_wallet";
		//$sql = "SELECT COALESCE(SUM(amount),0) amt FROM lottery_ticket";
	}
	$query = query_execute_sqli($sql);
	$result = round(mysqli_fetch_array($query)[0],2);
	mysqli_free_result($query);
	return $result;
}


function get_total_wallet_amnt($wall_type,$date = false){

	$quer_search = $sql = $amt = NULL;
	if($date != false){
		$quer_search = " WHERE date = '$date'";  
	}
	
	$sql = "SELECT SUM($wall_type) FROM wallet $quer_search";
	$query = query_execute_sqli($sql);
	$amt = round(mysqli_fetch_array($query)[0],2);
	mysqli_free_result($query);
	return $amt;
}

function get_month_act_dct_member($month){
	
	$quer_where = $quer_and = $sql1 = $qu1 = $sql2 = $qu2 = $sql3 = $qu3 = NULL;
	if($month == 'current'){
		$quer_where = " WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())"; 
		$quer_and = " AND MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())";
	}
	else{
		$quer_where = " WHERE `date` >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01 00:00:00') 
		AND `date` <= DATE_FORMAT(LAST_DAY(CURDATE()-INTERVAL 1 MONTH),'%Y-%m-%d 23:59:59')";
		$quer_and = " AND `date` >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01 00:00:00') 
		AND `date` <= DATE_FORMAT(LAST_DAY(CURDATE()-INTERVAL 1 MONTH),'%Y-%m-%d 23:59:59')"; 
	}
	
	$result = array(0,0,0);
	//Total Users
	$sql1 = "SELECT * FROM `users` $quer_where";
	$qu1 = query_execute_sqli($sql1);
	$result[0] = mysqli_num_rows($qu1);
	
	//Total Active Users
	$sql2 = "SELECT * FROM `users` WHERE package > 0 $quer_and";
	$qu2 = query_execute_sqli($sql2);
	$result[1] = mysqli_num_rows($qu2);
	
	//Total Block Users
	$sql3 = "SELECT * FROM `users` WHERE type = 'D' $quer_and";
	$qu3 = query_execute_sqli($sql3);
	$result[2] = mysqli_num_rows($qu3);
	
	mysqli_free_result($qu1);
	mysqli_free_result($qu2);
	mysqli_free_result($qu3);
	return $result;	
}

function get_month_users_investment($month){

	$quer_search = $sql = $amt = NULL;
	if($month == 'current'){
		$quer_search = " WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())"; 
	}
	else{
		$quer_search = " WHERE `date` >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01 00:00:00') 
		AND `date` <= DATE_FORMAT(LAST_DAY(CURDATE()-INTERVAL 1 MONTH),'%Y-%m-%d 23:59:59')"; 
	}
	
	$sql = "SELECT SUM(request_crowd) amt FROM reg_fees_structure $quer_search";
	$query = query_execute_sqli($sql);
	$amt = mysqli_fetch_array($query)[0];
	if($amt > 0){ return $amt; }
	else{ return 0; } 
}

function get_month_wallet_amnt($wall_type,$month){

	$quer_search = $sql = $amt = NULL;
	if($month == 'current'){
		$quer_search = " WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE())"; 
	}
	else{
		$quer_search = " WHERE `date` >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH,'%Y-%m-01 00:00:00') 
		AND `date` <= DATE_FORMAT(LAST_DAY(CURDATE()-INTERVAL 1 MONTH),'%Y-%m-%d 23:59:59')"; 
	}
	
	$sql = "SELECT SUM($wall_type) FROM wallet $quer_search";
	$query = query_execute_sqli($sql);
	$amt = round(mysqli_fetch_array($query)[0],2);
	mysqli_free_result($query);
	return $amt;
}

function get_total_withdrawal($ac_type,$date = false){
	
	$quer_search = $date_find = $day_give = $sql = $amt = $pre_mnth_first_day = NULL;
	
	if($date != false){
		$pre_mnth_first_day =  date('Y-n-j', strtotime('first day of previous month')); //First day of previous month
		$day_give = date('d', strtotime($date));
		switch($day_give){
			case($day_give > 1 and $day_give <= 10): 
				$date_find = date('Y-m-01');
			break;
			case($day_give > 10 and $day_give <= 20): $date_find = date('Y-m-11'); break;
			case($day_give > 20 and $day_give < 31): $date_find = date('Y-m-21'); break;
			case ($day_give == 1): 
				$date_find =  date("Y-n-j", strtotime($pre_mnth_first_day."+20 Day"));
			break;
		}
		$quer_search = " AND DATE(`date`) = '$date_find'";
	}
	
	$sql1 = "SELECT * FROM withdrawal_crown_wallet WHERE ac_type = '$ac_type' $quer_search";
	$que1 = query_execute_sqli($sql1);
	$result[0] = mysqli_num_rows($que1);
	
	$sql2 = "SELECT SUM(request_crowd) amt FROM withdrawal_crown_wallet WHERE ac_type = '$ac_type' $quer_search";
	$que2 = query_execute_sqli($sql2);
	$result[1] = round(mysqli_fetch_array($que2)[0],2);
	mysqli_free_result($que1);
	mysqli_free_result($que2);
	//if($result > 0){ return $result; }
	//else{ return 0; } 
	return $result;	
}

function get_availabe_ewallet_amnt(){
	$sql = "SELECT SUM(dr) FROM account WHERE type = 11";
	$query = query_execute_sqli($sql);
	$amt = round(mysqli_fetch_array($query)[0],2);
	mysqli_free_result($query);
	return $amt;
}

function get_user_income_total($type,$date = false){
	$quer_search = $sql = $amt = NULL;
	if($date != false){
		$quer_search = " AND date = '$date'";  
	}
	
	$sql = "SELECT COALESCE(SUM(amount),0) FROM income WHERE type = '$type' $quer_search";
	$query = query_execute_sqli($sql);
	$result = mysqli_fetch_array($query)[0];
	mysqli_free_result($query);
	return $result;
}
?>
