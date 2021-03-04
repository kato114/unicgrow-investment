<?php
ini_set("display_errors",'on');
session_start();
$db_host = "localhost";
$db_username = "mahendra";
$db_password = "mahendra123";
$db = "topride_business_test4";//topride_business
//$con=mysqli_connect($db_host,$db_username,$db_password);
//mysqli_select_db($db,$con);

$con = new mysqli($db_host,$db_username,$db_password,$db);
if (!$con){
  die("Connection error: " . mysqli_connect_error());
}
/*********free up memory variable ****/
$vr = array();					   /**/
$vri = 0;						   /**/
/********free up memory variable******/
function query_execute_sqli($sqli){
	global $con;
	global $vr;
	global $vri;
	$vr[$vri] = $srs = mysqli_query($con,$sqli);
	$vri++;
	return $srs;
}
function get_mysqli_insert_id(){
	global $con;
	return mysqli_insert_id($con);
}
function free_object_memory(){
	global $vr;
	global $con;
	for($i = 0; $i < count($vr); $i++){
		mysqli_free_result($vr[$i]);
	}
	unset($vr);
	mysqli_close($con);
}
include("setting.php");
include("functions.php");
$sql = "select * from reg_fees_structure where mode=1 order by id asc";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query)){
	$user_id = $row['user_id'];
	$date = $row['date'];
	$pleg = false;
	$invest_type = $row['invest_type'];
	
	$pid = getmypid()-1;
	exec("kill $pid");
	exec("TSKILL $pid");
	?>
	<form id="myform" action="pair_point_calc.php" method="post">
	<input type="hidden" name="user_id" value="<?=$user_id?>" />
	<input type="hidden" name="date" value="<?=$date?>" />
	<input type="hidden" name="invest_type" value="<?=$invest_type?>" />
	</form>
	<script type="text/javascript">document.getElementById("myform").submit();</script>
	<?php
	
}
function pair_point_calculation($id,$date,$pleg = false)
{
	include("setting.php");
	if(!$pleg){
		$sql = "select t1.* from reg_fees_structure t1 where t1.user_id = '$id' and t1.request_crowd > 0 limit 1";
		$query = query_execute_sqli($sql);
		$cnt = mysqli_num_rows($query);
		mysqli_free_result($query);
		if($cnt == 1){
			$carry_forward = point_carry_forward($id,$date,$udirect_l,$udirect_r);
			$left_point = 	$carry_forward[0];
			$right_point = 	$carry_forward[1];
			if($left_point > 0 or $right_point > 0){
				$chk_pair = chk_pair_poin_id_exist_with_date($id,'left_point',$date);
				if($chk_pair[0][0] == 0){
					$sql = "insert into pair_point (user_id, left_point,right_point,date) 
					values('$id','$left_point','$right_point','$date')";
				}
				query_execute_sqli($sql);
			}
			query_execute_sqli("update users set step = 1 where id_user=$id");
		}
	}
	$pre_pv = 0;
	//check upgrade when yes then booster is not getting by member start
	$sql = "select * from reg_fees_structure where user_id='$id' and mode=99 order by id desc limit 1";
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	if($cnt > 0){
		while($row = mysqli_fetch_array($query))
		{
			$pre_pv = $row['request_crowd'];
		}
	}
	mysqli_free_result($query);
	//check upgrade when yes then booster is not getting by member end
	$sql = "select t1.* from reg_fees_structure t1
			where t1.user_id = '$id' and t1.date = '$date' and t1.request_crowd > 0";
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	if($cnt > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$new_amount = $row['request_crowd'];
		}
		mysqli_free_result($query);
		$new_amount = $new_amount ;//- $pre_pv;//deduct pre top pv
		$club_business = NULL;
		$club_business = array();
		$sql = "select * from plan_club order by id desc";
		$quer = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($quer)){
			$club_business[] = $row['business'];
		}
		mysqli_free_result($quer);
		$real_p = real_parent($id);
		//unset($parents);
		$parents = NULL;
		$parents = array();
		$parents = get_all_parent($id);
		$cnt_parent = count($parents[0]); 
		for($i = 1; $i < $cnt_parent ; $i++)
		{
			$amount = $new_amount;
			$pair_field = '';
			switch($parents[1][$i])
			{
				case 0 : $pair_field = 'left_point';
						  break;
				case 1 : $pair_field = 'right_point';
						  break;
			}
			$chk = chk_pair_poin_id_exist($parents[0][$i]);
			$user_id = $parents[0][$i];
			if(($parents[2][$i] >=1 and $parents[3][$i] > 1) or ($parents[2][$i] > 1 and $parents[3][$i] >= 1)){
				$udirect_l = $parents[2][$i];
				$udirect_r = $parents[3][$i];
			}
			else{
				$sql = "select * from users where id_user='$user_id'";
				$qul = query_execute_sqli($sql);
				$row = mysqli_fetch_array($qul);
				$udirect_l = $row['l_lps'];
				$udirect_r = $row['r_lps'];
				$pair_lapse_amount = $row['pair_lapse'];
				mysqli_free_result($qul);
				if($real_p == $user_id){
					if($udirect_l < 2 or $udirect_r < 2){
						$ubc = $amount / $per_day_multiple_pair;
						if($ubc > 2)$ubc = 2; 
						query_execute_sqli("update users set r_lps = r_lps + $ubc where id_user=$user_id and r_lps < 2");
						query_execute_sqli("update users set l_lps = l_lps + $ubc where id_user=$user_id and l_lps < 2");
					}
					if($pair_lapse_amount > 0){
						if($amount >= $pair_lapse_amount){
							$amount = $amount - $pair_lapse_amount;
							query_execute_sqli("update users set pair_lapse = 0,pair_lapse_date='$date' 
							where id_user=$user_id");
						}
						else{
							$amount = $pair_lapse_amount - $amount;
							query_execute_sqli("update users set pair_lapse = $amount,pair_lapse_date='$date' 
							where id_user=$user_id");
						}
					}
					
				}
				
			}
			if( $user_id > 0){//$pleg and
				add_member_total_business($user_id,$new_amount,$parents[1][$i],$date);
			}
			if($user_id > 0)
			{
				if($chk == 0)// id dos'nt exist with date
				{	
					$sql_insert = "insert into pair_point (user_id, $pair_field, date) values('$user_id','$amount','$date')";
					query_execute_sqli($sql_insert);
				}
				else{
					$chk_2 = NULL;
					$chk_2 = chk_pair_poin_id_exist_with_date($parents[0][$i],$pair_field,$date);
					if($chk_2[0][0] == 1)// id exist with date
					{
						$pair_amount = $chk_2[0][1];
						$pair_id = $chk_2[0][2];
						$point = $amount+$pair_amount;
						$sql_update = "update pair_point set  $pair_field = $point 
						where id = '$pair_id' and user_id = '$user_id' ";
						query_execute_sqli($sql_update);					
					}
					if($chk_2[0][0] == 0){// carry forward
						$carry_forward = point_carry_forward($user_id,$date,$udirect_l,$udirect_r);
						$left_point = 	$carry_forward[0];
						$right_point = 	$carry_forward[1];
						

						if($pair_field == 'right_point')
						{
							$right_point = $amount + $right_point;
							$left_point = $left_point;
						}
						
						if($pair_field == 'left_point')
						{
							$left_point = $amount + $left_point;
							$right_point = $right_point;
						}
						$insert_left_point = $left_point;
						$insert_right_point = $right_point;
						
						$sql = "insert into pair_point (user_id, left_point,right_point,date) values('$user_id','$insert_left_point','$insert_right_point','$date')";
						query_execute_sqli($sql);
					}
				}
				Set_member_qualification($user_id,$club_business,$date);
			}
		}
	}
	
}


function get_all_parent($id)
{	
	require_once "functions.php";
	$parent = NULL;
	$parent = array();
	$parent[0][0] = $id;
	$pos = get_user_pos($id);
	if($pos == 'Left')
	{
		$pos = 0;
	}
	if($pos == 'Right')
	{
		$pos = 1;
	}
	$parent[1][0] = $pos;
	$count = count($parent[0]);
	for($i = 0; $i <$count; $i++)
	{ $user_id =  $parent[0][$i];
			$sql = "select * from users where id_user = '$user_id' ";
			$result = query_execute_sqli($sql);
			$num = mysqli_num_rows($result);
			if($num > 0)
			{
				while($row = mysqli_fetch_array($result))
				{
					$parent[0][] = $row['parent_id'];
					$parent[1][] = $row['position'];
					$parent[2][] = $row['l_lps'];
					$parent[3][] = $row['r_lps'];
				}
			}
			mysqli_free_result($result);
		$count = count($parent[0]);
	}
	return $parent;
}

function chk_pair_poin_id_exist($id)
{
	 $sql = "select * from pair_point where user_id = '$id'";
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	if($cnt == 0)
	return 0;
	else
	return 1;
}

function chk_pair_poin_id_exist_with_date($id,$pair_field,$date)//$previous_date,$next_date
{
	 $sql = "select * from pair_point where user_id = '$id' and date='$date'";// between '$previous_date' and '$next_date'
	$query = query_execute_sqli($sql);
	$cnt = mysqli_num_rows($query);
	$user_info = NULL;
	$user_info = array();
	if($cnt == 0)
	{
			$user_info[0][0] = 0;
			$user_info[0][1] = 0;
			$user_info[0][2] = 0;
		
	}
	else
	{
		$user_info[0][0] = 1;
		while($row = mysqli_fetch_array($query))
		{
			$user_info[0][1] = $row[$pair_field];
			$user_info[0][2] = $row['id'];
		}
	}
	return $user_info;	
}


function point_carry_forward($id,$date,$ulapse_l,$ulapse_r)
{
	include("setting.php");
	$date = $date; //date("Y-m-d") ;
	$query = query_execute_sqli("select * from pair_point where date < '$date' and user_id = '$id' group by date order by id desc limit 1 ");
	$num = mysqli_num_rows($query);
	$child = NULL;$max = NULL;
	$max = $child = array();
	if($num != 0)
	{
		while($row = mysqli_fetch_array($query)){
			$child[0] = $row['left_point'];
			$child[1] = $row['right_point'];
		}
		
		$total_pair = 0;
		$sql = "select * from users where id_user='$id'";
		$qul = query_execute_sqli($sql);
		$row = mysqli_fetch_array($qul);
		$ulapse_l = $row['l_lps'];
		$ulapse_r = $row['r_lps'];
		$topup_comp = $row['step'];
		if((($ulapse_l >= 1 and $ulapse_r > 1) or ($ulapse_l > 1 and $ulapse_r >= 1)) and $topup_comp==1){
			$pc = 1;
			$max_pair = min($child[0],$child[1]);
			do
			{
				$pair_calc = $per_day_multiple_pair*$pc;
				$pc++;
			}
			while($pair_calc <= $max_pair);
			$total_pair = $pair_calc-$per_day_multiple_pair;
		}
		
		$max[0] = $child[0]-$total_pair;
		$max[1] = $child[1]-$total_pair;
	}
	else { 	
		$max[0] = 0;
		$max[1] = 0;
	}
	return $max;
}

function add_member_total_business($user_id,$amount,$pos,$date){
	$s_date = date("Y-m-01",strtotime($date));
	$e_date = date("Y-m-t",strtotime($date));
	switch($pos){
		case 0 : $pair_field = 'left_point';
				  break;
		case 1 : $pair_field = 'right_point';
				  break;
	}
	$sql = "select * from month_pair_point where user_id='$user_id' and date between '$s_date' and '$e_date'";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0){
		while($row = mysqli_fetch_array($query)){
			$pair_id = $row['id'];
		}
		$sql = "update month_pair_point set  $pair_field = $pair_field + $amount 
				where id = '$pair_id' and user_id = '$user_id' ";
	}
	else{
		$sql = "insert into month_pair_point (user_id, $pair_field, date) values('$user_id','$amount','$date')";
	}
	query_execute_sqli($sql);
}
function Set_member_qualification($user_id,$club_business,$date){
	$sql = "select t1.*,
			case 
				WHEN least(t1.`left_point`,t1.`right_point`) >= $club_business[0] THEN 3
				WHEN least(t1.`left_point`,t1.`right_point`) >= $club_business[1] THEN 2
				WHEN least(t1.`left_point`,t1.`right_point`) >= $club_business[2] THEN 1
				ELSE 0
			end club_type	
			from month_pair_point t1
			inner join users t2 on t1.user_id = t2.id_user 
			where least(t1.`left_point`,t1.`right_point`) > 0 and t2.type='B' and t1.user_id='$user_id'";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query)){
		$club_type = $row['club_type'];
	}
	query_execute_sqli("update users set matching_qualification= $club_type where id_user=$user_id");
	$s_date = date("Y-m-01",strtotime($date));
	$e_date = date("Y-m-t",strtotime($date));
	$sql = "update month_pair_point set  matching_qualification = $club_type 
			where user_id = '$user_id' and date between '$s_date' and '$e_date' ";
	query_execute_sqli($sql);
}
?>