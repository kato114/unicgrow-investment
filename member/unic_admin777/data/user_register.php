<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
$actual_parent = $_SESSION['real_parent_id'];
	?>
	
	
	<!-- Form Code Start -->  
	<div id="form"> <?php $mg=$_REQUEST[mg]; echo "<h2>".$mg."</h2>"; ?>
	<form name="register" action="index.php?val=add_member&open=3" method="post" >
	

	<?php 
	$child = geting_virtual_parent($actual_parent);
	if($child > 1)
	{ ?>
		<div class="form_label"> Virtual Parent List </div><div class="form_data">
			<select name="virtual_parent" >
				<option value="" >Select One</option>
			<?php
				$virtual_par =geting_all_blank_position($actual_parent);
				$count = count($virtual_par);
				for($i = 0; $i <$count; $i++)
				{ 
					$user_name = get_user_name($virtual_par[$i]);
					$position = get_user_pos($virtual_par[$i]);
				?> 
					<option value="<?php echo $virtual_par[$i]; ?>" ><?php echo $user_name." (".$position.")"; ?></option>
				<?php } ?>
			</select>	
		</div>	
	<?php }
	else
	{ ?> 
		<input type="hidden" size=26 name=virtual_parent value="<?php echo $actual_parent; ?>" /> <?php 
		} ?>
	<div class="form_label"> First Name </div><div class="form_data"><input type=text size=26 name=f_name /></div>
	<div class="form_label"> Last Name </div><div class="form_data"><input type=text size=26 name=l_name /></div>
	<div class="form_label"> Date of Birth </div><div class="form_data"><input type=text size=26 name=dob /></div>
	<div class="form_label"> Gender </div><div class="form_data"><input type="radio" name=gender value="male" checked="checked" />	<strong>Male</strong><input type="radio" name=gender value="female" /><strong>Female</strong></div>
	<div class="form_label"> Address </div><div class="form_data"><textarea  name=address /></textarea></div>
	<div class="form_label"> City </div><div class="form_data"><input type=text size=26 name=city /></div>
	<div class="form_label"> Provience </div><div class="form_data"><input type=text size=26 name=provience /></div>
	<div class="form_label"> Country </div><div class="form_data"><input type=text size=26 name=country /></div>
	<div class="form_label"> E-Mail </div><div class="form_data"><input type=text size=26 name=email /></div>
	<div class="form_label"> Phone No. </div><div class="form_data"><input type=text size=26 name=phone /></div>
	<div class="form_label"> User Name </div><div class="form_data"><input type=text size=26 name=username /></div>
	<div class="form_label"> Password </div><div class="form_data"><input type="password" size=26 name=password /></div>
	<div class="form_label"> Re-Password </div><div class="form_data"><input type="password" size=26 name=re_password /></div>
	<div class="form_label"> Mode </div><div class="form_data">
		<select name="reg_mode">
			<option value="" >Select Mode</option>
			<?php $count = 2;//count($registration_mode);
			for($i = 0; $i < $count; $i++)
			{ ?>
				<option value="<?php echo $registration_mode[$i]; ?>" ><?php echo $registration_mode_value[$i]; ?></option>
			<?php  }  ?>
			</select>	
	</div>

	<div id="submit">
	<input type="submit" name="submit" value="Register" class="btn btn-info" />
	</div>
	
	</form>
	</div>
	
	
<?php ?>

				



				
				
			
			
			
			
			
			
			
				