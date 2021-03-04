<?php
include('../../security_web_validation.php');

session_start();
include("../function/setting.php");
include("condition.php");
require_once "../function/formvalidator.php";

if(isset($_POST['Submit']))
{ 
	$old_password = $_REQUEST['old_password'];
	$new_password = $_REQUEST['new_password'];
	$con_new_password = $_REQUEST['con_new_password'];
	
	$q = query_execute_sqli("select * from admin where password = '$old_password' ");
	$num = mysqli_fetch_array($q);
	if($num > 0){
	
		if($new_password == $con_new_password and $new_password != ''){
		
			query_execute_sqli("UPDATE admin SET password = '$new_password' ");
			?>
			<script>
				alert("Password Updated Successfully !");
				window.location = "index.php?page=<?=$val?>";
			</script> <?php
		}
		else{ ?>
			<script>
				alert("Please Enter same Password in both New and Confirm password Field !");
				window.location = "index.php?page=<?=$val?>";
			</script> <?php
		}
	}
	else{ ?>
		<script>
			alert("Please Enter Correct Old Password !"); window.location = "index.php?page=<?=$val?>";
		</script> <?php
	}
}	

else
{ ?>
<form method="post" action="">
<table class="table table-bordered">
	<tr>
		<th>Old Password</th>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group">
					<input id="pass-field3" type="password" name="old_password" class="form-control" />
					<span class="input-group-addon">
						<span toggle="#pass-field3" class="fa fa-eye toggle-password"></span>
					</span>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th>New Password</th>
		<td>
			<div class="form-group" id="data_1">
				<div class="input-group">
					<input id="pass-field4" type="password" name="new_password" class="form-control" />
					<span class="input-group-addon">
						<span toggle="#pass-field4" class="fa fa-eye toggle-password"></span>
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
					<input id="pass-field5" type="password" name="con_new_password" class="form-control" />
					<span class="input-group-addon">
						<span toggle="#pass-field5" class="fa fa-eye toggle-password"></span>
					</span>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th colspan="2" class="text-center">
			<input type="submit" value="Update" name="Submit" class="btn btn-info">
		</th>
	</tr>
</table>
</form>	
<?php } ?>

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

