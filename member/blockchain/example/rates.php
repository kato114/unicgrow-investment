<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <style type="text/css">
        td {
            text-align: right;
        }
        td:first-child {
            text-align: left;
        }
    </style>
</head>
<body>
<pre><?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

$api_code = null;
if(file_exists('code.txt')) {
    $api_code = trim(file_get_contents('code.txt'));
}

$Blockchain = new \Blockchain\Blockchain($api_code);

// Convert a fiat amount to BTC
$amount = $Blockchain->Rates->toBTC(500, 'USD');

?>
<p>500 USD will purchase <?php echo $amount; ?> BTC.</p>
<?php

// Get Exchanges Rates
$rates = $Blockchain->Rates->get();

?>
<table>
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>15 m</th>
            <th>Last</th>
            <th>Buy</th>
            <th>Sell</th>
        </tr>
    </thead>
    <tbody>
<?php

foreach ($rates as $cur => $ticker) {
    ?>
        <tr>
            <td><strong><?php echo $cur; ?> (<?php echo $ticker->symbol; ?>)</strong></td>
            <td><?php echo $ticker->m15; ?></td>
            <td><?php echo $ticker->last; ?></td>
            <td><?php echo $ticker->buy; ?></td>
            <td><?php echo $ticker->sell; ?></td>
        </tr>
    <?php
}

?>
    </tbody>
</table>
<?php

var_dump($rates);

// Output log of activity
var_dump($Blockchain->log);

?></pre>
</body>
</html>