<?php
session_start();
include("../config.php");
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
require_once("../config.php");
require_once "function/formvalidator.php";
if($_POST['update'])
{
	$validator = new FormValidator();
	$validator->addValidation("password","req","Please fill password");

	if($validator->ValidateForm())
    {
		$id = $_SESSION['mlmproject_user_id'];
		$password = $_POST['password'];
		
		$insert_q = query_execute_sqli("UPDATE users SET password = '$password' WHERE id_user = '$id'");
		
		data_logs($id,$pos,$data_log[2][0],$data_log[2][1],$log_type[2]);
		echo "<B>Password UpdatedS uccessfully </B>";
		
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
		$password = $row['password'];	
	}
?>


<div id="form"> <?php $mg=$_REQUEST[mg]; echo "<h2>".$mg."</h2>"; ?>
<form name="change_pass" action="change_password.php" method="post" id="commentform">
</div>
<div class="form_label"> Password </div><div class="form_data"><input type=text size=25 name=password value=<?php echo $password; ?> /></div>

<div id="submit">
<input type="submit" name="update" value="update" />
</div>

</form>
</div>
</div>
</body>
</html>
