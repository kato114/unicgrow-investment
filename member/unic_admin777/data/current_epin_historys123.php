<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
$epin = $_GET['epin'];
?>

<table cellpadding="0" cellspacing="1" width="90%">
  <tr height="40px" class="text-center" style="color:#000000;">
		<th>Sr</th>
		<th>E-pin</th>
		<th>Amount</th>
		<th>Date</th>
		<th>Generate By</th>
		<th>User Id</th>
		<th>Transfer To</th>
		<th>Used By</th>
		<th>Used Date</th>
	</tr>
<?php	
	
$sr = 1;	
$sql = "select * from e_pin as t1 inner join epin_history as t2 on t1.id = t2.epin_id where t2.epin_id = '$epin'";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
	while($row = mysqli_fetch_array($query))
	{
		$id = $row['id'];
		$generate_id = $row['generate_id'];
		
		if($generate_id == 0){
			$generate_id = 'Admin';	
		}
		else{
			$generate_id = get_user_name($generate_id);
		}
		
		$owner = $row['transfer_to'];
		$transfer_id = $row['user_id'];
		if($transfer_id == $owner)
		{
			$transfer_id = 'No Transfer';
			$owner = get_user_name($owner);
		}
		else
		{
			$transfer_id = get_user_name($transfer_id);
			$owner = get_user_name($owner);
		}
		$used_id = get_user_name($row['used_id']);
		$used_date = $row['used_date'];
		if($used_id == '')
		{
			$used_id = "<font color=red>Unused</font>";
			$used_date = "<font color=red>No Date</font>";
		}
		$date = $row['date'];
		$epin = $row['epin'];
		$amount = $row['amount'];
		echo"<tr class=\"success\" height='30px' style=\"color:#000\">
				<th>$sr</th>
				<th>$epin</th>
				<th>$amount</th>
				<th>$date</th>
				<th>$generate_id</th>
				<th>$owner</th>
				<th>$transfer_id</th>
				<th>$used_id</th>
				<th>$used_date</th>
			</tr>";
		$sr++;
	}
?>				
	</table>
</form>	
<?php
?>