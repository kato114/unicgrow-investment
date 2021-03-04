Push Transaction Documentation
==============================

The `pushtx` funcionality allows the API user to broadcast raw transactions directly to the Bitcoin network.

A [web interface](https://blockchain.info/pushtx) is available as well.

Push->TX
--------
Broadcast a hex-encoded transaction to the Bitcoin network. Returns `true` on success, raises exception on failures such as malformed transactions.

```php
$Blockchain = new \Blockchain\Blockchain($api_code);

$tx = "RawHexCodeHere";

try {
    $Blockchain->Push->TX($tx);
} catch (Blockchain_Error $e) {
    // Something went wrong
    echo $e->getMessage() . '<br />';
}
```