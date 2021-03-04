<?php
error_reporting(0);
session_start();
include("../config.php");
include("condition.php");
session_unset();
include "../free_up_memory.php";
?>
<script>window.location="index.php";</script>