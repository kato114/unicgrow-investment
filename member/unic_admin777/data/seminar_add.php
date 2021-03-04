<?php
include('../../security_web_validation.php');

session_start();

if(isset($_SESSION['sem_succ']))
{
	echo $_SESSION['sem_succ'];
	unset($_SESSION['sem_succ']);
}

if(isset($_POST['Submit']))
{ 
	$venue = $_POST['venue'];
	$organizer = $_POST['organizer'];
	$address = $_POST['address'];
	$desc = $_POST['desc'];
	$date = date('Y-m-d');
	$time = date('H:i:s');
	
	$sql = "INSERT INTO seminar (venue , organized_by , date , time , address ,  description) 
	VALUES ('$venue' , '$organizer' , '$date' , '$time' , '$address' , '$desc') ";
	query_execute_sqli($sql);
	
	$_SESSION['sem_succ'] = "<B style=\"color:#008000;\">Seminar Add Successfully !!</B>";
	
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=seminar_add\"";
	echo "</script>";
}	

else
{ ?>
<form action="" method="post">
<table class="table table-bordered">
	<tr>
		<th>Venue</th>
		<td><input type="text" name="venue" class="form-control" /></td>
	</tr>
	<tr>
		<th>Organized By</th>
		<td><input type="text" name="organizer" class="form-control" /></td>
	</tr>
	<tr>
		<th>Address</th>
		<td><textarea name="address" class="form-control"></textarea></td>
	</tr>
	<tr>
		<th>Description</th>
		<td><textarea name="desc" class="form-control"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="Submit" class="btn btn-info" value="Create" />
		</td>
	</tr>
</table>
</form>
<?php } ?>

