<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");
include("function/all_month.php");	
?>
<!--<h1 align="left">E-pin Logs</h1>-->
<?php
$user_id = $_SESSION['mlmproject_user_id'];
$query = query_execute_sqli("select * from logs where user_id = '$user_id' and type = '$log_type[4]' ");
$num = mysqli_num_rows($query);
if($num != 0)
{
	print "<table id=\"data-table\" class=\"display\" align=center hspace = 0 cellspacing=0 cellpadding=0 border=0 width=96%>
			<tr height=\"35\"><td width=200 class=\"message tip\"><strong>Date</strong></td>
				<td width=200 class=\"message tip\"><strong>Title</strong></td>
				<td width=200 class=\"message tip\"><strong>Massage</strong></td></tr>";
	while($row = mysqli_fetch_array($query))
	{
		$title = $row['title'];
		$message = $row['message'];
		$date = $row['date'];
		print  "<tr height=\"35\" class=\"odd\"><td width=200 style=\"padding-left:60px\"><small>$date</small></td>
					<td width=200 style=\"padding-left:80px\"><small>$title</small></td>
					<td width=200 style=\"padding-left:30px\"><small>$message</small></td></tr>";
	}
	print"</table>";	
}
else { print "<tr><td colspan=\"3\" width=200 class=\"td_title\">There is no logs !</td></tr></table>"; }


?>
