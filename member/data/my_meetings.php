<?php
include('../security_web_validation.php');
?>
<?php
session_start();


$user_id = $_SESSION['mlmproject_user_id'];

if(isset($_SESSION['success']))
{
	print $_SESSION['success'];
	unset($_SESSION['success']);
}

if(isset($_POST['submit']))
{
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
		
	$sql = "Insert into my_meetings (user_id , title , purpose , message , venue , organizer_name , 
	state , city , contact_no , mode , date , time) 
	values('$user_id' , '$title' , '$purpose' , '$message' , '$venue' , '$org_name' , '$state' , 
	'$city' , '$contact_no' , '0' , '$date' , '$time')";
	
	$query = query_execute_sqli($sql);
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=my_meetings\"";
	echo "</script>";
	
	$_SESSION['success'] = "<font color=\"green\" size=+0>Meeting Add Successfully !!</font>";
		
}
else
{
	/*$query = query_execute_sqli("select * from my_meetings where user_id = '$user_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$title = $row['title'];
		$purpose = $row['purpose'];
		$message = $row['message'];
		$venue = $row['venue'];
		$orgz_name = $row['organizer_name'];
		$state = $row['state'];
		$city = $row['city'];
		$contact_no = $row['contact_no'];
	}*/	

?>
<form name="my_meeting" action="index.php?page=my_meetings" method="post">
	<table class="table table-bordered table-hover">		
		<thead><tr><th colspan="3">My Meetings Details :-</th></tr></thead>	
		<tr>
			<td>Meeting Name</td>
			<td><input type="text" name="title" value="<?=$title;?>" required /></td>
		</tr>
		<tr>
			<td>Purpose</td>
			<td><input type="text" name="purpose" value="<?=$purpose;?>" required /></td>
		</tr>
		<tr>
			<td>Message</td>
			<td><textarea name="message" required /><?=$message;?></textarea></td>
		</tr>
		<tr>
			<td>Venue</td>
			<td><input type="text" name="venue" value="<?=$venue;?>" required /></td>
		</tr>
		<tr>
			<td>Organizer Name</td>
			<td><input type="text" name="organizer_name" value="<?=$orgz_name;?>" required /></td>
		</tr>
		<tr>
			<td>State</td>
			<td><input type="text" name="state" value="<?=$state;?>" required /></td>
		</tr>
		<tr>
			<td>City</td>
			<td><input type="text" name="city" value="<?=$city;?>" required /></td>
		</tr>
		<tr>
			<td>Contact No</td>
			<td><input type="text" name="contact_no" value="<?=$contact_no;?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" required />
			</td>
		</tr>
		<tr>
			<td colspan="3" class="span1 text-center">
				<input type="submit" name="submit" value="Submit" class="btn btn-primary" /> 
			</td>
		</tr>
	</table>
</form>

<?php 
}
 ?>