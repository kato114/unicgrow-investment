<?php
include('../../security_web_validation.php');
?>
<?php
$file=$_GET['val'];

if($file != logout)
{?>
<iframe  id="Iframe1"  frameborder="0"  vspace="0"  hspace="0"  marginwidth="0"  marginheight="0" width="100%"  scrolling="yes"  height="100%"  src="<?php print $file; ?>.php">
</iframe>
<?php  }  
else
{
	session_start();
	include("config.php");
	include("condition.php");
	session_destroy();
	print  "<center><font style=\"color:#656781; font-size:16px; \"> <b>You are Successfully logged out ! <A href=index.php>Click</a></b></font></center>";
}
?>
