<?php
include('../../security_web_validation.php');
?>
<title></title>
<meta http-equiv="refresh" content="5" > 
<?php
echo '<script type="text/javascript">' . "\n";
echo 'window.location="header("Refresh:50")";';
echo '</script>';
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
// total
$all_user = get_all_users();
$total_income = total_income();
$total_amnt_paid = get_total_amnt_paid();
$total_wallet_amnt = get_total_wallet_amnt();
$total_amnt_unpaid = get_total_amnt_unpaid();
$get_total_fund_added = get_total_fund_added();

//latest

$latest_joining = get_all_latest_joining();
$latest_joining_income = total_latest_income();
$total_latest_amnt_paid = get_total_latest_amnt_paid();
$total_latest_wallet_amnt = get_total_latest_wallet_amnt();
$total_latest_amnt_unpaid = get_total_latest_amnt_unpaid();
$get_total_lestest_fund_added = get_total_lestest_fund_added();


print "<table align=\"center\" width=\"90%\" border=\"0\">
<div align=\"50px\" style= color:\"#999999\">Latest Updates<p></p></div>
  <tr>
    <th class=\"message tip\"><strong>Total</strong></th><th class=\"message tip\"><strong>Latest ( ";print date('Y-m-d'); print " )</strong></th>
  </tr>
  <tr><td>
<div class=\"narrowcolumn\">
<div class=\"comment odd alt thread-odd thread-alt depth-1\" style=\"width:200px\">
<table width=\"210%\">
<tr>
    <td class=\"input-small\" align=\"center\">Total Member In Company</td>
    <td class=\"input-small\" align=\"center\"><small>$all_user</small></td>
  </tr>
  <tr>
    <td class=\"input-small\" align=\"center\">Total Income of Company</td>
    <td class=\"input-small\" align=\"center\"><small>$total_income RC</small></td>
  </tr>
  <tr>
    <td class=\"input-small\" align=\"center\">Total Funds Added</td>
    <td class=\"input-small\" align=\"center\"><small>$get_total_fund_added RC</small></td>
  </tr>
  <tr>
    <td class=\"input-small\" align=\"center\">Total Amount Paid</td>
    <td class=\"input-small\" align=\"center\"><small>$total_amnt_paid RC</small></td>
  </tr>
  <tr>
    <td class=\"input-small\" align=\"center\">Total Amount Unpaid</td>
    <td class=\"input-small\" align=\"center\"><small> $total_amnt_unpaid RC</small></td>
  </tr>
  <tr>
    <td class=\"input-small\" align=\"center\">Total Amount in Wallet</td>
    <td class=\"input-small\" align=\"center\"><small>$total_wallet_amnt RC</small></td>
  </tr>
</table></div></div>
</td>
<td><div class=\"narrowcolumn\">
<div class=\"comment odd alt thread-odd thread-alt depth-1\" style=\"width:200px\">
<table width=\"210%\"><tr>
<td class=\"input-small\" align=\"center\">Member Joings</td>
<td class=\"input-small\" align=\"center\"><small>$latest_joining</small></td></tr>
<tr><td class=\"input-small\" align=\"center\">Total Income</td>
<td class=\"input-small\" align=\"center\"><small>$latest_joining_income RC</small></td></tr>
<tr>
    <td class=\"input-small\" align=\"center\">Total Funds Added</td>
    <td class=\"input-small\" align=\"center\"><small> $get_total_lestest_fund_added RC</small></td>
  </tr>
<tr><td class=\"input-small\" align=\"center\">Total Amount Paid</td>
<td class=\"input-small\" align=\"center\"><small> $total_latest_amnt_paid RC</small></td></tr>
<tr><td class=\"input-small\" align=\"center\">Total Amount Unpaid</td>
<td class=\"input-small\" align=\"center\"><small> $total_latest_amnt_unpaid RC</small></td></tr>
<tr><td class=\"input-small\" align=\"center\">Total Amount in Wallet</td>
<td class=\"input-small\" align=\"center\"><small> $total_latest_wallet_amnt RC</small></td></tr>
</table>
</div></div>
</td></tr>
<tr><td colspan=2>
<table width=\"100%\>
<tr>
    <th class=\"message tip\"><strong></strong></th><th colspan=4 class=\"message tip\"><strong>Latest visitors </strong></th>
  </tr>
  <tr>
<td class=\"input-small\" align=\"center\">Username</td>
<td class=\"input-small\" align=\"center\">IP Address</td>
<td class=\"input-small\" align=\"center\">Operation</td>
<td class=\"input-small\" align=\"center\">Date</td>
</tr>";  
  $query = query_execute_sqli("select * from logs where user_id != 0 group by id desc limit 50 ");
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
print "
</table>
</td></tr></table>";

// total 

function get_all_users()
{
	$query = query_execute_sqli("select * from users ");
	$all_user = mysqli_num_rows($query);
	return $all_user;
}

function total_income()
{
	$amount = query_execute_sqli("select * from reg_fees_structure ");
	$total_income = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$total_income = $total_income+$row['update_fees'];
	}
	return $total_income;
}

function get_total_amnt_paid()
{
	$amount = query_execute_sqli("select * from paid_unpaid where 	paid = 1 ");
	$total_amnt_paid = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$total_amnt_paid = $total_amnt_paid+$row['amount'];
	}
	return $total_amnt_paid;
}

function get_total_amnt_unpaid()
{
	$amount = query_execute_sqli("select * from paid_unpaid where 	paid = 0 ");
	$total_amnt_unpaid = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$total_amnt_unpaid = $total_amnt_unpaid+$row['amount'];
	}
	return $total_amnt_unpaid;
}

function get_total_wallet_amnt()
{
$amount = query_execute_sqli("select * from wallet ");
	$total_wallet_amnt = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$total_wallet_amnt = $total_wallet_amnt+$row['amount'];
	}
	return $total_wallet_amnt;
}

function get_total_fund_added()
{
$amount = query_execute_sqli("select * from add_funds where mode = 1 ");
	$total_fund = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$total_fund = $total_fund+$row['amount'];
	}
	return $total_fund;
}

//monthly

function get_all_latest_joining()
{
	$joining = 0;
	$query = query_execute_sqli("select * from users ");
	while($row = mysqli_fetch_array($query))
	{
		$curr_date = date('Y-m-d');
		$db_date = $row['date'];
		if($db_date == $curr_date)
		{
			$joining++;
		}
	}
	return $joining;		
}

function get_total_latest_amnt_paid()
{
	$amount = query_execute_sqli("select * from paid_unpaid where 	paid = 1 ");
	$total_amnt_paid = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$curr_date = date('Y-m-d');
		$db_date = $row['paid_date'];
		if($db_date == $curr_date)
		{
			$total_amnt_paid = $total_amnt_paid+$row['amount'];
		}	
	}
	return $total_amnt_paid;
}

function get_total_latest_amnt_unpaid()
{
	$amount = query_execute_sqli("select * from paid_unpaid where 	paid = 0 ");
	$total_amnt_unpaid = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$curr_date = date('Y-m-d');
		$db_date = $row['request_date'];
		if($db_date == $curr_date)
		{
			$total_amnt_unpaid = $total_amnt_unpaid+$row['amount'];
		}	
	}
	return $total_amnt_unpaid;
}

function get_total_latest_wallet_amnt()
{
$amount = query_execute_sqli("select * from wallet ");
	$total_wallet_amnt = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$curr_date = date('Y-m-d');
		$db_date = $row['date'];
		if($db_date == $curr_date)
		{
			$total_wallet_amnt = $total_wallet_amnt+$row['amount'];
		}	
	}
	return $total_wallet_amnt;
}

function get_total_lestest_fund_added()
{
$amount = query_execute_sqli("select * from add_funds where mode = 1 ");
	$total_fund = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$curr_date = date('Y-m-d');
		$db_date = $row['app_date'];
		if($db_date == $curr_date)
		{
			$total_fund = $total_fund+$row['amount'];
		}	
	}
	return $total_fund;
}

function total_latest_income()
{
	$date = date('Y-m-d');
	$amount = query_execute_sqli("select * from reg_fees_structure where date = '$date' ");
	$total_income = 0;
	while($row = mysqli_fetch_array($amount))
	{
		$total_income = $total_income+$row['update_fees'];
	}
	return $total_income;
}


?>
