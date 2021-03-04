
<?php
error_reporting(1);

include "config.php";
include "function/setting.php";
include "function/functions.php";

//step = 0;
$sql = "SELECT t1.* FROM users t1
left join `reg_fees_structure` t2 on t1.id_user = t2.user_id
where t1.step=0 and t2.user_id is not null and t2.invest_type in(1,2,3,4) and ((t1.l_lps > 1 and t1.r_lps >= 1) or (t1.l_lps >= 1 and t1.r_lps > 1))
group by t1.id_user";
$que = query_execute_sqli($sql);
while($row = mysqli_fetch_array($que)){
	$user_id = $row['id_user'];
	//query_execute_sqli("update users set step = 1 where id_user=$user_id");
	 $sql = "select t1.*,t2.l_lps,t2.r_lps,t2.step from pair_point t1
			left join users t2 on t1.user_id = t2.id_user
			inner join reg_fees_structure as t3 on t1.user_id = t3.user_id
			where t1.user_id = '$user_id' and least(t1.`left_point`,t1.`right_point`) > 0 and t1.pair_mode=1
			and t1.date >= t3.date";
	print "<br>";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			//$date = date("Y-m-d");
			$pair_id = $row['id'];
			$left_point = $row['left_point'];
			$right_point = $row['right_point'];
			$ul_lps = $row['l_lps'];
			$ur_lps = $row['r_lps'];
			$step_topup = $row['step'];
			$bdate = date("Y-m-d",strtotime($row['date']." +1 DAY"));
			
			if((($ul_lps > 1 and $ur_lps >= 1) or ($ul_lps >= 1 and $ur_lps > 1)) and $step_topup == 0){
				$incomes = get_pair_point_income($user_id,$left_point,$right_point,$bdate);
				echo 'USER ID = ',$user_id,' *** INCOME = ',$incomes,' *** DATE = ',$bdate,'<br>';
				
				$sql = "insert into income (user_id , amount , date , type,mode ) 
						values ('$user_id' , '$incomes' , '$bdate' , '4',11)";
				query_execute_sqli($sql);
				query_execute_sqli("update pair_point set pair_mode=0 where id ='$pair_id' ");
				//insert_wallet_account($user_id , $user_id , $incomes , $date , $acount_type[2] ,$acount_type_desc[2], $mode=1 , get_wallet_amount($user_id),$wallet_type[1],$remarks = "Binary Income");
				
				
			}
		}
		mysqli_free_result($query);
	}
}
mysqli_free_result($que);

//not generated income
$sql = "select t1.* from (SELECT t1.* FROM `pair_point` t1
left join income t2 on t1.user_id = t2.user_id and t2.date = DATE_ADD(t1.date,INTERVAL 1 DAY)
where t2.user_id is null and t1.pair_mode=0 and least(t1.`left_point`,t1.`right_point`) > 0) t1
left join `reg_fees_structure` t2 on t1.user_id = t2.user_id
where t1.date >= t2.date
group by t1.user_id,t1.date";
$que = query_execute_sqli($sql);
$k = 1;
while($row = mysqli_fetch_array($que)){
	$user_id = $row['user_id'];
	$bbdate = $row['date'];
	//query_execute_sqli("update users set step = 1 where id_user=$user_id");
	$sql = "select t1.*,t2.l_lps,t2.r_lps,t2.step from pair_point t1
			left join users t2 on t1.user_id = t2.id_user
			inner join reg_fees_structure as t3 on t1.user_id = t3.user_id
			where t1.user_id = '$user_id' and least(t1.`left_point`,t1.`right_point`) > 0 and t1.pair_mode=0
			and t1.date = '$bbdate'
			group by t1.user_id,t1.date";
	print "<br>";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			//$date = date("Y-m-d");
			$pair_id = $row['id'];
			$left_point = $row['left_point'];
			$right_point = $row['right_point'];
			$ul_lps = $row['l_lps'];
			$ur_lps = $row['r_lps'];
			$step_topup = $row['step'];
			$bdate = date("Y-m-d",strtotime($row['date']." +1 DAY"));
			
			if((($ul_lps > 1 and $ur_lps >= 1) or ($ul_lps >= 1 and $ur_lps > 1)) and $step_topup == 1){
				$income = get_pair_point_income($user_id,$left_point,$right_point,$bdate);
				echo $k,'.*** USER ID = ',$user_id,' *** INCOME = ',$income,' *** DATE = ',$bdate,'<br>';
				
				$sql = "insert into income (user_id , amount , date , type,mode ) 
						values ('$user_id' , '$income' , '$bdate' , '4',10)";
				query_execute_sqli($sql);
				query_execute_sqli("update pair_point set pair_mode=0 where id ='$pair_id' ");
				//insert_wallet_account($user_id , $user_id , $income , $date , $acount_type[2] ,$acount_type_desc[2], $mode=1 , get_wallet_amount($user_id),$wallet_type[1],$remarks = "Binary Income");
				
				$k++;
			}
		}
	}
	mysqli_free_result($query);
}
mysqli_free_result($que);
function get_pair_point_income($id,$left_point,$right_point,$date)
{
	include("function/setting.php");
	$income = 0;
	
	$pc = 1;
	$max_pair = min($left_point,$right_point);
	do
	{
		$pair_calc = $per_day_multiple_pair*$pc;
		$pc++;
	}
	while($pair_calc <= $max_pair);
	
	$total_pair = $pair_calc-$per_day_multiple_pair;
	
	
	if($total_pair >= $matching_business_amount[1]){
		$sql = "update users set matching_qualification=1  where id_user='$id'";
		query_execute_sqli($sql);
	}
	$sql = "select rfs.*,t2.capping
			from reg_fees_structure as rfs 
			left join users t2 on rfs.user_id = t2.id_user
			where rfs.user_id='$id' and rfs.mode in (1,189)
			order by rfs.invest_type desc limit 1";
	$result = query_execute_sqli($sql);
	$rows = mysqli_fetch_array($result);
	$tot_inv = $rows['update_fees'];
	$plan_id = $rows['invest_type'];
	$chk_capping = $rows['capping'];
	$income = $total_pair*($set_binary_percent[$plan_id-1]/100);
	
	$caping = $set_capping[$plan_id-1];
	if($chk_capping != NULL)
		$caping = $chk_capping;
	if($caping < $income)
		return $caping;
	else
		return $income;
}