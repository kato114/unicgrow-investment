<?php
function side_bar($menu,$val2)
{
	$main = count($menu);
	for($i = 0; $i < $main; $i++)
	{
		 ?><li><a class="headitem item<?php  print $i+1; ?>" style="background:url(icons/<?php print $menu[$i][0][2]; ?>) no-repeat 15px center;" href="#"><?php echo $menu[$i][0][0]; ?></a> <?php if($menu[$i][0][3] == $val2) { ?> <ul class="opened"> <?php } else { ?>
		<ul> <?php } ?><!-- ul items without this class get hiddden by jquery--> 
		<?php $sub = count($menu[$i]);
		for($j = 1; $j < $sub; $j++)
		{ ?>
			 <li><a href="index.php?val=<?php echo $menu[$i][$j][1];?>&open=<?php echo $menu[$i][0][3]; ?>"><?php echo $menu[$i][$j][0]; ?> </a></li>        
		 <?php  } ?>        
		 </ul>
		 </li> 
 <?php  }
}  
 
 
function tab_menu_content($tab_menu,$val)
{
	$sub = count($tab_menu[$val]);
		for($j = 1; $j < $sub; $j++)
		{  ?>
			<div class="jquery_tab">
			<div class="content_block">
				<h2 class="jquery_tab_title"><?php echo $tab_menu[$val][$j]; ?></h2>
				<?php
				 $file = $tab_menu[$val][0].".php";
				 include("data/$file"); ?> 
			</div><!--end content_block-->
			</div><!-- end jquery_tab -->	
<?php 	}
} ?>
 