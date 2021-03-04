<?php
include('../../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
require_once "../function/formvalidator.php";
include("../function/setting.php");
include("../function/functions.php");
require_once("../function/country_list.php");

$admin_id = $_SESSION['intrade_admin_id'];

if(isset($_SESSION['edit_userid'])){
	$_POST['submit'] = 'Submit';
	$_POST['user_name'] = $_SESSION['edit_userid'];
	unset($_SESSION['edit_userid']);
}
if(isset($_POST['submit']))
{
	if($_POST['submit'] == 'Submit' or $_POST['submit'] == 'Update')
	{
		$u_name = $_POST['user_name'];
		$sql = "select t1.*,t2.remarks from users t1
		left join profile_record t2 ON t1.id_user = t2.member_id
		where t1.username = '$u_name' ";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		if($num == 0){ echo "<B class='text-danger'>Please Enter right Username! </B>"; }
		else{
			while($row = mysqli_fetch_array($query)){
				$id_user = $row['id_user'];
				$f_name1 = $row['f_name'];
				$l_name1 = $row['l_name'];
				//$name = ucwords($row['f_name'].' '.$row['l_name']);
				$gender = $row['gender'];
				$dob = $row['dob'];
				$phone1 = $row['phone_no'];
				$city = $row['city'];
				$country = $row['country'];
				$state = $row['state'];
				$father_husband = $row['father_husband'];
				$pincode =$row['pincode'];
				
				$fb_id = $row['fb_id'];
				$whatsapp = $row['whatsapp'];
				$skype_id = $row['skype_id'];
				$nominee = $row['nominee'];
				$relation = $row['relation'];
				
				//$username = $row['username'];
				$password1 = $row['password'];
				$remarks = $row['remarks'];
				
				$date = $row['date'];
				
				$email1 = $row['email'];
				$address = $row['address'];

				$ac_no = $row['ac_no'];
				$bit_ac_no = $row['bit_ac_no'];
			}
			$before_update = $f_name1." ".$l_name1."<br />".$phone1."<br />".$email1."<br />".$password1;
			
			if($_POST['submit'] == 'Update'){
				/*$validator = new FormValidator();
				$validator->addValidation("f_name","req","Please fill in First Name");
				$validator->addValidation("l_name","req","Please fill in Last Name");
				$validator->addValidation("dob","req","Please fill Date of Birth");
				$validator->addValidation("gender","req","Please fill in Gender");
				$validator->addValidation("address","req","Please fill in Address");
				$validator->addValidation("city","req","Please fill City");
				$validator->addValidation("provience","req","Please fill in Provience");
				$validator->addValidation("country","req","Please fill in Country");
				$validator->addValidation("phone","req","Please fill in Phone");*/
			
				if(1)//$validator->ValidateForm()
				{ 
					$id = $_POST['user_id'];
					$gender = $_POST['gender'];
					$dob = $_POST['dob'];
					$phone_no = $_POST['phone'];
					$city = $_POST['city'];
					$country = $_POST['country'];
					$state = $_POST['state'];
					$pincode = $_POST['pincode'];
					
					$father_husband = $_POST['father_husband'];
		
					$email = $_POST['email'];
					$remarks = $_POST['remarks'];
					
					//$username = $_POST['username'];
					$password = $_POST['password'];
					$f_name = $_POST['f_name'];
					$l_name = $_POST['l_name'];
					
					$fb_id = $_POST['fb_id'];
					$whatsapp = $_POST['whatsapp'];
					$skype_id = $_POST['skype_id'];
					$nominee = $_POST['nominee'];
					$relation = $_POST['relation'];
					
					//$full_name = explode(" ", $_POST['name']);
					//$f_name = $full_name[0];
					//$l_name = $full_name[1]." ".$full_name[2];
					$after_update = $f_name." ".$l_name."<br />".$phone_no."<br />".$email."<br />".$password;
					
					$SQWL = "SELECT * FROM users WHERE f_name = '$f_name'";
					$quer = query_execute_sqli($SQWL);
					$num = mysqli_num_rows($quer);
					if($num == 0){
						
						$sqlk = "SELECT * FROM kyc WHERE user_id = '$id'";
						$query = query_execute_sqli($sqlk);
						$nums = mysqli_num_rows($query);
						if($nums > 0){
							while($ro = mysqli_fetch_array($query))
							{
								$pan_card = $ro['pan_card'];
								$id_proof = $ro['id_proof'];
								$photo = $ro['photo'];
								$addr_proof = $ro['address_proof'];
								$chq_passbook = $ro['cancl_chq_passbook'];
							}
							unlink($kyc_docs_path.$pan_card);
							unlink($kyc_docs_path.$id_proof);
							unlink($kyc_docs_path.$photo);
							unlink($kyc_docs_path.$addr_proof);
							unlink($kyc_docs_path.$chq_passbook);
							
							query_execute_sqli("DELETE FROM `kyc` WHERE user_id = '$id'");
							query_execute_sqli("ALTER TABLE `kyc` DROP `id`");
							$sqlk = "ALTER TABLE `kyc` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST , ADD PRIMARY 
							KEY (id)";
							query_execute_sqli($sqlk);
						}
					}
					
					$SQL = "INSERT INTO profile_record (member_id, admin_id, f_name, l_name, phone_no, email, 
					beneficiery_name, bank_ac, bank_name, ifsc_code, swift_code,`date`)
					(SELECT id_user, '$admin_id', f_name, l_name, phone_no, email, beneficiery_name, 
					bank_ac, bank_name, ifsc_code, swift_code,'$systems_date' from users WHERE id_user = '$id')";
					query_execute_sqli($SQL);
					$insert_id = get_mysqli_insert_id();
					
					$sqlk = "UPDATE profile_record SET before_update='$before_update', after_update='$after_update', 
					remarks = '$remarks' WHERE id = '$insert_id'";
					query_execute_sqli($sqlk);
					
					$sql = "UPDATE users SET f_name='$f_name', l_name='$l_name', father_husband='$father_husband', 
					gender = '$gender', country = '$country' , state = '$state' , city = '$city', email = '$email',
					pincode = '$pincode', phone_no = '$phone_no', fb_id = '$fb_id',
		            whatsapp = '$whatsapp', skype_id = '$skype_id', nominee = '$nominee', relation = '$relation' WHERE id_user = '$id'";
					query_execute_sqli($sql);
					
					/*query_execute_sqli("insert into profile_record (admin_id) values('$admin_id')");
					$insert_id = get_mysqli_insert_id();
					
					$SQL = "UPDATE profile_record SET admin_id='$admin_id',member_id='$id',f_name = '$f_name', l_name = '$l_name', phone_no = '$phone_no',  email = '$email' , password = '$password',beneficiery_name = '$benf_name' , bank_ac = '$bank_ac' , bank_name = '$bank_name' , ifsc_code = '$ifsc_code', swift_code = '$swift_code',`date`='$systems_date', before_update, after_update where id='$insert_id'";
					query_execute_sqli($SQL);
*/					
					$date = date('Y-m-d');
					$username = get_user_name($id);
					$updated_by = $username." by Admin";
					include("../function/logs_messages.php");
					data_logs($id,$data_log[1][0],$data_log[1][1],$log_type[1]);
					//echo "<B class='text-success'>Successfully Updated</B>";
					
					$_SESSION['edit_userid'] = $u_name;
					?> 
					<script>
						alert("Successfully Updated"); window.location ="index.php?page=<?=$val?>";
						//alert("Successfully Updated"); window.location ="index.php?page=<?=$val?>&submit=Submit&user_name=<?=$u_name?>";
					</script> 
					<?php 
				}	
				else
				{
					$submit = 0;
					echo "<B class='text-danger'>Validation Errors:</B>";
					$error_hash = $validator->GetErrors();
					foreach($error_hash as $inpname => $inp_err)
					{
						echo "<B class='text-danger'>$inpname : $inp_err</B>";
					}        
				}	
			}
			?>
			<form name="register" action="" method="post">
			<input type="hidden" name="user_id" value="<?=$id_user?>" />
			<input type="hidden" name="user_name" value="<?=$u_name?>" />
			<table class="table table-bordered">
				<thead>
				<tr>
					<th colspan="4">
						Profile Information Of User ID <i class="fa fa-arrow-right"></i>  
						<B class="text-danger"><?=$u_name?></B> 
					</th>
				</tr>
				</thead>
				<tr>
					<th>First Name</th>
					<td><input type="text" name="f_name" value="<?=$f_name1?>" class="form-control" /></td>
					<th>Last Name</th>
					<td><input type="text" name="l_name" value="<?=$l_name1?>" class="form-control" /></td>
				</tr>
				<tr>
					<th>Gender </th>
					<td>
						<input type="radio" name="gender" value="male" <?php if($gender == 'male') { ?>  checked="checked" <?php } ?> /> &nbsp;Male
						<input type="radio" name="gender" value="female" <?php if($gender == 'female') { ?>  checked="checked" <?php } ?> style="width:50px;" /> &nbsp;Female
					</td>
					<th>Country</th>
					<td>
						<select name="country" class="form-control">
							<option value="">Select One</option>
							<?php
							$list = count($country_list);
							for($cl = 0; $cl < $list; $cl++)
							{ ?>
							<option value="<?=$country_list[$cl]?>" <?php if($country_list[$cl] == $country) { ?> selected="selected" <?php } ?>><?=$country_list[$cl];?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>State</th>
					<td><input type="text" name="state" value="<?=$state?>" class="form-control" /></td>
					<th>City</th>
					<td><input type="text" name="city" value="<?=$city?>" class="form-control" /></td>
				</tr>
				<!--<tr>
					<th>Zip Code</th>
					<td><input type="text" name="pincode" value="<?=$pincode?>" class="form-control" /></td>
				</tr>-->
				<thead><tr><th colspan="4">Contact Details</th></tr></thead>
				<tr>
					<th>E-mail</th>
					<td><input type="text" name="email" value="<?=$email1?>" class="form-control" /></td>
					<th>Phone Number</th>
					<td>
						<input type="text" name="phone" value="<?=$phone1?>" class="form-control" maxlength="10" />
					</td>
				</tr>
				<!--<thead><tr><th colspan="4">Social Media Details</th></tr></thead>
				<tr>
					<th>Facebook ID</th>
					<td><input type="text" name="fb_id" value="<?=$fb_id?>" class="form-control" /></td>
					<th>whatsapp</th>
					<td><input type="text" name="whatsapp" value="<?=$whatsapp?>" class="form-control" /></td>
				</tr>
				<tr>
					<th>Skype ID</th>
					<td><input type="text" name="skype_id" value="<?=$skype_id?>" class="form-control" /></td>
				</tr>
				<thead><tr><th colspan="4">Nominee Details</th></tr></thead>
				<tr>
					<th>Nominee</th>
					<td><input type="text" name="nominee" value="<?=$nominee?>" class="form-control" /></td>
					<th>Relation</th>
					<td><input type="text" name="relation" value="<?=$relation?>" class="form-control" /></td>
				</tr>
				<tr>
					<th>Remarks</th>
					<td colspan="3"><textarea name="remarks" class="form-control"><?=$remarks?></textarea></td>
				</tr>-->
				<tr>
					<td colspan="4" class="text-center">
						<input type="submit" name="submit" value="Update" class="btn btn-info" onclick="javascript:return confirm(&quot; Are You Sure to update Details &quot;)" id="update" />
					</td>
				</tr>
			</table>
			</form>
<?php					
		}
	}		
	elseif($_POST['submit'] == 'Update')
	{
		$validator = new FormValidator();
		$validator->addValidation("f_name","req","Please fill in First Name");
		$validator->addValidation("l_name","req","Please fill in Last Name");
		/*$validator->addValidation("dob","req","Please fill Date of Birth");
		$validator->addValidation("gender","req","Please fill in Gender");
		$validator->addValidation("address","req","Please fill in Address");
		$validator->addValidation("city","req","Please fill City");
		$validator->addValidation("provience","req","Please fill in Provience");
		$validator->addValidation("country","req","Please fill in Country");*/
		$validator->addValidation("phone","req","Please fill in Phone");
	
		if($validator->ValidateForm())
		{
	
			$benf_name =$_POST['benf_name'];
			$bank_ac =$_POST['bank_ac'];
			$bank_name = $_POST['bank_name'];
			$ifsc_code =$_POST['ifsc_code'];
			$swift_code = $_POST['swift_code'];
			
			$id = $_POST['user_id'];
			$f_name = $_POST['f_name'];
			$l_name = $_POST['l_name'];
			$gender = $_POST['gender'];
			$dob = $_POST['dob'];
			$phone_no = $_POST['phone'];
			$city = $_POST['city'];
			$country = $_POST['country'];
			$provience = $_POST['provience'];
			$address = $_POST['address'];
			$nominee = $_POST['nominee'];
			$n_relation = $_POST['n_relation'];

			$email = $_POST['email'];
			$alert_email = $_POST['alert_email'];
			$liberty_email = $_POST['liberty_email'];
			//$username = $_POST['username'];
			$password = $_POST['password'];
			$insert_q = query_execute_sqli("UPDATE users SET f_name = '$f_name', l_name = '$l_name', phone_no = '$phone_no',  email = '$email' , password = '$password',beneficiery_name = '$benf_name' , bank_ac = '$bank_ac' , bank_name = '$bank_name' , ifsc_code = '$ifsc_code', swift_code = '$swift_code'  WHERE id_user = '$id'");
			query_execute_sqli("insert into profile_record (admin_id) values('$admin_id')");
			$insert_id = get_mysqli_insert_id();
			$insert_q = query_execute_sqli("UPDATE profile_record SET admin_id='$admin_id',member_id='$id',f_name = '$f_name', l_name = '$l_name', phone_no = '$phone_no',  email = '$email' , password = '$password',beneficiery_name = '$benf_name' , bank_ac = '$bank_ac' , bank_name = '$bank_name' , ifsc_code = '$ifsc_code', swift_code = '$swift_code',`date`='$systems_date' where id='$insert_id'");
			
			$date = date('Y-m-d');
			$username = get_user_name($id);
			$updated_by = $username." Your self";
			include("../function/logs_messages.php");
			data_logs($id,$data_log[1][0],$data_log[1][1],$log_type[1]);
			echo "<B class='text-success'>Successfully Updated</B>";
			
		}	
		else
		{
			$submit = 0;
			echo "<B class='text-danger'>Validation Errors:</B>";
			$error_hash = $validator->GetErrors();
			foreach($error_hash as $inpname => $inp_err)
			{
				echo "<B class='text-danger'>$inpname : $inp_err</B>";
			}        
		}	
	}
}	
else
{ ?> 

<form action="" method="post">
<table class="table table-bordered">
	<tr><thead><th colspan="3">Enter Information</th></thead></tr>
	<tr>
		<th>Enter Member User ID</th>
		<td><input type="text" name="user_name" class="form-control" /></td>
		<td><input type="submit" name="submit" value="Submit" class="btn btn-info" /></td>
	</tr>
</table>
</form>
<?php  
}  ?>

