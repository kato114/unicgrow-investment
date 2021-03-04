<?php
session_start();
include('../security_web_validation.php');
include_once("function/setting.php");
include_once("function/trade_function.php");
include_once("function/direct_income.php");
$login_id = $_SESSION['mlmproject_user_id'];
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
	
	$('input[type=radio][name=trade_for]').change(function() {
		var trade_for = $(this).val();
		if(trade_for == 1){ 
			$("#trade_buy").show(); 
			$("#trade_sale").hide();
			$("#trade_rate").show();
			$('#unit_amount').html(<?=$trade_rate[1][0];?>);
			$('#buy_sell').html("Amount Deducted");
		}
		if(trade_for == 2){ 
			$("#trade_buy").hide(); 
			$("#trade_sale").show();
			$("#trade_rate").show();
			$('#unit_amount').html(<?=$trade_rate[2][0];?>);
			$('#buy_sell').html("Amount Received");
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
$st_date = date("Y-m-d",strtotime($systems_date."- $share_sale_day DAY"));
$en_date = $systems_date;
$total_share_buy = $total_share_sale = 0;
$sql = "select COALESCE(SUM(share),0) share from trade_buy 
		where user_id='$login_id' and type=1 and mode=1 and 
		DATE_FORMAT(date,'%Y-%m-%d') >= '$st_date' AND '$en_date' >= DATE_FORMAT(date,'%Y-%m-%d')";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0){
	while($row = mysqli_fetch_array($query)){
		$total_share_buy = $row['share'];
	}
}

$sql = "select COALESCE(SUM(share),0) share from trade_buy 
		where user_id='$login_id' and type=2 /*and mode=1*/ and 
		DATE_FORMAT(date,'%Y-%m-%d') >= '$st_date' AND '$en_date' >= DATE_FORMAT(date,'%Y-%m-%d')";
$query = query_execute_sqli($sql);
$num = mysqli_num_rows($query);
if($num > 0){
	while($row = mysqli_fetch_array($query)){
		$total_share_sale = $row['share'];
	}
}

$remain_share = $total_share_buy - $total_share_sale;

$trade_wallet = get_user_allwallet($login_id,'trade_gaming');//use for buy
$share_wallet = get_user_allwallet($login_id,'owner_share');//use for sale
$process = 0;
if(isset($_POST['submit']))
{
	$unit_amount = $_POST['unit_amount'];
	$no_share = $_POST['no_share'];
	$dr_amount = $investment = $unit_amount*$no_share;
	$request_user_id = $login_id;//get_new_user_id($_POST['username']);
	$trade_for = $_POST['trade_for'];
	$wfield = $trade_for == 1 ? 'trade_gaming' : 'owner_share';
	$dr_amount = $trade_for == 1 ? $unit_amount*$no_share : $no_share;
	$investment = $unit_amount*$no_share;
	if($request_user_id > 0){
		if($unit_amount >= $gtb_share_unit_amount && $no_share >= $gtb_share_unit_min){
			$main_wallet = get_user_allwallet($login_id,$wfield);//use for buy
			if($main_wallet >= $dr_amount){
				$trade_trasaction = true;
				if($trade_for == 2){
					$trade_trasaction = false;
					if($remain_share >= $no_share) $trade_trasaction = true;
				}
				if($trade_trasaction){
					if(!isset($_SESSION['session_user_investmentacw'])){
					$_SESSION['session_user_investmentacw'] = 0;
					$panel_by = 1;
					$wallet_bal = get_user_allwallet($request_user_id,$wfield);
					$sql = "update wallet set $wfield = $wfield - $dr_amount where id='$request_user_id';";
					query_execute_sqli($sql);
					$num = query_affected_rows();
					if($num > 0){
						$sql = "insert into trade_buy set user_id='$request_user_id',unit_amount='$unit_amount',
					total_amount='$investment',share='$no_share',date=CONCAT('$systems_date_time','.',(SELECT SUBSTRING_INDEX(now(6),'.',-1))),type='$trade_for'
					,gtb_balance='$wallet_bal'";
						query_execute_sqli($sql);
						$gmi = get_mysqli_insert_id();
						if($gmi > 0){
							$trade_for_desc = $trade_for == 1 ? "Buy" : "Sale";
							$ac_type = $trade_for == 1 ? $acount_type[9] : $acount_type[8];
							$ac_type_desc = $trade_for == 1 ? $acount_type_desc[9] : $acount_type_desc[8];
							$wall_type = $trade_for == 1 ? $wallet_type[4] : $wallet_type[5];
							
							insert_wallet_account($request_user_id , $request_user_id , $dr_amount , $systems_date_time , $ac_type , $ac_type_desc, 2 , get_user_allwallet($request_user_id,$wfield),$wall_type,$remarks = "Trade $trade_for_desc share");
							$trade_result = $trade_for == 1 ? get_buy_trade($request_user_id,$unit_amount,$systems_date_time,$gmi,$no_share,$panel_by=1) : get_sale_trade($request_user_id,$unit_amount,$systems_date_time,$gmi,$no_share,$panel_by=1);
							
							$trade_for_desc = $trade_for == 1 ? "Buy" : "Sale";
							if($trade_result['result']){
								//$_SESSION['succ_msg'] =  "<B style='color:#008000;'>$trade_for_desc Trade For Member is Successfully Completed !</B>";
								$succ_msg =  "$trade_for_desc Trade For Member is Successfully Completed !";
							}
							else{
								//$_SESSION['succ_msg'] =  "<B style='color:#008000;'>$trade_for_desc Trade For Member is Successfully Completed But ".$trade_result['total_share']." Share For $trade_for_desc Is Pending !</B>";
								$succ_msg =  "$trade_for_desc Trade For Member is Successfully Completed But ".$trade_result['total_share']." Share For $trade_for_desc Is Pending !";
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
					else{ echo "<B class='text-danger'>Error : Something Goes Wrong !!</B>"; }
				}
				else{ ?>
					<script>
						window.location = "index.php?page=trading_all";
					</script> <?php
				}
				}
				else{
					echo "<B class='text-danger'>Error : Share Sale Limit Is $remain_share !!</B>";
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
			<th width="30%">SMG Wallet</th>   
			<th>&#36;<?=$trade_wallet?></th> 
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



