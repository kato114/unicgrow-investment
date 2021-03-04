<?php
include('../security_web_validation.php');
?>
<img src="https://bitcoincharts.com/charts/chart.png?width=940&m=bitstampUSD&SubmitButton=Draw&r=60&i=&c=0&s=&e=&Prev=&Next=&t=S&b=&a1=&m1=10&a2=&m2=25&x=0&i1=&i2=&i3=&i4=&v=1&cv=0&ps=0&l=0&p=0&"><br /><br />
<?php	
$sql = "SELECT * FROM trade_history";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0)
{ ?>
	<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
		<tr>
			<!--<th class="text-center">Sr. No.</th>-->
			<th class="text-center">Currency </th>
			<!--<th class="text-center">BTC Value </th>-->
			<th class="text-center">Last Trade</th>
			<th class="text-center">&nbsp;</th>
		</tr>
		</thead>
		<?php
		$sr = 1;
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$currency = $row['currency'];
			$rate = $row['rate'];
			$date = date('d/m/Y', strtotime($row['date']));
			
			//$cur_rate = get_ETH_DOGE_LTC_BTC_TO_IN_USD($currency,'BTC');
			$curr_rate = array_shift(array_values($cur_rate));
			
			
			?>
			<tr class="text-center">
				<!--<td style="padding:0px;"><?=$sr;$sr++;?> </td>-->
				<td><?=$currency;?></td>
				<!--<td><?=$curr_rate;?></td>-->
				<td><?=$rate;?></td>
				<td>
					<input type="submit" name="buy" value="Buy" class="btn" />&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sale" value="Sale" class="btn" />
				</td>
			</tr> <?php
		} ?>
	</table>
	</div>
<?php
}
?>