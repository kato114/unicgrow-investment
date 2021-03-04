<pre><?php

require_once __DIR__ . '/vendor/autoload.php';

$api_code = "23b5281d-3594-4a9a-b815-b625cb6fff5d";
if(file_exists('code.txt')) {
	$api_code = trim(file_get_contents('code.txt'));
}

// Create the base Blockchain class instance
$Blockchain = new \Blockchain\Blockchain($api_code);
// Needed before calling $Blockchain->Wallet or $Blockchain->Create
$Blockchain->setServiceUrl('http://localhost:3000');
// List all blocks at a certain height run
//var_dump($Blockchain->Explorer->getBlocksAtHeight(1));

// Get block by index not
//$block = $Blockchain->Explorer->getBlockByIndex(100000);
//var_dump($block);

// Get previous block by hash not
// $hash = $block->previous_block;
// var_dump($Blockchain->Explorer->getBlock($hash));

// First mining reward transaction run
//var_dump($Blockchain->Explorer->getTransaction('0e3e2357e806b6cdb1f70b54c3a3a17b6714ee1f0e68bebb44a74b1efd512098'));

// Bitstamp audit (large) transaction run
// var_dump($Blockchain->Explorer->getTransaction('1c12443203a48f42cdf7b1acee5b4b1c1fedc144cb909a3bf5edbffafb0cd204'));

// Get the transaction from block 1, by index not
// var_dump($Blockchain->Explorer->getTransactionByIndex(14854));

// Convert a fiat amount to BTC run
var_dump($Blockchain->Explorer->getAddress('1AqC4PhwYf7QAyGBhThcyQCKHJyyyLyAwc'));

// Get unspent outputs for addresses run
// var_dump($Blockchain->Explorer->getUnspentOutputs(array('1AqC4PhwYf7QAyGBhThcyQCKHJyyyLyAwc', '1PfcDu4n11Dv7rNexM1AxrNWqkEgwCvYWD')));

// Get the latest block run
// var_dump($Blockchain->Explorer->getLatestBlock());

// Get blocks from the past run
// var_dump($Blockchain->Explorer->getBlocksForDay(1262325600));

// Get blocks from a mining pool not
// var_dump($Blockchain->Explorer->getBlocksByPool('Eligius'));

// Get unconfirmed transactions run
// $tx = $Blockchain->Explorer->getUnconfirmedTransactions();
// var_dump($tx);

// Get inventory data for an unconfirmed transaction in $tx not
// if(count($tx) > 0)
// 	var_dump($Blockchain->Explorer->getInventoryData($tx[0]->hash));

// Output log of activity
var_dump($Blockchain->log);
?></pre>
</body>
</html>