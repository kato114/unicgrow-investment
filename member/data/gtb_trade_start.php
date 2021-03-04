<?php
session_start();
include('../security_web_validation.php');
include_once("function/setting.php");
include_once("function/trade_function.php");
include_once("function/direct_income.php");
$login_id = $_SESSION['mlmproject_user_id'];
$trade_wallet = get_user_allwallet($login_id,'trade_gaming');//use for buy
$share_wallet = get_user_allwallet($login_id,'owner_share');//use for sale
$cash_wallet = get_user_allwallet($login_id,'amount');//use for sale
?>

<script>
$(function() {
  $('#unit_amount').on('input', function() {
    match = (/(\d{0,2})[^.]*((?:\.\d{0,2})?)/g).exec(this.value.replace(/[^\d.]/g, ''));
    this.value = match[1] + match[2];
  });
});
$(document).ready(function() { 
	$("#trade_buy").hide();
	$("#trade_sale").hide();
	$("#trade_rate").hide();
	$("#tot_amt").hide();
	var selectElem = $('select[name ="by_wallet"]');
	$('input[type=radio][name=trade_for]').change(function() {
		var trade_for = $(this).val();
		
		if(trade_for == 1){ 
			selectElem.attr("required", true);
			$("#by_wall").val("");
			$("#trade_buy").show(); 
			$("#trade_sale").hide();
			$("#trade_rate").show();
			$('#unit_amount').html(<?=$trade_rate[1][0];?>);
			$('#buy_sell').html("Amount Deducted");
		}
		if(trade_for == 2){ 
			selectElem.removeAttr("required");
			$("#trade_buy").hide(); 
			$("#by_wallet").hide();
			$("#trade_sale").show();
			$("#trade_rate").show();
			$('#unit_amount').html(<?=$trade_rate[2][0];?>);
			$('#buy_sell').html("Amount Received");
		}
	});
	$("#by_wallet").hide();
	$('#by_wall').change(function() {
		 var bwl = $(this).val();
		 $("#by_wallet").show();
		 if(bwl == 1){
		 	$('#w_balance').html("<?=$trade_wallet?>");
		 }
		 else if(bwl == 2){
		 	$('#w_balance').html("<?=$cash_wallet?>");
		 }
		 else{
		 	$("#by_wallet").hide();
		 }
		 
	});
	$('input[name=no_share]').keyup(function () {
		set_calculate();
	});
	$('input[name=unit_amount]').keyup(function () {
		set_calculate();
	});
});
function set_calculate(){
	var u_amt = $('input[name=unit_amount]').val();
	var no_share = $('input[name=no_share]').val();
	if(no_share > 0 && u_amt > 0){
		$("#tot_amt").show(); 
		var total = u_amt*no_share;
		$('#total_price').html(total);
	}
	else{ $('#total_price').html(0); }
}
</script>

<?php

$process = 0;
if(isset($_POST['submit']))
{
	if(isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]){
		$unit_amount = $_POST['unit_amount'];
		$no_share = $_POST['no_share'];
		$dr_amount = $investment = $unit_amount*$no_share;
		$request_user_id = $login_id;//get_new_user_id($_POST['username']);
		$trade_for = $_POST['trade_for'];
		$by_wallet = $_POST['by_wallet'];
		$wfield = $trade_for == 1 ? ($by_wallet == 1 ? 'trade_gaming' : 'amount') : 'owner_share';
		$hmode = $trade_for == 1 ? ($by_wallet == 1 ? 0 : 2) : 2;
		$udate = date("Y-m-d H:i:s",strtotime($systems_date_time."+ $share_sale_day DAY"));
		$dr_amount = $trade_for == 1 ? $unit_amount*$no_share : $no_share;
		$investment = $unit_amount*$no_share;
		if($request_user_id > 0){
			if($unit_amount >= $gtb_share_unit_amount && $no_share >= $gtb_share_unit_min){
				$main_wallet = get_user_allwallet($login_id,$wfield);//use for buy
				if($main_wallet >= $dr_amount){
					if(!isset($_SESSION['session_user_investmentacw'])){
						$_SESSION['session_user_investmentacw'] = 0;
						$panel_by = 1;
						$wallet_bal = get_user_allwallet($request_user_id,$wfield);
						$sql = "insert into trade_buy set user_id='$request_user_id',unit_amount='$unit_amount',
						total_amount='$investment',share='$no_share',date=CONCAT('$systems_date_time','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),type='$trade_for'
						,gtb_balance='$wallet_bal',`bywallet`='$by_wallet',`hmode`='$hmode',`udate`='$udate'";
						query_execute_sqli($sql);
						$gmi = get_mysqli_insert_id();
						if($gmi > 0){
							$trade_for_desc = $trade_for == 1 ? "Buy" : "Sale";
							$ac_type = $trade_for == 1 ? $acount_type[9] : $acount_type[8];
							$ac_type_desc = $trade_for == 1 ? $acount_type_desc[9] : $acount_type_desc[8];
							$wall_type = $trade_for == 1 ? ($by_wallet == 1 ? $wallet_type[4] : $wallet_type[1]) : $wallet_type[5];
							$trade_result = $trade_for == 1 ? get_buy_trade($request_user_id,$unit_amount,$systems_date_time,$gmi,$no_share,$panel_by=1,$by_wallet) : get_sale_trade($request_user_id,$unit_amount,$systems_date_time,$gmi,$no_share,$panel_by=1);
							
							if($trade_for == 1){
								$total_tx_amt = $trade_result['total_tx_amt'];
								$total_tx_share = $trade_result['total_tx_share'];
								$total_trade_amt = $total_tx_amt + ($no_share-$total_tx_share)*$unit_amount;
								$sql = "update wallet set $wfield = $wfield - $total_trade_amt where id='$request_user_id';";
								query_execute_sqli($sql);
								insert_wallet_account($request_user_id , $request_user_id , $total_trade_amt , $systems_date_time , $ac_type , $ac_type_desc, 2 , get_user_allwallet($request_user_id,$wfield),$wall_type,$remarks = "Trade $trade_for_desc share");
							}
							else{
								$sql = "update wallet set $wfield = $wfield - $no_share where id='$request_user_id';";
								query_execute_sqli($sql);
								insert_wallet_account($request_user_id , $request_user_id , $no_share , $systems_date_time , $ac_type , $ac_type_desc, 2 , get_user_allwallet($request_user_id,$wfield),$wall_type,$remarks = "Trade $trade_for_desc share");
							}
							
							$trade_for_desc = $trade_for == 1 ? "Buy" : "Sale";
							if($trade_result['result']){
								$succ_msg =  "$trade_for_desc Trade For Member is Successfully Completed !";
							}
							else{
								if($trade_result['error']){
									$succ_msg =  "$trade_for_desc Trade For Member is Successfully Completed But ".$trade_result['total_share']." Share For $trade_for_desc Is Pending, Because Some Error Occour!";
								}
								else{
									$succ_msg =  "$trade_for_desc Trade For Member is Successfully Completed But ".$trade_result['total_share']." Share For $trade_for_desc Is Pending !";
								}
							}
							$process = 1;
							?>
							<script>
								alert("<?=$succ_msg?>");
								window.location = "index.php?page=trading_all";
							</script>
							<?php
						}
						
					}
					else{ ?>
						<script>
							window.location = "index.php?page=trading_all";
						</script> <?php
					}
					
				}
				else{
					echo "<B class='text-danger'>Error : Wallet Fund In-Sufficient !!</B>";
				}
			}
			else{ echo "<B class='text-danger'>Error : Please Enter Correct Number !!</B>"; }	
		}
		else{ echo "<B class='text-danger'>Error : Please Enter Correct Requested User-Name!!</B>"; }
	}
	else{ echo $error_code = "<B class='text-danger'>Error : Please Enter correct Code !!</B>"; }
	
}
//if($process == 0){ 
	
	if(count($_POST) == 0)
	unset($_SESSION['session_user_investmentacw'],$_SESSION['succ_msg']);
	$sel = "selected";
	$checked = "checked='checked'";
	?>
	<div class="col-md-12">&nbsp;</div>
	<form name="invest" id="acw" method="post" action="index.php?page=<?=$val?>">
	<table class="table table-bordered table-hover" id="secw_id">
		<thead>
		<tr style="font-size:12pt;">     
			<th width="30%">Trade</th>   
			<th>
				<div class="col-md-6"><input type="radio" name="trade_for" value="1" <?php if($trade_for == 1)echo $checked;?>  required />&nbsp;Buy</div>
				<div class="col-md-6"><input type="radio" name="trade_for" value="2" <?php if($trade_for == 2)echo $checked;?> required />&nbsp;Sale</div>
			</th> 
		</tr>
		</thead>
		<tr id="trade_buy" style="display:none;">     
			<th width="30%">From Wallet</th>
			<th>
				<select name="by_wallet" id="by_wall" class="form-control" required>
					<option value="">Select Wallet</option>
					<option value="1">SMG Wallet</option>
					<option value="2">Cash Wallet</option>
				</select>  
		</tr>
		<tr id="by_wallet" style="display:none;">     
			<th width="30%">Wallet Balance</th>
			<th>&#36;<span id="w_balance"></span></th>
		</tr>
		<tr id="trade_sale" style="display:none;">     
			<th width="30%">SMG Share</th>   
			<th><?=$share_wallet?></th> 
		</tr>
		<tr>       
			<th>Unit Price</th>  
			<td>
				<input type="text" name="unit_amount" id="unit_amount" class="form-control" value="<?=$_POST['unit_amount']?>" />
			</td>    
		</tr> 
		<tr>       
			<th>Number Of Share</th>  
			<td><input type="text" name="no_share" id="no_share" class="form-control" value="<?=$_POST['no_share']?>" onkeyup="this.value=this.value.replace(/[^\d{0,4}]/,'')" /></td>    
		</tr> 
		<tr id="tot_amt" style="display:none;">     
			<th width="30%" id="buy_sell"></th>   
			<th id="total_price"></th> 
		</tr>
		<tr>     
			<th>Security Code</th>
			<td>
				
				<div class="pull-left"><input type="password" name="captcha" class="form-control" /></div>
				<div class="pull-right">&nbsp;<img src="captcha.php" /></div>
				
			</td>  
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
//}
?>



