<pre><?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$api_code = "26b5c2e4-13aa-40a7-85d2-c62fa5db2d9a";
if(file_exists('code.txt')) {
    $api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new \Blockchain\Blockchain($api_code);

$wallet = $Blockchain->Create->create('btc@0410',"habanjara99@gmail.com","firstaddrs");

var_dump($wallet);

print_r($Blockchain->log);

?></pre>