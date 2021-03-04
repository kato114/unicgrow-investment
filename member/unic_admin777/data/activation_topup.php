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
	if($_POST['submit'] == 'Make Investment' and $_REQUEST['tpouppack'] > 0)
	{
		$user_id = $id = $_SESSION['admin_make_invst_id'];
		$tpouppack = $_REQUEST['tpouppack'];
		$process = 1;
		$wallet_amount = $investment;
		if(!isset($_SESSION['make_investment'])){
			$_SESSION['make_investment'] = 0;
			$date = $systems_date;
			$plan_id = "";
			$query1 = query_execute_sqli("select * from plan_setting where id = '$tpouppack' order by id desc limit 1");	
			while($rr = mysqli_fetch_array($query1))
			{
				$plan_id = $rr['id'];
				$days = $rr['days'];
				$pv = $rr['pv'];
				$profit = $rr['daily_profit']; 
				$investment = $invest_amount = $rr['amount'];
			}
			if($plan_id > 0){
				$package_id=$plan_id;
				$p_value = 64+$plan_id;
				$p_value = chr($p_value);
				$time = $systems_date_time;
				$start_date = date('Y-m-d', strtotime($systems_date . ' + 1 MONTH'));
				$end_date = date('Y-m-d', strtotime($systems_date . " + $days MONTH"));
			
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
				$insert_sql = "insert into reg_fees_structure (user_id , rcw_id,request_crowd, update_fees , date ,start_date , profit , total_days , invest_type , plan , time,`count`,position) values ('$user_id' , '0' , '$investment' ,'$investment' , '$systems_date' , '$start_date',  '$profit' , '$days' , '$plan_id', 'z' , '$systems_date_time','100',$pos) ";
				query_execute_sqli($insert_sql);
				
				if(strtoupper($soft_chk) == "LIVE"){
					$topup_username = get_user_name($id);
					$username = $topup_username;
					$invest_amount = $investment;
					$date = $systems_date;
					include("../function/logs_messages.php");
					$phone_no = get_user_phone($id);
					send_sms($phone_no,$message1);
					
					$invest_amount = $investment;
					$invest_plan = $daily_income_percent[$package_id][3];
					include("../function/logs_tool_messages.php");
					data_logs_tool($user_id,$data_log[2][0],$data_log[2][1],$log_type[2]);
				}
				echo "<B class='text-success'>Your request of investment has been completed successfully!</B>";
			}
		}
		else{ ?> <script>window.location = "index.php?page=activation_topup";</script> <?php }
		
			
	}
	elseif($_POST['submit'] == 'Submit')
	{
		$u_name = $_REQUEST[user_name];
		$q = query_execute_sqli("select * from users where username = '$u_name' ");
		$num = mysqli_num_rows($q);
		if($num == 0){ echo "<B class='text-danger'>Please Enter right User Name!</B>"; }
		else
		{
			while($id_row = mysqli_fetch_array($q)){
				$_SESSION['admin_make_invst_id'] = $id = $id_row['id_user'];
				$mem_type = $id_row['type'];
			}
			if($mem_type == 'B'){
				$q = query_execute_sqli("select * from wallet where id = '$id' ");
				while($r = mysqli_fetch_array($q))
				{
					$wallet_amount = $r['amount'];
				}	
				?>
				<form name="invest" method="post" action="">
				<table class="table table-bordered">
					<thead>
					<tr><th colspan="6">Select Investment Package</th></tr>
					<tr>
						<th class="text-center">Select Package</th>
						<th class="text-center">Sr. No.</th>
						<th class="text-center">Plan Name</th>
						<th class="text-center">Return(&#36;)</th>
						<th class="text-center">Investment Amount ($)</th>
						<th class="text-center">Month</th>
					</tr>
					</thead>
					<?php 
					$count = count($daily_income_percent);
					for($i = 0; $i < $count; $i++)
					{ ?>
						<tr class="text-center">
							<td><input type="radio" name="tpouppack" value="<?=$daily_income_percent[$i][4]?>" /></td>
							<td><?=$daily_income_percent[$i][4]?></td>
							<td><?=$daily_income_percent[$i][3]?></td>
							<td><?=$daily_income_percent[$i][1]?></td>
							<td>&#36;<?=$daily_income_percent[$i][0]?></td>
							<td><?=$daily_income_percent[$i][9]?></td>
						</tr> <?php 
					} ?>
					<tr>     
						<td colspan="6" class="text-center">
							<input type="submit" name="submit" value="Make Investment" class="btn btn-info"  />
						</td>    
					</tr>
					<!--<tr>
					<td colspan="5">
						<a href="index.php?page=add_funds" class="btn btn-info">Add Funds</a>
					</td>
				</tr>-->
				</table>
				</form>
<?php		}
			else{
				echo "<B style='color:#FF0000;'>Error : Member is Block !!";
			}
		}
	}
	else { print "there are Some Conflict !"; }
}
else
{ 
unset($_SESSION['make_investment']);
?>	
<form action="" method="post">
<table class="table table-bordered">
	<tr>
		<th>Enter Member UserName</th>
		<td><input type="text" name="user_name" class="form-control"/></td>	
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>	
	
<?php } ?>

