<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/functions.php");

print "<table width=\"700\" border=\"0\">";
		  


	$user_id = $_SESSION['mlmproject_user_id'];
	$query = query_execute_sqli("select * from callback_request ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		print "<tr>
			<th class=\"message tip\">User Id</th>
			<th class=\"message tip\">Title</th>
			<th class=\"message tip\">Message</th>
			<th class=\"message tip\">Date</th>
		  </tr>";
		while($row = mysqli_fetch_array($query))
		{
			$title = $row['title'];
			$date = $row['date'];
			$message = $row['message'];
			$user_id = get_user_name($row['user_id']);
			
			print "<tr>
					<td class=\"input-small\" style=\"padding-left:20px\"><small>$user_id</small></td>
					<td class=\"input-small\" style=\"padding-left:20px\"><small>$title</small></td>
					<td class=\"input-small\" style=\"padding-left:30px\"><small>$message</small></td>
					<td class=\"input-small\" style=\"padding-left:20px\"><small>$date</small></td>
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
