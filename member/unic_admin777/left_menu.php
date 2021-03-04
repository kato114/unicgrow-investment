<?php
include("function/menu_calculation.php");
$val = $_REQUEST['page'];
$global_total_menu = get_total_menufor_admin($parent_menu);
$total_main_menu = $menu_count;
$j = count($menu);
for($i = 0; $i < $j; $i++){ 
	$cnt = count($sub_menu[$i]); 
	if($cnt >0){ 
		$main_menu = get_mainmenu($val);
		$sub_menu_tit =  get_submenu($val);
		$sec_sub_menu_tit =  get_sec_submenu($val);
		$sec_menu_tit =  get_submenu($val);
		
		if($sub_menu_tit == $menu[$i][0]){ $class = "active"; $col1= "collapse in"; }
		else{$class = ""; $col1 = '';}
		
		if($sec_sub_menu_tit == $menu[$i][0]){ $col1= "collapse in"; $area = "aria-expanded='true'"; }
		else{ $col1 = ''; $area = ''; }
		if(!get_active_menu($_SESSION['intrade_admin_id'],$menu[$i][2])){
			continue;
		} ?>
		<li class="<?= $class;?>">
			<a href="#" >
				<i class="<?=$icon[$i]?>"></i>
				<span class="nav-label"><?=$menu[$i][0]?></span> <span class="fa arrow"></span>
			</a> 
			<ul class="nav nav-second-level <?=$col1?> " <?=$area;?>> <?php
				for($k=0;$k<$cnt; $k++){ 
					$count_sec = count($second_sub_menu[$i][$k]);
					
					if($main_menu == $sub_menu[$i][$k][0]){ $sub_class = "active";}
					else{$sub_class = "";}
					
					if($count_sec > 0){
						if($sec_menu_tit == $sub_menu[$i][$k][0] )
						{ $act_class = "active"; $cols = "collapse in";}
						else{$act_class = ""; $cols = "";}
						$page_id = get_page_menuId($sub_menu[$i][$k][1]);
						if(!get_active_menu($_SESSION['intrade_admin_id'],$page_id,2)) continue; ?>
						<li class="<?= $act_class;?>">
							<a href="index.php?page=<?=$sub_menu[$i][$k][1];?>">
								<span class="fa arrow"></span> <?=$sub_menu[$i][$k][0];?>
							</a> <?php
							if(count($second_sub_menu[$i][$k]) > 0){ ?>
								<ul class="nav nav-third-level <?=$cols;?>" <?=$area;?>> <?php
								for($m = 0; $m < $count_sec; $m++){	
									if($val == $second_sub_menu[$i][$k][$m][1])
									{$sec_class = $act_class;}
									else
									{$sec_class = "";}
									$page_id = get_page_menuId($second_sub_menu[$i][$k][$m][1]);
									if(!in_array($page_id,$members_menuIds) and $second_sub_menu[$i][$k][$m][1]!=""){
										continue;
									} ?>
									<li class=<?=$sec_class;?>>
										<a href="index.php?page=<?=$second_sub_menu[$i][$k][$m][1];?>">
											<?=$second_sub_menu[$i][$k][$m][0];?>
										</a>
									</li> <?php
								} ?>	
								</ul> <?php
							} ?>
						</li> <?php 		
					}
					else{
						$page_id = get_page_menuId($sub_menu[$i][$k][1]);
						if(!in_array($page_id,$members_menuIds) and $sub_menu[$i][$k][1] !=""){
							continue;
						} ?>
						<li class="<?= $sub_class;?>">
							<a href="index.php?page=<?=$sub_menu[$i][$k][1];?>">
								<?=$sub_menu[$i][$k][0];?>
							</a>
						</li> <?php
					}
				}  ?>
			</ul>
		</li> <?php 	
	}
	else{
		$page_id = get_page_menuId($menu[$i][1]);
		if(!in_array($page_id,$members_menuIds) and $menu[$i][1] !=""){ continue; } ?>
		<li>
			<a href="index.php?page=<?=$menu[$i][1];?>" >
				<i class="<?=$icon[$i]; ?>"></i> <span class="nav-label"><?=$menu[$i][0];?></span>
			</a>
		</li> <?php 
	}   
} 
?>