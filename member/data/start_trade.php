<?php
session_start();
include('../security_web_validation.php');
include_once("function/setting.php");
include_once("function/trade_function.php");
$login_id = $_SESSION['mlmproject_user_id'];
get_live_trade();
?>



