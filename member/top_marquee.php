<marquee width="100%" scrollamount="3" class="text-white" bgcolor="#000000" style="padding:5px;">
	<?php
	//$str = file_get_contents("data.json"); //Offline Testing Code
	$str = file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
	$json = json_decode($str, true);
	for($i = 0; $i < count($json); $i++)
	{
		$cur_id = $json[$i]['id'];
		$name = $json[$i]['name'];
		$symbol = $json[$i]['symbol'];
		$price_usd = $json[$i]['price_usd'];
		$price_btc = $json[$i]['price_btc'];
		?>
			<?=$symbol." : &#36;".$price_usd?> &nbsp;&nbsp;&nbsp;&nbsp;
	<?php
	}
	?>
</marquee>
