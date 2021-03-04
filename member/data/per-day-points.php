<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
require_once("config.php");
include("function/child_info.php");
//
$id = $_SESSION['mlmproject_user_id'];
?>
<h1 align="left">Per Day Point</h1>
<?php

	$q = query_execute_sqli("select * from reg_fees_structure where user_id = '$id' ");
	$num = mysqli_num_rows($q);
	if($num != 0)
	{
		$j = 0;
		while($row = mysqli_fetch_array($q))
		{
			$detail[$j][0] = $row['reg_fees'];
			$detail[$j][1] = $row['update_fees'];
			$detail[$j][2] = $row['date'];
			
			if($detail[$j][0] != $detail[$j][1])
			{
				$max = get_max($detail[$j]);
				if($max[1] == 0) { 	$detail[$j][3] = $max[0]; $detail[$j][4] = "Left"; }
				if($max[1] == 1) { 	$detail[$j][3] = $max[0]; $detail[$j][4] = "Right"; }
			}
			$pair = $detail[$j][5] = min($detail[$j][0],$detail[$j][1]);
			$detail[$j][6] = capping($pair);
			$detail[$j][7] = $detail[$j][0]+$detail[$j][1]-$detail[$j-1][3];
			$j++;
			
		}
		
		print "
			<table hspace = 0 cellspacing=0 cellpadding=0 border=0 width=600>
			
			<tr>
			<td width=200 class=\"td_title\"><strong>Date</strong></th>
			<td width=200 class=\"td_title\"><strong>Left</strong></th>
			<td width=200 class=\"td_title\"><strong>Right</strong></th>
			<td width=200 class=\"td_title\"><strong>New <br> Joining</strong></th>
			<td width=200 class=\"td_title\"><strong>Carry <br> Forward</strong></th>
			<td width=200 class=\"td_title\"><strong>Pair</strong></th>
			<td width=200 class=\"td_title\"><strong>Capping</strong></th>
			</tr>
			<tr>
			<td align=center colspan=4 width=400 class=\"td_title\"><strong>&nbsp;</strong></th>
			</tr>"; 

	
			$c = count($detail);
			for($i = 0; $i < $c; $i++)
			{
				print "<tr>
					<td width=200 ><small>";echo $detail[$i][2]; print"</small></th>
					<td width=200 ><small>"; echo $detail[$i][0]; print"</small></th>
					<td width=200><small>"; echo $detail[$i][1]; print"</small></th>
					<td width=200 ><small>";echo $detail[$i][7]; print"</small></th>
					<td width=200 ><small>"; echo $detail[$i-1][4]." - "; echo $detail[$i-1][3]; print"</small></th>
					<td width=200 ><small>";echo $detail[$i][5]; print"</small></th>
					<td width=200 ><small>";echo $detail[$i][6]; print"</small></th>
					</tr>";
			}	
			print "</table>";
	}
else { print "You Have No child !"; }

function get_max($c)
{
	if($c[0] < $c[1])
	{
 		$max[0] = $c[1]-$c[0];
		$max[1] = 1;
	}
	else
	{
		$max[0] = $c[0]-$c[1];
		$max[1] = 0;
	}
	return $max;
}		

function capping($pair)
{	
	if($pair == 1 or $pair == 2 or $pair == 3 or $pair ==4 or $pair ==5 or $pair == 0)	
		$res = 0;
	else
		$res = $pair-5;
	return $res;
}

		
?>

