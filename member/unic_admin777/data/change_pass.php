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
				$password = $row['password'];
			} ?>
			<form action="" method="post">
			<input type="hidden" name="user_id" value="<?=$id_user; ?>"  />
			<table class="table table-bordered">
				<thead><tr><th colspan="2">Change Password</th></tr></thead>
				<tr>
					<th width="20%">User ID</th>
					<td><input type="text" value="<?=$u_name?>" class="form-control" readonly="" /></td>
				</tr>
				<tr>
					<th>Password Type</th>
					<td>
						<select name="type_search" class="form-control" required>
							<option value="">Select Type</option>
							<option value="1">Login Password</option>
							<!--<option value="2">Transaction Password</option>-->
						</select>
					</td>
				</tr>
				<tr>
					<th>Old Password</th>
					<td><input type="text" value="<?=$password?>" class="form-control" readonly="" /></td>
				</tr>
				<tr>
					<th>New Password</th>
					<td><input type="text" name="password" class="form-control" required /></td>
				</tr>
				<tr>
					<td colspan="2" class="text-center">
						<input type="submit" name="submit" value="Update" class="btn btn-info" />
					</td>
				</tr>
			</table>
			</form> <?php					
		}
	}		
	elseif($_POST['submit'] == 'Update')
	{
		$user_id = $_POST['user_id'];
		$password = $_POST['password'];
		$type_search = $_POST['type_search'];
		
		switch($type_search) {
			case 1 : $field = "password"; break;
			case 2 : $field = "user_pin"; break;
		}
		
		query_execute_sqli("UPDATE users SET $field = '$password' WHERE id_user = '$user_id'");
		
		?> <script>alert("Successfully Updated"); window.location = "index.php?page=<?=$val?>";</script> <?php 
	}
}	
else
{ ?> 
<form action="" method="post">
<table class="table table-bordered">
	<thead><tr><th colspan="3">Enter Information</th></tr></thead>
	<tr>
		<th>Enter Member User ID</th>
		<td><input type="text" name="user_name" class="form-control" /></td>
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>

<?php  }  ?>

