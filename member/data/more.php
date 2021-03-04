<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");


if($_REQUEST['type'] == "epin")
{
$newp = $_GET['p'];
$plimit = "12";

$user_id = $_SESSION['mlmproject_user_id'];
$sql = "select * from e_pin where user_id = '$user_id' and mode = 0 ";
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{?>
	<table class="table table-bordered table-hover">
		<thead>
		<tr>
			<th class="text-center">E-pin</th>
			<th class="text-center">Date</th>
			<th class="text-center">Amount</th>
			<th class="text-center">Used Id</th>
			<th class="text-center">Used Date</th>
		</tr>
		</thead>
<?php
	  $pnums = ceil ($totalrows/$plimit);

		if ($newp==''){ $newp='1'; }
		
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }	
	$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
	while($row = mysqli_fetch_array($query))
	{
		$epin = $row['epin'];
		$date = $row['date'];
		$amount = $row['amount'];
		$used_id = get_full_name($row['used_id']);
		$used_date = $row['used_date'];
		$tot_amt = $tot_amt + $amount;
		print "
			<tr>
				<td class=\"text-center\"><small>$epin</small></td>
				<td class=\"text-center\"><small>$date</small></td>
				<td class=\"text-center\"><small>$amount</small></td>
				<td class=\"text-center\"><small>$used_id</small></td>
				<td class=\"text-center\"><small>$used_date</small></td>
			</tr>";
	}
	print "
		<tr>
			<td class=\"text-center\" colspan=\"2\">Total</td>
			<td class=\"text-center\" colspan=\"2\">$tot</td>
		 </tr>";
				
	?>
	</table>
	<div class="dataTables_footer">
		<div id="sorting-advanced_paginate" class="dataTables_paginate paging_full_numbers">
	<?php
		if ($newp>1)
		{ 
		?>
			<a id="sorting-advanced_previous" class="previous paginate_button paginate_button_disabled" 
			href="<?="index.php?page=more&type=epin&p=".($newp-1);?>">Previous</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ 
			?>
				<a class="paginate_button" href="<?="index.php?page=more&type=epin&p=$i";?>">
					<?php print_r("$i");?>
				</a>
			<?php 
			}
			else
			{  ?> <a class="paginate_active" ><?php print_r("$i"); ?></a>  <?php   }
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a id="sorting-advanced_next" class="next paginate_button" href="<?="index.php?page=more&type=epin&p=".($newp+1);?>">Next</a>
		<?php  
		} 
		print "</div></div>";
	}
	else 
	{
		echo "<B style=\"color:#FF0000; font-size:12pt;\">There is no E-pin to show !!</B>"; 
		
	}
}

if($_REQUEST['type'] == "bank_history")
{
$newp = $_GET['p'];
$plimit = "12";

$user_id = $_SESSION['mlmproject_user_id'];
$sql = "select * from income where user_id = '$user_id' and type!=2  order by id desc";
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
<table class="table table-bordered table-hover">
	<thead>
	<tr>
		<th class="text-center">S.No.</th>
		<th class="text-center">Name</th>
		<th class="text-center">Amount</th>
		<th class="text-center">Date</th>
	</tr>
	</thead>
<?php
	  $pnums = ceil ($totalrows/$plimit);

		if ($newp==''){ $newp='1'; }
		
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }	
	$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
	$i = 0;
	while($row = mysqli_fetch_array($query))
	{
		$giver = $row['incomed_id'];
		$date = $row['date'];
		$amount = $row['amount'];
		$tot = $tot + $amount;
		$j = $i+1;
		$name =  get_user_name($giver);
		if($giver == 0)
		$name = "Admin";
		print "
			<tr>
				<td class=\"text-center\"><small>$j</small></td>
				<td class=\"text-center\"><small>$name</small></td>
				<td class=\"text-center\"><small>$amount</small></td>
				<td class=\"text-center\"><small>$date</small></td>
			</tr>";
		$i++;
	}
	print "
		<tr>
			<td>&nbsp;</td>
			<td class=\"text-center\">Total</td>
			<td class=\"text-center\">$tot</td>
			<td>&nbsp;</td>
		 </tr>";
	?>
	</table>
	<div class="dataTables_footer">
		<div id="sorting-advanced_paginate" class="dataTables_paginate paging_full_numbers">
	<?php
		if ($newp>1)
		{ 
		?>
			<a id="sorting-advanced_previous" class="previous paginate_button paginate_button_disabled" 
			href="<?="index.php?page=more&type=bank_history&p=".($newp-1);?>">Previous</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ 
			?>
				<a class="paginate_button" href="<?="index.php?page=more&type=bank_history&p=$i";?>">
					<?php print_r("$i");?>
				</a>
			<?php 
			}
			else
			{  ?> <a class="paginate_active" ><?php print_r("$i"); ?></a>  <?php   }
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a id="sorting-advanced_next" class="next paginate_button" href="<?="index.php?page=more&type=bank_history&p=".($newp+1);?>">Next</a>
		<?php  
		} 
		print "</div></div>";
	}
	else 
	{  echo "<B style=\"color:#FF0000; font-size:12pt;\">There is no E-pin to show !</B>"; }
}


if($_REQUEST['type'] == "roi_history")
{
$newp = $_GET['p'];
$plimit = "12";

$user_id = $_SESSION['mlmproject_user_id'];
$sql = "select * from income where user_id = '$user_id' and type=2 order by id desc ";
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{ ?>
	<table class="table table-bordered table-hover">
	<thead>
	<tr>
		<th class="text-center">S.No.</th>
		<th class="text-center">Name</th>
		<th class="text-center">Amount</th>
		<th class="text-center">Date</th>
	</tr>
	</thead>
	<?php
	  $pnums = ceil ($totalrows/$plimit);

		if ($newp==''){ $newp='1'; }
		
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }	
	$query = query_execute_sqli("$sql LIMIT $start,$plimit ");
	$i = 0;
	while($row = mysqli_fetch_array($query))
	{
		$giver = $row['incomed_id'];
		$date = $row['date'];
		$amount = $row['amount'];
		$tot = $tot + $amount;
		$j = $i+1;
		$name =  get_user_name($giver);
		if($giver == 0)
		$name = "Admin";
		
		print "
			<tr>
				<td class=\"text-center\"><small>$j</small></td>
				<td class=\"text-center\"><small>$name</small></td>
				<td class=\"text-center\"><small>$amount</small></td>
				<td class=\"text-center\"><small>$date</small></td>
			</tr>";
		$i++;
	}
	print "
		<tr>
			<td class=\"text-center\" colspan=\"2\">Total</td>
			<td class=\"text-center\" colspan=\"2\">$tot</td>
		 </tr>";
	
?>
	</table>
	<div class="dataTables_footer">
		<div id="sorting-advanced_paginate" class="dataTables_paginate paging_full_numbers">
	<?php
		if ($newp>1)
		{ 
		?>
			<a id="sorting-advanced_previous" class="previous paginate_button paginate_button_disabled" 
			href="<?="index.php?page=more&type=roi_history&p=".($newp-1);?>">Previous</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ 
			?>
				<a class="paginate_button" href="<?="index.php?page=more&type=roi_history&p=$i";?>">
					<?php print_r("$i");?>
				</a>
			<?php 
			}
			else
			{  ?> <a class="paginate_active" ><?php print_r("$i"); ?></a>  <?php   }
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a id="sorting-advanced_next" class="next paginate_button" href="<?="index.php?page=more&type=roi_history&p=".($newp+1);?>">Next</a>
		<?php  
		} 
		print "</div></div>";
	}
	else { echo "<B style=\"color:#FF0000; font-size:12pt;\">There is no E-pin to show !</B>"; }
}
?>
