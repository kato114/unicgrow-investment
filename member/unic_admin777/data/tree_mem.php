<?php
include('../../security_web_validation.php');
?>
<?php
error_reporting(0);
session_start();	
$login_id = 1;
?>
<p>Tree Have To Open On Another Window !!</p>
<form action="network_members.php" method="post" id="act_form" target="_blank">
	<input type="hidden" name="submit1" value="Activate" />
</form>
<script>document.getElementById("act_form").submit();</script>
