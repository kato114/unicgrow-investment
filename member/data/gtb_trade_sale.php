<?php
//session_start();
//include('../security_web_validation.php');
//include_once("function/setting.php");
//include_once("function/trade_function.php");
//include_once("function/direct_income.php");
//$login_id = $_SESSION['mlmproject_user_id'];
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
$main_wallet = get_user_allwallet($login_id,'owner_share');//trade_gaming
$process = 0;
if(isset($_POST['submit']))
{
	$unit_amount = $_POST['unit_amount'];
	$no_share = $_POST['no_share'];
	$dr_amount = $investment = $unit_amount*$no_share;
	$request_user_id = $login_id;//get_new_user_id($_POST['username']);
	$trade_for = 2;
	if($request_user_id > 0){
		if($unit_amount >= $gtb_share_unit_amount && $no_share >= $gtb_share_unit_min){
			if($main_wallet >= $no_share){
				if(!isset($_SESSION['session_user_investmentacw'])){
					$_SESSION['session_user_investmentacw'] = 0;
					$panel_by = 1;
					$wallet_bal = get_user_allwallet($request_user_id,'owner_share');;
					$sql="update wallet set owner_share = owner_share - $no_share where id='$request_user_id';";
					query_execute_sqli($sql);
					$num = query_affected_rows();
					if($num > 0){
						$sql = "insert into trade_buy set user_id='$request_user_id',unit_amount='$unit_amount',
						total_amount='$investment',share='$no_share',date=CONCAT('$systems_date_time','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),type='$trade_for'
						,gtb_balance='$wallet_bal'";
						query_execute_sqli($sql);
						$gmi = get_mysqli_insert_id();
						if($gmi > 0){
							insert_wallet_account($request_user_id , $request_user_id , $no_share , $date , $acount_type[8] , $acount_type_desc[8], 2 , get_user_allwallet($request_user_id,'owner_share'),$wallet_type[5],$remarks = "Trade Sale share");
							$trade_result = get_sale_trade($request_user_id,$unit_amount,$systems_date_time,$gmi,$no_share,$panel_by);
							$trade_for_desc = $trade_for == 1 ? "Buy" : "Sale";
							if($trade_result['result']){
								echo $_SESSION['succ_msg'] =  "<B style='color:#008000;'>$trade_for_desc Trade For Member is Successfully Completed !</B>";
							}
							else{
								echo $_SESSION['succ_msg'] =  "<B style='color:#008000;'>$trade_for_desc Trade For Member is Successfully Completed But ".$trade_result['total_share']." Share For $trade_for_desc Is Pending !</B>";
							}
							$process = 1;
						}
						?>
						<script>
							alert("<?=$_SESSION['succ_msg']?>");
							window.location = "index.php?page=gtb_trade_sale";
						</script>
						<?php
					}
					else{ echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>"; }
				}
				else{ ?>
					<script>
						window.location = "index.php?page=gtb_trade_sale";
					</script> <?php
				}
			}
			else{
				echo "<B class='text-danger'>Error : Sale For Share In-Sufficient !!</B>";
			}
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Number !!</B>"; }	
	}
	else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested User-Name!!</B>"; }
	
}
if($process == 0)
{ 
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg']);
	$sel = "selected";
	?>
	<div class="col-md-12">&nbsp;</div>
	<form name="invest" id="acw" method="post" action="">
	<table class="table table-bordered table-hover" id="secw_id">
		<tr>     
			<th width="30%">Share Wallet</th>   
			<th><?=$main_wallet?> Share</th> 
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
	get_live_trade();
}
?>



