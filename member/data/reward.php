<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");
$login_id = $_SESSION['mlmproject_user_id'];
$brw_date = get_business_reward_date();
$network_business = get_lft_rht_business($login_id,$brw_date[0],$brw_date[1]);//
$total_sec_remain = strtotime($brw_date[1])-strtotime($systems_date);
$day_rm = $total_sec_remain/(60*60*24).("&nbsp;Day");
if($day_rm < 0){
	$day_rm = "Over";
}
$left_nb = $network_business[0];
$right_nb = $network_business[1];
$sql = "select * from business_reward where mode = 1 order by id asc";
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0){
	?>
	<table class="table table-bordered table-hover">
		<tr>
			<th>Start Date</th>
			<th><?=$brw_date[0]?></th>
			<th>Left Business</th>
			<th><?=$network_business[0]?></th>
			<th>Remain Time</th>
		</tr>
		<tr>
			<th>End Date</th>
			<th><?=$brw_date[1]?></th>
			<th>Right Business</th>
			<th><?=$network_business[1]?></th>
			<th><?=$day_rm?></th>
		</tr>
	</table>
	<table  class="table table-bordered table-hover">
		
		<tr>
			<th>Reward</th>
			<th class="text-center">
				Business Matching(&#36;)
				<!--<table width="100%">
					<tr><th class="text-center">Left</th><th class="text-center">Right</th></tr>
				</table>-->
			</th>
			<th class="text-center" colspan="2">
				Business Matching Remain(&#36;)
				<table width="100%">
					<tr><th class="text-center">Left</th><th class="text-center">Right</th></tr>
				</table>
			</th>
			<th>Status</th>
		</tr>
		<?php
		$query1 = query_execute_sqli("$sql");
		while($row = mysqli_fetch_array($query1))
		{
			$title = $row['title'];
			$rwleft = $row['left'];
			$rwright = $row['right'];
			$rm_left = $rwleft - $left_nb;
			$status_lf = $status_rh = 0;
			if($left_nb >= $rwleft){
				$rm_left = "Full";
				$status_lf = 1;
			}
			$rm_right = $rwright - $right_nb;
			if($right_nb >= $rwright){
				$rm_right = "Full";
				$status_rh = 1;
			}
			$status = "<B class='color-red'>Wait</B>";
			if($status_rh and $status_lf){
				$status = "<B class='color-cyan'>Achived</B>";
			}
			?>
			<tr>
				<td class="text-left"><?=$title?></td>
				<td class="text-center"><?=$rwleft?></td>
				<!--<td class="text-center"><?=$rwright?></td>-->
				<td class="text-center"><?=$rm_left?></td>
				<td class="text-center"><?=$rm_right?></td>
				<td class="text-left"><?=$status?></td>
			</tr> <?php
		}
		?>
	</table>
	<?php
}
else{ echo "<B class='text-danger'>There is no Reward!</B>";  }
