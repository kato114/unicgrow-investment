<?php
include("../../../config.php");
include("../../../function/functions.php");
include("../../../function/setting.php");
include("../../../function/direct_income.php");

$day = date("l",strtotime($systems_date_time));
if($day == $lottery_result_day){
	$alrm = $auto_lottery_result['member'];
	$alrp = $auto_lottery_result['percent'];
	$auto_lottery_result = array();
	$sql = "select *  from `income` where date = '$systems_date' and type = '$income_type[7]'";
	$quer = query_execute_sqli($sql);
	$num = mysqli_num_rows($quer);
	if($num == 0){
		$sql = "select COALESCE(COUNT(t2.user_id),0) tmember,COALESCE(sum(t2.amount),0) amount,t2.mode  
				from `lottery_ticket` t2 where DATE_FORMAT(t2.`rdate`,'%Y-%m-%d')  = '$systems_date'";
		$quer = query_execute_sqli($sql);
		while($ro = mysqli_fetch_array($quer)){
			$tmember = $ro['tmember']; 
			$tamount = $ro['amount']; 
		}
		if($tmember > $lottery_distribute[4]){
			$auto_lottery_result['member'] = array($alrm[0],$alrm[1],$alrm[2],$alrm[3],$alrm[4]);
			$auto_lottery_result['percent'] = array($alrp[0],$alrp[1],$alrp[2],$alrp[3],$alrp[4]);
		}
		elseif($tmember > $lottery_distribute[3]){
			$auto_lottery_result['member'] = array($alrm[0],$alrm[1],$alrm[2],$alrm[3],0);
			$auto_lottery_result['percent'] = array($alrp[0],$alrp[1],$alrp[2],$alrp[3],0);
		}
		elseif($tmember > $lottery_distribute[2]){
			$auto_lottery_result['member'] = array($alrm[0],$alrm[1],$alrm[2],0,0);
			$auto_lottery_result['percent'] = array($alrp[0],$alrp[1],$alrp[2],0,0);
		}
		elseif($tmember > $lottery_distribute[1]){
			$auto_lottery_result['member'] = array($alrm[0],$alrm[1],0,0,0);
			$auto_lottery_result['percent'] = array($alrp[0],$alrp[1],0,0,0);
		}
		elseif($tmember > $lottery_distribute[0]){
			$auto_lottery_result['member'] = array($alrm[0],0,0,0,0);
			$auto_lottery_result['percent'] = array($alrp[0],0,0,0,0);
		}
		$auto_team = array_sum($auto_lottery_result['member']);
		$auto_tm_per = array_sum($auto_lottery_result['percent']);
		if($tmember >= $auto_team and $auto_tm_per <= 100){
			$result = set_lottery_prize($systems_date,$tamount,$tmember,$auto_lottery_result['member'],$auto_lottery_result['percent'],$systems_date_time);
			if($result){
				echo "<B class='text-success'>Lottery Ticket Result Successfully Announced !!</B>";
				$sql = "select * from lottery_ticket where DATE_FORMAT(rdate,'%Y-%m-%d') = '$systems_date' and mode=1 and `rank` >0 order by `rank` asc ";
				$quer = query_execute_sqli($sql);
				?>
				<table class="table table-bordered table-hover" border="1">
				<thead>
				<tr>
					<th class="text-center">Sr. no.</th>
					<th class="text-center">Date</th>
					<th class="text-center">Ticket No.</th>
					<th class="text-center">Prize</th>
					<th class="text-center">Rank</th>
				</tr>
				</thead>
				<?php
				$sr_no = 1;
				while($row = mysqli_fetch_array($quer))
				{
					$tmember = $row['tmember']; 
					$rank = round($row['rank'],2); 
					$date = date('d/m/Y' , strtotime($row['date']));
					$ticket_no = $row['ticket_no'];
					$ramount = round($row['ramount'],2); 
					?>
					<tr class="text-center">
						<td><?=$sr_no;?></td>
						<td><?=$date?></td>
						<td><?=$ticket_no?></td>
						<td>&#36;<?=$ramount?></td>
						<td><?=$rank?></td>
					</tr> <?php
					$sr_no++;
				}
				?>
				</table> <?php
			}
			else
				echo "<h4>Somthing Goes Wrong !!</h4>";
		}
		else{
			echo "<h4>Systems Generate Error, So Please Announce Manual !!</h4>";
		}
	}
	else{
		echo "<h4>Today Result Announce !!</h4>";
	}
}