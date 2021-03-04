<?php
ini_set("display_errors",'on');
session_start();

require_once("config.php");
include("function/setting.php");
include("function/functions.php");
include("function/direct_income.php");
include("function/pair_point_calc.php");
$step1 = false;//false
$step2 = true;//true
if($step1){
	query_execute_sqli("update users set l_lps=0,r_lps=0");
	$sql = "update atssolutions_business_test.`reg_fees_structure` set plan='A' where plan=''";
	query_execute_sqli($sql);
	$sql = "select * from atssolutions_business_test.reg_fees_structure where mode in(1,66) and boost_id=0 order by id asc";
	$query = query_execute_sqli($sql);
	while($r = mysqli_fetch_array($query))
	{
		$user_id = $r['user_id'];
		
		if($r['invest_type'] == 'z'){
			$r['profit'] = 0;
		}
		$sql = "select * from reg_fees_structure where user_id='$user_id'";
		$sq = query_execute_sqli($sql);
		$nsq = mysqli_num_rows($sq);
		if($nsq > 0){
			while($rt = query_execute_sqli($sq)){
				$pos = $rt['position'];
			}
		}
		else{
			$pos = direct_member_position(real_parent($user_id),$user_id);
		}
		$sql = "insert into `reg_fees_structure` (`user_id`,`rcw_id`,`request_crowd`
				,`update_fees`,`profit`,`count`,`date`,`start_date`,`total_days`,`invest_type`,`time`,`by_wallet`,`plan`,`position`)
				values('".$r['user_id']."','".$r['rcw_id']."','".$r['request_crowd']."','".$r['update_fees']."','".$r['profit']."','".$r['count']."','".$r['date']."','".$r['start_date']."','".$r['total_days']."','".$r['invest_type']."','".$r['time']."','".$r['by_wallet']."','".$r['plan']."','$pos')";
		query_execute_sqli($sql);
		pair_point_calculation($user_id , $r['date']);	
	}
}
if($step2){
	$sql = "select * from reg_fees_structure where mode in(1) and boost_id=0 order by id asc";
	$query = query_execute_sqli($sql);
	while($r = mysqli_fetch_array($query))
	{
		$user_id = $r['user_id'];
		get_booster_income(real_parent($user_id),$r['date']);
		get_booster_income($user_id,$r['date']);
	}
}
?>
