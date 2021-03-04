<?php
function display($pos,$page,$img,$user_name,$name,$parent_u_name,$parent_full_name,$mode,$position,$date,$left_child,$right_child,$gender)
{
?>
<link rel="stylesheet" type="text/css" href="web_css/css_style1.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<?	$genelogy_id = $pos[1];
	for($i = 1; $i < 16; $i++)
	{
	if($pos[$i] == '' && $pos[$i] == 0) { $img[$i] = "c"; }
	
	$total_user_left[$i] = $left_child[$i][1]+$left_child[$i][2]+$left_child[$i][3];
	$total_user_right[$i] = $right_child[$i][1]+$right_child[$i][2]+$right_child[$i][3];
		
	} 
	
	$query = query_execute_sqli("select * from regstration_products ");  
	while($row = mysqli_fetch_array($query))
		$product[] = $row['reg_name'];	
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
	width:330px;
	padding:0;
	background: #eeeeee;
	color: #000000;
	border: 1px solid #ffffff;
	font-size: 90%;
  }
  
</style>
<script type="text/javascript">
  $(function() {
	var moveLeft = 20;
	var moveDown = 10;
	<? for($tr = 0; $tr < 15; $tr++)
	{ ?>
	$('a#trigger<? print $tr; ?>').hover(function(e) {
	  $('div#pop-up<? print $tr; ?>').show();
	  //.css('top', e.pageY + moveDown)
	  //.css('left', e.pageX + moveLeft)
	  //.appendTo('body');
	}, function() {
	  $('div#pop-up<? print $tr; ?>').hide();
	});
	
	$('a#trigger<?=$tr; ?>').mousemove(function(e) {
	 
	 <?php if($tr == 0 or $tr == 1 or $tr == 3 or $tr == 4 or $tr == 7 or $tr == 8 or $tr ==  9 or $tr == 10)
	 		{				?>
			 var moveLeft = 20;
			 var moveDown = -200;
			  $('div#pop-up<?=$tr; ?>').css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft)
	  <?php }
	  		else
			{				?>
			  var moveLeft = -420;
			  var moveDown = -200;
			  $('div#pop-up<?=$tr; ?>').css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft)
	  <?php }?>
	});
	
	<? } ?>
  });
  
</script>
<center>
<table class="table table-borderless">
	<tr>
		<td>
			<table class="table table-bordered table-hover">
				<thead><tr><th colspan="3" class="align-left">Downline</th></tr></thead>
				<tr><th colspan="3">Welcome  <?=$user_name[1];?></th></tr>
				<tr>
					<td>Achieved Rank: <B>Registered</B></td>
					<td>New Team Volume(L): </td>				
					<td>Left Leg: <? print $total_user_left[1]; ?></td>
					<!--<td><span id="ctl00_ContentPlaceHolder1_lblLeftR"><?=$left_child[1][2];?></span> </td>
					
					<td><span id="ctl00_ContentPlaceHolder1_lblRightR"><?=$right_child[1][2];?></span></td>
					<td>
						<span id="ctl00_ContentPlaceHolder1_lblRightR">
							<?=$tp_a =$left_child[1][2]+$right_child[1][2];?>
						</span> 
					</td>-->
				</tr>
				<tr>
					<td>Active: <span style="padding-left:60px;">Yes</span></td>
					<!--<td><span id="ctl00_ContentPlaceHolder1_lblLeftR"><?=$left_child[1][1];?></span> </td>
					<td><span id="ctl00_ContentPlaceHolder1_lblRightR"><?=$right_child[1][1];?></span> </td>
					<td>
						<span id="ctl00_ContentPlaceHolder1_lblRightR">
							<?=$tp_d =$left_child[1][1]+$right_child[1][1];?>
						</span> 
					</td>-->
					<td>New Team Volume(R):</td>
					<td>Right Leg: <?=$total_user_right[1]; ?></td>
				</tr>
				<tr>
					<td height="25">Package: </td>
					<!--<td><span id="ctl00_ContentPlaceHolder1_lblLeftR"><?=$left_child[1][3];?></span> </td>
					<td><span id="ctl00_ContentPlaceHolder1_lblRightR"><?=$right_child[1][3];?></span> </td>
					<td>
						<span id="ctl00_ContentPlaceHolder1_lblRightR">
							<?=$tp_b =$left_child[1][3]+$right_child[1][3];?>
						</span> 
					</td>-->
					<td><?php echo "<span style=\"font-weight:bold; \"> $date[1]</spna>"; ?></td>
					<td>&nbsp;</td>
				</tr>
				<tr class="MyFooter">
					<td>
						Sponsor: <? print "
						<span style=\"font-weight:bold; padding-left:40px;\"> $parent_full_name[1] </spna>"; ?>
					</td>
					<!--<td><strong><?=$left_child[1][1]+$left_child[1][2]+$left_child[1][3];?> </strong></td>
					<td><strong><?=$right_child[1][1]+$right_child[1][2]+$right_child[1][3];?></strong></td>
					<td><strong><?=$tp_a+$tp_d+$tp_b; ?></strong></td>-->
					<td>Personally Sponsored:<span style="font-weight:bold; padding-left:10px;">No</span></td>
					<td>&nbsp;</td>
				</tr>
				<tr><td colspan="3">Qualified:<span style="font-weight:bold; padding-left:50px;">Yes</span></td></tr>
				<tr class="MyFooter">
					<!--<td><strong>Total PV</strong> </td>
					<td><strong><?=$left_child[1][4]; ?></strong></td>
					<td<strong><?=$right_child[1][4]; ?></strong></td>
					<td><strong><?=$tp =$left_child[1][4]+$right_child[1][4]; ?></strong></td>-->
				<tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height="40" colspan="2" class="binary_tree_txt">
			<center>
				<div style=""> 
				<table class="table table-borderless">
					<tr>
						<td>
							<form action="index.php?page=geneology"  method="post">
								<input type="hidden" name="id" value="<?php echo  $_SESSION['mlmproject_user_id']; ?>" />
								<input type="hidden" name="action" value="top" />
								<input type="submit" name="submit"  value="" style="background:url(img/bt_top.png); border:none; height:81px; width:233px; cursor:pointer;" /> 
							</form>
						</td>
						<td>
							<form action="index.php?page=geneology"  method="post">
								<input type="hidden" name="id" value="<?php echo $genelogy_id;?>" />
								<input type="hidden" name="action" value="up" />
								<input type="submit" name="submit"  value="" style="background:url(img/bt_up_level.png); border:none; height:81px; width:233px; cursor:pointer;" /> 
							</form>
						</td>
						<td>
							<form action="index.php?page=geneology"  method="post">
								<input type="hidden" name="id" value="<?php echo $genelogy_id;?>" />
								<input type="hidden" name="action" value="left" />
								<input type="submit" name="submit"  value="" style="background:url(img/bt_bottom_left.png); border:none; height:81px; width:233px; cursor:pointer;" /> 
							</form>
						</td>
						<td>
							<form action="index.php?page=geneology"  method="post">
								<input type="hidden" name="id" value="<?php echo $genelogy_id;?>" />
								<input type="hidden" name="action" value="right" />
								<input type="submit" name="submit"  value="" style="background:url(img/bt_bottom_right.png); border:none; height:81px; width:233px; cursor:pointer;" /> 
							</form>
						</td>
					</tr>
				</table>
				<table class="table table-borderless">
					<tr>
						<td>
					<?php
					for($tcd = 1; $tcd < 16; $tcd++)
					{
						if($pos[$tcd] != 0) 
						{ ?>																
					<div id="pop-up<?=$tcd-1; ?>">	
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-left">Date Of Joining :</th>	
								<th class="text-left" colspan="3"><?= $date[$tcd]; ?></th>
							</tr>
						</thead>
						<tr><td>Distributor ID </td>	<td colspan="3"><?=$user_name[$tcd]; ?></td></tr>
						<tr><td>Distributor Name</td>	<td colspan="3"><?=$name[$tcd]; ?></td></tr>
						<tr><td>Sponsor ID </td>		<td colspan="3"><?=$parent_u_name[$tcd]; ?></td></tr>
						<tr><td>Sponsor Name</td>		<td colspan="3"><?=$parent_full_name[$tcd]; ?></td></tr>
						<tr>
							<td>Total Left ID</td>		<td><?=$left_child[$tcd];?></td>
							<td>Total Right ID </td>	<td><?=$right_child[$tcd];?></td>
						</tr>
						<tr>
							<td>Total Child</td>
							<td><?=$total_user_left[$tcd];?></td>
							<td><?=$total_user_right[$tcd];?></td>
						</tr>
						<tr>
							<td>Total PV</td>
							<td><?=$left_child[$tcd][4];?></td>
							<td><?=$right_child[$tcd][4];?></td>
						</tr>
        			</table>
					</div>
			<?php 		} 
					} ?>		
   						</td>
					</tr>
					<tr>
						<td valign="top" colspan="8">
							<?= bord_position_chk($pos[1], $user_name[1] , $img[1] ,0 ,0, $name[1]); ?>
						</td>
					</tr>
					<tr>
						<td colspan="8">
							<div align="center"><img src="img/band1.gif" width="495" height="" /></div>
						</td>
					</tr>
					<tr>
						<td colspan="4"><?= bord_position_chk($pos[2], $user_name[2] , $img[2] ,0 ,1, $name[2]); ?></td>
						<td colspan="4"><?= bord_position_chk($pos[3], $user_name[3] , $img[3] ,1 ,2, $name[3]); ?></td>
					</tr>
					<tr>
						<td colspan="4">
							<div align="center"><img src="img/band2.gif" width="325" height="35" /></div>
						</td>
						<td colspan="4">
							<div align="center"><img src="img/band2.gif" width="325" height="35" /></div>
						</td>
					</tr>
					<tr>
						<td colspan="2"><?= bord_position_chk($pos[4], $user_name[4] , $img[4] ,0 ,3, $name[4]);?></td>
						<td colspan="2"><?= bord_position_chk($pos[5], $user_name[5] , $img[5] ,0 ,4, $name[5]);?></td>
						<td colspan="2"><?= bord_position_chk($pos[6], $user_name[6] , $img[6] ,1 ,5, $name[6]);?></td>
						<td colspan="2"><?= bord_position_chk($pos[7], $user_name[7] , $img[7] ,1 ,6, $name[7]);?></td>
					</tr>
					<tr>
						<td colspan="2">
							<div align="center">
								<a href="#"><img src="img/band4.gif" width="125" height="35" /></a>
							</div>
						</td>
						<td colspan="2">
							<div align="center">
								<a href="#"><img src="img/band4.gif" width="125" height="35" /></a>
							</div>
						</td>
						<td colspan="2">
							<div align="center">
								<a href="#"><img src="img/band4.gif" width="125" height="35" /></a>
							</div>
						</td>
						<td colspan="2">
							<div align="center">
								<a href="#"><img src="img/band4.gif" width="125" height="35" /></a>
							</div>
						</td>
					</tr>
					<tr>
						<td><?= bord_position_chk($pos[8], $user_name[8] , $img[8] ,0 ,7, $name[8]); ?></td>
						<td><?= bord_position_chk($pos[9], $user_name[9] , $img[9] ,0 ,8, $name[9]); ?></td>
						<td><?= bord_position_chk($pos[10], $user_name[10] , $img[10] ,0 ,9, $name[10]); ?></td>
						<td><?= bord_position_chk($pos[11], $user_name[11] , $img[11] ,0 ,10, $name[11]); ?></td>
						<td><?= bord_position_chk($pos[12], $user_name[12] , $img[12] ,1 ,11, $name[12]); ?></td>
						<td><?= bord_position_chk($pos[13], $user_name[13] , $img[13] ,1 ,12, $name[13]); ?></td>
						<td><?= bord_position_chk($pos[14], $user_name[14] , $img[14] ,1 ,13, $name[14]); ?></td>
						<td><?= bord_position_chk($pos[15], $user_name[15] , $img[15] ,1 ,14, $name[15]); ?></td>
					</tr>
				</table>
				</div>
			</center>
		</td>
	</tr>
</table>		
<? } 

function display_member($pos,$page,$img,$user_name,$parent_u_name,$name,$mode,$position,$date,$left_child,$right_child,$gender)
{
	for($i = 0; $i < 3; $i++)
	{
	if($pos[$i] == '' && $pos[$i] == 0) { $img[$i] = "c"; }
	} 
?>
</center>

<link rel="stylesheet" type="text/css" href="web_css/css_style1.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<?
	for($i = 0; $i < 3; $i++)
	{
	if($pos[$i] == '' && $pos[$i] == 0) { $img[$i] = "c"; }
	
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
        width:360px;
        padding:0;
        background: #eeeeee;
        color: #000000;
        border: 1px solid #ffffff;
        font-size: 90%;
      }
      
    </style>
    <script type="text/javascript">
      $(function() {
        var moveLeft = 20;
        var moveDown = 10;
		<? for($tr = 0; $tr < 3; $tr++)
		{ ?>
		$('a#trigger<? print $tr; ?>').hover(function(e) {
          $('div#pop-up<? print $tr; ?>').show();
          //.css('top', e.pageY + moveDown)
          //.css('left', e.pageX + moveLeft)
          //.appendTo('body');
        }, function() {
          $('div#pop-up<? print $tr; ?>').hide();
        });
        
        $('a#trigger<? print $tr; ?>').mousemove(function(e) {
          $('div#pop-up<? print $tr; ?>').css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
        });
		
		<? } ?>
      });
	  
	</script>
	
<table class="table table-borderless">
	<tr>
		<td>
		<?
		for($tcd = 0; $tcd < 3; $tcd++)
		{
			if($pos[$tcd] != 0) { ?>									
			<div id="pop-up<? print $tcd; ?>">	
			<table class="table table-borderless">
				<thead>
					<tr>
						<th colspan="4" class="text-left"><B>Date Of Joining : <?= $date[$tcd]; ?></B></th>
					</tr>
				</thead>
				<tr><td >Distributor ID </td>	<td colspan="3"><? print $user_name[$tcd]; ?></td></tr>
				<tr><td>Distributor Name</td>	<td colspan="3"><? print $name[$tcd]; ?></td></tr>
				<tr><td>Sponsor ID </td>		<td colspan="3"><? print $parent_u_name[$tcd]; ?></td></tr>
				<tr><td>Sponsor Name</td>		<td colspan="3"><? print $parent_full_name[$tcd]; ?></td></tr>
				<tr>
					<td>Total Left ID</td>
					<td width="50"><? print $left_child[$tcd]; ?></td>
					<td width="125">Total Right ID </td>
					<td width="50"><? print $right_child[$tcd]; ?></td>
				</tr>
				<!--<tr><tdcolspan="4"><strong>SelfTopUp&nbsp; :</strong><strong> 10000 </strong></td></tr>
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
			</div>
	<?		} 
		} ?>		
   		</td>
	</tr>
	<tr>
		<td colspan="6">
			<div align="center">
				<a href="index.php?page=search-member&id=<?=$pos[0];?>" id="trigger0">
					<img src="img/<?=$img[0];?>.png" width="80px" height="80px" />
				</a><br />
				<strong>
					<a href="index.php?page=search-member&id=<?=$pos[0];?>" id="trigger0"><?=$user_name[0];?></a>
				</strong>
			</div>
		</td>
	</tr>
	<tr><td colspan="6"><div align="center"><img src="img/band1.gif" width="470" height="35" /></div></td></tr>
	<tr>
		<td valign="top" colspan="4">
			<div align="center">
				<a href="index.php?page=search-member&id=<?=$pos[1];?>" id="trigger1">
					<img src="img/<?=$img[1];?>.png" width="80px" height="80px" />
				</a><br />
				<strong>
					<a href="index.php?page=search-member&id=<?=$pos[1];?>" id="trigger1"><?=$user_name[1];?></a>
				</strong>
			</div>
		</td>
		<td valign="top" colspan="4">
			<div align="center">
				<a href="index.php?page=search-member&id=<?=$pos[2];?>" id="trigger2">
					<img src="img/<?=$img[2];?>.png" width="80px" height="80px" />
				</a><br />
				<strong>
					<a href="index.php?page=search-member&id=<?=$pos[2];?>" id="trigger2"><?=$user_name[2];?></a>
				</strong>
			</div>
		</td>
	</tr>
</table>
</center>
<? } 


function bord_position_chk($pos, $user_name , $img , $postion , $trig, $name)
{
	$str = explode(' ', $name);
	$ful_name = $str[0];
	
	if($pos == 0 or $pos == '')
	{
		$pos_blank = "
			<div align=\"center\">
				<form name=\"tree_v\" action=\"register.php\" method=\"post\" target=\"_blank\">
					<input type=\"hidden\" name=\"id\" value=\"$pos\"  />
					<input type=\"hidden\" name=\"reg_pos_user\" value=\"$postion\"  />
					<input type=\"submit\" name=\"tree\" style=\"background:url(img/$img.png) no-repeat; height:60px; width:60px; border:none;cursor:pointer;\" value=\"\"/><br />
					<strong>$user_name </a></strong>
				</form>
			</div>";
	}
	else
	{
		
		$pos_blank = "
			<div align=\"center\">
				<a id=\"trigger$trig\">	
					<form name=\"tree_v\" action=\"index.php?page=geneology\" method=\"post\">
						<input type=\"hidden\" name=\"id\" value=\"$pos\"  />
						<input type=\"submit\" name=\"tree\" style=\"background:url(img/$img.png) no-repeat; height:60px; width:60px; border:none;cursor:pointer;\" value=\"\"/><br />
						<strong><a id=\"trigger$trig\">$user_name</a> <br />($ful_name)</strong>
					</form>
				</a>
			</div>";
		 }	
	return $pos_blank;
}

?>


