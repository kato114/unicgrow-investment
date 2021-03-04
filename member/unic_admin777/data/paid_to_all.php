<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/setting.php");
print	$sqli	= $_SESSION['sql_transfer_to_franchise'];
	$result = query_execute_sqli($sqli);
	$num = mysqli_num_rows($result);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($result))
		{
			$paid_id = $row['id_user'];
			$inc_date = $row['roi_date'];
			$amount = $row['total_inc'];
		print	"<br>".$sql_update = "update daily_income set paid = 1 where user_id = '$paid_id' and paid='0' 
							and date='$inc_date'";
			query_execute_sqli($sql_update);
		
			query_execute_sqli("insert into account (user_id , dr , date , account) 
			values ('$paid_id' , '$amount' , '$inc_date' , 'ROI PAID')");
		}
	}
	print "All Roi Paid Successfully";
	/*echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=show_daily_income\"";
	echo "</script>";*/
?>
<a href="index.php?page=show_daily_income">Back</a>