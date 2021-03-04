<?php
include("../../../config.php");
include("../../../function/functions.php");
include("../../../function/setting.php");
include("../../../function/direct_income.php");

 $sql = "select t1.* from `trade_buy` t1
		inner join `trade_trasaction` t2 on t1.`id` = t2.`buy_id`
		Where t1.`bywallet`=1 and t1.`hmode`=0 AND t1.mode = 1 AND t1.udate <= '$systems_date_time'";
		// and DATEDIFF('$systems_date_time',t2.`date`) > $share_sale_day
$query = query_execute_sqli($sql);

$num = mysqli_num_rows($query);
if($num > 0){
	while($row = mysqli_fetch_array($query)){
		$buy_id = $row['id'];
		$share = $row['share'];
		$user_id = $row['user_id'];
		$sql = "update wallet set owner_share = owner_share + $share where id='$user_id';";
		query_execute_sqli($sql);
		insert_wallet_account($user_id , $user_id , $share , $systems_date_time , $acount_type[9] , $acount_type_desc[9], 1 , get_user_allwallet($user_id,'owner_share'),$wallet_type[5],$remarks = "Trade Buy  Credit Owner Wallet");
		$sql = "update trade_buy set hmode=1 where id='$buy_id'";
		query_execute_sqli($sql);
	}
	print "Successfully !";
}
mysqli_free_result($query);