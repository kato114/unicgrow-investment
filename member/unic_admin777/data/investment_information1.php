<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "15";


?>
<h1>User Investment Information</h1>
<?php

if((isset($_POST['submit'])) or $newp != '')
{
	if($_POST['submit'] == 'Select')
	{
		
		$search_mode = $_REQUEST['search_mode'];
		if($search_mode == 'username')
		{
			?> 
					<table width="600" border="0">
					<form name="parent" action="index.php?page=investment_information" method="post">
   						<input type="hidden" name="mode" value="1" />
					  <tr>
						<td colspan="2">&nbsp;</td>
					  </tr>
					  <tr>
						<td class="form_label"><p>Enter Username</p> </td>
						<td><p><input type="text" class="form-control" style="width:150px;" name="username" /></p></td>
					  </tr>
					  <tr>
						<td colspan="2">&nbsp;</td>
					  </tr>
					  <tr>
						<td class="form_label"><p>Start Date</p> </td>
						<td><p><input type="text" name="start_date" style="width:150px;" class="input-medium flexy_datepicker_input" /></p></td>
					  </tr>
					  <tr>
						<td colspan="2">&nbsp;</td>
					  </tr>
					  <tr>
						<td class="form_label"><p>End Date</p> </td>
						<td><p><input type="text" name="end_date" style="width:150px;" class="input-medium flexy_datepicker_input" /></p></td>
					  </tr>
					  <tr>
						<td colspan="2">&nbsp;</td>
					  </tr>
					  <tr>
						<td align="right" colspan="2"><p align="center"><input type="submit" name="submit" value="Search" class="btn btn-info"  /></p></td>
					  </tr>
					  </form>
					</table>
					<?php
		}
		else
		{
			?> 
				<table width="600" border="0">
						<form name="parent" action="index.php?page=investment_information" method="post">
	   						<input type="hidden" name="mode" value="2" />
						  <tr>
							<td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
							<td class="form_label"><p>Start Date</p> </td>
							<td><p><input type="text" name="start_date" style="width:150px;" class="input-medium flexy_datepicker_input" /></p></td>
						  </tr>
						  <tr>
							<td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
							<td class="form_label"><p>End Date</p> </td>
							<td><p><input type="text" name="end_date" style="width:150px;" class="input-medium flexy_datepicker_input" /></p></td>
						  </tr>
						  <tr>
							<td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
							<td align="right" colspan="2"><p align="center"><input type="submit" name="submit" value="Search" class="btn btn-info"  /></p></td>
						  </tr>
						  </form>
						</table>
				<?php
		}
	}	
	elseif(($_POST['submit'] == 'Search') or $newp != '')
	{
		if($newp == '')
		{
			$mode = $_REQUEST['mode'];
			$_SESSION['invst_mode'] = $mode;
			$start_date = $_REQUEST['start_date'];
			$_SESSION['invst_start_date'] = $start_date;
			$end_date = $_REQUEST['end_date'];
			$_SESSION['invst_eend_date'] = $end_date;
		}
		else
		{
			$mode = $_SESSION['invst_mode'];
			$start_date = $_SESSION['invst_start_date'];
			$end_date = $_SESSION['invst_eend_date'];
		}
		if($start_date != '' and $end_date != '')
		{
			if($mode == 1)
			{
				if($newp == '')
				{
					$username = $_REQUEST['username'];
					$_SESSION['invst_username'] = $username;
				}
				else
				{
					$username = $_SESSION['invst_username'];
				}
				
				$query = query_execute_sqli("select * from users where username = '$username' ");
				$num = mysqli_num_rows($query);
				if($num != 0)
				{
					while($row = mysqli_fetch_array($query))
					{
						$user_id = $row['id_user'];
						$user_query = query_execute_sqli("select * from reg_fees_structure where user_id = '$user_id' and date >= '$start_date' and date <= '$end_date' ");
						$totalrows = mysqli_num_rows($user_query);
						
						$pnums = ceil ($totalrows/$plimit);
						if ($newp==''){ $newp='1'; }
							
						$start = ($newp-1) * $plimit;
						$starting_no = $start + 1;
						
						if ($totalrows - $start < $plimit) { $end_count = $totalrows;
						} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
							
							
						
						if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
						} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
						
						$user_query_lmt = query_execute_sqli("select * from reg_fees_structure where user_id = '$user_id' and date >= '$start_date' and date <= '$end_date' LIMIT $start,$plimit ");
					}
				}
				else
				{
					print "<font color=\"#FF0000\" size=\"+2\">Please Enter Correct Username !</font>"; 
				}	
			}
			else
			{
				$user_query = query_execute_sqli("select * from reg_fees_structure where date >= '$start_date' and date <= '$end_date' ");
				$totalrows = mysqli_num_rows($user_query);
				$pnums = ceil ($totalrows/$plimit);
				if ($newp==''){ $newp='1'; }
					
				$start = ($newp-1) * $plimit;
				$starting_no = $start + 1;
				
				if ($totalrows - $start < $plimit) { $end_count = $totalrows;
				} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
					
					
				
				if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
				} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
				
				$user_query_lmt = query_execute_sqli("select * from reg_fees_structure where date >= '$start_date' and date <= '$end_date' LIMIT $start,$plimit ");
			}
			$num_q = mysqli_num_rows($user_query);
			if($num_q > 0)
			{
				$total_investment = 0;
				while($r1 = mysqli_fetch_array($user_query))
				{
					$total_investment = $total_investment+$r1['update_fees'];
				}	
				?>
				<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=600>
				<tr>
						<td colspan="2" class="text-center" align="center"><strong>Total Investment</strong></td>
						<td colspan="3" class="text-center" align="center"><strong><?php print $total_investment; ?></strong></td>
				</tr>
				<tr>
						<td>&nbsp;</td>
				</tr>				
				<tr>
						<td class="text-center" align="center"><strong>Username</strong></td>
						<td class="text-center" align="center"><strong>Date</strong></td>
						 <td class="text-center" align="center"><strong>Investment</strong></td>
						 <td class="text-center" align="center"><strong>Profit (%)</strong></td>
						 <td class="text-center" align="center"><strong>Total Days</strong></td>
					  </tr>
				<?php
				while($r = mysqli_fetch_array($user_query_lmt))
				{
							$date = $r['date'];
							$user_ids = $r['user_id'];
							$usernames = get_user_name($user_ids);
							$profit = $r['profit'];
							$total_days = $r['total_days'];
							$reg_fees = $r['reg_fees'];
							$update_fees = $r['update_fees'];
							if($update_fees == 0)
								$amount = $reg_fees;
							else
								$amount = $update_fees;
							print "<tr>
								<td  align=\"center\" class=\"input-small\">$usernames</td>
								<td  align=\"center\" class=\"input-small\">$date</td>
								<td align=\"center\" class=\"input-small\">$amount</td>
								<td align=\"center\" class=\"input-small\">$profit</td>
								<td align=\"center\" class=\"input-small\">$total_days</td>
							  </tr>";
				}
				print "<tr><td colspan=5>&nbsp;</td></tr><td colspan=5 height=30px width=400 class=\"message tip\"><strong>";
				if ($newp>1)
				{ ?>
					<a href="<?php echo "index.php?page=investment_information&p=".($newp-1);?>">&laquo;</a>
				<?php 
				}
				for ($i=1; $i<=$pnums; $i++) 
				{ 
					if ($i!=$newp)
					{ ?>
						<a href="<?php echo "index.php?page=investment_information&p=$i";?>"><?php print_r("$i");?></a>
						<?php 
					}
					else
					{
						 print_r("$i");
					}
				} 
				if ($newp<$pnums) 
				{ ?>
				   <a href="<?php echo "index.php?page=investment_information&p=".($newp+1);?>">&raquo;</a>
				<?php 
				} 
				print"</strong></td></tr></table>";
			}
			else
			{
				print "<font color=\"#FF0000\" size=\"+2\">Username ".$username." have no Investment !</font>";
			}
		}
		else { print "<font color=\"#FF0000\" size=\"+2\">Please Enter Start Date or End Date !</font>"; }		
	}
	else { print "There Is Some Conflicts !"; }
}			

else
{
	?> 
		<table width="600" border="0">
		<form name="parent" action="index.php?page=investment_information" method="post">
   
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="form_label"><p>Search Mode</p> </td>
    <td><p><input type="radio" name="search_mode" value="username" /> By Username <input type="radio" name="search_mode" value="date" /> By Date</p></p></td>
  </tr>
  
  <tr>
    <td align="right" colspan="2"><p align="center"><input type="submit" name="submit" value="Select" class="btn btn-info"  /></p></td>
  </tr>
  </form>
</table>
	<?php
}	