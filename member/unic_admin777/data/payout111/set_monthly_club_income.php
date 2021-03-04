<?php
include("../../../config.php");
include("../../../function/setting.php");
include("../../../function/functions.php");
include("../../../function/direct_income.php");
include("../../../function/send_mail.php");
/*$ss_date = date("Y-m-d",strtotime('2020-07-30'));
$ee_date = date("Y-m-d",strtotime('2020-11-21'));
while($ss_date  <= $ee_date){
	$systems_date = $ss_date;*/


$date = $systems_date;
$day = date("d",strtotime($date));
$s_date = date("Y-m-01",strtotime($date." -1 DAYS"));
$e_date = date("Y-m-t",strtotime($s_date));
$is_date = date("Y-m-01",strtotime($date));
$ie_date = date("Y-m-t",strtotime($is_date));

$sql = "SELECT * FROM income where type = '3' and `date` between '$is_date' and '$ie_date' ";

$quer = query_execute_sqli($sql);
$num = mysqli_num_rows($quer);
if($day == 1 and $num == 0){
	 $sql = "select COALESCE(sum(request_crowd),0) request_crowd from reg_fees_structure 
			where mode=1 and `date` between '$s_date' and '$e_date' and boost_id=0 and plan not in('x','y','z') 
			having request_crowd > 0";
	$quer = query_execute_sqli($sql);
	$num = mysqli_num_rows($quer);
	if($num > 0){
		while($row = mysqli_fetch_array($quer)){
			$business = $row['request_crowd'];
		}
		$club_business = $share_percent_member = $plan_club_name = array();
		$sql = "select * from plan_club order by id desc";
		$quer = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($quer)){
			$club_business[] = $row['request_crowd'];
			$matching_business_share_percent[] = $row['incentive'];
			$plan_club_name[] = $row['cname'];
		}
		$sql = "select count(*) cnt,matching_qualification from month_pair_point 
				where `matching_qualification` > 0 and `date` between '$is_date' and '$ie_date'
				group by matching_qualification order by matching_qualification asc";
		$quer = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($quer)){
			$qualification = $row['matching_qualification'];
			$num_qualification_member[$qualification] = $row['cnt'];
		}
		$sql = "select t1.*	from month_pair_point t1 
				inner join users t2 on t1.user_id = t2.id_user
				where t1.`matching_qualification` > 0 and t2.type='B' and t1.`date` between '$is_date' and '$ie_date'";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num > 0){
			
			while($row = mysqli_fetch_array($query)){
				$user_id = $row['user_id'];
				$club_type = $row['matching_qualification'];
				$percent = $matching_business_share_percent[3-$club_type]/$num_qualification_member[$club_type];
				$income = $business * $percent / 100;
				$clb_name = $plan_club_name[3-$club_type];
				if($income > 0){
					$incomes = $income;// - ($income * $setting_withdrawal_tax/100);
					$itax = 0;//($income * $setting_withdrawal_tax/100);
					$sql = "insert into income (user_id , amount , tax , date , type , incomed_id,level ) values ('$user_id' , '$incomes' , '$itax' , '$date' , '3','$club_type',".$num_qualification_member[$club_type].")";
					query_execute_sqli($sql);
					update_member_wallet($user_id,$incomes,$income_type[3]);
					$systems_date_time = date("Y-m-d H:i:s",strtotime($date." ".date("H:i:s")));
					insert_wallet_account($user_id , $user_id , $incomes , $date , $acount_type[3] ,$acount_type_desc[3]." of $clb_name", $mode=1 , get_wallet_amount($user_id),$wallet_type[1],$remarks = "Club Income");
					
					if($soft_chk == "LIVE"):
						$to = get_user_email($user_id);
						$full_message = "$clb_name Income Receive successfully Thanks !! By https://www.unicgrow.com";
						$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from_email, $to,$title, $full_message);	
						$msg_roi = "$clb_name Income Receive successfully Thanks !! By https://www.unicgrow.com";
						$phone = get_user_phone($user_id);
						send_sms($phone,$msg_roi);
					endif;
				}
			}
			
		}
	}
}else
{
print "Diamond PV Matching bonus is distribute on 1 day of month or income is already distributed ";
}

/*$ss_date = date("Y-m-d",strtotime($ss_date." +1 day"));
}*/

?>