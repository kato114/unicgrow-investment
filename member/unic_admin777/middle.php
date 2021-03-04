<?php
session_start();
$val = $_REQUEST['page'];
$breadcrumb = get_breadcrumb_tit($val);
$query = query_execute_sqli("SELECT * FROM admin");
while($row = mysqli_fetch_array($query))
{
	$id_user = $row['id_user'];
	$username = $row['username'];
}

$sql = "Select * from admin_menu where menu_file = (Select parent_menu from admin_menu where menu_file = '$val') limit 1";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query))
{
	$menu = $row['menu'];
}
if($val == ''){$menu = 'Dashboard';}

$sub_menu = get_submenu_tit($val);
?>
<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-lg-10">
		<h2><?=$sub_menu?></h2>
		<ol class="breadcrumb">
			<li><a href="index.php">Home</a></li>
			<?php
			for($k = 0; $k < count($breadcrumb); $k++){
				$bdcmb_class = "";
				if($k == count($breadcrumb)-1){
					$bdcmb_class = 'class="active"';
				}
				?>
				<li <?=$bdcmb_class?>><a><?=$breadcrumb[$k];?></a></li>
				<?php
			}
			?>
		</ol>
	</div>
	<div class="col-lg-2"></div>
</div>
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox-content">	
				<div class="table-responsive">	
					<?php
					$file = $val.".php";
					if ($val == '')
					include("data/projects_summary.php");
					else
					include("data/".$file);
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
function get_submenu_tit($val){
	$sql = "SELECT menu FROM admin_menu WHERE menu_file = '$val'";
	$query = query_execute_sqli($sql);
	$menu_title = mysqli_fetch_array($query)[0];
	if($menu_title == '')
	return "Profile";
	else
	return $menu_title;
}
function get_breadcrumb_tit($val)
{
	$i = 0;
	$menu_title = array();
	do{
		$sql = "Select * from admin_menu where menu_file = '$val' limit 1";
		$query = query_execute_sqli($sql);
		while($row = mysqli_fetch_array($query))
		{
			$menu_title[$i] = $row['menu'];
			$val = $parent_menu = $row['parent_menu'];
		}
		$i++;
		$len = strlen($parent_menu);
	}while($len > 1);
	$menu_title = array_reverse($menu_title);
	return $menu_title;
}
?>
