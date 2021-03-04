<?php
session_start();
//include("config.php");
// IF LOGIN TRUE
//PUT LOGIN CONDITION HERE 

$message = "To create the full embossed effect you can combine both of the examples placing a dark shadow top and left and a light one right and bottom. The good news is that the text shadow property allows you to specify multiple shadows.
I always use RGBa when I define a shadow this means don’t have to worry about the background colors that the shadow may fall on.
So my embossed effect works like this
text-shadow:0px -1px 0px rgba(0,0,0, .4), 1px 1px 2px rgba(255,255,255, 0.6);
This can be overkill on small text, but it looks great on headings.";
if($_SESSION['intrade_admin_login'] != 1)
{
	echo '<script type="text/javascript">' . "\n";
	echo 'window.location="login.php";';
	echo '</script>';
}


?>