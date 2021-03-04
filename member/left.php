<?php
session_start();
include("menu_calculation.php");
$val = $_REQUEST['page'];
$login_id = $_SESSION['mlmproject_user_id'];
?>
<div class="main-menu menu-fixed menu-light menu-accordion    menu-shadow " data-scroll-to-active="true">
	<div class="main-menu-content">
		<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
			<?php
			$j = count($menu);
			for($i = 0;$i < $j; $i++)
			{ 
				$cnt = count($sub_menu[$i]); 
				if($cnt > 0)
				{ 
					$main_menu = get_mainmenu($val);
					$sub_menu_tit =  get_submenu($val);
					if($sub_menu_tit == $menu[$i][0]){ $class = "open";}else{$class = "";} ?>
					
					<li class=" nav-item <?=$class?>">
						<a href="#">
							<i class="la la-<?=$icon[$i]?>"></i>
							<span class="menu-title" data-i18n="nav.<?=$menu[$i][0];?>.main">
								<?=$menu[$i][0];?>
							</span>
							<!--<span class="badge badge badge-info badge-pill float-right mr-2">3</span>-->
						</a> 
			
						<ul class="menu-content"> <?php
						for($k = 0;$k < $cnt; $k++)
						{
							if($main_menu == $sub_menu[$i][$k][0]){ $sub_class = "active";}else{$sub_class = "";} ?>
							<li class="<?=$sub_class;?>">
								<a href="index.php?page=<?=$sub_menu[$i][$k][1];?>" class="menu-item" data-i18n="nav.<?=$menu[$i][0];?>.<?=$sub_menu[$i][$k][0];?>">
									<?=$sub_menu[$i][$k][0];?>
								</a>
							</li> <?php 	
						} ?>
						</ul>
					</li> <?php 	
				}
				else
				{
					$main_class = "";
					$main_menu = get_mainmenu($val);
					if($menu[$i][0] == $main_menu){ $main_class = "active";} else{ $main_class = ""; } ?>
					
					<li class=" nav-item <?=$main_class?>">
						<a href="<?=$menu[$i][1] == 'home' ? '../' : 'index.php?page='.$menu[$i][1];?>" <?=$menu[$i][1] == 'home' ? 'target="_blank"' : ''?> >
							<i class="la la-<?=$icon[$i]?>"></i>
							<span class="menu-title" data-i18n="nav.<?=$menu[$i][0];?>.main">
								<?=$menu[$i][0];?>
							</span>
						</a>
					</li> <?php 	
				} 
			} ?>
		</ul>
	</div>
</div>