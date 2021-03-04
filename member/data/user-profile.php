<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include('condition.php');
include("function/setting.php");
$id = $_SESSION['mlmproject_user_id'];

$title = 'Display';
$message = 'Display Profile';
data_logs($id,$title,$message,0);

$query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$id'");
while($row = mysqli_fetch_array($query))
{
	$name = ucfirst($row['f_name'])." ".ucfirst($row['l_name']);
	$gender = $row['gender'];
	$age = $row['dob'];
	$email = $row['email'];
	$phone_no = $row['phone_no'];
	$district = $row['district'];
	$dob = $row['dob'];
	
	$parent_id = get_user_name($row['parent_id']);
	$real_parent = get_user_name($row['real_parent']);
	$pos = $row['position'];
	if($pos == 0)
		$position = "Left Leg";
	else
		$position = "Right Leg";	
	
	$user_name = $row['username'];
	$step = $row['step'];
	$address = $row['address'];
	$pincode = $row['pincode'];
	$state = $row['state'];
	$city = $row['city'];
	$country = $row['country'];
	
	$ac_no = $row['ac_no'];
	$address = $row['address'];
	
	$beneficiery_name = $row['beneficiery_name'];
	$bank_ac = $row['bank_ac'];
	$bank_name = $row['bank_name'];
	$ifsc_code = $row['ifsc_code'];
	$swift_code = $row['swift_code'];
	
} 
if($gender == 'male'){ $surname = "Mr."; }
else{ $surname = "Miss."; }
?>
	
<table class="table table-bordered table-hover">
	<thead><tr><th colspan="2">My Details</th></tr></thead>
	<tr><th>Referral Link</th>		<td><?=$refferal_link."/wp78w/register.php?ref=".$user_name?></td></tr>
	<tr><th>My Sponsor</th>			<td><?=$real_parent?></td></tr>
	<!--<tr><th>Parent Leg</th>		<td><?=$position?></td></tr>-->
	
	<tr><th>Username</th>			<td><?=$user_name?></td></tr>
<!--	<tr><th>Title</th>				<td><?=$surname?></td></tr>
	<tr><th>User Name</th>			<td><?=$name?></td></tr>
	<tr><th>Father's/Husband</th>	<td><?=$father_name?></td></tr>-->
	<tr><th>Gender</th>				<td><?=$gender?></td></tr>
	<tr><th>Country</th>			<td><?=$country?></td></tr>
	<tr><th>State</th>				<td><?=$state?></td></tr>
	<tr><th>City</th>				<td><?=$city?></td></tr>
	<!--<tr><th>Pin Code</th>			<td><?=$pincode?></td></tr>-->
	
	
	<tr><th>Mobile No.</th>			<td><?=$phone_no?></td></tr>
	<tr><th>E-Mail</th>				<td><?=$email?></td></tr>
	
	<!--<tr><th colspan="2" style="background:#58636E; color:#FFFFFF;">General Details</th></tr>
	
	<tr><th>Account Holder Name</th>		<td><?=$beneficiery_name?></td></tr>
	<tr><th>Account No.</th>				<td><?=$bank_ac?></td></tr>
	<tr><th>Bank Name</th>				<td><?=$bank_name?></td></tr>
	<tr><th>IFSC Code</th>			<td><?=$ifsc_code?></td></tr>
	<tr><th>Swift Code</th>				<td><?=$swift_code?></td></tr>
	<tr><th>Bitcoin Address</th>	<td><?=$ac_no?></td></tr>-->
			
</table>
