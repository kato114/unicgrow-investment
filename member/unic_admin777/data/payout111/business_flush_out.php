<?php
ini_set("display_errors","off");

include("../../../config.php");
include("../../../function/setting.php");
include("../../../function/functions.php");

$s_date = date("Y-m-d",strtotime('2020-07-25'));
$e_date = date("Y-m-d",strtotime('2019-01-01'));

while($s_date  <= $e_date){
	$systems_date = $s_date;	

	$date = date("Y-m-d",strtotime($systems_date." -1 DAY"));
	$query = query_execute_sqli("select * from pair_point where date = '$date' order by id asc ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query)){
			set_flush_business($row['user_id'],$row['left_point'],$row['right_point'],$row['reg_id'],$date);
		}
	}
	mysqli_free_result($query);
	$s_date = date("Y-m-d",strtotime($s_date." +1 day"));
}
function set_flush_business($id,$left_point,$right_point,$capping_id,$date)
{
	include("../../../function/setting.php");
	$income = 0;
	$total_pair = 0;
	
	$bin_date = date('Y-m-d' , strtotime(get_user_binary_qualifier_date($id)."- 1 Day"));
	//if($ulapse_l == 2 and $ulapse_r == 2){
	if(strtotime($date) >= strtotime($bin_date) ){
		$pc = 1;
		
		$max_pair = $pair_calc = $total_pair = $income = '';
		$max_pair = min($left_point,$right_point);
		do
		{
			$pair_calc = $per_day_multiple_pair*$pc;
			$pc++;
		}
		while($pair_calc <= $max_pair);
		$total_pair = $pair_calc-$per_day_multiple_pair;
	}
		
	/*$sql = "select rfs.*,t2.capping
			from reg_fees_structure as rfs 
			left join users t2 on rfs.user_id = t2.id_user
			where rfs.user_id='$id' and rfs.mode=1
			order by rfs.invest_type desc limit 1";
	$result = query_execute_sqli($sql);
	$rows = mysqli_fetch_array($result);
	$tot_inv = $rows['request_crowd'];
	$plan_id = $rows['invest_type'];
	$chk_capping = $rows['capping'];*/
	//$income = $total_pair*($set_binary_percent[$plan_id-1]/100);
	
	//$caping = $set_capping[$plan_id-1];
	$income = $total_pair*($set_binary_percent[$capping_id-1]/100);
	
	$caping = $set_capping[$capping_id-1];
	if($chk_capping != NULL)
		$caping = $chk_capping;
		
	if($caping < $income){
		$flush_business = $income - $caping;
		$remain_business = $income - $flush_business;
		$rincome = $caping;
	}
	else{
		$flush_business = 0;
		$remain_business = $income;
		$rincome = $income;
	}
	$max[0] = $left_point-$total_pair;
	$max[1] = $right_point-$total_pair;
	$sql = "update pair_point set /*cf_left='".$max[0]."',cf_right='".$max[1]."',*/total_business='$income',flush_business='$flush_business',
	remain_business='$remain_business' 
	where user_id='$id' and `date`='$date' order by id desc limit 1";
	query_execute_sqli($sql);
	$vars = array_keys(get_defined_vars());
	foreach($vars as $var) {
		unset(${"$var"});
		${"$var"} = NULL;
	}
	unset($vars,$i);

}

?>