<pre><?php

require_once __DIR__ . '/vendor/autoload.php';

$api_code = "23b5281d-3594-4a9a-b815-b625cb6fff5d";
if(file_exists('code.txt')) {
	$api_code = trim(file_get_contents('code.txt'));
}

// Create the base Blockchain class instance
$Blockchain = new \Blockchain\Blockchain($api_code);
// Needed before calling $Blockchain->Wallet or $Blockchain->Create
$Blockchain->setServiceUrl('http://localhost:3000/manager');
$wallet = $Blockchain->Create->create('btc@0410',"habanjara99@gmail.com","firstaddrs");

var_dump($wallet);

print_r($Blockchain->log);
?></pre>
</body>
</html>