<?php
include('../../security_web_validation.php');

session_start();
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
?>
<script>
$(document).ready(function() {	
	$("#change_username").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var change_username = $(this).val();
		if(change_username.length < 3){$("#user-result").html('');return;}
		
		if(change_username.length >= 3){
			$("#user-result").html('<img src="img/ajax-loader.gif" />');
			$.post('../check_username.php', {'change_username':change_username},function(data)
			{
			  $("#user-result").html(data);
			});
		}
	});	
});		
</script>
<?php


if(isset($_REQUEST['submit'])){
	$u_name = $_REQUEST['user_name'];
	$query = query_execute_sqli("select * from users where username = '$u_name' ");
	$num = mysqli_num_rows($query);
	if($num == 0){ echo "<B class='text-danger'>Please enter correct Username !</B>"; }
	else{
		while($row = mysqli_fetch_array($query)){
			$id_user = $row['id_user'];
			$full_name = $row['f_name'].' '.$row['l_name'];
			$username = $row['username'];
		} ?>
		<form action="" method="post">
		<input type="hidden" name="user_id" value="<?=$id_user; ?>"  />
		<table class="table table-bordered">
			<thead><tr><th colspan="2">Change Username</th></tr></thead>
			<tr>
				<th>Username</th>
				<td>
					<input type="text" name="username" value="<?=$username?>" class="form-control" readonly="" />
				</td>
			</tr>
			<tr>
				<th>New User ID</th>
				<td>
					<input type="text" name="change_username" id="change_username" class="form-control" pattern=".{4,}" required title="4 characters minimum" />
					<span id="user-result"></span>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="update" value="Update" class="btn btn-info" /></td>
			</tr>
		</table>
		</form> <?php					
	}
}		
elseif(isset($_REQUEST['update'])){
	$id = $_POST['user_id'];
	$username = $_POST['change_username'];
	
	$results = query_execute_sqli("SELECT * FROM users WHERE username = '$username'");
	$num = mysqli_num_rows($results);
	
	if($num == 0){
		query_execute_sqli("UPDATE users SET username = '$username' WHERE id_user = '$id'");
		?>
		<script>alert("Successfully Updated"); window.location ="index.php?page=<?=$val?>";</script> <?php
	}
	else{ echo "<B class='text-danger'>Username Already Exist</B>"; }
}
else{ ?>
<form action="" method="post">
<table class="table table-bordered">
	<tr><thead><th colspan="3">Enter Information</th></thead></tr>
	<tr>
		<th>Enter Member User ID</th>
		<td><input type="text" name="user_name" class="form-control" /></td>
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>
<?php  
} ?>

