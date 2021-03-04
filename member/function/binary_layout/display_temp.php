<?php
function display($pos,$page,$img,$user_name,$name,$parent_u_name,$parent_full_name,$mode,$position,$date,$left_child,$right_child,$gender,$login_id)
{
$all_placement = $user_name;
?>
<link rel="stylesheet" type="text/css" href="web_css/css_style1.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<?php
for($i = 0; $i < 15; $i++)
{
	if($pos[$i] == '' && $pos[$i] == 0) { $img[$i] = "c"; }
} 
if($pos[0] != '')
{
	//include("function/all_child.php");
	include("function/total_info_display.php");
	//$childrens = give_all_children($pos[0]);
	//$total_left = count($childrens[0]);
	//$total_right = count($childrens[1]);
	$childrens = get_lft_rht_network_child($pos[0]);
	$left_info = get_total_paid_unpaid_members($childrens[0]);
	$right_info = get_total_paid_unpaid_members($childrens[1]);
	$total_net_invesment = get_lft_rht_business($pos[0]);
}

?>
<style>
div#container {
	width: 580px;
	margin: 100px auto 0 auto;
	padding: 20px;
	background: #000;
	border: 1px solid #1a1a1a;
}

/* HOVER STYLES */
div#pop-up0, #pop-up1, #pop-up2, #pop-up3, #pop-up4, #pop-up5, #pop-up6, #pop-up7, #pop-up8, #pop-up9, #pop-up10, #pop-up11, #pop-up12, #pop-up13, #pop-up14 {
	display: none;
	position:absolute;
	width:50%;
	padding:0;
	background: #eeeeee;
	color: #000000;
	border: 1px solid #ffffff;
	font-size: 90%;
}

</style>
<script type="text/javascript">
  $(function() {
	var moveLeft = -500;
	var moveDown = -650;
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
		$name = get_full_name($pos[$tcd]);
		$network_business = get_lft_rht_business($pos[$tcd]);
		$sql = "SELECT sum(update_fees) as amount,date FROM reg_fees_structure 
		WHERE user_id = '$pos[$tcd]' and mode = 1";
		$inv_query = query_execute_sqli($sql);
		$num = mysqli_num_rows($inv_query);
		if($num == 0)  
		{
			$totl_amount[$tcd] = 0;
			$totl_date[$tcd] = '';
		}
		else
		{	
			while($rowsss = mysqli_fetch_array($inv_query))
			{
				if($rowsss['amount'] == '')  
				{
					$totl_amount[$tcd] = 0;
					$totl_date[$tcd] = '';
				}
				else
				$totl_amount[$tcd] = $rowsss['amount'];
				$totl_date[$tcd] = $rowsss['date'];
			}
		} ?>									
		<div id="pop-up<?=$tcd; ?>" style="z-index:9999">	
			<div class="box">
				<div class="box-header"><h3 class="box-title">Details</h3></div>
				<div class="box-body">
					<table class="table table-bordered table-hover">
						<tr>
							<th>Date Of Joining</th> 
							<td colspan="3"><?=date('d/M/Y', strtotime($date[$tcd]));?></td>
						</tr>
						<tr>
							<th>Distributor ID</th>
							<td colspan="3"><?=$user_name[$tcd]; ?></td>
						</tr>
						<tr>
							<th>Distributor Name</th>
							<td colspan="3"><?=$name; ?></td>
						</tr>
						<tr>
							<th>Sponsor ID </th>
							<td colspan="3"><?=$parent_u_name[$tcd]; ?></td>
						</tr>
						<!--<tr>
							<th>Sponsor Name</th>
							<td colspan="3"><?=$parent_full_name[$tcd]; ?></td>
						</tr>-->
						<tr>
							<th>Total Left ID</th>
							<td><?=$left_child[$tcd];?></td>
							<th>Total Right ID </th>
							<td><?=$right_child[$tcd];?></td>
						</tr>
						 <tr>
							<th>Self TopUp</th>
							<td><?=$totl_amount[$tcd];?></td>
							<th>Date</th>
							<td><?=$totl_date[$tcd];?></td>
						</tr>
						 <tr>
							<th>Left TopUp</th>
							<td><?=$network_business[0];?></td>
							<th>Right TopUp</th>
							<td><?=$network_business[1];?></td>
						</tr>
					</table>
				</div>
			</div>
		</div> <?php 
	} 
} ?>
<table class="table table-bordered table-hover">
	<tr>
		<th class="text-center">Free Member</th>
		<th class="text-center">Paid Member</th>
		<th class="text-center">Blank Position </th>
	</tr>
	<tr class="text-center">
		<td><img src="images/mlm_tree_view/f.png" width="60" height="60" /></td>
		<td><img src="images/mlm_tree_view/p.png" width="60" height="60" /></td>
		<td><img src="images/mlm_tree_view/c.png" width="60" height="60" /></td>
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
		<td class="text-center"><?=$left_info[0];?></td>
		<td class="text-center"><?=$right_info[0];?></td>
		<th class="text-center"><?=$tp =$left_info[0]+$right_info[0];?></th>
	</tr>
	<tr>
		<th>Total Unpaid Members</th>
		<td class="text-center"><?=$left_info[1];?></td>
		<td class="text-center"><?=$right_info[1];?></td>
		<th class="text-center"><?=$tp =$left_info[1]+$right_info[1];?></th>
	</tr>
	<tr>
		<th>Total Investment</th>
		<td class="text-center"><?=$total_net_invesment[0];?></td>
		<td class="text-center"><?=$total_net_invesment[1];?></td>
		<th class="text-center"><?=$tp =$total_net_invesment[0]+$total_net_invesment[1];?></th>
	</tr>
</table>
<table width="100%" style="background: #fff;">
	<tr>
		<td>
			<form method="post" action="index.php?page=tree_view_temp">
				<input type="submit" value="Back" class="btn btn-info">
			</form>
		</td>
		<td colspan="6" class="text-center">
				
				<form name="tree_v" action="index.php?page=tree_view" method="post">
				<input type="hidden" name="id" value="<?=$pos[0]; ?>"  />
				<a id="trigger0">
				<input type="submit" name="tree" style="background:url(images/mlm_tree_view/<?=$img[0]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br /><B><?=$user_name[0];?></B>
				</a>
				</form>
		</td>
		<td></td>
	</tr>
	<tr><td colspan="8" class="text-center"><img src="images/mlm_tree_view/band1.gif" width="550"/></td></tr>
	<tr>
		<td colspan="4">
			<?=bord_position_chk($pos[1], $user_name[1] , $img[1] ,0 ,1,$login_id,1,$all_placement);?>
		</td>
		<td colspan="4">
			<?=bord_position_chk($pos[2], $user_name[2] , $img[2] ,1 ,2,$login_id,1,$all_placement);?>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="text-center"><img src="images/mlm_tree_view/band2.gif" /></td>
		<td colspan="4" class="text-center"><img src="images/mlm_tree_view/band2.gif" /></td>
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
		<td colspan="2" class="text-center"><img src="images/mlm_tree_view/band4.gif" /></td>
		<td colspan="2" class="text-center"><img src="images/mlm_tree_view/band4.gif" /></td>
		<td colspan="2" class="text-center"><img src="images/mlm_tree_view/band4.gif" /></td>
		<td colspan="2" class="text-center"><img src="images/mlm_tree_view/band4.gif" /></td>
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
<?php 
} 

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
			<input type='submit' name='tree' style='background:url(images/mlm_tree_view/$img.png) no-repeat; height:60px; width:60px; border:none' value=''/><br />Register
			<B>$user_name</B>
			$form_bottom 
		</div>";
	}
	else
	{
		$pos_blank = "
		<div class='text-center'>
			<form name='tree_v' action='index.php?page=tree_view_temp' method='post'>
				<input type='hidden' name='id' value='$pos'  />
				<a id='trigger$trig' style='color:#333'>
					<input type='submit' name='tree' style='background:url(images/mlm_tree_view/$img.png) no-repeat; height:60px; width:60px; border:none' value=''/><br />
					<B>$user_name</B>
				</a>
			</form>
		</div>";
	}	
	return $pos_blank;
}

?>