<?php
session_start();
if($_SESSION['mlmproject_user_login'] != 1)
{
	echo '<script type="text/javascript">';
	echo 'window.location=" https://www.unicgrow.com/";';
	echo '</script>';
}

/*echo $_SERVER['HTTP_HOST'];
echo PHP_SELF;*/
if($_SERVER['HTTP_HOST'] == '192.168.0.101'){

}
elseif($_SERVER['HTTP_HOST'] != 'www.unicgrow.com')
{
	die;
}


