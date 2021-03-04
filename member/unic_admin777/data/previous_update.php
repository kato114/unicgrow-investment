<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "30";

$query = query_execute_sqli("select * from logs where user_id != 0 group by id desc ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{

print "
<table width=\"100%\>
<tr>
    <th class=\"message tip\"><strong></strong></th><th colspan=4 class=\"message tip\"><strong>Previous Updates</strong></th>
  </tr>
  <tr>
<td class=\"input-small\" align=\"center\">Username</td>
<td class=\"input-small\" align=\"center\">IP Address</td>
<td class=\"input-small\" align=\"center\">Operation</td>
<td class=\"input-small\" align=\"center\">Date</td>
</tr>";  

$pnums = ceil ($totalrows/$plimit);
	
	if ($newp==''){ $newp='1'; }
	
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
	
	
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }

	
  $query = query_execute_sqli("select * from logs where user_id != 0 group by id desc LIMIT $start,$plimit ");
  while($rrr  = mysqli_fetch_array($query))
  {
  	$user_id = $rrr['user_id'];
	$username = get_user_name($user_id);
	$ip_add = $rrr['ip_add'];
	$date = $rrr['date'];
	$message = $rrr['message'];
  
  print "
<tr>
<td class=\"input-small\" align=\"center\">$username</td>
<td class=\"input-small\" align=\"center\">$ip_add</td>
<td class=\"input-small\" align=\"center\">$message</td>
<td class=\"input-small\" align=\"center\">$date</td>
</tr>";
}
print "<tr><td colspan=4>&nbsp;</td></tr><td colspan=4 height=30px width=100% class=\"message tip\"><strong>";
	if ($newp>1)
	{ ?>
		<a href="<?php echo "index.php?page=previous_update&p=".($newp-1);?>">&laquo;</a>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<a href="<?php echo "index.php?page=previous_update&p=$i";?>"><?php print_r("$i");?></a>
			<?php 
		}
		else
		{
			 print_r("$i");
		}
	} 
	if ($newp<$pnums) 
	{ ?>
	   <a href="<?php echo "index.php?page=previous_update&p=".($newp+1);?>">&raquo;</a>
	<?php 
	} 
	print"</strong></td></tr></table>";

}