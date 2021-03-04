<?php
ini_set('display_errors','on');
include("../config.php");
include("../function/functions.php");

$user_id = $_REQUEST['id'];
$username = $_REQUEST['username'];


$sqlk = "SELECT CONCAT(left_network, ',' , right_network) FROM network_users WHERE user_id IN($user_id)";
$result = rtrim(mysqli_fetch_array(query_execute_sqli($sqlk))[0],',');
$sql = "SELECT t1.*,t2.date reg_date,t2.update_fees ,t3.username spon_id FROM users t1 
LEFT JOIN reg_fees_structure t2 ON t2.user_id = t1.id_user
LEFT JOIN users t3 ON t1.real_parent = t3.id_user
WHERE t1.id_user in ($result) GROUP BY t1.id_user ORDER BY t1.id_user ASC";
mysqli_free_result($result);
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
mysqli_free_result($query);
if($totalrows != 0)
{ ?>
	<table class="table table-bordered">
		<thead>
		<tr>
			<th colspan="10">
				Total Downline Members of <B class="text-danger"><?=$username?></B> 
				<i class="fa fa-arrow-right"></i> <span class='label label-info'><?=$totalrows?></span>
			</th>
		</tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">Joining Date</th>
			<th class="text-center">Sponsor</th>
			<th class="text-center">Actual Side</th>
			<th class="text-center">Total Invesment</th>
			<th class="text-center">Left Business</th>
			<th class="text-center">Right Business</th>
			<th class="text-center">Status</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		$que = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($que))
		{ 	
			$user_id = $row['id_user'];
			$username = $row['username'];
			$position = $row['position'];
			$type = $row['type'];
			$date = $row['date'];
			$reg_date = $row['reg_date'];
			$spon_id = $row['spon_id'];
			$update_fees = $row['update_fees'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			
			if($date > 0){
				$join_date = date('d/m/Y', strtotime($row['date']));
			}
			
			$status = "<span class='label label-primary'>Active</span>";
			if($reg_date == ''){ 
				$status = "<span class='label label-danger'>Inactive</span>";
				$update_fees = "0.00";
			}
			
			if($position == 0){ $pos = 'Left'; }
			else{ $pos = 'Right'; }
			
			//$left_bus = get_network_lr_business($user_id,'left_network');
			//$right_bus = get_network_lr_business($user_id,'right_network');
			
			$net_mem = get_network_lr_business($user_id);
			$left_bus = $net_mem[0];
			$right_bus = $net_mem[1];
			?>
			<tr class="text-center">
				<td><?=$sr_no?></td>
				<td><?=$username;?></td>
				<td><?=$name?></td>
				<td><?=$join_date?></td>
				<td><?=$spon_id?></td>
				<td><?=$pos?></td>
				<td>&#36;<?=$update_fees?></td>
				<td><?=$left_bus?></td>
				<td><?=$right_bus?></td>
				<td><?=$status?></td>
			</tr> <?php
			$sr_no++;
		}
		mysqli_free_result($que);
		?>
	</table> <?php
}
else{ echo "<B class='text-danger'>No Network Member Found!</B>";  }
?>
