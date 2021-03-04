<?php
function display($pos,$page,$img,$user_name,$name,$parent_u_name,$parent_full_name,$mode,$position,$date,$gender,$login_id,$total_child,$admin_view)
{
	for($i = 0; $i < 15; $i++){
		if($pos[$i] == '' && $pos[$i] == 0) { $img[$i] = "c"; }
	} 
	if($pos[0] != '')
	{
		include("../function/all_child.php");
		include("../function/total_info_display.php");
		$childrens = give_all_children($pos[0]);
		//$total_left = count($childrens[0]);
		//$total_right = count($childrens[1]);
		$left_info = get_total_paid_unpaid_members($childrens[0]);
		$right_info = get_total_paid_unpaid_members($childrens[1]);
	}
?>

<style>
  /* HOVER STYLES */
  div#pop-up0, #pop-up1, #pop-up2, #pop-up3, #pop-up4, #pop-up5, #pop-up6, #pop-up7, #pop-up8, #pop-up9, #pop-up10, #pop-up11, #pop-up12, #pop-up13, #pop-up14 {
	display: none;
	position:absolute;
	width:360px;
	padding:0;
	background: #FFF;
	border: 1px solid #ffffff;
	z-index:999;
  }
  
</style>
<script type="text/javascript">
  $(function() {
	var moveLeft = -450;
	var moveDown = -450;
	<?php for($tr = 0; $tr < 15; $tr++)
	{ ?>
	$('a#trigger<?=$tr; ?>').hover(function(e) {
	  $('div#pop-up<?=$tr; ?>').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up<?=$tr; ?>').hide();
	});
	
	$('a#trigger<?=$tr; ?>').mousemove(function(e) {
	  $('div#pop-up<?=$tr; ?>').css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
	});
	
	<?php } ?>
  });
</script>

<?php
for($tcd = 0; $tcd < 15; $tcd++)
{
	if($pos[$tcd] != 0) { ?>									
	<div id="pop-up<?=$tcd; ?>">	
		<table class="table table-bordered">
			<tr><th colspan="4">Date Of Joining : <?=$date[$tcd]?></th></tr>
			<tr><th>Distributor ID </th>	<td><?=$user_name[$tcd]; ?></td></tr>
			<tr><th>Distributor Name</th>	<td colspan="3"><?=$name[$tcd]; ?></td></tr>
			<tr><th>Sponsor ID </th>		<td colspan="3"><?=$parent_u_name[$tcd]; ?></td></tr>
			<tr><th>Sponsor Name</th>		<td colspan="3"><?=$parent_full_name[$tcd]; ?></td></tr>
			<tr>
				<td>Total Left ID</td>
				<td><?=$total_child[$tcd][0][0]+$total_child[$tcd][0][1];?></td>
				<td>Total Right ID </td>
				<td><?=$total_child[$tcd][1][0]+$total_child[$tcd][1][1];?></td>
			</tr>
			<!--<tr><th colspan="4">SelfTopUp : 10000</th></tr>
			<tr>
				<td>Total Left TopUpAmount </td>
				<td>20000</td>
				<td>Total Right TopUpAmount</td>
				<td>20000</td>
			</tr>
			<tr>
				<td>Total Left Alpha TopUpAmount </td>
				<td>10000.00</td>
				<td>Total Right Alpha TopUpAmount</td>
				<td>10000.00</td>
			</tr>
			<tr>
				<td>Total Left Beta TopUpAmount </td>
				<td>10000.00</td>
				<td>Total Right Beta TopUpAmount</td>
				<td>10000.00</td>
			</tr>-->
        </table>
	</div> <?php 
	} 
} ?>	

<table class="table table-bordered">
	<thead>
	<tr>
		<th class="text-center">Free Member</th>
		<th class="text-center">Paid Member</th>
		<th class="text-center">Blank Position </th>
		<th class="text-center">Block Member </th>
	</tr>
	</thead>
	<tr class="text-center">
		<td><img src="images/f.png" width="50" height="50" /></td>
		<td><img src="images/p.png" width="50" height="50" /></td>
		<td><img src="images/c.png" width="50" height="50" /></td>
		<td><img src="images/b.png" width="50" height="50" /></td>
	</tr>
</table>
<table class="table table-bordered">
	<thead>
	<tr>
		<th>Tree Count</th>
		<th class="text-center">Left</th>
		<th class="text-center">Right </th>
		<th class="text-center">Total</th>
	</tr>
	</thead>
	<tr>
		<th>Total Paid Members</th>
		<td class="text-center"><?=$left_info[0]?></td>
		<td class="text-center"><?=$right_info[0]?></td>
		<td class="text-center"><?=$tp =$left_info[0]+$right_info[0]?></td>
	</tr>
	<tr>
		<th>Total Unpaid Members</th>
		<td class="text-center"><?=$left_info[1]?></td>
		<td class="text-center"><?=$right_info[1]?></td>
		<td class="text-center"><?=$tp =$left_info[1]+$right_info[1]?></td>
	</tr>
	<tr>
		<th>Total Investment</th>
		<td class="text-center"><?=$left_info[2]?></td>
		<td class="text-center"><?=$right_info[2]?></td>
		<td class="text-center"><?=$tp =$left_info[2]+$right_info[2]?></td>
	</tr>
</table>	

<table class="table">
	<tr>
		<td colspan="8" class="text-center">
			<form name="tree_v" action="index.php?page=tree_view" method="post">
				<input type="hidden" name="id" value="<?=$pos[0]; ?>"  />
				<a id="trigger0">
					<input type="submit" name="tree" style="background:url(images/<?=$img[0]; ?>.png) no-repeat; height:52px; width:60px; border:none" value=""/><br /><B><?=$user_name[0];?></B>
				</a>
			</form>
		</td>
	</tr>
	<tr><td colspan="8" class="text-center"><img src="images/band1.gif" width="550"/></td></tr>
	<tr>
		<td colspan="4">
			<?=bord_position_chk($pos[1], $user_name[1] , $img[1] ,0 ,1,$login_id,1,$all_placement);?>
		</td>
		<td colspan="4">
			<?=bord_position_chk($pos[2], $user_name[2] , $img[2] ,1 ,2,$login_id,1,$all_placement);?>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="text-center"><img src="images/band2.gif" /></td>
		<td colspan="4" class="text-center"><img src="images/band2.gif" /></td>
	</tr>
	<tr>
		<td colspan="2">
			<?=bord_position_chk($pos[3], $user_name[3] , $img[3] ,0 ,3,$login_id,2,$all_placement);?>
		</td>
		<td colspan="2">
			<?=bord_position_chk($pos[4], $user_name[4] , $img[4] ,1 ,4,$login_id,2,$all_placement);?>
		</td>
		<td colspan="2">
			<?=bord_position_chk($pos[5], $user_name[5] , $img[5] ,0 ,5,$login_id,3,$all_placement);?>
		</td>
		<td colspan="2">
			<?=bord_position_chk($pos[6], $user_name[6] , $img[6] ,1 ,6,$login_id,3,$all_placement);?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="text-center"><img src="images/band4.gif" /></td>
		<td colspan="2" class="text-center"><img src="images/band4.gif" /></td>
		<td colspan="2" class="text-center"><img src="images/band4.gif" /></td>
		<td colspan="2" class="text-center"><img src="images/band4.gif" /></td>
	</tr>
	<tr>
		<td><?=bord_position_chk($pos[7], $user_name[7] , $img[7] ,0 ,7,$login_id,4,$all_placement);?></td>
		<td><?=bord_position_chk($pos[8], $user_name[8] , $img[8] ,1 ,8,$login_id,4,$all_placement);?></td>
		<td><?=bord_position_chk($pos[9], $user_name[9] , $img[9] ,0 ,9,$login_id,5,$all_placement);?></td>
		<td><?=bord_position_chk($pos[10], $user_name[10] , $img[10] ,1 ,10,$login_id,5,$all_placement);?></td>
		<td><?=bord_position_chk($pos[11], $user_name[11] , $img[11] ,0 ,11,$login_id,6,$all_placement);?></td>
		<td><?=bord_position_chk($pos[12], $user_name[12] , $img[12] ,1 ,12,$login_id,6,$all_placement);?></td>
		<td><?=bord_position_chk($pos[13], $user_name[13] , $img[13] ,0 ,13,$login_id,7,$all_placement);?></td>
		<td><?=bord_position_chk($pos[14], $user_name[14] , $img[14] ,1 ,14,$login_id,7,$all_placement);?></td>
	</tr>
</table>
	
<?php } 


function bord_position_chk($pos, $user_name , $img , $postion , $trig , $login_id,$pair_pos,$all_pos)
{
	$ext_reg = $trig - ($pair_pos+$postion);
	$form_top = $placement_username = $form_bottom = "";
	if(array_key_exists($ext_reg,$all_pos)){
		$form_top = "<form name='tree_v' action='register.php' method='post' target='_blank'>";
		$form_bottom = "</form>";
		$placement_username = $all_pos[$ext_reg];
	}
	if($pos == 0)
	{
		$pos_blank = "
		<div class='text-center'>
			$form_top
			<input type='hidden' name='id' value='$pos'  />
			<input type='hidden' name='ref' value='$login_id'  />
			<input type='hidden' name='placeref' value='$placement_username'  />
			<input type='hidden' name='reg_pos_user' value='$postion'  />
			<input type='submit' name='tree' style='background:url(images/$img.png) no-repeat; height:60px; width:60px; border:none' value=''/><br />
			<B>$user_name</B>
			$form_bottom 
		</div>";
	}
	else
	{
		$pos_blank = "
		<div class='text-center'>
			<form name='tree_v' action='index.php?page=tree_view' method='post'>
				<input type='hidden' name='id' value='$pos'  />
				<a id='trigger$trig' style='color:#333'>
					<input type='submit' name='tree' style='background:url(images/$img.png) no-repeat; height:60px; width:60px; border:none' value=''/><br />
					<B>$user_name</B>
				</a>
			</form>
		</div>";
	}	
	return $pos_blank;
}
?>