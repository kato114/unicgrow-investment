<?php
ini_set("display_errors",'on');
session_start();

include("condition.php");
include("config.php");
$user_id = $_SESSION['mlmproject_user_id'];

$title = 'Logout';
$message = 'Logout Member';
session_unset();
include "free_up_memory.php";
echo '<script type="text/javascript">';
echo 'window.location="index.php";';
echo '</script>'; 