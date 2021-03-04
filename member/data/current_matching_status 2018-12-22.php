<?php
include('../security_web_validation.php');

session_start();
include("function/setting.php");

$login_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = 20;
$show_tab = 5;
if($newp == ''){ $newp='1'; }
$tstart = ($newp-1) * $plimit;
$tot_p = $plimit * $show_tab;


$sql = "SELECT * FROM pair_point where user_id = '$login_id' GROUP BY date";

$SQL = "$sql LIMIT $tstart,$tot_p ";

$query = query_execute_sqli($SQL);
$totalrows = mysqli_num_rows($query);

$sqlk = "SELECT COUNT(t1.id) num FROM ($sql) t1";
$quer = query_execute_sqli($sqlk);
while($ro = mysqli_fetch_array($quer))
{
	$tot_rec = $ro['num'];
	$lpnums = ceil ($tot_rec/$plimit);
}

if($totalrows > 0)
{ ?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center" rowspan="2">Sr. No.</th>
			<th class="text-center" rowspan="2">Date</th>
			<th class="text-center" colspan="2">Investment</th>
			<th class="text-center" colspan="2">Carry Forward</th>
			<th class="text-center" colspan="2">Current Business</th>
			<th class="text-center" rowspan="2">Total Business</th>
			<th class="text-center" rowspan="2">Matching Business</th>
			<th class="text-center" rowspan="2">Flush Business</th>
		</tr>
		<tr>	
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
			
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
			
			<th class="text-center">Left</th>
			<th class="text-center">Right</th>
			
		</tr>
		</thead>
		<?php
		$pnums = ceil($num/$plimit);
		if($newp == ''){ $newp = '1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		$sr_no = $starting_no;
		
		$pcl = array(0); 
		$pcr = array(0);
		$cp_l = array(0); 
		$cp_r = array(0);
		$i = 0;
		
		$que = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($que))
		{
			$user_id = $row['user_id'];
			$cp_l[] = $left_point1 = $left_point = $row['left_point'];
			$cp_r[] = $right_point1 = $right_point = $row['right_point'];
			$date_p = $row['date'];
			$date = date('d/m/Y', strtotime($date_p));
			$user_name = get_user_name($user_id);
			
			
			$rest_lp = $rest_rp = $tot_bus = $left_carry = $right_carry = $flush_business = $max_pair = 0;
			
			if(strtotime($date_p) >= strtotime(get_user_binary_qualifier_date($login_id)) ){
				$max_pair = (int)(min($left_point,$right_point)/$per_day_multiple_pair)*$per_day_multiple_pair;  
				// Original Pair Point
				
				// Start Pair Point after deduction 10%
				$max_pair_per = $max_pair*10/100;
				$active_investmnt = get_user_active_investment($user_id);
				
				
				if($active_investmnt < $max_pair_per){ $pair_point = $active_investmnt; }
				else{ $pair_point = $max_pair_per; }
				// End Pair Point after deduction 10%
				
				
				$left_carry = $right_carry = 0;
				
				$right_pair = (int)($right_point/$per_day_multiple_pair);
				$left_pair = (int)($left_point/$per_day_multiple_pair);
				
				if($right_point == 0){ $left_carry = $left_point; }
				elseif($right_point < $left_point){ 
					$left_carry = $left_point-($per_day_multiple_pair*$right_pair);
					$right_carry = $right_point-($per_day_multiple_pair*$right_pair);
				}
				
				if($left_point == 0){ $right_carry = $right_point; }
				elseif($left_point < $right_point){ 
					$right_carry = $right_point-($per_day_multiple_pair*$left_pair);
					$left_carry = $left_point-($per_day_multiple_pair*$left_pair);
				}
				
				$flush_business = user_flush_business($login_id,$max_pair);
				
				$pcl[] = $left_carry;
				$pcr[] = $right_carry;
				
				$rest_lp = $left_point-$pcl[$i];	//Value Stored in Array
				$rest_rp = $right_point-$pcr[$i];	//Value Stored in Array
				if($left_point1 > 0 and $right_point1 > 0){
					$rest_lp = max($left_point1,$cp_l[$i])-min($left_point1,$cp_l[$i]);	//Value Stored in Array
					$rest_rp = max($right_point1,$cp_r[$i])- min($right_point1,$cp_r[$i]);	//Value Stored in Array
				}
				
				$i++;
				//$rest_lp = get_today_network_business($user_id,$date_p,'left_network');
				//$rest_rp = get_today_network_business($user_id,$date_p,'right_network');
				$tot_bus = $rest_lp+$rest_rp;
			}
			?>
			<tr class="text-center">
				<td><?=$sr_no;?></td>
				<td><?=$date;?></td>
				<td><?=$left_point1;?></td>
				<td><?=$right_point1;?></td>
				<td><?=$left_carry;?></td>
				<td><?=$right_carry;?></td>
				<td><?=$rest_lp;?></td>
				<td><?=$rest_rp;?></td>
				<td><?=$tot_bus;?></td>
				<td><?=$max_pair;?></td>
				<td><?=$flush_business;?></td>
			</tr> <?php
			$sr_no++;
		} ?>
	</table> <?php
	pagging_initation_last_five($newp,$pnums,$val,$lpnums,$show_tab);
}
else { echo "<B class='text-danger'>There Are no information to show !</B>"; }	
?>

