<?php

$name = $_REQUEST['name'];
$date = $_REQUEST['date'];
$left_child = $_REQUEST['left'];
$right_child = $_REQUEST['right'];
$mode = $_REQUEST['mode'];
$position = $_REQUEST['position'];
$user_name = $_REQUEST['user_name'];
$gender = $_REQUEST['gender'];
?>
<table hspace=0 vspace=0 cellspacing=0 cellpadding=0 border=0>
<tr><td width=100><span style="color:#990000">Name</span></td><td width=100><span style="color:#009900"><?php print $name; ?></span></td><br>
<tr><td width=100><span style="color:#990000">Left Position</span></td><td width=100><span style="color:#009900"><?php print $left_child; ?><br>
<tr><td width=100><span style="color:#990000">Right Position</span></td><td width=100><span style="color:#009900"><?php print $right_child; ?><br>
<tr><td width=100><span style="color:#990000">Leg</span></td><td width=100><span style="color:#009900"><?php print $position; ?><br>
<tr><td width=100><span style="color:#990000">Mode</span></td><td width=100><span style="color:#009900"><?php print $mode; ?><br>
<tr><td width=100><span style="color:#990000">Gender</span></td><td width=100><span style="color:#009900"><?php print $gender; ?><br>
<tr><td width=100><span style="color:#990000">Date</span></td><td width=100><span style="color:#009900"><?php print $date; ?><br>

</table>