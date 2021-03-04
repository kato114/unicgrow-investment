<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");

$newp = $_GET['p'];
$plimit = "25";

$q = query_execute_sqli("select * from users ");
	$totalrows = mysqli_num_rows($q);
	
	print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=650>
		<tr><th>&nbsp;</td></tr>
		<tr><th class=\"message tip\"><strong>User Name</strong></th>
			<th class=\"message tip\"><strong>IP Address</strong></th>
			<th class=\"message tip\"><strong>Wallet Amount</strong></th>
			<th class=\"message tip\"><strong>E-mail</strong></td></tr>";
			
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$q1 = query_execute_sqli("select * from users LIMIT $start,$plimit ");		
	
	while($id_row = mysqli_fetch_array($q1))
	{
		$id_user = $id_row['id_user'];
		$username = $id_row['username'];
		$email = $id_row['email'];			
		$query = query_execute_sqli("select * from wallet where id = '$id_user' ");
		$num = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query))
		{
			$amount = $row['amount'];
		}
		$query = query_execute_sqli("select * from ips_address where user_id = '$id_user' order by id desc limit 1 ");
		$num = mysqli_num_rows($query);
		if($num > 0)
		{
			while($row = mysqli_fetch_array($query))
			{ 
				$ip_add = $row['ip_add'];
			}
		}
		else { $ip_add = ''; }	

		print "<tr><td class=\"input-medium\" style=\"padding-left:40px\"><small>$username</small></td>
					<td class=\"input-medium\" style=\"padding-left:40px\"><small>$ip_add</small></td>
					<td class=\"input-medium\" style=\"padding-left:40px\"><small>$amount &#36;</small></td>
					<td class=\"input-medium\" style=\"padding-left:40px\"><small>$email</small></td></tr>";
	}
	print "<tr><td colspan=4>&nbsp;</td></tr><td colspan=4 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=all_user_amount&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=all_user_amount&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=all_user_amount&p=".($newp+1);?>">&raquo;</a>
		<?php  
		} 
		print"</strong></td></tr></table>";
