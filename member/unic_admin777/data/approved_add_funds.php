<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "15";

$id = $_SESSION['admin_id'];

	$query = query_execute_sqli("select * from add_funds where ( mode = 1 or mode = 2 ) and amount > 0 ");
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0)
	{
		print " 
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=90%>
			<tr><th class=\"message tip\"><strong>User Name</strong></th>
			<th class=\"message tip\"><strong>Request Amount</strong></th>
			<th class=\"message tip\"><strong>Status</strong></th>
			<th class=\"message tip\"><strong>Pay Mode</strong></th>
			<th class=\"message tip\"><strong>Information</strong></th>
			<th class=\"message tip\"><strong>Request Date</strong></th>
			<th class=\"message tip\"><strong>Cancelled Date</strong></th></tr>";
		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
			
			
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
		
		$query = query_execute_sqli("select * from add_funds where ( mode = 1 or mode = 2 ) and amount > 0 LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$u_id = $row['user_id'];
			$username = get_user_name($u_id);
			$request_amount = $row['amount'];
			$paid_date = $row['app_date'];
			$date = $row['date'];
			$payment_mode = $row['payment_mode'];
			$information = $row['information'];
			$mode = $row['mode'];
			if($mode == 1)
				$pay_mode = "Accepted";
			else	
				$pay_mode = "Cancelled";
				
			print "<tr><td class=\"input-medium\" style=\"padding-left:40px\">$username</td>
			<td class=\"input-medium\" style=\"padding-left:40px\"><small>$request_amount RC</small></td>
			<td class=\"input-medium\" style=\"padding-left:40px\"><small>$pay_mode</small></td>
			<td class=\"input-medium\" style=\"padding-left:40px\"><small>$payment_mode</small></td>
			<td class=\"input-medium\" style=\"padding-left:40px\"><small>$information</small></td>
			<td class=\"input-medium\" style=\"padding-left:40px\"><small>$date</small></td>
			<td class=\"input-medium\" style=\"padding-left:40px\"><small>$paid_date</small></td>
			</tr>";
			
		}
		print "<tr><td colspan=7>&nbsp;</td></tr><td colspan=7 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=approved_add_funds&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=approved_add_funds&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=approved_add_funds&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print "</table>";	
	}
	else{ print "There is no fund for approved !"; }
?>

