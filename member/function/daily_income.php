<?php
//include("../config.php"); 
//include("functions.php");
/*$systems_date = strtotime("2020-02-02");
$systems_date_time = "2020-02-02 00:00:00";
$e_date = strtotime(date("Y-m-d"));
while($systems_date <= $e_date){
	get_daily_income($systems_date_time);
	$systems_date_time = date("Y-m-d H:i:s",strtotime($systems_date_time." + 1 Day"));
	$systems_date = strtotime(date("Y-m-d",strtotime($systems_date_time)));
}*/
function get_daily_income($time)
{
	global $systems_date;
	global $systems_date_time;
	include("setting.php");
	$explo = explode(" ",$time);
	$date = $explo[0];
	$roi_time = $explo[1];
	//$date = date('y-m-d');
	$pay_day = date("D",strtotime($date));
	/*if($pay_day == "Sat" or $pay_day == "Sun"){
	print "<font size=5 color=\"#FF0000\">Investment Income Not Distributed On ".date("l",strtotime($date))." !!</font>"; 
		die();
	}*/
	$income_cnt = array();
	$sql = "select t1.user_id,count(t1.user_id) cnt from income t1 where t1.type = '$income_type[1]' group by t1.user_id";
	$query = query_execute_sqli($sql);
	while($r = mysqli_fetch_array($query))
	{
		$user_id = $r['user_id'];
		$income_cnt[$user_id] = $r['cnt'];
	}
	mysqli_free_result($query);
	$sql = "select t1.* from income t1 where DATE(t1.date) = '$date' and type = '$income_type[1]'";
	if(mysqli_num_rows(query_execute_sqli($sql)) == 0){
	 	$sql = "select t1.* from reg_fees_structure t1 where date < '$date' and mode=1 and plan not in('z') and t1.update_fees > 0 and `count` < `total_days` and invest_type not in(5,6)";//DATE_ADD(t1.date,INTERVAL (t1.count+1) WEEK)=
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		$done = 0;
		if($num > 0)
		{
			while($r = mysqli_fetch_array($query))
			{
				$user_id = $r['user_id'];
				$type = get_type_user($user_id);
				$count = $r['count'];
				if($type == 'B')
				{
					$update_fees = $r['update_fees'];
					$incomed_id = $table_id = $r['id'];
					
					$percent_roi = $r['profit'];
					$total_amount = $update_fees;
					$p_value = $r['plan'];
					$plan_idss = $r['invest_type'];
					$total_days = $r['total_days'];
					$income = $total_amount*($percent_roi/100);
					$inc_type = $income_type[1];
					
					if($income > 0){
						$done = 1;
						
						$incomes = $income;
						$sql = "insert into income (user_id , amount , tax , tds_tax , plan , type , date,incomed_id,mode) values ('$user_id' , '$incomes' , '$tax_amount1' , '$tax_amount2' , '$p_value' ,'$inc_type' , '$systems_date_time','$incomed_id','1') ";
						query_execute_sqli($sql);
						query_execute_sqli("update reg_fees_structure set `count` = `count` + 1 where id = '$table_id' ");
						$sql = "update reg_fees_structure set `mode` =2 where id = '$table_id' 
						and count=total_days";
						query_execute_sqli($sql);
						update_member_wallet($user_id,$incomes,$income_type[2]);
						insert_wallet_account($user_id , $user_id , $incomes , $systems_date_time , $acount_type[4] ,$acount_type_desc[4], $mode=1 , get_wallet_amount($user_id),$wallet_type[1],$remarks = "ROI INCOME");
						/*if($total_days == $count+1){
							update_member_wallet($user_id,$incomes,$income_type[2]);
							insert_wallet_account($user_id , $user_id , $total_amount , $systems_date_time , $acount_type[2] ,$acount_type_desc[2], $mode=1 , get_wallet_amount($user_id),$wallet_type[1],$remarks = "Principle Return");
						}*/
						//daily_level_income($user_id,$income,$table_id);
						/*if($soft_chk == "LIVE"):
							$to = get_user_email($user_id);
							$full_message = "Today ROI Receive successfully Thanks !! By https://www.unicgrow.com";
							$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $full_message);	
							$msg_roi = "Today ROI Receive successfully Thanks !! By https://www.unicgrow.com";
							$phone = get_user_phone($user_id);
							send_sms($phone,$msg_roi);
						endif;*/
					}
					
				}
				
			}
			mysqli_free_result($query);
			if($done == 1)
			print "Investment Income distributed Successfully ";
		}
		else { print "<font size=5 color=\"#FF0000\">There Are No User For Investment Income !!</font>"; }
	}
	else{
		print "<font size=5 color=\"#FF0000\">Investment Income Already Distributed !!</font>"; 
	}	
}

?>