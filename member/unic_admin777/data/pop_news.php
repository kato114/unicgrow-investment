<?php
include('../../security_web_validation.php');
?>
<?php
if(isset($_SESSION['msg_sucss']))
{
	echo $_SESSION['msg_sucss'];
	unset($_SESSION['msg_sucss']);
}


if(isset($_POST['submit']))
{
	$title=$_POST['title'];
	$message=$_POST['message'];
	if($title!="" and $message!="")
	{
		$sql = "INSERT INTO `news`(`title`,`news`, `date`) VALUES ('$title','$message','$systems_date')";
		query_execute_sqli($sql);
		$_SESSION['msg_sucss'] = "<B class='text-success'>News add Successfully</B>";
		?> <script>window.location = "index.php?page=pop_news";</script> <?php
	}
	else { echo "<B class='text-danger'>Some field is blank !!</B>"; }
}

?>
<form action="" method="post">
<table class="table table-bordered">
	<tr>
		<th>Title</th>
		<td><input type="text" class="form-control" name="title" /></td>
	</tr>
	<tr>
		<th>News</th>
		<td><textarea name="message" class="form-control"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Submit" class="btn btn-info" />
		</td>
	</tr>
</table>
</form>
		

