<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "15";

?>
<div style=" float:right; text-align:right; height:70px;">
<form action="index.php?page=add_meetings" method="post">
<input type="submit" name="add_meeting" value="Add New Meeting" class="button3" />
</form>
</div>


<?php

/*if(isset($_POST['Search']))
{
	$search_date = $_SESSION['session_search_date'] = $_POST['search_date'];
	$query_date = " date = '$search_date' and ";
}
else
{
	if($_SESSION['session_search_date'] != '' and $newp > 0)
	{
		$search_date = $_SESSION['session_search_date'];
		$query_date = " date = '$search_date' and ";
	}
	else
		unset($_SESSION['session_search_date']);
}*/
$id = $_POST['id'];

if(isset($_POST['approved']))
{
	query_execute_sqli("update my_meetings set mode = 1 where id = '$id' ");
	print "<font color=\"green\" size=+2>Meeting Approved Successfully !!</font>";
}
elseif(isset($_POST['cancel']))
{
	query_execute_sqli("update my_meetings set mode = 2 where id = '$id' ");
	print "<font color=\"red\" size=+2>Meeting Cancel Successfully !!</font>";
}
print "<p>&nbsp;</p>";

$query = query_execute_sqli("select * from my_meetings where mode = '0' ");
$totalrows = mysqli_num_rows($query);
if($totalrows != 0)
{
	print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=90%>
			<tr>
				<th height=40 class=\"message tip\"><B>Sr No.</B></th>
				<th class=\"message tip\"><B>User Id</B></th>
				<th class=\"message tip\"><B>Meeting Name</B></th>
				<th class=\"message tip\"><B>Organizer Name</B></th>
				<th class=\"message tip\"><B>Message</B></th>
				<th class=\"message tip\"><B>Venue</B></th>
				<th class=\"message tip\"><B>State</B></th>
				<th class=\"message tip\"><B>Date</B></th>
				<th class=\"message tip\"><B>Action</B></th>
			</tr>";
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	$sr = 1;			
	$query = query_execute_sqli("select * from my_meetings where mode = '0' LIMIT $start,$plimit ");			
	while($row = mysqli_fetch_array($query))
	{
		$id = $row['id'];
		$user_id = $row['user_id'];
		$title = $row['title'];
		$purpose = $row['purpose'];
		$message = $row['message'];
		$venue = $row['venue'];
		$orgz_name = $row['organizer_name'];
		$state = $row['state'];
		$city = $row['city'];
		$contact_no = $row['contact_no'];
		$date = $row['date'];
		$mode = $row['mode'];
		
		$user_name = get_user_name($user_id);
		//$name = get_full_name($user_id);
				
		print  "
			<tr>
				<td class=\"input-small\" style=\"padding-left:20px;\"><small>$sr</small></td>
				<td class=\"input-small\" style=\"padding-left:20px;\"><small>$user_name</small></td>
				<td class=\"input-small\" style=\"padding-left:20px;\"><small>$title</small></td>
				<td class=\"input-small\" style=\"padding-left:20px;\"><small>$orgz_name</small></td>
				<td class=\"input-small\" style=\"padding-left:20px;\"><small>$message</small></td>
				<td class=\"input-small\" style=\"padding-left:20px;\"><small>$venue</small></td>
				<td class=\"input-small\" style=\"padding-left:20px;\"><small>$state</small></td>
				<td class=\"input-small\" style=\"padding-left:20px;\"><small>$date</small></td>"; 
			?>
				<td class="input-small" style="padding-left:20px;">
			
					<small>
					<form action="" method="post">
						<input type="hidden" name="id" value="<?=$id;?>" />
						<input type="submit" name="approved" value="Approved" class="btn btn-info" />
						<input type="submit" name="cancel" value="Cancel" class="btn btn-info" />
					</form>
					</small>
				</td>
			</tr>
	<?php	$sr++;	
	}
	print "
		<tr><td colspan=9>&nbsp;</td></tr>
		<tr><td colspan=9 height=30px width=400 class=\"message tip\">&nbsp;&nbsp;<strong>";
		if ($newp>1)
		{ ?> <a href="<?php echo "index.php?page=meeting&p=".($newp-1);?>">&laquo;</a> <?php  }
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?><a href="<?= "index.php?page=meeting&p=$i";?>"><?php print_r("$i");?></a> <?php }
			else
			{ print_r("$i"); }
		} 
		if ($newp<$pnums) 
		{ ?> <a href="<?php echo "index.php?page=meeting&p=".($newp+1);?>">&raquo;</a> <?php  } 
		print"</strong></td></tr></table>";

}
else { echo "<B style=\"color:#FF0000; font-size:12pt;\">There are no information to show !!</B>"; }


?>
