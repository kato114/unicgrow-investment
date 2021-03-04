<?php
$parent_menu = $menu = submenu(0);
$menu_count = count($menu);
for($i = 0; $i < $menu_count; $i++){
	$sub_file = $menu[$i][1];
	$sub_menu[$i] = submenu($sub_file);
	for($j=0; $j < count($sub_menu[$i]); $j++){
		$second_sub_menu[$i][$j] = submenu($sub_menu[$i][$j][1]);
	}
	
}

function submenu($parent_menu){
	$sql = "SELECT * FROM menu WHERE parent_menu = '$parent_menu' AND mode = 0";
	$q = query_execute_sqli($sql);
	$i=0;
	$sub_menu = array();
	while($row = mysqli_fetch_array($q)){
		$j=0;
		$sub_menu[$i][$j] = $row['menu'];
		$j++;
		$sub_menu[$i][$j] = $row['menu_file'];
		$j++;
		$sub_menu[$i][$j] = $row['id'];
		$i++;
	} 
	return $sub_menu;	
}

function get_mainmenu($page){
	$sql = "SELECT menu FROM menu WHERE menu_file = '$page' AND mode = 0";
	$file = mysqli_fetch_array(query_execute_sqli($sql))[0];
	return $file;
}

function get_submenu($page){
	$sql = "SELECT menu FROM menu WHERE menu_file = (Select parent_menu from menu WHERE menu_file = '$page')";
	$menu_title = mysqli_fetch_array(query_execute_sqli($sql))[0];
	return $menu_title;
}
function get_sec_submenu($page){
	$sql = "SELECT menu FROM menu WHERE menu_file = (SELECT parent_menu FROM menu WHERE menu_file = 
	(SELECT parent_menu FROM menu WHERE menu_file='$page'))";
	$menu_title = mysqli_fetch_array(query_execute_sqli($sql))[0];
	return $menu_title;
}


$icon[] = "home";
$icon[] = "dashboard";
$icon[] = "user";
$icon[] = "sitemap";
$icon[] = "download";
$icon[] = "usd";
$icon[] = "money";
$icon[] = "usd";
$icon[] = "support";
$icon[] = "support";
$icon[] = "sign-out";

$icon[] = "users";
$icon[] = "tags";
$icon[] = "btc";
$icon[] = "money";
$icon[] = "ticket ";
$icon[] = "ticket";
$icon[] = "bullhorn";
$icon[] = "envelope";
$icon[] = "power-off";
?>