<?php
include('../security_web_validation.php');

include("condition.php");
include("function/setting.php");
//include("function/send_mail.php");
//include("function/wallet_message.php");

$login_id = $id = $_SESSION['mlmproject_user_id'];
$position = $_SESSION['position'];
if(isset($_POST['submit']))
{
	$user_pin = $_REQUEST['user_pin'];
	$request_pin = $_REQUEST['request_pin'];
	$requested_user = $_REQUEST['requested_user'];
	$requested_user_id = get_new_user_id($requested_user);
	
	if($requested_user_id == 0){
		echo "<B class='text-danger'>Please Enter correct Username !</B>";
	}
	else
	{	
		$query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$id' AND password = '$user_pin' ");
		$num = mysqli_num_rows($query);
		if($num > 0)
		{
			$left_amount = $current_amount-$request_amount;
			$query = query_execute_sqli("SELECT * FROM e_pin WHERE user_id = '$id' AND mode = 1 AND epin='$request_pin'");
			$pin_num = mysqli_num_rows($query);
			if($pin_num > 0)
			{
				while($row = mysqli_fetch_array($query))
				{
					$epin_id = $row['id'];
				}	
				$request_date= date('Y-m-d');
				
				$sqls = "UPDATE e_pin SET user_id='$requested_user_id', date='$request_date' WHERE id='$epin_id'";
				query_execute_sqli($sqls);
				
				$qus = "SELECT * FROM e_pin t1 
				INNER JOIN epin_history t2 ON t1.id = t2.epin_id AND epin = '$request_pin' ";
				$query_epin = query_execute_sqli($qus);
				while($rok = mysqli_fetch_array($query_epin))
				{
					$epin_new_id = $rok['id'];
					$generate_id = $rok['generate_id'];
					$transfer_to = $rok['transfer_to'];
				}
				
				$SQLK = "INSERT INTO epin_history (epin_id, generate_id , user_id ,transfer_to, date) 
				VALUES ('$epin_new_id' , '$generate_id' , '$login_id' ,'$requested_user_id', '$request_date')";
				query_execute_sqli($SQLK);
				
				
				echo "<B class='text-success'>You request of transfer E-pin ".$request_pin." has completed successfully!</B>";
				/*$date = date('Y-m-d');
				$username_log = get_user_name($id);
				$transfer_log_name = get_user_name($requested_user_id);
				$epin_name = $request_pin;
				include("function/logs_messages.php");
				data_logs($id,$data_log[7][0],$data_log[7][1],$log_type[7]);*/
			}	
			else{ echo "<B class='text-danger'>Please Enter Correct E-pin to Transfer !!</B>"; }
		}
		else{ echo "<B class='text-danger'>Please enter correct Transaction Password !!</B>"; }
	}		
}
else
{
	//$SQL = "SELECT count(mode) reg,(SELECT count(mode) topup FROM `e_pin` WHERE `user_id`='$login_id' && mode='1') topup FROM `e_pin` WHERE `user_id`='$id' && mode='2' ";
	$SQL = "SELECT * FROM e_pin WHERE user_id ='$login_id' AND mode='1'";
	$query = query_execute_sqli($SQL);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$tot_reg_pin = $row['reg'];
			$tot_topup_pin = $row['topup'];
		}
	 	?> 
		<!--<table class="table table-bordered table-hover">
			<thead>
			<tr>
				<th class="text-center">Total Top-up E-pin</th>
				<th class="text-center"><?=$tot_topup_pin?></th>
				<td class="text-center">
					<?php if($tot_topup_pin > 0){?>
					<form method="post" action="index.php?page=unused_pin">
						<input type="submit" value="Transfer Topup Pin" class="btn btn-primary">
					</form>
					<?php }?>
				</td>
			</tr>
		</table>-->

		<table class="table table-bordered table-hover">
		<form action="index.php?page=pin-transfer-to-member" method="post">
			<tr><th width="25%">Your Total Unused Pin is</th> <th><?=$num?></th></tr>
			<tr>
				<th>E-pin</th>
				<td><input type="text" name="request_pin" class="form-control" /></td>
			</tr>
			<tr>
				<th>Transfer To</th>
				<td><input type="text" name="requested_user" class="form-control" /></td>
			</tr>
			<tr>
				<th>Transaction Password</th>
				<td><input type="text" name="user_pin" class="form-control" /></td>
			</tr>
			<tr>
				<td colspan="2" class="text-center">
					<input type="submit" name="submit" value="Request"  class="btn btn-primary" />
				</td>   
			</tr>
		</form>
		</table>
		<?php 
	}
	else{ echo "<B class='text-danger'>You Have No Unused E-pin to transfer !</B>"; }
}  ?>