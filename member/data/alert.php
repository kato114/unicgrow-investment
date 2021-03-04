<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
include("function/message.php");


?>

		
<?php
$id = $_SESSION['mlmproject_user_id'];
$message = message_alert($id);
print $message;
 ?>
