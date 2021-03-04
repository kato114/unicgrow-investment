<?php
include('../../security_web_validation.php');

//session_start();
include('condition.php');
include("../function/functions.php");

if(isset($_POST['submit']))
{
	$u_name = $_REQUEST['user_name'];
	$sql = "SELECT * FROM users WHERE username = '$u_name' ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num == 0){ echo "<B class='text-danger'>Please Enter right Username! </B>"; }
	else
	{
		while($row = mysqli_fetch_array($query))
		{
			$id_user = $row['id_user'];
			$f_name = $row['f_name'];
			$l_name = $row['l_name'];
			$gender = $row['gender'];
			$dob = $row['dob'];
			$email = $row['email'];
			$phone_no = $row['phone_no'];
			$city = $row['city'];
			
			$parent_id = $row['parent_id'];
			$real_parent = $row['real_parent'];
			$real_parent = get_user_name($row['real_parent']);
			$position = $row['position'];
			$position = "Right";
			if($position == 0) $position = "Left";
			$user_name = $row['username'];
			$step = $row['step'];
			$address = $row['address'];
			$provience = $row['provience'];
			$country = $row['country'];
			$username = $row['username'];
			$password = $row['password'];
			$fb_id = $row['fb_id'];
			$whatsapp = $row['whatsapp'];
			$skype_id = $row['skype_id'];
			$nominee = $row['nominee'];
			$relation = $row['relation'];
			
			$alert_email = $row['alert_email'];
			$liberty_email = $row['liberty_email']; 
	
			
			$ac_no = $row['ac_no'];
			$bit_ac_no = $row['bit_ac_no'];
			
			$sql = "SELECT * FROM kyc WHERE user_id = '$id_user' ";
			$query = query_execute_sqli($sql);
			while($rows = mysqli_fetch_array($query)){
				$pan_no = $rows['pan_no'];
				$bank_ac = $rows['bank_ac'];
				$bank = $rows['bank'];
				$branch = $rows['branch'];
				$ifsc = $rows['ifsc'];
				$name = $rows['name'];
				$chq_pas_b = $rows['chq_passbook'];
				$id_frnt = $rows['id_proof_front'];
				$id_back = $rows['id_proof_back'];
				$photo = $rows['photo'];
				$sign = $rows['signature'];
				$pan_card = $rows['pan_card'];
			}
		} ?>
		<div class="col-md-12">
			<a class="btn btn-danger" href="index.php?page=user_email"><i class="fa fa-reply"></i> Back</a>
		</div>
		<br /><br /><br />
		<table class="table table-bordered">
			<thead><tr><th colspan="2">Personal Details </th></tr></thead>
			<tr><th>My Sponsor</th>		<td><?=$real_parent?></td></tr>
			<tr><th>Username</th>		<td><?=$username?></td></tr>
			<tr><th>Password</th>		<td><?=$password?></td></tr>
			<tr><th>First Name</th>		<td><?=$f_name?></td></tr>
			<tr><th>Last Name</th>		<td><?=$l_name?></td></tr>
			<tr><th>Refferal Link</th>	<td><?=$refferal_link."/".$user_name?></td></tr>
			
			<!--<thead><tr><th colspan="2">Power Leg</th></tr></thead>
			<tr><th>Position</th>		<td><?=$position?></td></tr>-->
			
			<thead><tr><th colspan="2">Contact Details </th></tr></thead>
			<tr><th>E-Mail</th>			<td><?=$email?></td></tr>
			<tr><th>Phone Number</th>	<td><?=$phone_no?></td></tr>
			<!--<tr><th>City</th>		<td><?=$city?></td></tr>
			<tr><th>Country</th>		<td><?=$country?></td></tr>-->
			<!--<thead><tr><th colspan="2">Social Media Details</th></tr></thead>
				<tr><th>Facebook ID</th><td><?=$fb_id?></td></tr>
				<tr><th>whatsapp</th>	<td><?=$whatsapp?></td></tr>
				<tr><th>Skype ID</th>		<td><?=$skype_id?></td></tr>
			<thead><tr><th colspan="2">Nominee Details</th></tr></thead>
				<tr><th>Nominee</th><td><?=$nominee?></td></tr>
				<tr><th>Relation</th>	<td><?=$relation?></td></tr>-->
		</table> <?php	
	}	
}	
else
{ ?> 

<form action="" method="post">
<table class="table table-bordered">
	<tr><thead><th colspan="3">Enter Information</th></thead></tr>
	<tr>
		<th>Enter Member UserName</th>
		<td><input type="text" name="user_name" class="form-control" /></td>
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>
<?php  }  ?>
