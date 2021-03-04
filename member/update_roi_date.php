<?php
ini_set("display_errors",'on');
session_start();

require_once("config.php");

/*$sql = "SELECT t1.id,t1.user_id,t2.boost_id  FROM (select id,user_id from `reg_fees_structure` where `mode` = 66 ) t1
left join (select boost_id from `reg_fees_structure` where boost_id > 0 ) t2 on t1.id = t2.boost_id
WHERE  t2.boost_id is null";
$query = query_execute_sqli($sql);
$cnt = mysqli_num_rows($query);
if($cnt > 0){
	while($row = mysqli_fetch_array($query)){
		$tid = $row['id'];
		$user_id = $row['user_id'];
		$sql = "update reg_fees_structure set boost_id = $tid where boost_id =0 and user_id='$user_id' and mode not in (66) and plan not in ('x','y','z') order by id asc limit 1";
		query_execute_sqli($sql);
	}
}

die("complete");*/
query_execute_sqli("UPDATE `reg_fees_structure` SET `roi_date` = '' where profit=10 ");
$sql = "select * from reg_fees_structure where roi_date='' ";
$query = query_execute_sqli($sql);
$cnt = mysqli_num_rows($query);
if($cnt > 0){
	while($row = mysqli_fetch_array($query)){
		set_Next_Roi_Date($row['id']);
	}
}
mysqli_free_result($query);

function set_Next_Roi_Date($id){
	$sql = "select * from reg_fees_structure where id='$id' order by id desc limit 1";
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	if($cnt > 0){
		while($row = mysqli_fetch_array($query))
		{
			$start_date = $row['start_date'];
			//$start_date = "2019-05-".date("d",strtotime($row['start_date']));
			$total_days = $row['total_days'];
			$insert_id = $row['id'];
		}
		$ne_date = array();
		for($m = 0; $m < $total_days; $m++){
			$ne_date[] = date("Y-m-d",strtotime($start_date."+$m MONTH"));
		}
		$ne_date = implode(",",$ne_date);
		$sql = "UPDATE reg_fees_structure SET roi_date = '$ne_date' WHERE id=$insert_id";
		query_execute_sqli($sql);
	}
	mysqli_free_result($query);
}