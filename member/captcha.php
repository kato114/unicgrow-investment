<?php
session_start();
$code=rand(10000,99999);
$_SESSION["code"] = $code;
$font = 'assets/fonts/arial.ttf';
$im = imagecreatetruecolor(75, 40);// font size , hegith
$bg = imagecolorallocate($im, 100, 175, 53); //background color blue
$fg = imagecolorallocate($im, 255, 255, 255);//text color white
imagefill($im, 0, 0, $bg);
imagestring($im, 5, 15, 12, $code, $fg);
//imagettftext($im, 15, 3, 10, 29, $fg, $font, $code);
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
?>