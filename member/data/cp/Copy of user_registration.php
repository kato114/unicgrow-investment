<?php  
        require_once("validation/validation.php");  
    ?> 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">  
    <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">  
    <head>  
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />  
        <title>yensdesign.com - Validate Forms using PHP and jQuery</title>  
        <!--<link rel="stylesheet" href="data/validation/css/general.css" type="text/css" media="screen" />  -->
		 
    </head>  
    <body>  
<?php
session_start();
	require_once("config.php");
	include("condition.php");
	include("function/setting.php");
	
	require_once "function/formvalidator.php";
	include("function/virtual_parent.php");
	include("function/send_mail.php");
	include("function/e_pin.php");
	
	include("function/income.php");
//include("function/left_right.php");
include("function/insert_into_wallet.php");
include("function/check_income_condition.php");
include("function/direct_income.php");

include("function/pair_point_calculation.php");

$id = $_SESSION['mlmproject_user_id'];
		
	if(isset($_POST['submit']))
	{
		if($_POST['submit'] == 'ckeck')
		{ 
			$wallet_amount = $_REQUEST['wallet_amount'];
			$user_pin = $_REQUEST['user_pin'];
			$reg_amount = 0;
			$_SESSION['registration_amount'] = $reg_amount;
			$chk_q = query_execute_sqli("select * from users where id_user = '$id' and user_pin = '$user_pin' ");
			
			$num = mysqli_num_rows($chk_q);
			if($num > 0)
				if($minimum_registration_fees <= $reg_amount)
				{
					if($wallet_amount >= $reg_amount)
					{
						$board_virtual_par = $_REQUEST['vp'];
						$query = query_execute_sqli("select * from users where parent_id = '".$_SESSION['mlmproject_user_id']."' ");
						$num = mysqli_num_rows($query);
						if($num != 0 and $board_virtual_par == 0)
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
							<form name="register" id="customForm" action="index.php?page=user_registration" method="post"  >
							</div>
							<input type="hidden" size=26 name=real_parent value="<?php echo $_SESSION['mlmproject_user_id']; ?>" class="input-medium" /> 
							<input type="hidden" size=26 name=virtual_parent value="<?php if($board_virtual_par == 0) echo $_SESSION['mlmproject_user_id']; else print $board_virtual_par; ?>" class="input-medium" />
							<label for="name">Name</label> <input type=text size=26 id="name" name=f_name class="input-medium" /><span id="nameInfo"></span> 
				<label for="l_name"> Last Name </label><input type=text size=26 id="l_name" name=l_name class="input-medium" /><span id="l_nameInfo"></span> 
				<label for="date">Date of Birth</label><input type=text id="date" size=26 name=dob class="input-medium flexy_datepicker_input"/><span id="dateInfo"></span>
				<div class="form_label"> Gender </div><div class="form_data"><input type="radio" name=gender value="male" checked="checked" />	<strong>Male</strong><input type="radio" name=gender value="female" /><strong>Female</strong></div>
				 <label for="message">Address</label><textarea name=address  id="message" style="height:50px; width:240px" /></textarea><span id="messageInfo"></span>
				<label for="city">City </label><input type=text size=26 name=city id="city" class="input-medium" /><span id="cityInfo"></span>
				<label for="provience">Provience </label><input type=text size=26 name=provience id="provience" class="input-medium" /><span id="provienceInfo"></span>
				<label for="country"> Country </label><input type=text size=26 name=country id="country" class="input-medium" /><span id="countryInfo"></span>
				 <label for="email">E-mail</label> <input type=text size=26 id="email" name=email class="input-medium" /><span id="emailInfo">Valid E-mail please, you will need it to log in!</span>
				 <label for="alerts">Alert Pay Account </label><input type=text size=26 id="alerts" name=alert class="input-medium" /><span id="alertsInfo"></span>
					<label for="liberty">Liberty Reserve Account</label><input type=text id="liberty" size=26 name=liberty class="input-medium" /><span id="libertyInfo"></span>
				<label for="phone"> Phone No.</label><input type=text size=26 name=phone id="phone" class="input-medium" /><span id="phoneInfo"></span>
				
				<label for="email">Beneficiery Name</label> <input type=text size=26 id="name" name=beneficiery_name class="input-medium" /><span id="namelInfo"></span>
				<label for="alerts">Account No </label><input type=text size=26 id="phone" name=ac_no class="input-medium" /><span id="alertsInfo"></span>
				<label for="phone">Bank</label><input type=text size=26 name=bank id="phone" class="input-medium" /><span id="phoneInfo"></span>
				<label for="liberty">Branch</label><input type=text id="branch" size=26 name=branch class="input-medium" /><span id="libertyInfo"></span>
				<label for="phone">Bank Code</label><input type=text size=26 name=bank_code id="phone" class="input-medium" /><span id="phoneInfo"></span>
				
				<label for="username"> User Name </label><input type=text size=26 name=username id="username" class="input-medium" /><span id="usernameInfo"></span>
				<label for="pass1">Password</label>  <input type="password" size=26 id="pass1" name=password class="input-medium" /><span id="pass1Info">At least 5 characters: letters, numbers and '_'</span> 
				<label for="pass2">Confirm Password</label><input type="password" id="pass2" size=26 name=re_password class="input-medium" /><span id="pass2Info">Confirm password</span>
				<div>
							<input type="submit" name="submit" value="submit" class="button" />
							</div>
							</form>
							</div>
						
					<?php	}
					}
					else { print "Please Enter Less amount for registeration !"; }
				}
				else { print "Please Enter minimun ".$minimum_registration_fees." for Registration !"; }
			else
			{
				print "Sorry Your Pin is Wrong or user , Please enter correct Pin.";
			}	
			
		}
		elseif($_POST['submit'] == 'Position')
		{ 
			$par_position = $_REQUEST['position'];
		?>
			<div class="comment odd alt thread-odd thread-alt depth-1" style="width:90%">
				<!-- Form Code Start -->  
				
				
				
				
				
				<div id="container"> 
				<form name="form" id="registrarionForm" action="index.php?page=user_registration" method="post"  >
				
				<?php 
				//$child = geting_virtual_parent($_SESSION['mlmproject_user_id']);
				$child = geting_virtual_parent_with_position($_SESSION['mlmproject_user_id'],$par_position);
				if($child > 0)
				{ ?>
					<label for="virtual_parent"> Virtual Parent List</label>
						<select name="virtual_parent" id="virtual_parent" >
							
						<?php
							//$virtual_par =geting_all_blank_position($_SESSION['mlmproject_user_id']);
							$virtual_par =geting_all_blank_position_with_adding_position($_SESSION['mlmproject_user_id'],$par_position);
							$count = count($virtual_par);
							for($i = 0; $i <$count; $i++)
							{ 
								$user_name = get_user_name($virtual_par[$i]);
								$position = get_user_pos($virtual_par[$i]);
							?> 
								<option value="<?php echo $virtual_par[$i]; ?>" ><?php echo $user_name." (".$position.")"; ?></option>
							<?php } ?>
						</select>	
					<span id="virtualparentInfo"></span> 
				<?php }
				else{ ?>
				 <input type="hidden" size=26 name=virtual_parent value="<?php echo $_SESSION['mlmproject_user_id']; ?>" class="input-medium" /> <?php } ?>
				<label for="name">Name</label> <input type=text size=26 id="name" name=f_name class="input-medium" /><span id="nameInfo"></span> 
				<label for="l_name"> Last Name </label><input type=text size=26 id="l_name" name=l_name class="input-medium" /><span id="l_nameInfo"></span> 
				<label for="date">Date of Birth</label><input type=text id="date" size=26 name=dob class="input-medium flexy_datepicker_input"/><span id="dateInfo"></span>
				<div class="form_label"> Gender </div><div class="form_data"><input type="radio" name=gender value="male" checked="checked" />	<strong>Male</strong><input type="radio" name=gender value="female" /><strong>Female</strong></div>
				 <label for="message">Address</label><textarea name=address  id="message" style="height:50px; width:240px" /></textarea><span id="messageInfo"></span>
				<label for="city">City </label><input type=text size=26 name=city id="city" class="input-medium" /><span id="cityInfo"></span>
				<label for="provience">Provience </label><input type=text size=26 name=provience id="provience" class="input-medium" /><span id="provienceInfo"></span>
				<label for="country"> Country </label><input type=text size=26 name=country id="country" class="input-medium" /><span id="countryInfo"></span>
				 <label for="email">E-mail</label> <input type=text size=26 id="email" name=email class="input-medium" /><span id="emailInfo">Valid E-mail please, you will need it to log in!</span>
				 <label for="alerts">Alert Pay Account </label><input type=text size=26 id="alerts" name=alert class="input-medium" /><span id="alertsInfo"></span>
					<label for="liberty">Liberty Reserve Account</label><input type=text id="liberty" size=26 name=liberty class="input-medium" /><span id="libertyInfo"></span>
				<label for="phone"> Phone No.</label><input type=text size=26 name=phone id="phone" class="input-medium" /><span id="phoneInfo"></span>
				
				<label for="email">Beneficiery Name</label> <input type=text size=26 id="name" name=beneficiery_name class="input-medium" /><span id="namelInfo"></span>
				<label for="alerts">Account No </label><input type=text size=26 id="phone" name=ac_no class="input-medium" /><span id="alertsInfo"></span>
				<label for="phone">Bank</label><input type=text size=26 name=bank id="phone" class="input-medium" /><span id="phoneInfo"></span>
				<label for="liberty">Branch</label><input type=text id="branch" size=26 name=branch class="input-medium" /><span id="libertyInfo"></span>
				<label for="phone">Bank Code</label><input type=text size=26 name=bank_code id="phone" class="input-medium" /><span id="phoneInfo"></span>
				
				<label for="username"> User Name </div><div class="form_data"><input type=text size=26 name=username id="username" class="input-medium" /><span id="usernameInfo"></span>
				<label for="pass1">Password</label>  <input type="password" size=26 id="pass1" name=password class="input-medium" /><span id="pass1Info">At least 5 characters: letters, numbers and '_'</span> 
				<label for="pass2">Confirm Password</label><input type="password" id="pass2" size=26 name=re_password class="input-medium" /><span id="pass2Info">Confirm password</span><br />
				<div>
				<input id="send" type="submit" name="submit" value="submit" class="button" />
				</div>
				</form>
				
				
				
				</div> 
				
				<script type="text/javascript" src="validation/jquery.js"></script>  
        		<script type="text/javascript" src="validation/validation.js"></script> 
			</div>
			
				 <?php
			
		}			
		elseif(($_POST['submit'] == 'submit'))
		{
			if(!validateEmail($_POST['email']) || !validateUsername($_POST['username']) || !validatePhone($_POST['phone']) || !validatePasswords($_POST['password'], $_POST['re_password']) || !validateEmail($_POST['alert']) || !validateEmail($_POST['liberty']) )
		 	{ ?>  
                    <div id="error">  
                        <ul>  
							<?php if(!validateUsername($_POST['username'])):?>  
                                <li style="color:#CC3300";><strong>Invalid Username:</strong></li>  
                            <?php endif?> 
							<?php if(!validatePhone($_POST['phone'])):?>  
                                <li style="color:#CC3300";><strong>Invalid Phone:</strong></li>  
                            <?php endif?>  
							<?php if(!validateEmail($_POST['alert'])):?>  
                                <li style="color:#CC3300";><strong>Invalid alert Email:</strong></li>  
                            <?php endif?> 
							<?php if(!validateEmail($_POST['liberty'])):?>  
                                <li style="color:#CC3300";><strong>Invalid Liberty Email:</strong></li>  
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
			
				$validator = new FormValidator();
				$validator->addValidation("f_name","req","Please fill in First Name");
				$validator->addValidation("l_name","req","Please fill in Last Name");
				$validator->addValidation("dob","req","Please fill Date of Birth");
				$validator->addValidation("gender","req","Please fill in Gender");
				$validator->addValidation("address","req","Please fill in Address");
				$validator->addValidation("city","req","Please fill City");
				$validator->addValidation("provience","req","Please fill in Provience");
				$validator->addValidation("country","req","Please fill in Country");
				$validator->addValidation("email","email","Please Enter a valid Email Id");
				$validator->addValidation("email","req","Please fill in Email");
				$validator->addValidation("phone","req","Please fill in Phone");
				$validator->addValidation("username","req","Please fill in username");
				$validator->addValidation("password","req","Please fill password");
				$validator->addValidation("re_password","req","Please fill re_password");
				$password =$_POST['password'];
				$re_password =$_POST['re_password'];
				if($password != $re_password)
				{	print "please enter same password in both field!"; die; } 
			
				if($validator->ValidateForm())
				{
					//Validation success. 
					//Here we can proceed with processing the form 
					//(like sending email, saving to Database etc)
					// In this example, we just display a message
					//echo "<h2>Validation Success!</h2>";
					//$show_form=false;
				  /*for($i = 1; $i <10; $i++)
				  {*/
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
										
						$children = geting_virtual_parent($virtual_par);
						if($children < 2)
						{
						 
							$pos = $children;
							$chk = user_exist($username);
							if($chk >0)
							{
								echo "User name $username is already stored!";
							}
							else
							{ 
						 
				//				$reg_type = get_registration_type();
							
								$insert_q = query_execute_sqli("insert into users(parent_id, position, real_parent, f_name, l_name, dob, gender, address, city, activate_date , provience, country, email, phone_no, username, password, step , date , type , user_pin , alert_email , liberty_email , beneficiery_name , ac_no , bank , branch , bank_code) values('$virtual_par' , '$pos', '$real_parent_id' ,'$f_name', '$l_name', '$dob', '$gender', '$address', '$city', '$date' , '$provience', '$country', '$email', '$phone', '$username', '$password', '$step' , '$date' , '$type' , '$user_pin' , '$alert' , '$liberty' , '$beneficiery_name' , '$ac_no' , '$bank' , '$branch' , '$bank_code' )");
								
								
								
								//new registration message
								//$title = "new User register";
								//$to = $email;
								//$db_msg = $email_welcome_message;
								//include("function/full_message.php");
								//$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
								//$SMTPChat = $SMTPMail->SendMail();
								
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
				else				
				{
					echo "<B>Validation Errors:</B>";
			
					$error_hash = $validator->GetErrors();
					foreach($error_hash as $inpname => $inp_err)
					{
						echo "<p>$inpname : $inp_err</p>\n";
					}        
				}
			}	
		}
		else { print "There is some conflict!!"; }	
	}
	else
	{  
	?>
		<div class="comment odd alt thread-odd thread-alt depth-1" style="width:90%">
				<!-- Form Code Start -->  
				<div id="form"> <?php $mg=$_REQUEST[mg]; echo "<h2>".$mg."</h2>"; ?>
				<form name="register" id="customForm" action="index.php?page=user_registration" method="post"  >
				<input type="hidden" name="wallet_amount" value="<?php print $available_balance; ?>"
				<?php
				$virtual_par = $_REQUEST['vp']; 
				if($virtual_par == '')
					$virtual_par = 0;
				?>
				<input type="hidden" name="vp" value="<?php print $virtual_par; ?>"  />
				</div>

				<div class="form_label"> <strong>Enter Transaction Password</strong> </div><div class="form_data">
					<input type="text" name="user_pin"  />	
				</div><br />
				
					<div id="submit">
					<input type="submit" name="submit" value="ckeck" class="button" />
					</div>
					</form>
					</div>
	<?php   }	
	
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
		

