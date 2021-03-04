<?php
error_reporting(0);
session_start();
include "config.php";
include "function/functions.php";
include "function/setting.php";
extract($_REQUEST);
if(isset($_REQUEST['val']))
{
	if($_REQUEST['val'] >= $minimum_investment_fee and ($_REQUEST['val']%$per_day_multiple_pair == 0)){
		if($_REQUEST['val'] <= $set_max_amount[0]){
			$one_usd_value = get_USD_TO_BITCOIN("USD",1);
			$_SESSION['bitcoin_addresss'] = $address = admin_btc_address();
			$investment = $_REQUEST['val'];
			$priceBTC = $investment * $one_usd_value;
			do{
				$priceBTC = $priceBTC + round((rand(1000,9999)/1000),2)/10000;
				$sql = "select * from request_crown_wallet where `date` >='$time' and request_crowd='$priceBTC'";
				$num = mysqli_num_rows(query_execute_sqli($sql));
			}while($num > 0);
			$_SESSION['priceBTC'] = $priceBTC;
			?>
			<div id="bitcoin_addresss_info">
				<div class="col-md-12 color-red">
					<B>Are you sure?</B><br />
					<B>Current Rate in Bitcoin : <?=$one_usd_value?></B>
				</div>
				<div class="col-md-8" style="text-align:left;  line-height: 27px; padding-top:15px; color:#000;">
					This is Address <?=$_SESSION['bitcoin_addresss'];?></B> where you have to 
					transfer <span class="priceBTC"><B><?=$priceBTC;?></B></span> Bitcoin. To purchase <?=$investment?> USD  Investment, Put it to
					 this address. This address is using privately by Crypto Trade officials, please 
					 don't use for any other purpose. Confirm address for transfer is 
					 <B><?=$_SESSION['bitcoin_addresss'];?></B> *Placment should exactly 
					 <span class="priceBTC"><B><?=$priceBTC;?> Bitcoin </B></span>for confirm your purchase. 
					 <p class="text-red">Note* : This Transaction Complete With-in 10 Minute , Otherwise Transaction Will be Cancelled</p><br />
				</div>
			<div class="col-md-4 text-right">
				<img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=bitcoin:<?=$_SESSION['bitcoin_addresss'];?>?amount=<?=$priceBTC;?>&message=All Coins Trade" id="qr" />
			</div>
		</div>  
			<?php
		}
		else{
			echo "<font color='#ff0000'>Please Fill Order Amount Is Less Then Or Equal Of $set_max_amount[0] USD</font>";
		}
	}
	else{
		echo "<font color='#ff0000'>Please Fill Order Amount Is Multiple Of $per_day_multiple_pair USD</font>";
	}
	
}

?>