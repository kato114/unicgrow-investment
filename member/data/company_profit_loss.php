<?php
include('../security_web_validation.php');
?>
<?php	

$sql = "SELECT * FROM company_profitloss ORDER BY id DESC LIMIT 10";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0)
{ ?>
	<div class="table-responsive">
	<table class="table table-bordered" style="overflow:scroll; background-color:#fff;">
		<thead>
		<tr>
			<!--<th class="text-center" style="padding:0px;">Sr. No.</th>-->
			<th class="text-center" style="padding:0px;">Currency </th>
			<th class="text-center" style="padding:0px;">Value </th>
			<th class="text-center" style="padding:0px;">Rate</th>
			<th class="text-center" style="padding:0px;">Profit</th>
			<th class="text-center" style="padding:0px;">Date</th>
		</tr>
		</thead>
		<?php
		$sr = 1;
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$currency = $row['currency'];
			$value = $row['value'];
			$rate = $row['rate'];
			$profit = $row['profit'];
			$date = date('d/m/Y', strtotime($row['date']));
			
			$class = 'color-info';
			$updown = "<i class='fa fa-level-up'></i>";
			if($profit < $value) 
			{
				$class = 'color-red';
				$updown = "<i class='fa fa-level-down'></i>";
			}
			?>
			<tr class="text-center <?=$class?>">
				<!--<td style="padding:0px;"><?=$sr;$sr++;?> </td>-->
				<td style="padding:0px;"><?=$currency;?></td>
				<td style="padding:0px;">&#x24; <?=$value;?> </td>
				<td style="padding:0px;"><?=$rate;?></td>
				<td style="padding:0px;">&#x24; <?=$profit;?></td>
				<td style="padding:0px;"><?=$updown?>  <?=$date;?></td>
			</tr> <?php
		} ?>
	</table>
	</div>
<?php
}
?>
<!--<style>
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{ padding:0px;}
</style>-->