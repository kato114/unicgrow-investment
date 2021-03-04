<?php
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");

if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Submit' or $_POST['submit'] == 'Profile')
	{
		$u_name = $_REQUEST['user_name'];
		$query = query_execute_sqli("select * from users where username = '$u_name' ");
		$num = mysqli_num_rows($query);
		if($num == 0){ echo "<B class='text-danger'>Please enter correct Username !</B>"; }
		else
		{
			while($row = mysqli_fetch_array($query))
			{
				$id_user = $row['id_user'];
				$tr_pass = $row['user_pin'];
			} ?>
			<form action="" method="post">
			<input type="hidden" name="user_id" value="<?=$id_user; ?>"  />
			<table class="table table-bordered">
				<thead><tr><th colspan="3">Enter Transaction Password</th></tr></thead>
				<tr>
					<th>Change Transaction Password</th>
					<td><input type="text" name="tr_pass" value="<?=$tr_pass?>" class="form-control"/></td>
					<td><input type="submit" name="submit" value="Update" class="btn btn-info" /></td>
				</tr>
			</table>
			</form> <?php					
		}
	}		
	elseif($_POST['submit'] == 'Update')
	{
		$id = $_POST['user_id'];
		$tr_pass = $_POST['tr_pass'];
		
		query_execute_sqli("UPDATE users set password = '$tr_pass' WHERE id_user = '$id'");

		echo "<B class='text-success'>Successfully Updated</B>";
	}
}	
else
{ ?> 
<form action="" method="post">
<table class="table table-bordered">
	<thead><tr><th colspan="3">Enter Information</th></tr></thead>
	<tr>
		<th>Enter Member UserName</th>
		<td><input type="text" name="user_name" class="form-control" /></td>
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>

<?php  }  ?>

