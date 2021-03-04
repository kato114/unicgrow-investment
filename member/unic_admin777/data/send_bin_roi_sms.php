<?php
include('../../security_web_validation.php');
?>
<?php
//include '../config.php';
include '../function/functions.php';

$bin_query = query_execute_sqli("select t2.phone_no from income as t1 inner join users as t2 on t1.user_id = t2.id_user and t1.date = '$systems_date' and t1.type = 3 and t2.phone_no > 0 group by t2.phone_no order by user_id");
$num_bin_user = mysqli_num_rows($bin_query);

if($num_bin_user > 0)
{
	while($row = mysqli_fetch_array($bin_query))
	{
		//$user_id = $row['user_id'];
		$phone = $row['phone_no'];
		//$phone = get_user_phone($user_id);
					
//		$message = "Dear Member, You have received ROI from ecocpaitalfx for username : $username , Amount : $amount ";
		$message = "Dear Member, Binary Bonus for username : $username is credited successfully to wallet. Keep active with unicgrow.com";
		send_sms($phone,$message);
	}
	print "<p></p>Binary Income SMS Sent Successfully $num_bin_user<br />";
}
else
{
	print "<p></p>There Are No User For Binary SMS<br />";
}

$roi_query = query_execute_sqli("select t2.phone_no,t2.user_id from income as t1 inner join users as t2 on t1.user_id = t2.id_user and t1.date = '$systems_date' and t1.type = 2 and t2.phone_no > 0 group by t2.phone_no order by user_id");
$num_roi_user = mysqli_num_rows($roi_query);

if($num_roi_user > 0)
{
	$i = 1;
	while($row = mysqli_fetch_array($roi_query))
	{
		$user_id = $row['user_id'];
		$sql = "select * from daily_income where user_id='$user_id'";
		$result = query_execute_sqli($sql);
		$num = mysqli_num_rows($result);
		$phone = $row['phone_no'];
		if($num == 0)
		{
			$date = date("Y-m-d");
			$message = "Dear Member Your Daily Bouns has been started From  ".$date." And successfully credited to your account for account $username . Logon to your backoffice for cash/ bank transfers. www.canindia.co.in";
		}
		else
		{
			$message = "Dear Member, Daily Bonus is successfully credited to your account for account $username . Logon to your backoffice for cash/ bank transfers. www.canindia.co.in";
		}
		
		//$phone = get_user_phone($user_id);
		send_sms($phone,$message);
	}
	print "<p></p>Daily Income SMS Sent Successfully $num_roi_user ";
}
else
{
	print "<p></p>There Are No User For Daily SMS";
}
?>