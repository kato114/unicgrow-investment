<pre><?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$api_code = null;
if(file_exists('code.txt')) {
    $api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new \Blockchain\Blockchain($api_code);

// The raw transaction hex for a valid transaction, will not 
// send though, since it's an existing transaction
$tx = '0100000001adba98f4ddfb9183ded2fddd8b07fb45089a11851b4f97377cb740e0aade147c020000008a47304402203b75e3b8b05bdcaba1c2fa2f64c2b98b7e128fc2c11e160fe354870e52404a3902201cec2031b23acc2b9df18544af8334a05306853d081b3c14ef9ebb373886d9c80141049bd945451cb4e4b5e0c93fd69b34ec9fc0cded94b13ca5d4b3674a1b01e44660c64e5e01195253dfd9648ce9e8fcca91ad20a036a0ec75b4006355e00813b03dffffffff0130390a00000000001976a9142bb82f7eaf5942e6bf3a826bb8a285946d9ad5ca88ac00000000';

try {
    $Blockchain->Push->TX($tx);
} catch (Exception $e) {
    // Something went wrong
    echo $e->getMessage() . '<br />';
}

// A malformed transaction
$tx = 'NotValid';

try {
    $Blockchain->Push->TX($tx);
} catch (Exception $e) {
    // Something went wrong
    echo $e->getMessage() . '<br />';
}


// Output log of activity
var_dump($Blockchain->log);

?></pre>