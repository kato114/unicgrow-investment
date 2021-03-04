<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$qur_set_search = ' and t2.paid = 0';

if((isset($_POST['submit'])) or ((isset($newp)) and (isset($_POST['username']))))
{
	if(!isset($newp))
	{
		$search_username = $_POST['username'];
		$search_id = get_new_user_id($search_username);
		if($search_id == 0)
			print "<div style=\"width:80%; text-align:right; color:#FF0000; font-style:normal; font-size:14px; height:50px; padding-right:100px;\">There Are No Income Found</div>";
		else
		{
			$search_id = $_POST['username'];
			$_SESSION['session_search_username'] = $search_id;
			$qur_set_search = " and t1.username = '$search_id' and t2.paid = 0 ";
		}	
	}
	
	else
	{	
		$search_id = $_SESSION['session_search_username'];
		$qur_set_search = " and t1.username = '$search_id' and t2.paid = 0 ";
	}		
}

elseif(isset($_POST['paid']))
{
	$paid_id = $_POST['paid_id'];
	$inc_date = $_POST['inc_date'];
	$amount = $_POST['amount'];
	
	query_execute_sqli("update daily_income set paid = 1 where user_id = '$paid_id' and paid = 0 ");
	
	query_execute_sqli("insert into account (user_id , dr , date , account) values ('$paid_id' , '$amount' , '$inc_date' , 'ROI PAID')");
}	

else
{
	unset($_SESSION['session_search_date']);
}			
	$newp = $_GET['p'];
	$plimit = "15";
	
	$id = $_SESSION['id'];
	$date = date('Y-m-d');
	
	$sql = "SELECT t1.*, t2.*, sum(t2.income) AS total_inc FROM users as t1 inner join daily_income as t2 on t1.id_user = t2.user_id  $qur_set_search group by t2.user_id ";
	
	$query = query_execute_sqli($sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0)
	{
	print "
	
		<table width=100%>
		<tr>
		<th colspan=9 class=\"message tip\" height=40><strong style=\"font-size:20px\">To Create Excel File <a href=\"index.php?page=generate_daily_income\">Click Here</a></strong></th>
		</tr>
		
		
		<tr>
		<th colspan=\"9\" align=\"right\">
			<form method=\"post\" action=\"index.php?page=show_daily_income\">
				<input type=\"text\" name=\"username\" class=\"input-medium\">
				<input type=\"submit\" name=\"submit\" value=\"Search\" class=\"btn btn-info\">		
			</form>
		</th>
		</tr>
		
		<tr>
		<th  height=40 class=\"message tip\"><strong>Sr.</strong></th>
		<th class=\"message tip\"><strong>Username</strong></th>
		<th class=\"message tip\"><strong>Name</strong></th>
		<th class=\"message tip\"><strong>Income</strong></th>
		<th class=\"message tip\"><strong>Date</strong></th>
		<th class=\"message tip\"><strong>Email</strong></th>
		<th class=\"message tip\"><strong>Phone</strong></th>
		<th class=\"message tip\"><strong>Bank Details</strong></th>
		<th class=\"message tip\"><strong>Action</strong></th>
		</tr>";
		
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$start_plus = (($newp-1) * $plimit)+1;
		
		$starting_no = $start + 1;
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
			
			
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
		
		
		$query = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id_user'];
			$username = $row['username'];
			$name = $row['f_name']." ".$row['l_name'];
			$bank_code = $row['bank_code'];
			$email = $row['email'];
			$phone = $row['phone_no'];
			$bank = $row['bank'];
			$ac_no = $row['ac_no'];
			$branch = $row['branch'];
			$beneficiery_name = $row['beneficiery_name'];
			$income = $row['total_inc'];
			$inc_date = $row['date'];
			$paid = $row['paid'];
			
			$alert_email = $row['alert_email'];
			
			$deduct_amount = $income-($income*$daily_roi_tax)/100;
			if($deduct_amount >= $minimum_roi_amount)
			{
			print "<tr>
			<td class=\"input-medium\">$start_plus</td>
			<td class=\"input-medium\"><small>$username</small></td>
			<td class=\"input-medium\"><small>$name</small></th>
			<td class=\"input-medium\"><small>$deduct_amount</small></td>
			<td class=\"input-medium\"><small>$inc_date</small></td>
			<td class=\"input-medium\"><small>$email</small></td>
			<td class=\"input-medium\">$phone</td>
			<td class=\"input-medium\"><small>
			Branch - $branch<br />Bank - $bank<br />Beneficiery Name - $beneficiery_name<br />
			A/c No - $ac_no	<br />IFSC Code - $bank_code
			</small></td>
			<td class=\"input-medium\" align=\"center\">
			<form method=\"post\">
					<input type=\"hidden\" name=\"paid_id\" value=\"$id\">
					<input type=\"hidden\" name=\"amount\" value=\"$deduct_amount\">
					<input type=\"hidden\" name=\"inc_date\" value=\"$inc_date\">
					<input type=\"submit\" name=\"paid\" value=\"Paid\" class=\"buttonc\">
				</form>
			</td>
			</tr>";
			}
			else{}
				
		$start_plus++;
		}
		print "<tr><td colspan=9>&nbsp;</td></tr><td colspan=9 height=30px width=400 class=\"message tip\"><strong>";
			if ($newp>1)
			{ ?>
				<a href="<?php echo "index.php?page=show_daily_income&p=".($newp-1);?>">&laquo;</a>
			<?php 
			}
			for ($i=1; $i<=$pnums; $i++) 
			{ 
				if ($i!=$newp)
				{ ?>
					<a href="<?php echo "index.php?page=show_daily_income&p=$i";?>"><?php print_r("$i");?></a>
					<?php 
				}
				else
				{
					 print_r("$i");
				}
			} 
			if ($newp<$pnums) 
			{ ?>
			   <a href="<?php echo "index.php?page=show_daily_income&p=".($newp+1);?>">&raquo;</a>
			<?php 
			} 
			print"</strong></td></tr></table>";
	}
	else{ 
		print "There is no Income to Show !!"; 
		unset($_SESSION['serch_date']);
	}
	
function get_income_date($date)
{
	$query_find = query_execute_sqli("SELECT * FROM daily_income WHERE date = '$date' and paid = 0");
	$num = mysqli_num_rows($query_find);
	if($num != 0)
	{
		while($rows = mysqli_fetch_array($query_find))
		{
			$date = $rows['date'];
			return $date;
		}
	}
	else { return 0; }	
}	
?>
