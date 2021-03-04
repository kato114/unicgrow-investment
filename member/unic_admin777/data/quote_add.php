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
	$title = $_REQUEST['title'];
	$quotes = $_REQUEST['quotes'];
	$date = date('Y-m-d H:i:s');
	
	$sql = "INSERT INTO quote (title , quotes , date) values('$title' , '$quotes' , '$date') ";
	query_execute_sqli($sql);
	
	$_SESSION['cat_succ'] = "<B class='text-success'>Quote Add Successfully !!</B>";
	?> <script>window.location = "index.php?page=quote_add"; </script> <?php
}	

else
{ ?>
<form name="create_catg" action="" method="post">
<table class="table table-bordered">
	<tr>
		<th>Title</th>
		<td><input type="text" name="title" class="form-control" /></td>
	</tr>
	<tr>
		<th valign="top">Quote</th>
		<td><textarea name="quotes" class="form-control"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" name="Submit" class="btn btn-info" value="Create" />
		</td>
	</tr>
</table>
</form>
<?php } ?>

