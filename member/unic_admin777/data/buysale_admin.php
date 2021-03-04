<?php
include('../../security_web_validation.php');
//die("Please contact to customer care.");
include("../function/functions.php");
include("../function/setting.php");
include("../function/trade_function.php");
include("../function/direct_income.php");

$admin_id = $_SESSION['intrade_admin_id'];
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
		  $("#user-search").html("("+data+")");
		});
	}
});
</script>

<?php
if(isset($_POST['submit'])){
	$unit_amount = $_POST['unit_amount'];
	$no_share = $_POST['no_share'];
	$investment = $unit_amount*$no_share;
	$request_user_id = get_new_user_id($_POST['username']);
	$trade_for = $_POST['trade_for'];
	
	if($request_user_id > 0){
		if($unit_amount >= $gtb_share_unit_amount && $no_share >= $gtb_share_unit_min){
			if(!isset($_SESSION['session_user_investmentacw'])){
				$wallet_bal = get_user_allwallet($request_user_id,'trade_gaming');;
				$sql = "INSERT INTO trade_buy set user_id='$request_user_id',unit_amount='$unit_amount',
				total_amount='$investment',share='$no_share',date=CONCAT('$systems_date_time','.', 
				(SELECT SUBSTRING_INDEX(now(6),'.',-1))),type='$trade_for',gtb_balance='$wallet_bal', 
				panel_id = '$admin_id'";
				query_execute_sqli($sql);
				$gmi = get_mysqli_insert_id();
				$_SESSION['session_user_investmentacw'] = 0;
				if($gmi > 0){
					$panel_by = 0;
					$trade_result = $trade_for == 1 ? get_buy_trade($request_user_id,$unit_amount,$systems_date_time,$gmi,$no_share,$panel_by) : get_sale_trade($request_user_id,$unit_amount,$systems_date_time,$gmi,$no_share,$panel_by);
					$trade_for_desc = $trade_for == 1 ? "Buy" : "Sale";
					if($trade_result['result']){
						echo $_SESSION['succ_msg'] =  "<B class='text-success'>$trade_for_desc Trade For Member is Successfully Completed !</B>";
					}
					else{
						echo $_SESSION['succ_msg'] =  "<B class='text-success'>$trade_for_desc Trade For Member is Successfully Completed But ".$trade_result['total_share']." Share For $trade_for_desc Is Pending !</B>";
					}
				}
				else{ echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>"; }
			}
			else{ ?> <script>window.location = "index.php?page=<?=$val?>";</script> <?php }
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Number !!</B>"; }	
	}
	else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested User-Name!!</B>"; }
	
}
else
{ 
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg']);
	?>
	<div class="col-md-12">&nbsp;</div>
	<form name="invest" id="acw" method="post" action="">
	<table class="table table-bordered table-hover" id="secw_id">
		<tr>     
			<th width="30%">Trade For</th>   
			<th>
				<select name="trade_for" class="form-control">
					<option value="1">Buy</option>
					<option value="2">Sale</option>
				</select>
			</th> 
		</tr>
		<tr>     
			<th width="30%">Username &nbsp;<span id="user-search"></span></th>   
			<th><input type="text" name="username" id="comm_id" value="<?=$_POST['username']?>" class="form-control" /></th> 
		</tr>
		<tr id="community_row"></tr>
		<tr>       
			<th>Unit Amount</th>  
			<td><input type="text" name="unit_amount" class="form-control" value="<?=$_POST['unit_amount']?>"></td>    
		</tr> 
		<tr>       
			<th>Share</th>  
			<td><input type="text" name="no_share" class="form-control" value="<?=$_POST['no_share']?>"></td>    
		</tr> 
		<tr>     
			<td class="text-center" colspan="2">    
				<input type="submit" name="submit" value="CONFIRM" class="btn btn-info" />    
			</td>     
		</tr>     
	</table>
	</form>
	<?php
}
?>



