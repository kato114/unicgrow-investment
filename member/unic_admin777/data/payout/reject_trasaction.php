<?php
ini_set("display_errors","off");
include("../../../config.php");
include("../../../function/setting.php");
$ptime = date("Y-m-d H:i:s",strtotime($systems_date_time. "-120 MINUTE"));
$sql = "select * from request_crown_wallet where date <= '$ptime' and status = 0";
$q = query_execute_sqli($sql);
$num = mysqli_num_rows($q);
if($num > 0){
	while($r = mysqli_fetch_array($q)){
		$tid = $r['id'];
		$sql = "update request_crown_wallet set status = 3 where id = $tid";
		//query_execute_sqli($sql);
	}
}
mysqli_free_result($q);