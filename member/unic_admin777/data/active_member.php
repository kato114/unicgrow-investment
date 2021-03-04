<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");


if(isset($_POST['submit']))
{
	$id = $_REQUEST[id];
	query_execute_sqli("UPDATE users SET type = 'A' WHERE id_user = '$id' ");
	$pos = get_user_position($id);
	data_logs($id,$pos,$data_log[11][0],$data_log[11][1],$log_type[8]);
}


$d =query_execute_sqli("SELECT * FROM users WHERE type = 'B' ");
$num = mysqli_num_rows($d);
if($num != 0)
{
print "

	<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=600>
	<tr><td class=\"td_title\"><strong>User Name</strong></td>
		<td class=\"td_title\"><strong>Name</strong></td>
		<td class=\"td_title\"><strong>Date</strong></td>
		<td class=\"td_title\"><strong>Action</strong></td></tr>";
	while($row = mysqli_fetch_array($d))
	{
		$id = $row['id_user'];
		$username = get_user_name($id);
		$name = $row['f_name']." ".$row['l_name'];
		$date = $row['date'];
		print "<tr><td class=\"td_title\">$username</td><td><small>$name<small></td><td><small>$date<small></td>
		<td>
			<form name=\"inact\" action=\"index.php?val=active_member&open=3\" method=\"post\">
			<input type=\"hidden\" name=\"id\" value=\"$id\" />
			<input type=\"submit\" name=\"submit\" value=\"Inactivate\" />
			</form>
		</td></tr>";
			
	}
	print "</table>";
}
else { print "There is no Information To Show!"; } 
?>
	