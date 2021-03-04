<?php
ini_set('display_errors','off');
$val = isset($_REQUEST['page'])?$_REQUEST['page']:"";
$str=clean($val);
function clean($string)
{
   $string = str_replace('', '_', $string); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
   
}
			
$file = $val.".php";
if ($val == '')
{
	include("data/welcome.php");
}
else
{
include("data/".$file);

}
?>