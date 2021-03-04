<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
$user_id = $_SESSION['mlmproject_user_id'];
$title = 'Logout';
	$message = 'Logout Member';
	data_logs($user_id,$title,$message,0);
session_unset();
echo '<script type="text/javascript">' . "\n";
echo 'window.location="index.php";';
echo '</script>'; 