<?php
$user_id = $_SESSION['mlmproject_user_id'];
/*$sql = "SELECT * FROM trade_buy LIMIT 10"; //WHERE mode IN (0) GROUP BY DATE(date) 
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);


if($num > 0){*/
	$cur_br = current_share_rate(2);
	$cur_sr = current_share_rate(1);
	 ?>
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Share Chart</h5>
				<div class="pull-left" style="margin-left:30%">
					<B>Buy Rate : &#36;<?=$cur_br?> &nbsp;&nbsp;&nbsp;&nbsp;Sale Rate : &#36;<?=$cur_sr?></B>
				</div>
				<div class="ibox-tools">
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					<a href="#"><i class="fa fa-wrench"></i></a>
					<a class="close-link"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="ibox-content">
				<div><canvas id="lineChart" height="140"></canvas></div>
			</div>
			<div class="label label-buy">&nbsp;</div> <B>Buy</B><br /><br />
			<div class="label label-sale">&nbsp;</div> <B>Sale</B>
			<!--<div class="col-md-6">
				<div class="label label-buy">&nbsp;</div> <B>Buy</B><br /><br />
				<div class="label label-sale">&nbsp;</div> <B>Sale</B>
			</div>
			<div class="text-right">
				<div class="label label-buy">&nbsp;</div> <B>Buy Rate : <?=$cur_br?></B><br /><br />
				<div class="label label-sale">&nbsp;</div> <B>Sale Rate : <?=$cur_sr?></B>
			</div>-->
		</div>
	</div> <?php
//} 
function current_share_rate($type){
	$sql = "SELECT * FROM trade_buy WHERE id IN(SELECT MAX(id) FROM trade_buy WHERE mode=0 AND type=$type)";
	$bq = query_execute_sqli($sql);
	$num = mysqli_num_rows($bq);
	$cur_rate = 1;
	if($num > 0){
		$cur_rate = mysqli_fetch_array($bq)['unit_amount'];
	}
	mysqli_free_result($bq);
	return $cur_rate;
}
//else{ echo "<B class='text-danger'>There are no info to show</B>"; } 
?>
