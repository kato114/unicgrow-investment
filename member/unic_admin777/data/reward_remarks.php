<?php

$income_id = $_POST['income_id'];
$user_id = $_POST['user_id'];
$username = $_POST['username'];
$name = $_POST['name'];
$designation = $_POST['designation'];
$reward = $_POST['reward'];
$date = $_POST['date'];

if(isset($_POST['update'])){
	$income_id = $_POST['income_id'];
	$user_id = $_POST['user_id'];
	$designation = $_POST['designation'];
	$reward = $_POST['reward'];
	$remarks = $_POST['remarks'];
		
	$sql = "INSERT INTO `rewards_remarks`(`income_id`, `user_id`, `designation`, `rewards`, `remarks`, `date`) 
	VALUES ('$income_id','$user_id','$designation','$reward','$remarks',NOW())";
	query_execute_sqli($sql);
	?> 
	<script>alert("Add Remarks successfully !"); window.location = "index.php?page=bonus_reward";</script> <?php
}

?>
<form method="post" action="">
	<input type="hidden" name="income_id" value="<?=$income_id?>" />
	<input type="hidden" name="user_id" value="<?=$user_id?>" />
	<table class="table table-bordered">
		<tr>
			<th>User ID</th>
			<td><input type="text" name="username" value="<?=$username?>" class="form-control" readonly="" /></td>
		</tr>
		<tr>
			<th>Name</th>
			<td><input type="text" name="name" value="<?=$name?>" class="form-control" readonly="" /></td>
		</tr>
		<tr>
			<th>Designation</th>
			<td>
				<input type="text" name="designation" value="<?=$designation?>" class="form-control" readonly="" />
			</td>
		</tr>
		<tr>
			<th>Reward</th>
			<td><input type="text" name="reward" value="<?=$reward?>" class="form-control" readonly="" /></td>
		</tr>
		<tr>
			<th>Remarks</th>
			<td><textarea name="remarks" class="form-control"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="update" value="Update" class="btn btn-primary" />
			</td>
		</tr>
	</table>
</form>
