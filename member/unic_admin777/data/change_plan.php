<?php
include('../../security_web_validation.php');
session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/pair_point_calc.php");
?>
<script>$(document).ready(function() {	
	$("#change_username").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var change_username = $(this).val();
		if(change_username.length < 3){$("#user-result").html('');return;}
		
		if(change_username.length >= 3){
			$("#user-result").html('<img src="img/ajax-loader.gif" />');
			$.post('../check_username.php', {'username_search':change_username},function(data)
			{
			  $("#user-result").html(data);
			});
		}
	});	
});		
</script>
 
<?php
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Submit' or $_POST['submit'] == 'Profile')
	{
		$u_name = $_REQUEST['user_name'];
		$query = query_execute_sqli("SELECT id_user FROM users WHERE username = '$u_name' ");
		$num = mysqli_num_rows($query);
		
		if($num == 0){ echo "<B class='text-danger'>Please Enter right User Name!</B>"; }
		else{
			$row = mysqli_fetch_array($query);
			$id_user = $row[0];

			$sql = "SELECT * FROM reg_fees_structure WHERE `count` < `total_days` AND mode = 1 
			AND user_id = '$id_user' ORDER BY id DESC LIMIT 1 ";	
			$que = query_execute_sqli($sql);
			$query = query_execute_sqli($sql);
			$num = mysqli_num_rows($que);	
			$row = mysqli_fetch_array($query);
			$boost_id = $row['boost_id'];
			
			if($num > 0){
				if($boost_id == 0){ ?>
					<table class="table table-bordered">
						<?php
						$k = 1;
						while($orr = mysqli_fetch_array($que)){
							$table_id = $orr['id'];
							$p = $orr['invest_type'];
							$amount = $orr['update_fees'];
							$profit = $orr['profit'];
							$date = $orr['date'];
							$rcw_id = $orr['rcw_id'];
							$start_date = $orr['start_date'];
							$total_days = $orr['total_days'];
							$count = $orr['count'];
							$by_wallet = $orr['by_wallet'];
							$remarks = $orr['remarks'];
						} ?>
						<form action="" method="post">
						<input type="hidden" name="table_id" value="<?=$table_id?>" />
						<input type="hidden" name="user_id" value="<?=$id_user?>" />
						<input type="hidden" name="rcw_id" value="<?=$rcw_id?>" />
						<input type="hidden" name="start_date" value="<?=$start_date?>" />
						<input type="hidden" name="total_days" value="<?=$total_days?>" />
						<input type="hidden" name="count" value="<?=$count?>" />
						<input type="hidden" name="by_wallet" value="<?=$by_wallet?>" />
						<input type="hidden" name="remarks" value="<?=$remarks?>" />
						<tr><th width="30%">UserId</th> <td><?=$u_name?></td></tr>
						<tr><th>Packages</th> <td><?=$plan_name[$p-1]?></td></tr>
						<tr><th>Packages Amount</th> <td><?=$amount?> &#36;</td></tr>
						<tr><th>Packages Profit</th> <td><?=$profit?> &#36;</td></tr>
						<tr><th>Packages Date</th> <td><?=$date?></td></tr>
						<?php
						if($p < count($plan_name))
						{ ?>
						<tr>
							<th>Set New Packages</th> 
							<td>
								<select name="change_plan" class="form-control">
								<?php
								for($j = 0; $j < count($plan_name); $j++){
									if(($p-1) >= ($j))continue; ?>
									<option value="<?=$plan_id[$j]?>"><?=$plan_name[$j]?></option> <?php
								}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="text-center">
								<input type="submit" name="submit" value="Change" class="btn btn-info" />
							</td>
						</tr> <?php
						} ?>
						</form>
					</table> <?php	
				}	
				else{ echo "<B class='text-danger'>Booster User ('$u_name') can't change plan !!</B>"; }	
			}
			else{ echo "<B class='text-danger'>Member '$u_name' Have Not Active Investment !!</B>"; }			
		}
	}		
	elseif($_POST['submit'] == 'Change')
	{
		$table_id = $_POST['table_id'];
		$user_id = $_POST['user_id'];
		$plan_id = $_POST['change_plan'];
		$rcw_id = $_POST['rcw_id'];
		$start_date = $_POST['start_date'];
		$total_days = $_POST['total_days'];
		$count = $_POST['count'];
		$by_wallet = $_POST['by_wallet'];
		$remarks = $_POST['remarks'];
		
		if($plan_id > 0){
			$qr = query_execute_sqli("SELECT * FROM plan_setting WHERE id = '$plan_id'");
			while($rr = mysqli_fetch_array($qr))
			{
				$plan_id = $rr['id'];
				$days = $rr['days'];
				$profit = $rr['daily_profit']; 	
				$inv_amount = $rr['amount'];
				$pv = $rr['pv'];
				$p_value = chr(64+$plan_id);
				$log_plan_change = $plan_name[$plan_id-1];
			}
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
			$insert_sql = "INSERT INTO reg_fees_structure (user_id , rcw_id,request_crowd, update_fees , date ,start_date , profit , total_days , invest_type , plan , time,`count`,by_wallet,remarks,position) 
			VALUES ('$user_id' , '$rcw_id' , '$pv' , '$inv_amount' , '$systems_date' , '$start_date', '$profit' , '$total_days' , '$plan_id', '$p_value' , '$systems_date_time','$count','1','Plan Change By Admin',$pos) ";
			query_execute_sqli($insert_sql);
			

			$sql = "UPDATE reg_fees_structure SET mode = '100' WHERE id = '$table_id'";
			query_execute_sqli($sql);

			?> <script>alert('Plan updated Successfully !');
			window.location = 'index.php?page=change_plan'</script> <?php
		}
		else{ ?> <script>window.location = "index.php?page=change_plan";</script> <?php }
	}
}	
else
{ ?> 
<form name="my_form" action="index.php?page=change_plan" method="post">
<table class="table table-bordered">
	<thead><tr><th colspan="3">Enter Information</th></tr></thead>
	<tr>
		<th width="35%">Enter Username <span id="user-result"></span></th>
		<td><input type="text" name="user_name" size="3" class="form-control" id="change_username" /></td>
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>
<?php  }  ?>

