<?php
include('../../security_web_validation.php');
?>
<script>
function myFunction() {
	document.getElementById("myDiv").innerHTML = '';
    var x = document.getElementById("select_month").value;
    var myDiv = document.getElementById("myDiv");
	var array = x.split(" ");
	
	
	var months = [ "January", "February", "March", "April", "May", "June",   "July", "August", "September", "October", "November", "December" ];
month1 = months.indexOf(array[0]);
//Create array of options to be added
	
	var date = new Date(), y = array[1], m = month1;
	var firstDay = new Date(y, m, 1);
	firstDay = firstDay.getDate();
	var lastDay = new Date(y, m + 1, 0);
	lastDay = lastDay.getDate();
	
	//Create and append select list
	var selectList = document.createElement("select");
	selectList.id = "roi_date";
	selectList.name = "roi_date";
	myDiv.appendChild(selectList);
	 arr = ["1 To 15 Days","16 To "+lastDay+" Days"];
	
	var option = document.createElement("option");
	option.value = " ";
	option.text = "Select ROI";
	selectList.appendChild(option);
	//Create and append the options
	for (var i = 0; i < arr.length; i++) {
		var option = document.createElement("option");
		option.value = i+1;
		option.text = arr[i];
		selectList.appendChild(option);
	}
}
</script>

<?php
session_start();
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");
$qur_set_search = ' and t2.paid = 0 and t2.paid_by=0';
$query_roi_mode = " ";
$field = " ";
if(isset($_REQUEST['paid_to_all']))
{
	
	$sqli = $_SESSION['sql_transfer_to_franchise'];
	$result = query_execute_sqli($sqli);
	$num = mysqli_num_rows($result);
	if($num > 0)
	{	
		$sql_update = '';
		$sql_insert_account = '';
		while($row = mysqli_fetch_array($result))
		{
			$paid_id = $row['id_user'];
			$inc_date = $row['roi_date'];
			$amount = $row['total_inc'];
			if(isset($_REQUEST['s_date']) and isset($_REQUEST['e_date'])){
				$s_date = $_REQUEST['s_date'];
				$e_date = $_REQUEST['e_date'];
				$sql_update = "update daily_income set `paid`=1 where `user_id` = '$paid_id' 
							and `paid`=0 and `paid_by`=0 and  date<='$e_date'; ";
			}
			else
			{
				$sql_update = "update daily_income set `paid`=1 where `user_id` = '$paid_id' 
							and `paid`=0 and `paid_by`=0; ";
			}
			query_execute_sqli($sql_update);
			
			$sql_insert_account = "insert into account (`user_id` , `dr` , `date` , `account`) 
									values ('$paid_id' , '$amount' , '$inc_date' , 'ROI PAID'); ";
			query_execute_sqli($sql_insert_account);
		}
		
		
	}
	print "All Roi Paid Successfully";
}
if((isset($_POST['submit'])) or ((isset($newp)) and (isset($_POST['username']))))
{	
	if(!isset($newp))
	{
		$search_username = $_POST['username'];
		$roi_date = $_REQUEST['roi_date'];
		$roi_month = $_REQUEST['month'];
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
				$_SESSION['roi_pay_mode']	= $roi_pay_mode;
				$qur_set_search .= $_SESSION['qur_set_search'];
				$field .= ',tt3.roi_pay_mode,tt3.id as wallet_id';
		}	
		if($franc_location != '')
		{
			
			$_SESSION['qur_set_search'] = " inner join franchise as tt4 on tt4.id = t1.location
				and tt4.id = '$franc_location' ";
			$qur_set_search .= $_SESSION['qur_set_search'];
			$_SESSION['franc_location'] = $franc_location;
			$field .= ',tt4.id as franchise_id';
		}
		if($roi_date != '' and $roi_month != '' and isset($_REQUEST['month']))
		{	
			$time = split(' ',$roi_month);
			$y = $time[1];
			switch($roi_date)
			{
				case 1 : $start_date = date("$y-m-01",strtotime($time[0]));
						 $end_date = date("$y-m-15",strtotime($time[0]));
						 break;
				case 2 : $start_date = date("$y-m-16",strtotime($time[0]));
						 $end_date = date("$y-m-t",strtotime($time[0]));
						 break;
				default :	$start_date = date("$y-m-01",strtotime($time[0]));
							$end_date = date("$y-m-t",strtotime($time[0]));
						break;
			}
			
			
			
			 $_SESSION['qur_set_search'] = " where  t2.date<='$end_date'";
			 $qur_set_search .= $_SESSION['qur_set_search'];
			 
		}
		
		$_SESSION['qur_set_search'] = $qur_set_search;
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
	$location = $_REQUEST['loc'];
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	$date = $systems_date;
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			$id_user = $row['id_user'];
			$roi_date = $row['roi_date'];
			$sql_transfer_to_franchise = "update daily_income set paid_by=1 , franchise_location = '$location',tranfer_date = '$date' where user_id='$id_user'";
		
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
	
	$sql = "SELECT  t2.id as daily_id,t2.date as roi_date,t2.paid , 		
				 (sum(t2.income)-(sum(t2.income)*15)/100) as total_inc$field,t1.*
				 FROM users as t1 
				 inner join daily_income as t2 
				 on t1.id_user = t2.user_id $qur_set_search  
				 group by t2.user_id 
				 having total_inc > $minimum_roi_amount
				 order by t2.date";
		
	$_SESSION['sql_transfer_to_franchise'] = $sql;
	?>
	
	<?php
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
		if((isset($_REQUEST['franc_location']) and $_REQUEST['franc_location']!='' and 
			isset($_REQUEST['roi_pay_mode']) and $_REQUEST['roi_pay_mode']!='' and $_REQUEST['roi_pay_mode']==1))
		{	
		
			$select_field = 'sum(total_inc) as total';
			$sql = "select $select_field from(
			SELECT  t2.date as roi_date,t2.paid , 		
			 (sum(t2.income)-(sum(t2.income)*15)/100) as total_inc$field,t1.*
			 FROM users as t1 
			 inner join daily_income as t2 
			 on t1.id_user = t2.user_id $qur_set_search  
			 group by t2.user_id 
			 order by t1.id_user,t2.user_id
			 ) as tt
			 where  tt.total_inc > $minimum_roi_amount";
			 $result = query_execute_sqli($sql);
			 $obj = mysqli_fetch_object($result);
			 $total_transfer = $obj->total;
			$loc = $_REQUEST['franc_location'];
			$trans_to_franch = "<th colspan=1 class=\"message tip\" height=50>
								<form method=\"post\" action=\"index.php?page=show_daily_income_new\">
									<input type=\"hidden\" value=\"$loc\" name=\"loc\" />
									<input type=\"submit\" name=\"trans_to_franch\" Value=\"Transfer To Franchise\" class=\"btn btn-info\">
								</form></th>
								<th colspan=1 class=\"message tip\" height=50>
								<strong style=\"font-size:20px\"> $total_transfer RC</strong>
								</th>
								";
			$colspan = 7;
		}
		if(isset($_REQUEST['roi_pay_mode']) and $_REQUEST['roi_pay_mode'] == 0 and $_REQUEST['roi_pay_mode']!='')
		{
			$bank = "selected='selected'";
			
		}
		if(isset($_REQUEST['roi_pay_mode']) and $_REQUEST['roi_pay_mode'] == 1 and $_REQUEST['roi_pay_mode']!='')
		{
			$cash = "selected='selected'";
		}
		if(isset($_REQUEST['roi_date']) and $_REQUEST['roi_date'] == 1 and $_REQUEST['roi_date']!='')
		{
			$fir = "selected='selected'";
		}
		if(isset($_REQUEST['roi_date']) and $_REQUEST['roi_date'] == 2 and $_REQUEST['roi_date']!='')
		{
			$fir = "selected='selected'";
		}
		
	print "
	
		<table width=100%>
		<tr>
		<th colspan=$colspan class=\"message tip\" height=50>
		<form method=post>
			<input type=\"hidden\" name=\"s_date\" value=\"$start_date\">
			<input type=\"hidden\" name=\"e_date\" value=\"$end_date\">
			<input type=\"submit\" value=\"Paid All\" name=\"paid_to_all\">
		</form> &nbsp;&nbsp;
		
		To Create Excel File <a href=\"index.php?page=generate_daily_income\">Click Here</a></strong></th>
		$trans_to_franch
		</tr>
		
		
		<tr>
		<th colspan=4>
		<form method=\"post\" action=\"index.php?page=show_daily_income_new\">
			
		
		";?>
		<select id="select_month" name="month" onchange="myFunction()">
			<option value="">Select Month</option>
			<?php
			  for ($i = 0; $i <= 12; ++$i) {
				$time = strtotime(sprintf('-%d months', $i));
				$value = date('Y-m', $time);
				$label = date('F Y', $time);
				$select = $_REQUEST['month'];
				$selected = '';
				if($label == $select) $selected = "selected='selected'";
				printf('<option value="%s" %s>%s</option>', $label,$selected,$label);
			  }
			?>
	</select>
<?php	print	"<span id=\"myDiv\"></span></th><th colspan=2>
			<select name=\"roi_pay_mode\">
				<option value=\"\">Select Mode</option>
				<option value=\"0\" $bank>Bank</option>
				<option value=\"1\" $cash>Cash</option>
			</select>
		
			<select name=\"franc_location\">
				<option value=\"\">Select Location </option>"
?><?php
			$select_loc = query_execute_sqli(" select id , franchise_location from franchise ");
			$num = mysqli_num_rows($select_loc);
			
			while($row_loc = mysqli_fetch_array($select_loc)){
			
?>				
				<option value="<?=$row_loc[0]; ?>" <?php 
				if(isset($_REQUEST['franc_location']) and $_REQUEST['franc_location'] === $row_loc[0])
				{
				print	$loc = "selected='selected'";
				}
				?>><?=$row_loc[1]; ?></option>
		<?php }
print	"</select>
		</th>
		<th colspan=4 align=\"right\">
			
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
					<input type=\"submit\" name=\"paid\" value=\"Paid\" class=\"buttonc\">
				</form>
			</td>
			</tr>";
			/*}
			else{}*/
				
		$start_plus++;
		}
		$select_field = '';
		if(isset($_REQUEST['roi_pay_mode']) and $_REQUEST['roi_pay_mode'] != ''):
		$select_field .= "&roi_pay_mode=".$_REQUEST['roi_pay_mode'];
		endif;
		
		if(isset($_REQUEST['franc_location'])):
		$select_field .= "&franc_location=".$_REQUEST['franc_location'];
		endif;
	
		if(isset($_REQUEST['roi_date'])):
		$select_field .= "&roi_date=".$_REQUEST['roi_date'];
		endif;
		if(isset($_REQUEST['month'])):
		$select_field .= "&month=".$_REQUEST['month'];
		endif;
		print "<tr><td colspan=9>&nbsp;</td></tr><td colspan=9 height=30px width=400 class=\"message tip\"><strong>";
			if ($newp>1)
			{ ?>
				<a href="<?php echo "index.php?page=show_daily_income_new&p=".($newp-1).$select_field;?>">&laquo;</a>
			<?php 
			}
			for ($i=1; $i<=$pnums; $i++) 
			{ 
				if ($i!=$newp)
				{ ?>
					<a href="<?php echo "index.php?page=show_daily_income_new&p=$i".$select_field;?>"><?php print_r("$i");?></a>
					<?php 
				}
				else
				{
					 print_r("$i");
				}
			} 
			if ($newp<$pnums) 
			{ ?>
			   <a href="<?php echo "index.php?page=show_daily_income_new&p=".($newp+1).$select_field;?>">&raquo;</a>
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
.input-medium{
width:auto; }
</style>