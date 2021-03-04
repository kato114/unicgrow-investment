<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");

include("function/setting.php");

$qur_set_search = ' and t2.paid = 0 and paid_by=0';
$query_roi_mode = " ";
$field = " ";

if((isset($_POST['submit'])) or ((isset($newp)) and (isset($_POST['username']))))
{	
	if(!isset($newp))
	{
		$search_username = $_POST['username'];
		$roi_date = $_REQUEST['roi_date'];
		$roi_pay_mode = $_REQUEST['roi_pay_mode'];
		$franc_location = $_REQUEST['franc_location'];
		if($search_username != ''){
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
		
		if($roi_pay_mode != '')
		{
				$_SESSION['qur_set_search'] = " inner join wallet as tt3 on t1.id_user = tt3.id
					and tt3.roi_pay_mode = '$roi_pay_mode' ";
				$qur_set_search = $qur_set_search.$_SESSION['qur_set_search'];
				$field .= ',tt3.roi_pay_mode,tt3.id as wallet_id';
		}	
		if($franc_location != '')
		{
			
			$_SESSION['qur_set_search'] = " inner join franchise as tt4 on tt4.id = t1.location
				and tt4.id = '$franc_location' ";
			$qur_set_search = $qur_set_search.$_SESSION['qur_set_search'];
			$field .= ',tt4.id as franchise_id';
		}
		if($roi_date != '')
		{
			switch($roi_date)
			{
				case 1 : $start_date = date("Y-m-01");
						 $end_date = date("Y-m-15");
						 break;
				case 2 : $start_date = date("Y-m-16");
						 $end_date = date("Y-m-t");
						 break;
			}
			
			 $_SESSION['qur_set_search'] = " where t2.date between '$start_date' and '$end_date'";
			 $qur_set_search = $qur_set_search.$_SESSION['qur_set_search'];
			 
		}	
	}
	
	else
	{	
		$search_id = $_SESSION['session_search_username'];
		if(isset($_SESSION['qur_set_search'])){
			$qur_set_search = 
			" and t1.username = '$search_id' and t2.paid = 0 ".$_SESSION['qur_set_search'];
		}
		else {
			$qur_set_search = " and t1.username = '$search_id' and t2.paid = 0 ";
		}
	}		
}

elseif(isset($_POST['paid']))
{
	$paid_id = $_POST['paid_id'];
	$inc_date = $_POST['inc_date'];
	$amount = $_POST['amount'];
	
		$sql_update = "update daily_income set paid = 1 where user_id = '$paid_id' and paid='0' 
						and date='$inc_date'";
		query_execute_sqli($sql_update);

	query_execute_sqli("insert into account (user_id , dr , date , account) values ('$paid_id' , '$amount' , '$inc_date' , 'ROI PAID')");
}	

else
{	
	unset($_SESSION['session_search_date']);
	//unset($_SESSION['qur_set_search']);
}
if(isset($_REQUEST['trans_to_franch']))
{
	$sql = $_SESSION['sql_transfer_to_franchise'];
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$id_user = $row['id_user'];
			$roi_date = $row['roi_date'];
			$sql_transfer_to_franchise = "update daily_income set paid_by=1 where user_id='$id_user' and date='$roi_date'";
		query_execute_sqli($sql_transfer_to_franchise);
		}		
	}
	unset($_SESSION['sql_transfer_to_franchise']);
 
}			
	$newp = $_GET['p'];
	
	if(isset($_GET['p']) and isset($_SESSION['qur_set_search']) and $_SESSION['qur_set_search'] !='')
	{
		$qur_set_search = $qur_set_search.$_SESSION['qur_set_search'];
	}
	
	$plimit = "15";
	
	$id = $_SESSION['id'];
	$date = date('Y-m-d');
	$_SESSION['qur_set_search1'] = $qur_set_search;
	 	
 
 

 /*print	$sql = "select tt.*,tt2.* from 
				(
					select id_user,ac_no,sum(total_inc) as total_inc,roi_date,paid
					from
					( 
						SELECT t1.id_user,t1.username, t1.ac_no, t2.date as roi_date,t2.paid ,
						(sum(t2.income)-(sum(t2.income)*15)/100) as total_inc 
						FROM users as t1 
						inner join daily_income as t2 
						on t1.id_user = t2.user_id $qur_set_search 
						group by t2.user_id 
						order by t1.id_user,t2.user_id 
					)as tt 
					group by ac_no 
					order by id_user
				) as tt
				inner join users as tt2
				on tt.ac_no = tt2.ac_no
				where tt.total_inc > $minimum_roi_amount
				group by tt2.ac_no
				order by tt2.id_user";
	*/
	$sql = "select * from(
			SELECT  t2.date as roi_date,t2.paid , 		
			 (sum(t2.income)-(sum(t2.income)*15)/100) as total_inc$field,t1.*
			 FROM users as t1 
			 inner join daily_income as t2 
			 on t1.id_user = t2.user_id $qur_set_search  
			 group by t2.user_id 
			 order by t1.id_user,t2.user_id
		 ) as tt
		 where  total_inc > $minimum_roi_amount";
	$_SESSION['sql_transfer_to_franchise'] = $sql;
	
	$query = query_execute_sqli($sql);
	$totalrows = mysqli_num_rows($query);
	if($totalrows > 0)
	{
		//$one_to_ten_days_roi = date("01")." To ".date("10")." Days";
		$eleven_to_twenty_days_roi = date("1")." To ".date("15")." Days";
		$twentyone_to_last_days_roi = date("16")." To ".date("t")." Days";
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$start_plus = (($newp-1) * $plimit+1);
		
		$starting_no = $start + 1;
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
			
			
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
		$sql = "$sql LIMIT $start,$plimit";
		$query = query_execute_sqli($sql);
		$colspan = 9;
		
		
		if(isset($_REQUEST['roi_date']) and $_REQUEST['roi_date'] == 1)
		{
			$fir = "selected='selected'";
		}
		if(isset($_REQUEST['roi_pay_mode']) and $_REQUEST['roi_pay_mode'] == 2)
		{
			$sec = "selected='selected'";
		}
	print "
	
		<table class=\"table\">
		<!--<tr>
		<th colspan=$colspan class=\"message tip\" height=50><strong style=\"font-size:20px\">To Create Excel File <a href=\"index.php?page=generate_daily_income\">Click Here</a></strong></th>
		$trans_to_franch
		</tr>-->
		
		
		<tr >
		<th colspan=2 style=\"border:none;\">
		<form method=\"post\" action=\"index.php?page=show_daily_income\">
			<select name=\"roi_date\">
				<option value=\"\">Select ROI </option>
				<option value=\"1\" $fir>$eleven_to_twenty_days_roi</option>
				<option value=\"2\" $sec>$twentyone_to_last_days_roi</option>
			</select>
		
		</th>
		
		
		<th colspan=7 align=\"right\" style=\"border:none;text-align: right;\">
			
				<input type=\"text\" name=\"username\" class=\"input-medium\">
				<input type=\"submit\" name=\"submit\" value=\"Search\" class=\"normal-button\">		
			</form>
		</th>
		</tr>
		
		<tr class=\"success\">
		<th  height=40><strong>Sr.</strong></th>
		<th><strong>Username</strong></th>
		<th><strong>Name</strong></th>
		<th><strong>Income</strong></th>
		<th><strong>Date</strong></th>
		<th><strong>Email</strong></th>
		<th><strong>Phone</strong></th>
		<th><strong>Bank Details</strong></th>
		<th><strong>Action</strong></th>
		</tr>";
		
		
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
			$inc_date = $row['roi_date'];
			$paid = $row['paid'];
			
			$alert_email = $row['alert_email'];
			
			/*$deduct_amount = $income-($income*$daily_roi_tax)/100;
			if($deduct_amount >= $minimum_roi_amount)
			{*/
			print "<tr>
			<td class=\"input-medium\">$start_plus</td>
			<td class=\"input-medium\"><small>$username</small></td>
			<td class=\"input-medium\"><small>$name</small></th>
			<td class=\"input-medium\"><small>$income</small></td>
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
					<input type=\"hidden\" name=\"amount\" value=\"$income\">
					<input type=\"hidden\" name=\"inc_date\" value=\"$inc_date\">
					<input type=\"submit\" name=\"paid\" value=\"Pay\" class=\"normal-button\">
				</form>
			</td>
			</tr>";
			/*}
			else{}*/
				
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
		unset($_SESSION['qur_set_search']);
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
<style>
.normal-button{
display:inline;
}
</style>
