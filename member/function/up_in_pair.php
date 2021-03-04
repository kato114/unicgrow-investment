<?php
ini_set("display_errors",'on');
session_start();


require_once("../config.php");
include("setting.php");
include("functions.php");
//mysqli_query("update users set l_lps=0,r_lps=0,pair_lapse=25000,step=0 ");
//mysqli_query("update reg_fees_structure set mode=1 where mode in(189,190)");
//mysqli_query("update reg_fees_structure set mode=999 where mode in(99)");


//$l = get_all_parent(7426);
//print_r($l[0]);
//step2
$sql = "SELECT * FROM `pair_point` WHERE `user_id` IN (SELECT user_id FROM `pair_point` WHERE `date` = '2019-01-09') AND `date` = '2019-01-08' ";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query))
{
	$login_id  = $left_bus = $right_bus = $sl = $sr = $sql = $pos = NULL; 
	$login_id = $row['user_id'];
	$cf_left = $row['left_point'];
	$cf_right = $row['right_point'];
	$cr_pos = $diff = NULL;
	if($cf_left > $cf_right){
		$diff = $cf_left - $cf_right;
		$cr_pos = 0;
	}
	if($cf_right > $cf_left){
		$diff = $cf_right - $cf_left;
		$cr_pos = 1;
	}
	if($cf_right == $cf_left){
		$diff = $cf_right - $cf_left;
		$cr_pos = 0;
	}
	$sql = "select * from users where id_user='$login_id'";
	$qul = query_execute_sqli($sql);
	$row = mysqli_fetch_array($qul);
	$ulapse_l = $row['l_lps'];
	$ulapse_r = $row['r_lps'];
	$topup_comp = $row['step'];
	if((($ulapse_l >= 1 and $ulapse_r > 1) or ($ulapse_l > 1 and $ulapse_r >= 1)) and $topup_comp==1){
		$sql = "SELECT * FROM `pair_point` WHERE `user_id` ='$login_id' and `date` = '2019-01-09'";
		$qv = query_execute_sqli($sql);
		while($rv = mysqli_fetch_array($qv))
		{
			if($cr_pos == 0){
				$left_point = $rv['cf_left'] + $diff;
				$right_point = $rv['cf_right'];
			}
			if($cr_pos == 1){
				$left_point = $rv['cf_left'];
				$right_point = $rv['cf_right'] + $diff;
			}
			print $sql = "update pair_point set  left_point = $left_point, right_point = $right_point
					where user_id = '$login_id' and `date` = '2019-01-09'";
			print "<br>";
			query_execute_sqli($sql);
		}
	}
}



die();
$sql = "select * from lr_business order by id asc ";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query))
{
	$login_id  = $left_bus = $right_bus = $sl = $sr = $sql = $pos = NULL; 
	$login_id = $row['user_id'];
	$lb = $row['lb'];
	$rb = $row['rb'];
	
	$sql = "select * from pair_point where user_id = '$login_id' and date='2019-01-08'";
	$qv = query_execute_sqli($sql);
	$num = mysqli_num_rows($qv);
	$sql = NULL; 
	if($num > 0){
		while($rq = mysqli_fetch_array($qv)){
			$t_id = $rq['id'];
			$sql = "update pair_point set left_point=$lb,right_point=$rb,pair_mode=0 where id='$t_id'";
		}
	}
	else{
		$sql = "insert into pair_point set left_point=$lb,right_point=$rb,date='2019-01-08',user_id='$login_id',pair_mode=0 ";
	}
	//print $sql."<br>";
	query_execute_sqli($sql);
}

