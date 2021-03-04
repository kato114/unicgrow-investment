<?php

function get_main_menuId($menu_id){
	$parent_menu = "";
	$sql = "select * from admin_menu where id = '$menu_id'";
	$q = query_execute_sqli($sql);
	while($row = mysqli_fetch_array($q))
	{
		$parent_menu = $row['parent_menu'];
	}
	if(strlen($parent_menu) == 1)return $menu_id;
	$q = query_execute_sqli("select * from admin_menu where menu_file = '$parent_menu'");
	while($row = mysqli_fetch_array($q))
	{
		return $id = $row['id'];
	}
	
}
function get_members_menuId($user_id){
	$sql = "select * from privileges where user_id = '$user_id' and mode=1 order by id asc";
	$q = query_execute_sqli($sql);
	$i = 0;
	while($row = mysqli_fetch_array($q))
	{
		$members_menuId[$i][0] = $row['menu_id'];
		$members_menuId[$i][1] = $row['main_menuId'];
		$i++;
	}
	return $members_menuId;
}
function get_page_menuId($page){
	$q = query_execute_sqli("select * from admin_menu where menu_file = '$page'");
	while($row = mysqli_fetch_array($q))
	{
		$id = $row['id'];
	}
	return $id;
}
function get_total_menufor_admin($parent_menu){
	 $total_main_menu = count($parent_menu);
	//for($i = 0;$i < count($parent_menu); $i++){
		$m = 0;
		for($i=0;$i<$total_main_menu;$i++){
			$main_submenu=0;
			$main_submenu = submenu($parent_menu[$i][1]);
			$count_main_submenu = count($main_submenu);
			if($count_main_submenu == 0){
				$m++;
			}
			else{
				for($j=0;$j<$count_main_submenu;$j++){
					$tab = tab_menu_calculation($main_submenu[$j][1]);
					$total = count($tab);
					if($total == 0){
						$m++;
					}
					else{
						for($k=0;$k<$total;$k++){
							$m++;
						}
					}
				}
			}
		}
	//}
	return $m;
}
function get_active_menu($user_id,$id,$step=false){
	$sql = "select * from privileges where user_id = '$user_id' and main_menuId='$id' and mode=1";
	$q = query_execute_sqli($sql);
	$num = mysqli_num_rows($q);
	if($num > 0){
		return true;
	}
	else{
		$sql = "select * from privileges where user_id = '$user_id' and main_menuId in(SELECT id FROM `admin_menu` WHERE `parent_menu` in(SELECT menu_file FROM `admin_menu` WHERE `id`='$id')) and mode=1";
		$q = query_execute_sqli($sql);
		$num = mysqli_num_rows($q);
		if($num > 0){
			return true;
		}
	}
	return false;
}
?>

