<?php
function display($pos,$page,$img,$user_name,$name,$parent_u_name,$parent_full_name,$mode,$position,$date,$gender,$login_id,$total_child,$admin_view)
{
$all_placement = $user_name;
$dir_back = "";
//print_r($total_child);
if($admin_view){
	$dir_back = "../";
}

for($i = 0; $i < 15; $i++)
{
	if($pos[$i] == '' && $pos[$i] == 0) { $img[$i] = "c"; }
} 


?>
<link href="<?=$dir_back?>assets/css/tree.css" rel="stylesheet" />
<style>
/* HOVER STYLES */
div#pop-up0, #pop-up1, #pop-up2, #pop-up3, #pop-up4, #pop-up5, #pop-up6, #pop-up7, #pop-up8, #pop-up9, #pop-up10, #pop-up11, #pop-up12, #pop-up13, #pop-up14 {
	display: none;
	position:absolute;
	width:45%;
	padding:0;
	background: #FFF;
	color: #000000;
	font-size: 90%;
	z-index:9999;
}

</style>
<script type="text/javascript">
  $(function() {
	var moveLeft = -550;
	var moveDown = -300;
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
	if($pos[$tcd] != 0) 
	{ 
		$names = $name[$tcd];
		if($parent_u_name[$tcd] == ''){ $parent_u_name[$tcd] = 'No Sponsor';}
		?>									
		<div id="pop-up<?=$tcd; ?>" class="col-lg-4">	
			<div class="ibox float-e-margins">
				<div class="ibox-title"><h5>Details</h5></div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-4"><B>Date Of Joining:</B></div>
						<div class="col-sm-8"><?=date('d/M/Y', strtotime($date[$tcd]));?></div>
					
						<div class="col-sm-12">&nbsp;</div>
						
						<div class="col-sm-3"><B>Name : </B></div>
						<div class="col-sm-3"><?=$name[$tcd]?></div>
						<div class="col-sm-3"><B>Package : </B></div>
						<div class="col-sm-3"><?=$total_child[$tcd][0][4];?></div>
						<div class="col-sm-12">&nbsp;</div>
						<div class="col-sm-4"><B>Total Left Leg : </B></div>
						<div class="col-sm-2"><?=$total_child[$tcd][0][0]+$total_child[$tcd][0][1];?></div>
						
						<div class="col-sm-4"><B>Total Right Leg : </B></div>
						<div class="col-sm-2"><?=$total_child[$tcd][1][0]+$total_child[$tcd][1][1];?></div>
						<div class="col-sm-12">&nbsp;</div>
					
					
					
						<!--<div class="col-sm-4"><B>Activation Date : </B></div>
						<div class="col-sm-2"><?=$total_child[$tcd][0][5];?></div>
						<div class="col-sm-12">&nbsp;</div>
						
						<div class="col-sm-3"><B>Left Business : </B></div>
						<div class="col-sm-3"><?=$total_child[$tcd][0][2];?></div>
						
						<div class="col-sm-4"><B>Right Business : </B></div>
						<div class="col-sm-2"><?=$total_child[$tcd][1][2];?></div>
						<div class="col-sm-12">&nbsp;</div>
						
						<div class="col-sm-6"><B>Sponsor ID : </B></div>
						<div class="col-sm-6"><?=$parent_u_name[$tcd]; ?></div>
						<div class="col-sm-12">&nbsp;</div>
						
						<div class="col-sm-6"><B>Sponsor Name : </B></div>
						<div class="col-sm-6"><?=$parent_full_name[$tcd]; ?></div>
						<div class="col-sm-12">&nbsp;</div>
						
						<div class="col-sm-6"><B>Distributor ID : </B></div>
						<div class="col-sm-6"><?=$user_name[$tcd]; ?></div>
						<div class="col-sm-12">&nbsp;</div>
						
						<div class="col-sm-6"><B>Distributor Name : </B></div>
						<div class="col-sm-6"><?=$names[$tcd]; ?></div>
						<div class="col-sm-12">&nbsp;</div>-->
					</div>
				</div>
			</div>
		</div> <?php 
	} 
} ?>


<div class="col-lg-3">
	<div class="ibox float-e-margins text-center">
		<div class="ibox-title"><h5>Blank Position</h5></div>
		<div class="ibox-content">
			<img src="<?=$dir_back?>images/mlm_tree_view/c.png" width="60" height="60" />
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="ibox float-e-margins text-center">
		<div class="ibox-title"><h5>Registered ID</h5></div>
		<div class="ibox-content">
			<img src="<?=$dir_back?>images/mlm_tree_view/f.png" width="60" height="60" />
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="ibox float-e-margins text-center">
		<div class="ibox-title"><h5>Active ID</h5></div>
		<div class="ibox-content">
			<img src="<?=$dir_back?>images/mlm_tree_view/p.png" width="60" height="60" />
		</div>
	</div>
</div>
<div class="col-lg-3">
	<div class="ibox float-e-margins text-center">
		<div class="ibox-title"><h5>Block ID</h5></div>
		<div class="ibox-content">
			<img src="<?=$dir_back?>images/mlm_tree_view/b.png" width="60" height="60" />
		</div>
	</div>
</div>
<!--<table class="table table-bordered table-hover">
	<thead>
	<tr>
		<th class="text-center">Blank Position </th>
		<th class="text-center">Registered ID</th>
		<th class="text-center">Active ID</th>
		<th class="text-center">Block ID</th>
	</tr>
	</thead>
	<tr class="text-center">
		<td><img src="<?=$dir_back?>images/mlm_tree_view/c.png" width="60" height="60" /></td>
		<td><img src="<?=$dir_back?>images/mlm_tree_view/f.png" width="60" height="60" /></td>
		<td><img src="<?=$dir_back?>images/mlm_tree_view/p.png" width="60" height="60" /></td>
		<td><img src="<?=$dir_back?>images/mlm_tree_view/b.png" width="60" height="60" /></td>
	</tr>
</table>
			
<table class="table table-bordered table-hover">
	<tr>
		<th>Tree Count</th>
		<th class="text-center">Left</th>
		<th class="text-center">Right</th>
		<th class="text-center">Total</th>
	</tr>
	<tr>
		<th>Total Paid Members</th>
		<td class="text-center"><?=$total_child[0][0][0];?></td>
		<td class="text-center"><?=$total_child[0][1][0];;?></td>
		<th class="text-center"><?=$total_child[0][0][0]+$total_child[0][1][0];?></th>
	</tr>
	<tr>
		<th>Total Unpaid Members</th>
		<td class="text-center"><?=$total_child[0][0][1];?></td>
		<td class="text-center"><?=$total_child[0][1][1];?></td>
		<th class="text-center"><?=$total_child[0][0][1]+$total_child[0][1][1];?></th>
	</tr>
	<tr>
		<th>Total Investment</th>
		<td class="text-center"><?=$total_child[0][0][2];?></td>
		<td class="text-center"><?=$total_child[0][1][2];?></td>
		<th class="text-center"><?=$total_child[0][0][2]+$total_child[0][1][2];?></th>
	</tr>
</table>-->
<form method="post" action="index.php?page=tree_view">
	<input type="submit" value="Back" class="btn btn-info">
</form>

<div class="member">&nbsp;</div>

<div class="member">
	<div class="member-info">
		<?=bord_position_chk($pos[0],$user_name[0],$name[0],$img[0],0,0,$login_id,0,$all_placement,$dir_back);?>
		<div class="divhrline"></div>
	</div>
	<div style="width:100%"><div class="divhr"></div></div>
	<div style="width:100%">
		<div class="divhrlineleft"></div>
		<div class="divhrlineright"></div>
	</div>
</div>
<div class="container" style="width:49%;">
	<div class="member">
		<div class="member-info">
			<?=bord_position_chk($pos[1],$user_name[1],$name[1],$img[1],0,1,$login_id,1,$all_placement,$dir_back);?>
			<div class="divhrline"></div>
		</div>
		<div style="width:100%"><div class="divhr"></div></div>
		<div style="width:100%">
			<div class="divhrlineleft"></div>
			<div class="divhrlineright"></div>
		</div>
	</div>
	<div class="container" style="width:49%;">
		<div class="member">
			<div class="member-info">
			<?=bord_position_chk($pos[3],$user_name[3],$name[3],$img[3],0,3,$login_id,2,$all_placement,$dir_back);?>
			</div>
		</div>
	</div>
	<div class="container" style="width:49%;">
		<div class="member">
			<div class="member-info">
			<?=bord_position_chk($pos[4],$user_name[4],$name[4],$img[4],1,4,$login_id,2,$all_placement,$dir_back);?>
			</div>
		</div>
	</div>
</div>
<div class="container" style="width:49%;">
	<div class="member">
		<div class="member-info">
			<?=bord_position_chk($pos[2],$user_name[2],$name[2],$img[2],1,2,$login_id,1,$all_placement,$dir_back);?>
			<div class="divhrline"></div>
		</div>
		<div style="width:100%"><div class="divhr"></div></div>
		<div style="width:100%">
			<div class="divhrlineleft"></div>
			<div class="divhrlineright"></div>
		</div>
	</div>
	<div class="container" style="width:49%;">
		<div class="member">
			<div class="member-info">
			<?=bord_position_chk($pos[5],$user_name[5],$name[5],$img[5],0,5,$login_id,3,$all_placement,$dir_back);?>
			</div>
		</div>
	</div>
	<div class="container" style="width:49%;">
		<div class="member">
			<div class="member-info">
			<?=bord_position_chk($pos[6],$user_name[6],$name[6],$img[6],1,6,$login_id,3,$all_placement,$dir_back);?>
			</div>
		</div>
	</div>
</div>

<?php 
} 

function bord_position_chk($pos,$user_name,$name,$img, $postion, $trig, $login_id,$pair_pos,$all_pos,$dir_back)
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
		<div class='imgMem'>
			$form_top
			<input type='hidden' name='id' value='$pos'  />
			<input type='hidden' name='ref' value='$login_id'  />
			<input type='hidden' name='placeref' value='$placement_username'  />
			<input type='hidden' name='reg_pos_user' value='$postion'  />
			<input type='submit' name='tree' style='background:
			url(".$dir_back."images/mlm_tree_view/$img.png) no-repeat; height:60px; width:60px; border:none' value='' /><br /><span class='clsspanid'>Register</span>
			$form_bottom 
		</div>";
	}
	else
	{
		$pos_blank = "
		<a id='trigger$trig' style='color:#333'>
			<div class='imgMem'>
				<form name='tree_v' action='index.php?page=tree_view' method='post'>
					<input type='hidden' name='id' value='$pos'  />
				
					<input type='submit' name='tree' style='background:
					url(".$dir_back."images/mlm_tree_view/$img.png) no-repeat; height:60px; width:60px; border:none' value='' />
				</form>
			</div>
			<span class='clsspanid'><B>$user_name<br />$name</B></span>
		</a>";
	}	
	return $pos_blank;
}

?>