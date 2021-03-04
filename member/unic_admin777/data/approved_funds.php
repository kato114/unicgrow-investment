<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");



$id = $_SESSION['admin_id'];

	$query = query_execute_sqli("select * from paid_unpaid where paid = 1 and amount > 0 ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		print " 
					
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=100%>
			
			<tr><th class=\"message tip\"><strong>User Name</strong></th>
			<th class=\"message tip\"><strong>Request Amount</strong></th></th>
			<th class=\"message tip\"><strong>Date</strong></th></tr>";
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$u_id = $row['user_id'];
			$username = get_user_name($u_id);
			$request_amount = $row['amount'];
			$paid_date = $row['paid_date'];
			print "<tr><td class=\"input-medium\" style=\"padding-left:95px\">$username</td><td class=\"input-medium\" style=\"padding-left:65px\"><small>$request_amount RC</small></td><td class=\"input-medium\" style=\"padding-left:75px\"><small>$paid_date</small></td></tr>";
		}
		print "</table>";	
	}
	else{ print "There is no fund for approved !"; }
?>

