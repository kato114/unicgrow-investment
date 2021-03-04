<pre><?php

require_once __DIR__ . '/vendor/autoload.php';

$api_code = "23b5281d-3594-4a9a-b815-b625cb6fff5d";
if(file_exists('code.txt')) {
	$api_code = trim(file_get_contents('code.txt'));
}

// Create the base Blockchain class instance
$Blockchain = new \Blockchain\Blockchain($api_code);
// Needed before calling $Blockchain->Wallet or $Blockchain->Create
$Blockchain->setServiceUrl('http://localhost:3000/');
$wallet_guid = "9b02bd2d-34cc-4565-9cbf-d7323204d762";
$wallet_pass = "Btc@12345678";

if(is_null($wallet_guid) || is_null($wallet_pass)) {
    echo "Please enter a wallet GUID and password in the source file.<br/>";
    exit;
}
//http://127.0.0.1:3000/merchant/9b02bd2d-34cc-4565-9cbf-d7323204d762/balance?password=Btc@12345678

$Blockchain->Wallet->credentials($wallet_guid, $wallet_pass);

//echo "Using wallet " . $Blockchain->Wallet->getIdentifier() . "<br />" . PHP_EOL; //run
//$getAddress = var_dump($Blockchain->Explorer->getAddress('1AqC4PhwYf7QAyGBhThcyQCKHJyyyLyAwc'));
echo "Balance " . $Blockchain->Wallet->getBalance() . "<br />" . PHP_EOL; // run
//print $getAddress->total_received;
//$getNewAddress = ($Blockchain->Wallet->getNewAddress("Unic Grow Address"));
//print $addrs = $getNewAddress->address;
var_dump($Blockchain->Wallet->getNewAddress("Unic Grow Address"));
$amount =  number_format($Blockchain->Rates->toBTC(1, 'USD'),8);
//var_dump($Blockchain->Explorer->getAddress('3CieWDAgFCpDgGnTsivYcAu8SWje9QB3Nb'));
//echo "<p>Addresses</p>";
//$getAddresses = ($Blockchain->Wallet->getAddresses());
//foreach($getAddresses as $WalletAddress){
	//print $created_address = $WalletAddress->address."<br>";
//}
/*
// Enter recipient address here
$address = null;

try {
    // Uncomment to send
    // var_dump($Blockchain->Wallet->send($address, "0.001"));
} catch (\Blockchain\Exception\ApiError $e) {
    echo $e->getMessage() . '<br />';
}

// Multi-recipient format
$recipients = array();
$recipients[$address] = "0.001";

try {
    // Uncomment to send
    // var_dump($Blockchain->Wallet->sendMany($recipients));
} catch (Blockchain_ApiError $e) {
    echo $e->getMessage() . '<br />';
}

print_r($Blockchain->log);*/

?></pre>