<?php
session_start();
require_once("../config.php");
require_once "function/formvalidator.php";
include("function/setting.php");
?>

<html>
<head>
<script type='text/javascript' src='edit_ validition.js'></script> 
<link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>
<H2>Edit Profile</H2>
<div id="content" class="narrowcolumn">
<div class="comment odd alt thread-odd thread-alt depth-1" style="width:90%">
<?php

if($_POST['update'])
{
		
	$validator = new FormValidator();
	$validator->addValidation("f_name","req","Please fill in First Name");
	$validator->addValidation("l_name","req","Please fill in Last Name");
	$validator->addValidation("dob","req","Please fill Date of Birth");
	$validator->addValidation("gender","req","Please fill in Gender");
	$validator->addValidation("address","req","Please fill in Address");
	$validator->addValidation("city","req","Please fill City");
	$validator->addValidation("provience","req","Please fill in Provience");
	$validator->addValidation("country","req","Please fill in Country");
	$validator->addValidation("email","email","Please Enter a valid Email Id");
	$validator->addValidation("email","req","Please fill in Email");
	$validator->addValidation("phone","req","Please fill in Phone");
	$validator->addValidation("password","req","Please fill password");

	if($validator->ValidateForm())
    {

		$id = $_SESSION['mlmproject_user_id'];
		$f_name = $_POST['f_name'];
		$l_name = $_POST['l_name'];
		$gender = $_POST['gender'];
		$dob = $_POST['dob'];
		$email = $_POST['email'];
		$phone_no = $_POST['phone'];
		$city = $_POST['city'];
		$country = $_POST['country'];
		$password = $_POST['password'];
		$provience = $_POST['provience'];
		$address = $_POST['address'];
		
		
		$insert_q = query_execute_sqli("UPDATE users SET f_name = '$f_name', l_name = '$l_name', gender = '$gender', dob = '$dob', email = '$email', phone_no = '$phone_no', city = '$city', country = '$country', provience = '$provience', address = '$address' , password = '$password' WHERE id_user = '$id'");
		
		data_logs($id,$pos,$data_log[1][0],$data_log[1][1],$log_type[1]);
		echo "<B>Successfully Updated</B>";
		
	}	
	else
		{
			echo "<B>Validation Errors:</B>";
	
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err)
			{
				echo "<p>$inpname : $inp_err</p>\n";
			}        
		}//else		
	
}	
?>


<?php
	$id = $_SESSION['mlmproject_user_id'];
	$query_str = "select * from users where id_user = '$id'";
	$query = query_execute_sqli($query_str);
	while($row = mysqli_fetch_array($query))
	{
 	$f_name = $row['f_name'];
	$l_name = $row['l_name'];
	$gender = $row['gender'];
	$dob = $row['dob'];
	$email = $row['email'];
	$phone = $row['phone_no'];
	$city = $row['city'];
	$mg = $_REQUEST['mg'];
	$country = $row['country'];
	$provience = $row['provience'];
	$address = $row['address'];
	$password = $row['password'];
	
	
	}
?>


<div id="form"> <?php $mg=$_REQUEST[mg]; echo "<h2>".$mg."</h2>"; ?>
<form name="register" action="edit_profile.php" method="post" id="commentform">
</div>
<div class="form_label"> First Name </div><div class="form_data"><input type=text size=25 name=f_name value=<?php echo $f_name; ?> /></div>
<div class="form_label"> Last Name </div><div class="form_data"><input type=text size=25 name=l_name value=<?php echo $l_name; ?> /></div>
<div class="form_label"> Date of Birth </div><div class="form_data"><input type=text size=25 name=dob value=<?php echo $dob; ?> /></div>
<div class="form_label"> Gender </div><div class="form_data"><input type=text size=25 name=gender value=<?php echo $gender; ?> /></div>
<div class="form_label"> Address </div><div class="form_data"><input type=text size=25 name=address value=<?php echo $address; ?> /></div>
<div class="form_label"> City </div><div class="form_data"><input type=text size=25 name=city value=<?php echo $city; ?> /></div>
<div class="form_label"> Provience </div><div class="form_data"><input type=text size=25 name=provience value=<?php echo $provience; ?> /></div>
<div class="form_label"> Country </div><div class="form_data"><input type=text size=25 name=country value=<?php echo $country; ?> /></div>
<div class="form_label"> E-Mail </div><div class="form_data"><input type=text size=25 name=email value=<?php echo $email; ?> /></div>
<div class="form_label"> Phone No. </div><div class="form_data"><input type=text size=25 name=phone value=<?php echo $phone; ?> /></div>
<div class="form_label"> Password </div><div class="form_data"><input type=text size=25 name=password value=<?php echo $password; ?> /></div>

<div id="submit">
<input type="submit" name="update" value="update" />
</div>

</form>
</div>
</div>
</body>
</html>
