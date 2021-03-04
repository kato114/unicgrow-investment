<pre><?php
ini_set("display_errors","on");
require_once __DIR__ . '/vendor/autoload.php';

$api_code = "23b5281d-3594-4a9a-b815-b625cb6fff5d";
if(file_exists('code.txt')) {
    $api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new \Blockchain\Blockchain($api_code);
$Blockchain->setServiceUrl('http://localhost:3000/');

$wallet_guid = "9b02bd2d-34cc-4565-9cbf-d7323204d762";
$wallet_pass = "Jha@8510811811";

if(is_null($wallet_guid) || is_null($wallet_pass)) {
    echo "Please enter a wallet GUID and password in the source file.<br/>";
    exit;
}

$Blockchain->Wallet->credentials($wallet_guid, $wallet_pass);

echo "Using wallet " . $Blockchain->Wallet->getIdentifier() . "<br />" . PHP_EOL;

echo "Balance " . $Blockchain->Wallet->getBalance() . "<br />" . PHP_EOL;

var_dump($Blockchain->Wallet->getNewAddress("Programmatically created new address."));

echo "<p>Addresses</p>";
var_dump($Blockchain->Wallet->getAddresses());

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

print_r($Blockchain->log);

?></pre>