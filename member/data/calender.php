<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");

$_SESSION['s_date'] = $_REQUEST['s_date'];
$_SESSION['e_date'] = $_REQUEST['e_date'];
$_SESSION['cal_amount'] = $_REQUEST['amount'];
?>
<!--<h1 align="left">Request Status</h1>-->

<div id="calback">
	<div id="calendar"></div>
</div>
<script type="text/javascript">navigate('','','');</script> 
