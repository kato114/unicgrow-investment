Statistics Documentation
========================

Provides data found on the [Blockchain stats page](https://blockchain.info/stats), representing Bitcoin network statistics for the past 24 hours.

Get Stats
---------
Get a snapshot of the network statistics. Returns a `StatsResponse` object.

```php
$Blockchain = new \Blockchain\Blockchain($api_code);

$stats = $Blockchain->Stats->get();
```

Get Chart
---------
Get chart data for a specified chart. Returns a `ChartResponse` object.

```php
/*
* Optional params:
* $timespan - interval for which to fetch data, e.g. 'all', '2y', '14d'
* $rolling_average - duration over which data should be averaged, e.g. '8hours'
*/
$chart = $Blockchain->Stats->getChart('transactions-per-second');
```

Get Pools
---------
Get an array of mining pools and the total blocks they mined in the last few days (4 days by default, max 10). Returns a `string` => `int` array.

```php
/*
* Optional param:
* $timespan (int) - number of days to get data for (default 4, maximum 10)
*/
$pools = $Blockchain->Stats->getPools(8);
```

Response Object Properties
--------------------------

### StatsReponse

```php
class StatsResponse {
    public $blocks_size;                        // int
    public $difficulty;                         // float
    public $estimated_btc_sent;                 // string - Bitcoin value
    public $estimated_transaction_volume_usd;   // float
    public $hash_rate;                          // float
    public $market_cap;                         // string
    public $market_price_usd;                   // float
    public $miners_revenue_btc;                 // int
    public $miners_revenue_usd;                 // float
    public $minutes_between_blocks;             // float
    public $n_blocks_mined;                     // int
    public $n_blocks_total;                     // int
    public $n_btc_mined;                        // string - Bitcoin value
    public $n_tx;                               // int
    public $nextretarget;                       // int
    public $timestamp;                          // float, seconds.milliseconds
    public $total_btc_sent;                     // string - Bitcoin value
    public $total_fees_btc;                     // string - Bitcoin value
    public $totalbc;                            // string - Bitcoin value
    public $trade_volume_btc;                   // float
    public $trade_volume_usd;                   // float
}
```

### CarthResponse

```php
class ChartResponse {
    public $chart_name;                         // string
    public $unit;                               // string
    public $timespan;                           // string
    public $description;                        // string
    public $values = array();                   // array of ChartValue objects
}
```

### ChartValue

```php
class ChartValye {
    public $x;                                  // float
    public $y;                                  // float
}
```