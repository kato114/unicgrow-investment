<?php
include('../../security_web_validation.php');

session_start();
include("../function/setting.php");
include("condition.php");
require_once "../function/formvalidator.php";

if(isset($_POST['Submit'])){ 
	$old_password = $_POST['old_pass'];
	$new_password = $_POST['new_pass'];
	$con_new_password = $_POST['con_new_pass'];
	
	$user_id = $_REQUEST['user_id'];
	
	$sql = "SELECT * FROM users WHERE id_user = '$user_id' AND password = '$old_password'";
	$q = query_execute_sqli($sql);
	$num = mysqli_fetch_array($q);
	if(1)//$num > 0
	{
		
		if($new_password == $con_new_password and $new_password != ''){
			
			query_execute_sqli("UPDATE users SET password = '$new_password' WHERE id_user = '$user_id'");
			 ?>
			<script>
				alert("Password Updated Successfully !");
				window.location = "index.php?page=change_password";
			</script> <?php
		}
		else{ ?>
			<script>
				alert("Please Enter same Password in both New and Confirm password Field !");
				window.location = "index.php?page=change_password";
			</script> <?php
		}
	}
	else{ ?>
		<script>
			alert("Please Enter Correct Old Password !"); window.location = "index.php?page=change_password";
		</script> <?php
	}
}
elseif(isset($_POST['update_tr_pass'])){ 
	/*$old_tr_pass = $_POST['old_tr_pass'];*/
	$new_tr_pass = $_POST['new_tr_pass'];
	$con_new_tr_pass = $_POST['con_new_tr_pass'];
	
	$user_id = $_REQUEST['user_id'];
	
	$sql = "SELECT * FROM users WHERE id_user = '$user_id' AND password = '$old_tr_pass'";
	$q = query_execute_sqli($sql);
	$num = mysqli_fetch_array($q);
	if(1)//$num > 0
	{
		if($new_tr_pass == $con_new_tr_pass and $new_tr_pass != ''){
			query_execute_sqli("UPDATE users SET password = '$new_tr_pass' WHERE id_user = '$user_id'"); ?>
			<script>
				alert("Transaction Password Updated Successfully !"); 
				window.location = "index.php?page=change_password";
			</script> <?php
		}
		else{ ?>
			<script>
				alert("Please Enter same Transaction Password in both New and Confirm password Field !");
				window.location = "index.php?page=change_password";
			</script> <?php
		}
	}
	else{ ?>
		<script>
			alert("Please Enter Correct Old Transaction Password !"); 
			window.location = "index.php?page=change_password";
		</script> <?php
	}
}	
if(isset($_POST['submit'])){ 
	$u_name = $_POST['user_name'];
	$sql = "SELECT * FROM users WHERE username = '$u_name' ";
	$query = query_execute_sqli($sql);
	$num = mysqli_num_rows($query);
	if($num == 0){ echo "<B class='text-danger'>Please Enter right Username! </B>"; }
	else
	{
		while($row = mysqli_fetch_array($query)){
			$user_id = $row['id_user'];
			$old_pass = $row['password'];
			$tr_pass = $row['user_pin'];
		}
		
		?>
		<div class="col-md-12">
		<form method="post" action="">
		<input type="hidden" name="user_id" value="<?=$user_id?>" />
		<table class="table table-bordered">
			<thead><tr><th colspan="2">Login Password</th></tr></thead>
			<!--<tr>
				<th>Old Password</th>
				<td>
					<div class="form-group" id="data_1">
						<div class="input-group">
							<input id="pass-field" type="password" name="old_pass" value="<?=$old_pass?>" class="form-control" />
							<span class="input-group-addon">
								<span toggle="#pass-field" class="fa fa-eye toggle-password"></span>
							</span>
						</div>
					</div>
				</td>
			</tr>-->
			<tr>
				<th>New Password</th>
				<td>
					<div class="form-group" id="data_1">
						<div class="input-group">
							<input id="pass-field1" type="password" name="new_pass" class="form-control" />
							<span class="input-group-addon">
								<span toggle="#pass-field1" class="fa fa-eye toggle-password"></span>
							</span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th>Confirm New Password</th>
				<td>
					<div class="form-group" id="data_1">
						<div class="input-group">
							<input id="pass-field2" type="password" name="con_new_pass" class="form-control" />
							<span class="input-group-addon">
								<span toggle="#pass-field2" class="fa fa-eye toggle-password"></span>
							</span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th colspan="2" class="text-center">
					<input type="submit" value="Update" name="Submit" class="btn btn-info btn-sm">
				</th>
			</tr>
		</table>
		</form>
		</div>
		<!--<div class="col-md-6">
		<form method="post" action="">
		<input type="hidden" name="user_id" value="<?=$user_id?>" />
		<table class="table table-bordered">
			<thead><tr><th colspan="2">Transaction Password</th></tr></thead>
			<tr>
				<th>New Transaction Password</th>
				<td>
					<div class="form-group" id="data_1">
						<div class="input-group">
							<input id="pass-field5" type="password" name="new_tr_pass" class="form-control" />
							<span class="input-group-addon">
								<span toggle="#pass-field5" class="fa fa-eye toggle-password"></span>
							</span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th>Confirm New Transaction Password</th>
				<td>
					<div class="form-group" id="data_1">
						<div class="input-group">
							<input id="pass-field6" type="password" name="con_new_tr_pass" class="form-control" />
							<span class="input-group-addon">
								<span toggle="#pass-field6" class="fa fa-eye toggle-password"></span>
							</span>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th colspan="2" class="text-center">
					<input type="submit" value="Update" name="update_tr_pass" class="btn btn-info btn-sm">
				</th>
			</tr>
		</table>
		</form>
		</div>-->	<?php
	}
}
else
{ ?> 
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
}  ?>

<script type="text/javascript">
$(".toggle-password").click(function() {
	$(this).toggleClass("fa-eye fa-eye-slash");
	var input = $($(this).attr("toggle"));
	if (input.attr("type") == "password") {
		input.attr("type", "text");
	} 
	else {
		input.attr("type", "password");
	}
});
</script>

