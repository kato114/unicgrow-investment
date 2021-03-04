<?php
include('../../security_web_validation.php');
//die("Please contact to customer care.");
include("../function/functions.php");
include("../function/setting.php");
include("../function/direct_income.php");
?>

<script>
$(document).on('input', '#comm_id', function(){
    $(this).val($(this).val().replace(/\s/g, ''));
	var sponsor_username = $(this).val();
	if(sponsor_username.length < 2){$("#user-search").html('');return;}
		
	if(sponsor_username.length >= 2){
		
		$("#user-result").html('Lodding...');
		$.post('../check_username.php', {'search_username':sponsor_username},function(data)
		{
		  $("#user-search").html(data);
		});
	}
});


</script>

<?php
if(isset($_POST['submit']))
{
	$nolb = $_REQUEST['nolb'];
	$investment = $_REQUEST['investment_amt'] = $nolb*$lottery_amount;
	$remarks = $_REQUEST['remarks'];
	$request_user_id = get_new_user_id($_POST['username']);
	$currency_name = "&#36;";
	$pass_num = 0;
	$user_pin = $_REQUEST['user_pin'];
	
	if($request_user_id > 0){
		if($investment > 0){
			if(!isset($_SESSION['session_user_investmentacw'])){
				$_SESSION['session_user_investmentacw'] = 0;
				$sql = "insert into ledger set user_id='$request_user_id',particular='Admin Sale Ticket',cr='$nolb',date_time='$systems_date_time'";
				query_execute_sqli($sql);
				$gmi = get_mysqli_insert_id();
				if($nolb > 0 && $gmi > 0){
					get_weekly_lottery_ticket($request_user_id,$systems_date_time,$nolb,$type=3,$systems_date_time);
					echo $_SESSION['succ_msg'] =  "<B style='color:#008000;'>Sale Ticket Of $currency_name $investment Successfully !</B>";
				}
				else{ echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>"; }
			}
			else{ ?>
				<script>
					window.location = "index.php?page=sale_ticket";
				</script> <?php
			}
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Number !!</B>"; }	
	}
	else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested User-Name!!</B>"; }
	
}
else
{ 
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg'],$_SESSION['CONFIRM_BUY_OTP']);
	?>
	<div class="col-md-12">&nbsp;</div>
	<form name="invest" id="acw" method="post" action="">
	<table class="table table-bordered table-hover" id="secw_id">
		<tr>     
			<th width="30%">Username</th>   
			<th><input type="text" name="username" id="comm_id" value="<?=$_POST['username']?>" class="form-control" /><span id="user-search"></span></th> 
		</tr>
		<tr id="community_row"></tr>
		<tr>       
			<th>Number Of Lottery Buy</th>  
			<td><input type="text" name="nolb" class="form-control" value="<?=$_POST['nolb']?>"></td>    
		</tr> 
		<tr>       
			<th>Remarks</th>  
			<td><textarea type="text" name="remarks" class="form-control"></textarea></td>    
		</tr>     
		<tr>     
			<td class="text-center" colspan="2">    
				<input type="submit" name="back" value="Back" class="btn btn-info" />    
				<input type="submit" name="submit" value="CONFIRM BUY" class="btn btn-info" />    
			</td>     
		</tr>     
	</table>
	</form>
	<?php
}
?>



