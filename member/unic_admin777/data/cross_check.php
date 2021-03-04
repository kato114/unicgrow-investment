<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/logs_messages.php");
include("../function/functions.php");

$newp = $_GET['p'];
$plimit = "15";
if(isset($_POST['submit']) or $newp != '')
{
	if($_POST['submit'] == 'Select')
	{
		$search_mode = $_REQUEST['search_mode'];
		if($search_mode == 'username')
		{
			?> 
					<table width="600" border="0">
					<form name="parent" action="index.php?page=cross_check" method="post">
   						<input type="hidden" name="mode" value="1" />
					  <tr>
						<td colspan="2">&nbsp;</td>
					  </tr>
					  <tr>
						<td class="form_label"><p>Enter Username</p> </td>
						<td><p><input type="text"  style="width:150px;" class="form-control" name="username" /></p></td>
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
						<form name="parent" action="index.php?page=cross_check" method="post">
	   						<input type="hidden" name="mode" value="2" />
						  <tr>
							<td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
							<td class="form_label"><p>Start Date</p> </td>
							<td><p><input type="text" style="width:150px;" name="start_date" class="input-medium flexy_datepicker_input" /></p></td>
						  </tr>
						  <tr>
							<td colspan="2">&nbsp;</td>
						  </tr>
						  <tr>
							<td class="form_label"><p>End Date</p> </td>
							<td><p><input type="text" style="width:150px;" name="end_date" class="input-medium flexy_datepicker_input" /></p></td>
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
		
		if($newp != '')
		{
			$mode = $_SESSION['search_mode_save'];
			$start_date = $_SESSION['search_start_date_save'];
			$end_date = $_SESSION['search_end_date_save'];
		}
		else
		{
			$mode = $_REQUEST['mode'];
			$start_date = $_REQUEST['start_date'];
			$end_date = $_REQUEST['end_date'];
			$_SESSION['search_start_date_save'] = $start_date;
			$_SESSION['search_mode_save'] = $mode;
			$_SESSION['search_end_date_save'] = $end_date;
		}
		if($start_date != '' and $end_date != '')
		{
			if($mode == 1)
			{
				
				
				if($newp != '')
				{
					$username = $_SESSION['search_username_save'];
				}
				else
				{
					$username = $_REQUEST['username'];
					$_SESSION['search_username_save'] = $username;
				}
				$query = query_execute_sqli("select * from users where username = '$username' ");
				$num = mysqli_num_rows($query);
				if($num != 0)
				{
					while($row = mysqli_fetch_array($query))
					{
						$user_id = $row['id_user'];
						
						
						$query = query_execute_sqli("select * from logs where user_id = '$user_id' and date >= '$start_date' and date <= '$end_date' and type = '$log_type[4]' ");
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
										
							$query = query_execute_sqli("select * from logs where user_id = '$user_id' and date >= '$start_date' and date <= '$end_date' and type = '$log_type[4]' LIMIT $start,$plimit ");			
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
									<a href="<?php echo "index.php?page=cross_check&p=".($newp-1);?>">&laquo;</a>
								<?php 
								}
								for ($i=1; $i<=$pnums; $i++) 
								{ 
									if ($i!=$newp)
									{ ?>
										<a href="<?php echo "index.php?page=cross_check&p=$i";?>"><?php print_r("$i");?></a>
										<?php 
									}
									else
									{
										 print_r("$i");
									}
								} 
								if ($newp<$pnums) 
								{ ?>
								   <a href="<?php echo "index.php?page=cross_check&p=".($newp+1);?>">&raquo;</a>
								<?php 
								} 
								print"</strong></td></tr></table>";
						
						}
						else { print "<tr><td colspan=\"3\" width=200 class=\"td_title\">There is no logs !</td></tr></table>"; }

						
						
						
					}	
				}
				else
				{
					print "<font color=\"#FF0000\" size=\"+2\">Please Enter Correct Username !</font>"; 
				}		
			}
			else
			{
				$query = query_execute_sqli("select * from logs where  date >= '$start_date' and date <= '$end_date' and type = '$log_type[4]' ");
				$totalrows = mysqli_num_rows($query);
				if($totalrows != 0)
				{
					print "<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 width=800>
							<tr>Financial Logs <p></p><th  height=30px width=200 class=\"message tip\"><strong>Date</strong></th>
								<th width=200 class=\"message tip\"><strong>Account Id</strong></th>
								<th width=200 class=\"message tip\"><strong>Massage</strong></th></tr>";
					$pnums = ceil ($totalrows/$plimit);
					if ($newp==''){ $newp='1'; }
						
					$start = ($newp-1) * $plimit;
					$starting_no = $start + 1;
					
					if ($totalrows - $start < $plimit) { $end_count = $totalrows;
					} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
						
								
							
					if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
					} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
									
					$query = query_execute_sqli("select * from logs where date >= '$start_date' and date <= '$end_date' and type = '$log_type[4]' LIMIT $start,$plimit ");			
					while($row = mysqli_fetch_array($query))
					{
						$user_id = $row['user_id'];
						$u_anme = get_user_name($user_id);
						$message = $row['message'];
						$date = $row['date'];
						print  "<tr><td width=200 class=\"input-small\" style=\"padding-left:60px\"><small>$date</small></td>
									<td width=200 class=\"input-small\" style=\"padding-left:80px\"><small>$u_anme</small></td>
									<td width=200 class=\"input-small\" style=\"padding-left:30px\"><small>$message</small></td></tr>";	
					}
					print "<tr><td colspan=4>&nbsp;</td></tr><td colspan=4 height=30px width=400 class=\"message tip\"><strong>";
					if ($newp>1)
					{ ?>
						<a href="<?php echo "index.php?page=cross_check&p=".($newp-1);?>">&laquo;</a>
						<?php 
					}
					for ($i=1; $i<=$pnums; $i++) 
					{ 
						if ($i!=$newp)
						{ ?>
							<a href="<?php echo "index.php?page=cross_check&p=$i";?>"><?php print_r("$i");?></a>
							<?php 
						}
						else
						{
							 print_r("$i");
						}
					} 
					if ($newp<$pnums) 
					{ ?>
					   <a href="<?php echo "index.php?page=cross_check&p=".($newp+1);?>">&raquo;</a>
					<?php 
					} 
					print"</strong></td></tr></table>";
				
				}
				else { print "<tr><td colspan=\"3\" width=200 class=\"td_title\">There is no logs !</td></tr></table>"; }
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
		<form name="parent" action="index.php?page=cross_check" method="post">
   
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
?>
