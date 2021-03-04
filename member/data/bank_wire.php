<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");


$user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = "12";

if($newp == '')
{
	$title = 'Display';
	$message = 'Display Bank Wire';
	data_logs($user_id,$title,$message,0);
}


$query = query_execute_sqli("select * from daily_income where user_id = '$user_id' and paid = 1 ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{
	while($row1 = mysqli_fetch_array($query))
	{ 
		$tatal_amt = $tatal_amt+$row1['income'];
	}
		$tatal_amt = $tatal_amt-($tatal_amt*$daily_roi_tax)/100;
?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th width="50%" class="text-center">Total Paid</th>
			<th class="text-center">&#36; <?=$tatal_amt;?></th>
		</tr>
		</thead>
		<tr>
			<th class="text-center">Date</th> 
			<th class="text-center">Amount</th>
		</tr>
<?php

	$pnums = ceil ($totalrows/$plimit);
	
	if ($newp==''){ $newp='1'; }
	
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
	
	
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }

	$query1 = query_execute_sqli("select * from daily_income where user_id = '$user_id' and paid = 1 LIMIT $start,$plimit ");
	while($row = mysqli_fetch_array($query1))
	{
		$date = $row['date'];
		$amount = $row['income'];
		$amount = $amount-($amount*$daily_roi_tax)/100;
		
		print "
			<tr>
				<td class=\"text-center\">$date</td>
				<td class=\"text-center\">&#36; $amount</td>
			</tr>";
		$j = 1;
	}
?>	
	</table>
	<div class="dataTables_footer">
		<div id="sorting-advanced_paginate" class="dataTables_paginate paging_full_numbers">
	<?php
		if ($newp>1)
		{ 
		?>
			<a id="sorting-advanced_previous" class="previous paginate_button paginate_button_disabled" 
			href="<?="index.php?page=bank_wire&p=".($newp-1);?>">Previous</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ 
			?>
				<a class="paginate_button" href="<?="index.php?page=bank_wire&p=$i";?>">
					<?php print_r("$i");?>
				</a>
			<?php 
			}
			else
			{  ?> <a class="paginate_active" ><?php print_r("$i"); ?></a>  <?php   }
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a id="sorting-advanced_next" class="next paginate_button" href="<?="index.php?page=bank_wire&p=".($newp+1);?>">Next</a>
		<?php  
		} 
		print "</div></div>";
}		
else{ echo "<B style=\"color:#FF0000; font-size:12pt;\">There are no information to show !!</B>";  }

?>
