<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "15";

$query = query_execute_sqli("SELECT * FROM users ");
$totalrows = mysqli_num_rows($query);
print "

	<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=95%>
	
	<tr>
	<th class=\"message tip\"><strong>Name</strong></th>
	<th class=\"message tip\"><strong>User Name</strong></th>
	<th class=\"message tip\"><strong>Phone No.</strong></th>
	<th class=\"message tip\"><strong>E-mail</strong></th>
	<th class=\"message tip\"><strong>Wallet Balance</strong></th>
	<th class=\"message tip\"><strong>Edit</strong></th>
	</tr>";
	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	
	$query = query_execute_sqli("SELECT * FROM users LIMIT $start,$plimit ");
	while($row = mysqli_fetch_array($query))
	{
		$id = $row['id_user'];
		$username = get_user_name($id);
		$name = $row['f_name']." ".$row['l_name'];
		$type = $row['type'];
		$email = $row['email'];
		$phone_no = $row['phone_no'];
		if($type == 'B') { $status = "Active"; }
		elseif($type == 'C') {  $status = "Blocked"; }
		else { $status = "Deactive"; }
		$date = $row['date'];
		$wallet_bal = get_upgrade_membership_fees($id);
		print "<tr>
		<td class=\"input-medium\" style=\"padding-left:20px\">$name</td>
		<td class=\"input-medium\" style=\"padding-left:20px\"><small>$username</small></td>
		<td class=\"input-medium\" style=\"padding-left:20px\">$phone_no</td>
		<td class=\"input-medium\" style=\"padding-left:20px\"><small>$email</small></td>
		<td class=\"input-medium\" style=\"padding-left:20px\"><small>$wallet_bal RC</small></td>
		<td class=\"input-medium\" style=\"padding-left:0px;\">"; ?><div style="width:50px; float:left;">
		<form action="index.php?page=edit_profile" method="post">
		<input type="hidden" name="user_name" value="<?php print $username; ?>" />
		<input type="submit" style="border:none; background:none; font-weight:bold; text-decoration:underline; font-size:11px; color:#7B5B74;" name="submit" value="Profile"  />
		</form>
		</div>
		<div style="width:50px; float:right;">
		<form action="index.php?page=add_funds" method="post">
		<input type="hidden" name="username" value="<?php print $username; ?>" />
		<input type="submit" style="border:none; background:none; font-weight:bold; text-decoration:underline; font-size:11px; color:#7B5B74; padding-left:0px;" name="Wallet" value="Wallet"  />
		</form>
		 </div></th> <?php
		print "</tr>";
			
	}
	print "<tr><td colspan=4>&nbsp;</td></tr><td colspan=7 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=member_report&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=member_report&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=member_report&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print"</strong></td></tr></table>";

?>
