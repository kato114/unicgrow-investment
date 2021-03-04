<?php
include("../../../config.php");
include("../../../function/setting.php");
include("../../../function/functions.php");
include("../../../function/direct_income.php");
include("../../../function/send_mail.php");

$date = $systems_date;
$rwd_id = $rwd_business = $rwd_ttl = $rwd_inct =  array();
$sql = "select * from plan_reward order by id desc";
$quer = query_execute_sqli($sql);
while($row = mysqli_fetch_array($quer)){
	$rwd_id[] = $row['id'];
	$rwd_business[] = $row['business'];
	$rwd_ttl[] = $row['title'];
	$rwd_inct[] = $row['incentive'];
	
}
mysqli_free_result($quer);
		
 $sql = "select t1.* from (
		select user_id,
		case 
			WHEN least(sum(`cf_left`),sum(`cf_right`)) >= ($rwd_business[0]+$rwd_business[1]+$rwd_business[2]+$rwd_business[3]+$rwd_business[4]) THEN 5
			WHEN least(sum(`cf_left`),sum(`cf_right`)) >= ($rwd_business[1]+$rwd_business[2]+$rwd_business[3]+$rwd_business[4]) THEN 4
			WHEN least(sum(`cf_left`),sum(`cf_right`)) >= ($rwd_business[2]+$rwd_business[3]+$rwd_business[4]) THEN 3
			WHEN least(sum(`cf_left`),sum(`cf_right`)) >= ($rwd_business[3]+$rwd_business[4]) THEN 2
			WHEN least(sum(`cf_left`),sum(`cf_right`)) >= $rwd_business[4] THEN 1
			ELSE 0
		end rrwd_id,sum(`cf_left`) lf_bus,sum(`cf_right`) rf_bus
		from pair_point
		group by user_id) t1
		left join income t2 on t1.user_id = t2.user_id and t1.rrwd_id = t2.incomed_id and t2.type='$income_type[5]' 
		where t1.rrwd_id > 0 and t2.user_id is NULL";
$quer = query_execute_sqli($sql);
$num = mysqli_num_rows($quer);
if($num > 0){
	while($row = mysqli_fetch_array($quer)){
		$user_id = $row['user_id'];
		$rrwd_id = $row['rrwd_id'];
		$lf_bus = $row['lf_bus'];
		$rf_bus = $row['rf_bus'];
		
		$sql = "insert into income (user_id , amount , date , type , incomed_id ) values ('$user_id' , '0' , '$date' , '$income_type[5]','$rrwd_id') ";
		query_execute_sqli($sql);
	}
	print "Reward Distribute Successfully To $num Member";
		
}
else
{
	print "Diamond PV Matching bonus is distribute on 1 day of month or income is already distributed ";
}
mysqli_free_result($quer);

$sql = "select user_id,max(incomed_id) incomed_id from income where type=5 group by user_id";
$quer = query_execute_sqli($sql);
$num = mysqli_num_rows($quer);
if($num > 0){
	while($row = mysqli_fetch_array($quer)){
		$user_id = $row['user_id'];
		$incomed_id = $row['incomed_id'];
		for($k = 1; $k < $incomed_id; $k++){
			$sql = "select * from income where type=5 and incomed_id=$k and user_id='$user_id'";
			$query = query_execute_sqli($sql);
			$num = mysqli_num_rows($query);
			if($num == 0){
				$sql = "insert into income (user_id , amount , date , type , incomed_id ) values ('$user_id' , '0' , '$date' , '$income_type[5]','$k') ";
				query_execute_sqli($sql);
			}
			mysqli_free_result($query);
		}
	}
	mysqli_free_result($quer);
}
?>