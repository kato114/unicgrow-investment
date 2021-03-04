<?php
include('../security_web_validation.php');

session_start();
include("condition.php");
$login_id = $_SESSION['mlmproject_user_id'];


$newp = $_GET['p'];
$plimit = "15";

$sql = "SELECT t1.*,t2.update_fees,t3.username FROM ledger t1
LEFT JOIN reg_fees_structure t2 ON t1.by_id = t2.id
LEFT JOIN users t3 ON t2.user_id = t3.id_user
WHERE t1.user_id = '$login_id' and t2.by_wallet = 1 and t1.dr > 0 ";
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
		$update_fees = $row['update_fees'];
		$reg_fees = $row['reg_fees'];
		if($update_fees == 0)
			$tamount = $tamount+$reg_fees;
		else
			$tamount = $tamount+$update_fees;
	}		
	?>
	<table class="table table-bordered table-hover">
		<thead>
			<tr><th colspan="6">Total Amount:- &nbsp; &#36; <?=$tamount;?></th></tr>
		</thead>
		<tr>
			<th class="text-center" width="10%">Sr. No</th>
			<th class="text-center">Date</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Wallet By</th>
			<th class="text-center">Top-Up To</th>
		</tr>
	  

	<?php 
	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;

	$q = query_execute_sqli("$sql LIMIT $start,$plimit ");
	while($r = mysqli_fetch_array($q))
	{
		$date = $r['date_time'];
		$type = $r['by_wallet'];
		$reg_fees = $r['dr'];
		$update_fees = $r['update_fees'];

		$user_id = $r['user_id'];
		
		$topupto = $r['username'];
		
		if($type == 0)
			$status = "Activation Wallet";
		else
			$status = "Grow Well Wallet";
		
		?>
			<tr>
				<td class="text-center"><?=$starting_no;?></td>
				<td class="text-center"><?=$date;?></td>
				<td class="text-center">&#36; <?=$update_fees;?></td>
				<td class="text-center"><?=$status;?></td>
				<td class="text-center"><?=$topupto;?></td>
		  	</tr>
		<?php
	$starting_no++;
	}
	?>
	</table>
	<div class="col-xs-12">
		<div class="dataTables_paginate paging_bootstrap">
		<ul class="pagination">
		<?php
		if ($newp>1)
		{ ?> 
			<li class="prev"><a href="<?="index.php?page=activation_history&p=".($newp-1);?>">&larr; Previous</a></li>
			<?php  
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?> <li><a href="<?="index.php?page=activation_history&p=$i";?>"><?php print_r("$i");?></a></li> <?php  }
			else
			{ ?><li class="active"><a href="#"><?php print_r("$i"); ?></a></li> <?php }
		} 
		if ($newp<$pnums) 
		{ ?> 
			<li class="next"><a href="<?="index.php?page=activation_history&p=".($newp+1);?>">Next &rarr;</a></li> 
			<?php  
		} ?>
		</ul>
		</div>
	</div>
	<?php   
}	
