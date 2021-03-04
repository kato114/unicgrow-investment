
<?php
error_reporting(1);
include "config.php";
include("function/blockchain_trasaction.php");

print_r(chk_txs('3PgWKh7T5WmtfYeuZ78s2cT2GxcWPauPqs',$systems_date_time,1));