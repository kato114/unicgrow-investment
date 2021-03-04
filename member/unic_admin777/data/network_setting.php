<?php
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../function/setting.php");
if(isset($_SESSION['succss_msgs'])){ ?>	
	<div class="alert alert-success alert-dismissable">
		<button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
		<B>Updating completed Successfully</B>
	</div> <?PHP
	unset($_SESSION['succss_msgs']);
} 

if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Update')
	{
		$error[0] = false;	
		$plan_cnt = $_REQUEST['plan_cnt'];
		for($j = 0; $j < $plan_cnt; $j++)
		{
			$plan_ids = $_REQUEST['plan_id_'.$j];
			$plan_names = $_REQUEST['plan_name_'.$j];
			$amounts = $_REQUEST['amount_'.$j];
			$max_amount = $_REQUEST['max_amount_'.$j];
			$binary_percent = $_REQUEST['profit'][1][$j];
			$roi_bonus = $_REQUEST['profit'][2][$j];
			$capping = $_REQUEST['capping_'.$j];
			$days = $_REQUEST['profit'][3][$j];
			$id = $j+1;	 	
			$sql = "update plan_setting set plan_name = '$plan_names' , amount = '$amounts' ,
					max_amount = '$max_amount' , binary_percent = '$binary_percent',
					roi_bonus='$roi_bonus', days = '$days'
					where id = '$plan_ids' ";
			query_execute_sqli($sql);
		}
		
		$plan_cnt = $_REQUEST['level_profit'];
		for($j = 0; $j < count($plan_cnt); $j++)
		{
			$level_profit = $_REQUEST['level_profit'][$j];
			$id = $j+1;	 	
			$sql = "update level_income set percent = '$level_profit'
					where id = '$id' ";
			query_execute_sqli($sql);
		}	

		$maximum_withdrawal = $_REQUEST['maximum_withdrawal'];
		$binary_income_percent = $_REQUEST['binary_income_percent'];
		$transfer_count = $_REQUEST['transfer_count'];
		$token_rate = $_REQUEST['token_rate'];
		$minimum_withdrawal = $_REQUEST['minimum_withdrawal'];
		$ten_level_sponsor_percent = $_REQUEST['binary_pay_day'];
		$per_day_multiple_pair = $_REQUEST['per_day_multiple_pair'];
		$reg_fee = $_REQUEST['reg_fees'];
		$direct_spon_per = $_REQUEST['direct_spon_per'];
		
			$sql = "update setting set reg_sponser='$reg_sponser',direct_income_percent = '$maximum_withdrawal' , binary_income_percent = '$binary_income_percent' , per_day_max_binary_inc = '$token_rate' , transfer_count = '$transfer_count' , minimum_withdrawal = '$minimum_withdrawal' , per_day_multiple_pair = '$per_day_multiple_pair' , registration_fees = '$reg_fee',direct_spon_per='$direct_spon_per' ";
			query_execute_sqli($sql);
		$error[0] = false;
		$error[1] = "<B class='text-danger'>Registration Sponser Not Available !!</B>";		
		data_logs($id,$pos,$data_log[13][0],$data_log[13][1],$log_type[9]); 
	}
	if($_REQUEST['submit'] == 'Add Plan')
	{
		$sql = "insert into plan_setting (plan_name) values ('Enter All Info') ";
		query_execute_sqli($sql);
	} 
	if($_POST['submit'] == 'Delete Plan')
	{	
		$plan_ids = $_REQUEST['plans_id'];
		if($plan_ids == ''){
			echo "<B class='text-danger'>Please Select Any One Plan For Delete !</B>";
		}
		else
		{
			query_execute_sqli("delete from plan_setting where id = '$plan_ids' ");
			query_execute_sqli("ALTER TABLE `plan_setting` DROP `id`");
			query_execute_sqli("ALTER TABLE `plan_setting` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST ,ADD PRIMARY KEY (id)");
		}
	} 
	if($error[0]){
		echo $error[1];
	}
	else{
		$_SESSION['succss_msgs'] = 1;
		?> <script> window.location = "index.php?page=network_setting"; </script> <?php
	}
}

$query = query_execute_sqli("select * from setting ");
while($row = mysqli_fetch_array($query))
{
	$maximum_withdrawal = $row['direct_income_percent'];
	$binary_income_percent = $row['binary_income_percent'];
	$minimun_invests = $row['minimun_invest'];
	$transfer_count = $row['transfer_count'];
	$minimum_withdrawal = $row['minimum_withdrawal'];
	$binary_pay_day = $row['ten_level_sponsor_percent'];
	$per_day_multiple_pair = $row['per_day_multiple_pair'];
	$token_rate = $row['per_day_max_binary_inc'];
	$direct_spon_per = $row['direct_spon_per'];
	$reg_sponser = $row['reg_sponser'];
}
mysqli_free_result($query);
$q = query_execute_sqli("select * from plan_setting order by id asc ");
$plan_count = mysqli_num_rows($q);
$p=0;
while($row = mysqli_fetch_array($q))
{
	$plan_id[$p] = $row['id'];
	$plan_name[$p] = $row['plan_name'];
	$amount[$p] = $row['amount'];
	$pv[$p] = $row['pv'];
	$max_amount[$p] = $row['max_amount'];
	$binary_percent[$p] = $row['binary_percent'];
	$roi_bonus[$p] = $row['roi_bonus'];
	$capping[$p] = $row['capping'];	 
	$days[$p] = $row['days'];	 		
	$p++;
}
mysqli_free_result($q);


$q = query_execute_sqli("select * from level_income order by id asc ");
$p=0;
while($row = mysqli_fetch_array($q))
{
	$level_profit[$p] = $row['percent'];
	$p++;
}
mysqli_free_result($q);
$chked = "selected='selected'";
if($p == 1){ echo "Updating completed Successfully"; } 
?>

<form name="setting" method="post" action="index.php?page=network_setting">
<input type="hidden" name="plan_cnt" value="<?=$plan_count; ?>"  />
<input type="hidden" name="payment_count" value="<?=$payment_count; ?>"/>
<table class="table table-bordered">
	<thead><tr><th colspan="12">Investment Packages</th></tr>
	<tr>
		<th class="text-center">Select For Delete</th>
		<th class="text-center">Packages</th>
		<th class="text-center">MRP(USD)</th>
		<th class="text-center">MRP(USD)</th>
		<th class="text-center">ROI Bonus(%)</th>
		<!--<th class="text-center">Share Holder Referral Bonus(%)</th>
		<th class="text-center">Trade Gaming Bonus(USD)</th>
		<th class="text-center">Weekly Lottery Ticket</th>
		<th class="text-center">Qualification(Weekly)</th>
		<th class="text-center">Dividend(%)</th>
		<th class="text-center">Capping</th>-->
		<th class="text-center">Days</th>
		
	</tr>
	</thead>
  	<?php 
  	for($pi = 0; $pi < $plan_count; $pi++)
  	{ ?>
		<input type="hidden" name="plan_id_<?=$pi?>" value="<?=$plan_id[$pi]?>"/>
		<input type="hidden" name="plan_type" value="first_plan"/>
		<tr>
			<td><input type="radio" name="plans_id" value="<?=$plan_id[$pi]?>"/></td>
			<td>
				<input type="text" name="plan_name_<?=$pi?>" value="<?=$plan_name[$pi]?>" class="form-control" />
			</td>
			<td><input type="text" name="amount_<?=$pi?>" value="<?=$amount[$pi]?>" class="form-control" /></td>
			<td><input type="text" name="max_amount_<?=$pi?>" value="<?=$max_amount[$pi]?>" class="form-control" /></td>
			<td><input type="text" name="profit[2][]" value="<?=$roi_bonus[$pi]?>" class="form-control" /></td>
			
			<!--<td><input type="text" name="profit[2][]" value="<?=$share_bonus[$pi]?>" class="form-control" /></td>
			<td><input type="text" name="profit[4][]" value="<?=$share_rf_bonus[$pi]?>" class="form-control" /></td>
			<td><input type="text" name="profit[3][]" value="<?=$tgaming_bonus[$pi]?>" class="form-control" /></td>
			<td><input type="text" name="lottery[0][]" value="<?=$no_of_lottery[$pi]?>" class="form-control" /></td>
			<td ><input type="text" name="lottery[1][]" value="<?=$qualification_lottery[$pi]?>" class="form-control" /></td>
			<td><input type="text" name="dividend_<?=$pi?>" value="<?=$dividend[$pi]?>" class="form-control" /></td>
			<td><input type="text" name="capping_<?=$pi?>" value="<?=$capping[$pi]?>" class="form-control" /></td>-->
			<td><input type="text" name="profit[3][]" value="<?=$days[$pi]?>" class="form-control" /></td>
		</tr> <?php 
	} ?>
	<tr>
		<td colspan="11" class="text-right">
			<input type="submit" name="submit" value="Add Plan" class="btn btn-info" />
			<input type="submit" name="submit" value="Delete Plan" class="btn btn-info" />
		</td>
	</tr>
	</table>
	
	<table class="table table-bordered">
	<thead>
		<tr><th class="text-center" colspan="2">Referral Income</th></tr>
		<tr><th class="text-center">Level</th><th class="text-left">Percent</th></tr>
	</thead>
	<?php
	for($i = 0; $i < count($level_profit); $i++){
	?>
	<tr>
		<th class="text-center"><?=($i+1)?></th>
		<th class="text-center">
			<input type="text" name="level_profit[]" value="<?=$level_profit[$i]; ?>" class="form-control" />
		</th>
	</tr>
	<?php
	}
	?>
	</table>
	
	<table class="table table-bordered">
	<!--<tr>
		<th colspan="3">Maximum Transfer</th>
		<td colspan="6">
			<input type="text" name="transfer_count" value="<?=$transfer_count; ?>" class="form-control" />
		</td>
	</tr>-->
	<tr>
		<th colspan="3">Minimum Withdrawal</th>
		<td colspan="6">
			<input type="text" name="minimum_withdrawal" value="<?=$minimum_withdrawal?>" class="form-control" />
		</td>
	</tr>
	<tr>
		<th colspan="3">Maximum Withdrawal</th>
		<td colspan="6">
			<input type="text" name="maximum_withdrawal" value="<?=$maximum_withdrawal?>" class="form-control" />
		</td>
	</tr>
	<tr>
		<td colspan="9" class="text-center">
			<input type="submit" name="submit" value="Update" class="btn btn-info" />
		</td>
  	</tr>
</table>
</form>
<?php

?>
