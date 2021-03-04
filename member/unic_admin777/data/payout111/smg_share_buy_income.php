<?php
include("../../../config.php");
include("../../../function/functions.php");
include("../../../function/setting.php");
include("../../../function/direct_income.php");

trade_level_income($systems_date_time);

function trade_level_income($date = false ){
	include("../../../function/setting.php");
	$sql = "select t1.id,t1.level_amount,t2.user_id from trade_trasaction t1
			left join trade_buy t2 on t1.sale_id = t2.id
			where  t1.level_mode=0 and t2.user_id > 1";//t1.id in($trade_id) and
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$sale_user_id = $user_id = $row['user_id'];
			$level_amount = $row['level_amount'];
			for($i = 1; $i <= count($trade_level_percent); $i++){
				$user_id = real_par($user_id);
				$income = $level_amount * $trade_level_percent[$i-1] / 100;
				
				if($income > 0 and $user_id > 0 and get_type_user($user_id) == "B" and check_member_active_by_package($user_id)){
					$sql = "insert into income (user_id , amount , date , type , incomed_id,plan,level ) values ('$user_id' , '$income' , '$date' , '$income_type[9]','$sale_user_id',$id,".($i).") ";
					query_execute_sqli($sql);
					$m_wallet = $income * $wallet_dividend[0] /100;
					query_execute_sqli("UPDATE wallet SET amount = amount + '$m_wallet' , date = '$date' WHERE id = '$user_id' ");
					insert_wallet_account($user_id , $sale_user_id , $m_wallet , $date , $acount_type[12] , $acount_type_desc[12], 1 , get_user_allwallet($user_id,'amount'),$wallet_type[1],$remarks = "Trade Level Income");
					$m_wallet = $income * $wallet_dividend[1] /100;
					query_execute_sqli("UPDATE wallet SET trade_gaming = trade_gaming + '$m_wallet' , date = '$date' WHERE id = '$user_id' ");
					insert_wallet_account($user_id , $sale_user_id , $m_wallet , $date , $acount_type[12] , $acount_type_desc[12], 1 , get_user_allwallet($user_id,'trade_gaming'),$wallet_type[4],$remarks = "Trade Level Income");
				}
			}
			$sql = "update trade_trasaction set level_mode=1 where id=$id and level_mode=0";
			query_execute_sqli($sql);
		}
		print "Trade Level Income Distributed !!";
	}
	mysqli_free_result($query);
}
?>

