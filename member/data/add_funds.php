<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");
include("function/send_mail.php");

include("function/wallet_message.php");
include("function/check_income_condition.php");

$id = $_SESSION['mlmproject_user_id'];

if($_SESSION['ses'] == 1 and isset($_SESSION['ses']))
{
	if(isset($_POST['submit']))
	{
		unset($_SESSION['ses']);
		$_SESSION['ses'] = 0;
		$current_amount = $_REQUEST['curr_amnt'];
		$request_amount = $_REQUEST['amount'];
		$ref_no = $_REQUEST['ref_no'];
		$request_date= date('Y-m-d');
						
		print "<strong><center>Your request of ".$request_amount." &#36; has been Sent To Admin successfully!
	</center></strong>";
		
		query_execute_sqli("insert into add_funds (user_id ,email , amount , date , mode ) values ('$id' , '$neteller_mail' , '$request_amount' , '$request_date' , 0) "); 
		
	}
	else
	{
		$_SESSION['ses'] = 0;
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=add_funds\"";
		echo "</script>";
	}
}
else
{
	$_SESSION['ses'] = 1;
	$date = date('Y-m-d');
	$query = query_execute_sqli("select amount from wallet where id = '$id' ");
	while($row = mysqli_fetch_array($query))
	{
		$curr_amnt = $row[0];
	}	
	
	
	$msg = $_REQUEST['mg']; echo $msg; ?> 
	<table class="table table-bordered table-hover">
	<form name="money" action="index.php?page=add_fund" method="post">
	<input type="hidden" name="curr_amnt" value="<?=$curr_amnt; ?>"  />
	<thead>
	<tr>
		<th>Current Balance</th>
		<th><?=$curr_amnt." &#36; ";  ?></th>
	</tr>
	</thead>
	<tr>
		<td><h5 style="width:250px;">Request Amount :</h5></td>
		<td><input type="text" name="amount" class="input-small" required/> &#36; </td>
	</tr>
	<tr>
		<td><h5>Ref No :</h5></td>
		<td><input type="text" name="ref_no"  required/></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Request" class="btn btn-primary" />
		</td>   
	</tr>
	</form>
	</table>
<?php  
}   
	
?>