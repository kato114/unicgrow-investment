$board_virtual_par = $_REQUEST['vp'];
				$query = query_execute_sqli("select * from users where parent_id = '".$_SESSION['mlmproject_user_id']."' ");
				$num = mysqli_num_rows($query);
				if($num != 0 and $board_virtual_par == 0)
				{
				 ?>
					<table width="500" border="0">
					<form name="user_position" action="index.php?val=user_registration&open=2" method="post"> 
					  <tr>
						<td colspan="2">User Position for New User Registration</td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td>Position</td>
						<td>
							<select name="position">
								<option value="0">Left Position</option>
								<option value="1">Right Position</option>
							</select>	
						</td>
					  </tr>
					  <tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td colspan="2"><input type="submit" name="submit" value="Position" /></td>
					  </tr>
					</table>
			<?php
				}
				else
				{ ?>
					<div class="comment odd alt thread-odd thread-alt depth-1" style="width:90%">
					<!-- Form Code Start -->  
					<div id="form"> <?php $mg=$_REQUEST[mg]; echo "<h2>".$mg."</h2>"; ?>
					<form name="register" action="index.php?val=user_registration&open=2" method="post"  >
					</div>
					<input type="hidden" size=26 name=real_parent value="<?php echo $_SESSION['mlmproject_user_id']; ?>" class="input-medium" /> 
					<input type="hidden" size=26 name=virtual_parent value="<?php if($board_virtual_par == 0) echo $_SESSION['mlmproject_user_id']; else print $board_virtual_par; ?>" class="input-medium" />
					<div class="form_label"> First Name </div><div class="form_data"><input type=text size=26 name=f_name class="input-medium" /></div>
					<div class="form_label"> Last Name </div><div class="form_data"><input type=text size=26 name=l_name class="input-medium" /></div>
					<div class="form_label"> Date of Birth </div><div class="form_data"><input type=text size=26 name=dob class="input-medium flexy_datepicker_input"/></div>
					<div class="form_label"> Gender </div><div class="form_data"><input type="radio" name=gender value="male" checked="checked" />	<strong>Male</strong><input type="radio" name=gender value="female" /><strong>Female</strong></div>
					<div class="form_label"> Address </div><div class="form_data"><textarea  name=address style="height:50px; width:240px" /></textarea></div>
					<div class="form_label"> City </div><div class="form_data"><input type=text size=26 name=city class="input-medium" /></div>
					<div class="form_label"> Provience </div><div class="form_data"><input type=text size=26 name=provience class="input-medium" /></div>
					<div class="form_label"> Country </div><div class="form_data"><input type=text size=26 name=country class="input-medium" /></div>
					<div class="form_label"> E-Mail </div><div class="form_data"><input type=text size=26 name=email class="input-medium" /></div>
					<div class="form_label"> Phone No. </div><div class="form_data"><input type=text size=26 name=phone class="input-medium" /></div>
					<div class="form_label"> User Name </div><div class="form_data"><input type=text size=26 name=username class="input-medium" /></div>
					<div class="form_label"> Password </div><div class="form_data"><input type="password" size=26 name=password class="input-medium" /></div>
					<div class="form_label"> Re-Password </div><div class="form_data"><input type="password" size=26 name=re_password class="input-medium" /></div>
					<div id="submit">
					<input type="submit" name="submit" value="submit" class="button" />
					</div>
					</form>
					</div>
				
			<?php	}