<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

if(isset($_POST['submit']) and ($_SESSION['intrade_admin_login'] == 1))
{
	$username = $_POST['username'];
	$amount = $_POST['amount'];
	$wall_type = $_POST['wall_type'];
	$remarks = $_POST['remarks'];
	
	$field = $wall_type == 1 ? "amount" :  "activationw";
	$wall_types = $wall_type == 1 ? $wallet_type[1] : $wallet_type[2];

	
	$user_id = get_new_user_id($username);
	$wallet_balance = get_user_allwallet($user_id,$field);
	
	if($user_id != 0)
	{
		if($wallet_balance >= $amount)
		{
			$date = date('Y-m-d');
			
			$SQL="UPDATE wallet SET $field = (SELECT $field -'$amount') , date = '$date' where id = '$user_id'";
			//$SQL = "UPDATE wallet set amount = (SELECT amount -'$amount') , date = '$date' where id = '$user_id'";
			query_execute_sqli($SQL);
			if(query_affected_rows() > 0){
				$wallet_balance = get_user_allwallet($user_id,$field);
				
				insert_wallet_account($user_id , $user_id , $amount ,$systems_date_time , $acount_type[22] , $acount_type_desc[22], 2 , $wallet_balance , $wall_types , $remarks);
				
				?>
				<script>alert("Amount Deduct Successfully !"); window.location = "index.php?page=<?=$val?>";</script> 
				<?php
			}
			else{
				echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>";
			}
		}
		else{ ?> <script>alert("Low Balance !"); window.location = "index.php?page=<?=$val?>";</script> <?php }
	}
	else{ ?>
		<script>alert("Please Enter correct username !"); window.location = "index.php?page=<?=$val?>";</script> 
		<?php
	}
}
else
{
	$username = $_REQUEST['username'];
?>
<script>
$(document).ready(function() {	
	$("#search_username").keyup(function (e) {
		//removes spaces from username
		
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_username = $(this).val();
		if(sponsor_username.length < 3){$("#user-result").html('');return;}
		
		if(sponsor_username.length >= 3){
		
			$("#user-result").html('Lodding...');
			$.post('../check_username.php', {'search_username':sponsor_username},function(data)
			{
			  $("#user-result").html(data);
			});
		}
	});	
});		
</script>
<form action="" method="post">
<table class="table table-bordered">
	<tr>
		<th width="20%">Enter Username</th>
		<td>
			<div class="pull-left">
				<input type="text" name="username" id="search_username" value="<?=$_REQUEST['username']?>" class="form-control" required />
			</div>&nbsp;&nbsp;
			<span id="user-result"></span>
		</td>
	</tr>
	<tr>
		<th>Wallet Type</th>
		<td>
			<select name="wall_type" class="form-control" required>
				<!--<option value="1">Token Wallet</option>-->
				<option value="2">Deposit Wallet</option>
				<!--<option value="3">Tora Global Share</option>
				<option value="4">SMG Share</option>-->
			</select>
		</td>
	</tr>
	<tr>
		<th>Deduct Amount</th>
		<td>
			<div class="pull-left">
				<input type="text" name="amount" class="form-control" required />
			</div>
		</td>
	</tr>
	
	<tr>
		<th>Remarks</th>
		<td><textarea name="remarks" class="form-control"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Submit" class="btn btn-info" />
		</td>
	</tr>
</table>
</form>
<?php } ?>

