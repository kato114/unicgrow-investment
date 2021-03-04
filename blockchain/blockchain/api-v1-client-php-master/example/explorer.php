<pre><?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$api_code = null;
if(file_exists('code.txt')) {
	$api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new \Blockchain\Blockchain($api_code);

// List all blocks at a certain height
// var_dump($Blockchain->Explorer->getBlocksAtHeight(1));

// Get block by index
// $block = $Blockchain->Explorer->getBlockByIndex(100000);
// var_dump($block);

// Get previous block by hash
// $hash = $block->previous_block;
// var_dump($Blockchain->Explorer->getBlock($hash));

// First mining reward transaction
var_dump($Blockchain->Explorer->getTransaction('0e3e2357e806b6cdb1f70b54c3a3a17b6714ee1f0e68bebb44a74b1efd512098'));

// Bitstamp audit (large) transaction
// var_dump($Blockchain->Explorer->getTransaction('1c12443203a48f42cdf7b1acee5b4b1c1fedc144cb909a3bf5edbffafb0cd204'));

// Get the transaction from block 1, by index
// var_dump($Blockchain->Explorer->getTransactionByIndex(14854));

// Get details of a single address
// var_dump($Blockchain->Explorer->getAddress('1AqC4PhwYf7QAyGBhThcyQCKHJyyyLyAwc'));

// Get unspent outputs for addresses
// var_dump($Blockchain->Explorer->getUnspentOutputs(array('1AqC4PhwYf7QAyGBhThcyQCKHJyyyLyAwc', '1PfcDu4n11Dv7rNexM1AxrNWqkEgwCvYWD')));

// Get the latest block
// var_dump($Blockchain->Explorer->getLatestBlock());

// Get blocks from the past
// var_dump($Blockchain->Explorer->getBlocksForDay(1262325600));

// Get blocks from a mining pool
// var_dump($Blockchain->Explorer->getBlocksByPool('Eligius'));

// Get unconfirmed transactions
// $tx = $Blockchain->Explorer->getUnconfirmedTransactions();
// var_dump($tx);

// Get inventory data for an unconfirmed transaction in $tx
// if(count($tx) > 0)
// 	var_dump($Blockchain->Explorer->getInventoryData($tx[0]->hash));

// Output log of activity
var_dump($Blockchain->log);

?></pre>