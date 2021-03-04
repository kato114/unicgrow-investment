<?php
include('../../security_web_validation.php');
session_start();

if(isset($_POST['submit']))
{ 
	$title = $_POST['title'];
	$message = $_POST['message'];
	$username = $_POST['username'];
	
	if($_POST['message'] != '' and $_POST['title'] != ''){
		if($username == 'All' or $username == 'all' or $username == 'ALL' or $username == ''){
			$sql = "SELECT * FROM users";
		}
		else{
			$sql = "SELECT * FROM users WHERE username = '$username'";
		}
		
		$quu = query_execute_sqli($sql);
		while($rrr = mysqli_fetch_array($quu))
		{
			$user_id = $rrr ['id_user'];	
			$phone_no = $rrr ['phone_no'];	
			
			$sql = "INSERT INTO message (id_user,receive_id, title, message, message_date , time) 
			VALUES ('0','$user_id' , '$title' , '$message', '$systems_date' , CURRENT_TIME()) ";
			query_execute_sqli($sql);	
			
			send_sms($phone_no,$message);
			//echo "<B class='text-success'>SMS has been sent to $phone_no . <B>";
		}
		echo "<B class='text-success'>SMS has been sent to successfully !! <B>";
	}
	else{ echo "<B class='text-danger'>Please fill all field !!<B>"; }
}
else
{
?>
<script>
$(document).ready(function() {	
	$("#user_name").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var user_name = $(this).val();
		if(user_name.length == ''){$("#user_id").html('');return;}
		if(user_name.length < 3){$("#user_id").html('');return;}
		
		if(user_name.length >= 3){
			$("#user-result").html('<img src="img/ajax-loader.gif" />');
			$.post('../check_username.php', {'sponsor_username':user_name},function(data)
			{
			  $("#user_id").html(data);
			});
		}
	});	
});		
</script>
<form name="message" action="" method="post"> 
<table class="table table-bordered">
	<tr>
		<th width="15%">Username</th>
		<td>
			<div class="pull-left">
				<input type="text" name="username" id="user_name" class="form-control" min="3" max="30" />
			</div> &nbsp;<span id="user_id"></span>
		</td>
	</tr>
	<tr>
		<th>Title</th>
		<td><input type="text" name="title" class="form-control" required /></td>
	</tr>
	<tr>
		<th>Message</th>
		<td><textarea name="message" maxlength="160" class="form-control" required></textarea></td>
	</tr>
	<tr>
		<td align="right" colspan="2">
			<input type="submit" value="Send" name="submit" class="btn btn-info" />
		</td>
	</tr>
	
</table>
</form>
<?php
}
?>
