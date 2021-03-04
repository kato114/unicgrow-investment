<?php
ini_set("display_errors","on");
//include("config.php");

$fb_app_id="835044469959675";
$xquise_starling_root = "http://www.exclusivesterling.com/api/";
$api_key = "QpKxgVjGBT5DyJO1c09WfICAzqPv2sSh6Yr4td7u";
$curency_type = "USD";	


$db_host = "localhost";
$db_username = "mahendra";
$db_password = "mahendra123";
$db = "blockchainmax_business";
$con=mysqli_connect($db_host,$db_username,$db_password);
mysqli_select_db($db,$con);

$sqlk = "SELECT * FROM users";
$query = query_execute_sqli($sqlk);
$num = mysqli_num_rows($query);

while($row = mysqli_fetch_array($query))
{
	$user_id = $row['id_user'];
	
	if($bitcoinadd = bitcoinaddress())
	{
		$bitaddress = explode("/",bitcoinadd);
		$address = $bitaddress[0];
		$reqid = $bitaddress[1];
		
	
		$sqls = "UPDATE USERS SET branch = '$address' WHERE id_user = '$user_id'";
		query_execute_sqli($sqls);
	}
}
?>