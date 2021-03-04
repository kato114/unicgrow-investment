
<?php
error_reporting(1);

include "config.php";
include "function/setting.php";
include "function/functions.php";
$time = $systems_date_time;
get_daily_income_not_rc($time);
function get_daily_income_not_rc($time)
{
	include("function/setting.php");
	$explo = explode(" ",$time);
	$till_date = $explo[0];
	$roi_time = $explo[1];
	
	$k = 1;
	{
	 	
		$sql = "select t1.id,t1.user_id,t1.request_crowd,t1.profit,t1.invest_type,t1.plan,t1.total_days
				,t1.count,count(t2.user_id),t1.date 
				from reg_fees_structure t1 
				left join income t2 on t1.user_id = t2.user_id and t2.type=2
				where t1.mode=1 and t1.invest_type in (1,2,3,4) and t1.start_date <= '2020-11-24'
				GROUP by t1.user_id
				having t1.count = 0 and count(t2.user_id) = 0";
		$sql = "SELECT 
				t1.id,t1.request_crowd,t1.profit,t1.invest_type,t1.plan,t1.total_days,
				t1.user_id,TIMESTAMPDIFF(MONTH, DATE_ADD(t1.`start_date`,INTERVAL - 1 MONTH), '2020-12-31') rg_cnt,
				t1.start_date,t1.count,count(t2.user_id) in_cnt from reg_fees_structure t1
				left join (select * from income where type=2) t2 on t1.user_id = t2.user_id
				where t1.mode=1 and t1.invest_type in (1,2,3,4)  
				GROUP by t1.user_id,t2.user_id
				having rg_cnt <> in_cnt;";
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
					$update_fees = $r['request_crowd'];
					$incomed_id = $table_id = $r['id'];
					
					$percent_roi = $r['profit'];
					$total_amount = $update_fees;
					$p_value = $r['plan'];
					$time = date("Y-m-d 12:15:46",strtotime($r['start_date']." -1 MONTH"));
					$plan_idss = $r['invest_type'];
					$total_days = $r['total_days'];
					$income = $percent_roi;//$total_amount*($percent_roi/100);
					$inc_type = $income_type[2];
					$explo = explode(" ",$time);
					$rdate = $explo[0];
					$sql = "SELECT TIMESTAMPDIFF(MONTH, '$rdate', '$till_date')";
					
					$diff_m = mysqli_fetch_array(query_execute_sqli($sql))[0];
					for($i = 0; $i < $diff_m; $i++){
						
						if($income > 0){
							$done = 1;
							$time = date("Y-m-d 12:15:46",strtotime($rdate.($i+1)." month"));
							$explo = explode(" ",$time);
							$date = $explo[0];
							$sql = "select t1.* from income t1 where t1.date >= '$date' and user_id='$user_id' and t1.type=2";
							if(mysqli_num_rows(query_execute_sqli($sql)) == 0){
								
								$incomes = $income;// - ($income * $setting_withdrawal_tax/100);
								$itax = 0;//($income * $setting_withdrawal_tax/100);
								//print "$k";
								print $sql = "insert into income (user_id , amount , tax , plan , type , date,incomed_id,mode) values ('$user_id' , '$incomes' , '$itax' , '$p_value' ,'$inc_type' , '$date','$incomed_id','19') ";
								print "<br>";
								query_execute_sqli($sql);
								
								//$income = $income/2;
								$sql = "select t1.* from daily_income t1 where t1.date = '$date' and user_id='$user_id'";
								if(mysqli_num_rows(query_execute_sqli($sql)) == 0){
									$sql = "insert into daily_income (user_id , total_amount , percent , rand_percent , income , date , time , plan,investment_id,`paid_by`) values ('$user_id' , '$total_amount' , '".$r['profit']."' , '$rand_per' , '$incomes' , '$date' , '$time' , '$p_value', '$table_id','$percent_roi') ";
									query_execute_sqli($sql);
								}
								query_execute_sqli("update reg_fees_structure set `count` = `count` + 1 where id = '$table_id' and mode=1 ");
								
								$sql = "update reg_fees_structure set `mode` = 0,end_date='$date' where id = '$table_id' 
								and count=total_days";
								query_execute_sqli($sql);
								$k++;
							}
							
						}
					}
					
				}
				
			}
			if($done == 1)
			print "Investment Income distributed Successfully ";
		}
		else { print "<font size=5 color=\"#FF0000\">There Are No User For Investment Income !!</font>"; }
	}
		
}

?>