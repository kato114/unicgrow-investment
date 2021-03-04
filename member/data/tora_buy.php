<?php
session_start();
include('../security_web_validation.php');
include_once("function/setting.php");
include_once("function/trade_function.php");
include_once("function/direct_income.php");
$login_id = $_SESSION['mlmproject_user_id'];

$tora_buy_price = 1;//per share rate
$minimum_buy = 10;//dollar
$sponser_profit = 15;//percent
?>

<script>
$(document).ready(function() { 
	$("#exptr_share").hide();
	$('input[name=unit_amount]').keyup(function() {
		var unit_amount = $(this).val();
		if(unit_amount >= <?=$minimum_buy?> &&  unit_amount%<?=$tora_buy_price?>== 0){
			$("#exptr_share").show();
			var share = unit_amount/<?=$tora_buy_price?>;
			$("#exp_share").html(share);
		}
			
		
	});
});
</script>

<?php

$cash_wallet = get_user_allwallet($login_id,'amount');//use for buy
$share_wallet = get_user_allwallet($login_id,'share_holder');//use for sale
$process = 0;
if(isset($_POST['submit']))
{
	$unit_amount = mysqli_real_escape_string($con,$_POST['unit_amount']);
	$dr_amount = $investment = $unit_amount;
	$request_user_id = $login_id;//get_new_user_id($_POST['username']);
	$wfield = 'amount';
	if($request_user_id > 0){
		if($unit_amount >= $minimum_buy){
			$main_wallet = get_user_allwallet($login_id,$wfield);//use for buy
			if($cash_wallet >= $dr_amount){
				if(!isset($_SESSION['session_user_investmentacw'])){
					$_SESSION['session_user_investmentacw'] = 0;
					$panel_by = 1;
					$wallet_bal = get_user_allwallet($request_user_id,$wfield);
					$sql = "update wallet set $wfield = $wfield - $dr_amount where id='$request_user_id';";
					query_execute_sqli($sql);
					$num = query_affected_rows();
					if($num > 0){
							$share_buy = (int)($unit_amount / $tora_buy_price);
							$sql = "update wallet set share_holder = share_holder + $share_buy  where id='$request_user_id';";
							query_execute_sqli($sql);
							
							insert_wallet_account($request_user_id , $request_user_id , $dr_amount , $systems_date_time , $acount_type[32] , $acount_type_desc[32], 2 , get_user_allwallet($request_user_id,$wfield),$wallet_type[1],$remarks = "Tora share Buy");
							insert_wallet_account($request_user_id , $request_user_id , $share_buy , $systems_date_time , $acount_type[33] , $acount_type_desc[33], 1 , get_user_allwallet($request_user_id,'share_holder'),$wallet_type[3],$remarks = "Tora Share Credit");
							$real_p  = real_parent($request_user_id);
							if($real_p > 0){
								$profit = $unit_amount * $sponser_profit/100;
								$sql = "update wallet set $wfield = $wfield + $profit where id='$real_p';";
								query_execute_sqli($sql);
								insert_wallet_account($real_p , $request_user_id , $profit , $systems_date_time , $acount_type[34] , $acount_type_desc[34], 1 , get_user_allwallet($real_p,'amount'),$wallet_type[1],$remarks = "Bonus On Tora Share Buy");
							}
							$_SESSION['succ_msg'] =  "<B style='color:#008000;'>Buy Tora Share Successfully !</B>";
							?><script>alert('Buy Tora Share Successfully !');window.location = "index.php?page=<?=$val?>";</script> <?php
					}
					else{ echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>"; }
				}
				else{ ?>
					<script>
						window.location = "index.php?page=<?=$val?>";
					</script> <?php
				}
			}
			else{
				echo "<B class='text-danger'>Error : Wallet Fund In-Sufficient !!</B>";
			}
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Amount !!</B>"; }	
	}
	else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested User-Name!!</B>"; }
	
}
if($process == 0)
{ 
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg']);
	$sel = "selected";
	$cash_wallet = get_user_allwallet($login_id,'amount');//use for buy
	$share_wallet = get_user_allwallet($login_id,'share_holder');//use for sale
	?>
	<div class="col-md-12">&nbsp;</div>
	<form name="invest" id="acw" method="post" action="index.php?page=<?=$val?>">
	<table class="table table-bordered table-hover" id="secw_id">
		<tr>     
			<th width="30%">Cash Wallet</th>   
			<th>&#36;<?=$cash_wallet?></th> 
		</tr>
		<tr>     
			<th width="30%">Share Wallet</th>   
			<th><?=$share_wallet?></th> 
		</tr>
		<tr>       
			<th>Buy Amount</th>  
			<td><input type="text" name="unit_amount" class="form-control" value="<?=$_POST['unit_amount']?>"  ><!--onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/,'')"--></td>    
		</tr>
		<tr id="exptr_share">       
			<th>Share</th>  
			<td><span id="exp_share"></span></td>    
		</tr>  
		<tr>       
			<td colspan="2"><B class="text-danger">Note : Minimum Buy Amount &#36;<?=$minimum_buy?> And Share Rate &#36;<?=$tora_buy_price?>/Share !!</B></td>    
		</tr>
		<tr>     
			<td class="text-center" colspan="2">    
				<input type="submit" name="submit" value="CONFIRM" class="btn btn-info" />    
			</td>     
		</tr>     
	</table>
	</form>
	<?php
	//get_live_trade();
}
?>



