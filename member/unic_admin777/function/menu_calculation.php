<?php
$parent_menu = $menu = submenu(0);
$menu_count = count($menu);
for($i = 0; $i < $menu_count; $i++)
{
	$sub_file = $menu[$i][1];
	$sub_menu[$i] = submenu($sub_file);
	for($j=0; $j < count($sub_menu[$i]); $j++)
	{
		$second_sub_menu[$i][$j] = submenu($sub_menu[$i][$j][1]);
	}
	
}

function submenu($parent_menu)
{
	$q = query_execute_sqli("select * from admin_menu where parent_menu = '$parent_menu'");
	$i=0;
	while($row = mysqli_fetch_array($q))
	{
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


function get_mainmenu($page)
{
	$q = query_execute_sqli("select * from admin_menu where menu_file = '$page'");
	while($row = mysqli_fetch_array($q))
	{
		$file = $row['menu'];
	}
	return $file;
}

function get_submenu($page)
{
	$sql = "Select * from admin_menu where menu_file = (Select parent_menu from admin_menu where menu_file = '$page')";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{
		$menu_title = $row['menu'];
	}
	return $menu_title;
}
function get_sec_submenu($page)
{
	$sql = "Select * from admin_menu where menu_file = (Select parent_menu from admin_menu where menu_file = (Select parent_menu from admin_menu where menu_file='$page'))";
	$query = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($query))
	{
		$menu_title = $row['menu'];
	}
	return $menu_title;
}

function tab_menu_calculation($tab)
{
	$q = query_execute_sqli("select * from admin_menu where parent_menu = '$tab'");
	$i=0;
	while($row = mysqli_fetch_array($q))
	{
		$j=0;
		$t[$i][$j] = $row['menu'];
		$j++;
		$t[$i][$j] = $row['menu_file'];
		$j++;
		$t[$i][$j] = $row['id'];
		$i++;
	} 
	return $t;	
}

$icon[] = "fa fa-dashboard";
$icon[] = "fa fa-user";
$icon[] = "fa fa-tty";
$icon[] = "fa fa-money";
$icon[] = "fa fa-dollar";
$icon[] = "fa fa-trophy";
$icon[] = "fa fa-paw";
$icon[] = "fa fa-key";
$icon[] = "fa fa-envelope";
$icon[] = "fa fa-bullhorn";
$icon[] = "fa fa-gears";
$icon[] = "fa fa-money";
$icon[] = "fa fa-folder-open-o";
$icon[] = "fa fa-bars";
$icon[] = "fa fa-download";
?>