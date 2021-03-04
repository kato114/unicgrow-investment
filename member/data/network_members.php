<?php
include('../security_web_validation.php');
?>
<?php
error_reporting(0);
session_start();	
$login_id = $_SESSION['mlmproject_user_id'];
?>
<p><a href="network_members.php" target="_new">Click here to view Personal Network !!</a></p>
<form action="network_members.php" method="post" id="act_form" target="_blank">
	<input type="hidden" name="submit1" value="Activate" />
</form>
<script>document.getElementById("act_form").submit();</script>
