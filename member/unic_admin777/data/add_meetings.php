<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
ini_set('display_errors','on');
include("../function/functions.php");

if(isset($_SESSION['success']))
{
	print $_SESSION['success'];
	unset($_SESSION['success']);
}

if(isset($_POST['submit']))
{
	$username = $_POST['username'];
	$title = $_POST['title'];
	$purpose =$_POST['purpose'];
	$message = $_POST['message'];
	$venue =$_POST['venue'];
	$org_name = $_POST['organizer_name'];
	$state = $_POST['state'];
	$city = $_POST['city'];
	$contact_no = $_POST['contact_no'];
	$date = date('Y-m-d');
	$time = date('H:i:s');	
	
	$user_id = get_user_id($username);
	
	$query = query_execute_sqli("SELECT * FROM users WHERE username = '$username' ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{	
		$sql = "Insert into my_meetings (user_id , title , purpose , message , venue , organizer_name , 
		state , city , contact_no , mode , date , time) 
		values('$user_id' , '$title' , '$purpose' , '$message' , '$venue' , '$org_name' , '$state' , 
		'$city' , '$contact_no' , '0' , '$date' , '$time')";
		
		$query = query_execute_sqli($sql);
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=add_meetings\"";
		echo "</script>";
		
		$_SESSION['success'] = "<font color=\"green\" size=+0>Meeting Add Successfully !!</font>";
	}
	else 
	{
		print "Please Enter Correct Username";
	}
		
}
else
{ ?>
<form name="my_meeting" action="" method="post">
<table width="500" border="0">
	<tr>
		<td>Username</td>
		<td><input type="text" name="username" class="form-control" required /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>Meeting Name</td>
		<td><input type="text" name="title" class="form-control" required /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>Purpose</td>
		<td><input type="text" name="purpose" class="form-control" required /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>Message</td>
		<td><textarea name="message" class="form-control" style="height:70px;" /></textarea></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>Venue</td>
		<td><input type="text" name="venue" class="form-control" required /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>Organizer Name</td>
		<td><input type="text" name="organizer_name" class="form-control" required /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>State</td>
		<td><input type="text" name="state" class="form-control" required /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>City</td>
		<td><input type="text" name="city" class="form-control" required /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td>Contact No</td>
		<td><input type="text" name="contact_no" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" class="form-control" required />
		</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td colspan="3" align="center">
			<input type="submit" name="submit" value="Submit" class="button3" /> 
		</td>
	</tr>
</table>
</form>

<?php 
}
 ?>