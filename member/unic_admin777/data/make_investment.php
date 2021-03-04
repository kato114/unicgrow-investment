<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/direct_income.php");
include("../function/check_income_condition.php");
include("../function/pair_point_calc.php");
include("../function/send_mail.php");
?>
<script>
$(document).ready(function() {	
	$("#invest_dd").keyup(function (e) {	
		var amt = $(this).val();
		$.post('../selcet_plan.php', {'check_amt':amt},function(data2)
		{
		  	var obj = JSON.parse(data2);
			var genp = obj.p;
			if(genp > 0){
				var deduct = amt*<?=$set_activation_wallet_invest?>/100;			
				$("#genpad").html(deduct);
				$("#genpgd").html(amt-deduct);
				$("#msg_info").html(obj.m);
			}
			else{
				return false;
			}
		});
		
	});	
});	
</script>
<?php
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Make Investment')
	{
		$user_id = $id = $_SESSION['admin_make_invst_id'];
		$investment = $_REQUEST['investment_amt'];
		$package_id = $_REQUEST['package_id'];
		$process = 1;
		$wallet_amount = $investment;
		/*if($investment >= min($set_amount))
		{*/
			if(!isset($_SESSION['make_investment'])){
				$_SESSION['make_investment'] = 0;
				$date = $systems_date;
				$sql = "select * from plan_setting where amount = '$investment' order by id desc limit 1";
				$query1 = query_execute_sqli($sql);
				$plan_id = "";
				while($rr = mysqli_fetch_array($query1))
				{
					$plan_id = $rr['id'];
					$days = $rr['days'];
					$pv = $rr['pv'];
					$profit = $rr['daily_profit']; 
					$investment = $invest_amount = $investment;
				}
				$package_id=$plan_id;
				$p_value = 64+$plan_id;
				$p_value = chr($p_value);
				$time = $systems_date_time;
				$start_date = date('Y-m-d', strtotime($systems_date . ' + 1 month'));
				$end_date = date('Y-m-d', strtotime($systems_date . " + $days month"));
				$sql = "select * from reg_fees_structure where user_id='$user_id'";
				$sq = query_execute_sqli($sql);
				$nsq = mysqli_num_rows($sq);
				if($nsq > 0){
					while($rt = query_execute_sqli($sq)){
						$pos = $rt['position'];
					}
				}
				else{
					$pos = direct_member_position(real_parent($user_id),$user_id);
				}
				$rg_mode = 1;
				if($_POST['plty'] == 2){
					$rg_mode = 189;
				}
				$insert_sql = "insert into reg_fees_structure (user_id , rcw_id,request_crowd, update_fees , date ,start_date , profit , total_days , invest_type , plan , time,`count`,position,mode) values ('$user_id' , '0' ,'$pv', '$investment' , '$systems_date' , '$start_date',  '$profit' , '$days' , '$plan_id', 'x' , '$time','0',$pos,$rg_mode) ";
				query_execute_sqli($insert_sql);
				$insert_id = get_mysqli_insert_id();
				
				$sqk = "INSERT INTO `ledger`(`user_id`,`by_id`, `particular`, `cr`, `dr`, `balance`, 
						`date_time`) VALUES ('$user_id','$insert_id','System TOPUP FROM Company Wallet','0','$investment',(SELECT amount FROM wallet where id='$user_id'), '$systems_date_time')";
				query_execute_sqli($sqk);
				$sql = "update users set package='$pv' where id_user='$user_id'";
				query_execute_sqli($sql);
				/*insert_wallet_account($user_id , $user_id , $investment , $systems_date_time , $acount_type[20] ,$acount_type_desc[20], $mode=2 , get_wallet_amount($user_id),$wallet_type[1],$remarks = "System TOPUP");*/
				if($_POST['plty'] == 1){
					pair_point_calculation($user_id,$systems_date,false); // binary
					get_booster_income($user_id,$systems_date);
					get_booster_income(real_parent($user_id),$systems_date);
				}
				if(strtoupper($soft_chk) == "LIVE"){
					$username_log = get_user_name($id);
					$username = $username_log;
					$invest_amount = $investment;
					include("../function/logs_messages.php");
					$phone_no = get_user_phone($user_id);
					send_sms($phone_no,$message1);
					
					$title = "Topup Your ID";
					$to = get_user_email($user_id);
					$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $message1);	
					
					$invest_amount = $investment;
					$invest_plan = $daily_income_percent[$package_id][3];
					include("../function/logs_messages.php");
					data_logs($id,$data_log[11][0],$data_log[11][1],$log_type[5]);
					$income_log = $investment;
					$for = "New Investment";
					include("../function/logs_messages.php");
					data_logs($id,$data_log[8][0],$data_log[8][1],$log_type[4]);
				}
				
				{ echo "<B class='text-success'>Your request of investment has been completed successfully!</B>"; }
			}
			else{ ?> <script>window.location = "index.php?page=make_investment";</script> <?php }
		/*}
		else{ echo "<B class='text-danger'>Error : Please Enter Minimum Of &#36;".min($set_amount)." !!</B>"; }*/	
	}
	elseif($_POST['submit'] == 'Submit')
	{
		$u_name = $_REQUEST[user_name];
		$q = query_execute_sqli("select * from users where username = '$u_name' ");
		$num = mysqli_num_rows($q);
		
		if($num == 0){ echo "<B class='text-danger'>Please Enter right User Name!</B>"; }
		else
		{
			while($id_row = mysqli_fetch_array($q))
				$_SESSION['admin_make_invst_id'] = $id = $id_row['id_user'];
			$q = query_execute_sqli("select * from wallet where id = '$id' ");
			while($r = mysqli_fetch_array($q))
			{
				$wallet_amount = $r['amount'];
			} ?>
			<form name="invest" method="post" action="">
			<table class="table table-bordered">
				<thead>
				<tr><th colspan="5">Select Investment Package</th></tr>
				<tr>
					<th class="text-center">Sr. No.</th>
					<th class="text-center">Plan Name</th>
					<th class="text-center">Profit(%)</th>
					<th class="text-center">Investment Amount ($)</th>
					<th class="text-center">Days</th>
				</tr>
				</thead>
				<?php 
				$count = count($daily_income_percent);
				for($i = 0; $i < $count; $i++)
				{ ?>
					<tr class="text-center">
						<td><?=$daily_income_percent[$i][4]?></td>
						<td><?=$daily_income_percent[$i][3]?></td>
						<td><?=$daily_income_percent[$i][1]?></td>
						<td>&#36;<?=$daily_income_percent[$i][0]?></td>
						<td><?=$daily_income_percent[$i][9]?></td>
					</tr> <?php 
				} ?>
				<tr>
					<th class="text-center">Plan Type</th>
					<th class="text-left" colspan="4">
					<input type="radio" name="plty" value="1" checked="checked" />Normal&nbsp;&nbsp;
					<input type="radio" name="plty" value="2" />Basic
					</th>
				</tr>
				<tr class="text-center">     
					<th>Select Plan</th>  
					<td colspan="4" class="text-left">
						<select name="investment_amt" class="form-control">
							<?php
							$qu = query_execute_sqli("select * from plan_setting ");
							while($rrr = mysqli_fetch_array($qu))
							{ 
								$plan_name = $rrr['plan_name'];
								$plan_id = $rrr['id'];
								$amount = $rrr['amount'];
								?> <option value="<?=$amount; ?>"><?=$plan_name.' ('.$amount.')'; ?></option> <?php	
							}	
							?>		
						</select>
						<!--<input type="text"  name="investment_amt" id="invest_dd" class="form-control" />
						<span id="msg_info" class="text-danger">
							<?="Minimum Investment &#36; ".min($set_amount)?>
						</span>-->
					</td>    
				</tr>
				<tr>
					<td colspan="5" class="text-center">
						<input type="submit" name="submit" value="Make Investment" class="btn btn-info" />
					</td>
				</tr>
				<!--<tr>
					<td colspan="5">
						<a href="index.php?page=add_funds" class="btn btn-info">Add Funds</a>
					</td>
				</tr>-->
			</table>
			</form> <?php	
		}
	}
	else { echo "<B class='text-danger'>There are Some Conflict !</B>"; }
}
else
{ 
unset($_SESSION['make_investment']);
?>	
<form action="" method="post">
<table class="table table-bordered">
	<!--<tr><th colspan="2">Wallet Information</th></tr>-->
	<tr>
		<th>Enter Member UserName</th>
		<td><input type="text" name="user_name" class="form-control"/></td>	
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>	
<?php } ?>

