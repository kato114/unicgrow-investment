<?php
function display($pos,$page,$img,$user_name,$name,$parent_u_name,$parent_full_name,$mode,$position,$date,$left_child,$right_child,$gender)
{

?>
<link rel="stylesheet" type="text/css" href="web_css/css_style1.css" />
<script type="text/javascript" src="js/jquery.min.js"></script>
<?php
	for($i = 0; $i < 15; $i++)
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
	
	<?php } ?>
  });
  
</script>
<center>
<table class="table table-borderless">
	<tr>
		<td>
			<table class="table table-bordered table-hover">
				<thead><tr><th colspan="4" class="text-left">Legend</th></tr></thead>
			</table>
			<table class="table table-bordered table-hover">
				<thead>
				<tr>
					<th class="text-center">Free Member</th>
					<th class="text-center">Paid Member</td>
					<th class="text-center">Blank Position </td>
					<th class="text-center">Block Member </td>
				</tr>
				</thead>
				<tr>
					<td class="text-center"><img src="img/f.png" width="50" height="50" /></td>
					<td class="text-center"><img src="img/p.png" width="50" height="50" /></td>
					<td class="text-center"><img src="img/c.png" width="50" height="50" /></td>
					<td class="text-center"><img src="img/b.png" width="50" height="50" /></td>
				</tr>
			</table>				
		</td>
	</tr>
	<tr>
		<td colspan="2" valign="top" class="binary_tree_txt">
			<table class="table table-borderless">
				<tr>
					<td>
					<?php
						for($tcd = 0; $tcd < 15; $tcd++)
						{
							if($pos[$tcd] != 0) 
							{ 
								$sql = "SELECT sum(update_fees) as amount,date FROM reg_fees_structure WHERE 
								user_id = '$pos[$tcd]' and mode = 0";
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
								}	
						
					?>									
						<div id="pop-up<?=$tcd; ?>">	
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-left">Date Of Joining :</th>	
									<th class="text-left" colspan="3"><?= $date[$tcd]; ?></th>
								</tr>
							</thead>
							<tr><td>Distributor ID</td>		<td colspan="3"><?=$user_name[$tcd]; ?></td></tr>
							<tr><td>Distributor Name</td>	<td colspan="3"><?=$name[$tcd]; ?></td></tr>
							<tr><td>Sponsor ID </td>		<td colspan="3"><?=$parent_u_name[$tcd]; ?></td></tr>
							<tr><td>Sponsor Name</td>		<td colspan="3"><?=$parent_full_name[$tcd]; ?></td></tr>
							<tr>
								<td>Total Left ID</td>		<td><?=$left_child[$tcd]; ?></td>
								<td>Total Right ID </td>	<td><?=$right_child[$tcd]; ?></td>
							</tr>
							<tr>
								<td colspan="2">SelfTopUp&nbsp; : <?=$totl_amount[$tcd]; ?></td>
								<td colspan="2">Date : <?=$totl_date[$tcd];?></td>
							</tr>
							<!--<tr>
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

				<?php 		} 
						} ?>		
					</td>
				</tr>
				<tr>
					<td>
						<form method="post" action="index.php?page=simple_tree">
							<input type="submit" value="Back" class="btn btn-primary">
						</form>
					</td>
					<td colspan="6">
						<div align="center">
							<a id="trigger0">	
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[0]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[0]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger0"><?=$user_name[0]; ?></a></strong>
							</form>
							</a>
						</div>
 					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="8">
						<div align="center"><img src="img/band1.gif" width="550" height="35" /></div>
					</td>
				</tr>
				<tr>
					<td colspan="4" style="width:480px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger1"> 	 
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[1]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[1]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger1"><?=$user_name[1]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td colspan="4">
						<div align="center">
							<a id="trigger2">	
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[2]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[2]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger2"><?=$user_name[2]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
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
					<td colspan="2">
						<div align="center">
							<a id="trigger3">	
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[3]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[3]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger3"><?=$user_name[3]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td colspan="2" style="width:240px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger4">	
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[4]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[4]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger4"><?=$user_name[4]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td colspan="2" style="width:240px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger5">
							<form name="tree_v" action="index.php?page=simple_tree" method="post">								
								<input type="hidden" name="id" value="<?=$pos[5]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[5]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger5"><?=$user_name[5]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td colspan="2" style="width:240px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger6">
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[6]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[6]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger6"><?=$user_name[6]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
				</tr>
				<tr>
				<td colspan="2">
					<div align="center"><a href="#"><img src="img/band4.gif" width="125" height="35" /></a></div>
				</td>
				<td colspan="2">
					<div align="center"><a href="#"><img src="img/band4.gif" width="125" height="35" /></a></div>
				</td>
				<td colspan="2">
					<div align="center"><a href="#"><img src="img/band4.gif" width="125" height="35" /></a></div>
				</td>
				<td colspan="2">
					<div align="center"><a href="#"><img src="img/band4.gif" width="125" height="35" /></a></div>
				</td>
				</tr>
				<tr>
					<td style="width:120px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger7">
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[7]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[7]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger7"><?=$user_name[7]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td style="width:120px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger8">
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[8]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[8]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger7"><?=$user_name[8]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td style="width:120px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger9">	
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[9]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[9]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger9"><?=$user_name[9]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td style="width:120px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger10">	
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[10]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[10]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger10"><?=$user_name[10]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td style="width:120px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger11">
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[11]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[11]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger11"><?=$user_name[11]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td style="width:120px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger12">
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[12]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[12]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger12"><?=$user_name[12]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td style="width:120px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger13">	
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[13]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[13]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger13"><?=$user_name[13]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
					<td style="width:120px; height:100px; padding-top:10px; vertical-align:top;">
						<div align="center">
							<a id="trigger14">
							<form name="tree_v" action="index.php?page=simple_tree" method="post">
								<input type="hidden" name="id" value="<?=$pos[14]; ?>"  />
								<input type="submit" name="tree" style="background:url(img/<?=$img[14]; ?>.png) no-repeat; height:60px; width:60px; border:none" value=""/><br />
								<strong><a id="trigger14"><?=$user_name[14]; ?></a></strong>
							</form>
							</a>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>		
								</center>
<?php } 

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
<?php
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
		<?php for($tr = 0; $tr < 3; $tr++)
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
	
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
                                   <br /><br />
								   <tr>
								   	<td>
<?php
for($tcd = 0; $tcd < 3; $tcd++)
{
	if($pos[$tcd] != 0) { ?>									
									
<div id="pop-up<?=$tcd; ?>">	
<table class="MyTable" border="1" bordercolor="#FFFFFF" style="border-collapse:collapse; margin:6px;" cellpadding="0" cellspacing="0" width="350" >
		<tr>
            <td height="25" colspan="4" bgcolor="#E3E8EC"><p><strong>Date Of Joining : </strong><strong><?=$date[$tcd]; ?></strong></p></td>
            </tr>
          <tr>
            <td width="113">Distributor ID </td>
            <td colspan="3"><?=$user_name[$tcd]; ?></td>
            </tr>
          <tr>
            <td>Distributor Name</td>
            <td colspan="3"><?=$name[$tcd]; ?></td>
            </tr>
          <tr>
            <td>Sponsor ID </td>
            <td colspan="3"><?=$parent_u_name[$tcd]; ?></td>
            </tr>
          <tr>
            <td>Sponsor Name</td>
            <td colspan="3"><?=$parent_full_name[$tcd]; ?></td>
            </tr>
          
          <tr>
            <td>Total Left ID</td>
            <td width="50"><?=$left_child[$tcd]; ?></td>
            <td width="125">Total Right ID </td>
            <td width="50"><?=$right_child[$tcd]; ?></td>
          </tr>
       <!--  <tr>
            <td height="25" colspan="4" bgcolor="#E3E8EC"><p><strong>SelfTopUp&nbsp; :</strong><strong> <?php 200; ?> </strong></p></td>
            </tr>
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

<?php } } ?>		
   									</td>
								   </tr>
								  <tr>
                                    <td colspan="8">
									<div align="center"><a href="index.php?page=search-member&id=<?=$pos[0]; ?>" id="trigger0"><img src="images/<?=$img[0]; ?>.png" width="76" height="76" /></a><br />
                                        <strong><a href="index.php?page=search-member&id=<?=$pos[0]; ?>" id="trigger0"><?=$user_name[0]; ?></a></strong></div>
 									</td>
                                  </tr>
                                  <tr>
                                    <td colspan="8"><div align="center"><img src="web_img/band1.gif" width="470" height="35" /></div></td>
                                  </tr>
                                  <tr>
                                    <td colspan="4">
									<div align="center"><a href="index.php?page=search-member&id=<?=$pos[1]; ?>" id="trigger1"><img src="images/<?=$img[1]; ?>.png" width="76" height="76" /><br />
                                      <strong><a href="index.php?page=search-member&id=<?=$pos[1]; ?>" id="trigger1"><?=$user_name[1]; ?></a></strong></div>
									  </td>
                                    <td colspan="4">
									<div align="center"><a href="index.php?page=search-member&id=<?=$pos[2]; ?>" id="trigger2"><img src="images/<?=$img[2]; ?>.png" width="76" height="76" /><br />
                                      <strong><a href="index.php?page=search-member&id=<?=$pos[2]; ?>" id="trigger2"><?=$user_name[2]; ?></a></strong>
									</div>
									</td>
                                  </tr>
                                  
                                </table></center>
<?php } ?>