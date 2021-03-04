<?php
include('../../security_web_validation.php');
?>
<?php
include("../function/functions.php");
$newp = $_GET['p'];
$plimit = "25";
?>
<form method="post" action="index.php?page=transfer_epin_history">
	<table cellpadding="0" cellspacing="1" width="90%">
	  <tr>
	  	<th colspan="7" align="right">
			Date - <input type="text" name="date" class="input-medium flexy_datepicker_input" />
		</th>
		<th align="center">
			<input type="submit" value="Submit" name="Search" class="button3" style="cursor:pointer">		
		</th>
	  </tr>	
	  
	  <tr height="20px">
	  	<th colspan="8"></th>
	  </tr>
	  <tr height="40px" class="text-center" style="color:#000000;">
			<th>Sr</th>
			<th>E-pin</th>
			<th>Amount</th>
			<th>Date</th>
			<th>Generate By</th>
			<th>User Id</th>
			<th>Transfer By</th>
			<th>Used By</th>
			<th>Used Date</th>
		</tr>
<?php	
	$search_date = $systems_date;
	if(isset($_POST['Search']))
	{
		$search_date = $_SESSION['session_search_date'] = $_POST['date'];
		$sql = "select * from e_pin as t1 inner join epin_history as t2 on t1.id = t2.epin_id and t1.date = '$search_date' and t2.user_id != t2.transfer_to group by t1.epin order by t1.date desc ,t1.id desc";
	}
	else
	{
		if($_SESSION['session_search_date'] != '' and $newp > 0)
		{
			$search_date = $_SESSION['session_search_date'];
			$sql = "select * from e_pin as t1 inner join epin_history as t2 on t1.id = t2.epin_id and t1.date = '$search_date' and t2.user_id != t2.transfer_to  group by t1.epin order by t1.date desc ,t1.id desc";
		}
		else
		{
			unset($_SESSION['session_search_date']);
			$sql = '';
			//$sql = "select * from e_pin as t1 inner join epin_history as t2 on t1.id = t2.epin_id group by t1.epin order by t1.date desc ,t1.id desc";
		}
	}	
		
		$query = query_execute_sqli($sql);
		
		$num = mysqli_num_rows($query);
		if($num == 0)
		{
			//$sql = "select * from e_pin as t1 inner join epin_history as t2 on t1.id = t2.epin_id group by t1.epin order by t1.date desc ,t1.id desc ";
			$error = "<font color=\"dark\">No History For $search_date</font>";
		}
		else
		{
			$sql = $sql; 
		}
		$query_pin = query_execute_sqli($sql);
		$totalrows = mysqli_num_rows($query_pin); 
		//$sr = 1;
		print	$error;
		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
			
			
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; } 
		
		$sr = $plimit*($newp-1)+1;
		$sql = "$sql  LIMIT $start,$plimit";
		$query_in = query_execute_sqli($sql);
		
		while($row = mysqli_fetch_array($query_in))
		{
			$generate_id = '';
			$owner = '';
			$transfer_id = '';
			$used_id = '';
			
			$id = $row['id'];
			$generate_id = $row['generate_id'];
			
			if($generate_id == 0){
				$generate_id = 'Admin';	
			}
			else{
				$generate_id = get_user_name($generate_id);
			}
			
			$owner = $row['transfer_to'];
			$transfer_id = $row['user_id'];
			if($transfer_id == $owner)
			{
				$transfer_id = 'No Transfer';
				$owner = get_user_name($owner);
			}
			else
			{
				$transfer_id = get_user_name($transfer_id);
				$owner = get_user_name($owner);
			}
			
			$used_id = $row['used_id'];
			$used_date = $row['used_date'];
			if($used_id == 0 or $used_id == '')
			{
				$used_id = "<font color=red>Unused</font>";
				$used_date = "<font color=red>No Date</font>";
			}
			else{ $used_id = get_user_name($used_id);}
			$date = $row['date'];
			$epin = $row['epin'];
			$amount = $row['amount'];
			echo"<tr class=\"success\" height='30px' style=\"color:#000\">
					<th>$sr</th>
					<th><a href=\"index.php?page=current_epin_historys&epin=$id\"  title=\"View\">$epin</a></th>
					<th>$amount</th>
					<th>$date</th>
					<th>$generate_id</th>
					<th>$owner</th>
					<th>$transfer_id</th>
					<th>$used_id</th>
					<th>$used_date</th>
				</tr>";
			$sr++;
		}
		
		print "<tr><td colspan=9>&nbsp;</td></tr>
				<tr><td colspan=9 height=30px width=400 class=\"message tip\">&nbsp;&nbsp;<strong>";
		if ($newp>1)
		{ ?> <a href="<?php echo "index.php?page=current_epin_history&p=".($newp-1);?>">&laquo;</a> <?php }
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?> 
				<a href="<?php echo "index.php?page=current_epin_history&p=$i";?>"><?php print_r("$i");?></a> 
				<?php 
			}
			else
			{ print_r("$i"); }
		} 
		if ($newp<$pnums) 
		{ ?> <a href="<?php echo "index.php?page=current_epin_history&p=".($newp+1);?>">&raquo;</a> <?php } 
		print"</strong></td></tr>";
?>				
	</table>
</form>	
<?php
?>