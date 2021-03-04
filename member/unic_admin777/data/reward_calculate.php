<?php
include('../../security_web_validation.php');
?>
<?php
session_start();

include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
$brw_date = get_business_reward_date();
if(isset($_REQUEST['submit']) and $_REQUEST['submit'] == "Generate Report")
{
	$reward_setting = get_business_reward_setting();
	$total_rw = count($reward_setting);
	$done = 0;
	$g = 0;
	if(!isset($_SESSION['getting_reward_member'])){
		$_SESSION['getting_reward_member'] = array();
		for($k = 0; $k < $total_rw; $k++){
			$sql = "select * from users where type='B'";
			$query = query_execute_sqli($sql); 
			while($row = mysqli_fetch_array($query)){
				$user_id = $row['id_user'];
				$rw_id = $reward_setting[$k][2];
				$left_nb = $right_nb = 0;
				$sql = "select * from income 
						where user_id='$user_id' and incomed_id='$rw_id' and type='$income_type[6]'";
				$get_num = mysqli_num_rows(query_execute_sqli($sql));
				if($get_num == 0){
					$network_business = get_lft_rht_business($user_id,$brw_date[0],$brw_date[1]);//
					$left_nb = $network_business[0];
					$right_nb = $network_business[1];
				}
				if($reward_setting[$k][0] <= $left_nb and $reward_setting[$k][0] <= $right_nb){
					$done = 1;
					$get_reward[$g][0] = $user_id;
					$get_reward[$g][1] = $reward_setting[$k][2];//reward id
					$get_reward[$g][2] = $reward_setting[$k][3];//reward title
					$get_reward[$g][3] = $row['username'];
					$get_reward[$g][4] = $left_nb;
					$get_reward[$g][5] = $right_nb;
					$get_reward[$g][6] = $reward_setting[$k][0];//reward matching
					$g++;
				}
			}
		}
		if($done){
			$_SESSION['getting_reward_member'] = $get_reward;
		}
		
	}
	else{
		$get_reward = $_SESSION['getting_reward_member'];
		if(!empty($get_reward))
		$done = 1;
	}
	if($done){
		$getting_member = count($get_reward);
		?>
		<form name="setting" method="post" action="index.php?page=reward_calculate">
			<input type="submit" name="submit" value="Distribute Reward" class="btn btn-info"  />
		</form>
		<table width="100%">
			<tr><td colspan="6">&nbsp;</td></tr>
			<tr>
				<th class="text-center">S.No.</th>
				<th class="text-center">Username</th>
				<th class="text-center">Left Business</th>
				<th class="text-center">Right Business</th>
				<th class="text-center">Business Matching</th>
				<th class="text-center">Reward Title</th>
			</tr>
			<?php
				for($m = 0; $m < $getting_member; $m++){
					?>
					<tr>
						<td><?=($m+1)?></td>
						<td><?=$get_reward[$m][3]?></td>
						<td><?=$get_reward[$m][4]?></td>
						<td><?=$get_reward[$m][5]?></td>
						<td><?=$get_reward[$m][6]?></td>
						<td><?=$get_reward[$m][2]?></td>
					</tr>
					<?php
				}
			?>
		</table>
		<?php
	}
	else{
		echo "<B style='color:#FF0000;'>There Have No Member For Getting Rewards!</B>";
	}
		 	 	 	 	 	
}
elseif(isset($_REQUEST['submit']) and $_REQUEST['submit'] == "Distribute Reward"){
	echo "Distribute Reward";
	$get_reward = $_SESSION['getting_reward_member'];
	$getting_member = count($get_reward);
	for($m = 0; $m < $getting_member; $m++){
		$member_id = $get_reward[$m][0];
		$rw_id = $get_reward[$m][1];
		$rw_amount = $get_reward[$m][6];
		$sql = "insert into income(user_id,amount,date,type,incomed_id,mode)
				values('$member_id','$rw_amount','$systems_date','$income_type[5]','$rw_id','1');";
		query_execute_sqli($sql);
	}
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=reward_calculate\"";
	echo "</script>";	
}
else{
	$_SESSION['getting_reward_member'] = "";
	unset($_SESSION['getting_reward_member']);
	$s_date = $brw_date[0];
	$e_date = $brw_date[1];
	$sql = "select * from income where date between '$s_date' and '$e_date' and type='$income_type[6]'";
	$num = mysqli_num_rows(query_execute_sqli($sql));
	if($num > 0){
		echo "<b class='color-cyan'>Reward Calculated<b>";
	}
	else{
		$sql = "select * from business_reward where mode = 1 order by id asc";
		$query = query_execute_sqli($sql);
		$totalrows = mysqli_num_rows($query);
		if($totalrows != 0){
		?>
		<form name="setting" method="post" action="index.php?page=reward_calculate">
			<input type="submit" name="submit" value="Generate Report" class="btn btn-info"  />
		</form>
		<table width="100%">
			<tr>
				<th class="text-center">Start Date</th>
				<th class="form-control"><?=$brw_date[0]?></th>
				<th class="text-center">End Date</th>
				<th class="form-control"><?=$brw_date[1]?></th>
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>
		<table  width="100%">
			<tr>
				<th class="text-center">Title</th>
				<th class="text-center">
					Business Matching(&#36;)
				</th>
			</tr>
			<?php
			$query1 = query_execute_sqli("$sql");
			while($row = mysqli_fetch_array($query1))
			{
				$title = $row['title'];
				$rwleft = $row['left'];
				$rwright = $row['right'];
				?>
				<tr>
					<td><?=$title?></td>
					<td align="center"><?=$rwleft?></td>
				</tr> <?php
			}
			?>
		</table>
		<?php
		}
		else{ echo "<B style='color:#FF0000;'>There is no Reward!</B>";  }
	}
}
?>