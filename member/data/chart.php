<?php
$user_id = $_SESSION['mlmproject_user_id'];
$sql = "SELECT * FROM trade_buy WHERE user_id = '$user_id' AND mode IN (0) GROUP BY DATE(date) LIMIT 10";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0){ ?>
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Share Chart</h5>
				<div class="ibox-tools">
					<a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
					<a href="#"><i class="fa fa-wrench"></i></a>
					<a class="close-link"><i class="fa fa-times"></i></a>
				</div>
			</div>
			<div class="ibox-content">
				<div><canvas id="lineChart" height="140"></canvas></div>
			</div>
			
			<div class="label label-buy">&nbsp;</div> Buy<br /><br />
			<div class="label label-sale">&nbsp;</div> Sale
		</div>
	</div> <?php
}
else{ echo "<B class='text-danger'>There are no info to show</B>"; }
