Receive Documentation
=====================

The simplest way to receive Bitcoin payments. Blockchain forwards all incoming Bitcoin to the address you specify.

Be sure to check out the [official documentation](https://blockchain.info/api/api_receive) for information on callback URLs.

Usage
-----

Generate
--------

Call `ReceiveV2->generate` on a `Blockchain` object. Pass a v2 API key, xpub and callback URL. Returns a `ReceiveResponse` object.

```php
$blockchain = new \Blockchain\Blockchain($apiKey);

$v2ApiKey = 'myApiKey';
$xpub = 'xpubYourXPub';
$callbackUrl = 'http://example.com/transaction?secret=mySecret';
$gap_limit = 5 // optional - how many unused addresses are allowed before erroring out

$response = $blockchain->ReceiveV2->generate($v2ApiKey, $xPub, $callbackUrl, $gap_limit);

// Show receive address to user:
echo "Send coins to " . $response->getReceiveAddress();
```

Callback Logs
-------------

To view the callback logs call `ReceiveV2->callbackLogs` on a `Blockchain` object. Pass an API key and callback URL. Returns an array of `CallbackLogEntry` objects.

```php
$blockchain = new \Blockchain\Blockchain($apiKey);

$v2ApiKey = 'myApiKey';
$callbackUrl = 'http://example.com/transaction?secret=mySecret';

$logs = $blockchain->ReceiveV2->callbackLogs($apiKey, $callbackUrl);

foreach ($logs as $log) {
    $log->getCallback();
    $log->getCalledAt(); // DateTime instance
    $log->getResponseCode();
    $log->getResponse();
}
```

Check Address Gap
-----------------

To check the index gap between the last address paid to and the last address generated call `ReceiveV2->checkAddressGap` on a `Blockchain` object. Returns an `int`.

```php
$gap_int = $blockchain->ReceiveV2->checkAddressGap($apiKey, $xpub);
```


Response Object Properties
--------------------------

### ReceiveResponse

```php
class ReceiveResponse {
    private $address;            // string
    private $index;              // int
    private $callback;           // string

    public function getReceiveAddress();
    public function getIndex();
    public function getCallback();
}
```

### CallbackLogEntry

```php
class CallbackLogEntry {
    private $callback;          // string
    private $calledAt;          // DateTime
    private $rawResponse;       // string
    private $responseCode;      // int

    public function getCallback();
    public function getCalledAt();
    public function getResponse();
    public function getResponseCode();
}
```