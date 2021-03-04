<?php

function pagging_admin_panel($newp,$pnums,$url){ ?>
	<div class="dataTables_paginate paging_simple_numbers">
		<ul class="pagination">
			<?php
			if ($newp>1){ ?>
				<li class="paginate_button previous">
					<a href="<?="index.php?page=$url&p=".($newp-1);?>">Previous</a>
				</li> <?php 
			}
			for ($i=1; $i<=$pnums; $i++){ 
				if ($i!=$newp){ ?>
					<li class="paginate_button ">
						<a href="<?="index.php?page=$url&p=$i";?>"><?php print_r("$i");?></a>
					</li>
					<?php 
				}
				else{ ?> 
					<li class="paginate_button active">
						<a href="javascript:void(0)"><?php print_r("$i")?></a>
					</li> <?php 
				}
			} 
			if ($newp<$pnums){ ?>
			   <li class="paginate_button next">
					<a href="<?="index.php?page=$url&p=".($newp+1);?>">Next</a>
			   </li> <?php 
			} ?>
		</ul>
	</div> <?php 
}


function pagging_initation_last_five_admin($newp,$lpnums,$show_tab,$val){
	if($lpnums < $show_tab){
		$show_tab = $lpnums;
	}

	$tot = $newp+($show_tab-1);
	$newpp = $newp;
	if($tot > $lpnums){
		$tot = $lpnums;
		$newp = $lpnums-($show_tab-1);
	}
	?>
	<div class="dataTables_paginate paging_simple_numbers">
		<ul class="pagination"> <?PHP
			if($newpp > 1){ ?>
				<li class="paginate_button">
					<a title="First" href="<?="index.php?page=$val&p=1"?>">&laquo;</a>
				</li> <?php  
			}
			
			if($newpp > 1){ ?> 
				<li class="paginate_button previous">
					<a title="Previos" href="<?="index.php?page=$val&p=".($newpp-1);?>">Previous</a>
				</li> <?php 
			}
			for($i = $newp; $i <= $tot; $i++){ 
				if ($i!=$newpp){ ?> 
					<li class="paginate_button ">
						<a href="<?="index.php?page=$val&p=$i";?>"><?php print_r("$i");?></a> 
					</li> <?php 
				}
				else{ ?><li class="paginate_button active">
				<a href="javascript:void(0)"><?php print_r("$i"); ?></a></li><?php }
			} 
			if($newpp < $lpnums){ ?> 
				<li class="paginate_button next">
					<a title="Next" href="<?="index.php?page=$val&p=".($newpp+1);?>">Next</a>
				</li> <?php 
			} 
			if($newpp < $lpnums){ ?>
				<li class="paginate_button">
					<a title="Last" href="<?="index.php?page=$val&p=$lpnums"?>"> &raquo;</a>
				</li> <?php  
			} ?>
		</ul>
	</div> <?php 
}



function admin_main_menu_name($val)
{
	$sqli = "Select * from admin_menu where menu_file = '$val'";
	$querys = query_execute_sqli($sqli);
	while($rows = mysqli_fetch_array($querys))
	{
		$main_menu = $rows['menu'];
	}
	return $main_menu;
}
?>