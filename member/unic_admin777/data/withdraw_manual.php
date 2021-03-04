<?php
include('../../security_web_validation.php');

include('condition.php');
include("../function/functions.php");
include("../function/setting.php");
include("../function/send_mail.php");

if(isset($_POST['manual_withdrawal']))
{
	$withdra_amount = $_POST['withdra_amount'];
	$user_id = $_POST['user_id'];
	
	$sql = "SELECT * FROM wallet where amount > ".$withdra_amount." AND id = '$user_id'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$decs_mode = "Bank";
			$withdrwal_money_tax = $withdrwal_money_tax+$tds;
			$request_amount = $withdra_amount - ($withdra_amount*$withdrwal_money_tax/100);
			
			if(query_execute_sqli("INSERT INTO `withdrawal_crown_wallet`(`ac_type`,`user_id`, `amount`, `request_crowd`, `description`,`date`,`tax`,`cur_bitcoin_value`,status,user_comment,api_description,transaction_hash,accept_date,action_date) 
					VALUES ('1','$user_id','".$withdra_amount."','$request_amount' , '$decs_mode  Withdrawal', '$systems_date_time' , '$withdrwal_money_tax','0', '65' , 'Manual Withdrawal', 'xxxxxxxxxx', 'xxxxxxxxxx', '$systems_date_time', '$systems_date_time')"))
			{
				query_execute_sqli("UPDATE wallet SET amount = amount-".$withdra_amount." WHERE id = '$user_id' ");
				
				insert_wallet_account($user_id , $user_id , $withdra_amount , $systems_date , $acount_type[15] ,$acount_type_desc[15], $mode=2 , get_user_allwallet($user_id,'amount'),$wallet_type[1],$remarks = "Manual Withdrawal");
				
				$username = get_user_name($user_id);
				$mesgs = "Hi $username, Your Withdrawal Completed Successfully. Thanks https://www.unicgrow.com";
				send_sms(get_user_phone($user_id),$mesgs);
				
				$title = "Withdrawal By Admin";
				$to = get_user_email($user_id);
				$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to, $title, $mesgs);
			}
		} 
		?> 
		<script>
			alert("Withdrawal Completed Successfully !!"); 
			window.location = "index.php?page=withdraw_manual";
		</script> <?php
	}
	else{ echo "<B class='text-danger'>Please enter correct amount for Withdrawal !!</B>"; }
}


if(isset($_POST['submit']))
{
	$u_name = $_POST['user_name'];
	
	$query = query_execute_sqli("SELECT * FROM users WHERE username = '$u_name' ");
	$num = mysqli_num_rows($query);
	if($num == 0){ echo "<B class='text-danger'>Please Enter right Username! </B>"; }
	else
	{
		while($row = mysqli_fetch_array($query))
		{
			$user_id = $row['id_user'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$email = $row['email'];
			$phone_no = $row['phone_no'];
			
			$beneficiery_name = $row['beneficiery_name'];
			$bank_ac = $row['bank_ac'];
			$bank_name = $row['bank_name'];
			$ifsc_code = $row['ifsc_code'];
			
			$wal_amt = get_user_allwallet($user_id,'amount');
			
			$kyc_approve = kyc_approved_or_not($user_id);
		} 
		if($kyc_approve == 0){
		?>
			<form action="index.php?page=withdraw_manual" method="post">
			<input type="hidden" name="user_id" value="<?=$user_id?>" />
			<table class="table table-bordered">
				<tr><th width="20%">Wallet Amount</th>	<td><?=$wal_amt?> &#36;</td></tr>
				<tr><th>Name</th>				<td><?=$name?></td></tr>
				<tr><th>E-Mail</th>				<td><?=$email?></td></tr>
				<tr><th>Phone Number</th>		<td><?=$phone_no?></td></tr>
				
				<tr><th>Customer Name</th>		<td><?=$beneficiery_name?></td></tr>
				<tr><th>Account No.</th>		<td><?=$bank_ac?></td></tr>
				<tr><th>Bank Name</th>			<td><?=$bank_name?></td></tr>
				<tr><th>IFSC Code</th>			<td><?=$ifsc_code?></td></tr>
				<tr>
					<th>Withdraw Amount</th>
					<td><input type="text" name="withdra_amount" class="form-control" onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')" required /></td>
				</tr>
				<tr>
					<td colspan="2" class="text-center">	
						<input type="submit" name="manual_withdrawal" value="Pay" class="btn btn-primary" onclick="javascript:return confirm(&quot; Are You Sure? &quot;)" />
					</td>
				</tr>
			</table>
			</form> <?php
		}
		else{ echo "<B class='text-danger'>Please Add your KYC Details first !!</B>"; }	
	}	
}	
else
{ ?> 

<form action="" method="post">
<table class="table table-bordered">
	<tr><thead><th colspan="3">Enter Information</th></thead></tr>
	<tr>
		<th>Enter Member UserName</th>
		<td><input type="text" name="user_name" class="form-control" /></td>
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>
<?php  }  ?>
