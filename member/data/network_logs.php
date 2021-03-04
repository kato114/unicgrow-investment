<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/setting.php");
include("function/all_month.php");	
?>
<!--<h1 align="left">Network History</h1>-->
<?php


$user_id = $_SESSION['mlmproject_user_id'];

$newp = $_GET['p'];
$plimit = "12";

if($newp == '')
{
	$title = 'Display';
	$message = 'Display Network History';
	data_logs($user_id,$title,$message,0);
}


$query1 = query_execute_sqli("select * from logs where user_id = '$user_id' and type = '$log_type[3]' ");
$totalrows = mysqli_num_rows($query1);
if($totalrows != 0)
{
	print "<table id=\"data-table\" class=\"display\" align=center hspace = 0 cellspacing=0 cellpadding=0 border=0 width=750>
			<tr align=center height=\"30\">
				<td width=200 class=\"message tip\"><strong>Date</strong></td>
				<td width=200 class=\"message tip\"><strong>Title</strong></td>
				<td width=200 class=\"message tip\"><strong>Massage</strong></td>
			</tr>";
				
	$pnums = ceil ($totalrows/$plimit);
	
	if ($newp==''){ $newp='1'; }
	
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
	
	
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }
				
	$query = query_execute_sqli("select * from logs where user_id = '$user_id' and type = '$log_type[3]'  LIMIT $start,$plimit ");		
	while($row = mysqli_fetch_array($query))
	{
		$title = $row['title'];
		$message = $row['message'];
		$date = $row['date'];
		print  "<tr class=\"odd\">
					<td width=200 style=\"padding-left:80px\"><small>$date</small></td>
					<td width=200 style=\"padding-left:70px\"><small>$title</small></td>
					<td width=200 style=\"padding-left:45px\"><small>$message</small></td></tr>";
	}
	print "<tr><td colspan=3>&nbsp;</td></tr><td colspan=3 height=30px width=400 class=\"message tip\"><strong>";
	if ($newp>1)
	{ ?>
		<a href="<?php echo "index.php?page=network_logs&p=".($newp-1);?>">&laquo;</a>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<a href="<?php echo "index.php?page=network_logs&p=$i";?>"><?php print_r("$i");?></a>
			<?php 
		}
		else
		{
			 print_r("$i");
		}
	} 
	if ($newp<$pnums) 
	{ ?>
	   <a href="<?php echo "index.php?page=network_logs&p=".($newp+1);?>">&raquo;</a>
	<?php 
	} 
	print"</strong></td></tr></table>";
	
}
else { print "<tr><td colspan=\"3\" width=200 class=\"td_title\">There is no logs !</td></tr></table>"; }


?>

