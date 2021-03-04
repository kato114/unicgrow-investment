<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
$id = $_SESSION['mlmproject_user_id'];
$query = query_execute_sqli("select * from users where id_user = '$id' ");
while($row = mysqli_fetch_array($query))
{
	$name = $row['f_name']." ".$row['l_name'];
} 
?>