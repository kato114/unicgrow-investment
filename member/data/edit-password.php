<?php
include('../security_web_validation.php');

include("condition.php");
include("function/setting.php");
include("function/send_mail.php");


$login_id = $_SESSION['mlmproject_user_id'];

if(isset($_SESSION['msgs_sucs_pass'])){
	echo $_SESSION['msgs_sucs_pass'];
	unset($_SESSION['msgs_sucs_pass']);
}
if(isset($_REQUEST['change_password'])){
	$old_password = $_REQUEST['old_password'];
	$new_password = $_REQUEST['new_password'];
	$con_new_password = $_REQUEST['con_new_password'];

    $pass_num = 0;
	$sql = "SELECT password FROM users WHERE id_user ='$login_id' AND password = '$old_password' ";
	$sec_pass = mysqli_fetch_array(query_execute_sqli($sql))[0];
	if(trim($old_password) == trim($sec_pass)){ $pass_num = 1; } 
	if($pass_num > 0){
	    if($new_password == $con_new_password){
    		$sql = "UPDATE users SET password = '$new_password' WHERE id_user = '$login_id'";
    		$insert_q = query_execute_sqli($sql);
    		
    		$username = get_user_name($login_id);
    		if(strtoupper($soft_chk) == "LIVE"){
    			include "email_letter/edit_password.php";
    			$to = get_user_email($login_id);
    			 // Always set content-type when sending HTML email
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                // More headers
                $headers .= "From: <$from_email>" . "\r\n";
    
                mail($to,$title,$db_msg,$headers);
    		}
    		
    		$_SESSION['msgs_sucs_pass'] =  "<div class='col-md-12 p-2 text-success'>Password Updated Successfully</div>";
    		?> <script type="text/javascript">window.location = "index.php?page=edit-password";</script> <?php
    		
    		unset($_SESSION['random_pass']);
	    }else { echo "<div class='col-md-12 p-2 text-danger'>Please Enter same Password in New and Confirm password both Field !</div>"; }
	}else{ echo "<div class='col-md-12 p-2 text-danger'>Please Enter Correct Old Password ! </div>"; }
}


$sql = "SELECT * FROM users WHERE id_user = '$login_id'";
$query = query_execute_sqli($sql);
while($row = mysqli_fetch_array($query))
{
	$user_pin = $row['user_pin'];
	$password  = $row['password'];
}
?>
<form name="change_pass" action="" method="post">
    <div class="col-md-4">Old Password</div>
        <div class="col-md-8"><input type="password" name="old_password" value="" class="form-control" /></div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-4">New Password</div>
        <div class="col-md-8"><input type="password" name="new_password" class="form-control" minlength="6" required /></div>
    <div class="col-md-12">&nbsp;</div>
    <div class="col-md-4">Confirm Password</div>
        <div class="col-md-8"><input type="password" name="con_new_password" class="form-control" minlength="6" required /></div>
    <div class="col-md-12">&nbsp;</div>
        <div class="col-md-12 text-center"><input type="submit" name="change_password" value="Update" class="btn btn-info"/></div>
    <div class="col-md-12">&nbsp;</div>
</form>
</div> 
 