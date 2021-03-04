<?php
ini_set("display_errors",'on');
session_start();

require_once("config.php");
include("function/setting.php");
$sql = "UPDATE kyc SET proceed = 5";
query_execute_sqli($sql);
$sql = "select * from kyc";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query)){
	$user_id = $row['user_id'];
	
	$pan_card = $row['pan_card'];
	$chq_passbook = $row['chq_passbook'];
	$id_front = $row['id_proof_front'];
	$id_back = $row['id_proof_back'];
	$user_pic = $row['photo'];
	$sign = $row['signature'];
	
	$mode_pan = $row['mode_pan'];
	$mode_id = $row['mode_id'];
	$mode_photo = $row['mode_photo'];
	$mode_chq = $row['mode_chq'];
	$proceed = $row['proceed']-1;
	
	if($mode_pan == 4 and $mode_id == 4 and $mode_photo == 4 and $mode_chq == 4){
		$proceed = 0;
	}
	if($mode_pan == 1 and $mode_id == 1 and $mode_photo == 1 and $mode_chq == 1){
		$proceed = 5;
	}
	if(($mode_pan == 0 or $mode_pan == 4)  and $pan_card == '' and $proceed > 0){
		$proceed = $proceed - 1;
	}
	if(($mode_id == 0 or $mode_id == 4) and ($id_front == '' or $id_back == '') and $proceed > 0){
		$proceed = $proceed - 1;
	}
	if(($mode_photo == 0 or $mode_photo == 4) and ($user_pic == '' or $sign == '') and $proceed > 0){
		$proceed = $proceed - 1;
	}
	if(($mode_chq == 0 or $mode_chq == 4) and $chq_passbook == '' and $proceed > 0){
		$proceed = $proceed - 1;
	}
	if($proceed == 4)$proceed = 5;
	$sql = "UPDATE kyc SET proceed = $proceed WHERE user_id = '$user_id'";
	//print "<br>";
	query_execute_sqli($sql);
}