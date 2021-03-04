<?php
include('../security_web_validation.php');
?>
<?php
session_start();
$id = $_SESSION['mlmproject_user_id'];

print "<br>".$_SESSION['error'].$_SESSION['success']."<br>";
$_SESSION['error'] = $_SESSION['success'] = '';

?>
<form name="message" action="index.php?page=compose_post" method="post">
<table class="table table-bordered table-hover">
	<tr>
		<th>Title</th>
		<td><input type="text" name="title" class="form-control" /></td>
	</tr>
	<tr>
		<th>Username</th>
		<td><input type="text" name="username" value="Admin" readonly="readonly" class="form-control"></td>
	</tr>
	<tr>
		<th>Message</th>
		<td><textarea name="message" class="form-control"></textarea></td>
	</tr>
	<tr>
	<td colspan="2" class="span1 text-center">
		<input type="submit" value="Send" name="submit" class="btn btn-primary" />
	</td>
	</tr>
</table>
</form>


