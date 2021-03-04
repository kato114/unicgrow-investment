<?php
ini_set("display_erros","off");
session_start();
include('config.php');
include("function/functions.php");
include("function/setting.php");



/* $currency = get_USD_TO_ETH_DOGE_LTC_BTC('BTC','USD');
echo array_shift(array_values($currency));
//echo $aaff['DOGE']['USD'];
//array_shift(array_values($aaff)[0]);
$count = count($aaff);*/


//$currency_type = $_REQUEST['currency_type'];
//$value = $_REQUEST['value'];
$random_no = rand(0,5);

$currency_type = array_rand($currncy_arr_name, 1);
$currency_type = strtoupper($currncy_arr_name[$currency_type]);

$currency = get_USD_TO_ETH_DOGE_LTC_BTC($currency_type,'USD');
$rate = array_shift(array_values($currency));

//echo $profit_percent;
if($random_no == 0){ $profit = $amt_rand-($amt_rand*$profit_percent/100);}
else{ $profit = $amt_rand+($amt_rand*$profit_percent/100);}

echo $sql = "INSERT INTO company_profitloss (`currency`,`value`,`rate`,`profit`,`date`)
values('$currency_type' , '$amt_rand' , '$rate' , '$profit' , '$systems_date_time')";
query_execute_sqli($sql);

?>
<!--<form action="" method="post">
<table width="30%" align="center">
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<th>Select Currency</th>
		<td>
			<select name="currency_type" required>
				<option value="">Select Currency</option>
				<?php
				for($i = 0; $i < count($currncy_arr); $i++)
				{ ?>
					<option value="<?=$currncy_arr_name[$i]?>"><?=$currncy_arr[$i];?></option> <?php
				}
				?>
			</select>
		</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<th>Value</th>
		<td><input type="text" name="value" required /></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Submit" /></td></tr>
</table>
</form>-->