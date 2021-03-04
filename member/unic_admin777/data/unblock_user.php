<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("../function/functions.php");
include("../function/setting.php");

$newp = $_GET['p'];
$plimit = $page_limit;

$qur_set_search = ' ';
if((isset($_POST['submit'])) or ((isset($newp)) and (isset($_POST['username']))))
{
	if(!isset($newp))
	{
		$search_username = $_POST['username'];
		$search_id = get_new_user_id($search_username);
		if($search_id == 0){ echo"<B style='color:#FF0000;'>There Are No User Found</B>"; }
		else
		{
			$search_id = $_POST['username'];
			$_SESSION['session_search_username'] = $search_id;
			$qur_set_search = " and username = '$search_id' ";
		}	
	}
	
	else
	{	
		$search_id = $_SESSION['session_search_username'];
		$qur_set_search = " and username = '$search_id' ";
	}		
}
elseif(isset($_POST['unblock']))
{
	$user_id = $_POST['user_id'];
	query_execute_sqli("UPDATE users SET type = 'B' where id_user = '$user_id'");
	$w_q = query_execute_sqli("select * from reg_fees_structure where user_id = '$user_id' and mode=69 ");
	if(mysqli_num_rows($w_q) > 0){
		while($rr = mysqli_fetch_array($w_q))
		{
			$btable_id = $rr['id'];
			$count = $rr['total_days'] - $rr['count'];
			$end_date = get_date_after_given_days($date,$count+1);
			$sql = "update `reg_fees_structure` set `end_date`='$end_date' , `mode`=1 where id='$btable_id'";
			query_execute_sqli($sql);
			
		}
	}
	echo "<B style='color:#008000'>Unblocked Successfully !!</B>"; 
}
else
{
	unset($_SESSION['session_search_date']);
}			
	
	
$sql = "SELECT * FROM users WHERE type != 'B' $qur_set_search";
$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);
if($totalrows > 0)
{ ?>
	<table width=90%>
		<tr>
			<th colspan="9" align="right">
				<form method="post" action="">
					<input type="text" name="username" class="form-control" placeholder="Search By Username" />
					<input type="submit" name="submit" value="Search" class="btn btn-info">		
				</form>
			</th>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<th class="text-center">Sr.No.</th>
			<th class="text-center">Username</th>
			<th class="text-center">Name</th>
			<th class="text-center">Email</th>
			<th class="text-center">Phone</th>
			<th class="text-center">Action</th>
		</tr>
		<?php
	
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$start_plus = (($newp-1) * $plimit)+1;
		
		$starting_no = $start + 1;
		$query = query_execute_sqli("$sql LIMIT $start,$plimit");
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id_user'];
			$username = $row['username'];
			$name = ucwords($row['f_name']." ".$row['l_name']);
			$bank_code = $row['bank_code'];
			$email = $row['email'];
			$phone = $row['phone_no'];
			$bank = $row['bank'];
			$ac_no = $row['ac_no'];
			$branch = $row['branch'];
			$beneficiery_name = $row['beneficiery_name'];
			?>
			<tr>
				<td><?=$start_plus?></td>
				<td><?=$username?></td>
				<td><?=$name?></th>
				<td><?=$email?></td>
				<td><?=$phone?></td>
				<td align="center">
					<form method="post">
						<input type="hidden" name="user_id" value="<?=$id?>">
						<input type="submit" name="unblock" value="Unblock" class="buttonc">
					</form>
				</td>
			</tr> <?php
			$start_plus++;
		}
		pagging_admin_panel($newp,$pnums,6,$val); ?>
	</table> <?PHP
}
else{ 
	echo "<B style='color:#FF0000'>There is No User to Show !!</B>"; 
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
