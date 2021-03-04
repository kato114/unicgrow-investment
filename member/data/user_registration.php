<?php
include('../security_web_validation.php');
?>
<?php  
        require_once("validation/validation.php");  
    ?> 
 <h1 align="left">User Registration</h1><hr />
<?php
session_start();
	require_once("config.php");
	include("condition.php");
	include("function/setting.php");
	
	require_once "function/formvalidator.php";
	include("function/virtual_parent.php");
	include("function/send_mail.php");
	include("function/e_pin.php");
	include("function/best_position.php");
	include("function/income.php");
require_once("function/country_list.php");
include("function/insert_into_wallet.php");
include("function/check_income_condition.php");
include("function/direct_income.php");

include("function/pair_point_calculation.php");
?>
<h2 align="left">New User Registration</h2>
<?php

$id = $_SESSION['mlmproject_user_id'];
		
	if(isset($_POST['submit']))
	{
		if($_POST['submit'] == 'Position')
		{ 
			$par_position = $_REQUEST['position'];
		?>
			<div class="comment odd alt thread-odd thread-alt depth-1" style="width:90%">
				<!-- Form Code Start -->  
				<div id="form"> 
				<form name="register" id="registrarionForm" action="index.php?page=user_registration" method="post"  >
				</div>
				
				<?php 
				//$child = geting_virtual_parent($_SESSION['mlmproject_user_id']);
				$best_child = geting_best_position($_SESSION['mlmproject_user_id'],$par_position);
				?>
				<input type="hidden" name=virtual_parent value="<?php echo $best_child; ?>" class="input-medium" /> 
				<input type="hidden" size=26 name=user_pisition value="<?php echo $par_position; ?>" class="input-medium" />
				
				<p><div style=" width:200px; float:left; padding-left:36px;"> <label for="name">First Name</label></div><div style="width:260px; padding-right:10px;"> <input type=text size=26 id="name" name=f_name class="input-medium" /></div></span><div style=" padding-right:75px; float:right;margin-top:-25px; width:250px"><span id="nameInfo"></span> </div></p> 
												<p><div style="width:200px; float:left; padding-left:35px;"><label for="l_name"> Last Name </label></div><div style="width:260px; padding-right:10PX;"><input type=text size=26 id="l_name" name=l_name class="input-medium" /></div><div style=" width:250px; float:right; margin-top:-25px;  padding-right:75px;"><span id="l_nameInfo"></span> </div></p> 
											<p><div style="width:200px; float:left; padding-left:20px;"> <label for="message">Address</label></div><div style="width:300px; padding-right:10PX;"><textarea name=address  id="message" style="height:50px; width:240px" /></textarea></div><div style="float:right; margin-top:-40px; width:250px; padding-right:80px;"><span id="messageInfo"></span></div></p>
												
												<p><div style="width:200px; float:left; padding-left:20px;"><label for="country"> Country </label></div><div style="width:300px; padding-right:10PX;">
												<select name=country id="country" style="width:250px;">
														<option value="United States">United States</option>
													<?php
														$list = count($country_list);
														for($cl = 0; $cl < $list; $cl++)
														{ ?>
														<option value="<?php print $country_list[$cl]; ?>"><?php print $country_list[$cl]; ?></option>
													<?php } ?>
													</select></div><div style="float:right; margin-top:-25px;"><span id="countryInfo"></span></div></p>
												 <p><div style="width:200px; float:left; padding-left:13px;"><label for="email">E-mail</label> </div><div style="width:300px; padding-right:10PX;"><input type=text size=26 id="email" name=email class="input-medium" /></div><div style="float:right;  margin-top:-25px; width:250px; padding-right:60px;"><span id="emailInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:32px;"><label for="phone"> Phone No.</label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=phone id="phone" class="input-medium" /></div><div style="float:right; margin-top:-25px; width:250px; padding-right:72px;"><span id="phoneInfo"></span></div></p><p></p>
												<p><div style="width:200px; float:left; padding-left:25px;"> <label for="alerts">Alert Pay*  </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 id="alerts" name=alert class="input-medium" /></div><div style="float:right; margin-top:-25px;"><span id="alertsInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:60px;"><label for="liberty">Liberty Reserve* </label></div><div style="width:300px; padding-right:10PX;"><input type=text id="liberty" size=26 name=liberty class="input-medium" /></div><div style="float:right; margin-top:-25px;"><span id="libertyInfo"></span></div></p>
												
											<!--	<p><div style="width:200px; float:left; padding-left:52px;"><label for="date">Date of Birth</label></div><div style="border:solid #CC0000 2px; width:300px; padding-right:10PX;"><input type=text id="date" size=26 name=dob class="input-medium flexy_datepicker_input"/></div><div style="float:right; margin-top:-25px;"><span id="dateInfo"></span></div></p>
												<p><div class="form_label" style="width:200px; float:left; padding-left:20px;"> Gender </div><div class="form_data" style="width:300px; padding-right:10PX;"><input type="radio" name=gender value="male" checked="checked" />	<strong>Male</strong><input type="radio" name=gender value="female" /><strong>Female</strong></div></p>
												<p><div style="width:200px; float:left; padding-left:-10px;"><label for="city">City </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=city id="city" class="input-medium" /></div><div style="float:right; margin-top:-25px;"><span id="cityInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:30px;"><label for="provience">Provience </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=provience id="provience" class="input-medium" /></div><div style="float:right; margin-top:-25px;"><span id="provienceInfo"></span></div></p>-->
												
												<p><div style="width:200px; float:left; padding-left:38px;"><label for="username"> User Name </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=username id="username" class="input-medium" /></div><div style="float:right; margin-top:-25px; width:250px; padding-right:40px;"><span id="usernameInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:30px;"><label for="pass1">Password</label>  </div><div style="width:300px; padding-right:10PX;"><input type="password" size=26 id="pass1" name=password class="input-medium" /></div><div style="float:right; margin-top:-25px; width:250px; padding-right:45px;"><span id="pass1Info"></span></div></p> 
												<p><div style=" width:200px; float:left; padding-left:74px;"><label for="pass2">Confirm Password</label></div><div style="width:300px; padding-right:10PX;"><input type="password" id="pass2" size=26 name=re_password class="input-medium" /></div><div style="float:right; margin-top:-25px; width:250px; padding-right:60px;"><span id="pass2Info"></span></div></p>	
												
												<!--<p><div style="width:200px; float:left; padding-left:72px;"><label for="email">Beneficiery Name</label> </div><div style="width:300px; padding-right:10PX;"><input type=text size=26 id="name" name=beneficiery_name class="input-medium" /></div><div style="float:right;"><span id="namelInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:39px;"><label for="alerts">Account No </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 id="phone" name=ac_no class="input-medium" /></div><div style="float:right;"><span id="alertsInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:5px;"><label for="phone">Bank</label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=bank id="phone" class="input-medium" /></div><div style="float:right;"><span id="phoneInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:17px;"><label for="liberty">Branch</label></div><div style="width:300px; padding-right:10PX;"><input type=text id="branch" size=26 name=branch class="input-medium" /></div><div style="float:right;"><span id="libertyInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:35px;"><label for="phone">Bank Code</label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=bank_code id="phone" class="input-medium" /></div><div style="float:right;"><span id="phoneInfo"></span></div></p>-->
				<div>
				<input id="send" type="submit" name="submit" value="submit" class="normal-button"/>
				</div>
				</form>
				
				
				
				</div> 
				
				<!--<script type="text/javascript" src="validation/jquery.js"></script>  
        		<script type="text/javascript" src="validation/validation.js"></script> -->
			</div>
			
				 <?php
			
		}			
		elseif(($_POST['submit'] == 'submit'))
		{
			if(!validateEmail($_POST['email']) || !validateUsername($_POST['username']) || !validatePhone($_POST['phone']) || !validatePasswords($_POST['password'], $_POST['re_password']) || !validateAdderss($_POST['address']) )
		 	{ ?>  
                    <div id="error">  
                        <ul>  
							<?php if(!validateUsername($_POST['username'])):?>  
								<li style="color:#CC3300";><strong>Invalid Username:</strong></li>  
							   <?php endif?> 
								<?php if(!validatePhone($_POST['phone'])):?>  
									<li style="color:#CC3300";><strong>Invalid Phone:</strong></li>  
								<?php endif?>  
								<?php if(!validateAdderss($_POST['address'])):?>  
									<li style="color:#CC3300";><strong>Invalid Address:</strong></li>  
								<?php endif?>  
									<?php if(!validateEmail($_POST['email'])):?>  
										<li style="color:#CC3300";><strong>Invalid E-mail:</strong></li>  
								<?php endif?>  
								<?php if(!validatePasswords($_POST['password'], $_POST['re_password'])):?>  
									<li style="color:#CC3300";><strong>Passwords are invalid:</strong></li>
								<?php endif?>  
                        </ul>  
                    </div>  
        <?php }
			else
			{  
					$type = "B";
					$virtual_par = $_REQUEST['virtual_parent'];
					$f_name =$_POST['f_name'];
					$l_name =$_POST['l_name'];
					$user_name = $f_name." ".$l_name;
					$dob =$_POST['dob'];
					$gender =$_POST['gender'];
					$address =$_POST['address'];
					$city =$_POST['city'];
					$provience =$_POST['provience'];
					$country =$_POST['country'];
					$email =$_POST['email'];
					$phone =$_POST['phone'];
					$username = $_POST['username'];
					$password =$_POST['password'];
					$alert = $_POST['alert'];
					$liberty =$_POST['liberty'];
					$re_password =$_POST['re_password'];
					$date = date('Y-m-d');
					$reg_mode =$_POST['reg_mode'];
					$reg_amount = $_SESSION['registration_amount'];	
					$user_pisition = $_REQUEST['user_pisition'];
					$number =2;
					
					$beneficiery_name =$_POST['beneficiery_name'];
					$ac_no = $_POST['ac_no'];
					$bank =$_POST['bank'];
					$branch = $_POST['branch'];
					$bank_code = $_POST['bank_code'];
					
					$real_parent_id = $from = $_SESSION['mlmproject_user_id'];
					$user_pin = mt_rand(100000, 999999);
					
					if($virtual_parent_condition == 1) // checking condition
					{
										
						$children = check_virtual_parent_position($virtual_par,$user_pisition);
						if($children == 0)
						{
							$chk = user_exist($username);
							if($chk >0)
							{
								echo "User name $username is already stored!";
							}
							else
							{ 
							
								$insert_q = query_execute_sqli("insert into users(parent_id, position, real_parent, address,  country, email, phone_no, username, password, date , type , user_pin , f_name, l_name , alert_email , liberty_email ) values('$virtual_par' , '$user_pisition', '$real_parent_id' , '$address' , '$country', '$email', '$phone', '$username', '$password', '$date' , '$type' , '$user_pin' , '$f_name', '$l_name' , '$alert' , '$liberty' )");
								
								
								$virtual_parent_username = get_user_name($virtual_par);
								$real_parent_username = get_user_name($real_parent_id);
								if($pos == 0) $user_position = "Left Power Leg";
								else $user_position = "Right Power Leg";
								
								//new registration message
								$title = "new User register";
								$to = $email;
								$db_msg = $email_welcome_message;
								include("function/full_message.php");
								$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
								$SMTPChat = $SMTPMail->SendMail();							
								//direct member message
								$real_parent_username = get_user_name($real_parent_id);
								//$new_username = $username;
								//$to = get_user_email($real_parent_id);
								//$db_msg = $direct_member_message;
								//include("function/full_message.php");
								//$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
								//$SMTPChat = $SMTPMail->SendMail();
															
								$user_id = get_new_user_id($username); //newlly entered user id
								
								//insert_child_in_left_right($user_id);
								$par_id = real_par($user_id);
								
								$user_id = get_new_user_id($username); //newlly entered user id
								$real_parent_username_log = $real_parent_username;
								include("function/logs_messages.php");
								data_logs($from,$data_log[3][0],$data_log[3][1],$log_type[2]);
								

								insert_wallet($user_id);
								data_logs($user_id,$data_log[5][0],$data_log[5][1],$log_type[4]);
																
								print "<font color=\"#00274F\" size=\"3\"><b>User Registration Successfully Copmleted !</b></font>";
							} 
							
						}
						else { print "Selected virtual parent already have two child !"; } 
					} 
			}	
		}
		else { print "There is some conflict!!"; }	
	}
	else
	{  
		$query = query_execute_sqli("select * from users where parent_id = '".$_SESSION['mlmproject_user_id']."' ");
		$num = mysqli_num_rows($query);
		if($num != 0)
		{
			 ?>
				<table width="500" border="0">
				<form name="user_position" action="index.php?page=user_registration" method="post"> 
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
										<option value="0">Left Power Leg</option>
										<option value="1">Right Power Leg</option>
									</select>	
								</td>
							  </tr>
							  <tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							  </tr>
							  <tr>
								<td colspan="2"><input type="submit" name="submit" value="Position" class="normal-button" /></td>
							  </tr>
		</table>
	<?php  }	
		else
		{
			$posit = 0;
			 ?>
			<div class="comment odd alt thread-odd thread-alt depth-1" style="width:90%">
			<!-- Form Code Start -->  
			<div id="form"><font color="#313162"> Yuu Are Registering on Left Power Leg of user Id : <?php print  $_SESSION['mlmproject_user_name']; ?></font>
			<form name="register" id="registrarionForm" action="index.php?page=user_registration" method="post"  >
			</div>
							<input type="hidden" size=26 name=real_parent value="<?php echo $_SESSION['mlmproject_user_id']; ?>" class="input-medium" /> 
							<input type="hidden" size=26 name=virtual_parent value="<?php echo $_SESSION['mlmproject_user_id']; ?>" class="input-medium" />
							<input type="hidden" size=26 name=user_pisition value="<?php echo $posit; ?>" class="input-medium" />
							<p><div style=" width:200px; float:left; padding-left:36px;"> <label for="name">First Name</label></div><div style="width:260px; padding-right:10px;"> <input type=text size=26 id="name" name=f_name class="input-medium" /></div></span><div style=" padding-right:75px; float:right;margin-top:-25px; width:250px"><span id="nameInfo"></span> </div></p> 
												<p><div style="width:200px; float:left; padding-left:35px;"><label for="l_name"> Last Name </label></div><div style="width:260px; padding-right:10PX;"><input type=text size=26 id="l_name" name=l_name class="input-medium" /></div><div style=" width:250px; float:right; margin-top:-25px;  padding-right:75px;"><span id="l_nameInfo"></span> </div></p> 
											<p><div style="width:200px; float:left; padding-left:20px;"> <label for="message">Address</label></div><div style="width:300px; padding-right:10PX;"><textarea name=address  id="message" style="height:50px; width:240px" /></textarea></div><div style="float:right; margin-top:-40px; width:250px; padding-right:80px;"><span id="messageInfo"></span></div></p>
												
												<p><div style="width:200px; float:left; padding-left:20px;"><label for="country"> Country </label></div><div style="width:300px; padding-right:10PX;">
												<select name=country id="country" style="width:250px;">
														<option value="United States">United States</option>
													<?php
														$list = count($country_list);
														for($cl = 0; $cl < $list; $cl++)
														{ ?>
														<option value="<?php print $country_list[$cl]; ?>"><?php print $country_list[$cl]; ?></option>
													<?php } ?>
													</select></div><div style="float:right; margin-top:-25px;"><span id="countryInfo"></span></div></p>
												 <p><div style="width:200px; float:left; padding-left:13px;"><label for="email">E-mail</label> </div><div style="width:300px; padding-right:10PX;"><input type=text size=26 id="email" name=email class="input-medium" /></div><div style="float:right;  margin-top:-25px; width:250px; padding-right:60px;"><span id="emailInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:32px;"><label for="phone"> Phone No.</label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=phone id="phone" class="input-medium" /></div><div style="float:right; margin-top:-25px; width:250px; padding-right:72px;"><span id="phoneInfo"></span></div></p><p></p>
												<p><div style="width:200px; float:left; padding-left:25px;"> <label for="alerts">Alert Pay*  </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 id="alerts" name=alert class="input-medium" /></div><div style="float:right; margin-top:-25px;"><span id="alertsInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:60px;"><label for="liberty">Liberty Reserve* </label></div><div style="width:300px; padding-right:10PX;"><input type=text id="liberty" size=26 name=liberty class="input-medium" /></div><div style="float:right; margin-top:-25px;"><span id="libertyInfo"></span></div></p>
											<!--	<p><div style="width:200px; float:left; padding-left:52px;"><label for="date">Date of Birth</label></div><div style="border:solid #CC0000 2px; width:300px; padding-right:10PX;"><input type=text id="date" size=26 name=dob class="input-medium flexy_datepicker_input"/></div><div style="float:right; margin-top:-25px;"><span id="dateInfo"></span></div></p>
												<p><div class="form_label" style="width:200px; float:left; padding-left:20px;"> Gender </div><div class="form_data" style="width:300px; padding-right:10PX;"><input type="radio" name=gender value="male" checked="checked" />	<strong>Male</strong><input type="radio" name=gender value="female" /><strong>Female</strong></div></p>
												<p><div style="width:200px; float:left; padding-left:-10px;"><label for="city">City </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=city id="city" class="input-medium" /></div><div style="float:right; margin-top:-25px;"><span id="cityInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:30px;"><label for="provience">Provience </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=provience id="provience" class="input-medium" /></div><div style="float:right; margin-top:-25px;"><span id="provienceInfo"></span></div></p>-->
												
												<p><div style="width:200px; float:left; padding-left:38px;"><label for="username"> User Name </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=username id="username" class="input-medium" /></div><div style="float:right; margin-top:-25px; width:250px; padding-right:40px;"><span id="usernameInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:30px;"><label for="pass1">Password</label>  </div><div style="width:300px; padding-right:10PX;"><input type="password" size=26 id="pass1" name=password class="input-medium" /></div><div style="float:right; margin-top:-25px; width:250px; padding-right:45px;"><span id="pass1Info"></span></div></p> 
												<p><div style=" width:200px; float:left; padding-left:74px;"><label for="pass2">Confirm Password</label></div><div style="width:300px; padding-right:10PX;"><input type="password" id="pass2" size=26 name=re_password class="input-medium" /></div><div style="float:right; margin-top:-25px; width:250px; padding-right:60px;"><span id="pass2Info"></span></div></p>	
												
												<!--<p><div style="width:200px; float:left; padding-left:72px;"><label for="email">Beneficiery Name</label> </div><div style="width:300px; padding-right:10PX;"><input type=text size=26 id="name" name=beneficiery_name class="input-medium" /></div><div style="float:right;"><span id="namelInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:39px;"><label for="alerts">Account No </label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 id="phone" name=ac_no class="input-medium" /></div><div style="float:right;"><span id="alertsInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:5px;"><label for="phone">Bank</label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=bank id="phone" class="input-medium" /></div><div style="float:right;"><span id="phoneInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:17px;"><label for="liberty">Branch</label></div><div style="width:300px; padding-right:10PX;"><input type=text id="branch" size=26 name=branch class="input-medium" /></div><div style="float:right;"><span id="libertyInfo"></span></div></p>
												<p><div style="width:200px; float:left; padding-left:35px;"><label for="phone">Bank Code</label></div><div style="width:300px; padding-right:10PX;"><input type=text size=26 name=bank_code id="phone" class="input-medium" /></div><div style="float:right;"><span id="phoneInfo"></span></div></p>-->
												
							
							<div>
							<input type="submit" name="submit" value="submit" class="normal-button"  />
							</div>
							</form>
							</div>
							</div> 
				
				<!--<script type="text/javascript" src="validation/jquery.js"></script>  
        		<script type="text/javascript" src="validation/validation.js"></script> -->
			</div>
						
		<?php	}
	}
	
function reduce_reg_fees($id,$reg_amount)    
{
	$wallet_q = query_execute_sqli("select * from wallet where id = '$id' ");
	while($r = mysqli_fetch_array($wallet_q))
	{
		$available_balance = $r['amount'];
	}
	$left = $available_balance-$reg_amount;
	query_execute_sqli("update wallet set amount = '$left' where id = '$id' ");

}	
		

