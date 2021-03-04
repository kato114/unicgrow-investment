<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");
include("../function/wallet_message.php");
include("../function/direct_income.php");
include("../function/check_income_condition.php");
include("../function/pair_point_calculation.php");

$newp = $_GET['p'];
$newp_post = $_GET['ps'];
$plimit = "2";

if(isset($_POST['Search']) and ($_SESSION['dccan_admin_login'] == 1) or isset($newp_post))
{
	if(isset($newp_post))
	{
		$search_by = $_SESSION['serarc_approv_user'];
	}
	else
	{
		$search_by = $_SESSION['serarc_approv_user'] = $_REQUEST['search_by'];
	}	
	if($search_by == 0)
		$query = query_execute_sqli("select * from investment_request where mode > 0 and amount > 0 ");
	else
	{
		$query = query_execute_sqli("select * from investment_request where mode > 0 and payment_mode = '$fund_transfer_mode[$search_by]' and amount > 0 ");
	}	
	
	?>
		<div style="width:400px; float:right; font-size:13pt; text-align:right; padding-right:20px;">
		<form name="" action="index.php?page=approve_investment" method="post">fSearch By :- &nbsp;
		<select name="search_by" style="width:125px;">
		<option value="">All Investment</option>
		<option value="1" <?php if($search_by == 1) { ?>  selected="selected" <?php } ?>><?php print $fund_transfer_mode_value[1]; ?></option>
		<option value="2" <?php if($search_by == 2) { ?>  selected="selected" <?php } ?>><?php print $fund_transfer_mode_value[2]; ?></option>
		</select>&nbsp;
		<input type="submit" name="Search" class="btn btn-info" value="Search" style="width:80px;" />
		</form>
		</div>
	
	<?php
	
	
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0) 
	{  
	 print " 
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=900>
			<tr><td colspan=6 >&nbsp;</td>
			<tr>
			<td class=\"message tip\" align=\"center\"><strong>User Name</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Request Amount</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Payment Mode</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Investment Plan</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Date</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Information</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Status</strong></td>
			</tr>";
		
	$pnums = ceil ($totalrows/$plimit);
	if ($newp_post==''){ $newp_post='1'; }
		
	$start = ($newp_post-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	if($search_by == 0)
		$query = query_execute_sqli("select * from investment_request where mode > 0 and amount > 0 LIMIT $start,$plimit ");
	else
	{
		$query = query_execute_sqli("select * from investment_request where mode > 0 and payment_mode = '$fund_transfer_mode[$search_by]' and amount > 0 LIMIT $start,$plimit ");
	}
		
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$u_id = $row['user_id'];
			$plan_setting_id = $row['plan_setting_id'];
			$username = get_user_name($u_id);
			$request_amount = $row['amount'];
			$request_date = $row['date'];
			$payment_mode = $row['payment_mode'];
			$information = $row['information'];
			$mode = $row['mode'];
			if($mode == 1)
				$mode_status = "Approved";
			else
				$mode_status = "Cancelled";	
			$plan_type = $row['plan_type'];
			if($plan_type == 1)
				$inv_plan_type = "Forex Trading";
			elseif($plan_type == 2)
				$inv_plan_type = "Oil Trading";
			else
				$inv_plan_type = "Gold Trading";
			
				
			print "<tr>
			<td class=\"input-medium\" align=\"center\"><a href=\"index.php?page=requested_add_funds_info&inf=$id\">$username</a></td>
			<td class=\"input-medium\" align=\"center\"><small>$request_amount RC</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$payment_mode</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$inv_plan_type</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$request_date</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$information</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$mode_status</small></td></tr>";
		}
		print "<tr><td colspan=6 >&nbsp;</td></tr><td colspan=7 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp_post>1)
		{ ?>
			<a href="<?php echo "index.php?page=approve_investment&ps=".($newp_post-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp_post)
			{ ?>
				<a href="<?php echo "index.php?page=approve_investment&ps=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp_post<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=approve_investment&ps=".($newp_post+1);?>">&raquo;</a>
		<?php 
		} 
		print "</table>";	
	}
	else{ print "<font color=red>There is No approved Investment Request !</font>"; } 

}
else
{
	

	
	$mg = $_REQUEST[mg]; echo $mg;
	$query = query_execute_sqli("select * from investment_request where mode > 0 and amount > 0 ");
	$totalrows = mysqli_num_rows($query);
	if($totalrows != 0) 
	{  ?>
		<div style="width:600px; float:right; font-size:13pt; text-align:right; padding-right:20px;">
		<form name="" action="" method="post">Search By :- &nbsp;
		<select name="search_by" style="width:125px;">
		<option value="">All Investment</option>
		<option value="1"><?php print $fund_transfer_mode_value[1]; ?></option>
		<option value="1"><?php print $fund_transfer_mode_value[2]; ?></option>
		</select>&nbsp;
		<input type="submit" name="Search" class="btn btn-info" value="Search" style="width:80px;" />
		</form>
		</div>
	
	<?php print " 
			<table align=\"center\" hspace = 0 cellspacing=0 cellpadding=0 border=0 height=\"40\" width=900>
			<tr><td colspan=6 >&nbsp;</td>
			<tr>
			<td class=\"message tip\" align=\"center\"><strong>User Name</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Request Amount</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Payment Mode</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Investment Plan</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Date</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Information</strong></td>
			<td class=\"message tip\" align=\"center\"><strong>Status</strong></td>
			</tr>";
		
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
		
	
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
	
		$query = query_execute_sqli("select * from investment_request where mode > 0 and amount > 0 LIMIT $start,$plimit ");
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$u_id = $row['user_id'];
			$plan_setting_id = $row['plan_setting_id'];
			$username = get_user_name($u_id);
			$request_amount = $row['amount'];
			$request_date = $row['date'];
			$payment_mode = $row['payment_mode'];
			$information = $row['information'];
			$mode = $row['mode'];
			if($mode == 1)
				$mode_status = "Approved";
			else
				$mode_status = "Cancelled";	
			$plan_type = $row['plan_type'];
			if($plan_type == 1)
				$inv_plan_type = "Forex Trading";
			elseif($plan_type == 2)
				$inv_plan_type = "Oil Trading";
			else
				$inv_plan_type = "Gold Trading";
			
				
			print "<tr>
			<td class=\"input-medium\" align=\"center\"><a href=\"index.php?page=requested_add_funds_info&inf=$id\">$username</a></td>
			<td class=\"input-medium\" align=\"center\"><small>$request_amount RC</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$payment_mode</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$inv_plan_type</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$request_date</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$information</small></td>
			<td class=\"input-medium\" align=\"center\"><small>$mode_status</small></td></tr>";
		}
		print "<tr><td colspan=6 >&nbsp;</td></tr><td colspan=7 height=30px width=400 class=\"message tip\"><strong>";
		if ($newp>1)
		{ ?>
			<a href="<?php echo "index.php?page=approve_investment&p=".($newp-1);?>">&laquo;</a>
		<?php 
		}
		for ($i=1; $i<=$pnums; $i++) 
		{ 
			if ($i!=$newp)
			{ ?>
				<a href="<?php echo "index.php?page=approve_investment&p=$i";?>"><?php print_r("$i");?></a>
				<?php 
			}
			else
			{
				 print_r("$i");
			}
		} 
		if ($newp<$pnums) 
		{ ?>
		   <a href="<?php echo "index.php?page=approve_investment&p=".($newp+1);?>">&raquo;</a>
		<?php 
		} 
		print "</table>";	
	}
	else{ print "There is no request !"; } 
 }
 
 

function get_date_after_given_days($date,$days) 
{
	$i = 1;
	$given_date = $date;
	do
	{
		$temp_day = date('D', strtotime($given_date . ' +1 days'));
		if($temp_day == 'Sat' or $temp_day == 'Sun')
			$given_date = date('Y-m-d', strtotime($given_date . ' +1 days'));
		else
		{
			$given_date = date('Y-m-d', strtotime($given_date . ' +1 days'));
			$i++;
		}	
	}
	while($i <= $days);
	return $given_date;
}	
 