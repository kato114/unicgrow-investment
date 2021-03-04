<?php
//include '../config.php';
include '../function/functions.php';

$bin_query = query_execute_sqli("select t2.phone_no from (select * from $db.income UNION ALL select * from $db2.income) as t1 inner join users as t2 on t1.user_id = t2.id_user and t1.date = '$systems_date' and t1.type = 3 and t2.phone_no > 0 group by t2.phone_no order by user_id");
$num_bin_user = mysqli_fetch_array($bin_query);

if($num_bin_user > 0)
{
	while($row = mysqli_fetch_array($bin_query))
	{
		//$user_id = $row['user_id'];
		$phone = $row['phone_no'];
		//$phone = get_user_phone($user_id);
					
//		$message = "Dear Member, You have received ROI from ecocpaitalfx for username : $username , Amount : $amount ";
		$message = "Dear Member, You have received Binary Bonus from ecocpaitalfx for username : $username . Check your backoffice for more info. www.bitfinbull.com";
		send_sms($phone,$message);
	}
	print "Binary Income SMS Sent Successfully ";
}


$roi_query = query_execute_sqli("select t2.phone_no from (select * from $db.income UNION ALL select * from $db2.income) as t1 inner join users as t2 on t1.user_id = t2.id_user and t1.date = '$systems_date' and t1.type = 2 and t2.phone_no > 0 group by t2.phone_no order by user_id");
$num_roi_user = mysqli_fetch_array($roi_query);

if($num_roi_user > 0)
{
	while($row = mysqli_fetch_array($roi_query))
	{
		//$user_id = $row['user_id'];
		$phone = $row['phone_no'];
		//$phone = get_user_phone($user_id);
					
		$message = "Dear Member, You have received Daily Bonus from ecocpaitalfx for username : $username . Check your backoffice for more info. www.bitfinbull.com";
		send_sms($phone,$message);
	}
	print "Daily Income SMS Sent Successfully ";
}
?>