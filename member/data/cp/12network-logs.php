<?php
session_start();
include("function/setting.php");
include("function/all_month.php");	
?>
<h2 align="left">Network History</h2>
<?php


$user_id = $_SESSION['mlmproject_user_id'];
$query = query_execute_sqli("select * from logs where user_id = '$user_id' and type = '$log_type[3]' ");
$num = mysqli_num_rows($query);
if($num != 0)
{
	print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=600>
			<tr><th width=200 class=\"message tip\"><strong>Date</strong></th>
				<th width=200 class=\"message tip\"><strong>Title</strong></th>
				<th width=200 class=\"message tip\"><strong>Massage</strong></th></tr>";
	while($row = mysqli_fetch_array($query))
	{
		$title = $row['title'];
		$message = $row['message'];
		$date = $row['date'];
		print  "<tr><td class=\"message success\" width=200 style=\"padding-left:80px\"><small>$date</small></td>
					<td class=\"message error\" width=200 style=\"padding-left:70px\"><small>$title</small></td>
					<td class=\"message success\" width=200 style=\"padding-left:45px\"><small>$message</small></td></tr>";
	}
	print"</table>";	
}
else { print "<tr><td colspan=\"3\" width=200 class=\"td_title\">There is no logs !</td></tr></table>"; }


?>

