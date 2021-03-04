<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/setting.php");
include("../function/e_pin.php");
include("../function/functions.php");
include("../function/send_mail.php");
ini_set("display_errors","off");
?>
<script>$(document).ready(function() {	
	$("#sponsor_username").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_username = $(this).val();
		if(sponsor_username.length < 5){$("#user-result").html('');return;}
		
		if(sponsor_username.length >= 5){
			$("#user-result").html('<img src="img/ajax-loader.gif" />');
			$.post('../check_username.php', {'sponsor_username':sponsor_username},function(data)
			{
			  $("#user-result").html(data);
			});
		}
	});	
});		
</script>

<script>$(document).ready(function() {	
	$("#sponsor_username_next").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_username_next = $(this).val();
		if(sponsor_username_next.length < 5){$("#user-result_new").html('');return;}
		
		if(sponsor_username_next.length >= 5){
			$("#user-result_new").html('<img src="img/ajax-loader.gif" />');
			$.post('../check_username.php', {'sponsor_username_next':sponsor_username_next},function(data)
			{
			  $("#user-result_new").html(data);
			});
		}
	});	
});		
</script>

<script>$(document).ready(function() {	
	$("#sponsor_usernames").keyup(function (e) {
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		var sponsor_usernames = $(this).val();
		if(sponsor_usernames.length < 5){$("#user-result_new").html('');return;}
		
		if(sponsor_usernames.length >= 5){
			$("#user-result_new").html('<img src="img/ajax-loader.gif" />');
			$.post('../check_username.php', {'sponsor_usernames':sponsor_usernames},function(data)
			{
			  $("#user-results").html(data);
			});
		}
	});	
});		
</script>
<script type="text/javascript">

    $(document).ready(function(){
		$(".box").hide();
		$(".epin").hide();
		 $(".cash").show();
        $('input[type="radio"]').click(function(){
            if($(this).attr("value")=="topup-epin"){

                $(".box").hide();

                $(".cash").show();

            }
            if($(this).attr("value")=="reg-epin"){

                $(".box").hide();

                $(".epin").show();
            }
			
			if($(this).attr("value")=="first_plan"){

                $(".box").hide();

                $(".next").show();
            }
        });
    });

</script>
<style type="text/css">

    .box{ display: none; }

    .cash{ background: none; }

    .epin{ background: none; }
	
	.next{ background: none; }
	
	.button3{ display:inline;}

</style>
<?php
$date = $systems_date;
$t = date('h:i:s');

if(isset($_POST['register']))
{
	$epin_number = $_POST['epin_number'];
	$new_user = $_REQUEST['username'];
	$gen_plan = $_REQUEST['gen_plan'];
	
	if($_SESSION['generate_pin_for_user'] == 1)
	{
		$user_num = user_exist($new_user);
		if($user_num != 0)
		{
			$new_user_id = $user_num;
			
			if($gen_plan == 'plan-5000'){
				$gen_amnt = 5000;
				$sel_p = 'B';
				$mode = $pl_id = 1;
			}	
			else{	
				$gen_amnt = $fee_of_registration;
				$sel_p = '';
				$mode = 2;
				$pl_id = 0;
			}	
			
			$epin = "$epin_number E-pin ";
			for($ii = 0; $ii < $epin_number; $ii++)
			{
				do
				{
					$unique_epin = substr(md5(rand(0, 1000000)), 0, 10);
					$query = query_execute_sqli("select * from e_pin where epin = '$unique_epin' ");
					$num = mysqli_num_rows($query);
				}while($num > 0);
				
				$sql_pin = "INSERT INTO e_pin (epin, epin_type , user_id , amount , mode , time , date ,plan) 
				VALUES ('$unique_epin', '$pl_id', '$new_user_id','$gen_amnt', '$mode' , '$t', '$date','$sel_p')";
				query_execute_sqli($sql_pin);
				
				$qus = "SELECT * FROM e_pin WHERE epin = '$unique_epin' ";
				$query_epin = query_execute_sqli($qus);
				while($rok = mysqli_fetch_array($query_epin))
				{
					$epin_new_id = $rok['id'];
				}
				
				$sql_history = "INSERT INTO epin_history (epin_id, generate_id , user_id ,transfer_to, date) 
				VALUES ('$epin_new_id' , '0' , '$new_user_id' , '$new_user_id' , '$date')";
				query_execute_sqli($sql_history);
			}
			
			echo "<B class='text-success'>E-pin generated Successfully !</B>";
			$_SESSION['generate_pin_for_user'] = 0;	
		}
		else { echo "<B class='text-danger'>Enter Correct Username !</B>"; }
	}
	else{
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=generate_epin\"";
		echo "</script>";
	}
}


elseif(isset($_POST['submit']))
{
	if($_SESSION['generate_pin_for_user'] == 1)
	{
		$new_user = $_REQUEST['username'];
		$epin_type = $_POST['epin_type'];
		$epin_number = $_POST['epin_number'];
		$p_value = $_POST['plan_value'];
		
		$qu = query_execute_sqli("select * from plan_setting where id = '$epin_type' ");
		while($rrr = mysqli_fetch_array($qu))
		{ 
			$amount = $rrr['amount'];
		}
		$user_num = get_new_user_id($new_user);
		if($user_num != 0)
		{
			$new_user_id = $user_num;
			
			$epin = "$epin_number E-pin ";
			for($ii = 0; $ii < $epin_number; $ii++)
			{
				do
				{
					$unique_epin = substr(md5(rand(0, 1000000)), 0, 10);
					$query = query_execute_sqli("SELECT * FROM e_pin WHERE epin = '$unique_epin' ");
					$num = mysqli_num_rows($query);
				}while($num > 0);
				
				$date = $systems_date;
				$t = date('h:i:s');
				
				$sql_pin = "INSERT INTO e_pin (epin, epin_type , user_id , amount , mode , time , date ,plan) 
				VALUES ('$unique_epin' , '$epin_type' , '$new_user_id' ,'$amount' ,'1','$t', '$date', '$p_value')";
				query_execute_sqli($sql_pin);
				
				$qus = "SELECT * FROM e_pin WHERE epin = '$unique_epin' ";
				$query_epin = query_execute_sqli($qus);
				while($rok = mysqli_fetch_array($query_epin))
				{
					$epin_new_id = $rok['id'];
				}
				
				$generate_id = 0;
				
				$sql_history = "INSERT INTO epin_history (epin_id, generate_id , user_id ,transfer_to, date) 
				VALUES ('$epin_new_id' , '$generate_id' , '$new_user_id' , '$new_user_id' , '$date')";
				query_execute_sqli($sql_history);
			}
			echo "<B class='text-success'>Top Up E pin generated Successfully !</B>";
			$_SESSION['generate_pin_for_user'] = 0;	
		}
		else { echo "<B class='text-danger'>Enter Correct Username !</B>"; }	
	}
	else{
		echo "<script type=\"text/javascript\">";
		echo "window.location = \"index.php?page=generate_epin\"";
		echo "</script>";
	}
}
else
{ 
	$_SESSION['generate_pin_for_user'] = 1;
?>

<table class="table table-bordered">
	<tr>
		<th colspan="3">
			<!--<input type="radio" id="inv_mode" name="investmentmode"  value="reg-epin" checked="checked"  />  
			Registration E-pin  -->
			<input type="radio" id="inv_mode" name="investmentmode"  value="topup-epin" checked="checked" /> 
			Top-up E-pin
			<!--<input type="radio" id="inv_mode" name="investmentmode"  value="first_plan" /> 
			5000 E-pin-->
		</th> 
	</tr>
	
	<tr>
		<td class="epin box" colspan="3">
			<form method="post" action="">
			<table class="table table-bordered">
				<tr>
					<th>User Id</th>
					<td>
						<input type="text" name="username" id="sponsor_username" class="form-control" required />
						<span id="user-result"></span>&nbsp;
					</td>
				</tr>
				<tr>
					<th>No of E-pin</th>
					<td><input type="text" name="epin_number" class="form-control"  required /></td>
				</tr>
				<tr>
					<td colspan="2" class="text-center">
						<input type="submit" name="register" value="Generate" class="button3" />
					</td>
				</tr>
			</table>
			</form>
		</td>
		<td class="cash box" colspan="3">
			<form method="post" action="">
			<input type="hidden" name="plan_value" value="A" />	
			<table class="table table-bordered">
				<tr>
					<th>User Id</th>
					<td>
						<input type="text" name="username" id="sponsor_username_next" class="form-control" required/>
						<span id="user-result_new"></span>&nbsp;
					</td>
				</tr>
				<tr>
					<th>No of E-pin</th>
					<td><input type="text" name="epin_number" class="form-control" required/></td>
				</tr>
				<tr>
					<th>E-pin Type </th>
					<td>
						<select name="epin_type" class="form-control">
							<?php
							$qu = query_execute_sqli("select * from plan_setting ");
							while($rrr = mysqli_fetch_array($qu))
							{ 
								$plan_name = $rrr['plan_name'];
								$plan_id = $rrr['id'];
								$amount = $rrr['amount'];
								?> <option value="<?=$plan_id; ?>"><?=$plan_name.' ('.$amount.')'; ?></option> <?php	
							}	
							?>		
						</select>
					</td>
				</tr>
				<!--<tr>
					<td>Plan</td>
					<td>
						<select name="plan_value" class="form-control" required>
							<option value="">Select Plan</option>
							<option value="A">Plan A</option>
							<option value="B">Plan B</option>
						</select>
					</td>
				</tr> -->
				<tr>
					<td colspan="2" class="text-center">
						<input type="submit" name="submit" value="Generate" class="btn btn-info" />
					</td>
				</tr>
			</table>
		  </form>	
		</td>
		<td class="next box" colspan="3">
			<form method="post" action="">
			<input type="hidden" name="gen_plan" value="plan-5000" />
			<table class="table table-bordered">
				<tr>
					<th>User Id</th>
					<td>
						<input type="text" name="username" id="sponsor_usernames" class="form-control" required />
						<span id="user-results"></span>&nbsp;
					</td>
				</tr>
				<tr>
					<th>No of E-pin</th>
					<td><input type="text" name="epin_number" class="form-control" required /></td>
				</tr>
				<tr>
					<td colspan="2" class="text-center">
						<input type="submit" name="register" value="Generate" class="button3" />
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<?php } ?>