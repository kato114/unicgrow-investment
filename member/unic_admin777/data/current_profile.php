<?php
include('../../security_web_validation.php');
?>
<div class="col-sm-12 text-right">
	<button type="button" id="close" onclick="window.close()" class='btn btn-danger btn-sm'>
		<i class="fa fa-reply"></i> Close Window
	</button>
</div>
<div class="col-sm-12">&nbsp;</div>
<?php


$user_id = $_POST['user_id'];

$sql = "SELECT * FROM users WHERE id_user = '$user_id'";
$query = query_execute_sqli("$sql");
while($row = mysqli_fetch_array($query))
{
	$id = $row['id_user'];
	$username = $row['username'];
	$name = ucwords($row['f_name']." ".$row['l_name']);
	$date = $row['date'];
	$phone_no = $row['phone_no'];
	$email = $row['email'];

	$date1 = "0000-00-00";
	if($date > 0)
	$date1 = date('d/m/Y', strtotime($date));
}

?>
<table class="table table-bordered table-hover">
	<tr><th>User ID</th><td><?=$username?></td></tr>
	<tr><th>User Name</th><td><?=$name?></td></tr>
	<tr><th>Contact</th><td><?=$phone_no?></td></tr>
	<tr><th>E-mail</th><td><?=$email?></td></tr>
	<tr><th>Date</th><td><?=$date1?></td></tr>
</table>


