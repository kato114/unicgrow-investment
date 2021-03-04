<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("../config.php");
include("condition.php");

print "<table width=\"100%\" border=\"0\">";
		  


	$user_id = $_SESSION['mlmproject_user_id'];
	$query = query_execute_sqli("select * from e_pin where mode = 1 ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		print "<tr>
			<th class=\"message tip\">E-pin</th>
			<th class=\"message tip\">Date</th>
			<th class=\"message tip\">Product Id</th>
			<th class=\"message tip\">Used Id</th>
			<th class=\"message tip\">Used Date</th>
		  </tr>";
		while($row = mysqli_fetch_array($query))
		{
			$epin = $row['epin'];
			$date = $row['date'];
			$product_id = $row['product_id'];
			$used_id = $row['used_id'];
			$used_date = $row['used_date'];
			
			print "<tr>
					<td class=\"input-small\" style=\"padding-left:20px\"><small>$epin</small></td>
					<td class=\"input-small\" style=\"padding-left:20px\"><small>$date</small></td>
					<td class=\"input-small\" style=\"padding-left:30px\"><small>$product_id</small></td>
					<td class=\"input-small\" style=\"padding-left:30px\"><small>$used_id</small></td>
					<td class=\"input-small\" style=\"padding-left:20px\"><small>$used_date</small></td>
				  </tr>";
		}
	}
	else 
	{
		print "<tr>
					<td colspan=5>There is no E-pin to show !</td>		  
				</tr>";
	}
	print "</table>";
?>
