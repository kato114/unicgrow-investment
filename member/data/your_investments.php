<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
$id = $_SESSION['mlmproject_user_id'];
unset($_SESSION['s_date']);
unset($_SESSION['e_date']);

$newp = $_GET['p'];
$plimit = "15";

if($newp == '')
{
	$title = 'Display';
	$message = 'Display User Investments';
	data_logs($id,$title,$message,0);
}


$tamount = 0;
$sql = "SELECT t1.`user_id`,t1.`by_id`,t1.`dr`,t1.`particular`,t2.id,t2.user_id topup_id,t2.update_fees
		,t3.username,t2.date,t2.by_wallet,t2.profit 
		FROM `ledger` t1
		left join reg_fees_structure t2 on t1.by_id = t2.id
		left join users t3 on t2.user_id = t3.id_user
		WHERE t1.user_id='$id' and t1.particular NOT LIKE 'Debit Fund For TOPUP FROM Company Wallet' and t2.user_id is not null ";
$q = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($q);
if($totalrows == 0)
{
	echo "<B class='text-danger'>There are no information to show !!</B>"; 
}
else
{
	while($row = mysqli_fetch_array($q))
	{
		$update_fees = $row['dr'];
		$tamount = $tamount+$update_fees;
	}		
	?>
	<table class="table table-bordered table-hover">
		<thead>
			<tr><th colspan="6">Total Investment:- &nbsp; &#36; <?=$tamount;?></th></tr>
		</thead>
		<tr>
			<th class="text-center" width="10%">Sr. No</th>
			<th class="text-center">Date</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Profit</th>
			<th class="text-center">Wallet By</th>
			<th class="text-center">Top-Up To</th>
		</tr>
	  

	<?php 
	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	$i = 1;
	$q = query_execute_sqli("$sql LIMIT $start,$plimit ");
	while($r = mysqli_fetch_array($q))
	{
		$date = $r['date'];
		$type = $r['by_wallet'];
		$reg_fees = $r['dr'];
		$update_fees = $r['dr'];
		$rcw_id = $r['rcw_id'];
		$profit = $r['profit'];
		$amount = $update_fees;
		$topupto = $r['username'];
		$particular = $r['particular'];
		
		
		
		
		/*$particular = explode("FROM", $particular);
		$particul = $particular[1];
		
		$par = explode("BY", $particul);
		$para = $par[1];
		//echo $BY[1]; // piece1
		$status = 'ACTIVATION WALLET';
		if($para == 'Company WALLET')
			$status = 'Company WALLET';*/
			
			
			
		?>
			<tr>
				<td class="text-center"><?=$i;?></td>
				<td class="text-center"><?=$date;?></td>
				<td class="text-center">&#36; <?=$amount;?></td>
				<td class="text-center"><?=$profit;?></td>
				<td class="text-center"><?=$particular;?></td>
				<td class="text-center"><?=$topupto;?></td>
				<!--<td class="span1 text-center"><?=$total_days;?></td>
				<td class="span1 text-center">
					<form method="post" action="data/img.php" target="_blank">
						<input type="hidden" name="topup_id" value="<?=$reg_fees_id; ?>">
						<input type="submit" name="certificate" value="Certificate" class="btn btn-primary">
					</form>
					<form method="post" action="index.php?page=calender" target="_blank">
						<input type="hidden" name="s_date" value="<?=$s_date?>">
						<input type="hidden" name="e_date" value="<?=$e_date?>">
						<input type="hidden" name="amount" value="<?=$amount?>">
						<input type="submit" name="calender" value="Calender" class="btn btn-primary">
					</form>
		 		</td>-->
		  	</tr>
		<?php
	$i++;
	}
	?>
	</table>
	<div class="col-xs-12">
		<div class="dataTables_paginate paging_bootstrap">
		<ul class="pagination">
		<?php
		if ($newp>1)
		{ ?> 
			<li class="prev"><a href="<?="index.php?page=other_activation_history&p=".($newp-1);?>">&larr; Previous</a></li>
			<?php  
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?> <li><a href="<?="index.php?page=other_activation_history&p=$i";?>"><?php print_r("$i");?></a></li> <?php  }
			else
			{ ?><li class="active"><a href="#"><?php print_r("$i"); ?></a></li> <?php }
		} 
		if ($newp<$pnums) 
		{ ?> 
			<li class="next"><a href="<?="index.php?page=other_activation_history&p=".($newp+1);?>">Next &rarr;</a></li> 
			<?php  
		} ?>
		</ul>
		</div>
	</div>
	<?php   
}	
