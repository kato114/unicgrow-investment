<?php
include('../../security_web_validation.php');

session_start();

if(isset($_SESSION['cat_succ']))
{
	echo $_SESSION['cat_succ'];
	unset($_SESSION['cat_succ']);
}

if(isset($_POST['Submit']))
{ 
	$category = $_REQUEST['category'];
	$email = $_REQUEST['email'];
	$date = date('Y-m-d H:i:s');
	
	$sql = "Insert into my_ticket_categry (category , email , date) values('$category' , '$email' , '$date') ";
	query_execute_sqli($sql);
	
	$_SESSION['cat_succ'] = "<B class='text-success'>Category Add Successfully !!</B>";
	?> <script>window.location = "index.php?page=support_category"; </script> <?php
}	

else
{ ?>
<form name="create_catg" action="" method="post">
<table class="table table-bordered">
	<tr>
		<th>Category</th>
		<td><input type="text" name="category" class="form-control" /></td>
	</tr>
	<tr>
		<th>E-mail</th>
		<td><input type="text" name="email" class="form-control" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" name="Submit" class="btn btn-info" value="Create" />
		</td>
	</tr>
</table>
</form>
<?php } ?>

