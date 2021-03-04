<?php
include("../config.php");

$a = query_execute_sqli("select * from admin_menu where id > 3 ");
while($r = mysqli_fetch_array($a))
{
	$menu_file = $r['menu_file'];
	$parent_menu = $r['parent_menu'];
	$menu = $r['menu'];
	query_execute_sqli("insert into fdsfd (menu , parent_menu , menu_file) values ('$menu' , '$parent_menu' , '$menu_file') ");
}	
	
	