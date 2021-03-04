<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
$login_id = $_SESSION['mlmproject_user_id'];

if(isset($_SESSION['msg_refund']))
{
	echo $_SESSION['msg_refund'];
	unset($_SESSION['msg_refund']);
}

if(isset($_REQUEST['refund']))
{
	$invest_id = $_REQUEST['invest_id'];
	$btc_address = $_REQUEST['btc_address'];
	$date = date('Y-m-d H:i:s');
	
	if($btc_address != '')	
	{
		$SQL = "SELECT * FROM request_crown_wallet WHERE id = '$invest_id' and user_id = '$login_id'";
		$query = query_execute_sqli($SQL);
		while($row = mysqli_fetch_array($query))
		{
			$inv_amt = $row['investment'];
		}
		
		$refund_exist = refund_exist($login_id,$invest_id);
		if($refund_exist == 0)
		{
		
			$sql = "INSERT INTO investment_refund(rcw_id , user_id , amount , btc_address , date) 
			VALUES ('$invest_id', '$login_id', '$inv_amt', '$btc_address' , '$date')";
			query_execute_sqli($sql);	
			$sql = "UPDATE request_crown_wallet set `status` = '3', `action_date` = '$systems_time' 
			where `id`='$invest_id' and `status`=0";	
			query_execute_sqli($sql);	
			$_SESSION['msg_refund'] = "<B style='color:#008000;'>Refund Request Successfully Created !!</B>";
				
			echo "<script>window.location='index.php?page=refund_request'</script>";
		}
		else { echo "<B class='text-danger'>This refund request is already in system !!</B>"; }
	}
	else { echo "<B class='text-danger'>Please Fill All Field !</B>"; }
}


$SQLK = "SELECT * FROM request_crown_wallet WHERE user_id = '$login_id' and status = 0";
$query = query_execute_sqli($SQLK);
$tot_row = mysqli_num_rows($query);
if($tot_row > 0)
{ ?>
	<form method="post" action="">
	<table class="table table-bordered table-hover">
		<tr>
			<th>My Mining Space Amount</th>
			<td>
				<select name="invest_id" class="form-control" style="width:30%;">
					<option value="">Select My Mining Space</option>
				<?php 
				while($ro = mysqli_fetch_array($query))
				{
					$id = $ro['id'];
					$investment = $ro['investment'];
				?>
					<option value="<?=$id?>"><?=$investment?></option>
				<?php
				} ?>	
				</select>
			</td>
		</tr>
		<tr>
			<th>Bitcoin Address</th>
			<td><input type="text" name="btc_address" /></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="refund" value="Refund" class="btn btn-info" />
			</td>
		</tr>
		<tr><th colspan="2" style="color:#FF0000">Note : &#3647 100 will be deducted from your wallet</th></tr>
	</table>
	</form> <?php
}
else{ echo "<B class='text-danger'>No Investment Found !!</B>"; }

?>