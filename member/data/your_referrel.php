<?php
include('../security_web_validation.php');
?>
<?php
//session_start();
include('condition.php');

require_once("config.php");
?>
<h1 align="left">Referrel Profile</h1>
<?php
$id = $_SESSION['mlmproject_user_id'];

$query1 = query_execute_sqli("SELECT * FROM users WHERE id_user = '$id' ");
$num1 = mysqli_num_rows($query1);
if($num1 == 0)
{
	echo "There is no information  to show!"; 
}
else 
{
	while($row1 = mysqli_fetch_array($query1))
	{
		$real_parent = $row1['real_parent'];
	}
}	
$query = query_execute_sqli("SELECT * FROM users WHERE id_user = '$real_parent' ");
$num = mysqli_num_rows($query);
if($num == 0)
{
	echo "There is no information  to show!"; 
}
else {
	while($row = mysqli_fetch_array($query))
	{
	$f_name = $row['f_name'];
	$l_name = $row['l_name'];
	$gender = $row['gender'];
	$age = $row['dob'];
	$email = $row['email'];
	$phone_no = $row['phone_no'];
	$city = $row['city'];
	
	$parent_id = $row['parent_id'];
	$real_parent = $row['real_parent'];
	$position = $row['position'];
	$user_name = $row['username'];
	$step = $row['step'];
	$address = $row['address'];
	$provience = $row['provience'];
	$country = $row['country'];
	
	$ac_no = $row['ac_no'];
	$bank = $row['bank'];
	$branch = $row['branch'];
	$bank_code = $row['bank_code'];
	$beneficiery_name = $row['beneficiery_name'];
	
	} ?>
	
	
	
	<div class="entry">

<?php	
		print "

<div style=\"width:600px;\" class=\"content_block\">
<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;padding-left:10px;padding-top:10px\"><div style=\"float:left\">First Name</div><div style=\"margin-left:200px\">$f_name</div></div>
<div class=\"messaging error\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Last Name</div><div style=\"margin-left:200px\">$l_name</div></div>
<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Gender</div><div style=\"margin-left:200px\">$gender</div></div>
<div class=\"messaging error\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Age</div><div style=\"margin-left:200px\">$age</div></div>
<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">E-Mail</div><div style=\"margin-left:200px\">$email</div></div>
<div class=\"messaging error\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Phone Number</div><div style=\"margin-left:200px\">$phone_no</div></div>
<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">City</div><div style=\"margin-left:200px\">$city</div></div>
<div class=\"messaging error\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Provience</div><div style=\"margin-left:200px\">$provience</div></div>
<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Country</div><div style=\"margin-left:200px\">$country</div></div>

<div class=\"messaging error\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Beneficiery Name</div><div style=\"margin-left:200px\">$beneficiery_name</div></div>
<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">A/C No.</div><div style=\"margin-left:200px\">$ac_no</div></div>
<div class=\"messaging error\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Bank</div><div style=\"margin-left:200px\">$bank</div></div>
<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Branch</div><div style=\"margin-left:200px\">$branch</div></div>
<div class=\"messaging error\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">IFSC/NEFT Code</div><div style=\"margin-left:200px\">$bank_code</div></div>

<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Website</div><div style=\"margin-left:200px\">http://www.royaltradergroup.com/$user_name</div></div>
<div class=\"messaging error\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">My Sponsor</div><div style=\"margin-left:200px\">$real_parent</div></div>
<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Spill Sponsor</div><div style=\"margin-left:200px\">$parent_id</div></div>

<div class=\"messaging error\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">Parent Leg</div><div style=\"margin-left:200px\">$position</div></div>

<div class=\"messaging warning\" style=\"height:35px;font-size:18px;padding-left:10px;;padding-left:10px;padding-top:10px\"><div style=\"float:left\">User Name</div><div style=\"margin-left:200px\">$user_name</div>

";
	
}	
?>


</div>
