<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
session_unset();
echo '<script type="text/javascript">' . "\n";
echo 'window.location="location:index.php";';
echo '</script>'; 