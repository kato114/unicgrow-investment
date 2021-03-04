<?php
include('../security_web_validation.php');
?>
<?php
session_start();
require_once("config.php");
include("condition.php");
require_once("validation/validation.php"); 
include("function/setting.php");

include("function/send_mail.php");
require_once("function/country_list.php");

$login_id = $id = $_SESSION['mlmproject_user_id'];

$allowedfiletypes = array("jpeg","jpg","png","gif");
$uploadfolder = $user_profile_folder;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;

if(isset($_SESSION['edit_succ']))
{
	echo $_SESSION['edit_succ'];
	unset($_SESSION['edit_succ']);	
}

if(isset($_POST['change_pic'])){
	$photo = $_REQUEST['photo'];
	$unique_time = time();
	$unique_name =	"CN".$unique_time;
	$uploadfilename = $_FILES['photo']['name'];
	
	if(!empty($_FILES['photo']['name'])){
		$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
		
		if(!in_array($fileext,$allowedfiletypes)){ echo "<B class='text-danger'>Invalid Extension</B>"; }
		else{
			$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
			if (copy($_FILES['photo']['tmp_name'], $fulluploadfilename)){ 
				$unique_name = $unique_name.".".$fileext;
	
				$sql = "UPDATE users SET image = '$unique_name' WHERE id_user = '$login_id'";
				query_execute_sqli($sql);
					
				$_SESSION['edit_succ']="<B class='text-success'>Profile Picture Updated Successfully !!</B>";
				
				?> <script>window.location = "index.php?page=edit-profile";</script> <?php
			}
		}
	}
}


if(isset($_POST['update'])){
	$user_pin = $_POST['sec_code'];
	/*$pass_num = 0;
	$sql = "SELECT password FROM users WHERE id_user ='$login_id' AND password = '$user_pin' ";
	$get_security_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
	if(trim($user_pin) == trim($get_security_pass)){ $pass_num = 1; }*/ 
	
	//if($_SESSION['rand_code'] == $_POST['sec_code']){
	//if($pass_num > 0){
		$before_update = $_POST['before_update'];
		$title_name = $_POST['title_name'];
		$name = $_POST['name'];
		$f_name = $_POST['f_name'];
		$l_name = $_POST['l_name'];
		/*$full_name = explode(" ", $name);
		$f_name = $full_name[0];
		$l_name = $full_name[1]." ".$full_name[2];
		$father_husband = $_POST['father_husband'];*/
		$gender = $_POST['gender'];
		$country = $_POST['country'];
		$state = $_POST['state'];
		$city = $_POST['city'];
		$pincode = $_POST['pincode'];
		
		$phone_no = $_POST['phone_no'];
		$email = $_POST['email'];
		
		$nominee = $_POST['nominee'];
		$relation = $_POST['relation'];
		$nominee_dob = date('Y-m-d', strtotime($_POST['n_dob']));
		$nominee_cntno = $_POST['nominee_cntno'];
		
		$address = $_POST['address'];
		
		$ac_no = $_POST['ac_no'];
		$dob = $_POST['dob'];
		$pan_no = $_POST['pan_no'];
		
		$benf_name = $_POST['benf_name'];
		$power_leg = $_POST['power_leg'] == "" ? "" : ",power_leg=".($_POST['power_leg']-1);
		
		$bank_name = $_POST['bank_name'];
		$bank_country = $_POST['bank_country'];
		$bank_ac = $_POST['bank_ac'];
		$swift_code = $_POST['swift_code'];
		$ifsc_code = $_POST['ifsc_code'];
		$bank_info = $_POST['bank_info'];
		
		$fb_id = $_POST['fb_id'];
		$whatsapp = $_POST['whatsapp'];
		$skype_id = $_POST['skype_id'];
		
		$photo = $_REQUEST['photo'];
		$unique_time = time();
		$unique_name =	"CN".$unique_time;
		$uploadfilename = $_FILES['photo']['name'];
		
		$query = query_execute_sqli("SELECT image FROM users WHERE id_user = '$id' ");
		$row = mysqli_fetch_array($query);
		$image = $row[0];
		
		if(!empty($_FILES['photo']['name'])){
			$fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
		
			if(!in_array($fileext,$allowedfiletypes)){ echo "<B class='text-danger'>Invalid Extension</B>"; }
			else{
				unlink($user_profile_folder.$image);
				$fulluploadfilename = $uploadfolder.$unique_name.".".$fileext;
				
				if (copy($_FILES['photo']['tmp_name'], $fulluploadfilename)){ 
					$photo_name = $unique_name.".".$fileext; 
				}
			}
		}
		else{
			$photo_name = $image;
		}
		$after_update = $name."<br />".$phone_no."<br />".$email."<br />".$benf_name."<br />".$bank_ac."<br />".$bank_name."<br />".$ifsc_code."<br />".$branch;
		/*$sql_update = "UPDATE users SET f_name = '$f_name' , l_name = '$l_name' ,phone_no = '$phone_no' , 
		ac_no = '$ac_no' , dob = '$dob' ,email = '$email', address = '$address'  , state = '$state'  , 
		city = '$city', district = '$district',beneficiery_name = '$beneficiery_name' , bank_ac = '$bank_ac' , 
		bank_name = '$bank_name', ifsc_code = '$ifsc_code', swift_code = '$swift_code', pan_no = '$pan_no'
		WHERE id_user = '$id'";
		
		$SQL = "INSERT INTO profile_record (member_id, admin_id, f_name, l_name, phone_no, email, 
		password, beneficiery_name, bank_ac, bank_name, ifsc_code, swift_code,`date`)
		SELECT id_user, 1000, f_name, l_name, phone_no, email, password, beneficiery_name, bank_ac, bank_name, 
		ifsc_code, swift_code,`date` from users WHERE id_user = '$id'";
		query_execute_sqli($SQL);*/
		
		$SQL = "INSERT INTO profile_record (member_id, admin_id, f_name, l_name, phone_no, email, 
		password, beneficiery_name, bank_ac, bank_name, ifsc_code, swift_code,`date`)
		SELECT id_user, 1000, f_name, l_name, phone_no, email, password, beneficiery_name, bank_ac, bank_name, 
		ifsc_code, swift_code,`date` from users WHERE id_user = '$id'";
		query_execute_sqli($SQL);
		$insert_id = get_mysqli_insert_id();
		
		$sqlk = "UPDATE profile_record SET before_update = '$before_update', after_update = '$after_update', 
		remarks = 'Updated by User Self' WHERE id = '$insert_id'";
		query_execute_sqli($sqlk);
		
	 	$sql = "UPDATE users SET title_name = '$title_name', f_name = '$f_name', l_name = '$l_name', 
		gender = '$gender', country = '$country' , state = '$state' , 
		city = '$city', pincode = '$pincode', phone_no = '$phone_no', email = '$email'$power_leg , fb_id = '$fb_id',
		whatsapp = '$whatsapp', skype_id = '$skype_id', nominee = '$nominee', relation = '$relation', 
		bank_name = '$bank_name', bank_country = '$bank_country', bank_ac = '$bank_ac', swift_code = '$swift_code',
		ifsc_code = '$ifsc_code', bank_info = '$bank_info' WHERE id_user = '$id'";
		query_execute_sqli($sql);
		
		$date = date('Y-m-d');
		$username = get_user_name($id);
		$updated_by = $username." Your self";
		include("function/logs_messages.php");
		data_logs($id,$data_log[1][0],$data_log[1][1],$log_type[1]);
		
		unset($_SESSION['rand_code']);
		
		$_SESSION['edit_succ'] = "<B class='text-success'>Profile Successfully Updated</B>";
		?> <script>window.location="index.php?page=edit-profile";</script> <?php
	//}
	//else{ echo "<B class='text-danger'>Please Enter Correct OTP Code ! </B>"; }
}

else
{
	$query = query_execute_sqli("select * from users where id_user = '$id' ");
	while($row = mysqli_fetch_array($query))
	{	
		$username = $row['username'];
		$password =$row['password'];
		$power_leg =$row['power_leg'] == NULL ? $row['power_leg'] : $row['power_leg']+1;
		$title_name =$row['title_name'];
		$f_name =$row['f_name'];
		$l_name =$row['l_name'];
		//$name = ucwords($row['f_name']." ".$row['l_name']);
		//$father_husband = $row['father_husband'];
		
		$dob = $row['dob'];
		$gender = $row['gender'];
		$city = $row['city'];
		$address = $row['address'];
		$email = $row['email'];
		$phone_no = $row['phone_no'];
		$country = $row['country'];
		$state = $row['state'];
		$pincode =$row['pincode'];

		$nominee = $row['nominee'];
		$relation = $row['relation'];
		$nominee_dob = date('d/m/Y', strtotime($row['nominee_dob']));
		$nominee_cntno = $row['nominee_cntno'];
		
		$ac_no = $row['ac_no'];
		$pan_no = $row['pan_no'];
		
		$beneficiery_name = $row['beneficiery_name'];
		$bank_ac = $row['bank_ac'];
		$bank_name = $row['bank_name'];
		$ifsc_code = $row['ifsc_code'];
		$bank_country = $row['bank_country'];
		$bank_info = $row['bank_info'];
		$swift_code = $row['swift_code'];
		
		$fb_id = $row['fb_id'];
		$whatsapp = $row['whatsapp'];
		$skype_id = $row['skype_id'];
	

		$readonly = "";	
		/*if(get_paid_member($login_id) > 0){
			$readonly = "readonly";
		}*/
		
		
		$image = $row['image'];
		
		if($image == ''){ $profil_photo = "user.png";}
		else{ $profil_photo = $image; }
		
		//if(!empty($beneficiery_name)){ $read_bnf = "readonly"; }
		if(!empty($bank_ac)){ $read_bank = "readonly"; }
		//if(!empty($bank_name)){ $read_bnam = "readonly"; }
		//if(!empty($ifsc_code)){ $read_ifsc = "readonly"; }
		//if(!empty($swift_code)){ $read_swft = "readonly"; }
		
		
		//$before_update = $name."<br />".$phone_no."<br />".$email."<br />".$beneficiery_name."<br />".$bank_ac."<br />".$bank_name."<br />".$ifsc_code."<br />".$branch;
	}

	?>
	<form name="money" action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="before_update" value="<?=$before_update?>" />
	
	<div class="col-md-12"><h2>My Details</h2></div>
	<div class="col-md-4">User ID</div>	    <div class="col-md-8"><input type="text" value="<?=$username?>" class="form-control" readonly="" /></div>
	<div class="col-md-12">&nbsp;</div>
	<div class="col-md-4">First Name</div>	<div class="col-md-8"><input type="text" name="f_name" value="<?=$f_name?>" class="form-control" <?=$readonly?>  /></div>
	<div class="col-md-12">&nbsp;</div>
	<div class="col-md-4">Last Name</div>	<div class="col-md-8"><input type="text" name="l_name" value="<?=$l_name?>" class="form-control" <?=$readonly?>  /></div>
	<div class="col-md-12">&nbsp;</div>
        <div class="col-md-4">Gender </div>
        <div class="col-md-2 text-left"><input type="radio" name="gender" value="male" <?php if($gender == 'male') { ?>  checked="checked" <?php } ?> /> &nbsp;Male</div>
        <div class="col-md-4 text-left"><input type="radio" name="gender" value="female" <?php if($gender == 'female') { ?>  checked="checked" <?php } ?>  /> &nbsp;Female</div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-4">Country</div> <div class="col-md-8">
        <select name="country" class="form-control">
			<option value="">Select One</option>
			<?php
			$list = count($country_list);
			for($cl = 0; $cl < $list; $cl++)
			{ ?>
			<option value="<?=$country_list[$cl]?>" <?php if($country_list[$cl] == $country) { ?> selected="selected" <?php } ?>><?=$country_list[$cl];?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-md-12">&nbsp;</div>
	<div class="col-md-4">State</div>	<div class="col-md-8"><input type="text" name="state" value="<?=$state?>" class="form-control" /></div>
	<div class="col-md-12">&nbsp;</div>
	<div class="col-md-4">City</div>	<div class="col-md-8"><input type="text" name="city" value="<?=$city?>" class="form-control" /></div>
	<div class="col-md-12">&nbsp;</div>
	<div class="col-md-4">Photo</div>	<div class="col-md-4"><img src="images/mlm_profile_image/<?=$profil_photo?>" width="50" /></div>
	<div class="col-md-4 pull-right">
		<a href="#dialog-approve-chng_photo" data-toggle="modal" class="btn btn-success btn-sm">
			Change Picture
		</a>
	</div>
		
    <div class="col-md-12"><h2>Contact Details</h2></div>
		<div class="col-md-4">Mobile No.</div><div class="col-md-8"><input type="text" name="phone_no" value="<?=$phone_no?>" class="form-control" <?=$readonly?> onKeyUp="if (/[^\'0-9]/g.test(this.value)) this.value = this.value.replace(/[^\'0-9]/g,'')" /></div>
		    <div class="col-md-12">&nbsp;</div>
		<div class="col-md-4">E-mail ID</div><div class="col-md-8"><input type="text" name="email" value="<?=$email?>" class="form-control" <?=$readonly?> pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" /></div>
		    <div class="col-md-12">&nbsp;</div>
        <div class="text-center"><input type="submit" name="update" value="Update" class="btn btn-primary" /></div>
	</form>	
	<?php
} ?>

<div class="modal fade" id="dialog-approve-chng_photo">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title" id="hierarchy_title">Change Profile Photo</h3>
			</div>
			<div class="modal-footer">
				<form method="post" action="index.php?page=edit-profile" enctype="multipart/form-data">
				<table class="table table-bordered table-hover">
					<tr>
						<th>Profile Picture</th>
						<td class="text-left">
							<img src="images/mlm_profile_image/<?=$profil_photo?>" width="80" />
						</td>
					</tr>
					<tr>
						<th>Change Picture</th>
						<td>
							<input class="form-control" type="file" name="photo" required />
							<div class="pull-left">Image Size 200 X 200</div>
						</td>
					</tr>
				</table>
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
				<input class="btn btn-primary btn-sm" value="Change" type="submit" name="change_pic">
				</form>
			</div>
		</div>
	</div>
</div>	
