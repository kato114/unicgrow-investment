<?php
include('../../security_web_validation.php');

include("condition.php");
include("../function/functions.php");
include("../function/setting.php");


if(isset($_POST['submit']))
{
	$username = $_REQUEST['username'];
	$amount = $_REQUEST['amount'];
	$wall_type = $_REQUEST['wall_type'];
	$remarks = $_REQUEST['remarks'];
	$date = date('Y-m-d');
	
	$user_id = get_new_user_id($username);
	
	$field = $wall_type == 1 ? "amount" :  "activationw";
	$wall_types = $wall_type == 1 ? $wallet_type[1] : $wallet_type[2];
	//$wallet_balance = get_user_allwallet($user_id,$field);
	
	if($user_id != 0){
		//if($wallet_balance >= $amount){
			$SQL = "UPDATE wallet SET $field = (SELECT $field +'$amount') , date = '$date' where id = '$user_id'";
			query_execute_sqli($SQL);
			if(query_affected_rows() > 0){
				$wallet_balance = get_user_allwallet($user_id,$field);
				insert_wallet_account($user_id , $user_id , $amount ,$systems_date_time , $acount_type[21] , $acount_type_desc[21], 1 , $wallet_balance , $wall_types , $remarks);
				?>
				<script>alert("Amount Added Successfully !"); window.location = "index.php?page=<?=$val?>";</script> 
				<?php
			}
			else{
				echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>";
			}
		/*}
		else{ ?> <script>alert("Low Balance !"); window.location = "index.php?page=<?=$val?>";</script> <?php }*/
	}
	else { 
		?>
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
	<!--<tr>
		<th>Currency</th>
		<td><input type="radio" name="curr_type" class="curr_type" value="2" checked="checked" /> USD</td>
	</tr>-->
	<tr>
		<th>Enter Username</th>
		<td>
			<div class="pull-left">
				<input type="text" name="username" id="search_username" class="form-control" value="<?=$username?>" required />
			</div>
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
		<th>Amount</th>
		<td>
			<div class="pull-left">
				<input type="text" name="amount" id="invest_dd" class="form-control" required />
			</div> &nbsp;<span id="main_walletf"></span> 
		</td>
	</tr>
	<!--<tr id="genpadinr">      
		<td>Amount(USD)</td>  
		<td><span id="genpaad">0</span></td>    
	</tr> -->
	
	<tr>
		<th>Remarks </th>
		<td><textarea name="remarks" class="form-control"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Add Balance" class="btn btn-info" />
		</td>
	</tr>
</table>
</form>
<?php } ?>

