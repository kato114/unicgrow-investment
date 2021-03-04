<?php
session_start();
ini_set('display_errors','on');
include('../config.php');
require("../web_security.php");
if(!empty($_POST))validate_all_post_from_input($_POST);
if(!empty($_GET))validate_all_post_from_input($_GET);

$username = mysqli_real_escape_string($con,$_POST['username']);
$password = mysqli_real_escape_string($con,$_POST['password']);

$sql = "select * from admin where username='".$username."' && password='".$password."' AND id_user=1";

$sql1=query_execute_sqli($sql);
$count = mysqli_num_rows($sql1);
if($count > 0)
{	
	while($row = mysqli_fetch_array($sql1))
	{
		$_SESSION['intrade_admin_id']=$row['id_user'];
	}
	$_SESSION['intrade_admin_name']=$_POST['username'];
	$_SESSION['intrade_admin_email']=$_POST['email'];
	$_SESSION['intrade_admin_login']=1;
	include "../free_up_memory.php";
	?> <script>window.location = "index.php";</script> <?php
}
else{ include "../free_up_memory.php";?> <script>window.location = "index.php?err=d";</script> <?php }

?>