<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/logs_messages.php");

$newp = $_GET['p'];
$plimit = "15";

$query = query_execute_sqli("select * from logs where type = '$log_type[4]' ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{
	print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=800>
			<tr>Financial Logs <p></p><th  height=30px width=200 class=\"message tip\"><strong>Date</strong></th>
				<th width=200 class=\"message tip\"><strong>Title</strong></th>
				<th width=200 class=\"message tip\"><strong>Massage</strong></th></tr>";
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
				
	$query = query_execute_sqli("select * from logs where type = '$log_type[4]' LIMIT $start,$plimit ");			
	while($row = mysqli_fetch_array($query))
	{
		$title = $row['title'];
		$message = $row['message'];
		$date = $row['date'];
		print  "<tr><td width=200 class=\"input-small\" style=\"padding-left:60px\"><small>$date</small></td>
					<td width=200 class=\"input-small\" style=\"padding-left:80px\"><small>$title</small></td>
					<td width=200 class=\"input-small\" style=\"padding-left:30px\"><small>$message</small></td></tr>";
	}
	print "<tr><td colspan=4>&nbsp;</td></tr><td colspan=4 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=finiancial_logs&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=finiancial_logs&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=finiancial_logs&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print"</strong></td></tr></table>";

}
else { print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=800><tr><td colspan=\"3\" width=200 class=\"td_title\">There is no logs !</td></tr></table>"; }


?>
