<?php
include("../config.php");
include("../function/functions.php");
include("../function/setting.php");



print $ioi = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];


if(isset($_POST['submit']))
{
	$d = query_execute_sqli("select * from users where type = 'C' ");
	$num = mysqli_num_rows($d);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($d))
		{
			
			$id_user = $row['id_user'];
			$username = get_user_name($id_user);
			print "user : ".$username."<br>";
			query_execute_sqli("update wallet set amount = 0 where id = '$id_user' ");
			query_execute_sqli("update reg_fees_structure set reg_fees = 0 , update_fees = 0 , total_days = 0 where user_id = '$id_user' ");
		}
	}	
		
		
}

?>
<form name="zero_investment" method="post" action="block_member_investment_zero.php">
<table width="200" border="0">
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2"><input type="submit" value="Submit" name="submit" /> </td></tr>
</table>
</form>	
