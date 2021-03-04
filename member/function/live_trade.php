<?php
error_reporting(1);
session_start();
include('../config.php');
//include('../security_web_validation.php');
//include('../condition.php');
?>
<div  class="table-responsive">
<table class="table table-bordered live_trade">
	<thead>
		<tr>
			<th class="text-center">Buy Trade</th>
			<th class="text-center">Sale Trade</th>
		</tr>
	</thead>
	<tr>
		<td>
			<table class="table table-bordered">
				<thead>
					<!--<th class="text-center">User ID</th>-->
					<th class="text-center">Share</th>
					<th class="text-center">Unit Price</th>
					<th class="text-center">Total Amount</th>
				</thead>
				<?php
				$sql = "select sum(`total_amount`) `total_amount`,sum(`share`) `share`,unit_amount from (
				SELECT t1.*, t2.username FROM trade_buy t1 
				LEFT JOIN users t2 ON t1.user_id = t2.id_user
				WHERE t1.type = 1 AND t1.mode IN(0) ORDER BY t1.unit_amount DESC 
				) t1 group by unit_amount ORDER BY unit_amount DESC LIMIT 10";
				$query1 = query_execute_sqli($sql);
				$i = 1;
				while($row = mysqli_fetch_array($query1)){
					$bg_color = $i%2 ? 'bg-success' : 'bg-danger';
					?>
					<tr class="text-center <?=$bg_color?>">
						<!--<td><?=$row["username"];?></td>-->
						<td><?=$row["share"];?></td>
						<td>&#36;<?=$row["unit_amount"];?></td>
						<td>&#36;<?=round($row["total_amount"],2);?></td>
					</tr>
					<?php
					$i++;
				}
				mysqli_free_result($query1);
				?>
			</table>
		</td>
		<td>
			<table class="table table-bordered">
				<thead>
					<!--<th class="text-center">User ID</th>-->
					<th class="text-center">Share</th>
					<th class="text-center">Unit Price</th>
					<th class="text-center">Total Amount</th>
				</thead>
				<?php
				$sql = "select sum(`total_amount`) `total_amount`,sum(`share`) `share`,unit_amount from (
				SELECT t1.*, t2.username FROM trade_buy t1 
				LEFT JOIN users t2 ON t1.user_id = t2.id_user
				WHERE t1.type = 2 AND t1.mode IN(0) ORDER BY t1.unit_amount asc
				) t1 group by unit_amount ORDER BY unit_amount asc  LIMIT 10";
				$query2 = query_execute_sqli($sql);
				$i = 1;
				while($row = mysqli_fetch_array($query2)){
					$bg_color = $i%2 ? 'bg-primary' : 'bg-warning';
					?>
					<tr class="text-center <?=$bg_color?>">
						<!--<td><?=$row["username"];?></td>-->
						<td><?=$row["share"];?></td>
						<td>&#36;<?=$row["unit_amount"];?></td>
						<td>&#36;<?=round($row["total_amount"],2);?></td>
					</tr>
					<?php
					$i++;
				}
				mysqli_free_result($query2);
				?>
			</table>
		</td>
	</tr>
</table>
</div>