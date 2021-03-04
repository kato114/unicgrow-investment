<?php
ini_set('display_errors','on');
session_start();
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../config.php");
include("../function/setting.php");
include("../function/functions.php");
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>UNICGROW - Admin Panel</title>
<link rel="shortcut icon" href="images/logo.png" />
<style>
.text-center{
	text-align:center;
}
.text-left{
	text-align:left;
}
</style>

</head>
<body>
<?php
$sql = $_SESSION['SQL_network_member'];

$query = query_execute_sqli($sql);
$totalrows = mysqli_num_rows($query);

if($totalrows != 0)
{ ?>
	<table align="center" width="90%" border="1" bordercolor="#ebebeb" cellpadding="0" cellspacing="0">
		<thead>
		<tr><th colspan="19" class="text-left">Network Members</th></tr>
		<tr>
			<th class="text-center">Sr. No.</th>
			<th class="text-center">User ID</th>
			<th class="text-center">User Name</th>
			<th class="text-center">E-mail</th>
			<th class="text-center">Phone No.</th>
			<th class="text-center">Sponsor ID</th>
			<th class="text-center">Sponsor Name</th>
			<th class="text-center">Joining Date</th>
			<th class="text-center">Activate Date</th>
			<th class="text-center">Status</th>
			<th class="text-center">Investment</th>
			<th class="text-center">Package</th>
			<th class="text-center">Booster</th>
			<th class="text-center">Kyc Status</th>
			<th class="text-center">PAN No.</th>
			<th class="text-center">Bank</th>
			<th class="text-center">A/C</th>
			<th class="text-center">IFSC Code</th>
			<th class="text-center">Branch</th>
		</tr>
		</thead>
		<?php
		$sr_no = 1;
		while($row = mysqli_fetch_array($query))
		{
			$cnt++;
			$id = $row['id_user'];
			$password = $row['password'];
			$username = $row['username'];
			$email = $row['email'];
			$phone_no = $row['phone_no'];
			
			$alert_email = $row['alert_email'];
			$parent_id = $row['real_parent'];
			$name = $row['f_name']." ".$row['l_name'];
			$type = $row['type'];
			$user_pin = $row['user_pin'];
			$date = $row['date'];
			$act_date = $row['act_date'];
			$pt_mode = $row['rg_mode'];//plan type
			$pt_mode = $pt_mode == 1 ? "Noraml" : "Basic"; 
			
			$date1 = $act_date1 = "N/A";
			if($date > 0)
			$date1 = $date;
			
			if($act_date > 0)
			$act_date1 = $act_date;
			
			/*if($type == 'B') { $status = "<span class='label label-success'>Active</span>"; }
			elseif($type == 'C') {  $status = "<span class='label label-warning'>Blocked</span>"; }
			else { $status = "<span class='label label-danger'>Deactive</span>"; }*/
			
			$top_up = get_paid_member($id);
			if($top_up == 0) { $status = "<span class='label label-danger'>Inactive</span>"; }
			else { $status = "<span class='label label-info'>Active</span>"; }
			if($row['type']== 'D'){ $status = "<span class='label label-danger'>Block</span>"; }
		
			$wallet = get_user_wallet($id);
			//$investment = round(get_user_investment($id),4);
			$withdrawal = get_user_withdrawal($id);
			$investment = "";
			$package = '***';
			$my_plan = my_package($id);
			if(!empty($my_plan)){
				$investment = $my_plan[0];
				$package = '('.$my_plan[1].')';
			}
			//$class = '';
			//if($investment == 0){ $class = 'text-danger';}
			
			$benf = $ac_no = $bank = $bank_code = "";
			$sql = "SELECT * FROM kyc WHERE user_id = '$id'";
			$query1 = query_execute_sqli($sql);
			while($rows = mysqli_fetch_array($query1))
			{
				$benf = $rows['name'];
				$ac_no = $rows['bank_ac'];
				$bank = $rows['bank'];
				$bank_code = $rows['ifsc'];
				$pan_no = $rows['pan_no'];
				$branch = $rows['branch'];
			}
			
			$sposor_id = get_user_name($parent_id);
			$sposor_name = get_full_name($parent_id);
			
			$booster = "N/A";
			if(user_booster_active($id) > 0){
				$booster = "<B class='text-success'>Booster</B>";
			}
			
			$kyc_sts = get_user_kyc_status_new($id);
			switch($kyc_sts){
				case 'Cancelled' : 	$kyc_status = "<B class='text-danger'>Cancelled </B>"; break;
				case 'Pending' : 	$kyc_status = "<B class='text-warning'>Pending </B>"; break;
				case 'Approved' : 	$kyc_status = "<B class='text-success'>Approved </B>"; break;
			}
			
			$user_reg_status = get_user_reg_mode_status($id);
			
			
			$reg_date = get_user_active_investment_with_date($id)[1];
			 
			?>
			<tr class="text-center">
				<td><?=$cnt?></td>
				<td><?=$username?></td>
				<td><?=$name?></td>
				<td><?=$email?></td>
				<td><?=$phone_no?></td>
				<td><?=$sposor_id?></td>
				<td><?=$sposor_name?></td>
				<td><?=$date1?></td>
				<td><?=$act_date1?></td>
				<td><?=$status?></td>
				<td><?=$investment?></td>
				<td><?=$package?><br><?=$pt_mode?></td>
				<td><?=$booster?></td>
				<td><?=$kyc_status?></td>
				<td><?=$pan_no?></td>
			
				<td><?=$bank?></td>
				<td><?=$ac_no?></td>
				<td><?=$bank_code?></td>
				<td><?=$branch?></td>
			</tr> <?php		
		} 
		?>
	</table>
	<?PHP
}

function user_booster_active($user_id){
	$querw = query_execute_sqli("SELECT * FROM reg_fees_structure WHERE user_id = '$user_id' and boost_id > 0 ");
	$nums = mysqli_num_rows($querw );
	return $nums;
}
?>
</body>
</html>