<?php
ini_set("display_errors","off");
include("../../../config.php");
include("../../../function/setting.php");
include("../../../function/functions.php");
include("../../../function/send_mail.php");
$sql = "select * from reg_fees_structure where mode = 2 and end_date <= '$systems_date'";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0){
	while($row = mysqli_fetch_array($query)){
		$table_id = $row['id'];
		$user_id = $row['user_id'];
		$principal = $row['update_fees'];
		$plan_id = $row['invest_type'];
		$count = $row['count'];
		$total_days = $row['total_days'];
		$return = false;
		if($plan_id == 5 or $plan_id == 6){
			$profit = $row['profit'];
			$principal = $principal * $profit;
			$return = true;
		}elseif($total_days == $count){
			$return = true;
		}
		if($return){
			$sql = "update reg_fees_structure set mode = 0 where id = $table_id";
			query_execute_sqli($sql);
			$sql = "insert into income (user_id , amount , tax , tds_tax , plan , type , date,incomed_id,mode) values ('$user_id' , '$principal' , '$tax_amount1' , '$tax_amount2' , '$p_value' ,'$income_type[2]' , '$systems_date_time','$table_id','1') ";
			query_execute_sqli($sql);
			update_member_wallet($user_id,$principal,$income_type[2]);
			insert_wallet_account($user_id , $user_id , $principal , $systems_date_time , $acount_type[2] ,$acount_type_desc[2], $mode=1 , get_wallet_amount($user_id),$wallet_type[1],$remarks = "Principle Return");
		}
	}
}
mysqli_free_result($query);
?>