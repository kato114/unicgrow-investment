<?php
include "config.php";
$id = 1;
do{
	$query = query_execute_sqli("select * from users where parent_id='$id' and position=0");
	$num = mysqli_num_rows($query);
	while($rrr = mysqli_fetch_array($query)){
		$id = $rrr['id_user'];
		$username = $rrr['username'];
	}
}while($num > 0);
echo "Member_id : $id &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Left User : $username<br>";
$id = 1;
do{
	$query = query_execute_sqli("select * from users where parent_id='$id' and position=1");
	$num = mysqli_num_rows($query);
	while($rrr = mysqli_fetch_array($query)){
		$id = $rrr['id_user'];
		$username = $rrr['username'];
	}
}while($num > 0);
echo "Member_id : $id &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Right User : $username";